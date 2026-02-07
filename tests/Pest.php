<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
}

function ensureRole(string $name): \Spatie\Permission\Models\Role
{
    return \Spatie\Permission\Models\Role::firstOrCreate(
        ['name' => $name, 'guard_name' => 'web'],
        ['is_active' => true]
    );
}

function createWorkflowWithRoles(array $allowedRoleIds): \App\Models\Workflow\Workflow
{
    $allowedRoleIds = array_values(array_unique(array_map('intval', $allowedRoleIds)));

    $workflow = \App\Models\Workflow\Workflow::create([
        'name' => 'Test Workflow ' . uniqid(),
        'code' => 'test-workflow-' . uniqid(),
        'description' => null,
        'is_active' => true,
        'is_default' => false,
        'allowed_roles' => $allowedRoleIds,
    ]);

    $s1 = $workflow->statuses()->create([
        'name' => 'New',
        'code' => 'new',
        'is_initial' => true,
        'is_final' => false,
        'display_order' => 0,
    ]);

    $s2 = $workflow->statuses()->create([
        'name' => 'In Progress',
        'code' => 'in_progress',
        'is_initial' => false,
        'is_final' => false,
        'display_order' => 1,
    ]);

    $workflow->transitions()->create([
        'name' => 'Start',
        'from_status_id' => $s1->id,
        'to_status_id' => $s2->id,
        'allowed_roles' => $allowedRoleIds,
        'is_active' => true,
        'display_order' => 0,
    ]);

    return $workflow;
}
