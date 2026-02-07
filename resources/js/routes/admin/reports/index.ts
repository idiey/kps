import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
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
* @see \App\Http\Controllers\Admin\ReportController::jobs
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
export const jobs = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: jobs.url(options),
    method: 'post',
})

jobs.definition = {
    methods: ["post"],
    url: '/admin/reports/jobs',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ReportController::jobs
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
jobs.url = (options?: RouteQueryOptions) => {
    return jobs.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportController::jobs
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
jobs.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: jobs.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ReportController::jobs
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
    const jobsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: jobs.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportController::jobs
 * @see app/Http/Controllers/Admin/ReportController.php:33
 * @route '/admin/reports/jobs'
 */
        jobsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: jobs.url(options),
            method: 'post',
        })
    
    jobs.form = jobsForm
/**
* @see \App\Http\Controllers\Admin\ReportController::customers
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
export const customers = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: customers.url(options),
    method: 'post',
})

customers.definition = {
    methods: ["post"],
    url: '/admin/reports/customers',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ReportController::customers
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
customers.url = (options?: RouteQueryOptions) => {
    return customers.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportController::customers
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
customers.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: customers.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ReportController::customers
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
    const customersForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: customers.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportController::customers
 * @see app/Http/Controllers/Admin/ReportController.php:84
 * @route '/admin/reports/customers'
 */
        customersForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: customers.url(options),
            method: 'post',
        })
    
    customers.form = customersForm
/**
* @see \App\Http\Controllers\Admin\ReportController::performance
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
export const performance = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: performance.url(options),
    method: 'post',
})

performance.definition = {
    methods: ["post"],
    url: '/admin/reports/performance',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ReportController::performance
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
performance.url = (options?: RouteQueryOptions) => {
    return performance.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportController::performance
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
performance.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: performance.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ReportController::performance
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
    const performanceForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: performance.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportController::performance
 * @see app/Http/Controllers/Admin/ReportController.php:127
 * @route '/admin/reports/performance'
 */
        performanceForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: performance.url(options),
            method: 'post',
        })
    
    performance.form = performanceForm
const reports = {
    index: Object.assign(index, index),
jobs: Object.assign(jobs, jobs),
customers: Object.assign(customers, customers),
performance: Object.assign(performance, performance),
}

export default reports