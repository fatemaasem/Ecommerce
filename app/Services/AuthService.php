<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Exceptions\CustomExceptions;
use App\Interfaces\UserRepositoryInterface;
use App\Mappers\UserMapper;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserDTO $userDTO)
    {
        try{
        $userModel=$this->userRepository->createUser($userDTO);
        $token = Auth::guard('jwt')->login($userModel);
        return $this->userMessege($token,$userModel);
        }
        catch(Exception $e){
            throw new CustomExceptions("Faild to create user :" . $e->getMessage(),500);
        }
      
    }
       

    public function login(array $credentials)
    {
        if(!Auth::guard('jwt')->attempt($credentials)){
            throw new CustomExceptions("Failed to login user (Invalid credentials) " , 401);
        }
        try {
            // محاولة المصادقة باستخدام الـ credentials
            $token = Auth::guard('jwt')->attempt($credentials);
    
           
            // إذا نجحت المصادقة، نحصل على بيانات المستخدم
            $userModel = Auth::guard('jwt')->user();
    
            // نرجع رسالة نجاح مع الـ token وبيانات المستخدم
            return $this->userMessege($token, $userModel);
    
        } catch (Exception $e) {
            // نتعامل مع أي استثناءات أخرى غير متوقعة
            throw new CustomExceptions("Failed to login user: " . $e->getMessage(), 500);
        }
    }
    public function logout(){
        try{
       return Auth::guard('jwt')->logout();}
       catch (Exception $e) {
        // نتعامل مع أي استثناءات أخرى غير متوقعة
        throw new CustomExceptions("Failed to logout user: " . $e->getMessage(), 500);
    }
    }
    public function refresh(){
        try{
        $newToken = JWTAuth::parseToken()->refresh();
        $userModel=Auth::guard('jwt')->user();
        return $this->userMessege($newToken, $userModel);}
        catch(Exception $e){
            // نتعامل مع أي استثناءات أخرى غير متوقعة
            throw new CustomExceptions("Failed to logout user: " . $e->getMessage(), 500);
        }
    }

    // return message

    public function userMessege($token,$user){
        return [
            'user' =>UserMapper::toResponse($user) ,
            'authorisation' => [
                'token' =>$token,
                'type' => 'bearer',
            ]
            ];
    }

   
    
}
