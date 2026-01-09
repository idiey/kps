import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::index
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:24
 * @route '/admin/templates/{template}/fields'
 */
export const index = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/fields',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::index
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:24
 * @route '/admin/templates/{template}/fields'
 */
index.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::index
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:24
 * @route '/admin/templates/{template}/fields'
 */
index.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::index
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:24
 * @route '/admin/templates/{template}/fields'
 */
index.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::index
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:24
 * @route '/admin/templates/{template}/fields'
 */
    const indexForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::index
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:24
 * @route '/admin/templates/{template}/fields'
 */
        indexForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::index
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:24
 * @route '/admin/templates/{template}/fields'
 */
        indexForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateFieldController::create
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:44
 * @route '/admin/templates/{template}/fields/create'
 */
export const create = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/fields/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::create
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:44
 * @route '/admin/templates/{template}/fields/create'
 */
create.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return create.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::create
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:44
 * @route '/admin/templates/{template}/fields/create'
 */
create.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::create
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:44
 * @route '/admin/templates/{template}/fields/create'
 */
create.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::create
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:44
 * @route '/admin/templates/{template}/fields/create'
 */
    const createForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::create
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:44
 * @route '/admin/templates/{template}/fields/create'
 */
        createForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::create
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:44
 * @route '/admin/templates/{template}/fields/create'
 */
        createForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateFieldController::store
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:59
 * @route '/admin/templates/{template}/fields'
 */
export const store = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/templates/{template}/fields',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::store
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:59
 * @route '/admin/templates/{template}/fields'
 */
store.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::store
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:59
 * @route '/admin/templates/{template}/fields'
 */
store.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::store
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:59
 * @route '/admin/templates/{template}/fields'
 */
    const storeForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::store
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:59
 * @route '/admin/templates/{template}/fields'
 */
        storeForm.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::show
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:107
 * @route '/admin/templates/{template}/fields/{field}'
 */
export const show = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/fields/{field}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::show
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:107
 * @route '/admin/templates/{template}/fields/{field}'
 */
show.url = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    field: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                field: typeof args.field === 'object'
                ? args.field.id
                : args.field,
                }

    return show.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{field}', parsedArgs.field.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::show
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:107
 * @route '/admin/templates/{template}/fields/{field}'
 */
show.get = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::show
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:107
 * @route '/admin/templates/{template}/fields/{field}'
 */
show.head = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::show
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:107
 * @route '/admin/templates/{template}/fields/{field}'
 */
    const showForm = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::show
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:107
 * @route '/admin/templates/{template}/fields/{field}'
 */
        showForm.get = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::show
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:107
 * @route '/admin/templates/{template}/fields/{field}'
 */
        showForm.head = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateFieldController::edit
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:125
 * @route '/admin/templates/{template}/fields/{field}/edit'
 */
export const edit = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/fields/{field}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::edit
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:125
 * @route '/admin/templates/{template}/fields/{field}/edit'
 */
edit.url = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    field: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                field: typeof args.field === 'object'
                ? args.field.id
                : args.field,
                }

    return edit.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{field}', parsedArgs.field.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::edit
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:125
 * @route '/admin/templates/{template}/fields/{field}/edit'
 */
edit.get = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::edit
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:125
 * @route '/admin/templates/{template}/fields/{field}/edit'
 */
edit.head = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::edit
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:125
 * @route '/admin/templates/{template}/fields/{field}/edit'
 */
    const editForm = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::edit
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:125
 * @route '/admin/templates/{template}/fields/{field}/edit'
 */
        editForm.get = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::edit
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:125
 * @route '/admin/templates/{template}/fields/{field}/edit'
 */
        editForm.head = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateFieldController::update
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:148
 * @route '/admin/templates/{template}/fields/{field}'
 */
export const update = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/templates/{template}/fields/{field}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::update
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:148
 * @route '/admin/templates/{template}/fields/{field}'
 */
update.url = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    field: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                field: typeof args.field === 'object'
                ? args.field.id
                : args.field,
                }

    return update.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{field}', parsedArgs.field.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::update
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:148
 * @route '/admin/templates/{template}/fields/{field}'
 */
update.put = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::update
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:148
 * @route '/admin/templates/{template}/fields/{field}'
 */
update.patch = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::update
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:148
 * @route '/admin/templates/{template}/fields/{field}'
 */
    const updateForm = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::update
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:148
 * @route '/admin/templates/{template}/fields/{field}'
 */
        updateForm.put = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::update
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:148
 * @route '/admin/templates/{template}/fields/{field}'
 */
        updateForm.patch = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateFieldController::destroy
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:199
 * @route '/admin/templates/{template}/fields/{field}'
 */
export const destroy = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/templates/{template}/fields/{field}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::destroy
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:199
 * @route '/admin/templates/{template}/fields/{field}'
 */
destroy.url = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    field: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                field: typeof args.field === 'object'
                ? args.field.id
                : args.field,
                }

    return destroy.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{field}', parsedArgs.field.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::destroy
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:199
 * @route '/admin/templates/{template}/fields/{field}'
 */
destroy.delete = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::destroy
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:199
 * @route '/admin/templates/{template}/fields/{field}'
 */
    const destroyForm = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::destroy
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:199
 * @route '/admin/templates/{template}/fields/{field}'
 */
        destroyForm.delete = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\TemplateFieldController::reorder
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:222
 * @route '/admin/templates/{template}/fields/reorder'
 */
export const reorder = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reorder.url(args, options),
    method: 'post',
})

reorder.definition = {
    methods: ["post"],
    url: '/admin/templates/{template}/fields/reorder',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::reorder
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:222
 * @route '/admin/templates/{template}/fields/reorder'
 */
reorder.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return reorder.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::reorder
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:222
 * @route '/admin/templates/{template}/fields/reorder'
 */
reorder.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reorder.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::reorder
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:222
 * @route '/admin/templates/{template}/fields/reorder'
 */
    const reorderForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: reorder.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::reorder
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:222
 * @route '/admin/templates/{template}/fields/reorder'
 */
        reorderForm.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: reorder.url(args, options),
            method: 'post',
        })
    
    reorder.form = reorderForm
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::duplicate
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:246
 * @route '/admin/templates/{template}/fields/{field}/duplicate'
 */
export const duplicate = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: duplicate.url(args, options),
    method: 'post',
})

duplicate.definition = {
    methods: ["post"],
    url: '/admin/templates/{template}/fields/{field}/duplicate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::duplicate
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:246
 * @route '/admin/templates/{template}/fields/{field}/duplicate'
 */
duplicate.url = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    field: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                field: typeof args.field === 'object'
                ? args.field.id
                : args.field,
                }

    return duplicate.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{field}', parsedArgs.field.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::duplicate
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:246
 * @route '/admin/templates/{template}/fields/{field}/duplicate'
 */
duplicate.post = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: duplicate.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::duplicate
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:246
 * @route '/admin/templates/{template}/fields/{field}/duplicate'
 */
    const duplicateForm = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: duplicate.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::duplicate
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:246
 * @route '/admin/templates/{template}/fields/{field}/duplicate'
 */
        duplicateForm.post = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: duplicate.url(args, options),
            method: 'post',
        })
    
    duplicate.form = duplicateForm
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::preview
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:266
 * @route '/admin/templates/{template}/fields/{field}/preview'
 */
export const preview = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(args, options),
    method: 'get',
})

preview.definition = {
    methods: ["get","head"],
    url: '/admin/templates/{template}/fields/{field}/preview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::preview
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:266
 * @route '/admin/templates/{template}/fields/{field}/preview'
 */
preview.url = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    field: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                field: typeof args.field === 'object'
                ? args.field.id
                : args.field,
                }

    return preview.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{field}', parsedArgs.field.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::preview
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:266
 * @route '/admin/templates/{template}/fields/{field}/preview'
 */
preview.get = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::preview
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:266
 * @route '/admin/templates/{template}/fields/{field}/preview'
 */
preview.head = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: preview.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::preview
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:266
 * @route '/admin/templates/{template}/fields/{field}/preview'
 */
    const previewForm = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: preview.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::preview
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:266
 * @route '/admin/templates/{template}/fields/{field}/preview'
 */
        previewForm.get = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: preview.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::preview
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:266
 * @route '/admin/templates/{template}/fields/{field}/preview'
 */
        previewForm.head = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: preview.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    preview.form = previewForm
/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::testFormula
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:283
 * @route '/admin/templates/{template}/fields/{field}/test-formula'
 */
export const testFormula = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: testFormula.url(args, options),
    method: 'post',
})

testFormula.definition = {
    methods: ["post"],
    url: '/admin/templates/{template}/fields/{field}/test-formula',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::testFormula
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:283
 * @route '/admin/templates/{template}/fields/{field}/test-formula'
 */
testFormula.url = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                    field: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                                field: typeof args.field === 'object'
                ? args.field.id
                : args.field,
                }

    return testFormula.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace('{field}', parsedArgs.field.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TemplateFieldController::testFormula
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:283
 * @route '/admin/templates/{template}/fields/{field}/test-formula'
 */
testFormula.post = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: testFormula.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::testFormula
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:283
 * @route '/admin/templates/{template}/fields/{field}/test-formula'
 */
    const testFormulaForm = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: testFormula.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TemplateFieldController::testFormula
 * @see app/Http/Controllers/Admin/TemplateFieldController.php:283
 * @route '/admin/templates/{template}/fields/{field}/test-formula'
 */
        testFormulaForm.post = (args: { template: number | { id: number }, field: number | { id: number } } | [template: number | { id: number }, field: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: testFormula.url(args, options),
            method: 'post',
        })
    
    testFormula.form = testFormulaForm
const fields = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
reorder: Object.assign(reorder, reorder),
duplicate: Object.assign(duplicate, duplicate),
preview: Object.assign(preview, preview),
testFormula: Object.assign(testFormula, testFormula),
}

export default fields