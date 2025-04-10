<?php

namespace App\Http\Controllers\Api;

use App\DTOs\WishlistDTO;
use App\Exceptions\CustomExceptions;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreWishlistRequest;
use App\Http\Requests\UpdateWishlistRequest;
use App\Models\Wishlist;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends ApiController
{
    public function __construct(private WishlistService $wishlistService) {   }

    public function index(): JsonResponse
    {

        try {
            // This will throw AuthorizationException if not authorized
            $this->authorize('viewAny', Wishlist::class);

            $wishlists = $this->wishlistService->getUserWishlist(Auth::id());

            return ApiController::successResponse([
                'data' => $wishlists,
                'message' => 'Wishlists fetched successfully',
                'count' => count($wishlists['data'])
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Convert to your custom exception
            throw CustomExceptions::authorizationError();
        }

    }

    public function store(StoreWishlistRequest $request): JsonResponse
    {

        $wishlistDto=WishlistDTO::fromRequest(Auth::user()->id,$request->product_id,$request->quantity);

        $wishlistItem = $this->wishlistService->addToWishlist($wishlistDto);

        return ApiController::successResponse([
            'data'=>  $wishlistItem,
            'message'=>'Wishlists created successfully',
        ],201);
    }

    public function update(UpdateWishlistRequest $request, int $wishlistId): JsonResponse
    {

        $wishlistItem = $this->wishlistService->updateWishlist(
            $wishlistId,
            $request
        );

        return ApiController::successResponse([
            'message'=>'Wishlists updated successfully',
        ]);
    }

    public function destroy( int $wishlistId): JsonResponse
    {
        $this->wishlistService->removeFromWishlist($wishlistId);

        return ApiController::successResponse([
            'message'=>'Wishlists deleted successfully',
        ],201);
    }
}
