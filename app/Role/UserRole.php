<?php

namespace App\Role;

/***
 * Class UserRole
 * @package App\Role
 */
class UserRole {

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_BLOGGER = 'ROLE_BLOGGER';
    const ROLE_SUPPORT = 'ROLE_SUPPORT';
    const ROLE_VERIFIED = 'ROLE_VERIFIED';
    const ROLE_SEO = 'ROLE_SEO';

    /**
     * @var array
     */
    protected static $roleHierarchy = [
        self::ROLE_ADMIN => [
            self::ROLE_MANAGER,
            self::ROLE_SEO
        ],

        self::ROLE_MANAGER => [
            self::ROLE_BLOGGER,
            self::ROLE_SUPPORT,
        ],

        self::ROLE_BLOGGER => [
            self::ROLE_VERIFIED,
        ],

        self::ROLE_SEO => [],

        self::ROLE_SUPPORT => [
            self::ROLE_VERIFIED,
        ],

        self::ROLE_VERIFIED => [],
    ];

    public static function getRoles(string $role, array $known_roles = [])
    {
        $roles = [$role];
        if(isset(self::$roleHierarchy[$role])) {
            foreach(self::$roleHierarchy[$role] as $r) {
                if (!in_array($r, $known_roles)) {
                    $roles = array_merge($roles, static::getRoles($r, $roles));
                }
            }
        }
        return array_unique($roles);
    }

//    public static function _getRoles(array $known_roles) {
//        foreach ($known_roles as $known_role) {
//            // ...
//        }
//    }

    /**
     * @param string $role
     * @return array
     */
    public static function getAllowedRoles(string $role)
    {
        if (isset(self::$roleHierarchy[$role])) {
            return self::$roleHierarchy[$role];
        }

        return [];
    }

    /***
     * @return array
     */
    public static function getRoleList()
    {
        return [
            static::ROLE_ADMIN =>'Admin',
            static::ROLE_MANAGEMENT => 'Management',
            static::ROLE_ACCOUNT_MANAGER => 'Account Manager',
            static::ROLE_FINANCE => 'Finance',
            static::ROLE_SUPPORT => 'Support',
        ];
    }

}
