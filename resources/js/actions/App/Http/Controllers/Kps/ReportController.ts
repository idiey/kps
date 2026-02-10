import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\ReportController::index
 * @see app/Http/Controllers/Kps/ReportController.php:16
 * @route '/kps/sites/{site}/reports'
 */
export const index = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\ReportController::index
 * @see app/Http/Controllers/Kps/ReportController.php:16
 * @route '/kps/sites/{site}/reports'
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
* @see \App\Http\Controllers\Kps\ReportController::index
 * @see app/Http/Controllers/Kps/ReportController.php:16
 * @route '/kps/sites/{site}/reports'
 */
index.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\ReportController::index
 * @see app/Http/Controllers/Kps/ReportController.php:16
 * @route '/kps/sites/{site}/reports'
 */
index.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\ReportController::index
 * @see app/Http/Controllers/Kps/ReportController.php:16
 * @route '/kps/sites/{site}/reports'
 */
    const indexForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\ReportController::index
 * @see app/Http/Controllers/Kps/ReportController.php:16
 * @route '/kps/sites/{site}/reports'
 */
        indexForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\ReportController::index
 * @see app/Http/Controllers/Kps/ReportController.php:16
 * @route '/kps/sites/{site}/reports'
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
* @see \App\Http\Controllers\Kps\ReportController::penerokaStatement
 * @see app/Http/Controllers/Kps/ReportController.php:36
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}'
 */
export const penerokaStatement = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: penerokaStatement.url(args, options),
    method: 'get',
})

penerokaStatement.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/reports/peneroka/{peneroka}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\ReportController::penerokaStatement
 * @see app/Http/Controllers/Kps/ReportController.php:36
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}'
 */
penerokaStatement.url = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions) => {
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

    return penerokaStatement.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{peneroka}', parsedArgs.peneroka.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\ReportController::penerokaStatement
 * @see app/Http/Controllers/Kps/ReportController.php:36
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}'
 */
penerokaStatement.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: penerokaStatement.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\ReportController::penerokaStatement
 * @see app/Http/Controllers/Kps/ReportController.php:36
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}'
 */
penerokaStatement.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: penerokaStatement.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\ReportController::penerokaStatement
 * @see app/Http/Controllers/Kps/ReportController.php:36
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}'
 */
    const penerokaStatementForm = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: penerokaStatement.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\ReportController::penerokaStatement
 * @see app/Http/Controllers/Kps/ReportController.php:36
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}'
 */
        penerokaStatementForm.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: penerokaStatement.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\ReportController::penerokaStatement
 * @see app/Http/Controllers/Kps/ReportController.php:36
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}'
 */
        penerokaStatementForm.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: penerokaStatement.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    penerokaStatement.form = penerokaStatementForm
const ReportController = { index, penerokaStatement }

export default ReportController