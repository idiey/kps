import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import fields from './fields'
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
* @see \App\Http\Controllers\Admin\TemplateController::workflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
export const workflows = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: workflows.url(args, options),
    method: 'get',
})

workflows.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/workflows',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::workflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
workflows.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return workflows.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::workflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
workflows.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: workflows.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::workflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
workflows.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: workflows.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::workflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
    const workflowsForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: workflows.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::workflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
        workflowsForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: workflows.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::workflows
 * @see app/Http/Controllers/Admin/TemplateController.php:129
 * @route '/admin/templates/{template}/workflows'
 */
        workflowsForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: workflows.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    workflows.form = workflowsForm
/**
* @see \App\Http\Controllers\Admin\TemplateController::schema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
export const schema = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schema.url(args, options),
    method: 'get',
})

schema.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/schema',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateController::schema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
schema.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return schema.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateController::schema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
schema.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schema.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateController::schema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
schema.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: schema.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateController::schema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
    const schemaForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: schema.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateController::schema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
        schemaForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: schema.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateController::schema
 * @see app/Http/Controllers/Admin/TemplateController.php:139
 * @route '/admin/templates/{template}/schema'
 */
        schemaForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: schema.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    schema.form = schemaForm
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
const templates = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
workflows: Object.assign(workflows, workflows),
schema: Object.assign(schema, schema),
attachWorkflow: Object.assign(attachWorkflow, attachWorkflow),
detachWorkflow: Object.assign(detachWorkflow, detachWorkflow),
fields: Object.assign(fields, fields),
}

export default templates