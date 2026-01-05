<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dynamic Workflow Configuration
    |--------------------------------------------------------------------------
    |
    | This file controls the behavior of the dynamic workflow system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Use Dynamic Workflows
    |--------------------------------------------------------------------------
    |
    | Set to true to enable the dynamic workflow system. When false, the
    | system will fall back to legacy enum-based workflows.
    |
    */

    'use_dynamic_workflows' => env('USE_DYNAMIC_WORKFLOWS', false),

    /*
    |--------------------------------------------------------------------------
    | Migration Mode
    |--------------------------------------------------------------------------
    |
    | Controls how the system handles workflows during migration:
    | - 'legacy': Use only enum-based workflows (old system)
    | - 'dual': Support both enum and dynamic workflows (migration period)
    | - 'dynamic': Use only database-driven workflows (new system)
    |
    */

    'migration_mode' => env('WORKFLOW_MIGRATION_MODE', 'dual'),

    /*
    |--------------------------------------------------------------------------
    | Legacy Enum Support
    |--------------------------------------------------------------------------
    |
    | Keep enum-based workflow logic available for backward compatibility.
    | Set to false only after all jobs have been migrated to dynamic workflows.
    |
    */

    'legacy_enum_support' => env('LEGACY_ENUM_SUPPORT', true),

    /*
    |--------------------------------------------------------------------------
    | Workflow Cache
    |--------------------------------------------------------------------------
    |
    | Enable caching of workflow definitions for better performance.
    | Cache is automatically cleared when workflows are updated.
    |
    */

    'cache' => [
        'enabled' => env('WORKFLOW_CACHE_ENABLED', true),
        'ttl' => env('WORKFLOW_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'workflow:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Field Types
    |--------------------------------------------------------------------------
    |
    | Default field types available for templates. These will be seeded
    | into the template_field_types table.
    |
    */

    'default_field_types' => [
        'text' => [
            'name' => 'Text Input',
            'component_name' => 'TextField',
        ],
        'number' => [
            'name' => 'Number Input',
            'component_name' => 'NumberField',
        ],
        'date' => [
            'name' => 'Date Picker',
            'component_name' => 'DateField',
        ],
        'datetime' => [
            'name' => 'Date Time Picker',
            'component_name' => 'DateTimeField',
        ],
        'textarea' => [
            'name' => 'Text Area',
            'component_name' => 'TextAreaField',
        ],
        'dropdown' => [
            'name' => 'Dropdown Select',
            'component_name' => 'DropdownField',
        ],
        'radio' => [
            'name' => 'Radio Buttons',
            'component_name' => 'RadioField',
        ],
        'checkbox' => [
            'name' => 'Checkbox',
            'component_name' => 'CheckboxField',
        ],
        'multiselect' => [
            'name' => 'Multi Select',
            'component_name' => 'MultiSelectField',
        ],
        'file' => [
            'name' => 'File Upload',
            'component_name' => 'FileUploadField',
        ],
        'image' => [
            'name' => 'Image Upload',
            'component_name' => 'ImageUploadField',
        ],
        'calculated' => [
            'name' => 'Calculated Field',
            'component_name' => 'CalculatedField',
        ],
    ],

];
