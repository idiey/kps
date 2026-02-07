import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\SettingsController::index
 * @see app/Http/Controllers/Admin/SettingsController.php:21
 * @route '/admin/settings'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SettingsController::index
 * @see app/Http/Controllers/Admin/SettingsController.php:21
 * @route '/admin/settings'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingsController::index
 * @see app/Http/Controllers/Admin/SettingsController.php:21
 * @route '/admin/settings'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\SettingsController::index
 * @see app/Http/Controllers/Admin/SettingsController.php:21
 * @route '/admin/settings'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\SettingsController::index
 * @see app/Http/Controllers/Admin/SettingsController.php:21
 * @route '/admin/settings'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\SettingsController::index
 * @see app/Http/Controllers/Admin/SettingsController.php:21
 * @route '/admin/settings'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\SettingsController::index
 * @see app/Http/Controllers/Admin/SettingsController.php:21
 * @route '/admin/settings'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Admin\SettingsController::store
 * @see app/Http/Controllers/Admin/SettingsController.php:58
 * @route '/admin/settings'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/settings',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SettingsController::store
 * @see app/Http/Controllers/Admin/SettingsController.php:58
 * @route '/admin/settings'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingsController::store
 * @see app/Http/Controllers/Admin/SettingsController.php:58
 * @route '/admin/settings'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\SettingsController::store
 * @see app/Http/Controllers/Admin/SettingsController.php:58
 * @route '/admin/settings'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\SettingsController::store
 * @see app/Http/Controllers/Admin/SettingsController.php:58
 * @route '/admin/settings'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\SettingsController::update
 * @see app/Http/Controllers/Admin/SettingsController.php:33
 * @route '/admin/settings'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/admin/settings',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\SettingsController::update
 * @see app/Http/Controllers/Admin/SettingsController.php:33
 * @route '/admin/settings'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingsController::update
 * @see app/Http/Controllers/Admin/SettingsController.php:33
 * @route '/admin/settings'
 */
update.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\SettingsController::update
 * @see app/Http/Controllers/Admin/SettingsController.php:33
 * @route '/admin/settings'
 */
    const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\SettingsController::update
 * @see app/Http/Controllers/Admin/SettingsController.php:33
 * @route '/admin/settings'
 */
        updateForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Admin\SettingsController::destroy
 * @see app/Http/Controllers/Admin/SettingsController.php:76
 * @route '/admin/settings/{setting}'
 */
export const destroy = (args: { setting: number | { id: number } } | [setting: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/settings/{setting}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\SettingsController::destroy
 * @see app/Http/Controllers/Admin/SettingsController.php:76
 * @route '/admin/settings/{setting}'
 */
destroy.url = (args: { setting: number | { id: number } } | [setting: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { setting: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { setting: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    setting: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        setting: typeof args.setting === 'object'
                ? args.setting.id
                : args.setting,
                }

    return destroy.definition.url
            .replace('{setting}', parsedArgs.setting.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingsController::destroy
 * @see app/Http/Controllers/Admin/SettingsController.php:76
 * @route '/admin/settings/{setting}'
 */
destroy.delete = (args: { setting: number | { id: number } } | [setting: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\SettingsController::destroy
 * @see app/Http/Controllers/Admin/SettingsController.php:76
 * @route '/admin/settings/{setting}'
 */
    const destroyForm = (args: { setting: number | { id: number } } | [setting: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\SettingsController::destroy
 * @see app/Http/Controllers/Admin/SettingsController.php:76
 * @route '/admin/settings/{setting}'
 */
        destroyForm.delete = (args: { setting: number | { id: number } } | [setting: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\SettingsController::initialize
 * @see app/Http/Controllers/Admin/SettingsController.php:86
 * @route '/admin/settings/initialize'
 */
export const initialize = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initialize.url(options),
    method: 'post',
})

initialize.definition = {
    methods: ["post"],
    url: '/admin/settings/initialize',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\SettingsController::initialize
 * @see app/Http/Controllers/Admin/SettingsController.php:86
 * @route '/admin/settings/initialize'
 */
initialize.url = (options?: RouteQueryOptions) => {
    return initialize.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SettingsController::initialize
 * @see app/Http/Controllers/Admin/SettingsController.php:86
 * @route '/admin/settings/initialize'
 */
initialize.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initialize.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\SettingsController::initialize
 * @see app/Http/Controllers/Admin/SettingsController.php:86
 * @route '/admin/settings/initialize'
 */
    const initializeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: initialize.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\SettingsController::initialize
 * @see app/Http/Controllers/Admin/SettingsController.php:86
 * @route '/admin/settings/initialize'
 */
        initializeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: initialize.url(options),
            method: 'post',
        })
    
    initialize.form = initializeForm
const settings = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
initialize: Object.assign(initialize, initialize),
}

export default settings