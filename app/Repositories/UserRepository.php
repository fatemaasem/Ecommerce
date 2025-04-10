<?php 

namespace App\Repositories;

use App\DTOs\UserDTO;
use App\Exceptions\CustomExceptions;
use App\Interfaces\UserRepositoryInterface;
use App\Mappers\UserMapper;
use App\Models\User;

use Illuminate\Database\QueryException;
/**
 * Repository class for handling user-related database operations.
 * 
 * This class follows the Repository Pattern to abstract data access logic.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user in the database.
     *
     * @param UserDTO $userDTO The data transfer object containing user information.
     * @return User The newly created user instance.
     */
    public function createUser(UserDTO $userDTO): User
    {
        try{
        // Convert UserDTO object to an array and create a new user record.
        $user = User::create($userDTO->toArray());
        
        return $user; // Return the created user instance.
        }
        catch( QueryException $e){
            throw CustomExceptions::queryError($e);
        }
    }
}
