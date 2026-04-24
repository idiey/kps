import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:164
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
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:164
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
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:164
 * @route '/kps/sites/{site}/potongan/bulk'
 */
store.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:164
 * @route '/kps/sites/{site}/potongan/bulk'
 */
    const storeForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::store
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:164
 * @route '/kps/sites/{site}/potongan/bulk'
 */
        storeForm.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::template
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:218
 * @route '/kps/sites/{site}/potongan/bulk/template'
 */
export const template = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: template.url(args, options),
    method: 'get',
})

template.definition = {
    methods: ["get","head"],
    url: '/kps/sites/{site}/potongan/bulk/template',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::template
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:218
 * @route '/kps/sites/{site}/potongan/bulk/template'
 */
template.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return template.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::template
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:218
 * @route '/kps/sites/{site}/potongan/bulk/template'
 */
template.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: template.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::template
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:218
 * @route '/kps/sites/{site}/potongan/bulk/template'
 */
template.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: template.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::template
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:218
 * @route '/kps/sites/{site}/potongan/bulk/template'
 */
    const templateForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: template.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::template
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:218
 * @route '/kps/sites/{site}/potongan/bulk/template'
 */
        templateForm.get = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: template.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::template
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:218
 * @route '/kps/sites/{site}/potongan/bulk/template'
 */
        templateForm.head = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: template.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    template.form = templateForm
/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::upload
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:248
 * @route '/kps/sites/{site}/potongan/bulk/upload'
 */
export const upload = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(args, options),
    method: 'post',
})

upload.definition = {
    methods: ["post"],
    url: '/kps/sites/{site}/potongan/bulk/upload',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::upload
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:248
 * @route '/kps/sites/{site}/potongan/bulk/upload'
 */
upload.url = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return upload.definition.url
            .replace('{site}', parsedArgs.site.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::upload
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:248
 * @route '/kps/sites/{site}/potongan/bulk/upload'
 */
upload.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::upload
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:248
 * @route '/kps/sites/{site}/potongan/bulk/upload'
 */
    const uploadForm = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: upload.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Kps\MonthlyDeductionController::upload
 * @see app/Http/Controllers/Kps/MonthlyDeductionController.php:248
 * @route '/kps/sites/{site}/potongan/bulk/upload'
 */
        uploadForm.post = (args: { site: string | { id: string } } | [site: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: upload.url(args, options),
            method: 'post',
        })
    
    upload.form = uploadForm
const bulk = {
    store: Object.assign(store, store),
template: Object.assign(template, template),
upload: Object.assign(upload, upload),
}

export default bulk