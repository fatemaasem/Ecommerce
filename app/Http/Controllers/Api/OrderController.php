<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateOrderDTO;
use App\DTOs\OrderDTO;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Mappers\OrderMapper;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends ApiController
{
    public function __construct(private OrderService $orderService) {}

    public function createOrderFromWishlist(CreateOrderRequest $request): JsonResponse
    {
        $user = Auth::guard('jwt')->user();
     
        $response = $this->orderService->createOrderFromWishlist( $user->id);
        return ApiController::successResponse([
            'data'=>$response,
            'message'=>'order created successfully',
        ],201);

        
    }

    public function getUserOrders(): JsonResponse
    {
       
        $user = Auth::guard('jwt')->user();
        $ordersRespnse = $this->orderService->getUserOrders($user->id);
       
        return ApiController::successResponse([
            'data'=>$ordersRespnse,
            'message'=>'orders fetched  successfully',
            'count'=>count($ordersRespnse['order'])

        ]);
        
    }

    public function getOrderDetails($id): JsonResponse
    {
        $user = Auth::guard('jwt')->user();
        $orderResponse = $this->orderService->getOrderDetails($id, $user->id);
      
        return ApiController::successResponse([
            'data'=>$orderResponse,
            'message'=>'orders fetched  successfully',
        ]);
    }

    public function updateOrderStatus(UpdateOrderStatusRequest $request, $id): JsonResponse
    {
        $response= $this->orderService->updateOrderStatus($id, $request->status);
        return response()->json([
            'message' => 'Order status updated successfully',
        ]);
    }

    public function getAllOrders(): JsonResponse
    {
        $orders = $this->orderService->getAllOrders();
        return ApiController::successResponse([
                'data'=> $orders,
                'message'=>'orders fetched  successfully',
                'count'=>count( $orders['order'])
            ]);

      
    }
}
