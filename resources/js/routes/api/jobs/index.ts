import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\DynamicJobController::availableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
export const availableTransitions = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: availableTransitions.url(args, options),
    method: 'get',
})

availableTransitions.definition = {
    methods: ["get","head"],
    url: '/api/jobs/{job}/available-transitions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::availableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
availableTransitions.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return availableTransitions.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::availableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
availableTransitions.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: availableTransitions.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::availableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
availableTransitions.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: availableTransitions.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::availableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
    const availableTransitionsForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: availableTransitions.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::availableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
        availableTransitionsForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: availableTransitions.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::availableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
        availableTransitionsForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: availableTransitions.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    availableTransitions.form = availableTransitionsForm
/**
* @see \App\Http\Controllers\DynamicJobController::fieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
export const fieldRules = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: fieldRules.url(args, options),
    method: 'get',
})

fieldRules.definition = {
    methods: ["get","head"],
    url: '/api/jobs/{job}/field-rules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::fieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
fieldRules.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { job: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                }

    return fieldRules.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::fieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
fieldRules.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: fieldRules.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::fieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
fieldRules.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: fieldRules.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::fieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
    const fieldRulesForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: fieldRules.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::fieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
        fieldRulesForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: fieldRules.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::fieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
        fieldRulesForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: fieldRules.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    fieldRules.form = fieldRulesForm
const jobs = {
    availableTransitions: Object.assign(availableTransitions, availableTransitions),
fieldRules: Object.assign(fieldRules, fieldRules),
}

export default jobs