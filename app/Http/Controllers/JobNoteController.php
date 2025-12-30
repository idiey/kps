<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobNoteRequest;
use App\Http\Requests\UpdateJobNoteRequest;
use App\Models\JobNote;
use App\Models\WorkshopJob;
use App\Services\JobNoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Controller for managing job notes.
 */
class JobNoteController extends Controller
{
    public function __construct(
        protected JobNoteService $noteService
    ) {}

    /**
     * Display a listing of notes for a job.
     */
    public function index(WorkshopJob $job)
    {
        Gate::authorize('viewAny', JobNote::class);

        $notes = $job->notes()->with('user')->get();

        // Filter notes based on authorization
        $filteredNotes = $notes->filter(function ($note) {
            return auth()->user()->can('view', $note);
        });

        return response()->json([
            'notes' => $filteredNotes->values()->map(function ($note) {
                return [
                    'id' => $note->id,
                    'content' => $note->content,
                    'is_public' => $note->is_public,
                    'note_type' => $note->note_type,
                    'user' => [
                        'id' => $note->user->id,
                        'name' => $note->user->name,
                    ],
                    'created_at' => $note->created_at,
                    'updated_at' => $note->updated_at,
                    'can_edit' => auth()->user()->can('update', $note),
                    'can_delete' => auth()->user()->can('delete', $note),
                ];
            }),
        ]);
    }

    /**
     * Store a newly created note.
     */
    public function store(StoreJobNoteRequest $request, WorkshopJob $job): RedirectResponse
    {
        $validated = $request->validated();

        $this->noteService->createNote(
            $job,
            $validated['content'],
            $validated['is_public'] ?? false,
            $validated['note_type'] ?? 'general'
        );

        return redirect()->back()
            ->with('success', __('notes.created_successfully'));
    }

    /**
     * Update the specified note.
     */
    public function update(UpdateJobNoteRequest $request, WorkshopJob $job, JobNote $note): RedirectResponse
    {
        $validated = $request->validated();

        $this->noteService->updateNote($note, $validated);

        return redirect()->back()
            ->with('success', __('notes.updated_successfully'));
    }

    /**
     * Remove the specified note.
     */
    public function destroy(WorkshopJob $job, JobNote $note): RedirectResponse
    {
        Gate::authorize('delete', $note);

        $this->noteService->deleteNote($note);

        return redirect()->back()
            ->with('success', __('notes.deleted_successfully'));
    }
}
