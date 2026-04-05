import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import profile from './profile'
import sites from './sites'
import peneroka from './peneroka'
import hutang from './hutang'
import potongan from './potongan'
import allocations from './allocations'
import reports from './reports'
import auditLogs from './audit-logs'
/**
 * @see routes/kps.php:20
 * @route '/kps'
 */
export const home = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

home.definition = {
    methods: ["get","head"],
    url: '/kps',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/kps.php:20
 * @route '/kps'
 */
home.url = (options?: RouteQueryOptions) => {
    return home.definition.url + queryParams(options)
}

/**
 * @see routes/kps.php:20
 * @route '/kps'
 */
home.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})
/**
 * @see routes/kps.php:20
 * @route '/kps'
 */
home.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: home.url(options),
    method: 'head',
})

    /**
 * @see routes/kps.php:20
 * @route '/kps'
 */
    const homeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: home.url(options),
        method: 'get',
    })

            /**
 * @see routes/kps.php:20
 * @route '/kps'
 */
        homeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: home.url(options),
            method: 'get',
        })
            /**
 * @see routes/kps.php:20
 * @route '/kps'
 */
        homeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: home.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    home.form = homeForm
/**
* @see \App\Http\Controllers\Kps\DashboardController::dashboard
 * @see app/Http/Controllers/Kps/DashboardController.php:16
 * @route '/kps/dashboard'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/kps/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\DashboardController::dashboard
 * @see app/Http/Controllers/Kps/DashboardController.php:16
 * @route '/kps/dashboard'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\DashboardController::dashboard
 * @see app/Http/Controllers/Kps/DashboardController.php:16
 * @route '/kps/dashboard'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\DashboardController::dashboard
 * @see app/Http/Controllers/Kps/DashboardController.php:16
 * @route '/kps/dashboard'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\DashboardController::dashboard
 * @see app/Http/Controllers/Kps/DashboardController.php:16
 * @route '/kps/dashboard'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\DashboardController::dashboard
 * @see app/Http/Controllers/Kps/DashboardController.php:16
 * @route '/kps/dashboard'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\DashboardController::dashboard
 * @see app/Http/Controllers/Kps/DashboardController.php:16
 * @route '/kps/dashboard'
 */
        dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    dashboard.form = dashboardForm
/**
* @see \App\Http\Controllers\Kps\AnalyticsController::analytics
 * @see app/Http/Controllers/Kps/AnalyticsController.php:17
 * @route '/kps/analytics'
 */
export const analytics = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analytics.url(options),
    method: 'get',
})

analytics.definition = {
    methods: ["get","head"],
    url: '/kps/analytics',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\AnalyticsController::analytics
 * @see app/Http/Controllers/Kps/AnalyticsController.php:17
 * @route '/kps/analytics'
 */
analytics.url = (options?: RouteQueryOptions) => {
    return analytics.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\AnalyticsController::analytics
 * @see app/Http/Controllers/Kps/AnalyticsController.php:17
 * @route '/kps/analytics'
 */
analytics.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analytics.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\AnalyticsController::analytics
 * @see app/Http/Controllers/Kps/AnalyticsController.php:17
 * @route '/kps/analytics'
 */
analytics.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: analytics.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\AnalyticsController::analytics
 * @see app/Http/Controllers/Kps/AnalyticsController.php:17
 * @route '/kps/analytics'
 */
    const analyticsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: analytics.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\AnalyticsController::analytics
 * @see app/Http/Controllers/Kps/AnalyticsController.php:17
 * @route '/kps/analytics'
 */
        analyticsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analytics.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\AnalyticsController::analytics
 * @see app/Http/Controllers/Kps/AnalyticsController.php:17
 * @route '/kps/analytics'
 */
        analyticsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analytics.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    analytics.form = analyticsForm
const kps = {
    home: Object.assign(home, home),
dashboard: Object.assign(dashboard, dashboard),
analytics: Object.assign(analytics, analytics),
profile: Object.assign(profile, profile),
sites: Object.assign(sites, sites),
peneroka: Object.assign(peneroka, peneroka),
hutang: Object.assign(hutang, hutang),
potongan: Object.assign(potongan, potongan),
allocations: Object.assign(allocations, allocations),
reports: Object.assign(reports, reports),
auditLogs: Object.assign(auditLogs, auditLogs),
}

export default kps