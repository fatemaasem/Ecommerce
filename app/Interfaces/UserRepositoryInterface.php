<?php

namespace App\Interfaces;

use App\DTOs\UserDTO;
use App\Models\User;

/**
 * Interface for the User Repository.
 * 
 * This interface defines the contract for handling user-related database operations.
 */
interface UserRepositoryInterface
{
    /**
     * Create a new user in the database.
     *
     * @param UserDTO $userDTO The data transfer object containing user information.
     * @return User The newly created user instance.
     */
    public function createUser(UserDTO $userDTO): User;
}
