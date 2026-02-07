import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\WorkshopAnalyticsController::show
 * @see app/Http/Controllers/Admin/WorkshopAnalyticsController.php:23
 * @route '/admin/workshops/{workshop}/analytics'
 */
export const show = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/workshops/{workshop}/analytics',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\WorkshopAnalyticsController::show
 * @see app/Http/Controllers/Admin/WorkshopAnalyticsController.php:23
 * @route '/admin/workshops/{workshop}/analytics'
 */
show.url = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workshop: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { workshop: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    workshop: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        workshop: typeof args.workshop === 'object'
                ? args.workshop.id
                : args.workshop,
                }

    return show.definition.url
            .replace('{workshop}', parsedArgs.workshop.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\WorkshopAnalyticsController::show
 * @see app/Http/Controllers/Admin/WorkshopAnalyticsController.php:23
 * @route '/admin/workshops/{workshop}/analytics'
 */
show.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\WorkshopAnalyticsController::show
 * @see app/Http/Controllers/Admin/WorkshopAnalyticsController.php:23
 * @route '/admin/workshops/{workshop}/analytics'
 */
show.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\WorkshopAnalyticsController::show
 * @see app/Http/Controllers/Admin/WorkshopAnalyticsController.php:23
 * @route '/admin/workshops/{workshop}/analytics'
 */
    const showForm = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\WorkshopAnalyticsController::show
 * @see app/Http/Controllers/Admin/WorkshopAnalyticsController.php:23
 * @route '/admin/workshops/{workshop}/analytics'
 */
        showForm.get = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\WorkshopAnalyticsController::show
 * @see app/Http/Controllers/Admin/WorkshopAnalyticsController.php:23
 * @route '/admin/workshops/{workshop}/analytics'
 */
        showForm.head = (args: { workshop: string | { id: string } } | [workshop: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const WorkshopAnalyticsController = { show }

export default WorkshopAnalyticsController