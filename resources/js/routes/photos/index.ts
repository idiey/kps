import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\PhotoController::destroy
 * @see app/Http/Controllers/PhotoController.php:102
 * @route '/photos/{photo}'
 */
export const destroy = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/photos/{photo}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\PhotoController::destroy
 * @see app/Http/Controllers/PhotoController.php:102
 * @route '/photos/{photo}'
 */
destroy.url = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { photo: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { photo: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    photo: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        photo: typeof args.photo === 'object'
                ? args.photo.id
                : args.photo,
                }

    return destroy.definition.url
            .replace('{photo}', parsedArgs.photo.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PhotoController::destroy
 * @see app/Http/Controllers/PhotoController.php:102
 * @route '/photos/{photo}'
 */
destroy.delete = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\PhotoController::destroy
 * @see app/Http/Controllers/PhotoController.php:102
 * @route '/photos/{photo}'
 */
    const destroyForm = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PhotoController::destroy
 * @see app/Http/Controllers/PhotoController.php:102
 * @route '/photos/{photo}'
 */
        destroyForm.delete = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\PhotoController::togglePublic
 * @see app/Http/Controllers/PhotoController.php:115
 * @route '/photos/{photo}/toggle-public'
 */
export const togglePublic = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: togglePublic.url(args, options),
    method: 'post',
})

togglePublic.definition = {
    methods: ["post"],
    url: '/photos/{photo}/toggle-public',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PhotoController::togglePublic
 * @see app/Http/Controllers/PhotoController.php:115
 * @route '/photos/{photo}/toggle-public'
 */
togglePublic.url = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { photo: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { photo: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    photo: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        photo: typeof args.photo === 'object'
                ? args.photo.id
                : args.photo,
                }

    return togglePublic.definition.url
            .replace('{photo}', parsedArgs.photo.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PhotoController::togglePublic
 * @see app/Http/Controllers/PhotoController.php:115
 * @route '/photos/{photo}/toggle-public'
 */
togglePublic.post = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: togglePublic.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PhotoController::togglePublic
 * @see app/Http/Controllers/PhotoController.php:115
 * @route '/photos/{photo}/toggle-public'
 */
    const togglePublicForm = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: togglePublic.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PhotoController::togglePublic
 * @see app/Http/Controllers/PhotoController.php:115
 * @route '/photos/{photo}/toggle-public'
 */
        togglePublicForm.post = (args: { photo: number | { id: number } } | [photo: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: togglePublic.url(args, options),
            method: 'post',
        })
    
    togglePublic.form = togglePublicForm
const photos = {
    destroy: Object.assign(destroy, destroy),
togglePublic: Object.assign(togglePublic, togglePublic),
}

export default photos