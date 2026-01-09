<?php

namespace Database\Seeders;

use App\Models\Template\TemplateFieldType;
use Illuminate\Database\Seeder;

class TemplateFieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fieldTypes = [
            [
                'name' => 'Text Input',
                'code' => 'text',
                'component_name' => 'TextField',
                'validation_schema' => [
                    'supports' => ['required', 'min', 'max', 'pattern', 'email', 'url'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Number Input',
                'code' => 'number',
                'component_name' => 'NumberField',
                'validation_schema' => [
                    'supports' => ['required', 'min', 'max', 'step'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Textarea',
                'code' => 'textarea',
                'component_name' => 'TextareaField',
                'validation_schema' => [
                    'supports' => ['required', 'min', 'max'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Date Picker',
                'code' => 'date',
                'component_name' => 'DateField',
                'validation_schema' => [
                    'supports' => ['required', 'min_date', 'max_date'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Date Time Picker',
                'code' => 'datetime',
                'component_name' => 'DateTimeField',
                'validation_schema' => [
                    'supports' => ['required', 'min_datetime', 'max_datetime'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Dropdown Select',
                'code' => 'dropdown',
                'component_name' => 'DropdownField',
                'validation_schema' => [
                    'supports' => ['required'],
                    'requires' => ['options'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Radio Buttons',
                'code' => 'radio',
                'component_name' => 'RadioField',
                'validation_schema' => [
                    'supports' => ['required'],
                    'requires' => ['options'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Checkbox',
                'code' => 'checkbox',
                'component_name' => 'CheckboxField',
                'validation_schema' => [
                    'supports' => ['required'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Multi Select',
                'code' => 'multiselect',
                'component_name' => 'MultiSelectField',
                'validation_schema' => [
                    'supports' => ['required', 'min_items', 'max_items'],
                    'requires' => ['options'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'File Upload',
                'code' => 'file',
                'component_name' => 'FileUploadField',
                'validation_schema' => [
                    'supports' => ['required', 'max_size', 'allowed_types'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Image Upload',
                'code' => 'image',
                'component_name' => 'ImageUploadField',
                'validation_schema' => [
                    'supports' => ['required', 'max_size', 'max_width', 'max_height'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Calculated Field',
                'code' => 'calculated',
                'component_name' => 'CalculatedField',
                'validation_schema' => [
                    'requires' => ['formula'],
                ],
                'is_active' => true,
            ],
            // NEW: Inspection Form Field Types
            [
                'name' => 'Section Header',
                'code' => 'section_header',
                'component_name' => 'SectionHeaderField',
                'validation_schema' => [
                    'supports' => [],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Inspection Grid',
                'code' => 'inspection_grid',
                'component_name' => 'InspectionGridField',
                'validation_schema' => [
                    'supports' => ['required'],
                    'requires' => ['options'], // columns and items config
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Checkbox Grid',
                'code' => 'checkbox_grid',
                'component_name' => 'CheckboxGridField',
                'validation_schema' => [
                    'supports' => ['required'],
                    'requires' => ['options'], // grid options config
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Signature',
                'code' => 'signature',
                'component_name' => 'SignatureField',
                'validation_schema' => [
                    'supports' => ['required'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Image Gallery',
                'code' => 'image_gallery',
                'component_name' => 'ImageGalleryField',
                'validation_schema' => [
                    'supports' => ['required', 'max_items', 'max_size'],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($fieldTypes as $fieldType) {
            TemplateFieldType::updateOrCreate(
                ['code' => $fieldType['code']],
                $fieldType
            );
        }

        $this->command->info('Template field types seeded successfully.');
    }
}
