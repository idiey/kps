import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ReportController::index
 * @see app/Http/Controllers/Admin/ReportController.php:25
 * @route '/admin/reports'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ReportController::index
 * @see app/Http/Controllers/Admin/ReportController.php:25
 * @route '/admin/reports'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportController::index
 * @see app/Http/Controllers/Admin/ReportController.php:25
 * @route '/admin/reports'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ReportController::index
 * @see app/Http/Controllers/Admin/ReportController.php:25
 * @route '/admin/reports'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ReportController::index
 * @see app/Http/Controllers/Admin/ReportController.php:25
 * @route '/admin/reports'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportController::index
 * @see app/Http/Controllers/Admin/ReportController.php:25
 * @route '/admin/reports'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ReportController::index
 * @see app/Http/Controllers/Admin/ReportController.php:25
 * @route '/admin/reports'
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
* @see \App\Http\Controllers\Admin\ReportController::jobReport
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
export const jobReport = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: jobReport.url(options),
    method: 'post',
})

jobReport.definition = {
    methods: ["post"],
    url: '/admin/reports/jobs',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ReportController::jobReport
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
jobReport.url = (options?: RouteQueryOptions) => {
    return jobReport.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportController::jobReport
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
jobReport.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: jobReport.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ReportController::jobReport
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
    const jobReportForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: jobReport.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportController::jobReport
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
        jobReportForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: jobReport.url(options),
            method: 'post',
        })
    
    jobReport.form = jobReportForm
/**
* @see \App\Http\Controllers\Admin\ReportController::customerReport
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
export const customerReport = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: customerReport.url(options),
    method: 'post',
})

customerReport.definition = {
    methods: ["post"],
    url: '/admin/reports/customers',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ReportController::customerReport
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
customerReport.url = (options?: RouteQueryOptions) => {
    return customerReport.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportController::customerReport
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
customerReport.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: customerReport.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ReportController::customerReport
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
    const customerReportForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: customerReport.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportController::customerReport
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
        customerReportForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: customerReport.url(options),
            method: 'post',
        })
    
    customerReport.form = customerReportForm
/**
* @see \App\Http\Controllers\Admin\ReportController::performanceReport
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
export const performanceReport = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: performanceReport.url(options),
    method: 'post',
})

performanceReport.definition = {
    methods: ["post"],
    url: '/admin/reports/performance',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ReportController::performanceReport
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
performanceReport.url = (options?: RouteQueryOptions) => {
    return performanceReport.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportController::performanceReport
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
performanceReport.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: performanceReport.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ReportController::performanceReport
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
    const performanceReportForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: performanceReport.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportController::performanceReport
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
        performanceReportForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: performanceReport.url(options),
            method: 'post',
        })
    
    performanceReport.form = performanceReportForm
const ReportController = { index, jobReport, customerReport, performanceReport }

export default ReportController