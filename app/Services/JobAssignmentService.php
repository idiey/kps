<?php

namespace App\Services;

use App\Events\JobAssigned;
use App\Models\JobAssignment;
use App\Models\WorkshopJob;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing job assignments.
 * Handles assignment logic and history tracking.
 */
class JobAssignmentService
{
    /**
     * Assign a job to a technician.
     */
    public function assignJob(
        WorkshopJob $job,
        int $technicianId,
        ?string $notes = null
    ): JobAssignment {
        return DB::transaction(function () use ($job, $technicianId, $notes) {
            // Mark previous assignments as inactive
            $job->assignments()
                ->where('is_current', true)
                ->each(fn($assignment) => $assignment->markAsInactive());

            // Create new assignment
            $assignment = $job->assignments()->create([
                'assigned_by' => auth()->id(),
                'assigned_to' => $technicianId,
                'notes' => $notes,
                'assigned_at' => now(),
                'is_current' => true,
            ]);

            // Update job assigned_to field
            $job->update(['assigned_to' => $technicianId]);

            // Dispatch event for notifications
            event(new JobAssigned($job, $assignment));

            return $assignment->load(['assignedBy', 'assignedTo', 'job']);
        });
    }

    /**
     * Reassign a job to a different technician.
     */
    public function reassignJob(
        WorkshopJob $job,
        int $newTechnicianId,
        ?string $notes = null
    ): JobAssignment {
        return $this->assignJob($job, $newTechnicianId, $notes);
    }

    /**
     * Unassign a job (remove current assignment).
     */
    public function unassignJob(WorkshopJob $job): bool
    {
        return DB::transaction(function () use ($job) {
            // Mark current assignments as inactive
            $job->assignments()
                ->where('is_current', true)
                ->each(fn($assignment) => $assignment->markAsInactive());

            // Clear job assignment
            $job->update(['assigned_to' => null]);

            return true;
        });
    }

    /**
     * Get assignment history for a job.
     */
    public function getAssignmentHistory(WorkshopJob $job)
    {
        return $job->assignments()
            ->with(['assignedBy', 'assignedTo'])
            ->orderBy('assigned_at', 'desc')
            ->get();
    }

    /**
     * Get current workload for a technician.
     */
    public function getTechnicianWorkload(int $technicianId): array
    {
        $assignments = JobAssignment::forTechnician($technicianId)
            ->current()
            ->with('job')
            ->get();

        return [
            'total_jobs' => $assignments->count(),
            'by_status' => $assignments->groupBy('job.status')
                ->map(fn($group) => $group->count())
                ->toArray(),
            'by_priority' => $assignments->groupBy('job.priority')
                ->map(fn($group) => $group->count())
                ->toArray(),
        ];
    }

    /**
     * Get all technicians with their current workload.
     */
    public function getAllTechniciansWorkload(): array
    {
        $technicians = \App\Models\User::role('juruteknik')
            ->withCount(['assignedJobs' => function ($query) {
                $query->whereNotIn('status', ['completed', 'invoiced']);
            }])
            ->get();

        return $technicians->map(function ($technician) {
            return [
                'id' => $technician->id,
                'name' => $technician->name,
                'active_jobs_count' => $technician->assigned_jobs_count,
                'workload' => $this->getTechnicianWorkload($technician->id),
            ];
        })->toArray();
    }
}
