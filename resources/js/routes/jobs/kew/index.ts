import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\KewApprovalController::approve
 * @see app/Http/Controllers/KewApprovalController.php:51
 * @route '/jobs/kew/{job}/approve'
 */
export const approve = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

approve.definition = {
    methods: ["post"],
    url: '/jobs/kew/{job}/approve',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\KewApprovalController::approve
 * @see app/Http/Controllers/KewApprovalController.php:51
 * @route '/jobs/kew/{job}/approve'
 */
approve.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return approve.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\KewApprovalController::approve
 * @see app/Http/Controllers/KewApprovalController.php:51
 * @route '/jobs/kew/{job}/approve'
 */
approve.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\KewApprovalController::approve
 * @see app/Http/Controllers/KewApprovalController.php:51
 * @route '/jobs/kew/{job}/approve'
 */
    const approveForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: approve.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\KewApprovalController::approve
 * @see app/Http/Controllers/KewApprovalController.php:51
 * @route '/jobs/kew/{job}/approve'
 */
        approveForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: approve.url(args, options),
            method: 'post',
        })
    
    approve.form = approveForm
/**
* @see \App\Http\Controllers\KewApprovalController::reject
 * @see app/Http/Controllers/KewApprovalController.php:77
 * @route '/jobs/kew/{job}/reject'
 */
export const reject = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

reject.definition = {
    methods: ["post"],
    url: '/jobs/kew/{job}/reject',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\KewApprovalController::reject
 * @see app/Http/Controllers/KewApprovalController.php:77
 * @route '/jobs/kew/{job}/reject'
 */
reject.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return reject.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\KewApprovalController::reject
 * @see app/Http/Controllers/KewApprovalController.php:77
 * @route '/jobs/kew/{job}/reject'
 */
reject.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\KewApprovalController::reject
 * @see app/Http/Controllers/KewApprovalController.php:77
 * @route '/jobs/kew/{job}/reject'
 */
    const rejectForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: reject.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\KewApprovalController::reject
 * @see app/Http/Controllers/KewApprovalController.php:77
 * @route '/jobs/kew/{job}/reject'
 */
        rejectForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: reject.url(args, options),
            method: 'post',
        })
    
    reject.form = rejectForm
/**
* @see \App\Http\Controllers\KewApprovalController::history
 * @see app/Http/Controllers/KewApprovalController.php:115
 * @route '/jobs/kew/{job}/history'
 */
export const history = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(args, options),
    method: 'get',
})

history.definition = {
    methods: ["get","head"],
    url: '/jobs/kew/{job}/history',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\KewApprovalController::history
 * @see app/Http/Controllers/KewApprovalController.php:115
 * @route '/jobs/kew/{job}/history'
 */
history.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return history.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\KewApprovalController::history
 * @see app/Http/Controllers/KewApprovalController.php:115
 * @route '/jobs/kew/{job}/history'
 */
history.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\KewApprovalController::history
 * @see app/Http/Controllers/KewApprovalController.php:115
 * @route '/jobs/kew/{job}/history'
 */
history.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: history.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\KewApprovalController::history
 * @see app/Http/Controllers/KewApprovalController.php:115
 * @route '/jobs/kew/{job}/history'
 */
    const historyForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: history.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\KewApprovalController::history
 * @see app/Http/Controllers/KewApprovalController.php:115
 * @route '/jobs/kew/{job}/history'
 */
        historyForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: history.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\KewApprovalController::history
 * @see app/Http/Controllers/KewApprovalController.php:115
 * @route '/jobs/kew/{job}/history'
 */
        historyForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: history.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    history.form = historyForm
/**
* @see \App\Http\Controllers\KewApprovalController::pending
 * @see app/Http/Controllers/KewApprovalController.php:33
 * @route '/jobs/kew/pending'
 */
export const pending = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending.url(options),
    method: 'get',
})

pending.definition = {
    methods: ["get","head"],
    url: '/jobs/kew/pending',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\KewApprovalController::pending
 * @see app/Http/Controllers/KewApprovalController.php:33
 * @route '/jobs/kew/pending'
 */
pending.url = (options?: RouteQueryOptions) => {
    return pending.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\KewApprovalController::pending
 * @see app/Http/Controllers/KewApprovalController.php:33
 * @route '/jobs/kew/pending'
 */
pending.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pending.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\KewApprovalController::pending
 * @see app/Http/Controllers/KewApprovalController.php:33
 * @route '/jobs/kew/pending'
 */
pending.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pending.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\KewApprovalController::pending
 * @see app/Http/Controllers/KewApprovalController.php:33
 * @route '/jobs/kew/pending'
 */
    const pendingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pending.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\KewApprovalController::pending
 * @see app/Http/Controllers/KewApprovalController.php:33
 * @route '/jobs/kew/pending'
 */
        pendingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\KewApprovalController::pending
 * @see app/Http/Controllers/KewApprovalController.php:33
 * @route '/jobs/kew/pending'
 */
        pendingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pending.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pending.form = pendingForm
const kew = {
    approve: Object.assign(approve, approve),
reject: Object.assign(reject, reject),
history: Object.assign(history, history),
pending: Object.assign(pending, pending),
}

export default kew