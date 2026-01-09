import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::index
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:19
 * @route '/admin/workflows/{workflow}/transitions'
 */
export const index = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/transitions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::index
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:19
 * @route '/admin/workflows/{workflow}/transitions'
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::index
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:19
 * @route '/admin/workflows/{workflow}/transitions'
 */
index.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::index
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:19
 * @route '/admin/workflows/{workflow}/transitions'
 */
index.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::index
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:19
 * @route '/admin/workflows/{workflow}/transitions'
 */
    const indexForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::index
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:19
 * @route '/admin/workflows/{workflow}/transitions'
 */
        indexForm.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::index
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:19
 * @route '/admin/workflows/{workflow}/transitions'
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::create
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:35
 * @route '/admin/workflows/{workflow}/transitions/create'
 */
export const create = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/transitions/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::create
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:35
 * @route '/admin/workflows/{workflow}/transitions/create'
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::create
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:35
 * @route '/admin/workflows/{workflow}/transitions/create'
 */
create.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::create
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:35
 * @route '/admin/workflows/{workflow}/transitions/create'
 */
create.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::create
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:35
 * @route '/admin/workflows/{workflow}/transitions/create'
 */
    const createForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::create
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:35
 * @route '/admin/workflows/{workflow}/transitions/create'
 */
        createForm.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::create
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:35
 * @route '/admin/workflows/{workflow}/transitions/create'
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::store
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:55
 * @route '/admin/workflows/{workflow}/transitions'
 */
export const store = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/workflows/{workflow}/transitions',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::store
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:55
 * @route '/admin/workflows/{workflow}/transitions'
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::store
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:55
 * @route '/admin/workflows/{workflow}/transitions'
 */
store.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::store
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:55
 * @route '/admin/workflows/{workflow}/transitions'
 */
    const storeForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::store
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:55
 * @route '/admin/workflows/{workflow}/transitions'
 */
        storeForm.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::show
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:108
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
export const show = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/transitions/{transition}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::show
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:108
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
show.url = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return show.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::show
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:108
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
show.get = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::show
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:108
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
show.head = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::show
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:108
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
    const showForm = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::show
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:108
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
        showForm.get = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::show
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:108
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
        showForm.head = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::edit
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:133
 * @route '/admin/workflows/{workflow}/transitions/{transition}/edit'
 */
export const edit = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/transitions/{transition}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::edit
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:133
 * @route '/admin/workflows/{workflow}/transitions/{transition}/edit'
 */
edit.url = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return edit.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::edit
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:133
 * @route '/admin/workflows/{workflow}/transitions/{transition}/edit'
 */
edit.get = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::edit
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:133
 * @route '/admin/workflows/{workflow}/transitions/{transition}/edit'
 */
edit.head = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::edit
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:133
 * @route '/admin/workflows/{workflow}/transitions/{transition}/edit'
 */
    const editForm = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::edit
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:133
 * @route '/admin/workflows/{workflow}/transitions/{transition}/edit'
 */
        editForm.get = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::edit
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:133
 * @route '/admin/workflows/{workflow}/transitions/{transition}/edit'
 */
        editForm.head = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::update
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:161
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
export const update = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/workflows/{workflow}/transitions/{transition}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::update
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:161
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
update.url = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return update.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::update
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:161
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
update.put = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::update
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:161
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
update.patch = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::update
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:161
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
    const updateForm = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::update
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:161
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
        updateForm.put = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::update
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:161
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
        updateForm.patch = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::destroy
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:217
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
export const destroy = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/workflows/{workflow}/transitions/{transition}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::destroy
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:217
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
destroy.url = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return destroy.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::destroy
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:217
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
destroy.delete = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::destroy
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:217
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
    const destroyForm = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::destroy
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:217
 * @route '/admin/workflows/{workflow}/transitions/{transition}'
 */
        destroyForm.delete = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::reorder
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:240
 * @route '/admin/workflows/{workflow}/transitions/reorder'
 */
export const reorder = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reorder.url(args, options),
    method: 'post',
})

reorder.definition = {
    methods: ["post"],
    url: '/admin/workflows/{workflow}/transitions/reorder',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::reorder
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:240
 * @route '/admin/workflows/{workflow}/transitions/reorder'
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
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::reorder
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:240
 * @route '/admin/workflows/{workflow}/transitions/reorder'
 */
reorder.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reorder.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::reorder
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:240
 * @route '/admin/workflows/{workflow}/transitions/reorder'
 */
    const reorderForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: reorder.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::reorder
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:240
 * @route '/admin/workflows/{workflow}/transitions/reorder'
 */
        reorderForm.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: reorder.url(args, options),
            method: 'post',
        })
    
    reorder.form = reorderForm
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateConditions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:260
 * @route '/admin/workflows/{workflow}/transitions/{transition}/conditions'
 */
export const updateConditions = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateConditions.url(args, options),
    method: 'patch',
})

updateConditions.definition = {
    methods: ["patch"],
    url: '/admin/workflows/{workflow}/transitions/{transition}/conditions',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateConditions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:260
 * @route '/admin/workflows/{workflow}/transitions/{transition}/conditions'
 */
updateConditions.url = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return updateConditions.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateConditions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:260
 * @route '/admin/workflows/{workflow}/transitions/{transition}/conditions'
 */
updateConditions.patch = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateConditions.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateConditions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:260
 * @route '/admin/workflows/{workflow}/transitions/{transition}/conditions'
 */
    const updateConditionsForm = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateConditions.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateConditions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:260
 * @route '/admin/workflows/{workflow}/transitions/{transition}/conditions'
 */
        updateConditionsForm.patch = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateConditions.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateConditions.form = updateConditionsForm
/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateActions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:279
 * @route '/admin/workflows/{workflow}/transitions/{transition}/actions'
 */
export const updateActions = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateActions.url(args, options),
    method: 'patch',
})

updateActions.definition = {
    methods: ["patch"],
    url: '/admin/workflows/{workflow}/transitions/{transition}/actions',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateActions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:279
 * @route '/admin/workflows/{workflow}/transitions/{transition}/actions'
 */
updateActions.url = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workflow: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workflow: typeof args.workflow === 'object'
                ? args.workflow.id
                : args.workflow,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return updateActions.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateActions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:279
 * @route '/admin/workflows/{workflow}/transitions/{transition}/actions'
 */
updateActions.patch = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateActions.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateActions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:279
 * @route '/admin/workflows/{workflow}/transitions/{transition}/actions'
 */
    const updateActionsForm = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateActions.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowTransitionController::updateActions
 * @see app/Http/Controllers/Admin/WorkflowTransitionController.php:279
 * @route '/admin/workflows/{workflow}/transitions/{transition}/actions'
 */
        updateActionsForm.patch = (args: { workflow: number | { id: number }, transition: number | { id: number } } | [workflow: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateActions.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateActions.form = updateActionsForm
const WorkflowTransitionController = { index, create, store, show, edit, update, destroy, reorder, updateConditions, updateActions }

export default WorkflowTransitionController