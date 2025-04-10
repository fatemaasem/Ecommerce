<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\DTOs\UserDTO;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;
class AuthController extends ApiController
{
    private AuthService $authService;

    /**
     * Constructor to inject AuthService dependency
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handles user registration
     * 
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    { 
       
            // Convert request data into a UserDTO object and register the user
            $userMessage = $this->authService->register(UserDTO::fromArray($request));

            return ApiController::successResponse([
                'data'=>$userMessage ,
                'message' => 'Register Successfully',
            ]);
           
       
    }

    /**
     * Handles user login
     * 
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    { 
     
            // Convert request data into a UserDTO object
            $userDto = UserDTO::fromArray($request);
          
            // Authenticate user with provided email and password
            $userMessage = $this->authService->login(['email' => $userDto->email, 'password' => $userDto->password]);

            return ApiController::successResponse([
                'data'=>$userMessage ,
                'message' => 'Login Successfully',
            ]);

          
       
    }

   /**
     * Handles user logout
     * 
     * This function logs out the currently authenticated user
     * and invalidates their token.
     * 
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        // Log out the user by invalidating their token
       $this->authService->logout();
        
        return ApiController::successResponse([
            'message' => 'Logout Successfully',
        ]);
    }
    /**
     * Refresh the JWT token for the authenticated user.
     *
     * This method generates a new token and returns it along with the user data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Call the auth service to refresh the token and retrieve the user data.
        $userMessage = $this->authService->refresh(); // Returns a new token and UserDTO.

        // Return the refreshed token and user details as a JSON response.
        return ApiController::successResponse([
            'data'=>$userMessage ,
            'message' => 'Refresh Successfully',
        ]);

    }


}
