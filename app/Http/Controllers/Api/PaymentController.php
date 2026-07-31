<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Ressources\PaymentResource;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentServiceInterface $paymentService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $payments = $this->paymentService->findByAdvertiser(auth()->id(), $request->query('per_page', 15));

        return response()->json(['data' => PaymentResource::collection($payments)]);
    }

    public function show(int $id): PaymentResource
    {
        $payment = $this->paymentService->findByAdvertiser(auth()->id())->where('id', $id)->firstOrFail();

        return new PaymentResource($payment);
    }

    public function store(StorePaymentRequest $request): PaymentResource
    {
        $payment = $this->paymentService->process($request->validatedDTO());

        return new PaymentResource($payment);
    }
}
