<?php

namespace Database\Seeders;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\Workflow\WorkflowTransition;
use Illuminate\Database\Seeder;

class SimpleKewPA10WorkflowSeeder extends Seeder
{
    /**
     * Creates a simple KEW.PA-10 workflow:
     * Received → Registered → Verified → Closed
     * 
     * KEW.PA-10 form is REQUIRED at the "Received" status
     */
    public function run(): void
    {
        // Get field types
        $text = TemplateFieldType::where('code', 'text')->first();
        $select = TemplateFieldType::where('code', 'dropdown')->first();
        $textarea = TemplateFieldType::where('code', 'textarea')->first();
        $date = TemplateFieldType::where('code', 'date')->first();
        $sectionHeader = TemplateFieldType::where('code', 'section_header')->first();
        $checkboxGrid = TemplateFieldType::where('code', 'checkbox_grid')->first();
        $signature = TemplateFieldType::where('code', 'signature')->first();

        // =====================
        // 1. Create KEW.PA-10 Template
        // =====================
        $template = JobTemplate::updateOrCreate(
            ['code' => 'kewpa10_simple'],
            [
                'name' => 'KEW.PA-10 (Simplified)',
                'description' => 'Borang Aduan Kerosakan Aset Alih - Versi Ringkas',
                'is_active' => true,
            ]
        );

        $this->createTemplateFields($template, $text, $select, $textarea, $date, $sectionHeader, $checkboxGrid, $signature);
        $this->command->info("Created template: {$template->name}");

        // =====================
        // 2. Create Simple Workflow
        // =====================
        $workflow = Workflow::updateOrCreate(
            ['code' => 'kewpa10_simple_flow'],
            [
                'name' => 'KEW.PA-10 Simple Flow',
                'description' => 'Simple 4-step workflow: Received → Registered → Verified → Closed',
                'is_active' => true,
            ]
        );
        $this->command->info("Created workflow: {$workflow->name}");

        // =====================
        // 3. Create Statuses
        // =====================
        $received = WorkflowStatus::updateOrCreate(
            ['workflow_id' => $workflow->id, 'code' => 'received'],
            [
                'name' => 'Received',
                'description' => 'Form received - user MUST fill KEW.PA-10 before proceeding',
                'color' => '#f59e0b', // Amber
                'display_order' => 1,
                'is_initial' => true,
                'is_final' => false,
                'required_template_id' => $template->id, // *** FORM REQUIRED HERE ***
            ]
        );
        $this->command->info("  → Status: Received (KEW.PA-10 form required)");

        $registered = WorkflowStatus::updateOrCreate(
            ['workflow_id' => $workflow->id, 'code' => 'registered'],
            [
                'name' => 'Registered',
                'description' => 'Form data has been entered into system',
                'color' => '#3b82f6', // Blue
                'display_order' => 2,
                'is_initial' => false,
                'is_final' => false,
            ]
        );

        $verified = WorkflowStatus::updateOrCreate(
            ['workflow_id' => $workflow->id, 'code' => 'verified'],
            [
                'name' => 'Verified',
                'description' => 'Data verified by supervisor',
                'color' => '#8b5cf6', // Purple
                'display_order' => 3,
                'is_initial' => false,
                'is_final' => false,
            ]
        );

        $closed = WorkflowStatus::updateOrCreate(
            ['workflow_id' => $workflow->id, 'code' => 'closed'],
            [
                'name' => 'Closed',
                'description' => 'Case completed',
                'color' => '#10b981', // Green
                'display_order' => 4,
                'is_initial' => false,
                'is_final' => true,
            ]
        );

        // =====================
        // 4. Create Transitions
        // =====================
        WorkflowTransition::updateOrCreate(
            ['workflow_id' => $workflow->id, 'from_status_id' => $received->id, 'to_status_id' => $registered->id],
            ['name' => 'Submit to Registration', 'is_active' => true]
        );

        WorkflowTransition::updateOrCreate(
            ['workflow_id' => $workflow->id, 'from_status_id' => $registered->id, 'to_status_id' => $verified->id],
            ['name' => 'Submit for Verification', 'is_active' => true]
        );

        WorkflowTransition::updateOrCreate(
            ['workflow_id' => $workflow->id, 'from_status_id' => $verified->id, 'to_status_id' => $closed->id],
            ['name' => 'Close Case', 'is_active' => true]
        );

        $this->command->info("Created 3 transitions");

        // Link template to workflow
        $template->workflows()->syncWithoutDetaching([$workflow->id => ['is_default' => true]]);

        $this->command->info("\n✓ Simple KEW.PA-10 workflow ready!");
        $this->command->info("  When job starts at 'Received' status, user must complete KEW.PA-10 form before proceeding.");
    }

    protected function createTemplateFields($template, $text, $select, $textarea, $date, $sectionHeader, $checkboxGrid, $signature)
    {
        $order = 0;

        // BAHAGIAN I
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'bahagian_1'],
            ['field_type_id' => $sectionHeader->id, 'name' => 'Bahagian I (Untuk diisi oleh Pengadu)', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'jenis_aset'],
            ['field_type_id' => $text->id, 'name' => '1. Jenis Aset', 'is_required' => true, 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'no_siri'],
            ['field_type_id' => $text->id, 'name' => '2. No. Siri Pendaftaran Aset', 'is_required' => true, 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'pengguna_terakhir'],
            ['field_type_id' => $text->id, 'name' => '3. Pengguna Terakhir', 'is_required' => true, 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'tarikh_kerosakan'],
            ['field_type_id' => $date->id, 'name' => '4. Tarikh Kerosakan', 'is_required' => true, 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'perihal_kerosakan'],
            ['field_type_id' => $textarea->id, 'name' => '5. Perihal Kerosakan', 'is_required' => true, 'display_order' => ++$order]
        );

        // Checkbox grid for damage category
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'jenis_kerosakan'],
            [
                'field_type_id' => $checkboxGrid->id,
                'name' => 'Jenis Kerosakan (Sila tandakan)',
                'display_order' => ++$order,
                'options' => json_encode([
                    'columns' => ['✓'],
                    'rows' => [
                        'SERVIS BERKALA', 'SISTEM ELEKTRIK', 'SISTEM PENGHAWA DINGIN',
                        'SISTEM ENJIN', 'SISTEM BREK', 'SISTEM HYDRAULIK',
                        'SISTEM GEAR', 'SISTEM STERENG', 'BADAN KENDERAAN',
                        'SUSPENSION/CLUTCH', 'BREAKDOWN', 'TAYAR',
                        'OBM', 'IBM (ENJIN BOT)', 'TRELER',
                    ],
                ]),
            ]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'catatan_lain'],
            ['field_type_id' => $text->id, 'name' => 'Lain-lain Kerosakan / Catatan', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'nama_pengadu'],
            ['field_type_id' => $text->id, 'name' => '6. Nama dan Jawatan', 'is_required' => true, 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'tarikh_lapor'],
            ['field_type_id' => $date->id, 'name' => '7. Tarikh', 'is_required' => true, 'display_order' => ++$order]
        );

        // BAHAGIAN II
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'bahagian_2'],
            ['field_type_id' => $sectionHeader->id, 'name' => 'Bahagian II (Pegawai Teknikal)', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'anggaran_kos'],
            ['field_type_id' => $text->id, 'name' => 'Anggaran Kos Penyelenggaraan', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'syor_ulasan'],
            ['field_type_id' => $textarea->id, 'name' => 'Syor dan Ulasan', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'tandatangan_teknikal'],
            ['field_type_id' => $signature->id, 'name' => 'Tandatangan Pegawai Teknikal', 'display_order' => ++$order, 'grid_column_span' => 6]
        );

        // BAHAGIAN III
        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'bahagian_3'],
            ['field_type_id' => $sectionHeader->id, 'name' => 'Bahagian III (Keputusan Ketua)', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'keputusan'],
            [
                'field_type_id' => $select->id,
                'name' => 'Keputusan',
                'display_order' => ++$order,
                'options' => json_encode([
                    ['label' => 'Diluluskan', 'value' => 'diluluskan'],
                    ['label' => 'Tidak Diluluskan', 'value' => 'tidak_diluluskan'],
                ]),
            ]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'ulasan_ketua'],
            ['field_type_id' => $textarea->id, 'name' => 'Ulasan', 'display_order' => ++$order]
        );

        TemplateField::updateOrCreate(
            ['template_id' => $template->id, 'code' => 'tandatangan_ketua'],
            ['field_type_id' => $signature->id, 'name' => 'Tandatangan Ketua', 'display_order' => ++$order, 'grid_column_span' => 6]
        );
    }
}
