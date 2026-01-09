import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PhotoController::index
 * @see app/Http/Controllers/PhotoController.php:24
 * @route '/jobs/{job}/photos'
 */
export const index = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/photos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PhotoController::index
 * @see app/Http/Controllers/PhotoController.php:24
 * @route '/jobs/{job}/photos'
 */
index.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return index.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PhotoController::index
 * @see app/Http/Controllers/PhotoController.php:24
 * @route '/jobs/{job}/photos'
 */
index.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PhotoController::index
 * @see app/Http/Controllers/PhotoController.php:24
 * @route '/jobs/{job}/photos'
 */
index.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PhotoController::index
 * @see app/Http/Controllers/PhotoController.php:24
 * @route '/jobs/{job}/photos'
 */
    const indexForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PhotoController::index
 * @see app/Http/Controllers/PhotoController.php:24
 * @route '/jobs/{job}/photos'
 */
        indexForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PhotoController::index
 * @see app/Http/Controllers/PhotoController.php:24
 * @route '/jobs/{job}/photos'
 */
        indexForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\PhotoController::store
 * @see app/Http/Controllers/PhotoController.php:41
 * @route '/jobs/{job}/photos'
 */
export const store = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/jobs/{job}/photos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PhotoController::store
 * @see app/Http/Controllers/PhotoController.php:41
 * @route '/jobs/{job}/photos'
 */
store.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return store.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PhotoController::store
 * @see app/Http/Controllers/PhotoController.php:41
 * @route '/jobs/{job}/photos'
 */
store.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PhotoController::store
 * @see app/Http/Controllers/PhotoController.php:41
 * @route '/jobs/{job}/photos'
 */
    const storeForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PhotoController::store
 * @see app/Http/Controllers/PhotoController.php:41
 * @route '/jobs/{job}/photos'
 */
        storeForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\PhotoController::bulkUpload
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
export const bulkUpload = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpload.url(args, options),
    method: 'post',
})

bulkUpload.definition = {
    methods: ["post"],
    url: '/jobs/{job}/photos/bulk',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PhotoController::bulkUpload
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
bulkUpload.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return bulkUpload.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PhotoController::bulkUpload
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
bulkUpload.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpload.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PhotoController::bulkUpload
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
    const bulkUploadForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkUpload.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PhotoController::bulkUpload
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
        bulkUploadForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkUpload.url(args, options),
            method: 'post',
        })
    
    bulkUpload.form = bulkUploadForm
/**
* @see \App\Http\Controllers\PhotoController::byStage
 * @see app/Http/Controllers/PhotoController.php:80
 * @route '/jobs/{job}/photos/stage/{stage}'
 */
export const byStage = (args: { job: number | { id: number }, stage: string | number } | [job: number | { id: number }, stage: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byStage.url(args, options),
    method: 'get',
})

byStage.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/photos/stage/{stage}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PhotoController::byStage
 * @see app/Http/Controllers/PhotoController.php:80
 * @route '/jobs/{job}/photos/stage/{stage}'
 */
byStage.url = (args: { job: number | { id: number }, stage: string | number } | [job: number | { id: number }, stage: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                    stage: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                                stage: args.stage,
                }

    return byStage.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace('{stage}', parsedArgs.stage.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PhotoController::byStage
 * @see app/Http/Controllers/PhotoController.php:80
 * @route '/jobs/{job}/photos/stage/{stage}'
 */
byStage.get = (args: { job: number | { id: number }, stage: string | number } | [job: number | { id: number }, stage: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byStage.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PhotoController::byStage
 * @see app/Http/Controllers/PhotoController.php:80
 * @route '/jobs/{job}/photos/stage/{stage}'
 */
byStage.head = (args: { job: number | { id: number }, stage: string | number } | [job: number | { id: number }, stage: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byStage.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PhotoController::byStage
 * @see app/Http/Controllers/PhotoController.php:80
 * @route '/jobs/{job}/photos/stage/{stage}'
 */
    const byStageForm = (args: { job: number | { id: number }, stage: string | number } | [job: number | { id: number }, stage: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: byStage.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PhotoController::byStage
 * @see app/Http/Controllers/PhotoController.php:80
 * @route '/jobs/{job}/photos/stage/{stage}'
 */
        byStageForm.get = (args: { job: number | { id: number }, stage: string | number } | [job: number | { id: number }, stage: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: byStage.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PhotoController::byStage
 * @see app/Http/Controllers/PhotoController.php:80
 * @route '/jobs/{job}/photos/stage/{stage}'
 */
        byStageForm.head = (args: { job: number | { id: number }, stage: string | number } | [job: number | { id: number }, stage: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: byStage.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    byStage.form = byStageForm
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
const PhotoController = { index, store, bulkUpload, byStage, destroy, togglePublic }

export default PhotoController