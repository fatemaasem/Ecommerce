<?php

namespace App\DTOs;

use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserDTO
{
    /**
     * Data Transfer Object (DTO) for user information.
     *
     * This class is used to transfer user data between layers 
     * without exposing the actual model.
     */

    /**
     * Constructor to initialize user properties.
     *
     * @param string|null $name The name of the user (nullable).
     * @param string $email The email address of the user.
     * @param string $password The user's password.
     * @param int|null $role_id The role ID assigned to the user (nullable).
     */
    public function __construct(
        public readonly ?string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?int $role_id,
        public readonly ?string $role_name,
    ) {}

    /**
     * Create a new UserDTO instance from an array request.
     *
     * @param \Illuminate\Http\Request $request The request containing user data.
     * @return self A new instance of UserDTO.
     */
    public static function fromArray($request): self
    {
        $role_id = $request->input('role_id');
        $role_name = $role_id ? optional(Role::find($role_id))->name : null; // تجنب findOrFail لمنع الخطأ
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
            role_id: $role_id ? (int) $role_id : null,
            role_name: $role_name
        );
    }

    /**
     * Convert the UserDTO instance to an array format.
     *
     * @return array The user data as an associative array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password), // Hash the password for security
            'role_id' => $this->role_id,
        ];
    }
}
