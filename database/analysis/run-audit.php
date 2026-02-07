<?php

/**
 * Data Audit Script - Run all analysis queries
 * Usage: php database/analysis/run-audit.php
 */

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$output = [];
$output[] = "=================================================";
$output[] = "DATA AUDIT REPORT - " . date('Y-m-d H:i:s');
$output[] = "=================================================\n";

// Query 1: Count jobs per workflow type
$output[] = "QUERY 1: Jobs per Workflow Type";
$output[] = str_repeat("-", 50);
try {
    $results = DB::select("
        SELECT 
            w.name AS workflow_name, 
            COUNT(j.id) AS job_count,
            ROUND(COUNT(j.id) * 100.0 / (SELECT COUNT(*) FROM workshop_jobs), 2) AS percentage
        FROM workshop_jobs j
        LEFT JOIN workflows w ON j.workflow_id = w.id
        GROUP BY w.name
        ORDER BY job_count DESC
    ");
    foreach ($results as $row) {
        $output[] = sprintf("  %s: %d jobs (%.2f%%)", 
            $row->workflow_name ?? 'NULL/Normal', 
            $row->job_count, 
            $row->percentage
        );
    }
} catch (Exception $e) {
    $output[] = "  ERROR: " . $e->getMessage();
}
$output[] = "";

// Query 2: Total counts summary
$output[] = "QUERY 2: Overall Statistics";
$output[] = str_repeat("-", 50);
try {
    $stats = DB::selectOne("
        SELECT 
            (SELECT COUNT(*) FROM workshop_jobs) AS total_jobs,
            (SELECT COUNT(*) FROM workshop_jobs WHERE workflow_id IS NOT NULL) AS jobs_with_workflow,
            (SELECT COUNT(*) FROM workflows WHERE is_active = 1) AS active_workflows,
            (SELECT COUNT(*) FROM form_templates WHERE is_active = 1) AS active_templates,
            (SELECT COUNT(*) FROM job_form_data) AS total_form_submissions,
            (SELECT COUNT(*) FROM workflow_rules) AS total_workflow_rules
    ");
    $output[] = "  Total Jobs: " . $stats->total_jobs;
    $output[] = "  Jobs with Workflow: " . $stats->jobs_with_workflow;
    $output[] = "  Active Workflows: " . $stats->active_workflows;
    $output[] = "  Active Templates: " . $stats->active_templates;
    $output[] = "  Total Form Submissions: " . $stats->total_form_submissions;
    $output[] = "  Total Workflow Rules: " . $stats->total_workflow_rules;
} catch (Exception $e) {
    $output[] = "  ERROR: " . $e->getMessage();
}
$output[] = "";

// Query 3: KEW.PA-10 jobs count
$output[] = "QUERY 3: KEW.PA-10 Jobs Identification";
$output[] = str_repeat("-", 50);
try {
    $kewCount = DB::table('workshop_jobs as j')
        ->join('job_form_data as jfd', 'j.id', '=', 'jfd.job_id')
        ->leftJoin('form_templates as ft', 'jfd.form_template_id', '=', 'ft.id')
        ->where(function($query) {
            $query->where('ft.name', 'LIKE', '%KEW%')
                  ->orWhere('ft.name', 'LIKE', '%PA-10%');
        })
        ->count();
    $output[] = "  KEW.PA-10 Jobs Found: " . $kewCount;
    
    // Sample KEW jobs
    $sampleKew = DB::table('workshop_jobs as j')
        ->select('j.id', 'j.reference_no', 'ft.name as form_template_name', 'j.status')
        ->join('job_form_data as jfd', 'j.id', '=', 'jfd.job_id')
        ->leftJoin('form_templates as ft', 'jfd.form_template_id', '=', 'ft.id')
        ->where(function($query) {
            $query->where('ft.name', 'LIKE', '%KEW%')
                  ->orWhere('ft.name', 'LIKE', '%PA-10%');
        })
        ->limit(5)
        ->get();
    
    $output[] = "\n  Sample KEW.PA-10 Jobs:";
    foreach ($sampleKew as $job) {
        $output[] = sprintf("    - ID: %d, Ref: %s, Template: %s, Status: %s", 
            $job->id, 
            $job->reference_no, 
            $job->form_template_name,
            $job->status
        );
    }
} catch (Exception $e) {
    $output[] = "  ERROR: " . $e->getMessage();
}
$output[] = "";

// Query 4: Active Workflow Rules
$output[] = "QUERY 4: Active Workflow Rules";
$output[] = str_repeat("-", 50);
try {
    $rules = DB::table('workflow_rules as wr')
        ->select('wr.id', 'w.name as workflow_name', 'wr.from_status', 'wr.to_status')
        ->join('workflows as w', 'wr.workflow_id', '=', 'w.id')
        ->where('w.is_active', 1)
        ->get();
    
    $output[] = "  Total Active Rules: " . $rules->count();
    foreach ($rules as $rule) {
        $output[] = sprintf("    - Rule #%d: %s (%s → %s)", 
            $rule->id,
            $rule->workflow_name,
            $rule->from_status,
            $rule->to_status
        );
    }
} catch (Exception $e) {
    $output[] = "  ERROR: " . $e->getMessage();
}
$output[] = "";

// Query 5: Template variations
$output[] = "QUERY 5: Template Variations";
$output[] = str_repeat("-", 50);
try {
    $templates = DB::table('form_templates as ft')
        ->select('ft.id', 'ft.name', 'ft.version', DB::raw('COUNT(jfd.id) as usage_count'))
        ->leftJoin('job_form_data as jfd', 'ft.id', '=', 'jfd.form_template_id')
        ->groupBy('ft.id', 'ft.name', 'ft.version')
        ->orderByDesc('usage_count')
        ->get();
    
    $output[] = "  Total Templates: " . $templates->count();
    foreach ($templates as $tpl) {
        $output[] = sprintf("    - %s (v%s): Used %d times", 
            $tpl->name,
            $tpl->version ?? '1.0',
            $tpl->usage_count
        );
    }
} catch (Exception $e) {
    $output[] = "  ERROR: " . $e->getMessage();
}
$output[] = "";

// Query 6: Recent activity (last 30 days)
$output[] = "QUERY 6: Recent Activity (Last 30 Days)";
$output[] = str_repeat("-", 50);
try {
    $recentJobs = DB::table('workshop_jobs as j')
        ->select(DB::raw('DATE(j.created_at) as creation_date'), 'w.name as workflow_name', DB::raw('COUNT(j.id) as jobs_created'))
        ->leftJoin('workflows as w', 'j.workflow_id', '=', 'w.id')
        ->where('j.created_at', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 30 DAY)'))
        ->groupBy(DB::raw('DATE(j.created_at)'), 'w.name')
        ->orderByDesc('creation_date')
        ->limit(10)
        ->get();
    
    foreach ($recentJobs as $activity) {
        $output[] = sprintf("    - %s: %s (%d jobs)", 
            $activity->creation_date,
            $activity->workflow_name ?? 'Normal',
            $activity->jobs_created
        );
    }
} catch (Exception $e) {
    $output[] = "  ERROR: " . $e->getMessage();
}
$output[] = "";

// Save to file
$reportPath = __DIR__ . '/data-audit-results.txt';
file_put_contents($reportPath, implode("\n", $output));

// Also print to console
echo implode("\n", $output);
echo "\n\nReport saved to: " . $reportPath . "\n";
