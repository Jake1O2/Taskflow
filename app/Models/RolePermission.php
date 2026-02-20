<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    public const ADMIN = 'admin';
    public const EDITOR = 'editor';
    public const VIEWER = 'viewer';
    public const COMMENTER = 'commenter';

    public const PERMISSION_VIEW = 'view';
    public const PERMISSION_CREATE = 'create';
    public const PERMISSION_EDIT = 'edit';
    public const PERMISSION_DELETE = 'delete';
    public const PERMISSION_ASSIGN = 'assign';
    public const PERMISSION_COMMENT = 'comment';

    /**
     * Map of permissions by role.
     *
     * @var array<string, array<int, string>>
     */
    public static array $permissionsByRole = [
        self::ADMIN => [
            self::PERMISSION_VIEW,
            self::PERMISSION_CREATE,
            self::PERMISSION_EDIT,
            self::PERMISSION_DELETE,
            self::PERMISSION_ASSIGN,
            self::PERMISSION_COMMENT,
        ],
        self::EDITOR => [
            self::PERMISSION_VIEW,
            self::PERMISSION_CREATE,
            self::PERMISSION_EDIT,
            self::PERMISSION_ASSIGN,
            self::PERMISSION_COMMENT,
        ],
        self::VIEWER => [
            self::PERMISSION_VIEW,
        ],
        self::COMMENTER => [
            self::PERMISSION_VIEW,
            self::PERMISSION_COMMENT,
        ],
    ];

    /**
     * Supported team roles.
     *
     * @return array<int, string>
     */
    public static function roles(): array
    {
        return array_keys(self::$permissionsByRole);
    }

    /**
     * Get permissions for a role.
     *
     * @return array<int, string>
     */
    public static function permissionsForRole(?string $role): array
    {
        if (! $role) {
            return [];
        }

        return self::$permissionsByRole[$role] ?? [];
    }

    public static function roleHasPermission(?string $role, string $permission): bool
    {
        return in_array($permission, self::permissionsForRole($role), true);
    }
}
