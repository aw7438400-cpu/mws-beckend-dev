<?php

return [

    'defaults' => [
        'guard' => 'sanctum',
    ],

    'models' => [
        /*
         * Eloquent models to use for Permission and Role.
         * Harus class, bukan nama tabel.
         */
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'guards' => [
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],
    ],

    'column_names' => [
        'role_pivot_key' => 'role_uuid',
        'permission_pivot_key' => 'permission_uuid',
        'model_morph_key' => 'model_uuid',
        'team_foreign_key' => 'team_uuid',
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'primary_keys' => [
        'role' => 'uuid',
        'permission' => 'uuid',
    ],

    'register_permission_check_method' => true,

    'register_octane_reset_listener' => false,

    'events_enabled' => false,

    'teams' => false,

    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,

    'use_passport_client_credentials' => false,

    'display_permission_in_exception' => false,

    'display_role_in_exception' => false,

    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'model_key' => 'name',
        'store' => 'default',
    ],
];
