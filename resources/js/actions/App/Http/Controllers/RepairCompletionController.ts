import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\RepairCompletionController::create
 * @see app/Http/Controllers/RepairCompletionController.php:25
 * @route '/jobs/{job}/completion/create'
 */
export const create = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/jobs/{job}/completion/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::create
 * @see app/Http/Controllers/RepairCompletionController.php:25
 * @route '/jobs/{job}/completion/create'
 */
create.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return create.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::create
 * @see app/Http/Controllers/RepairCompletionController.php:25
 * @route '/jobs/{job}/completion/create'
 */
create.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RepairCompletionController::create
 * @see app/Http/Controllers/RepairCompletionController.php:25
 * @route '/jobs/{job}/completion/create'
 */
create.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::create
 * @see app/Http/Controllers/RepairCompletionController.php:25
 * @route '/jobs/{job}/completion/create'
 */
    const createForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::create
 * @see app/Http/Controllers/RepairCompletionController.php:25
 * @route '/jobs/{job}/completion/create'
 */
        createForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RepairCompletionController::create
 * @see app/Http/Controllers/RepairCompletionController.php:25
 * @route '/jobs/{job}/completion/create'
 */
        createForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\RepairCompletionController::store
 * @see app/Http/Controllers/RepairCompletionController.php:49
 * @route '/jobs/{job}/completion'
 */
export const store = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/jobs/{job}/completion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::store
 * @see app/Http/Controllers/RepairCompletionController.php:49
 * @route '/jobs/{job}/completion'
 */
store.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::store
 * @see app/Http/Controllers/RepairCompletionController.php:49
 * @route '/jobs/{job}/completion'
 */
store.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::store
 * @see app/Http/Controllers/RepairCompletionController.php:49
 * @route '/jobs/{job}/completion'
 */
    const storeForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::store
 * @see app/Http/Controllers/RepairCompletionController.php:49
 * @route '/jobs/{job}/completion'
 */
        storeForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\RepairCompletionController::show
 * @see app/Http/Controllers/RepairCompletionController.php:64
 * @route '/completion/{report}'
 */
export const show = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/completion/{report}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::show
 * @see app/Http/Controllers/RepairCompletionController.php:64
 * @route '/completion/{report}'
 */
show.url = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: typeof args.report === 'object'
                ? args.report.id
                : args.report,
                }

    return show.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::show
 * @see app/Http/Controllers/RepairCompletionController.php:64
 * @route '/completion/{report}'
 */
show.get = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RepairCompletionController::show
 * @see app/Http/Controllers/RepairCompletionController.php:64
 * @route '/completion/{report}'
 */
show.head = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::show
 * @see app/Http/Controllers/RepairCompletionController.php:64
 * @route '/completion/{report}'
 */
    const showForm = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::show
 * @see app/Http/Controllers/RepairCompletionController.php:64
 * @route '/completion/{report}'
 */
        showForm.get = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RepairCompletionController::show
 * @see app/Http/Controllers/RepairCompletionController.php:64
 * @route '/completion/{report}'
 */
        showForm.head = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\RepairCompletionController::edit
 * @see app/Http/Controllers/RepairCompletionController.php:85
 * @route '/completion/{report}/edit'
 */
export const edit = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/completion/{report}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::edit
 * @see app/Http/Controllers/RepairCompletionController.php:85
 * @route '/completion/{report}/edit'
 */
edit.url = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: typeof args.report === 'object'
                ? args.report.id
                : args.report,
                }

    return edit.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::edit
 * @see app/Http/Controllers/RepairCompletionController.php:85
 * @route '/completion/{report}/edit'
 */
edit.get = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RepairCompletionController::edit
 * @see app/Http/Controllers/RepairCompletionController.php:85
 * @route '/completion/{report}/edit'
 */
edit.head = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::edit
 * @see app/Http/Controllers/RepairCompletionController.php:85
 * @route '/completion/{report}/edit'
 */
    const editForm = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::edit
 * @see app/Http/Controllers/RepairCompletionController.php:85
 * @route '/completion/{report}/edit'
 */
        editForm.get = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RepairCompletionController::edit
 * @see app/Http/Controllers/RepairCompletionController.php:85
 * @route '/completion/{report}/edit'
 */
        editForm.head = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\RepairCompletionController::update
 * @see app/Http/Controllers/RepairCompletionController.php:114
 * @route '/completion/{report}'
 */
export const update = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/completion/{report}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::update
 * @see app/Http/Controllers/RepairCompletionController.php:114
 * @route '/completion/{report}'
 */
update.url = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: typeof args.report === 'object'
                ? args.report.id
                : args.report,
                }

    return update.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::update
 * @see app/Http/Controllers/RepairCompletionController.php:114
 * @route '/completion/{report}'
 */
update.put = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\RepairCompletionController::update
 * @see app/Http/Controllers/RepairCompletionController.php:114
 * @route '/completion/{report}'
 */
update.patch = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::update
 * @see app/Http/Controllers/RepairCompletionController.php:114
 * @route '/completion/{report}'
 */
    const updateForm = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::update
 * @see app/Http/Controllers/RepairCompletionController.php:114
 * @route '/completion/{report}'
 */
        updateForm.put = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\RepairCompletionController::update
 * @see app/Http/Controllers/RepairCompletionController.php:114
 * @route '/completion/{report}'
 */
        updateForm.patch = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\RepairCompletionController::destroy
 * @see app/Http/Controllers/RepairCompletionController.php:0
 * @route '/completion/{report}'
 */
export const destroy = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/completion/{report}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::destroy
 * @see app/Http/Controllers/RepairCompletionController.php:0
 * @route '/completion/{report}'
 */
destroy.url = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: args.report,
                }

    return destroy.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::destroy
 * @see app/Http/Controllers/RepairCompletionController.php:0
 * @route '/completion/{report}'
 */
destroy.delete = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::destroy
 * @see app/Http/Controllers/RepairCompletionController.php:0
 * @route '/completion/{report}'
 */
    const destroyForm = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::destroy
 * @see app/Http/Controllers/RepairCompletionController.php:0
 * @route '/completion/{report}'
 */
        destroyForm.delete = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\RepairCompletionController::sign
 * @see app/Http/Controllers/RepairCompletionController.php:130
 * @route '/completion/{report}/sign'
 */
export const sign = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sign.url(args, options),
    method: 'post',
})

sign.definition = {
    methods: ["post"],
    url: '/completion/{report}/sign',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::sign
 * @see app/Http/Controllers/RepairCompletionController.php:130
 * @route '/completion/{report}/sign'
 */
sign.url = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: typeof args.report === 'object'
                ? args.report.id
                : args.report,
                }

    return sign.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::sign
 * @see app/Http/Controllers/RepairCompletionController.php:130
 * @route '/completion/{report}/sign'
 */
sign.post = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sign.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::sign
 * @see app/Http/Controllers/RepairCompletionController.php:130
 * @route '/completion/{report}/sign'
 */
    const signForm = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sign.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::sign
 * @see app/Http/Controllers/RepairCompletionController.php:130
 * @route '/completion/{report}/sign'
 */
        signForm.post = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sign.url(args, options),
            method: 'post',
        })
    
    sign.form = signForm
/**
* @see \App\Http\Controllers\RepairCompletionController::submitForReview
 * @see app/Http/Controllers/RepairCompletionController.php:147
 * @route '/completion/{report}/submit'
 */
export const submitForReview = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submitForReview.url(args, options),
    method: 'post',
})

submitForReview.definition = {
    methods: ["post"],
    url: '/completion/{report}/submit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::submitForReview
 * @see app/Http/Controllers/RepairCompletionController.php:147
 * @route '/completion/{report}/submit'
 */
submitForReview.url = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: typeof args.report === 'object'
                ? args.report.id
                : args.report,
                }

    return submitForReview.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::submitForReview
 * @see app/Http/Controllers/RepairCompletionController.php:147
 * @route '/completion/{report}/submit'
 */
submitForReview.post = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submitForReview.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::submitForReview
 * @see app/Http/Controllers/RepairCompletionController.php:147
 * @route '/completion/{report}/submit'
 */
    const submitForReviewForm = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: submitForReview.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::submitForReview
 * @see app/Http/Controllers/RepairCompletionController.php:147
 * @route '/completion/{report}/submit'
 */
        submitForReviewForm.post = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: submitForReview.url(args, options),
            method: 'post',
        })
    
    submitForReview.form = submitForReviewForm
/**
* @see \App\Http\Controllers\RepairCompletionController::addPart
 * @see app/Http/Controllers/RepairCompletionController.php:165
 * @route '/completion/{report}/parts'
 */
export const addPart = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addPart.url(args, options),
    method: 'post',
})

addPart.definition = {
    methods: ["post"],
    url: '/completion/{report}/parts',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::addPart
 * @see app/Http/Controllers/RepairCompletionController.php:165
 * @route '/completion/{report}/parts'
 */
addPart.url = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: typeof args.report === 'object'
                ? args.report.id
                : args.report,
                }

    return addPart.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::addPart
 * @see app/Http/Controllers/RepairCompletionController.php:165
 * @route '/completion/{report}/parts'
 */
addPart.post = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addPart.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::addPart
 * @see app/Http/Controllers/RepairCompletionController.php:165
 * @route '/completion/{report}/parts'
 */
    const addPartForm = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: addPart.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::addPart
 * @see app/Http/Controllers/RepairCompletionController.php:165
 * @route '/completion/{report}/parts'
 */
        addPartForm.post = (args: { report: number | { id: number } } | [report: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: addPart.url(args, options),
            method: 'post',
        })
    
    addPart.form = addPartForm
/**
* @see \App\Http\Controllers\RepairCompletionController::removePart
 * @see app/Http/Controllers/RepairCompletionController.php:189
 * @route '/completion/{report}/parts/{partIndex}'
 */
export const removePart = (args: { report: number | { id: number }, partIndex: string | number } | [report: number | { id: number }, partIndex: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removePart.url(args, options),
    method: 'delete',
})

removePart.definition = {
    methods: ["delete"],
    url: '/completion/{report}/parts/{partIndex}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\RepairCompletionController::removePart
 * @see app/Http/Controllers/RepairCompletionController.php:189
 * @route '/completion/{report}/parts/{partIndex}'
 */
removePart.url = (args: { report: number | { id: number }, partIndex: string | number } | [report: number | { id: number }, partIndex: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                    partIndex: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: typeof args.report === 'object'
                ? args.report.id
                : args.report,
                                partIndex: args.partIndex,
                }

    return removePart.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace('{partIndex}', parsedArgs.partIndex.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepairCompletionController::removePart
 * @see app/Http/Controllers/RepairCompletionController.php:189
 * @route '/completion/{report}/parts/{partIndex}'
 */
removePart.delete = (args: { report: number | { id: number }, partIndex: string | number } | [report: number | { id: number }, partIndex: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removePart.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\RepairCompletionController::removePart
 * @see app/Http/Controllers/RepairCompletionController.php:189
 * @route '/completion/{report}/parts/{partIndex}'
 */
    const removePartForm = (args: { report: number | { id: number }, partIndex: string | number } | [report: number | { id: number }, partIndex: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: removePart.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RepairCompletionController::removePart
 * @see app/Http/Controllers/RepairCompletionController.php:189
 * @route '/completion/{report}/parts/{partIndex}'
 */
        removePartForm.delete = (args: { report: number | { id: number }, partIndex: string | number } | [report: number | { id: number }, partIndex: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: removePart.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    removePart.form = removePartForm
const RepairCompletionController = { create, store, show, edit, update, destroy, sign, submitForReview, addPart, removePart }

export default RepairCompletionController