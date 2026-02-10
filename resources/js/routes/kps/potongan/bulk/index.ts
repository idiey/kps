import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:121
 * @route '/kps/sites/{site}/potongan/bulk'
 */
export const store = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/kps/sites/{site}/potongan/bulk',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:121
 * @route '/kps/sites/{site}/potongan/bulk'
 */
store.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:121
 * @route '/kps/sites/{site}/potongan/bulk'
 */
store.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:121
 * @route '/kps/sites/{site}/potongan/bulk'
 */
    const storeForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:121
 * @route '/kps/sites/{site}/potongan/bulk'
 */
        storeForm.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
const bulk = {
    store: Object.assign(store, store),
}

export default bulk