import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
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
* @see \App\Http\Controllers\PhotoController::bulk
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
export const bulk = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulk.url(args, options),
    method: 'post',
})

bulk.definition = {
    methods: ["post"],
    url: '/jobs/{job}/photos/bulk',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PhotoController::bulk
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
bulk.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return bulk.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PhotoController::bulk
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
bulk.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulk.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PhotoController::bulk
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
    const bulkForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulk.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PhotoController::bulk
 * @see app/Http/Controllers/PhotoController.php:57
 * @route '/jobs/{job}/photos/bulk'
 */
        bulkForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulk.url(args, options),
            method: 'post',
        })
    
    bulk.form = bulkForm
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
const photos = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
bulk: Object.assign(bulk, bulk),
byStage: Object.assign(byStage, byStage),
}

export default photos