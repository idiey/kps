import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\DebtController::index
 * @see app/Http/Controllers/Kps/DebtController.php:20
 * @route '/kps/sites/{site}/hutang'
 */
export const index = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/hutang',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\DebtController::index
 * @see app/Http/Controllers/Kps/DebtController.php:20
 * @route '/kps/sites/{site}/hutang'
 */
index.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { site: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { site: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                }

    return index.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\DebtController::index
 * @see app/Http/Controllers/Kps/DebtController.php:20
 * @route '/kps/sites/{site}/hutang'
 */
index.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\DebtController::index
 * @see app/Http/Controllers/Kps/DebtController.php:20
 * @route '/kps/sites/{site}/hutang'
 */
index.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\DebtController::index
 * @see app/Http/Controllers/Kps/DebtController.php:20
 * @route '/kps/sites/{site}/hutang'
 */
    const indexForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\DebtController::index
 * @see app/Http/Controllers/Kps/DebtController.php:20
 * @route '/kps/sites/{site}/hutang'
 */
        indexForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\DebtController::index
 * @see app/Http/Controllers/Kps/DebtController.php:20
 * @route '/kps/sites/{site}/hutang'
 */
        indexForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Kps\DebtController::create
 * @see app/Http/Controllers/Kps/DebtController.php:63
 * @route '/kps/sites/{site}/hutang/create'
 */
export const create = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/hutang/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\DebtController::create
 * @see app/Http/Controllers/Kps/DebtController.php:63
 * @route '/kps/sites/{site}/hutang/create'
 */
create.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { site: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { site: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                }

    return create.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\DebtController::create
 * @see app/Http/Controllers/Kps/DebtController.php:63
 * @route '/kps/sites/{site}/hutang/create'
 */
create.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\DebtController::create
 * @see app/Http/Controllers/Kps/DebtController.php:63
 * @route '/kps/sites/{site}/hutang/create'
 */
create.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\DebtController::create
 * @see app/Http/Controllers/Kps/DebtController.php:63
 * @route '/kps/sites/{site}/hutang/create'
 */
    const createForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\DebtController::create
 * @see app/Http/Controllers/Kps/DebtController.php:63
 * @route '/kps/sites/{site}/hutang/create'
 */
        createForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\DebtController::create
 * @see app/Http/Controllers/Kps/DebtController.php:63
 * @route '/kps/sites/{site}/hutang/create'
 */
        createForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Kps\DebtController::store
 * @see app/Http/Controllers/Kps/DebtController.php:80
 * @route '/kps/sites/{site}/hutang'
 */
export const store = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/kps/sites/{site}/hutang',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\DebtController::store
 * @see app/Http/Controllers/Kps/DebtController.php:80
 * @route '/kps/sites/{site}/hutang'
 */
store.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { site: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { site: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                }

    return store.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\DebtController::store
 * @see app/Http/Controllers/Kps/DebtController.php:80
 * @route '/kps/sites/{site}/hutang'
 */
store.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\DebtController::store
 * @see app/Http/Controllers/Kps/DebtController.php:80
 * @route '/kps/sites/{site}/hutang'
 */
    const storeForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\DebtController::store
 * @see app/Http/Controllers/Kps/DebtController.php:80
 * @route '/kps/sites/{site}/hutang'
 */
        storeForm.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Kps\DebtController::edit
 * @see app/Http/Controllers/Kps/DebtController.php:96
 * @route '/kps/sites/{site}/hutang/{debt}/edit'
 */
export const edit = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/hutang/{debt}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\DebtController::edit
 * @see app/Http/Controllers/Kps/DebtController.php:96
 * @route '/kps/sites/{site}/hutang/{debt}/edit'
 */
edit.url = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    debt: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                debt: typeof args.debt === 'object'
                ? args.debt.id
                : args.debt,
                }

    return edit.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{debt}', parsedArgs.debt.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\DebtController::edit
 * @see app/Http/Controllers/Kps/DebtController.php:96
 * @route '/kps/sites/{site}/hutang/{debt}/edit'
 */
edit.get = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\DebtController::edit
 * @see app/Http/Controllers/Kps/DebtController.php:96
 * @route '/kps/sites/{site}/hutang/{debt}/edit'
 */
edit.head = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\DebtController::edit
 * @see app/Http/Controllers/Kps/DebtController.php:96
 * @route '/kps/sites/{site}/hutang/{debt}/edit'
 */
    const editForm = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\DebtController::edit
 * @see app/Http/Controllers/Kps/DebtController.php:96
 * @route '/kps/sites/{site}/hutang/{debt}/edit'
 */
        editForm.get = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\DebtController::edit
 * @see app/Http/Controllers/Kps/DebtController.php:96
 * @route '/kps/sites/{site}/hutang/{debt}/edit'
 */
        editForm.head = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Kps\DebtController::update
 * @see app/Http/Controllers/Kps/DebtController.php:113
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
export const update = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/kps/sites/{site}/hutang/{debt}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Kps\DebtController::update
 * @see app/Http/Controllers/Kps/DebtController.php:113
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
update.url = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    debt: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                debt: typeof args.debt === 'object'
                ? args.debt.id
                : args.debt,
                }

    return update.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{debt}', parsedArgs.debt.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\DebtController::update
 * @see app/Http/Controllers/Kps/DebtController.php:113
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
update.put = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\Kps\DebtController::update
 * @see app/Http/Controllers/Kps/DebtController.php:113
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
    const updateForm = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\DebtController::update
 * @see app/Http/Controllers/Kps/DebtController.php:113
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
        updateForm.put = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Kps\DebtController::destroy
 * @see app/Http/Controllers/Kps/DebtController.php:127
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
export const destroy = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/kps/sites/{site}/hutang/{debt}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Kps\DebtController::destroy
 * @see app/Http/Controllers/Kps/DebtController.php:127
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
destroy.url = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    debt: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                debt: typeof args.debt === 'object'
                ? args.debt.id
                : args.debt,
                }

    return destroy.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{debt}', parsedArgs.debt.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\DebtController::destroy
 * @see app/Http/Controllers/Kps/DebtController.php:127
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
destroy.delete = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Kps\DebtController::destroy
 * @see app/Http/Controllers/Kps/DebtController.php:127
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
    const destroyForm = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\DebtController::destroy
 * @see app/Http/Controllers/Kps/DebtController.php:127
 * @route '/kps/sites/{site}/hutang/{debt}'
 */
        destroyForm.delete = (args: { site: string | { id: string }, debt: string | { id: string } } | [site: string | { id: string }, debt: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const hutang = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default hutang