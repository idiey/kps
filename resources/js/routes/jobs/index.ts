import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import notes from './notes'
import photos from './photos'
/**
* @see \App\Http\Controllers\JobController::index
 * @see app/Http/Controllers/JobController.php:34
 * @route '/jobs'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/jobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::index
 * @see app/Http/Controllers/JobController.php:34
 * @route '/jobs'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::index
 * @see app/Http/Controllers/JobController.php:34
 * @route '/jobs'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::index
 * @see app/Http/Controllers/JobController.php:34
 * @route '/jobs'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::index
 * @see app/Http/Controllers/JobController.php:34
 * @route '/jobs'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::index
 * @see app/Http/Controllers/JobController.php:34
 * @route '/jobs'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::index
 * @see app/Http/Controllers/JobController.php:34
 * @route '/jobs'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:56
 * @route '/jobs/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/jobs/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:56
 * @route '/jobs/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:56
 * @route '/jobs/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:56
 * @route '/jobs/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:56
 * @route '/jobs/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:56
 * @route '/jobs/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:56
 * @route '/jobs/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:77
 * @route '/jobs'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/jobs',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:77
 * @route '/jobs'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:77
 * @route '/jobs'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:77
 * @route '/jobs'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:77
 * @route '/jobs'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:115
 * @route '/jobs/{job}'
 */
export const show = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:115
 * @route '/jobs/{job}'
 */
show.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:115
 * @route '/jobs/{job}'
 */
show.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:115
 * @route '/jobs/{job}'
 */
show.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:115
 * @route '/jobs/{job}'
 */
    const showForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:115
 * @route '/jobs/{job}'
 */
        showForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:115
 * @route '/jobs/{job}'
 */
        showForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:151
 * @route '/jobs/{job}/edit'
 */
export const edit = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:151
 * @route '/jobs/{job}/edit'
 */
edit.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:151
 * @route '/jobs/{job}/edit'
 */
edit.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:151
 * @route '/jobs/{job}/edit'
 */
edit.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:151
 * @route '/jobs/{job}/edit'
 */
    const editForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:151
 * @route '/jobs/{job}/edit'
 */
        editForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:151
 * @route '/jobs/{job}/edit'
 */
        editForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:169
 * @route '/jobs/{job}'
 */
export const update = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/jobs/{job}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:169
 * @route '/jobs/{job}'
 */
update.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:169
 * @route '/jobs/{job}'
 */
update.put = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:169
 * @route '/jobs/{job}'
 */
update.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:169
 * @route '/jobs/{job}'
 */
    const updateForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:169
 * @route '/jobs/{job}'
 */
        updateForm.put = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:169
 * @route '/jobs/{job}'
 */
        updateForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\JobController::destroy
 * @see app/Http/Controllers/JobController.php:180
 * @route '/jobs/{job}'
 */
export const destroy = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/jobs/{job}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\JobController::destroy
 * @see app/Http/Controllers/JobController.php:180
 * @route '/jobs/{job}'
 */
destroy.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::destroy
 * @see app/Http/Controllers/JobController.php:180
 * @route '/jobs/{job}'
 */
destroy.delete = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\JobController::destroy
 * @see app/Http/Controllers/JobController.php:180
 * @route '/jobs/{job}'
 */
    const destroyForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobController::destroy
 * @see app/Http/Controllers/JobController.php:180
 * @route '/jobs/{job}'
 */
        destroyForm.delete = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\JobController::updateStatus
 * @see app/Http/Controllers/JobController.php:193
 * @route '/jobs/{job}/status'
 */
export const updateStatus = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

updateStatus.definition = {
    methods: ["patch"],
    url: '/jobs/{job}/status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\JobController::updateStatus
 * @see app/Http/Controllers/JobController.php:193
 * @route '/jobs/{job}/status'
 */
updateStatus.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return updateStatus.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::updateStatus
 * @see app/Http/Controllers/JobController.php:193
 * @route '/jobs/{job}/status'
 */
updateStatus.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\JobController::updateStatus
 * @see app/Http/Controllers/JobController.php:193
 * @route '/jobs/{job}/status'
 */
    const updateStatusForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateStatus.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobController::updateStatus
 * @see app/Http/Controllers/JobController.php:193
 * @route '/jobs/{job}/status'
 */
        updateStatusForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateStatus.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateStatus.form = updateStatusForm
/**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}/timeline'
 */
export const timeline = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: timeline.url(args, options),
    method: 'get',
})

timeline.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/timeline',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}/timeline'
 */
timeline.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return timeline.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}/timeline'
 */
timeline.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: timeline.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}/timeline'
 */
timeline.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: timeline.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}/timeline'
 */
    const timelineForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: timeline.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}/timeline'
 */
        timelineForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: timeline.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}/timeline'
 */
        timelineForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: timeline.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    timeline.form = timelineForm
/**
* @see \App\Http\Controllers\JobAssignmentController::assign
 * @see app/Http/Controllers/JobAssignmentController.php:23
 * @route '/jobs/{job}/assign'
 */
export const assign = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assign.url(args, options),
    method: 'post',
})

assign.definition = {
    methods: ["post"],
    url: '/jobs/{job}/assign',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\JobAssignmentController::assign
 * @see app/Http/Controllers/JobAssignmentController.php:23
 * @route '/jobs/{job}/assign'
 */
assign.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return assign.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobAssignmentController::assign
 * @see app/Http/Controllers/JobAssignmentController.php:23
 * @route '/jobs/{job}/assign'
 */
assign.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assign.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\JobAssignmentController::assign
 * @see app/Http/Controllers/JobAssignmentController.php:23
 * @route '/jobs/{job}/assign'
 */
    const assignForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: assign.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobAssignmentController::assign
 * @see app/Http/Controllers/JobAssignmentController.php:23
 * @route '/jobs/{job}/assign'
 */
        assignForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: assign.url(args, options),
            method: 'post',
        })
    
    assign.form = assignForm
/**
* @see \App\Http\Controllers\JobAssignmentController::assignmentHistory
 * @see app/Http/Controllers/JobAssignmentController.php:50
 * @route '/jobs/{job}/assignment-history'
 */
export const assignmentHistory = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assignmentHistory.url(args, options),
    method: 'get',
})

assignmentHistory.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/assignment-history',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobAssignmentController::assignmentHistory
 * @see app/Http/Controllers/JobAssignmentController.php:50
 * @route '/jobs/{job}/assignment-history'
 */
assignmentHistory.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return assignmentHistory.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobAssignmentController::assignmentHistory
 * @see app/Http/Controllers/JobAssignmentController.php:50
 * @route '/jobs/{job}/assignment-history'
 */
assignmentHistory.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assignmentHistory.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobAssignmentController::assignmentHistory
 * @see app/Http/Controllers/JobAssignmentController.php:50
 * @route '/jobs/{job}/assignment-history'
 */
assignmentHistory.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assignmentHistory.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobAssignmentController::assignmentHistory
 * @see app/Http/Controllers/JobAssignmentController.php:50
 * @route '/jobs/{job}/assignment-history'
 */
    const assignmentHistoryForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: assignmentHistory.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobAssignmentController::assignmentHistory
 * @see app/Http/Controllers/JobAssignmentController.php:50
 * @route '/jobs/{job}/assignment-history'
 */
        assignmentHistoryForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: assignmentHistory.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobAssignmentController::assignmentHistory
 * @see app/Http/Controllers/JobAssignmentController.php:50
 * @route '/jobs/{job}/assignment-history'
 */
        assignmentHistoryForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: assignmentHistory.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    assignmentHistory.form = assignmentHistoryForm
/**
* @see \App\Http\Controllers\DynamicJobController::selectTemplate
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
export const selectTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: selectTemplate.url(options),
    method: 'get',
})

selectTemplate.definition = {
    methods: ["get","head"],
    url: '/jobs/templates/select',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::selectTemplate
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
selectTemplate.url = (options?: RouteQueryOptions) => {
    return selectTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::selectTemplate
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
selectTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: selectTemplate.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::selectTemplate
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
selectTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: selectTemplate.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::selectTemplate
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
    const selectTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: selectTemplate.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::selectTemplate
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
        selectTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: selectTemplate.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::selectTemplate
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
        selectTemplateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: selectTemplate.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    selectTemplate.form = selectTemplateForm
/**
* @see \App\Http\Controllers\DynamicJobController::createDynamic
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
export const createDynamic = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createDynamic.url(args, options),
    method: 'get',
})

createDynamic.definition = {
    methods: ["get","head"],
    url: '/jobs/create/{template}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::createDynamic
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
createDynamic.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                }

    return createDynamic.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::createDynamic
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
createDynamic.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createDynamic.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::createDynamic
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
createDynamic.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createDynamic.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::createDynamic
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
    const createDynamicForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createDynamic.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::createDynamic
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
        createDynamicForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createDynamic.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::createDynamic
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
        createDynamicForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createDynamic.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    createDynamic.form = createDynamicForm
/**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
export const executeTransition = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: executeTransition.url(args, options),
    method: 'post',
})

executeTransition.definition = {
    methods: ["post"],
    url: '/jobs/{job}/transitions/{transition}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
executeTransition.url = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return executeTransition.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
executeTransition.post = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: executeTransition.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
    const executeTransitionForm = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: executeTransition.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
        executeTransitionForm.post = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: executeTransition.url(args, options),
            method: 'post',
        })
    
    executeTransition.form = executeTransitionForm
const jobs = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
updateStatus: Object.assign(updateStatus, updateStatus),
timeline: Object.assign(timeline, timeline),
assign: Object.assign(assign, assign),
assignmentHistory: Object.assign(assignmentHistory, assignmentHistory),
notes: Object.assign(notes, notes),
photos: Object.assign(photos, photos),
selectTemplate: Object.assign(selectTemplate, selectTemplate),
createDynamic: Object.assign(createDynamic, createDynamic),
executeTransition: Object.assign(executeTransition, executeTransition),
}

export default jobs