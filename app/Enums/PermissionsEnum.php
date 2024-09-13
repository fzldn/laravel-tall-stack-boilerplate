<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case USERS_CREATE = 'users.create';
    case USERS_VIEWANY = 'users.viewAny';
    case USERS_VIEW = 'users.view';
    case USERS_UPDATE = 'users.update';
    case USERS_DELETE = 'users.delete';
    case USERS_DELETEANY = 'users.deleteAny';

    case ROLES_CREATE = 'roles.create';
    case ROLES_VIEWANY = 'roles.viewAny';
    case ROLES_VIEW = 'roles.view';
    case ROLES_UPDATE = 'roles.update';
    case ROLES_DELETE = 'roles.delete';
    case ROLES_DELETEANY = 'roles.deleteAny';

    public function label(): string
    {
        return match ($this) {
            self::USERS_CREATE => __('permissions.users.create'),
            self::USERS_VIEWANY => __('permissions.users.viewAny'),
            self::USERS_VIEW => __('permissions.users.view'),
            self::USERS_UPDATE => __('permissions.users.update'),
            self::USERS_DELETE => __('permissions.users.delete'),
            self::USERS_DELETEANY => __('permissions.users.deleteAny'),

            self::ROLES_CREATE => __('permissions.roles.create'),
            self::ROLES_VIEWANY => __('permissions.roles.viewAny'),
            self::ROLES_VIEW => __('permissions.roles.view'),
            self::ROLES_UPDATE => __('permissions.roles.update'),
            self::ROLES_DELETE => __('permissions.roles.delete'),
            self::ROLES_DELETEANY => __('permissions.roles.deleteAny'),

            default => $this->value,
        };
    }
}
