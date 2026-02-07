import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\WorkshopController::index
 * @see app/Http/Controllers/Admin/WorkshopController.php:30
 * @route '/admin/workshops'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/workshops',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::index
 * @see app/Http/Controllers/Admin/WorkshopController.php:30
 * @route '/admin/workshops'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::index
 * @see app/Http/Controllers/Admin/WorkshopController.php:30
 * @route '/admin/workshops'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopController::index
 * @see app/Http/Controllers/Admin/WorkshopController.php:30
 * @route '/admin/workshops'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::index
 * @see app/Http/Controllers/Admin/WorkshopController.php:30
 * @route '/admin/workshops'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::index
 * @see app/Http/Controllers/Admin/WorkshopController.php:30
 * @route '/admin/workshops'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopController::index
 * @see app/Http/Controllers/Admin/WorkshopController.php:30
 * @route '/admin/workshops'
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
* @see \App\Http\Controllers\Admin\WorkshopController::create
 * @see app/Http/Controllers/Admin/WorkshopController.php:86
 * @route '/admin/workshops/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/workshops/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::create
 * @see app/Http/Controllers/Admin/WorkshopController.php:86
 * @route '/admin/workshops/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::create
 * @see app/Http/Controllers/Admin/WorkshopController.php:86
 * @route '/admin/workshops/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopController::create
 * @see app/Http/Controllers/Admin/WorkshopController.php:86
 * @route '/admin/workshops/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::create
 * @see app/Http/Controllers/Admin/WorkshopController.php:86
 * @route '/admin/workshops/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::create
 * @see app/Http/Controllers/Admin/WorkshopController.php:86
 * @route '/admin/workshops/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopController::create
 * @see app/Http/Controllers/Admin/WorkshopController.php:86
 * @route '/admin/workshops/create'
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
* @see \App\Http\Controllers\Admin\WorkshopController::store
 * @see app/Http/Controllers/Admin/WorkshopController.php:98
 * @route '/admin/workshops'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/workshops',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::store
 * @see app/Http/Controllers/Admin/WorkshopController.php:98
 * @route '/admin/workshops'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::store
 * @see app/Http/Controllers/Admin/WorkshopController.php:98
 * @route '/admin/workshops'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::store
 * @see app/Http/Controllers/Admin/WorkshopController.php:98
 * @route '/admin/workshops'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::store
 * @see app/Http/Controllers/Admin/WorkshopController.php:98
 * @route '/admin/workshops'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\WorkshopController::show
 * @see app/Http/Controllers/Admin/WorkshopController.php:109
 * @route '/admin/workshops/{workshop}'
 */
export const show = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/workshops/{workshop}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::show
 * @see app/Http/Controllers/Admin/WorkshopController.php:109
 * @route '/admin/workshops/{workshop}'
 */
show.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workshop: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workshop: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                }

    return show.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::show
 * @see app/Http/Controllers/Admin/WorkshopController.php:109
 * @route '/admin/workshops/{workshop}'
 */
show.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopController::show
 * @see app/Http/Controllers/Admin/WorkshopController.php:109
 * @route '/admin/workshops/{workshop}'
 */
show.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::show
 * @see app/Http/Controllers/Admin/WorkshopController.php:109
 * @route '/admin/workshops/{workshop}'
 */
    const showForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::show
 * @see app/Http/Controllers/Admin/WorkshopController.php:109
 * @route '/admin/workshops/{workshop}'
 */
        showForm.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopController::show
 * @see app/Http/Controllers/Admin/WorkshopController.php:109
 * @route '/admin/workshops/{workshop}'
 */
        showForm.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkshopController::edit
 * @see app/Http/Controllers/Admin/WorkshopController.php:181
 * @route '/admin/workshops/{workshop}/edit'
 */
export const edit = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/workshops/{workshop}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::edit
 * @see app/Http/Controllers/Admin/WorkshopController.php:181
 * @route '/admin/workshops/{workshop}/edit'
 */
edit.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workshop: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workshop: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                }

    return edit.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::edit
 * @see app/Http/Controllers/Admin/WorkshopController.php:181
 * @route '/admin/workshops/{workshop}/edit'
 */
edit.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopController::edit
 * @see app/Http/Controllers/Admin/WorkshopController.php:181
 * @route '/admin/workshops/{workshop}/edit'
 */
edit.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::edit
 * @see app/Http/Controllers/Admin/WorkshopController.php:181
 * @route '/admin/workshops/{workshop}/edit'
 */
    const editForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::edit
 * @see app/Http/Controllers/Admin/WorkshopController.php:181
 * @route '/admin/workshops/{workshop}/edit'
 */
        editForm.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopController::edit
 * @see app/Http/Controllers/Admin/WorkshopController.php:181
 * @route '/admin/workshops/{workshop}/edit'
 */
        editForm.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkshopController::update
 * @see app/Http/Controllers/Admin/WorkshopController.php:196
 * @route '/admin/workshops/{workshop}'
 */
export const update = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/workshops/{workshop}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::update
 * @see app/Http/Controllers/Admin/WorkshopController.php:196
 * @route '/admin/workshops/{workshop}'
 */
update.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workshop: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workshop: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                }

    return update.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::update
 * @see app/Http/Controllers/Admin/WorkshopController.php:196
 * @route '/admin/workshops/{workshop}'
 */
update.put = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopController::update
 * @see app/Http/Controllers/Admin/WorkshopController.php:196
 * @route '/admin/workshops/{workshop}'
 */
update.patch = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::update
 * @see app/Http/Controllers/Admin/WorkshopController.php:196
 * @route '/admin/workshops/{workshop}'
 */
    const updateForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::update
 * @see app/Http/Controllers/Admin/WorkshopController.php:196
 * @route '/admin/workshops/{workshop}'
 */
        updateForm.put = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopController::update
 * @see app/Http/Controllers/Admin/WorkshopController.php:196
 * @route '/admin/workshops/{workshop}'
 */
        updateForm.patch = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkshopController::destroy
 * @see app/Http/Controllers/Admin/WorkshopController.php:207
 * @route '/admin/workshops/{workshop}'
 */
export const destroy = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/workshops/{workshop}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::destroy
 * @see app/Http/Controllers/Admin/WorkshopController.php:207
 * @route '/admin/workshops/{workshop}'
 */
destroy.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workshop: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workshop: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                }

    return destroy.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::destroy
 * @see app/Http/Controllers/Admin/WorkshopController.php:207
 * @route '/admin/workshops/{workshop}'
 */
destroy.delete = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::destroy
 * @see app/Http/Controllers/Admin/WorkshopController.php:207
 * @route '/admin/workshops/{workshop}'
 */
    const destroyForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::destroy
 * @see app/Http/Controllers/Admin/WorkshopController.php:207
 * @route '/admin/workshops/{workshop}'
 */
        destroyForm.delete = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkshopController::toggleStatus
 * @see app/Http/Controllers/Admin/WorkshopController.php:276
 * @route '/admin/workshops/{workshop}/toggle-status'
 */
export const toggleStatus = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleStatus.url(args, options),
    method: 'post',
})

toggleStatus.definition = {
    methods: ["post"],
    url: '/admin/workshops/{workshop}/toggle-status',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::toggleStatus
 * @see app/Http/Controllers/Admin/WorkshopController.php:276
 * @route '/admin/workshops/{workshop}/toggle-status'
 */
toggleStatus.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workshop: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workshop: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                }

    return toggleStatus.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::toggleStatus
 * @see app/Http/Controllers/Admin/WorkshopController.php:276
 * @route '/admin/workshops/{workshop}/toggle-status'
 */
toggleStatus.post = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleStatus.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::toggleStatus
 * @see app/Http/Controllers/Admin/WorkshopController.php:276
 * @route '/admin/workshops/{workshop}/toggle-status'
 */
    const toggleStatusForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggleStatus.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::toggleStatus
 * @see app/Http/Controllers/Admin/WorkshopController.php:276
 * @route '/admin/workshops/{workshop}/toggle-status'
 */
        toggleStatusForm.post = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: toggleStatus.url(args, options),
            method: 'post',
        })
    
    toggleStatus.form = toggleStatusForm
/**
* @see \App\Http\Controllers\Admin\WorkshopController::jobs
 * @see app/Http/Controllers/Admin/WorkshopController.php:227
 * @route '/admin/workshops/{workshop}/jobs'
 */
export const jobs = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: jobs.url(args, options),
    method: 'get',
})

jobs.definition = {
    methods: ["get","head"],
    url: '/admin/workshops/{workshop}/jobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopController::jobs
 * @see app/Http/Controllers/Admin/WorkshopController.php:227
 * @route '/admin/workshops/{workshop}/jobs'
 */
jobs.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workshop: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workshop: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                }

    return jobs.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopController::jobs
 * @see app/Http/Controllers/Admin/WorkshopController.php:227
 * @route '/admin/workshops/{workshop}/jobs'
 */
jobs.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: jobs.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopController::jobs
 * @see app/Http/Controllers/Admin/WorkshopController.php:227
 * @route '/admin/workshops/{workshop}/jobs'
 */
jobs.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: jobs.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopController::jobs
 * @see app/Http/Controllers/Admin/WorkshopController.php:227
 * @route '/admin/workshops/{workshop}/jobs'
 */
    const jobsForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: jobs.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopController::jobs
 * @see app/Http/Controllers/Admin/WorkshopController.php:227
 * @route '/admin/workshops/{workshop}/jobs'
 */
        jobsForm.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: jobs.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopController::jobs
 * @see app/Http/Controllers/Admin/WorkshopController.php:227
 * @route '/admin/workshops/{workshop}/jobs'
 */
        jobsForm.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: jobs.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    jobs.form = jobsForm
const WorkshopController = { index, create, store, show, edit, update, destroy, toggleStatus, jobs }

export default WorkshopController