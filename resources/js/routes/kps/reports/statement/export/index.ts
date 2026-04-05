import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:134
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv'
 */
export const csv = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: csv.url(args, options),
    method: 'get',
})

csv.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:134
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv'
 */
csv.url = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions) => {
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

    return csv.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{peneroka}', parsedArgs.peneroka.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:134
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv'
 */
csv.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: csv.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:134
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv'
 */
csv.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: csv.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:134
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv'
 */
    const csvForm = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: csv.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:134
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv'
 */
        csvForm.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: csv.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\ReportController::csv
 * @see app/Http/Controllers/Kps/ReportController.php:134
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/csv'
 */
        csvForm.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: csv.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    csv.form = csvForm
/**
* @see \App\Http\Controllers\Kps\ReportController::pdf
 * @see app/Http/Controllers/Kps/ReportController.php:204
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf'
 */
export const pdf = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(args, options),
    method: 'get',
})

pdf.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\ReportController::pdf
 * @see app/Http/Controllers/Kps/ReportController.php:204
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf'
 */
pdf.url = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions) => {
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

    return pdf.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace('{peneroka}', parsedArgs.peneroka.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\ReportController::pdf
 * @see app/Http/Controllers/Kps/ReportController.php:204
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf'
 */
pdf.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\ReportController::pdf
 * @see app/Http/Controllers/Kps/ReportController.php:204
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf'
 */
pdf.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pdf.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\ReportController::pdf
 * @see app/Http/Controllers/Kps/ReportController.php:204
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf'
 */
    const pdfForm = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pdf.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\ReportController::pdf
 * @see app/Http/Controllers/Kps/ReportController.php:204
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf'
 */
        pdfForm.get = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pdf.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\ReportController::pdf
 * @see app/Http/Controllers/Kps/ReportController.php:204
 * @route '/kps/sites/{site}/reports/peneroka/{peneroka}/export/pdf'
 */
        pdfForm.head = (args: { site: string | { id: string }, peneroka: string | { id: string } } | [site: string | { id: string }, peneroka: string | { id: string } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pdf.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pdf.form = pdfForm
const exportMethod = {
    csv: Object.assign(csv, csv),
pdf: Object.assign(pdf, pdf),
}

export default exportMethod