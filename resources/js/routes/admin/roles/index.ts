import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import permissions7d3099 from './permissions'
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::index
 * @see app/Http/Controllers/Admin/RoleManagementController.php:22
 * @route '/admin/roles'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::index
 * @see app/Http/Controllers/Admin/RoleManagementController.php:22
 * @route '/admin/roles'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::index
 * @see app/Http/Controllers/Admin/RoleManagementController.php:22
 * @route '/admin/roles'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::index
 * @see app/Http/Controllers/Admin/RoleManagementController.php:22
 * @route '/admin/roles'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::index
 * @see app/Http/Controllers/Admin/RoleManagementController.php:22
 * @route '/admin/roles'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::index
 * @see app/Http/Controllers/Admin/RoleManagementController.php:22
 * @route '/admin/roles'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::index
 * @see app/Http/Controllers/Admin/RoleManagementController.php:22
 * @route '/admin/roles'
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
* @see \App\Http\Controllers\Admin\RoleManagementController::create
 * @see app/Http/Controllers/Admin/RoleManagementController.php:36
 * @route '/admin/roles/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::create
 * @see app/Http/Controllers/Admin/RoleManagementController.php:36
 * @route '/admin/roles/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::create
 * @see app/Http/Controllers/Admin/RoleManagementController.php:36
 * @route '/admin/roles/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::create
 * @see app/Http/Controllers/Admin/RoleManagementController.php:36
 * @route '/admin/roles/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::create
 * @see app/Http/Controllers/Admin/RoleManagementController.php:36
 * @route '/admin/roles/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::create
 * @see app/Http/Controllers/Admin/RoleManagementController.php:36
 * @route '/admin/roles/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::create
 * @see app/Http/Controllers/Admin/RoleManagementController.php:36
 * @route '/admin/roles/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::store
 * @see app/Http/Controllers/Admin/RoleManagementController.php:48
 * @route '/admin/roles'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/roles',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::store
 * @see app/Http/Controllers/Admin/RoleManagementController.php:48
 * @route '/admin/roles'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::store
 * @see app/Http/Controllers/Admin/RoleManagementController.php:48
 * @route '/admin/roles'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::store
 * @see app/Http/Controllers/Admin/RoleManagementController.php:48
 * @route '/admin/roles'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::store
 * @see app/Http/Controllers/Admin/RoleManagementController.php:48
 * @route '/admin/roles'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::show
 * @see app/Http/Controllers/Admin/RoleManagementController.php:78
 * @route '/admin/roles/{role}'
 */
export const show = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/roles/{role}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::show
 * @see app/Http/Controllers/Admin/RoleManagementController.php:78
 * @route '/admin/roles/{role}'
 */
show.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return show.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::show
 * @see app/Http/Controllers/Admin/RoleManagementController.php:78
 * @route '/admin/roles/{role}'
 */
show.get = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::show
 * @see app/Http/Controllers/Admin/RoleManagementController.php:78
 * @route '/admin/roles/{role}'
 */
show.head = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::show
 * @see app/Http/Controllers/Admin/RoleManagementController.php:78
 * @route '/admin/roles/{role}'
 */
    const showForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::show
 * @see app/Http/Controllers/Admin/RoleManagementController.php:78
 * @route '/admin/roles/{role}'
 */
        showForm.get = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::show
 * @see app/Http/Controllers/Admin/RoleManagementController.php:78
 * @route '/admin/roles/{role}'
 */
        showForm.head = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\RoleManagementController::edit
 * @see app/Http/Controllers/Admin/RoleManagementController.php:90
 * @route '/admin/roles/{role}/edit'
 */
export const edit = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/roles/{role}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::edit
 * @see app/Http/Controllers/Admin/RoleManagementController.php:90
 * @route '/admin/roles/{role}/edit'
 */
edit.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return edit.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::edit
 * @see app/Http/Controllers/Admin/RoleManagementController.php:90
 * @route '/admin/roles/{role}/edit'
 */
edit.get = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::edit
 * @see app/Http/Controllers/Admin/RoleManagementController.php:90
 * @route '/admin/roles/{role}/edit'
 */
edit.head = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::edit
 * @see app/Http/Controllers/Admin/RoleManagementController.php:90
 * @route '/admin/roles/{role}/edit'
 */
    const editForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::edit
 * @see app/Http/Controllers/Admin/RoleManagementController.php:90
 * @route '/admin/roles/{role}/edit'
 */
        editForm.get = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::edit
 * @see app/Http/Controllers/Admin/RoleManagementController.php:90
 * @route '/admin/roles/{role}/edit'
 */
        editForm.head = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\RoleManagementController::update
 * @see app/Http/Controllers/Admin/RoleManagementController.php:108
 * @route '/admin/roles/{role}'
 */
export const update = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/roles/{role}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::update
 * @see app/Http/Controllers/Admin/RoleManagementController.php:108
 * @route '/admin/roles/{role}'
 */
update.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return update.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::update
 * @see app/Http/Controllers/Admin/RoleManagementController.php:108
 * @route '/admin/roles/{role}'
 */
update.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::update
 * @see app/Http/Controllers/Admin/RoleManagementController.php:108
 * @route '/admin/roles/{role}'
 */
update.patch = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::update
 * @see app/Http/Controllers/Admin/RoleManagementController.php:108
 * @route '/admin/roles/{role}'
 */
    const updateForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::update
 * @see app/Http/Controllers/Admin/RoleManagementController.php:108
 * @route '/admin/roles/{role}'
 */
        updateForm.put = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::update
 * @see app/Http/Controllers/Admin/RoleManagementController.php:108
 * @route '/admin/roles/{role}'
 */
        updateForm.patch = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\RoleManagementController::destroy
 * @see app/Http/Controllers/Admin/RoleManagementController.php:163
 * @route '/admin/roles/{role}'
 */
export const destroy = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/roles/{role}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::destroy
 * @see app/Http/Controllers/Admin/RoleManagementController.php:163
 * @route '/admin/roles/{role}'
 */
destroy.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return destroy.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::destroy
 * @see app/Http/Controllers/Admin/RoleManagementController.php:163
 * @route '/admin/roles/{role}'
 */
destroy.delete = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::destroy
 * @see app/Http/Controllers/Admin/RoleManagementController.php:163
 * @route '/admin/roles/{role}'
 */
    const destroyForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::destroy
 * @see app/Http/Controllers/Admin/RoleManagementController.php:163
 * @route '/admin/roles/{role}'
 */
        destroyForm.delete = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\RoleManagementController::permissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:204
 * @route '/admin/roles-permissions'
 */
export const permissions = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: permissions.url(options),
    method: 'get',
})

permissions.definition = {
    methods: ["get","head"],
    url: '/admin/roles-permissions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::permissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:204
 * @route '/admin/roles-permissions'
 */
permissions.url = (options?: RouteQueryOptions) => {
    return permissions.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::permissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:204
 * @route '/admin/roles-permissions'
 */
permissions.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: permissions.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::permissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:204
 * @route '/admin/roles-permissions'
 */
permissions.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: permissions.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::permissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:204
 * @route '/admin/roles-permissions'
 */
    const permissionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: permissions.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::permissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:204
 * @route '/admin/roles-permissions'
 */
        permissionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: permissions.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::permissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:204
 * @route '/admin/roles-permissions'
 */
        permissionsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: permissions.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    permissions.form = permissionsForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::updatePermissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:188
 * @route '/admin/roles/{role}/permissions'
 */
export const updatePermissions = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updatePermissions.url(args, options),
    method: 'patch',
})

updatePermissions.definition = {
    methods: ["patch"],
    url: '/admin/roles/{role}/permissions',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::updatePermissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:188
 * @route '/admin/roles/{role}/permissions'
 */
updatePermissions.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return updatePermissions.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::updatePermissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:188
 * @route '/admin/roles/{role}/permissions'
 */
updatePermissions.patch = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updatePermissions.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::updatePermissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:188
 * @route '/admin/roles/{role}/permissions'
 */
    const updatePermissionsForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updatePermissions.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::updatePermissions
 * @see app/Http/Controllers/Admin/RoleManagementController.php:188
 * @route '/admin/roles/{role}/permissions'
 */
        updatePermissionsForm.patch = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updatePermissions.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updatePermissions.form = updatePermissionsForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::deactivate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:268
 * @route '/admin/roles/{role}/deactivate'
 */
export const deactivate = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deactivate.url(args, options),
    method: 'post',
})

deactivate.definition = {
    methods: ["post"],
    url: '/admin/roles/{role}/deactivate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::deactivate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:268
 * @route '/admin/roles/{role}/deactivate'
 */
deactivate.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return deactivate.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::deactivate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:268
 * @route '/admin/roles/{role}/deactivate'
 */
deactivate.post = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deactivate.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::deactivate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:268
 * @route '/admin/roles/{role}/deactivate'
 */
    const deactivateForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: deactivate.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::deactivate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:268
 * @route '/admin/roles/{role}/deactivate'
 */
        deactivateForm.post = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: deactivate.url(args, options),
            method: 'post',
        })
    
    deactivate.form = deactivateForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::activate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:284
 * @route '/admin/roles/{role}/activate'
 */
export const activate = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

activate.definition = {
    methods: ["post"],
    url: '/admin/roles/{role}/activate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::activate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:284
 * @route '/admin/roles/{role}/activate'
 */
activate.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return activate.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::activate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:284
 * @route '/admin/roles/{role}/activate'
 */
activate.post = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::activate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:284
 * @route '/admin/roles/{role}/activate'
 */
    const activateForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: activate.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::activate
 * @see app/Http/Controllers/Admin/RoleManagementController.php:284
 * @route '/admin/roles/{role}/activate'
 */
        activateForm.post = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: activate.url(args, options),
            method: 'post',
        })
    
    activate.form = activateForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::users
 * @see app/Http/Controllers/Admin/RoleManagementController.php:294
 * @route '/admin/roles/{role}/users'
 */
export const users = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(args, options),
    method: 'get',
})

users.definition = {
    methods: ["get","head"],
    url: '/admin/roles/{role}/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::users
 * @see app/Http/Controllers/Admin/RoleManagementController.php:294
 * @route '/admin/roles/{role}/users'
 */
users.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return users.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::users
 * @see app/Http/Controllers/Admin/RoleManagementController.php:294
 * @route '/admin/roles/{role}/users'
 */
users.get = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::users
 * @see app/Http/Controllers/Admin/RoleManagementController.php:294
 * @route '/admin/roles/{role}/users'
 */
users.head = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: users.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::users
 * @see app/Http/Controllers/Admin/RoleManagementController.php:294
 * @route '/admin/roles/{role}/users'
 */
    const usersForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: users.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::users
 * @see app/Http/Controllers/Admin/RoleManagementController.php:294
 * @route '/admin/roles/{role}/users'
 */
        usersForm.get = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: users.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::users
 * @see app/Http/Controllers/Admin/RoleManagementController.php:294
 * @route '/admin/roles/{role}/users'
 */
        usersForm.head = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: users.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    users.form = usersForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::assignUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:308
 * @route '/admin/roles/{role}/users'
 */
export const assignUsers = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assignUsers.url(args, options),
    method: 'post',
})

assignUsers.definition = {
    methods: ["post"],
    url: '/admin/roles/{role}/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::assignUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:308
 * @route '/admin/roles/{role}/users'
 */
assignUsers.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return assignUsers.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::assignUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:308
 * @route '/admin/roles/{role}/users'
 */
assignUsers.post = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assignUsers.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::assignUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:308
 * @route '/admin/roles/{role}/users'
 */
    const assignUsersForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: assignUsers.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::assignUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:308
 * @route '/admin/roles/{role}/users'
 */
        assignUsersForm.post = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: assignUsers.url(args, options),
            method: 'post',
        })
    
    assignUsers.form = assignUsersForm
/**
* @see \App\Http\Controllers\Admin\RoleManagementController::removeUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:327
 * @route '/admin/roles/{role}/users'
 */
export const removeUsers = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removeUsers.url(args, options),
    method: 'delete',
})

removeUsers.definition = {
    methods: ["delete"],
    url: '/admin/roles/{role}/users',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::removeUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:327
 * @route '/admin/roles/{role}/users'
 */
removeUsers.url = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { role: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { role: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    role: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        role: typeof args.role === 'object'
                ? args.role.id
                : args.role,
                }

    return removeUsers.definition.url
            .replace('{role}', parsedArgs.role.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\RoleManagementController::removeUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:327
 * @route '/admin/roles/{role}/users'
 */
removeUsers.delete = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removeUsers.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\RoleManagementController::removeUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:327
 * @route '/admin/roles/{role}/users'
 */
    const removeUsersForm = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: removeUsers.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\RoleManagementController::removeUsers
 * @see app/Http/Controllers/Admin/RoleManagementController.php:327
 * @route '/admin/roles/{role}/users'
 */
        removeUsersForm.delete = (args: { role: number | { id: number } } | [role: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: removeUsers.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    removeUsers.form = removeUsersForm
const roles = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
permissions: Object.assign(permissions, permissions7d3099),
updatePermissions: Object.assign(updatePermissions, updatePermissions),
deactivate: Object.assign(deactivate, deactivate),
activate: Object.assign(activate, activate),
users: Object.assign(users, users),
assignUsers: Object.assign(assignUsers, assignUsers),
removeUsers: Object.assign(removeUsers, removeUsers),
}

export default roles