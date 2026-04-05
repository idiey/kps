<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Models\Kps\AuditLog;
use App\Models\Kps\Site;
use App\Services\Kps\SiteContextResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('view', $site);

        if (! $request->user()->hasPermissionTo('kps.approve_month')) {
            abort(403);
        }

        $selectedAction = $request->string('action')->toString();

        $logsQuery = AuditLog::query()
            ->where('site_id', $site->id)
            ->with('user:id,name,email')
            ->latest();

        if ($selectedAction !== '' && $selectedAction !== 'all') {
            $logsQuery->where('action', $selectedAction);
        }

        $auditLogs = $logsQuery
            ->paginate(20)
            ->withQueryString()
            ->through(function (AuditLog $log) {
                return [
                    'id' => $log->id,
                    'site_id' => $log->site_id,
                    'user_id' => $log->user_id,
                    'action' => $log->action,
                    'auditable_type' => $log->auditable_type,
                    'auditable_id' => $log->auditable_id,
                    'auditable_label' => class_basename($log->auditable_type),
                    'metadata' => $log->metadata,
                    'created_at' => $log->created_at?->toDateTimeString(),
                    'updated_at' => $log->updated_at?->toDateTimeString(),
                    'user' => $log->user ? [
                        'id' => $log->user->id,
                        'name' => $log->user->name,
                        'email' => $log->user->email,
                    ] : null,
                ];
            });

        $availableActions = AuditLog::query()
            ->where('site_id', $site->id)
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/AuditLogs/Index', [
            'site' => $site,
            'siteRole' => $context['siteRole'],
            'auditLogs' => $auditLogs,
            'availableActions' => $availableActions,
            'filters' => [
                'action' => $selectedAction !== '' ? $selectedAction : 'all',
            ],
        ]);
    }
}
