import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import statuses from './statuses'
import transitions from './transitions'
/**
* @see \App\Http\Controllers\Admin\WorkflowController::index
 * @see app/Http/Controllers/Admin/WorkflowController.php:20
 * @route '/admin/workflows'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/workflows',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::index
 * @see app/Http/Controllers/Admin/WorkflowController.php:20
 * @route '/admin/workflows'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::index
 * @see app/Http/Controllers/Admin/WorkflowController.php:20
 * @route '/admin/workflows'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowController::index
 * @see app/Http/Controllers/Admin/WorkflowController.php:20
 * @route '/admin/workflows'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::index
 * @see app/Http/Controllers/Admin/WorkflowController.php:20
 * @route '/admin/workflows'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::index
 * @see app/Http/Controllers/Admin/WorkflowController.php:20
 * @route '/admin/workflows'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowController::index
 * @see app/Http/Controllers/Admin/WorkflowController.php:20
 * @route '/admin/workflows'
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
* @see \App\Http\Controllers\Admin\WorkflowController::create
 * @see app/Http/Controllers/Admin/WorkflowController.php:35
 * @route '/admin/workflows/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::create
 * @see app/Http/Controllers/Admin/WorkflowController.php:35
 * @route '/admin/workflows/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::create
 * @see app/Http/Controllers/Admin/WorkflowController.php:35
 * @route '/admin/workflows/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowController::create
 * @see app/Http/Controllers/Admin/WorkflowController.php:35
 * @route '/admin/workflows/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::create
 * @see app/Http/Controllers/Admin/WorkflowController.php:35
 * @route '/admin/workflows/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::create
 * @see app/Http/Controllers/Admin/WorkflowController.php:35
 * @route '/admin/workflows/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowController::create
 * @see app/Http/Controllers/Admin/WorkflowController.php:35
 * @route '/admin/workflows/create'
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
* @see \App\Http\Controllers\Admin\WorkflowController::store
 * @see app/Http/Controllers/Admin/WorkflowController.php:43
 * @route '/admin/workflows'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/workflows',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::store
 * @see app/Http/Controllers/Admin/WorkflowController.php:43
 * @route '/admin/workflows'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::store
 * @see app/Http/Controllers/Admin/WorkflowController.php:43
 * @route '/admin/workflows'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::store
 * @see app/Http/Controllers/Admin/WorkflowController.php:43
 * @route '/admin/workflows'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::store
 * @see app/Http/Controllers/Admin/WorkflowController.php:43
 * @route '/admin/workflows'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\WorkflowController::show
 * @see app/Http/Controllers/Admin/WorkflowController.php:65
 * @route '/admin/workflows/{workflow}'
 */
export const show = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::show
 * @see app/Http/Controllers/Admin/WorkflowController.php:65
 * @route '/admin/workflows/{workflow}'
 */
show.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::show
 * @see app/Http/Controllers/Admin/WorkflowController.php:65
 * @route '/admin/workflows/{workflow}'
 */
show.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowController::show
 * @see app/Http/Controllers/Admin/WorkflowController.php:65
 * @route '/admin/workflows/{workflow}'
 */
show.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::show
 * @see app/Http/Controllers/Admin/WorkflowController.php:65
 * @route '/admin/workflows/{workflow}'
 */
    const showForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::show
 * @see app/Http/Controllers/Admin/WorkflowController.php:65
 * @route '/admin/workflows/{workflow}'
 */
        showForm.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowController::show
 * @see app/Http/Controllers/Admin/WorkflowController.php:65
 * @route '/admin/workflows/{workflow}'
 */
        showForm.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowController::edit
 * @see app/Http/Controllers/Admin/WorkflowController.php:77
 * @route '/admin/workflows/{workflow}/edit'
 */
export const edit = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::edit
 * @see app/Http/Controllers/Admin/WorkflowController.php:77
 * @route '/admin/workflows/{workflow}/edit'
 */
edit.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::edit
 * @see app/Http/Controllers/Admin/WorkflowController.php:77
 * @route '/admin/workflows/{workflow}/edit'
 */
edit.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowController::edit
 * @see app/Http/Controllers/Admin/WorkflowController.php:77
 * @route '/admin/workflows/{workflow}/edit'
 */
edit.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::edit
 * @see app/Http/Controllers/Admin/WorkflowController.php:77
 * @route '/admin/workflows/{workflow}/edit'
 */
    const editForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::edit
 * @see app/Http/Controllers/Admin/WorkflowController.php:77
 * @route '/admin/workflows/{workflow}/edit'
 */
        editForm.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowController::edit
 * @see app/Http/Controllers/Admin/WorkflowController.php:77
 * @route '/admin/workflows/{workflow}/edit'
 */
        editForm.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowController::update
 * @see app/Http/Controllers/Admin/WorkflowController.php:89
 * @route '/admin/workflows/{workflow}'
 */
export const update = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/workflows/{workflow}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::update
 * @see app/Http/Controllers/Admin/WorkflowController.php:89
 * @route '/admin/workflows/{workflow}'
 */
update.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::update
 * @see app/Http/Controllers/Admin/WorkflowController.php:89
 * @route '/admin/workflows/{workflow}'
 */
update.put = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowController::update
 * @see app/Http/Controllers/Admin/WorkflowController.php:89
 * @route '/admin/workflows/{workflow}'
 */
update.patch = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::update
 * @see app/Http/Controllers/Admin/WorkflowController.php:89
 * @route '/admin/workflows/{workflow}'
 */
    const updateForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::update
 * @see app/Http/Controllers/Admin/WorkflowController.php:89
 * @route '/admin/workflows/{workflow}'
 */
        updateForm.put = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowController::update
 * @see app/Http/Controllers/Admin/WorkflowController.php:89
 * @route '/admin/workflows/{workflow}'
 */
        updateForm.patch = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowController::destroy
 * @see app/Http/Controllers/Admin/WorkflowController.php:110
 * @route '/admin/workflows/{workflow}'
 */
export const destroy = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/workflows/{workflow}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::destroy
 * @see app/Http/Controllers/Admin/WorkflowController.php:110
 * @route '/admin/workflows/{workflow}'
 */
destroy.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::destroy
 * @see app/Http/Controllers/Admin/WorkflowController.php:110
 * @route '/admin/workflows/{workflow}'
 */
destroy.delete = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::destroy
 * @see app/Http/Controllers/Admin/WorkflowController.php:110
 * @route '/admin/workflows/{workflow}'
 */
    const destroyForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::destroy
 * @see app/Http/Controllers/Admin/WorkflowController.php:110
 * @route '/admin/workflows/{workflow}'
 */
        destroyForm.delete = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkflowController::builder
 * @see app/Http/Controllers/Admin/WorkflowController.php:129
 * @route '/admin/workflows/{workflow}/builder'
 */
export const builder = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: builder.url(args, options),
    method: 'get',
})

builder.definition = {
    methods: ["get","head"],
    url: '/admin/workflows/{workflow}/builder',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::builder
 * @see app/Http/Controllers/Admin/WorkflowController.php:129
 * @route '/admin/workflows/{workflow}/builder'
 */
builder.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return builder.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::builder
 * @see app/Http/Controllers/Admin/WorkflowController.php:129
 * @route '/admin/workflows/{workflow}/builder'
 */
builder.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: builder.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkflowController::builder
 * @see app/Http/Controllers/Admin/WorkflowController.php:129
 * @route '/admin/workflows/{workflow}/builder'
 */
builder.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: builder.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::builder
 * @see app/Http/Controllers/Admin/WorkflowController.php:129
 * @route '/admin/workflows/{workflow}/builder'
 */
    const builderForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: builder.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::builder
 * @see app/Http/Controllers/Admin/WorkflowController.php:129
 * @route '/admin/workflows/{workflow}/builder'
 */
        builderForm.get = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: builder.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkflowController::builder
 * @see app/Http/Controllers/Admin/WorkflowController.php:129
 * @route '/admin/workflows/{workflow}/builder'
 */
        builderForm.head = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: builder.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    builder.form = builderForm
/**
* @see \App\Http\Controllers\Admin\WorkflowController::importMethod
 * @see app/Http/Controllers/Admin/WorkflowController.php:147
 * @route '/admin/workflows/{workflow}/import'
 */
export const importMethod = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(args, options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/admin/workflows/{workflow}/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkflowController::importMethod
 * @see app/Http/Controllers/Admin/WorkflowController.php:147
 * @route '/admin/workflows/{workflow}/import'
 */
importMethod.url = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return importMethod.definition.url
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkflowController::importMethod
 * @see app/Http/Controllers/Admin/WorkflowController.php:147
 * @route '/admin/workflows/{workflow}/import'
 */
importMethod.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkflowController::importMethod
 * @see app/Http/Controllers/Admin/WorkflowController.php:147
 * @route '/admin/workflows/{workflow}/import'
 */
    const importMethodForm = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkflowController::importMethod
 * @see app/Http/Controllers/Admin/WorkflowController.php:147
 * @route '/admin/workflows/{workflow}/import'
 */
        importMethodForm.post = (args: { workflow: number | { id: number } } | [workflow: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(args, options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
const workflows = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
builder: Object.assign(builder, builder),
import: Object.assign(importMethod, importMethod),
statuses: Object.assign(statuses, statuses),
transitions: Object.assign(transitions, transitions),
}

export default workflows