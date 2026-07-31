<?php

namespace App\Services\Contracts;

use App\DTOs\Payment\ProcessPaymentDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Payment;

interface PaymentServiceInterface extends ServiceInterface
{
    public function process(ProcessPaymentDTO $dto): Payment;

    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;

    public function findPending(int $perPage = 15): LengthAwarePaginator;
}
