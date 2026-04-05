import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\PenerokaController::index
 * @see app/Http/Controllers/Kps/PenerokaController.php:19
 * @route '/kps/sites/{site}/peneroka'
 */
export const index = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/peneroka',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\PenerokaController::index
 * @see app/Http/Controllers/Kps/PenerokaController.php:19
 * @route '/kps/sites/{site}/peneroka'
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
* @see \App\Http\Controllers\Kps\PenerokaController::index
 * @see app/Http/Controllers/Kps/PenerokaController.php:19
 * @route '/kps/sites/{site}/peneroka'
 */
index.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\PenerokaController::index
 * @see app/Http/Controllers/Kps/PenerokaController.php:19
 * @route '/kps/sites/{site}/peneroka'
 */
index.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\PenerokaController::index
 * @see app/Http/Controllers/Kps/PenerokaController.php:19
 * @route '/kps/sites/{site}/peneroka'
 */
    const indexForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\PenerokaController::index
 * @see app/Http/Controllers/Kps/PenerokaController.php:19
 * @route '/kps/sites/{site}/peneroka'
 */
        indexForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\PenerokaController::index
 * @see app/Http/Controllers/Kps/PenerokaController.php:19
 * @route '/kps/sites/{site}/peneroka'
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
* @see \App\Http\Controllers\Kps\PenerokaController::create
 * @see app/Http/Controllers/Kps/PenerokaController.php:60
 * @route '/kps/sites/{site}/peneroka/create'
 */
export const create = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/peneroka/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\PenerokaController::create
 * @see app/Http/Controllers/Kps/PenerokaController.php:60
 * @route '/kps/sites/{site}/peneroka/create'
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
* @see \App\Http\Controllers\Kps\PenerokaController::create
 * @see app/Http/Controllers/Kps/PenerokaController.php:60
 * @route '/kps/sites/{site}/peneroka/create'
 */
create.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\PenerokaController::create
 * @see app/Http/Controllers/Kps/PenerokaController.php:60
 * @route '/kps/sites/{site}/peneroka/create'
 */
create.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\PenerokaController::create
 * @see app/Http/Controllers/Kps/PenerokaController.php:60
 * @route '/kps/sites/{site}/peneroka/create'
 */
    const createForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\PenerokaController::create
 * @see app/Http/Controllers/Kps/PenerokaController.php:60
 * @route '/kps/sites/{site}/peneroka/create'
 */
        createForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\PenerokaController::create
 * @see app/Http/Controllers/Kps/PenerokaController.php:60
 * @route '/kps/sites/{site}/peneroka/create'
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
* @see \App\Http\Controllers\Kps\PenerokaController::store
 * @see app/Http/Controllers/Kps/PenerokaController.php:72
 * @route '/kps/sites/{site}/peneroka'
 */
export const store = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/kps/sites/{site}/peneroka',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\PenerokaController::store
 * @see app/Http/Controllers/Kps/PenerokaController.php:72
 * @route '/kps/sites/{site}/peneroka'
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
* @see \App\Http\Controllers\Kps\PenerokaController::store
 * @see app/Http/Controllers/Kps/PenerokaController.php:72
 * @route '/kps/sites/{site}/peneroka'
 */
store.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\PenerokaController::store
 * @see app/Http/Controllers/Kps/PenerokaController.php:72
 * @route '/kps/sites/{site}/peneroka'
 */
    const storeForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\PenerokaController::store
 * @see app/Http/Controllers/Kps/PenerokaController.php:72
 * @route '/kps/sites/{site}/peneroka'
 */
        storeForm.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Kps\PenerokaController::edit
 * @see app/Http/Controllers/Kps/PenerokaController.php:83
 * @route '/kps/sites/{site}/peneroka/{peneroka}/edit'
 */
export const edit = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/peneroka/{peneroka}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\PenerokaController::edit
 * @see app/Http/Controllers/Kps/PenerokaController.php:83
 * @route '/kps/sites/{site}/peneroka/{peneroka}/edit'
 */
edit.url = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    peneroka: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                peneroka: typeof args.peneroka === 'object'
                ? args.peneroka.id
                : args.peneroka,
                }

    return edit.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{peneroka}', parsedArgs.peneroka.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\PenerokaController::edit
 * @see app/Http/Controllers/Kps/PenerokaController.php:83
 * @route '/kps/sites/{site}/peneroka/{peneroka}/edit'
 */
edit.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\PenerokaController::edit
 * @see app/Http/Controllers/Kps/PenerokaController.php:83
 * @route '/kps/sites/{site}/peneroka/{peneroka}/edit'
 */
edit.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\PenerokaController::edit
 * @see app/Http/Controllers/Kps/PenerokaController.php:83
 * @route '/kps/sites/{site}/peneroka/{peneroka}/edit'
 */
    const editForm = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\PenerokaController::edit
 * @see app/Http/Controllers/Kps/PenerokaController.php:83
 * @route '/kps/sites/{site}/peneroka/{peneroka}/edit'
 */
        editForm.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\PenerokaController::edit
 * @see app/Http/Controllers/Kps/PenerokaController.php:83
 * @route '/kps/sites/{site}/peneroka/{peneroka}/edit'
 */
        editForm.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Kps\PenerokaController::update
 * @see app/Http/Controllers/Kps/PenerokaController.php:100
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
export const update = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/kps/sites/{site}/peneroka/{peneroka}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Kps\PenerokaController::update
 * @see app/Http/Controllers/Kps/PenerokaController.php:100
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
update.url = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    peneroka: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                peneroka: typeof args.peneroka === 'object'
                ? args.peneroka.id
                : args.peneroka,
                }

    return update.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{peneroka}', parsedArgs.peneroka.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\PenerokaController::update
 * @see app/Http/Controllers/Kps/PenerokaController.php:100
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
update.put = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\Kps\PenerokaController::update
 * @see app/Http/Controllers/Kps/PenerokaController.php:100
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
    const updateForm = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\PenerokaController::update
 * @see app/Http/Controllers/Kps/PenerokaController.php:100
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
        updateForm.put = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Kps\PenerokaController::destroy
 * @see app/Http/Controllers/Kps/PenerokaController.php:115
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
export const destroy = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/kps/sites/{site}/peneroka/{peneroka}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Kps\PenerokaController::destroy
 * @see app/Http/Controllers/Kps/PenerokaController.php:115
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
destroy.url = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    peneroka: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                peneroka: typeof args.peneroka === 'object'
                ? args.peneroka.id
                : args.peneroka,
                }

    return destroy.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{peneroka}', parsedArgs.peneroka.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\PenerokaController::destroy
 * @see app/Http/Controllers/Kps/PenerokaController.php:115
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
destroy.delete = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Kps\PenerokaController::destroy
 * @see app/Http/Controllers/Kps/PenerokaController.php:115
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
    const destroyForm = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\PenerokaController::destroy
 * @see app/Http/Controllers/Kps/PenerokaController.php:115
 * @route '/kps/sites/{site}/peneroka/{peneroka}'
 */
        destroyForm.delete = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const peneroka = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default peneroka