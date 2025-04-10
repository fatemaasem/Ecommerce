<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTOs\OfferDTO;

use App\Http\Requests\CreateOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Services\OfferService;
use Illuminate\Http\JsonResponse;

class OfferController extends ApiController
{

        public function __construct(private OfferService $offerService) {}
    
        public function index(): JsonResponse
        {
            $result = $this->offerService->getAllOffers();
            return ApiController::successResponse([
                'data'=>$result,
                'message'=>'Offers fetched successfully',
            ]);
        }
    
        public function store(CreateOfferRequest $request): JsonResponse
        {
            $result = $this->offerService->createOffer(
                OfferDTO::fromRequest($request->validated())
            );
            
         
            return ApiController::successResponse([
                'data'=>$result,
                'message'=>'Offer created successfully',
            ],201);
        }
    
        public function show(int $id): JsonResponse
        {
            $result = $this->offerService->getOfferById($id);
            
           
            return ApiController::successResponse([
                'data'=>$result,
                'message'=>'Offer fetched successfully',
            ]);
        }
    
        public function update(UpdateOfferRequest $request, int $id): JsonResponse
        {
            $result = $this->offerService->updateOffer(
                $id,
                OfferDTO::fromRequest(array_merge($request->validated(), ['id' => $id]))
            );
            
            return ApiController::successResponse([
                'message' => 'Offer updated successfully',
            ]);
        }
    
        public function destroy(int $id): JsonResponse
        {
            $success = $this->offerService->deleteOffer($id);
    
            return ApiController::successResponse([
                'message' => 'Offer deleted successfully',
            ]);
        }
}
    

