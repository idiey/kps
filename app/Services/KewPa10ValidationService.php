<?php

namespace App\Services;

use App\Models\WorkshopJob;

class KewPa10ValidationService
{
    /**
     * Validate required fields for a KEW.PA-10 job update.
     * Returns an array of error messages.
     */
    public function validate(array $data): array
    {
        $errors = [];
        // Basic validation rules can be here, but usually FormRequest handles input validation.
        // This service is more for business logic validation (e.g. cross-field checks).
        
        return $errors;
    }

    /**
     * Check if a job has all required KEW.PA-10 fields populated before approval/submission.
     */
    public function ensureReadyForApproval(WorkshopJob $job): void
    {
        $requiredFields = [
            'kew_vehicle_registration',
            'kew_asset_tag',
            'kew_department_name',
            'kew_inspection_date',
            // 'kew_inspector_name', // May only be filled AT inspection time
        ];

        $missing = [];
        foreach ($requiredFields as $field) {
            if (empty($job->$field)) {
                $missing[] = $this->getFriendlyFieldName($field);
            }
        }

        if (!empty($missing)) {
             throw new \InvalidArgumentException(
                "Missing required KEW.PA-10 information: " . implode(', ', $missing)
             );
        }
    }

    /**
     * Check if inspection details are filled before completing inspection.
     */
    public function ensureInspectionComplete(WorkshopJob $job): void
    {
        $requiredFields = [
            'kew_inspector_name',
            'kew_inspector_ic',
            'kew_findings',
            'kew_recommendations'
        ];

        $missing = [];
        foreach ($requiredFields as $field) {
            if (empty($job->$field)) {
                $missing[] = $this->getFriendlyFieldName($field);
            }
        }

        if (!empty($missing)) {
             throw new \InvalidArgumentException(
                "Inspection details incomplete: " . implode(', ', $missing)
             );
        }
    }

    protected function getFriendlyFieldName(string $field): string
    {
        return ucwords(str_replace(['kew_', '_'], ['', ' '], $field));
    }
}
