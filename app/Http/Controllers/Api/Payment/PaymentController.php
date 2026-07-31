<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Ressources\PaymentResource;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(private PaymentServiceInterface $paymentService)
    {
    }

    public function store(StorePaymentRequest $request): PaymentResource
    {
        $payment = $this->paymentService->process($request->validatedDTO());

        return new PaymentResource($payment);
    }

    public function index(): JsonResponse
    {
        $payments = $this->paymentService->findByAdvertiser(auth()->id());

        return response()->json(['data' => PaymentResource::collection($payments)]);
    }

    public function show(int $id): PaymentResource
    {
        $payment = $this->paymentService->findByAdvertiser(auth()->id())->where('id', $id)->firstOrFail();

        return new PaymentResource($payment);
    }
}
