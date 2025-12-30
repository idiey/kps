<?php

namespace App\Http\Controllers;

use App\Enums\PhotoStage;
use App\Http\Requests\Photo\UploadPhotoRequest;
use App\Models\JobPhoto;
use App\Models\WorkshopJob;
use App\Services\PhotoStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PhotoController extends Controller
{
    public function __construct(
        protected PhotoStorageService $photoService
    ) {}

    /**
     * Display photo gallery for a job.
     */
    public function index(WorkshopJob $job): Response
    {
        Gate::authorize('view', $job);

        $photoGallery = $this->photoService->generatePhotoGallery($job);

        return Inertia::render('Photos/Gallery', [
            'job' => $job->load(['customer', 'kewPA10', 'asset']),
            'photoGallery' => $photoGallery,
            'photoStages' => PhotoStage::options(),
            'canUpload' => auth()->user()->can('create', JobPhoto::class),
        ]);
    }

    /**
     * Store a single photo.
     */
    public function store(UploadPhotoRequest $request, WorkshopJob $job): RedirectResponse
    {
        $photo = $this->photoService->uploadPhoto(
            $job,
            $request->file('photo'),
            PhotoStage::from($request->input('photo_stage')),
            $request->only(['description', 'location_context', 'is_public', 'inspection_report_id'])
        );

        return redirect()->back()
            ->with('success', __('Photo uploaded successfully'));
    }

    /**
     * Upload multiple photos at once.
     */
    public function bulkUpload(UploadPhotoRequest $request, WorkshopJob $job): RedirectResponse
    {
        $files = $request->file('photos');

        if (!$files) {
            return redirect()->back()
                ->with('error', __('No photos provided'));
        }

        $photos = $this->photoService->uploadMultiplePhotos(
            $job,
            $files,
            PhotoStage::from($request->input('photo_stage')),
            $request->only(['description', 'location_context', 'is_public', 'inspection_report_id'])
        );

        return redirect()->back()
            ->with('success', __(':count photos uploaded successfully', ['count' => $photos->count()]));
    }

    /**
     * Display photos filtered by stage.
     */
    public function byStage(WorkshopJob $job, string $stage): Response
    {
        Gate::authorize('view', $job);

        try {
            $photoStage = PhotoStage::from($stage);
        } catch (\ValueError $e) {
            abort(404, 'Invalid photo stage');
        }

        $photos = $this->photoService->getPhotosByStage($job, $photoStage);

        return Inertia::render('Photos/StageView', [
            'job' => $job,
            'stage' => $photoStage,
            'photos' => $photos,
        ]);
    }

    /**
     * Delete a photo.
     */
    public function destroy(JobPhoto $photo): RedirectResponse
    {
        Gate::authorize('delete', $photo);

        $this->photoService->deletePhoto($photo);

        return redirect()->back()
            ->with('success', __('Photo deleted successfully'));
    }

    /**
     * Toggle photo visibility (public/private).
     */
    public function togglePublic(JobPhoto $photo): RedirectResponse
    {
        Gate::authorize('update', $photo);

        $photo->update([
            'is_public' => !$photo->is_public,
        ]);

        return redirect()->back()
            ->with('success', __('Photo visibility updated'));
    }
}
