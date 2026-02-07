<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pentadbiran');
    }

    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable'],
            'settings.*.group' => ['required', 'string'],
            'settings.*.type' => ['required', 'in:string,boolean,integer,json'],
        ]);

        foreach ($validated['settings'] as $settingData) {
            Setting::set(
                $settingData['key'],
                $settingData['value'],
                $settingData['group'],
                $settingData['type']
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Create a new setting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:settings,key'],
            'value' => ['nullable'],
            'group' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:string,boolean,integer,json'],
            'description' => ['nullable', 'string'],
        ]);

        Setting::create($validated);

        return back()->with('success', 'Setting created successfully.');
    }

    /**
     * Delete a setting
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return back()->with('success', 'Setting deleted successfully.');
    }

    /**
     * Initialize default settings
     */
    public function initializeDefaults()
    {
        $defaults = [
            // General Settings
            ['key' => 'app_name', 'value' => 'Workshop Management System', 'group' => 'general', 'type' => 'string', 'description' => 'Application name'],
            ['key' => 'app_timezone', 'value' => 'Asia/Kuala_Lumpur', 'group' => 'general', 'type' => 'string', 'description' => 'Default timezone'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'group' => 'general', 'type' => 'boolean', 'description' => 'Enable maintenance mode'],
            
            // Job Settings
            ['key' => 'auto_assign_jobs', 'value' => 'false', 'group' => 'jobs', 'type' => 'boolean', 'description' => 'Automatically assign jobs to technicians'],
            ['key' => 'job_number_prefix', 'value' => 'JOB', 'group' => 'jobs', 'type' => 'string', 'description' => 'Job number prefix'],
            ['key' => 'default_job_priority', 'value' => 'normal', 'group' => 'jobs', 'type' => 'string', 'description' => 'Default job priority'],
            
            // Notification Settings
            ['key' => 'email_notifications', 'value' => 'true', 'group' => 'notifications', 'type' => 'boolean', 'description' => 'Enable email notifications'],
            ['key' => 'sms_notifications', 'value' => 'false', 'group' => 'notifications', 'type' => 'boolean', 'description' => 'Enable SMS notifications'],
            
            // Inventory Settings
            ['key' => 'low_stock_threshold', 'value' => '10', 'group' => 'inventory', 'type' => 'integer', 'description' => 'Default low stock threshold'],
            ['key' => 'auto_reorder', 'value' => 'false', 'group' => 'inventory', 'type' => 'boolean', 'description' => 'Enable automatic reordering'],
        ];

        foreach ($defaults as $default) {
            Setting::firstOrCreate(
                ['key' => $default['key']],
                $default
            );
        }

        return back()->with('success', 'Default settings initialized successfully.');
    }
}
