<?php

namespace Database\Seeders;

use App\Models\Kps\AuditLog;
use App\Models\Kps\Debt;
use App\Models\Kps\DeductionAllocation;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use App\Services\Kps\AllocationService;
use App\Services\Kps\MonthlyClosingService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KpsDemoFlowSeeder extends Seeder
{
    private const SITE_CODE = 'FELDA-DEMO';
    private const SITE_NAME = 'FELDA Demo Pembayaran';
    private const SITE_ADDRESS = 'Lot Demo KPS, 28000 Temerloh, Pahang';
    private const SITE_PHONE = '09-900 1000';
    private const SITE_EMAIL = 'demo-pembayaran@felda.gov.my';
    private const ADMIN_NAME = 'Demo Site Admin';
    private const ADMIN_EMAIL = 'demo-site-admin@felda.gov.my';
    private const ADMIN_PASSWORD = 'password';
    private const OPEN_MONTH = '2026-04-01';
    private const CLOSED_MONTH = '2026-03-01';
    private const HISTORY_MONTH = '2026-02-01';

    public function run(): void
    {
        $site = Site::withTrashed()->firstOrNew(['code' => self::SITE_CODE]);
        $site->fill([
            'name' => self::SITE_NAME,
            'address' => self::SITE_ADDRESS,
            'phone' => self::SITE_PHONE,
            'email' => self::SITE_EMAIL,
            'is_active' => true,
        ]);
        $site->deleted_at = null;
        $site->save();

        $admin = User::firstOrCreate(
            ['email' => self::ADMIN_EMAIL],
            [
                'name' => self::ADMIN_NAME,
                'password' => Hash::make(self::ADMIN_PASSWORD),
            ]
        );
        $admin->name = self::ADMIN_NAME;
        if (! Hash::check(self::ADMIN_PASSWORD, $admin->password)) {
            $admin->password = Hash::make(self::ADMIN_PASSWORD);
        }
        $admin->save();
        $admin->syncRoles(['site_admin']);
        $site->assignUser($admin->id, 'site_admin');

        $this->resetDemoSite($site);
        $this->seedDemoCases($site, $admin);
    }

    private function resetDemoSite(Site $site): void
    {
        $penerokaIds = Peneroka::withTrashed()
            ->where('site_id', $site->id)
            ->pluck('id');

        $deductionIds = MonthlyDeduction::query()
            ->where('site_id', $site->id)
            ->pluck('id');

        DeductionAllocation::query()
            ->whereIn('monthly_deduction_id', $deductionIds)
            ->delete();

        MonthlyDeduction::query()
            ->where('site_id', $site->id)
            ->delete();

        Debt::withTrashed()
            ->whereIn('peneroka_id', $penerokaIds)
            ->forceDelete();

        Peneroka::withTrashed()
            ->where('site_id', $site->id)
            ->forceDelete();

        AuditLog::query()
            ->where('site_id', $site->id)
            ->delete();
    }

    private function seedDemoCases(Site $site, User $admin): void
    {
        $allocationService = app(AllocationService::class);
        $closingService = app(MonthlyClosingService::class);

        $ahmad = $this->createPeneroka(
            $site,
            'Ahmad Demo Penuh',
            '700101-01-1001',
            '013-880 1201',
            'Blok A Demo Penuh'
        );
        $this->createDebt($ahmad, 1, 120.00, 'Hutang Baja Demo Penuh', '2026-04-10');
        $this->createDebt($ahmad, 2, 80.00, 'Hutang Racun Demo Penuh', '2026-04-20');
        $allocationService->allocate($this->createDeduction($site, $ahmad, self::OPEN_MONTH, 150.00));

        $badrul = $this->createPeneroka(
            $site,
            'Badrul Demo Baki',
            '700202-02-2002',
            '013-880 1202',
            'Blok B Demo Baki'
        );
        $this->createDebt($badrul, 1, 40.00, 'Hutang Input Demo Baki', '2026-04-09');
        $this->createDebt($badrul, 2, 30.00, 'Hutang Tunai Demo Baki', '2026-04-18');
        $allocationService->allocate($this->createDeduction($site, $badrul, self::OPEN_MONTH, 120.00));

        $cheSiti = $this->createPeneroka(
            $site,
            'Che Siti Demo Tutup',
            '700303-03-3003',
            '013-880 1203',
            'Blok C Demo Tutup'
        );
        $this->createDebt($cheSiti, 1, 90.00, 'Hutang Tutup Bulan', '2026-03-15');
        $allocationService->allocate($this->createDeduction($site, $cheSiti, self::CLOSED_MONTH, 90.00));

        $dahlia = $this->createPeneroka(
            $site,
            'Dahlia Demo Penyata',
            '700404-04-4004',
            '013-880 1204',
            'Blok D Demo Penyata'
        );
        $this->createDebt($dahlia, 1, 150.00, 'Hutang Penyata 1', '2026-02-18');
        $this->createDebt($dahlia, 2, 130.00, 'Hutang Penyata 2', '2026-03-20');
        $this->createDebt($dahlia, 3, 110.00, 'Hutang Penyata 3', '2026-04-22');
        $allocationService->allocate($this->createDeduction($site, $dahlia, self::HISTORY_MONTH, 60.00));
        $allocationService->allocate($this->createDeduction($site, $dahlia, self::CLOSED_MONTH, 80.00));
        $allocationService->allocate($this->createDeduction($site, $dahlia, self::OPEN_MONTH, 100.00));

        $closingService->closeMonth($site, Carbon::parse(self::HISTORY_MONTH), $admin);
        $closingService->closeMonth($site, Carbon::parse(self::CLOSED_MONTH), $admin);
    }

    private function createPeneroka(
        Site $site,
        string $name,
        string $icNumber,
        string $phone,
        string $address
    ): Peneroka {
        return Peneroka::create([
            'site_id' => $site->id,
            'name' => $name,
            'ic_number' => $icNumber,
            'phone' => $phone,
            'address' => $address,
        ]);
    }

    private function createDebt(
        Peneroka $peneroka,
        int $priority,
        float $amount,
        string $description,
        string $dueDate
    ): Debt {
        return Debt::create([
            'peneroka_id' => $peneroka->id,
            'priority' => $priority,
            'balance' => $amount,
            'original_amount' => $amount,
            'due_date' => $dueDate,
            'description' => $description,
        ]);
    }

    private function createDeduction(
        Site $site,
        Peneroka $peneroka,
        string $month,
        float $amount
    ): MonthlyDeduction {
        return MonthlyDeduction::create([
            'site_id' => $site->id,
            'peneroka_id' => $peneroka->id,
            'month' => $month,
            'amount' => $amount,
            'unallocated_amount' => 0,
            'is_closed' => false,
            'closed_at' => null,
        ]);
    }
}
