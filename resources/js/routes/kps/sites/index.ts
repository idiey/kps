import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\SiteController::index
 * @see app/Http/Controllers/Kps/SiteController.php:18
 * @route '/kps/sites'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/kps/sites',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\SiteController::index
 * @see app/Http/Controllers/Kps/SiteController.php:18
 * @route '/kps/sites'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\SiteController::index
 * @see app/Http/Controllers/Kps/SiteController.php:18
 * @route '/kps/sites'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\SiteController::index
 * @see app/Http/Controllers/Kps/SiteController.php:18
 * @route '/kps/sites'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\SiteController::index
 * @see app/Http/Controllers/Kps/SiteController.php:18
 * @route '/kps/sites'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\SiteController::index
 * @see app/Http/Controllers/Kps/SiteController.php:18
 * @route '/kps/sites'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\SiteController::index
 * @see app/Http/Controllers/Kps/SiteController.php:18
 * @route '/kps/sites'
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
* @see \App\Http\Controllers\Kps\SiteController::create
 * @see app/Http/Controllers/Kps/SiteController.php:31
 * @route '/kps/sites/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/kps/sites/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\SiteController::create
 * @see app/Http/Controllers/Kps/SiteController.php:31
 * @route '/kps/sites/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\SiteController::create
 * @see app/Http/Controllers/Kps/SiteController.php:31
 * @route '/kps/sites/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\SiteController::create
 * @see app/Http/Controllers/Kps/SiteController.php:31
 * @route '/kps/sites/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\SiteController::create
 * @see app/Http/Controllers/Kps/SiteController.php:31
 * @route '/kps/sites/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\SiteController::create
 * @see app/Http/Controllers/Kps/SiteController.php:31
 * @route '/kps/sites/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\SiteController::create
 * @see app/Http/Controllers/Kps/SiteController.php:31
 * @route '/kps/sites/create'
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
* @see \App\Http\Controllers\Kps\SiteController::store
 * @see app/Http/Controllers/Kps/SiteController.php:38
 * @route '/kps/sites'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/kps/sites',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\SiteController::store
 * @see app/Http/Controllers/Kps/SiteController.php:38
 * @route '/kps/sites'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\SiteController::store
 * @see app/Http/Controllers/Kps/SiteController.php:38
 * @route '/kps/sites'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\SiteController::store
 * @see app/Http/Controllers/Kps/SiteController.php:38
 * @route '/kps/sites'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\SiteController::store
 * @see app/Http/Controllers/Kps/SiteController.php:38
 * @route '/kps/sites'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Kps\SiteController::show
 * @see app/Http/Controllers/Kps/SiteController.php:46
 * @route '/kps/sites/{site}'
 */
export const show = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\SiteController::show
 * @see app/Http/Controllers/Kps/SiteController.php:46
 * @route '/kps/sites/{site}'
 */
show.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\SiteController::show
 * @see app/Http/Controllers/Kps/SiteController.php:46
 * @route '/kps/sites/{site}'
 */
show.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\SiteController::show
 * @see app/Http/Controllers/Kps/SiteController.php:46
 * @route '/kps/sites/{site}'
 */
show.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\SiteController::show
 * @see app/Http/Controllers/Kps/SiteController.php:46
 * @route '/kps/sites/{site}'
 */
    const showForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\SiteController::show
 * @see app/Http/Controllers/Kps/SiteController.php:46
 * @route '/kps/sites/{site}'
 */
        showForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\SiteController::show
 * @see app/Http/Controllers/Kps/SiteController.php:46
 * @route '/kps/sites/{site}'
 */
        showForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Kps\SiteController::edit
 * @see app/Http/Controllers/Kps/SiteController.php:63
 * @route '/kps/sites/{site}/edit'
 */
export const edit = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\SiteController::edit
 * @see app/Http/Controllers/Kps/SiteController.php:63
 * @route '/kps/sites/{site}/edit'
 */
edit.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\SiteController::edit
 * @see app/Http/Controllers/Kps/SiteController.php:63
 * @route '/kps/sites/{site}/edit'
 */
edit.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\SiteController::edit
 * @see app/Http/Controllers/Kps/SiteController.php:63
 * @route '/kps/sites/{site}/edit'
 */
edit.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\SiteController::edit
 * @see app/Http/Controllers/Kps/SiteController.php:63
 * @route '/kps/sites/{site}/edit'
 */
    const editForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\SiteController::edit
 * @see app/Http/Controllers/Kps/SiteController.php:63
 * @route '/kps/sites/{site}/edit'
 */
        editForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\SiteController::edit
 * @see app/Http/Controllers/Kps/SiteController.php:63
 * @route '/kps/sites/{site}/edit'
 */
        editForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Kps\SiteController::update
 * @see app/Http/Controllers/Kps/SiteController.php:72
 * @route '/kps/sites/{site}'
 */
export const update = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/kps/sites/{site}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Kps\SiteController::update
 * @see app/Http/Controllers/Kps/SiteController.php:72
 * @route '/kps/sites/{site}'
 */
update.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\SiteController::update
 * @see app/Http/Controllers/Kps/SiteController.php:72
 * @route '/kps/sites/{site}'
 */
update.put = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Kps\SiteController::update
 * @see app/Http/Controllers/Kps/SiteController.php:72
 * @route '/kps/sites/{site}'
 */
update.patch = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Kps\SiteController::update
 * @see app/Http/Controllers/Kps/SiteController.php:72
 * @route '/kps/sites/{site}'
 */
    const updateForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\SiteController::update
 * @see app/Http/Controllers/Kps/SiteController.php:72
 * @route '/kps/sites/{site}'
 */
        updateForm.put = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Kps\SiteController::update
 * @see app/Http/Controllers/Kps/SiteController.php:72
 * @route '/kps/sites/{site}'
 */
        updateForm.patch = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Kps\SiteController::destroy
 * @see app/Http/Controllers/Kps/SiteController.php:80
 * @route '/kps/sites/{site}'
 */
export const destroy = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/kps/sites/{site}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Kps\SiteController::destroy
 * @see app/Http/Controllers/Kps/SiteController.php:80
 * @route '/kps/sites/{site}'
 */
destroy.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\SiteController::destroy
 * @see app/Http/Controllers/Kps/SiteController.php:80
 * @route '/kps/sites/{site}'
 */
destroy.delete = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Kps\SiteController::destroy
 * @see app/Http/Controllers/Kps/SiteController.php:80
 * @route '/kps/sites/{site}'
 */
    const destroyForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\SiteController::destroy
 * @see app/Http/Controllers/Kps/SiteController.php:80
 * @route '/kps/sites/{site}'
 */
        destroyForm.delete = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const sites = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default sites