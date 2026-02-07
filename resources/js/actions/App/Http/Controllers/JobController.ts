import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\JobController::selectMode
 * @see app/Http/Controllers/JobController.php:69
 * @route '/jobs/select-mode'
 */
export const selectMode = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: selectMode.url(options),
    method: 'get',
})

selectMode.definition = {
    methods: ["get","head"],
    url: '/jobs/select-mode',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::selectMode
 * @see app/Http/Controllers/JobController.php:69
 * @route '/jobs/select-mode'
 */
selectMode.url = (options?: RouteQueryOptions) => {
    return selectMode.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::selectMode
 * @see app/Http/Controllers/JobController.php:69
 * @route '/jobs/select-mode'
 */
selectMode.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: selectMode.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::selectMode
 * @see app/Http/Controllers/JobController.php:69
 * @route '/jobs/select-mode'
 */
selectMode.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: selectMode.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::selectMode
 * @see app/Http/Controllers/JobController.php:69
 * @route '/jobs/select-mode'
 */
    const selectModeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: selectMode.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::selectMode
 * @see app/Http/Controllers/JobController.php:69
 * @route '/jobs/select-mode'
 */
        selectModeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: selectMode.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::selectMode
 * @see app/Http/Controllers/JobController.php:69
 * @route '/jobs/select-mode'
 */
        selectModeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: selectMode.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    selectMode.form = selectModeForm
/**
* @see \App\Http\Controllers\JobController::createKew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
export const createKew = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createKew.url(options),
    method: 'get',
})

createKew.definition = {
    methods: ["get","head"],
    url: '/jobs/create/kew',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::createKew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
createKew.url = (options?: RouteQueryOptions) => {
    return createKew.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::createKew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
createKew.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createKew.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::createKew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
createKew.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createKew.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::createKew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
    const createKewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createKew.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::createKew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
        createKewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createKew.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::createKew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
        createKewForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createKew.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    createKew.form = createKewForm
/**
* @see \App\Http\Controllers\JobController::createNormal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
export const createNormal = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createNormal.url(options),
    method: 'get',
})

createNormal.definition = {
    methods: ["get","head"],
    url: '/jobs/create/normal',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::createNormal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
createNormal.url = (options?: RouteQueryOptions) => {
    return createNormal.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::createNormal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
createNormal.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createNormal.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::createNormal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
createNormal.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createNormal.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::createNormal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
    const createNormalForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createNormal.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::createNormal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
        createNormalForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createNormal.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::createNormal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
        createNormalForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createNormal.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    createNormal.form = createNormalForm
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
 * @see app/Http/Controllers/JobController.php:57
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
 * @see app/Http/Controllers/JobController.php:57
 * @route '/jobs/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:57
 * @route '/jobs/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:57
 * @route '/jobs/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:57
 * @route '/jobs/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:57
 * @route '/jobs/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::create
 * @see app/Http/Controllers/JobController.php:57
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
 * @see app/Http/Controllers/JobController.php:118
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
 * @see app/Http/Controllers/JobController.php:118
 * @route '/jobs'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:118
 * @route '/jobs'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:118
 * @route '/jobs'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\JobController::store
 * @see app/Http/Controllers/JobController.php:118
 * @route '/jobs'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:152
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
 * @see app/Http/Controllers/JobController.php:152
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
 * @see app/Http/Controllers/JobController.php:152
 * @route '/jobs/{job}'
 */
show.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:152
 * @route '/jobs/{job}'
 */
show.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:152
 * @route '/jobs/{job}'
 */
    const showForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:152
 * @route '/jobs/{job}'
 */
        showForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::show
 * @see app/Http/Controllers/JobController.php:152
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
 * @see app/Http/Controllers/JobController.php:188
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
 * @see app/Http/Controllers/JobController.php:188
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
 * @see app/Http/Controllers/JobController.php:188
 * @route '/jobs/{job}/edit'
 */
edit.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:188
 * @route '/jobs/{job}/edit'
 */
edit.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:188
 * @route '/jobs/{job}/edit'
 */
    const editForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:188
 * @route '/jobs/{job}/edit'
 */
        editForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::edit
 * @see app/Http/Controllers/JobController.php:188
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
 * @see app/Http/Controllers/JobController.php:206
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
 * @see app/Http/Controllers/JobController.php:206
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
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}'
 */
update.put = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:206
 * @route '/jobs/{job}'
 */
update.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\JobController::update
 * @see app/Http/Controllers/JobController.php:206
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
 * @see app/Http/Controllers/JobController.php:206
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
 * @see app/Http/Controllers/JobController.php:206
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
 * @see app/Http/Controllers/JobController.php:217
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
 * @see app/Http/Controllers/JobController.php:217
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
 * @see app/Http/Controllers/JobController.php:217
 * @route '/jobs/{job}'
 */
destroy.delete = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\JobController::destroy
 * @see app/Http/Controllers/JobController.php:217
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
 * @see app/Http/Controllers/JobController.php:217
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
 * @see app/Http/Controllers/JobController.php:230
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
 * @see app/Http/Controllers/JobController.php:230
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
 * @see app/Http/Controllers/JobController.php:230
 * @route '/jobs/{job}/status'
 */
updateStatus.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\JobController::updateStatus
 * @see app/Http/Controllers/JobController.php:230
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
 * @see app/Http/Controllers/JobController.php:230
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
 * @see app/Http/Controllers/JobController.php:243
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
 * @see app/Http/Controllers/JobController.php:243
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
 * @see app/Http/Controllers/JobController.php:243
 * @route '/jobs/{job}/timeline'
 */
timeline.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: timeline.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:243
 * @route '/jobs/{job}/timeline'
 */
timeline.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: timeline.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:243
 * @route '/jobs/{job}/timeline'
 */
    const timelineForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: timeline.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:243
 * @route '/jobs/{job}/timeline'
 */
        timelineForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: timeline.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::timeline
 * @see app/Http/Controllers/JobController.php:243
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
const JobController = { selectMode, createKew, createNormal, index, create, store, show, edit, update, destroy, updateStatus, timeline }

export default JobController