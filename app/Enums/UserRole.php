<?php

namespace App\Enums;

enum UserRole: string
{
    case PENTADBIRAN = 'pentadbiran'; // Admin Officer
    case PENYELIA = 'penyelia'; // Supervisor
    case PEMERIKSA = 'pemeriksa'; // Inspector
    case PELULUS = 'pelulus'; // Approver
    case JURUTEKNIK = 'juruteknik'; // Technician
    case KAUNTER = 'kaunter'; // Front Desk

    /**
     * Get the display label for the role.
     */
    public function label(): string
    {
        return match($this) {
            self::PENTADBIRAN => 'Admin Officer (Pentadbiran)',
            self::PENYELIA => 'Supervisor (Penyelia)',
            self::PEMERIKSA => 'Inspector (Pemeriksa)',
            self::PELULUS => 'Approver (Pelulus)',
            self::JURUTEKNIK => 'Technician (Juruteknik)',
            self::KAUNTER => 'Front Desk (Kaunter)',
        };
    }

    /**
     * Get the English name for the role.
     */
    public function englishName(): string
    {
        return match($this) {
            self::PENTADBIRAN => 'Admin Officer',
            self::PENYELIA => 'Supervisor',
            self::PEMERIKSA => 'Inspector',
            self::PELULUS => 'Approver',
            self::JURUTEKNIK => 'Technician',
            self::KAUNTER => 'Front Desk',
        };
    }

    /**
     * Get the Malay name for the role.
     */
    public function malayName(): string
    {
        return match($this) {
            self::PENTADBIRAN => 'Pentadbiran',
            self::PENYELIA => 'Penyelia',
            self::PEMERIKSA => 'Pemeriksa',
            self::PELULUS => 'Pelulus',
            self::JURUTEKNIK => 'Juruteknik',
            self::KAUNTER => 'Kaunter',
        };
    }

    /**
     * Check if role can assign jobs.
     */
    public function canAssignJobs(): bool
    {
        return in_array($this, [self::PENYELIA, self::PENTADBIRAN], true);
    }

    /**
     * Check if role can be assigned to jobs.
     */
    public function canBeAssignedJobs(): bool
    {
        return $this === self::JURUTEKNIK;
    }

    /**
     * Check if role can approve.
     */
    public function canApprove(): bool
    {
        return $this === self::PELULUS || $this === self::PENYELIA;
    }

    /**
     * Check if this role can manage KEW.PA-10 forms.
     * Only Admin Officers can create, update, verify, and process KEW.PA-10 forms.
     */
    public function canManageKewPA10(): bool
    {
        return $this === self::PENTADBIRAN;
    }

    /**
     * Check if this role can conduct inspections.
     * Only Inspectors can create and manage inspection reports.
     */
    public function canInspect(): bool
    {
        return $this === self::PEMERIKSA;
    }

    /**
     * Check if this role can supervise and approve reports.
    * Supervisors can approve/reject inspection and completion reports.
     */
    public function canSupervise(): bool
    {
        return $this === self::PENYELIA;
    }

    /**
     * Check if this role can perform repairs.
     * Only Technicians can create completion reports and perform repairs.
     */
    public function canRepair(): bool
    {
        return $this === self::JURUTEKNIK;
    }

    /**
     * Get the description of the role's responsibilities in KEW.PA-10 workflow.
     */
    public function kewPA10Description(): string
    {
        return match($this) {
            self::PENTADBIRAN => 'Manages KEW.PA-10 forms, administrative tasks, and form verification',
            self::PEMERIKSA => 'Conducts inspections, creates inspection reports, and documents findings',
            self::PENYELIA => 'Approves/rejects inspections and completion reports, oversees work quality',
            self::JURUTEKNIK => 'Performs repairs, creates completion reports, and documents work performed',
            self::PELULUS => 'Reviews and approves workflow processes',
            self::KAUNTER => 'Manages front desk operations, job intake, and customer interactions',
        };
    }

    /**
     * Get all roles as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get all roles with labels.
     */
    public static function options(): array
    {
        return array_map(
            fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
                'english' => $case->englishName(),
                'malay' => $case->malayName(),
            ],
            self::cases()
        );
    }
}
