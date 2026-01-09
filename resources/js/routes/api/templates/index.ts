import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\DynamicJobController::validate
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
export const validate = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validate.url(args, options),
    method: 'post',
})

validate.definition = {
    methods: ["post"],
    url: '/api/templates/{template}/validate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DynamicJobController::validate
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
validate.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return validate.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::validate
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
validate.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validate.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::validate
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
    const validateForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: validate.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::validate
 * @see app/Http/Controllers/DynamicJobController.php:249
 * @route '/api/templates/{template}/validate'
 */
        validateForm.post = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: validate.url(args, options),
            method: 'post',
        })
    
    validate.form = validateForm
/**
* @see \App\Http\Controllers\DynamicJobController::schema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
export const schema = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schema.url(args, options),
    method: 'get',
})

schema.definition = {
    methods: ["get","head"],
    url: '/api/templates/{template}/schema',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::schema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
schema.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return schema.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::schema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
schema.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schema.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::schema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
schema.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: schema.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::schema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
    const schemaForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: schema.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::schema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
        schemaForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: schema.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::schema
 * @see app/Http/Controllers/DynamicJobController.php:269
 * @route '/api/templates/{template}/schema'
 */
        schemaForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: schema.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    schema.form = schemaForm
/**
* @see \App\Http\Controllers\DynamicJobController::workflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
export const workflows = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: workflows.url(args, options),
    method: 'get',
})

workflows.definition = {
    methods: ["get","head"],
    url: '/api/templates/{template}/workflows',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DynamicJobController::workflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
workflows.url = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return workflows.definition.url
            .replace('{template}', parsedArgs.template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DynamicJobController::workflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
workflows.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: workflows.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DynamicJobController::workflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
workflows.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: workflows.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DynamicJobController::workflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
    const workflowsForm = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: workflows.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DynamicJobController::workflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
        workflowsForm.get = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: workflows.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DynamicJobController::workflows
 * @see app/Http/Controllers/DynamicJobController.php:279
 * @route '/api/templates/{template}/workflows'
 */
        workflowsForm.head = (args: { template: number | { id: number } } | [template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: workflows.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    workflows.form = workflowsForm
const templates = {
    validate: Object.assign(validate, validate),
schema: Object.assign(schema, schema),
workflows: Object.assign(workflows, workflows),
}

export default templates