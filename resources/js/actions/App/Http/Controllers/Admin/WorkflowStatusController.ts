import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::index
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:19
 * @route '/admin/workflows/{workflow}/statuses'
 */
export const index = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/statuses',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::index
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:19
 * @route '/admin/workflows/{workflow}/statuses'
 */
index.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workflow: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workflow: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                }

    return index.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::index
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:19
 * @route '/admin/workflows/{workflow}/statuses'
 */
index.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::index
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:19
 * @route '/admin/workflows/{workflow}/statuses'
 */
index.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::index
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:19
 * @route '/admin/workflows/{workflow}/statuses'
 */
    const indexForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::index
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:19
 * @route '/admin/workflows/{workflow}/statuses'
 */
        indexForm.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::index
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:19
 * @route '/admin/workflows/{workflow}/statuses'
 */
        indexForm.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowStatusController::create
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:34
 * @route '/admin/workflows/{workflow}/statuses/create'
 */
export const create = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/statuses/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::create
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:34
 * @route '/admin/workflows/{workflow}/statuses/create'
 */
create.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workflow: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workflow: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                }

    return create.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::create
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:34
 * @route '/admin/workflows/{workflow}/statuses/create'
 */
create.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::create
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:34
 * @route '/admin/workflows/{workflow}/statuses/create'
 */
create.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::create
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:34
 * @route '/admin/workflows/{workflow}/statuses/create'
 */
    const createForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::create
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:34
 * @route '/admin/workflows/{workflow}/statuses/create'
 */
        createForm.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::create
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:34
 * @route '/admin/workflows/{workflow}/statuses/create'
 */
        createForm.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowStatusController::store
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:47
 * @route '/admin/workflows/{workflow}/statuses'
 */
export const store = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/workflows/{workflow}/statuses',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::store
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:47
 * @route '/admin/workflows/{workflow}/statuses'
 */
store.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workflow: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workflow: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                }

    return store.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::store
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:47
 * @route '/admin/workflows/{workflow}/statuses'
 */
store.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::store
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:47
 * @route '/admin/workflows/{workflow}/statuses'
 */
    const storeForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::store
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:47
 * @route '/admin/workflows/{workflow}/statuses'
 */
        storeForm.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::show
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:88
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
export const show = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/statuses/{status}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::show
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:88
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
show.url = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    status: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                status: typeof args.status === 'object'
                ? args.status.id
                : args.status,
                }

    return show.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{status}', parsedArgs.status.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::show
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:88
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
show.get = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::show
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:88
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
show.head = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::show
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:88
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
    const showForm = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::show
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:88
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
        showForm.get = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::show
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:88
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
        showForm.head = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowStatusController::edit
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:106
 * @route '/admin/workflows/{workflow}/statuses/{status}/edit'
 */
export const edit = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/statuses/{status}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::edit
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:106
 * @route '/admin/workflows/{workflow}/statuses/{status}/edit'
 */
edit.url = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    status: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                status: typeof args.status === 'object'
                ? args.status.id
                : args.status,
                }

    return edit.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{status}', parsedArgs.status.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::edit
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:106
 * @route '/admin/workflows/{workflow}/statuses/{status}/edit'
 */
edit.get = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::edit
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:106
 * @route '/admin/workflows/{workflow}/statuses/{status}/edit'
 */
edit.head = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::edit
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:106
 * @route '/admin/workflows/{workflow}/statuses/{status}/edit'
 */
    const editForm = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::edit
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:106
 * @route '/admin/workflows/{workflow}/statuses/{status}/edit'
 */
        editForm.get = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::edit
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:106
 * @route '/admin/workflows/{workflow}/statuses/{status}/edit'
 */
        editForm.head = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowStatusController::update
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:125
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
export const update = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/workflows/{workflow}/statuses/{status}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::update
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:125
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
update.url = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    status: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                status: typeof args.status === 'object'
                ? args.status.id
                : args.status,
                }

    return update.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{status}', parsedArgs.status.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::update
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:125
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
update.put = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::update
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:125
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
update.patch = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::update
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:125
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
    const updateForm = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::update
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:125
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
        updateForm.put = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::update
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:125
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
        updateForm.patch = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowStatusController::destroy
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:171
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
export const destroy = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/workflows/{workflow}/statuses/{status}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::destroy
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:171
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
destroy.url = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    status: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                status: typeof args.status === 'object'
                ? args.status.id
                : args.status,
                }

    return destroy.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{status}', parsedArgs.status.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::destroy
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:171
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
destroy.delete = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::destroy
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:171
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
    const destroyForm = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::destroy
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:171
 * @route '/admin/workflows/{workflow}/statuses/{status}'
 */
        destroyForm.delete = (args: { workflow: number | { id: number }, status: number | { id: number } } | [workflow: number | { id: number }, status: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowStatusController::reorder
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:201
 * @route '/admin/workflows/{workflow}/statuses/reorder'
 */
export const reorder = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reorder.url(args, options),
    method: 'post',
})

reorder.definition = {
    methods: ["post"],
    url: '/admin/workflows/{workflow}/statuses/reorder',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::reorder
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:201
 * @route '/admin/workflows/{workflow}/statuses/reorder'
 */
reorder.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workflow: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workflow: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                }

    return reorder.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::reorder
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:201
 * @route '/admin/workflows/{workflow}/statuses/reorder'
 */
reorder.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reorder.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::reorder
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:201
 * @route '/admin/workflows/{workflow}/statuses/reorder'
 */
    const reorderForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: reorder.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowStatusController::reorder
 * @see app/Http/Controllers/Admin/WorkflowStatusController.php:201
 * @route '/admin/workflows/{workflow}/statuses/reorder'
 */
        reorderForm.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: reorder.url(args, options),
            method: 'post',
        })
    
    reorder.form = reorderForm
const WorkflowStatusController = { index, create, store, show, edit, update, destroy, reorder }

export default WorkflowStatusController