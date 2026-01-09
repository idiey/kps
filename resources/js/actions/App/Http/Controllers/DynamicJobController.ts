import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
const create9143c87e233e70a06b48ada6958f9390 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create9143c87e233e70a06b48ada6958f9390.url(options),
    method: 'get',
})

create9143c87e233e70a06b48ada6958f9390.definition = {
    methods: ["get","head"],
    url: '/jobs/templates/select',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
create9143c87e233e70a06b48ada6958f9390.url = (options?: RouteQueryOptions) => {
    return create9143c87e233e70a06b48ada6958f9390.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
create9143c87e233e70a06b48ada6958f9390.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create9143c87e233e70a06b48ada6958f9390.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
create9143c87e233e70a06b48ada6958f9390.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create9143c87e233e70a06b48ada6958f9390.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
    const create9143c87e233e70a06b48ada6958f9390Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create9143c87e233e70a06b48ada6958f9390.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
        create9143c87e233e70a06b48ada6958f9390Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create9143c87e233e70a06b48ada6958f9390.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/templates/select'
 */
        create9143c87e233e70a06b48ada6958f9390Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create9143c87e233e70a06b48ada6958f9390.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create9143c87e233e70a06b48ada6958f9390.form = create9143c87e233e70a06b48ada6958f9390Form
    /**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
const createab8c1aa2af6a612baa3589a2ffa95e7f = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createab8c1aa2af6a612baa3589a2ffa95e7f.url(args, options),
    method: 'get',
})

createab8c1aa2af6a612baa3589a2ffa95e7f.definition = {
    methods: ["get","head"],
    url: '/jobs/create/{template}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
createab8c1aa2af6a612baa3589a2ffa95e7f.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                }

    return createab8c1aa2af6a612baa3589a2ffa95e7f.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
createab8c1aa2af6a612baa3589a2ffa95e7f.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createab8c1aa2af6a612baa3589a2ffa95e7f.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
createab8c1aa2af6a612baa3589a2ffa95e7f.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createab8c1aa2af6a612baa3589a2ffa95e7f.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
    const createab8c1aa2af6a612baa3589a2ffa95e7fForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createab8c1aa2af6a612baa3589a2ffa95e7f.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
        createab8c1aa2af6a612baa3589a2ffa95e7fForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createab8c1aa2af6a612baa3589a2ffa95e7f.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::create
 * @see app/Http/Controllers/DynamicJobController.php:28
 * @route '/jobs/create/{template}'
 */
        createab8c1aa2af6a612baa3589a2ffa95e7fForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createab8c1aa2af6a612baa3589a2ffa95e7f.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    createab8c1aa2af6a612baa3589a2ffa95e7f.form = createab8c1aa2af6a612baa3589a2ffa95e7fForm

export const create = {
    '/jobs/templates/select': create9143c87e233e70a06b48ada6958f9390,
    '/jobs/create/{template}': createab8c1aa2af6a612baa3589a2ffa95e7f,
}

/**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
export const executeTransition = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: executeTransition.url(args, options),
    method: 'post',
})

executeTransition.definition = {
    methods: ["post"],
    url: '/jobs/{job}/transitions/{transition}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
executeTransition.url = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    job: args[0],
                    transition: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        job: typeof args.job === 'object'
                ? args.job.id
                : args.job,
                                transition: typeof args.transition === 'object'
                ? args.transition.id
                : args.transition,
                }

    return executeTransition.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace('{transition}', parsedArgs.transition.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
executeTransition.post = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: executeTransition.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
    const executeTransitionForm = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: executeTransition.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::executeTransition
 * @see app/Http/Controllers/DynamicJobController.php:168
 * @route '/jobs/{job}/transitions/{transition}'
 */
        executeTransitionForm.post = (args: { job: number | { id: number }, transition: number | { id: number } } | [job: number | { id: number }, transition: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: executeTransition.url(args, options),
            method: 'post',
        })
    
    executeTransition.form = executeTransitionForm
/**
* @see \App\Http\Controllers\DynamicJobController::getAvailableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
export const getAvailableTransitions = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getAvailableTransitions.url(args, options),
    method: 'get',
})

getAvailableTransitions.definition = {
    methods: ["get","head"],
    url: '/api/jobs/{job}/available-transitions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::getAvailableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
getAvailableTransitions.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return getAvailableTransitions.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::getAvailableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
getAvailableTransitions.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getAvailableTransitions.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::getAvailableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
getAvailableTransitions.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getAvailableTransitions.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::getAvailableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
    const getAvailableTransitionsForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getAvailableTransitions.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::getAvailableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
        getAvailableTransitionsForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getAvailableTransitions.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::getAvailableTransitions
 * @see app/Http/Controllers/DynamicJobController.php:209
 * @route '/api/jobs/{job}/available-transitions'
 */
        getAvailableTransitionsForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getAvailableTransitions.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getAvailableTransitions.form = getAvailableTransitionsForm
/**
* @see \App\Http\Controllers\DynamicJobController::getFieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
export const getFieldRules = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getFieldRules.url(args, options),
    method: 'get',
})

getFieldRules.definition = {
    methods: ["get","head"],
    url: '/api/jobs/{job}/field-rules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::getFieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
getFieldRules.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return getFieldRules.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::getFieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
getFieldRules.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getFieldRules.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::getFieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
getFieldRules.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getFieldRules.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::getFieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
    const getFieldRulesForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getFieldRules.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::getFieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
        getFieldRulesForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getFieldRules.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::getFieldRules
 * @see app/Http/Controllers/DynamicJobController.php:229
 * @route '/api/jobs/{job}/field-rules'
 */
        getFieldRulesForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getFieldRules.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getFieldRules.form = getFieldRulesForm
/**
* @see \App\Http\Controllers\DynamicJobController::validateFieldData
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
export const validateFieldData = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validateFieldData.url(args, options),
    method: 'post',
})

validateFieldData.definition = {
    methods: ["post"],
    url: '/api/templates/{template}/validate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DynamicJobController::validateFieldData
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
validateFieldData.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                }

    return validateFieldData.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::validateFieldData
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
validateFieldData.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validateFieldData.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::validateFieldData
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
    const validateFieldDataForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: validateFieldData.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::validateFieldData
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
        validateFieldDataForm.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: validateFieldData.url(args, options),
            method: 'post',
        })
    
    validateFieldData.form = validateFieldDataForm
/**
* @see \App\Http\Controllers\DynamicJobController::getFormSchema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
export const getFormSchema = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getFormSchema.url(args, options),
    method: 'get',
})

getFormSchema.definition = {
    methods: ["get","head"],
    url: '/api/templates/{template}/schema',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::getFormSchema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
getFormSchema.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                }

    return getFormSchema.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::getFormSchema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
getFormSchema.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getFormSchema.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::getFormSchema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
getFormSchema.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getFormSchema.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::getFormSchema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
    const getFormSchemaForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getFormSchema.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::getFormSchema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
        getFormSchemaForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getFormSchema.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::getFormSchema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
        getFormSchemaForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getFormSchema.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getFormSchema.form = getFormSchemaForm
/**
* @see \App\Http\Controllers\DynamicJobController::getWorkflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
export const getWorkflows = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getWorkflows.url(args, options),
    method: 'get',
})

getWorkflows.definition = {
    methods: ["get","head"],
    url: '/api/templates/{template}/workflows',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::getWorkflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
getWorkflows.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        template: typeof args.template === 'object'
                ? args.template.id
                : args.template,
                }

    return getWorkflows.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::getWorkflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
getWorkflows.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getWorkflows.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::getWorkflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
getWorkflows.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getWorkflows.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::getWorkflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
    const getWorkflowsForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getWorkflows.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::getWorkflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
        getWorkflowsForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getWorkflows.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::getWorkflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
        getWorkflowsForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getWorkflows.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getWorkflows.form = getWorkflowsForm
const DynamicJobController = { create, executeTransition, getAvailableTransitions, getFieldRules, validateFieldData, getFormSchema, getWorkflows }

export default DynamicJobController