<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // TODO: Add role middleware - only PENTADBIRAN should access
        // $this->middleware('role:pentadbiran');
    }

    /**
     * Display a listing of fields for a template.
     */
    public function index(JobTemplate $template)
    {
        $fields = $template->fields()
            ->with('fieldType')
            ->orderBy('section')
            ->orderBy('display_order')
            ->get();

        $sections = $fields->groupBy('section');

        return Inertia::render('Admin/Templates/Fields/Index', [
            'template' => $template,
            'fields' => $fields,
            'sections' => $sections->keys(),
        ]);
    }

    /**
     * Show the form for creating a new field.
     */
    public function create(JobTemplate $template)
    {
        $fieldTypes = TemplateFieldType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Templates/Fields/Create', [
            'template' => $template,
            'fieldTypes' => $fieldTypes,
        ]);
    }

    /**
     * Store a newly created field.
     */
    public function store(Request $request, JobTemplate $template)
    {
        $validated = $request->validate([
            'field_type_id' => 'required|exists:template_field_types,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'description' => 'nullable|string',
            'placeholder' => 'nullable|string',
            'default_value' => 'nullable|string',
            'section' => 'nullable|string|max:255',
            'display_order' => 'integer|min:0',
            'grid_column_span' => 'integer|min:1|max:12',
            'is_required' => 'boolean',
            'validation_rules' => 'nullable|array',
            'conditional_rules' => 'nullable|array',
            'options' => 'nullable|array',
            'options_source' => 'nullable|string|in:static,database,api',
            'options_table' => 'nullable|string|max:255',
            'options_value_column' => 'nullable|string|max:255',
            'options_label_column' => 'nullable|string|max:255',
            'options_api_endpoint' => 'nullable|string|max:255',
            'formula' => 'nullable|string',
            'help_text' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Ensure code is unique within template
        $exists = $template->fields()
            ->where('code', $validated['code'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'code' => 'A field with this code already exists in this template.',
            ]);
        }

        $validated['template_id'] = $template->id;

        $field = TemplateField::create($validated);

        return redirect()->route('admin.templates.fields.index', $template)
            ->with('success', 'Field created successfully.');
    }

    /**
     * Display the specified field.
     */
    public function show(JobTemplate $template, TemplateField $field)
    {
        // Ensure field belongs to template
        if ($field->template_id !== $template->id) {
            abort(404);
        }

        $field->load('fieldType');

        return Inertia::render('Admin/Templates/Fields/Show', [
            'template' => $template,
            'field' => $field,
        ]);
    }

    /**
     * Show the form for editing the specified field.
     */
    public function edit(JobTemplate $template, TemplateField $field)
    {
        // Ensure field belongs to template
        if ($field->template_id !== $template->id) {
            abort(404);
        }

        $field->load('fieldType');

        $fieldTypes = TemplateFieldType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Templates/Fields/Edit', [
            'template' => $template,
            'field' => $field,
            'fieldTypes' => $fieldTypes,
        ]);
    }

    /**
     * Update the specified field.
     */
    public function update(Request $request, JobTemplate $template, TemplateField $field)
    {
        // Ensure field belongs to template
        if ($field->template_id !== $template->id) {
            abort(404);
        }

        $validated = $request->validate([
            'field_type_id' => 'required|exists:template_field_types,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'description' => 'nullable|string',
            'placeholder' => 'nullable|string',
            'default_value' => 'nullable|string',
            'section' => 'nullable|string|max:255',
            'display_order' => 'integer|min:0',
            'grid_column_span' => 'integer|min:1|max:12',
            'is_required' => 'boolean',
            'validation_rules' => 'nullable|array',
            'conditional_rules' => 'nullable|array',
            'options' => 'nullable|array',
            'options_source' => 'nullable|string|in:static,database,api',
            'options_table' => 'nullable|string|max:255',
            'options_value_column' => 'nullable|string|max:255',
            'options_label_column' => 'nullable|string|max:255',
            'options_api_endpoint' => 'nullable|string|max:255',
            'formula' => 'nullable|string',
            'help_text' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Ensure code is unique within template (excluding current field)
        $exists = $template->fields()
            ->where('code', $validated['code'])
            ->where('id', '!=', $field->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'code' => 'A field with this code already exists in this template.',
            ]);
        }

        $field->update($validated);

        return back()->with('success', 'Field updated successfully.');
    }

    /**
     * Remove the specified field.
     */
    public function destroy(JobTemplate $template, TemplateField $field)
    {
        // Ensure field belongs to template
        if ($field->template_id !== $template->id) {
            abort(404);
        }

        // Check if field has values in any jobs
        if ($field->jobFieldValues()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete field that has values in existing jobs.',
            ]);
        }

        $field->delete();

        return redirect()->route('admin.templates.fields.index', $template)
            ->with('success', 'Field deleted successfully.');
    }

    /**
     * Reorder fields within a section.
     */
    public function reorder(Request $request, JobTemplate $template)
    {
        $validated = $request->validate([
            'fields' => 'required|array',
            'fields.*.id' => 'required|exists:template_fields,id',
            'fields.*.display_order' => 'required|integer|min:0',
            'fields.*.section' => 'nullable|string|max:255',
        ]);

        foreach ($validated['fields'] as $fieldData) {
            TemplateField::where('id', $fieldData['id'])
                ->where('template_id', $template->id)
                ->update([
                    'display_order' => $fieldData['display_order'],
                    'section' => $fieldData['section'] ?? null,
                ]);
        }

        return back()->with('success', 'Fields reordered successfully.');
    }

    /**
     * Duplicate a field.
     */
    public function duplicate(JobTemplate $template, TemplateField $field)
    {
        // Ensure field belongs to template
        if ($field->template_id !== $template->id) {
            abort(404);
        }

        $newField = $field->replicate();
        $newField->code = $field->code . '_copy';
        $newField->name = $field->name . ' (Copy)';
        $newField->display_order = $field->display_order + 1;
        $newField->save();

        return redirect()->route('admin.templates.fields.edit', [$template, $newField])
            ->with('success', 'Field duplicated successfully. Please update the code and name.');
    }

    /**
     * Preview field configuration.
     */
    public function preview(Request $request, JobTemplate $template, TemplateField $field)
    {
        // Ensure field belongs to template
        if ($field->template_id !== $template->id) {
            abort(404);
        }

        $formConfig = $field->toFormConfig();

        return response()->json([
            'formConfig' => $formConfig,
        ]);
    }

    /**
     * Test formula field.
     */
    public function testFormula(Request $request, JobTemplate $template, TemplateField $field)
    {
        // Ensure field belongs to template
        if ($field->template_id !== $template->id) {
            abort(404);
        }

        $validated = $request->validate([
            'test_values' => 'required|array',
        ]);

        try {
            $result = $field->evaluateFormula($validated['test_values']);

            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
