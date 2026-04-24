import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:64
 * @route '/kps/sites/{site}/reports/export/csv'
 */
export const csv = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: csv.url(args, options),
    method: 'get',
})

csv.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/reports/export/csv',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:64
 * @route '/kps/sites/{site}/reports/export/csv'
 */
csv.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return csv.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:64
 * @route '/kps/sites/{site}/reports/export/csv'
 */
csv.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: csv.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:64
 * @route '/kps/sites/{site}/reports/export/csv'
 */
csv.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: csv.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:64
 * @route '/kps/sites/{site}/reports/export/csv'
 */
    const csvForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: csv.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:64
 * @route '/kps/sites/{site}/reports/export/csv'
 */
        csvForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: csv.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:64
 * @route '/kps/sites/{site}/reports/export/csv'
 */
        csvForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: csv.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    csv.form = csvForm
const exportMethod = {
    csv: Object.assign(csv, csv),
}

export default exportMethod