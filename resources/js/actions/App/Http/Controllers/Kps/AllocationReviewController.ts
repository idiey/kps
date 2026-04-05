import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::index
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:19
 * @route '/kps/sites/{site}/allocations'
 */
export const index = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/allocations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::index
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:19
 * @route '/kps/sites/{site}/allocations'
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
* @see \App\Http\Controllers\Kps\AllocationReviewController::index
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:19
 * @route '/kps/sites/{site}/allocations'
 */
index.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::index
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:19
 * @route '/kps/sites/{site}/allocations'
 */
index.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::index
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:19
 * @route '/kps/sites/{site}/allocations'
 */
    const indexForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::index
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:19
 * @route '/kps/sites/{site}/allocations'
 */
        indexForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::index
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:19
 * @route '/kps/sites/{site}/allocations'
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
* @see \App\Http\Controllers\Kps\AllocationReviewController::show
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:73
 * @route '/kps/sites/{site}/allocations/{deduction}'
 */
export const show = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/allocations/{deduction}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::show
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:73
 * @route '/kps/sites/{site}/allocations/{deduction}'
 */
show.url = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    deduction: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                deduction: typeof args.deduction === 'object'
                ? args.deduction.id
                : args.deduction,
                }

    return show.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{deduction}', parsedArgs.deduction.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::show
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:73
 * @route '/kps/sites/{site}/allocations/{deduction}'
 */
show.get = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::show
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:73
 * @route '/kps/sites/{site}/allocations/{deduction}'
 */
show.head = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::show
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:73
 * @route '/kps/sites/{site}/allocations/{deduction}'
 */
    const showForm = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::show
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:73
 * @route '/kps/sites/{site}/allocations/{deduction}'
 */
        showForm.get = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::show
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:73
 * @route '/kps/sites/{site}/allocations/{deduction}'
 */
        showForm.head = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Kps\AllocationReviewController::reallocate
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:92
 * @route '/kps/sites/{site}/allocations/{deduction}/reallocate'
 */
export const reallocate = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reallocate.url(args, options),
    method: 'post',
})

reallocate.definition = {
    methods: ["post"],
    url: '/kps/sites/{site}/allocations/{deduction}/reallocate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::reallocate
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:92
 * @route '/kps/sites/{site}/allocations/{deduction}/reallocate'
 */
reallocate.url = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    site: args[0],
                    deduction: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        site: typeof args.site === 'object'
                ? args.site.id
                : args.site,
                                deduction: typeof args.deduction === 'object'
                ? args.deduction.id
                : args.deduction,
                }

    return reallocate.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{deduction}', parsedArgs.deduction.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::reallocate
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:92
 * @route '/kps/sites/{site}/allocations/{deduction}/reallocate'
 */
reallocate.post = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reallocate.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::reallocate
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:92
 * @route '/kps/sites/{site}/allocations/{deduction}/reallocate'
 */
    const reallocateForm = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: reallocate.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::reallocate
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:92
 * @route '/kps/sites/{site}/allocations/{deduction}/reallocate'
 */
        reallocateForm.post = (args: { site: string | { id: string }, deduction: string | { id: string } } | [site: string | { id: string }, deduction: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: reallocate.url(args, options),
            method: 'post',
        })
    
    reallocate.form = reallocateForm
/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::closeMonth
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:113
 * @route '/kps/sites/{site}/allocations/close-month'
 */
export const closeMonth = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: closeMonth.url(args, options),
    method: 'post',
})

closeMonth.definition = {
    methods: ["post"],
    url: '/kps/sites/{site}/allocations/close-month',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::closeMonth
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:113
 * @route '/kps/sites/{site}/allocations/close-month'
 */
closeMonth.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return closeMonth.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\AllocationReviewController::closeMonth
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:113
 * @route '/kps/sites/{site}/allocations/close-month'
 */
closeMonth.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: closeMonth.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::closeMonth
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:113
 * @route '/kps/sites/{site}/allocations/close-month'
 */
    const closeMonthForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: closeMonth.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\AllocationReviewController::closeMonth
 * @see app/Http/Controllers/Kps/AllocationReviewController.php:113
 * @route '/kps/sites/{site}/allocations/close-month'
 */
        closeMonthForm.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: closeMonth.url(args, options),
            method: 'post',
        })
    
    closeMonth.form = closeMonthForm
const AllocationReviewController = { index, show, reallocate, closeMonth }

export default AllocationReviewController