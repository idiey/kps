<?php

namespace Database\Seeders;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use Illuminate\Database\Seeder;

class KewPA10TemplateSeeder extends Seeder
{
    /**
     * Create KEW.PA-10 form template with all required fields.
     * This template can be attached to workflow steps that require
     * KEW.PA-10 form data collection.
     */
    public function run(): void
    {
        // Create KEW.PA-10 template
        $template = JobTemplate::firstOrCreate(
            ['code' => 'kew-pa-10'],
            [
                'name' => 'KEW.PA-10 Form',
                'description' => 'Borang KEW.PA-10 - Laporan Pelupusan/Pembaikan Aset Kerajaan',
                'icon' => 'document-text',
                'color' => 'blue',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        // Get or create field types
        $textType = TemplateFieldType::firstOrCreate(
            ['code' => 'text'],
            [
                'name' => 'Text',
                'component_name' => 'BaseInput',
                'is_active' => true,
            ]
        );

        $textareaType = TemplateFieldType::firstOrCreate(
            ['code' => 'textarea'],
            [
                'name' => 'Textarea',
                'component_name' => 'BaseTextarea',
                'is_active' => true,
            ]
        );

        $selectType = TemplateFieldType::firstOrCreate(
            ['code' => 'select'],
            [
                'name' => 'Select',
                'component_name' => 'BaseSelect',
                'is_active' => true,
            ]
        );

        $dateType = TemplateFieldType::firstOrCreate(
            ['code' => 'date'],
            [
                'name' => 'Date',
                'component_name' => 'BaseDatePicker',
                'is_active' => true,
            ]
        );

        $fileType = TemplateFieldType::firstOrCreate(
            ['code' => 'file'],
            [
                'name' => 'File Upload',
                'component_name' => 'BaseFileUpload',
                'is_active' => true,
            ]
        );

        $checkboxType = TemplateFieldType::firstOrCreate(
            ['code' => 'checkbox'],
            [
                'name' => 'Checkbox',
                'component_name' => 'BaseCheckbox',
                'is_active' => true,
            ]
        );

        // Define KEW.PA-10 fields
        $fields = [
            // Section: Form Information
            [
                'field_type_id' => $textType->id,
                'name' => 'Nombor KEW.PA-10',
                'code' => 'kew_pa_10_number',
                'placeholder' => 'Contoh: KEW.PA-10/2026/001',
                'section' => 'Maklumat Borang',
                'display_order' => 1,
                'grid_column_span' => 6,
                'is_required' => true,
            ],
            [
                'field_type_id' => $dateType->id,
                'name' => 'Tarikh Terima',
                'code' => 'received_date',
                'section' => 'Maklumat Borang',
                'display_order' => 2,
                'grid_column_span' => 6,
                'is_required' => true,
            ],
            [
                'field_type_id' => $selectType->id,
                'name' => 'Jabatan Kerajaan',
                'code' => 'government_department_id',
                'section' => 'Maklumat Borang',
                'display_order' => 3,
                'grid_column_span' => 6,
                'is_required' => true,
                'options_source' => 'database',
                'options_query' => 'government_departments:id,name',
            ],
            [
                'field_type_id' => $selectType->id,
                'name' => 'Aset',
                'code' => 'asset_id',
                'section' => 'Maklumat Borang',
                'display_order' => 4,
                'grid_column_span' => 6,
                'is_required' => true,
                'options_source' => 'database',
                'options_query' => 'assets:id,asset_name',
            ],

            // Section: Request Details
            [
                'field_type_id' => $textareaType->id,
                'name' => 'Perihal Pembaikan',
                'code' => 'description',
                'placeholder' => 'Nyatakan butiran pembaikan yang diperlukan...',
                'section' => 'Butiran Permohonan',
                'display_order' => 5,
                'grid_column_span' => 12,
                'is_required' => true,
            ],
            [
                'field_type_id' => $selectType->id,
                'name' => 'Keutamaan',
                'code' => 'priority',
                'section' => 'Butiran Permohonan',
                'display_order' => 6,
                'grid_column_span' => 6,
                'is_required' => true,
                'options' => [
                    ['value' => 'low', 'label' => 'Rendah'],
                    ['value' => 'medium', 'label' => 'Sederhana'],
                    ['value' => 'high', 'label' => 'Tinggi'],
                    ['value' => 'urgent', 'label' => 'Segera'],
                ],
            ],
            [
                'field_type_id' => $textType->id,
                'name' => 'Rujukan Peruntukan Bajet',
                'code' => 'budget_allocation_reference',
                'placeholder' => 'Contoh: VOT 13000',
                'section' => 'Butiran Permohonan',
                'display_order' => 7,
                'grid_column_span' => 6,
                'is_required' => false,
            ],

            // Section: Document Upload
            [
                'field_type_id' => $fileType->id,
                'name' => 'Dokumen KEW.PA-10',
                'code' => 'kew_pa_10_document',
                'help_text' => 'Muat naik salinan borang KEW.PA-10 (PDF)',
                'section' => 'Dokumen',
                'display_order' => 8,
                'grid_column_span' => 12,
                'is_required' => false,
            ],

            // Section: Verification
            [
                'field_type_id' => $checkboxType->id,
                'name' => 'Borang Lengkap Disahkan',
                'code' => 'form_completeness_verified',
                'help_text' => 'Sahkan bahawa borang telah diisi dengan lengkap',
                'section' => 'Pengesahan',
                'display_order' => 9,
                'grid_column_span' => 6,
                'is_required' => false,
            ],
            [
                'field_type_id' => $checkboxType->id,
                'name' => 'Tandatangan Disahkan',
                'code' => 'signatures_verified',
                'help_text' => 'Sahkan bahawa semua tandatangan adalah sah',
                'section' => 'Pengesahan',
                'display_order' => 10,
                'grid_column_span' => 6,
                'is_required' => false,
            ],
        ];

        // Create/update fields
        foreach ($fields as $fieldData) {
            TemplateField::updateOrCreate(
                [
                    'template_id' => $template->id,
                    'code' => $fieldData['code'],
                ],
                array_merge($fieldData, ['template_id' => $template->id])
            );
        }

        $this->command->info('KEW.PA-10 form template created with ' . count($fields) . ' fields.');
    }
}
