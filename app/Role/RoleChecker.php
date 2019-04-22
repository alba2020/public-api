<?php

namespace App\Role;

use App\User;

/**
 * Class RoleChecker
 * @package App\Role
 */
class RoleChecker
{
    /**
     * @param User $user
     * @param string $requiredRole
     * @return bool
     */
    public function check(User $user, string $requiredRole)
    {
//        // Admin has everything
//        if ($user->hasRole(UserRole::ROLE_ADMIN)) {
//            return true;
//        } // todo make recursive check
//        else if($user->hasRole(UserRole::ROLE_MANAGEMENT)) {
////        else {
//            $managementRoles = UserRole::getAllowedRoles(UserRole::ROLE_MANAGEMENT);
//
//            if (in_array($role, $managementRoles)) {
//                return true;
//            }
//        }
//        return $user->hasRole($role);

        foreach($user->roles as $_role) {
            foreach(UserRole::getRoles($_role) as $__role) {
                if ($__role == $requiredRole) {
                    return true;
                }
            }
        }

        return false;
    }
}
