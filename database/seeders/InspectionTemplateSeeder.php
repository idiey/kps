<?php

namespace Database\Seeders;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use Illuminate\Database\Seeder;

class InspectionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get field types
        $text = TemplateFieldType::where('code', 'text')->first();
        $select = TemplateFieldType::where('code', 'dropdown')->first();
        $textarea = TemplateFieldType::where('code', 'textarea')->first();
        $sectionHeader = TemplateFieldType::where('code', 'section_header')->first();
        $inspectionGrid = TemplateFieldType::where('code', 'inspection_grid')->first();
        $imageGallery = TemplateFieldType::where('code', 'image_gallery')->first();
        $signature = TemplateFieldType::where('code', 'signature')->first();

        // =====================
        // TRAILER INSPECTION
        // =====================
        $trailerTemplate = JobTemplate::updateOrCreate(
            ['code' => 'pemeriksaan_trailer'],
            [
                'name' => 'Laporan Pemeriksaan - Trailer',
                'description' => 'Borang pemeriksaan untuk kenderaan trailer',
                'is_active' => true,
            ]
        );

        $this->createTrailerFields($trailerTemplate, $text, $select, $textarea, $sectionHeader, $inspectionGrid, $imageGallery, $signature);

        // =====================
        // BOT (BOAT) INSPECTION
        // =====================
        $botTemplate = JobTemplate::updateOrCreate(
            ['code' => 'pemeriksaan_bot'],
            [
                'name' => 'Laporan Pemeriksaan - Bot',
                'description' => 'Borang pemeriksaan untuk bot/perahu',
                'is_active' => true,
            ]
        );

        $this->createBotFields($botTemplate, $text, $select, $textarea, $sectionHeader, $inspectionGrid, $imageGallery, $signature);

        // =====================
        // ENGINE INSPECTION
        // =====================
        $engineTemplate = JobTemplate::updateOrCreate(
            ['code' => 'pemeriksaan_enjin'],
            [
                'name' => 'Laporan Pemeriksaan - Engine',
                'description' => 'Borang pemeriksaan untuk enjin',
                'is_active' => true,
            ]
        );

        $this->createEngineFields($engineTemplate, $text, $select, $textarea, $sectionHeader, $inspectionGrid, $imageGallery, $signature);

        $this->command->info('Inspection templates seeded successfully.');
    }

    protected function createTrailerFields($template, $text, $select, $textarea, $sectionHeader, $inspectionGrid, $imageGallery, $signature)
    {
        $order = 0;

        // Header section
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'section_info'],
            [
                'field_type_id' => $sectionHeader->id,
                'name' => 'Maklumat Pemeriksa',
                'display_order' => ++$order,
            ]
        );

        // Basic info fields
        $basicFields = [
            ['code' => 'nama_pemeriksa', 'name' => 'Nama Pemeriksa'],
            ['code' => 'jenis_pemeriksaan', 'name' => 'Jenis Pemeriksaan'],
            ['code' => 'jenama', 'name' => 'Jenama'],
            ['code' => 'no_siri_pendaftaran', 'name' => 'No. Siri Pendaftaran'],
            ['code' => 'no_casis', 'name' => 'No. Casis'],
        ];

        foreach ($basicFields as $field) {
            TemplateField::updateOrCreate(
                ['template_id' => $template->id, 'code' => $field['code']],
                [
                    'field_type_id' => $text->id,
                    'name' => $field['name'],
                    'is_required' => true,
                    'display_order' => ++$order,
                    'grid_column_span' => 6,
                ]
            );
        }

        // Dropdown fields
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'zon'],
            [
                'field_type_id' => $select->id,
                'name' => 'Zon',
                'display_order' => ++$order,
                'grid_column_span' => 4,
                'options' => json_encode([
                    ['label' => 'Zon Utara', 'value' => 'utara'],
                    ['label' => 'Zon Selatan', 'value' => 'selatan'],
                    ['label' => 'Zon Timur', 'value' => 'timur'],
                    ['label' => 'Zon Barat', 'value' => 'barat'],
                ]),
            ]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'ptj_negeri'],
            [
                'field_type_id' => $select->id,
                'name' => 'PTJ/Negeri',
                'display_order' => ++$order,
                'grid_column_span' => 4,
                'options' => json_encode([
                    ['label' => 'Johor', 'value' => 'johor'],
                    ['label' => 'Kedah', 'value' => 'kedah'],
                    ['label' => 'Kelantan', 'value' => 'kelantan'],
                    ['label' => 'Melaka', 'value' => 'melaka'],
                    ['label' => 'Negeri Sembilan', 'value' => 'negeri_sembilan'],
                    ['label' => 'Pahang', 'value' => 'pahang'],
                    ['label' => 'Perak', 'value' => 'perak'],
                    ['label' => 'Perlis', 'value' => 'perlis'],
                    ['label' => 'Pulau Pinang', 'value' => 'pulau_pinang'],
                    ['label' => 'Sabah', 'value' => 'sabah'],
                    ['label' => 'Sarawak', 'value' => 'sarawak'],
                    ['label' => 'Selangor', 'value' => 'selangor'],
                    ['label' => 'Terengganu', 'value' => 'terengganu'],
                ]),
            ]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'penempatan_aset'],
            [
                'field_type_id' => $text->id,
                'name' => 'Penempatan Aset/Daerah',
                'display_order' => ++$order,
                'grid_column_span' => 4,
            ]
        );

        // Inspection Grid Section
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'section_checklist'],
            [
                'field_type_id' => $sectionHeader->id,
                'name' => 'Senarai Semak Pemeriksaan',
                'display_order' => ++$order,
            ]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'inspection_items'],
            [
                'field_type_id' => $inspectionGrid->id,
                'name' => 'Item Pemeriksaan Trailer',
                'display_order' => ++$order,
                'options' => json_encode([
                    'columns' => ['OK', 'Perlu Perhatian', 'Perhatian Segera'],
                    'has_notes' => true,
                    'items' => [
                        'Tongue (Hook)',
                        'Safety Stand',
                        'Winch',
                        'Roller',
                        'Parking Brake',
                        'Coil/Leaf Spring',
                        'Rim/Tyres/Bearing',
                        'Axle',
                        'Fender',
                        'Lighting System',
                        'Structure/Frame',
                    ],
                ]),
            ]
        );

        // Images section
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'section_gambar'],
            [
                'field_type_id' => $sectionHeader->id,
                'name' => 'Catatan Gambar',
                'display_order' => ++$order,
            ]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'catatan_gambar'],
            [
                'field_type_id' => $imageGallery->id,
                'name' => 'Muat Naik Gambar',
                'display_order' => ++$order,
                'metadata' => json_encode(['max_items' => 4]),
            ]
        );

        // Notes
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'catatan_pemeriksaan'],
            [
                'field_type_id' => $textarea->id,
                'name' => 'Catatan Pemeriksaan',
                'display_order' => ++$order,
            ]
        );

        // Signature
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'tandatangan_pemeriksa'],
            [
                'field_type_id' => $signature->id,
                'name' => 'Tandatangan Pemeriksa (E-Sign)',
                'display_order' => ++$order,
                'grid_column_span' => 6,
            ]
        );
    }

    protected function createBotFields($template, $text, $select, $textarea, $sectionHeader, $inspectionGrid, $imageGallery, $signature)
    {
        $order = 0;

        // Reuse basic header fields
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'section_info'],
            ['field_type_id' => $sectionHeader->id, 'name' => 'Maklumat Pemeriksa', 'display_order' => ++$order]
        );

        $basicFields = ['nama_pemeriksa', 'jenis_pemeriksaan', 'jenama', 'no_siri_pendaftaran', 'no_casis'];
        foreach ($basicFields as $code) {
            TemplateField::updateOrCreate(
                ['template_id' => $template->id, 'code' => $code],
                [
                    'field_type_id' => $text->id,
                    'name' => ucwords(str_replace('_', ' ', $code)),
                    'is_required' => true,
                    'display_order' => ++$order,
                    'grid_column_span' => 6,
                ]
            );
        }

        // Bot-specific inspection grid
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'inspection_items'],
            [
                'field_type_id' => $inspectionGrid->id,
                'name' => 'Item Pemeriksaan Bot',
                'display_order' => ++$order,
                'options' => json_encode([
                    'columns' => ['OK', 'Perlu Perhatian', 'Perhatian Segera'],
                    'has_notes' => true,
                    'items' => [
                        'Hitch Ball Coupler',
                        'Gunwale',
                        'Hull',
                        'Cleat',
                        'Keel',
                        'Body Paint',
                        'Drain Tap/Plug',
                        'Seat',
                        'Capacity Plate',
                    ],
                ]),
            ]
        );

        // Images and signature
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'catatan_gambar'],
            ['field_type_id' => $imageGallery->id, 'name' => 'Catatan Gambar', 'display_order' => ++$order, 'metadata' => json_encode(['max_items' => 7])]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'catatan_pemeriksaan'],
            ['field_type_id' => $textarea->id, 'name' => 'Catatan Pemeriksaan', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'tandatangan_pemeriksa'],
            ['field_type_id' => $signature->id, 'name' => 'Tandatangan Pemeriksa (E-Sign)', 'display_order' => ++$order, 'grid_column_span' => 6]
        );
    }

    protected function createEngineFields($template, $text, $select, $textarea, $sectionHeader, $inspectionGrid, $imageGallery, $signature)
    {
        $order = 0;

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'section_info'],
            ['field_type_id' => $sectionHeader->id, 'name' => 'Maklumat Pemeriksa', 'display_order' => ++$order]
        );

        $basicFields = ['nama_pemeriksa', 'jenis_pemeriksaan', 'jenama', 'no_siri_pendaftaran', 'no_casis'];
        foreach ($basicFields as $code) {
            TemplateField::updateOrCreate(
                ['template_id' => $template->id, 'code' => $code],
                [
                    'field_type_id' => $text->id,
                    'name' => ucwords(str_replace('_', ' ', $code)),
                    'is_required' => true,
                    'display_order' => ++$order,
                    'grid_column_span' => 6,
                ]
            );
        }

        // Engine-specific inspection grid
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'inspection_items'],
            [
                'field_type_id' => $inspectionGrid->id,
                'name' => 'Item Pemeriksaan Engine',
                'display_order' => ++$order,
                'options' => json_encode([
                    'columns' => ['OK', 'Perlu Perhatian', 'Perhatian Segera'],
                    'has_notes' => true,
                    'items' => [
                        'Engine Cover',
                        'Recoil Starter',
                        'Throttle Assembly',
                        'Forward/Reverse Gear Lever',
                        'Transom Clamping',
                        'Anti-Vortex Plate (Anode)',
                        'Propeller Assembly',
                        'Fuel Hose/Pipe',
                        'Emergency Kill Switch',
                        'Drain Cooling Hose',
                    ],
                ]),
            ]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'catatan_gambar'],
            ['field_type_id' => $imageGallery->id, 'name' => 'Catatan Gambar', 'display_order' => ++$order, 'metadata' => json_encode(['max_items' => 4])]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'catatan_pemeriksaan'],
            ['field_type_id' => $textarea->id, 'name' => 'Catatan Pemeriksaan', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'tandatangan_pemeriksa'],
            ['field_type_id' => $signature->id, 'name' => 'Tandatangan Pemeriksa (E-Sign)', 'display_order' => ++$order, 'grid_column_span' => 6]
        );
    }
}
