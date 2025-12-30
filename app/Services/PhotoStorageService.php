<?php

namespace App\Services;

use App\Enums\PhotoStage;
use App\Models\JobPhoto;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Service class for managing job photo uploads and storage.
 * Handles photo categorization by workflow stage.
 */
class PhotoStorageService
{
    /**
     * Upload a single photo for a job.
     */
    public function uploadPhoto(
        WorkshopJob $job,
        UploadedFile $file,
        PhotoStage $stage,
        array $metadata = []
    ): JobPhoto {
        // Validate file
        $this->validatePhotoFile($file);

        // Generate storage path
        $path = $this->generateStoragePath($job, $stage);

        // Store file
        $filename = $this->generateUniqueFilename($file);
        $filePath = Storage::disk('public')->putFileAs($path, $file, $filename);

        // Create photo record
        return JobPhoto::create([
            'workshop_job_id' => $job->id,
            'user_id' => auth()->id(),
            'inspection_report_id' => $metadata['inspection_report_id'] ?? null,
            'photo_stage' => $stage,
            'category' => $metadata['category'] ?? null,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $metadata['description'] ?? null,
            'location_context' => $metadata['location_context'] ?? null,
            'is_public' => $metadata['is_public'] ?? false,
            'taken_at' => $metadata['taken_at'] ?? now(),
        ]);
    }

    /**
     * Upload multiple photos at once.
     */
    public function uploadMultiplePhotos(
        WorkshopJob $job,
        array $files,
        PhotoStage $stage,
        array $metadata = []
    ): Collection {
        $photos = collect();

        foreach ($files as $file) {
            $photos->push($this->uploadPhoto($job, $file, $stage, $metadata));
        }

        return $photos;
    }

    /**
     * Delete a photo and its file.
     */
    public function deletePhoto(JobPhoto $photo): bool
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        }

        // Soft delete the photo record
        return $photo->delete();
    }

    /**
     * Validate photo requirements for a job.
     * Returns array of missing requirements.
     */
    public function validatePhotoRequirements(WorkshopJob $job): array
    {
        $missing = [];

        // Check INITIAL photos (minimum 3)
        if (!$this->hasMinimumPhotos($job, PhotoStage::INITIAL, 3)) {
            $missing[] = 'Minimum 3 INITIAL condition photos required';
        }

        // Check DIAGNOSTIC photos (minimum 3)
        if (!$this->hasMinimumPhotos($job, PhotoStage::DIAGNOSTIC, 3)) {
            $missing[] = 'Minimum 3 DIAGNOSTIC photos required';
        }

        // Check AFTER_REPAIR photos (minimum 3)
        if (!$this->hasMinimumPhotos($job, PhotoStage::AFTER_REPAIR, 3)) {
            $missing[] = 'Minimum 3 AFTER_REPAIR photos required';
        }

        return $missing;
    }

    /**
     * Check if job has minimum number of photos for a stage.
     */
    public function hasMinimumPhotos(WorkshopJob $job, PhotoStage $stage, int $minimum = 3): bool
    {
        return $job->photos()->ofStage($stage)->count() >= $minimum;
    }

    /**
     * Get photos by stage for a job.
     */
    public function getPhotosByStage(WorkshopJob $job, PhotoStage $stage): Collection
    {
        return $job->photos()
            ->ofStage($stage)
            ->with('user')
            ->orderBy('taken_at', 'desc')
            ->get();
    }

    /**
     * Get the public URL for a photo.
     */
    public function getPhotoUrl(JobPhoto $photo): string
    {
        return Storage::disk('public')->url($photo->file_path);
    }

    /**
     * Generate a photo gallery organized by stage.
     */
    public function generatePhotoGallery(WorkshopJob $job): array
    {
        $gallery = [];

        foreach (PhotoStage::cases() as $stage) {
            $photos = $this->getPhotosByStage($job, $stage);

            if ($photos->isNotEmpty()) {
                $gallery[$stage->value] = [
                    'stage' => $stage->value,
                    'label' => $stage->label(),
                    'description' => $stage->description(),
                    'count' => $photos->count(),
                    'photos' => $photos->map(function ($photo) {
                        return [
                            'id' => $photo->id,
                            'url' => $this->getPhotoUrl($photo),
                            'original_filename' => $photo->original_filename,
                            'description' => $photo->description,
                            'location_context' => $photo->location_context,
                            'taken_at' => $photo->taken_at,
                            'uploaded_by' => $photo->user->name,
                        ];
                    })->toArray(),
                ];
            }
        }

        return $gallery;
    }

    /**
     * Validate uploaded file.
     */
    protected function validatePhotoFile(UploadedFile $file): void
    {
        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \InvalidArgumentException('Photo file size cannot exceed 10MB');
        }

        // Check mime type (only JPG and PNG)
        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \InvalidArgumentException('Photo must be JPG or PNG format');
        }

        // Check if file is valid
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('Invalid photo file upload');
        }
    }

    /**
     * Generate storage path for photo.
     */
    protected function generateStoragePath(WorkshopJob $job, PhotoStage $stage): string
    {
        return "job_photos/{$job->id}/{$stage->value}";
    }

    /**
     * Generate unique filename for photo.
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = substr(md5(uniqid()), 0, 8);

        return "{$timestamp}_{$random}.{$extension}";
    }
}
