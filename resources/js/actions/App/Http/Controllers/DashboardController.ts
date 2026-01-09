import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DashboardController::workload
 * @see app/Http/Controllers/DashboardController.php:25
 * @route '/dashboard/workload'
 */
export const workload = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: workload.url(options),
    method: 'get',
})

workload.definition = {
    methods: ["get","head"],
    url: '/dashboard/workload',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::workload
 * @see app/Http/Controllers/DashboardController.php:25
 * @route '/dashboard/workload'
 */
workload.url = (options?: RouteQueryOptions) => {
    return workload.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::workload
 * @see app/Http/Controllers/DashboardController.php:25
 * @route '/dashboard/workload'
 */
workload.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: workload.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DashboardController::workload
 * @see app/Http/Controllers/DashboardController.php:25
 * @route '/dashboard/workload'
 */
workload.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: workload.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DashboardController::workload
 * @see app/Http/Controllers/DashboardController.php:25
 * @route '/dashboard/workload'
 */
    const workloadForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: workload.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DashboardController::workload
 * @see app/Http/Controllers/DashboardController.php:25
 * @route '/dashboard/workload'
 */
        workloadForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: workload.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DashboardController::workload
 * @see app/Http/Controllers/DashboardController.php:25
 * @route '/dashboard/workload'
 */
        workloadForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: workload.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    workload.form = workloadForm
/**
* @see \App\Http\Controllers\DashboardController::myJobs
 * @see app/Http/Controllers/DashboardController.php:62
 * @route '/dashboard/my-jobs'
 */
export const myJobs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myJobs.url(options),
    method: 'get',
})

myJobs.definition = {
    methods: ["get","head"],
    url: '/dashboard/my-jobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::myJobs
 * @see app/Http/Controllers/DashboardController.php:62
 * @route '/dashboard/my-jobs'
 */
myJobs.url = (options?: RouteQueryOptions) => {
    return myJobs.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::myJobs
 * @see app/Http/Controllers/DashboardController.php:62
 * @route '/dashboard/my-jobs'
 */
myJobs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myJobs.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DashboardController::myJobs
 * @see app/Http/Controllers/DashboardController.php:62
 * @route '/dashboard/my-jobs'
 */
myJobs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: myJobs.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DashboardController::myJobs
 * @see app/Http/Controllers/DashboardController.php:62
 * @route '/dashboard/my-jobs'
 */
    const myJobsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: myJobs.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DashboardController::myJobs
 * @see app/Http/Controllers/DashboardController.php:62
 * @route '/dashboard/my-jobs'
 */
        myJobsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: myJobs.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DashboardController::myJobs
 * @see app/Http/Controllers/DashboardController.php:62
 * @route '/dashboard/my-jobs'
 */
        myJobsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: myJobs.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    myJobs.form = myJobsForm
/**
* @see \App\Http\Controllers\DashboardController::statistics
 * @see app/Http/Controllers/DashboardController.php:84
 * @route '/dashboard/statistics'
 */
export const statistics = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: statistics.url(options),
    method: 'get',
})

statistics.definition = {
    methods: ["get","head"],
    url: '/dashboard/statistics',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::statistics
 * @see app/Http/Controllers/DashboardController.php:84
 * @route '/dashboard/statistics'
 */
statistics.url = (options?: RouteQueryOptions) => {
    return statistics.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::statistics
 * @see app/Http/Controllers/DashboardController.php:84
 * @route '/dashboard/statistics'
 */
statistics.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: statistics.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DashboardController::statistics
 * @see app/Http/Controllers/DashboardController.php:84
 * @route '/dashboard/statistics'
 */
statistics.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: statistics.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DashboardController::statistics
 * @see app/Http/Controllers/DashboardController.php:84
 * @route '/dashboard/statistics'
 */
    const statisticsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: statistics.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DashboardController::statistics
 * @see app/Http/Controllers/DashboardController.php:84
 * @route '/dashboard/statistics'
 */
        statisticsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: statistics.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DashboardController::statistics
 * @see app/Http/Controllers/DashboardController.php:84
 * @route '/dashboard/statistics'
 */
        statisticsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: statistics.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    statistics.form = statisticsForm
const DashboardController = { workload, myJobs, statistics }

export default DashboardController