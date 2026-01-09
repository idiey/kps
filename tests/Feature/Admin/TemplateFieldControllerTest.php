<?php

namespace Tests\Feature\Admin;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateFieldControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected JobTemplate $template;
    protected TemplateFieldType $fieldType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'pentadbiran']);
        $this->template = JobTemplate::factory()->create();
        
        // Seed field types
        if (TemplateFieldType::count() === 0) {
            $this->fieldType = TemplateFieldType::create([
                'name' => 'Text',
                'code' => 'text',
                'component_name' => 'TextInput',
            ]);
        } else {
            $this->fieldType = TemplateFieldType::first();
        }
    }

    public function test_admin_can_create_field(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.templates.fields.store', $this->template), [
            'field_type_id' => $this->fieldType->id,
            'name' => 'Test Field',
            'code' => 'test_field',
            'is_required' => true,
            'display_order' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('template_fields', [
            'template_id' => $this->template->id,
            'name' => 'Test Field',
            'code' => 'test_field',
        ]);
    }

    public function test_field_code_must_be_unique_within_template(): void
    {
        $this->actingAs($this->admin);
        
        TemplateField::factory()->create([
            'template_id' => $this->template->id,
            'code' => 'duplicate_code',
        ]);

        $response = $this->post(route('admin.templates.fields.store', $this->template), [
            'field_type_id' => $this->fieldType->id,
            'name' => 'Another Field',
            'code' => 'duplicate_code',
            'is_required' => false,
            'display_order' => 2,
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_admin_can_reorder_fields(): void
    {
        $this->actingAs($this->admin);
        
        $field1 = TemplateField::factory()->create([
            'template_id' => $this->template->id,
            'display_order' => 1,
        ]);
        
        $field2 = TemplateField::factory()->create([
            'template_id' => $this->template->id,
            'display_order' => 2,
        ]);

        $response = $this->post(route('admin.templates.fields.reorder', $this->template), [
            'fields' => [
                ['id' => $field2->id, 'display_order' => 1],
                ['id' => $field1->id, 'display_order' => 2],
            ],
        ]);

        $response->assertRedirect();
        
        $this->assertEquals(1, $field2->fresh()->display_order);
        $this->assertEquals(2, $field1->fresh()->display_order);
    }

    public function test_admin_can_delete_field(): void
    {
        $this->actingAs($this->admin);
        
        $field = TemplateField::factory()->create([
            'template_id' => $this->template->id,
        ]);

        $response = $this->delete(route('admin.templates.fields.destroy', [
            'template' => $this->template,
            'field' => $field,
        ]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('template_fields', ['id' => $field->id]);
    }
}
