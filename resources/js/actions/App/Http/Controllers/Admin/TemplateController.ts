import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\TemplateController::index
 * @see app/Http/Controllers/Admin/TemplateController.php:20
 * @route '/admin/templates'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/templates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::index
 * @see app/Http/Controllers/Admin/TemplateController.php:20
 * @route '/admin/templates'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::index
 * @see app/Http/Controllers/Admin/TemplateController.php:20
 * @route '/admin/templates'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::index
 * @see app/Http/Controllers/Admin/TemplateController.php:20
 * @route '/admin/templates'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::index
 * @see app/Http/Controllers/Admin/TemplateController.php:20
 * @route '/admin/templates'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::index
 * @see app/Http/Controllers/Admin/TemplateController.php:20
 * @route '/admin/templates'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::index
 * @see app/Http/Controllers/Admin/TemplateController.php:20
 * @route '/admin/templates'
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
* @see \App\Http\Controllers\Admin\TemplateController::create
 * @see app/Http/Controllers/Admin/TemplateController.php:35
 * @route '/admin/templates/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/templates/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::create
 * @see app/Http/Controllers/Admin/TemplateController.php:35
 * @route '/admin/templates/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::create
 * @see app/Http/Controllers/Admin/TemplateController.php:35
 * @route '/admin/templates/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::create
 * @see app/Http/Controllers/Admin/TemplateController.php:35
 * @route '/admin/templates/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::create
 * @see app/Http/Controllers/Admin/TemplateController.php:35
 * @route '/admin/templates/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::create
 * @see app/Http/Controllers/Admin/TemplateController.php:35
 * @route '/admin/templates/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::create
 * @see app/Http/Controllers/Admin/TemplateController.php:35
 * @route '/admin/templates/create'
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
* @see \App\Http\Controllers\Admin\TemplateController::store
 * @see app/Http/Controllers/Admin/TemplateController.php:43
 * @route '/admin/templates'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/templates',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::store
 * @see app/Http/Controllers/Admin/TemplateController.php:43
 * @route '/admin/templates'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::store
 * @see app/Http/Controllers/Admin/TemplateController.php:43
 * @route '/admin/templates'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::store
 * @see app/Http/Controllers/Admin/TemplateController.php:43
 * @route '/admin/templates'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::store
 * @see app/Http/Controllers/Admin/TemplateController.php:43
 * @route '/admin/templates'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\TemplateController::show
 * @see app/Http/Controllers/Admin/TemplateController.php:66
 * @route '/admin/templates/{template}'
 */
export const show = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::show
 * @see app/Http/Controllers/Admin/TemplateController.php:66
 * @route '/admin/templates/{template}'
 */
show.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::show
 * @see app/Http/Controllers/Admin/TemplateController.php:66
 * @route '/admin/templates/{template}'
 */
show.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::show
 * @see app/Http/Controllers/Admin/TemplateController.php:66
 * @route '/admin/templates/{template}'
 */
show.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::show
 * @see app/Http/Controllers/Admin/TemplateController.php:66
 * @route '/admin/templates/{template}'
 */
    const showForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::show
 * @see app/Http/Controllers/Admin/TemplateController.php:66
 * @route '/admin/templates/{template}'
 */
        showForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::show
 * @see app/Http/Controllers/Admin/TemplateController.php:66
 * @route '/admin/templates/{template}'
 */
        showForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateController::edit
 * @see app/Http/Controllers/Admin/TemplateController.php:78
 * @route '/admin/templates/{template}/edit'
 */
export const edit = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::edit
 * @see app/Http/Controllers/Admin/TemplateController.php:78
 * @route '/admin/templates/{template}/edit'
 */
edit.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::edit
 * @see app/Http/Controllers/Admin/TemplateController.php:78
 * @route '/admin/templates/{template}/edit'
 */
edit.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::edit
 * @see app/Http/Controllers/Admin/TemplateController.php:78
 * @route '/admin/templates/{template}/edit'
 */
edit.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::edit
 * @see app/Http/Controllers/Admin/TemplateController.php:78
 * @route '/admin/templates/{template}/edit'
 */
    const editForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::edit
 * @see app/Http/Controllers/Admin/TemplateController.php:78
 * @route '/admin/templates/{template}/edit'
 */
        editForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::edit
 * @see app/Http/Controllers/Admin/TemplateController.php:78
 * @route '/admin/templates/{template}/edit'
 */
        editForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateController::update
 * @see app/Http/Controllers/Admin/TemplateController.php:90
 * @route '/admin/templates/{template}'
 */
export const update = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/templates/{template}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::update
 * @see app/Http/Controllers/Admin/TemplateController.php:90
 * @route '/admin/templates/{template}'
 */
update.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::update
 * @see app/Http/Controllers/Admin/TemplateController.php:90
 * @route '/admin/templates/{template}'
 */
update.put = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::update
 * @see app/Http/Controllers/Admin/TemplateController.php:90
 * @route '/admin/templates/{template}'
 */
update.patch = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::update
 * @see app/Http/Controllers/Admin/TemplateController.php:90
 * @route '/admin/templates/{template}'
 */
    const updateForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::update
 * @see app/Http/Controllers/Admin/TemplateController.php:90
 * @route '/admin/templates/{template}'
 */
        updateForm.put = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::update
 * @see app/Http/Controllers/Admin/TemplateController.php:90
 * @route '/admin/templates/{template}'
 */
        updateForm.patch = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateController::destroy
 * @see app/Http/Controllers/Admin/TemplateController.php:113
 * @route '/admin/templates/{template}'
 */
export const destroy = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/templates/{template}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::destroy
 * @see app/Http/Controllers/Admin/TemplateController.php:113
 * @route '/admin/templates/{template}'
 */
destroy.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::destroy
 * @see app/Http/Controllers/Admin/TemplateController.php:113
 * @route '/admin/templates/{template}'
 */
destroy.delete = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::destroy
 * @see app/Http/Controllers/Admin/TemplateController.php:113
 * @route '/admin/templates/{template}'
 */
    const destroyForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::destroy
 * @see app/Http/Controllers/Admin/TemplateController.php:113
 * @route '/admin/templates/{template}'
 */
        destroyForm.delete = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateController::getWorkflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
export const getWorkflows = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getWorkflows.url(args, options),
    method: 'get',
})

getWorkflows.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/workflows',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::getWorkflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
getWorkflows.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return getWorkflows.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::getWorkflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
getWorkflows.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getWorkflows.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::getWorkflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
getWorkflows.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getWorkflows.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::getWorkflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
    const getWorkflowsForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getWorkflows.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::getWorkflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
        getWorkflowsForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getWorkflows.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::getWorkflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
        getWorkflowsForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getWorkflows.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getWorkflows.form = getWorkflowsForm
/**
* @see \App\Http\Controllers\Admin\TemplateController::getSchema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
export const getSchema = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getSchema.url(args, options),
    method: 'get',
})

getSchema.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/schema',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::getSchema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
getSchema.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return getSchema.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::getSchema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
getSchema.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getSchema.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::getSchema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
getSchema.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getSchema.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::getSchema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
    const getSchemaForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getSchema.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::getSchema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
        getSchemaForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getSchema.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::getSchema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
        getSchemaForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getSchema.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getSchema.form = getSchemaForm
/**
* @see \App\Http\Controllers\Admin\TemplateController::attachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:149
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
export const attachWorkflow = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: attachWorkflow.url(args, options),
    method: 'post',
})

attachWorkflow.definition = {
    methods: ["post"],
    url: '/admin/templates/{template}/workflows/{workflow}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::attachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:149
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
attachWorkflow.url = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    workflow: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                workflow: args.workflow,
                }

    return attachWorkflow.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::attachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:149
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
attachWorkflow.post = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: attachWorkflow.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::attachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:149
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
    const attachWorkflowForm = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: attachWorkflow.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::attachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:149
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
        attachWorkflowForm.post = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: attachWorkflow.url(args, options),
            method: 'post',
        })
    
    attachWorkflow.form = attachWorkflowForm
/**
* @see \App\Http\Controllers\Admin\TemplateController::detachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:166
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
export const detachWorkflow = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: detachWorkflow.url(args, options),
    method: 'delete',
})

detachWorkflow.definition = {
    methods: ["delete"],
    url: '/admin/templates/{template}/workflows/{workflow}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::detachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:166
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
detachWorkflow.url = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    workflow: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                workflow: args.workflow,
                }

    return detachWorkflow.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{workflow}', parsedArgs.workflow.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::detachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:166
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
detachWorkflow.delete = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: detachWorkflow.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::detachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:166
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
    const detachWorkflowForm = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: detachWorkflow.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::detachWorkflow
 * @see app/Http/Controllers/Admin/TemplateController.php:166
 * @route '/admin/templates/{template}/workflows/{workflow}'
 */
        detachWorkflowForm.delete = (args: { template: number | { id: number }, workflow: string | number } | [template: number | { id: number }, workflow: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: detachWorkflow.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    detachWorkflow.form = detachWorkflowForm
const TemplateController = { index, create, store, show, edit, update, destroy, getWorkflows, getSchema, attachWorkflow, detachWorkflow }

export default TemplateController