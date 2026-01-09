import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\InspectionController::create
 * @see app/Http/Controllers/InspectionController.php:65
 * @route '/jobs/{job}/inspections/create'
 */
export const create = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/inspections/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InspectionController::create
 * @see app/Http/Controllers/InspectionController.php:65
 * @route '/jobs/{job}/inspections/create'
 */
create.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return create.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::create
 * @see app/Http/Controllers/InspectionController.php:65
 * @route '/jobs/{job}/inspections/create'
 */
create.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InspectionController::create
 * @see app/Http/Controllers/InspectionController.php:65
 * @route '/jobs/{job}/inspections/create'
 */
create.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\InspectionController::create
 * @see app/Http/Controllers/InspectionController.php:65
 * @route '/jobs/{job}/inspections/create'
 */
    const createForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\InspectionController::create
 * @see app/Http/Controllers/InspectionController.php:65
 * @route '/jobs/{job}/inspections/create'
 */
        createForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\InspectionController::create
 * @see app/Http/Controllers/InspectionController.php:65
 * @route '/jobs/{job}/inspections/create'
 */
        createForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\InspectionController::store
 * @see app/Http/Controllers/InspectionController.php:78
 * @route '/jobs/{job}/inspections'
 */
export const store = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/jobs/{job}/inspections',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InspectionController::store
 * @see app/Http/Controllers/InspectionController.php:78
 * @route '/jobs/{job}/inspections'
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
* @see \App\Http\Controllers\InspectionController::store
 * @see app/Http/Controllers/InspectionController.php:78
 * @route '/jobs/{job}/inspections'
 */
store.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\InspectionController::store
 * @see app/Http/Controllers/InspectionController.php:78
 * @route '/jobs/{job}/inspections'
 */
    const storeForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\InspectionController::store
 * @see app/Http/Controllers/InspectionController.php:78
 * @route '/jobs/{job}/inspections'
 */
        storeForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\InspectionController::index
 * @see app/Http/Controllers/InspectionController.php:28
 * @route '/inspections'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/inspections',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InspectionController::index
 * @see app/Http/Controllers/InspectionController.php:28
 * @route '/inspections'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::index
 * @see app/Http/Controllers/InspectionController.php:28
 * @route '/inspections'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InspectionController::index
 * @see app/Http/Controllers/InspectionController.php:28
 * @route '/inspections'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\InspectionController::index
 * @see app/Http/Controllers/InspectionController.php:28
 * @route '/inspections'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\InspectionController::index
 * @see app/Http/Controllers/InspectionController.php:28
 * @route '/inspections'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\InspectionController::index
 * @see app/Http/Controllers/InspectionController.php:28
 * @route '/inspections'
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
* @see \App\Http\Controllers\InspectionController::show
 * @see app/Http/Controllers/InspectionController.php:93
 * @route '/inspections/{inspection}'
 */
export const show = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/inspections/{inspection}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InspectionController::show
 * @see app/Http/Controllers/InspectionController.php:93
 * @route '/inspections/{inspection}'
 */
show.url = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { inspection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { inspection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    inspection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        inspection: typeof args.inspection === 'object'
                ? args.inspection.id
                : args.inspection,
                }

    return show.definition.url
            .replace('{inspection}', parsedArgs.inspection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::show
 * @see app/Http/Controllers/InspectionController.php:93
 * @route '/inspections/{inspection}'
 */
show.get = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InspectionController::show
 * @see app/Http/Controllers/InspectionController.php:93
 * @route '/inspections/{inspection}'
 */
show.head = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\InspectionController::show
 * @see app/Http/Controllers/InspectionController.php:93
 * @route '/inspections/{inspection}'
 */
    const showForm = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\InspectionController::show
 * @see app/Http/Controllers/InspectionController.php:93
 * @route '/inspections/{inspection}'
 */
        showForm.get = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\InspectionController::show
 * @see app/Http/Controllers/InspectionController.php:93
 * @route '/inspections/{inspection}'
 */
        showForm.head = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\InspectionController::edit
 * @see app/Http/Controllers/InspectionController.php:116
 * @route '/inspections/{inspection}/edit'
 */
export const edit = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/inspections/{inspection}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InspectionController::edit
 * @see app/Http/Controllers/InspectionController.php:116
 * @route '/inspections/{inspection}/edit'
 */
edit.url = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { inspection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { inspection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    inspection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        inspection: typeof args.inspection === 'object'
                ? args.inspection.id
                : args.inspection,
                }

    return edit.definition.url
            .replace('{inspection}', parsedArgs.inspection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::edit
 * @see app/Http/Controllers/InspectionController.php:116
 * @route '/inspections/{inspection}/edit'
 */
edit.get = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InspectionController::edit
 * @see app/Http/Controllers/InspectionController.php:116
 * @route '/inspections/{inspection}/edit'
 */
edit.head = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\InspectionController::edit
 * @see app/Http/Controllers/InspectionController.php:116
 * @route '/inspections/{inspection}/edit'
 */
    const editForm = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\InspectionController::edit
 * @see app/Http/Controllers/InspectionController.php:116
 * @route '/inspections/{inspection}/edit'
 */
        editForm.get = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\InspectionController::edit
 * @see app/Http/Controllers/InspectionController.php:116
 * @route '/inspections/{inspection}/edit'
 */
        editForm.head = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\InspectionController::update
 * @see app/Http/Controllers/InspectionController.php:131
 * @route '/inspections/{inspection}'
 */
export const update = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/inspections/{inspection}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InspectionController::update
 * @see app/Http/Controllers/InspectionController.php:131
 * @route '/inspections/{inspection}'
 */
update.url = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { inspection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { inspection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    inspection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        inspection: typeof args.inspection === 'object'
                ? args.inspection.id
                : args.inspection,
                }

    return update.definition.url
            .replace('{inspection}', parsedArgs.inspection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::update
 * @see app/Http/Controllers/InspectionController.php:131
 * @route '/inspections/{inspection}'
 */
update.put = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\InspectionController::update
 * @see app/Http/Controllers/InspectionController.php:131
 * @route '/inspections/{inspection}'
 */
update.patch = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\InspectionController::update
 * @see app/Http/Controllers/InspectionController.php:131
 * @route '/inspections/{inspection}'
 */
    const updateForm = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\InspectionController::update
 * @see app/Http/Controllers/InspectionController.php:131
 * @route '/inspections/{inspection}'
 */
        updateForm.put = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\InspectionController::update
 * @see app/Http/Controllers/InspectionController.php:131
 * @route '/inspections/{inspection}'
 */
        updateForm.patch = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InspectionController::destroy
 * @see app/Http/Controllers/InspectionController.php:0
 * @route '/inspections/{inspection}'
 */
export const destroy = (args: { inspection: string | number } | [inspection: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/inspections/{inspection}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InspectionController::destroy
 * @see app/Http/Controllers/InspectionController.php:0
 * @route '/inspections/{inspection}'
 */
destroy.url = (args: { inspection: string | number } | [inspection: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { inspection: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    inspection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        inspection: args.inspection,
                }

    return destroy.definition.url
            .replace('{inspection}', parsedArgs.inspection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::destroy
 * @see app/Http/Controllers/InspectionController.php:0
 * @route '/inspections/{inspection}'
 */
destroy.delete = (args: { inspection: string | number } | [inspection: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\InspectionController::destroy
 * @see app/Http/Controllers/InspectionController.php:0
 * @route '/inspections/{inspection}'
 */
    const destroyForm = (args: { inspection: string | number } | [inspection: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\InspectionController::destroy
 * @see app/Http/Controllers/InspectionController.php:0
 * @route '/inspections/{inspection}'
 */
        destroyForm.delete = (args: { inspection: string | number } | [inspection: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InspectionController::approve
 * @see app/Http/Controllers/InspectionController.php:142
 * @route '/inspections/{inspection}/approve'
 */
export const approve = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

approve.definition = {
    methods: ["post"],
    url: '/inspections/{inspection}/approve',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InspectionController::approve
 * @see app/Http/Controllers/InspectionController.php:142
 * @route '/inspections/{inspection}/approve'
 */
approve.url = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { inspection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { inspection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    inspection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        inspection: typeof args.inspection === 'object'
                ? args.inspection.id
                : args.inspection,
                }

    return approve.definition.url
            .replace('{inspection}', parsedArgs.inspection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::approve
 * @see app/Http/Controllers/InspectionController.php:142
 * @route '/inspections/{inspection}/approve'
 */
approve.post = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\InspectionController::approve
 * @see app/Http/Controllers/InspectionController.php:142
 * @route '/inspections/{inspection}/approve'
 */
    const approveForm = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: approve.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\InspectionController::approve
 * @see app/Http/Controllers/InspectionController.php:142
 * @route '/inspections/{inspection}/approve'
 */
        approveForm.post = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: approve.url(args, options),
            method: 'post',
        })
    
    approve.form = approveForm
/**
* @see \App\Http\Controllers\InspectionController::approveConditions
 * @see app/Http/Controllers/InspectionController.php:163
 * @route '/inspections/{inspection}/approve-with-conditions'
 */
export const approveConditions = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approveConditions.url(args, options),
    method: 'post',
})

approveConditions.definition = {
    methods: ["post"],
    url: '/inspections/{inspection}/approve-with-conditions',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InspectionController::approveConditions
 * @see app/Http/Controllers/InspectionController.php:163
 * @route '/inspections/{inspection}/approve-with-conditions'
 */
approveConditions.url = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { inspection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { inspection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    inspection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        inspection: typeof args.inspection === 'object'
                ? args.inspection.id
                : args.inspection,
                }

    return approveConditions.definition.url
            .replace('{inspection}', parsedArgs.inspection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::approveConditions
 * @see app/Http/Controllers/InspectionController.php:163
 * @route '/inspections/{inspection}/approve-with-conditions'
 */
approveConditions.post = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approveConditions.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\InspectionController::approveConditions
 * @see app/Http/Controllers/InspectionController.php:163
 * @route '/inspections/{inspection}/approve-with-conditions'
 */
    const approveConditionsForm = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: approveConditions.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\InspectionController::approveConditions
 * @see app/Http/Controllers/InspectionController.php:163
 * @route '/inspections/{inspection}/approve-with-conditions'
 */
        approveConditionsForm.post = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: approveConditions.url(args, options),
            method: 'post',
        })
    
    approveConditions.form = approveConditionsForm
/**
* @see \App\Http\Controllers\InspectionController::reject
 * @see app/Http/Controllers/InspectionController.php:191
 * @route '/inspections/{inspection}/reject'
 */
export const reject = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

reject.definition = {
    methods: ["post"],
    url: '/inspections/{inspection}/reject',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InspectionController::reject
 * @see app/Http/Controllers/InspectionController.php:191
 * @route '/inspections/{inspection}/reject'
 */
reject.url = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { inspection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { inspection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    inspection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        inspection: typeof args.inspection === 'object'
                ? args.inspection.id
                : args.inspection,
                }

    return reject.definition.url
            .replace('{inspection}', parsedArgs.inspection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InspectionController::reject
 * @see app/Http/Controllers/InspectionController.php:191
 * @route '/inspections/{inspection}/reject'
 */
reject.post = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\InspectionController::reject
 * @see app/Http/Controllers/InspectionController.php:191
 * @route '/inspections/{inspection}/reject'
 */
    const rejectForm = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: reject.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\InspectionController::reject
 * @see app/Http/Controllers/InspectionController.php:191
 * @route '/inspections/{inspection}/reject'
 */
        rejectForm.post = (args: { inspection: number | { id: number } } | [inspection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: reject.url(args, options),
            method: 'post',
        })
    
    reject.form = rejectForm
const inspections = {
    create: Object.assign(create, create),
store: Object.assign(store, store),
index: Object.assign(index, index),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
approve: Object.assign(approve, approve),
approveConditions: Object.assign(approveConditions, approveConditions),
reject: Object.assign(reject, reject),
}

export default inspections