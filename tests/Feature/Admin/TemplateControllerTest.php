<?php

namespace Tests\Feature\Admin;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected JobTemplate $template;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'pentadbiran']);
        
        // Seed field types if needed
        if (TemplateFieldType::count() === 0) {
            TemplateFieldType::create(['name' => 'Text', 'code' => 'text', 'component_name' => 'TextInput']);
            TemplateFieldType::create(['name' => 'Textarea', 'code' => 'textarea', 'component_name' => 'Textarea']);
            TemplateFieldType::create(['name' => 'Select', 'code' => 'select', 'component_name' => 'Select']);
        }
    }

    public function test_admin_can_view_templates_index(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.templates.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_template(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.templates.store'), [
            'name' => 'Test Template',
            'code' => 'test_template',
            'description' => 'Test Description',
            'is_active' => true,
            'is_default' => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_templates', [
            'name' => 'Test Template',
            'code' => 'test_template',
        ]);
    }

    public function test_admin_can_update_template(): void
    {
        $this->actingAs($this->admin);
        
        $template = JobTemplate::factory()->create([
            'name' => 'Original Name',
            'code' => 'original_code',
        ]);

        $response = $this->put(route('admin.templates.update', $template), [
            'name' => 'Updated Name',
            'code' => 'updated_code',
            'description' => 'Updated Description',
            'is_active' => true,
            'is_default' => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_templates', [
            'id' => $template->id,
            'name' => 'Updated Name',
            'code' => 'updated_code',
        ]);
    }

    public function test_admin_can_delete_unused_template(): void
    {
        $this->actingAs($this->admin);
        
        $template = JobTemplate::factory()->create();

        $response = $this->delete(route('admin.templates.destroy', $template));

        $response->assertRedirect();
        $this->assertSoftDeleted('job_templates', ['id' => $template->id]);
    }

    public function test_template_code_must_be_unique(): void
    {
        $this->actingAs($this->admin);
        
        JobTemplate::factory()->create(['code' => 'unique_code']);

        $response = $this->post(route('admin.templates.store'), [
            'name' => 'Test Template',
            'code' => 'unique_code',
            'description' => 'Test',
            'is_active' => true,
            'is_default' => false,
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_can_get_template_schema(): void
    {
        $this->actingAs($this->admin);
        
        $template = JobTemplate::factory()->create();
        $fieldType = TemplateFieldType::first();
        
        TemplateField::factory()->create([
            'template_id' => $template->id,
            'field_type_id' => $fieldType->id,
            'name' => 'Test Field',
            'code' => 'test_field',
        ]);

        $response = $this->get(route('admin.templates.schema', $template));

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    '*' => [
                        'id',
                        'code',
                        'type',
                        'label',
                    ],
                ],
            ]);
    }
}
