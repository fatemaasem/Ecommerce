<?php

namespace App\Mappers;

use App\DTOs\UserDTO;
use App\Models\Role;
use App\Models\User;

/**
 * Class UserMapper
 * 
 * This class is responsible for mapping User model data to a UserDTO object.
 */
class UserMapper
{
    /**
     * Convert a User model instance into a UserDTO object.
     *
     * @param User $user The User model instance.
     * @return UserDTO The corresponding UserDTO object.
     */
    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            $user->name,      // User's name
            $user->email,     // User's email
            $user->password,  // User's password (hashed)
            $user->role_id,   // Role ID of the user
            $user->role->name // Role name associated with the user
        );
    }

    public static function toResponse(User $user): array
    {
        return [
            'name'=>$user->name,
            'email' => $user->email,
            'role'=>Role::find($user->role_id)->name
        ];
    }
}
