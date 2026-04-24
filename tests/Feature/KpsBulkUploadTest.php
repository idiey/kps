<?php

use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['kps.manage_sites', 'kps.manage_potongan', 'kps.view'] as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->site = Site::factory()->create([
        'hutang_weightage_pct' => 0,
    ]);

    $this->admin = User::factory()->create();
    $this->admin->givePermissionTo('kps.manage_sites');
});

function createBulkWorkbook(array $rows): string
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = [
        'Bil',
        'NAMA PENEROKA',
        'No. IC',
        'Gaji',
    ];

    $sheet->fromArray($headers, null, 'A1');
    $sheet->fromArray($rows, null, 'A2');

    $path = tempnam(sys_get_temp_dir(), 'kps-bulk-');
    $writer = new Xlsx($spreadsheet);
    $writer->save($path);

    return $path;
}

test('admin can download excel template with current site data', function () {
    $peneroka = Peneroka::factory()->create([
        'site_id' => $this->site->id,
        'name' => 'Ali Bakar',
        'ic_number' => '900101101111',
        'phone' => '0123456789',
        'address' => 'Kg Durian',
    ]);

    Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'description' => 'Baja',
        'balance' => 120,
        'original_amount' => 120,
        'priority' => 1,
    ]);

    Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'description' => 'Traktor',
        'balance' => 80,
        'original_amount' => 80,
        'priority' => 2,
    ]);

    MonthlyDeduction::factory()->create([
        'site_id' => $this->site->id,
        'peneroka_id' => $peneroka->id,
        'month' => '2026-04-01',
        'amount' => 150,
        'unallocated_amount' => 150,
    ]);

    $response = $this->actingAs($this->admin)
        ->get("/kps/sites/{$this->site->id}/potongan/bulk/template?month=2026-04");

    $response->assertOk();
    $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    $downloadPath = $response->baseResponse->getFile()->getPathname();
    $spreadsheet = IOFactory::load($downloadPath);
    $sheet = $spreadsheet->getActiveSheet();

    expect($sheet->getCell('A1')->getValue())->toBe('Bil');
    expect($sheet->getCell('B1')->getValue())->toBe('NAMA PENEROKA');
    expect($sheet->getCell('C1')->getValue())->toBe('No. IC');
    expect($sheet->getCell('D1')->getValue())->toBe('Gaji');

    expect((float) $sheet->getCell('A2')->getCalculatedValue())->toBe(1.0);
    expect($sheet->getCell('B2')->getValue())->toBe('Ali Bakar');
    expect((string) $sheet->getCell('C2')->getValue())->toBe('900101101111');
    expect((string) $sheet->getCell('D2')->getValue())->toBe('');
});

test('admin can upload excel and bulk upsert site data', function () {
    $existing = Peneroka::factory()->create([
        'site_id' => $this->site->id,
        'name' => 'Siti Lama',
        'ic_number' => '880202102222',
        'phone' => '0110000000',
    ]);

    $xlsxPath = createBulkWorkbook([
        [1, 'Siti Baru', '880202102222', 90],
        [2, 'Ahmad', '850303103333', 40],
    ]);

    $uploaded = new UploadedFile(
        $xlsxPath,
        'site-bulk.xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        null,
        true
    );

    $this->actingAs($this->admin)
        ->post("/kps/sites/{$this->site->id}/potongan/bulk/upload", [
            'month' => '2026-04',
            'file' => $uploaded,
        ])
        ->assertRedirect("/kps/sites/{$this->site->id}/potongan");

    $existing->refresh();

    expect($existing->name)->toBe('Siti Baru');

    $newPeneroka = Peneroka::query()
        ->where('site_id', $this->site->id)
        ->where('ic_number', '850303103333')
        ->first();

    expect($newPeneroka)->not->toBeNull();

    expect(Debt::query()->where('peneroka_id', $existing->id)->count())->toBe(0);

    expect((float) (
        MonthlyDeduction::query()
            ->where('site_id', $this->site->id)
            ->where('peneroka_id', $existing->id)
            ->whereDate('month', '2026-04-01')
            ->value('amount')
    ))->toBe(90.0);

    expect((float) (
        MonthlyDeduction::query()
            ->where('site_id', $this->site->id)
            ->where('peneroka_id', $newPeneroka->id)
            ->whereDate('month', '2026-04-01')
            ->value('amount')
    ))->toBe(40.0);
});

test('non admin cannot download template or upload bulk excel', function () {
    $staff = User::factory()->create();
    $staff->givePermissionTo(['kps.view', 'kps.manage_potongan']);
    $this->site->assignUser($staff->id, 'staff');

    $this->actingAs($staff)
        ->get("/kps/sites/{$this->site->id}/potongan/bulk/template")
        ->assertForbidden();

    $xlsxPath = createBulkWorkbook([
        [1, 'Ali', '900101101111', 10],
    ]);

    $uploaded = new UploadedFile(
        $xlsxPath,
        'site-bulk.xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        null,
        true
    );

    $this->actingAs($staff)
        ->post("/kps/sites/{$this->site->id}/potongan/bulk/upload", [
            'month' => '2026-04',
            'file' => $uploaded,
        ])
        ->assertForbidden();
});
