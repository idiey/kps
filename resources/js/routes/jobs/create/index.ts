import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\JobController::kew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
export const kew = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kew.url(options),
    method: 'get',
})

kew.definition = {
    methods: ["get","head"],
    url: '/jobs/create/kew',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::kew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
kew.url = (options?: RouteQueryOptions) => {
    return kew.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::kew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
kew.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kew.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::kew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
kew.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kew.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::kew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
    const kewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: kew.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::kew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
        kewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: kew.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::kew
 * @see app/Http/Controllers/JobController.php:86
 * @route '/jobs/create/kew'
 */
        kewForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: kew.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    kew.form = kewForm
/**
* @see \App\Http\Controllers\JobController::normal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
export const normal = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: normal.url(options),
    method: 'get',
})

normal.definition = {
    methods: ["get","head"],
    url: '/jobs/create/normal',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\JobController::normal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
normal.url = (options?: RouteQueryOptions) => {
    return normal.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\JobController::normal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
normal.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: normal.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\JobController::normal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
normal.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: normal.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\JobController::normal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
    const normalForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: normal.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\JobController::normal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
        normalForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: normal.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\JobController::normal
 * @see app/Http/Controllers/JobController.php:104
 * @route '/jobs/create/normal'
 */
        normalForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: normal.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    normal.form = normalForm