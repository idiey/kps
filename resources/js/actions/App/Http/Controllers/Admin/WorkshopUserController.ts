import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::index
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:26
 * @route '/admin/workshops/{workshop}/users'
 */
export const index = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/workshops/{workshop}/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::index
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:26
 * @route '/admin/workshops/{workshop}/users'
 */
index.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::index
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:26
 * @route '/admin/workshops/{workshop}/users'
 */
index.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::index
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:26
 * @route '/admin/workshops/{workshop}/users'
 */
index.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::index
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:26
 * @route '/admin/workshops/{workshop}/users'
 */
    const indexForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::index
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:26
 * @route '/admin/workshops/{workshop}/users'
 */
        indexForm.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::index
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:26
 * @route '/admin/workshops/{workshop}/users'
 */
        indexForm.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\WorkshopUserController::store
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:77
 * @route '/admin/workshops/{workshop}/users'
 */
export const store = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/workshops/{workshop}/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::store
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:77
 * @route '/admin/workshops/{workshop}/users'
 */
store.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::store
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:77
 * @route '/admin/workshops/{workshop}/users'
 */
store.post = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::store
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:77
 * @route '/admin/workshops/{workshop}/users'
 */
    const storeForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::store
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:77
 * @route '/admin/workshops/{workshop}/users'
 */
        storeForm.post = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::update
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:105
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
export const update = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/admin/workshops/{workshop}/users/{user}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::update
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:105
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
update.url = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                    user: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                                user: typeof args.user === 'object'
                ? args.user.id
                : args.user,
                }

    return update.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::update
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:105
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
update.patch = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::update
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:105
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
    const updateForm = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::update
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:105
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
        updateForm.patch = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\WorkshopUserController::destroy
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:123
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
export const destroy = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/workshops/{workshop}/users/{user}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::destroy
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:123
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
destroy.url = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                    user: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                                user: typeof args.user === 'object'
                ? args.user.id
                : args.user,
                }

    return destroy.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopUserController::destroy
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:123
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
destroy.delete = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::destroy
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:123
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
    const destroyForm = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopUserController::destroy
 * @see app/Http/Controllers/Admin/WorkshopUserController.php:123
 * @route '/admin/workshops/{workshop}/users/{user}'
 */
        destroyForm.delete = (args: { workshop: string | { id: string }, user: number | { id: number } } | [workshop: string | { id: string }, user: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const WorkshopUserController = { index, store, update, destroy }

export default WorkshopUserController