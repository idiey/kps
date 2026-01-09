import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\JobNoteController::index
 * @see app/Http/Controllers/JobNoteController.php:25
 * @route '/jobs/{job}/notes'
 */
export const index = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/notes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobNoteController::index
 * @see app/Http/Controllers/JobNoteController.php:25
 * @route '/jobs/{job}/notes'
 */
index.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobNoteController::index
 * @see app/Http/Controllers/JobNoteController.php:25
 * @route '/jobs/{job}/notes'
 */
index.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobNoteController::index
 * @see app/Http/Controllers/JobNoteController.php:25
 * @route '/jobs/{job}/notes'
 */
index.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobNoteController::index
 * @see app/Http/Controllers/JobNoteController.php:25
 * @route '/jobs/{job}/notes'
 */
    const indexForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobNoteController::index
 * @see app/Http/Controllers/JobNoteController.php:25
 * @route '/jobs/{job}/notes'
 */
        indexForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobNoteController::index
 * @see app/Http/Controllers/JobNoteController.php:25
 * @route '/jobs/{job}/notes'
 */
        indexForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\JobNoteController::store
 * @see app/Http/Controllers/JobNoteController.php:59
 * @route '/jobs/{job}/notes'
 */
export const store = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/jobs/{job}/notes',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\JobNoteController::store
 * @see app/Http/Controllers/JobNoteController.php:59
 * @route '/jobs/{job}/notes'
 */
store.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobNoteController::store
 * @see app/Http/Controllers/JobNoteController.php:59
 * @route '/jobs/{job}/notes'
 */
store.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\JobNoteController::store
 * @see app/Http/Controllers/JobNoteController.php:59
 * @route '/jobs/{job}/notes'
 */
    const storeForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobNoteController::store
 * @see app/Http/Controllers/JobNoteController.php:59
 * @route '/jobs/{job}/notes'
 */
        storeForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\JobNoteController::update
 * @see app/Http/Controllers/JobNoteController.php:77
 * @route '/jobs/{job}/notes/{note}'
 */
export const update = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/jobs/{job}/notes/{note}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\JobNoteController::update
 * @see app/Http/Controllers/JobNoteController.php:77
 * @route '/jobs/{job}/notes/{note}'
 */
update.url = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                    note: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                                note: typeof args.note === 'object'
                ? args.note.id
                : args.note,
                }

    return update.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace('{note}', parsedArgs.note.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobNoteController::update
 * @see app/Http/Controllers/JobNoteController.php:77
 * @route '/jobs/{job}/notes/{note}'
 */
update.put = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\JobNoteController::update
 * @see app/Http/Controllers/JobNoteController.php:77
 * @route '/jobs/{job}/notes/{note}'
 */
    const updateForm = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobNoteController::update
 * @see app/Http/Controllers/JobNoteController.php:77
 * @route '/jobs/{job}/notes/{note}'
 */
        updateForm.put = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\JobNoteController::destroy
 * @see app/Http/Controllers/JobNoteController.php:90
 * @route '/jobs/{job}/notes/{note}'
 */
export const destroy = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/jobs/{job}/notes/{note}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\JobNoteController::destroy
 * @see app/Http/Controllers/JobNoteController.php:90
 * @route '/jobs/{job}/notes/{note}'
 */
destroy.url = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                    note: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                                note: typeof args.note === 'object'
                ? args.note.id
                : args.note,
                }

    return destroy.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace('{note}', parsedArgs.note.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobNoteController::destroy
 * @see app/Http/Controllers/JobNoteController.php:90
 * @route '/jobs/{job}/notes/{note}'
 */
destroy.delete = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\JobNoteController::destroy
 * @see app/Http/Controllers/JobNoteController.php:90
 * @route '/jobs/{job}/notes/{note}'
 */
    const destroyForm = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobNoteController::destroy
 * @see app/Http/Controllers/JobNoteController.php:90
 * @route '/jobs/{job}/notes/{note}'
 */
        destroyForm.delete = (args: { job: number | { id: number }, note: number | { id: number } } | [job: number | { id: number }, note: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const JobNoteController = { index, store, update, destroy }

export default JobNoteController