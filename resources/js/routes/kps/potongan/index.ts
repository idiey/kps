import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import bulk36930f from './bulk'
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::index
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:22
 * @route '/kps/sites/{site}/potongan'
 */
export const index = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/potongan',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::index
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:22
 * @route '/kps/sites/{site}/potongan'
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
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::index
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:22
 * @route '/kps/sites/{site}/potongan'
 */
index.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::index
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:22
 * @route '/kps/sites/{site}/potongan'
 */
index.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::index
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:22
 * @route '/kps/sites/{site}/potongan'
 */
    const indexForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::index
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:22
 * @route '/kps/sites/{site}/potongan'
 */
        indexForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::index
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:22
 * @route '/kps/sites/{site}/potongan'
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
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::create
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:49
 * @route '/kps/sites/{site}/potongan/create'
 */
export const create = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/potongan/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::create
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:49
 * @route '/kps/sites/{site}/potongan/create'
 */
create.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return create.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::create
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:49
 * @route '/kps/sites/{site}/potongan/create'
 */
create.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::create
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:49
 * @route '/kps/sites/{site}/potongan/create'
 */
create.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::create
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:49
 * @route '/kps/sites/{site}/potongan/create'
 */
    const createForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::create
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:49
 * @route '/kps/sites/{site}/potongan/create'
 */
        createForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::create
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:49
 * @route '/kps/sites/{site}/potongan/create'
 */
        createForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:66
 * @route '/kps/sites/{site}/potongan'
 */
export const store = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/kps/sites/{site}/potongan',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:66
 * @route '/kps/sites/{site}/potongan'
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
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:66
 * @route '/kps/sites/{site}/potongan'
 */
store.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:66
 * @route '/kps/sites/{site}/potongan'
 */
    const storeForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:66
 * @route '/kps/sites/{site}/potongan'
 */
        storeForm.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::bulk
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:104
 * @route '/kps/sites/{site}/potongan/bulk'
 */
export const bulk = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulk.url(args, options),
    method: 'get',
})

bulk.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/potongan/bulk',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::bulk
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:104
 * @route '/kps/sites/{site}/potongan/bulk'
 */
bulk.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return bulk.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::bulk
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:104
 * @route '/kps/sites/{site}/potongan/bulk'
 */
bulk.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulk.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::bulk
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:104
 * @route '/kps/sites/{site}/potongan/bulk'
 */
bulk.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bulk.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::bulk
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:104
 * @route '/kps/sites/{site}/potongan/bulk'
 */
    const bulkForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bulk.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::bulk
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:104
 * @route '/kps/sites/{site}/potongan/bulk'
 */
        bulkForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulk.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::bulk
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:104
 * @route '/kps/sites/{site}/potongan/bulk'
 */
        bulkForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulk.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bulk.form = bulkForm
const potongan = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
bulk: Object.assign(bulk, bulk36930f),
}

export default potongan