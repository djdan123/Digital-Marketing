<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Advertisement;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
	public function index(): JsonResponse
	{
		$data = [
			'campaigns_count' => Campaign::count(),
			'advertisements_count' => Advertisement::count(),
			'users_count' => User::count(),
			'payments_total' => Payment::sum('amount'),
		];

		return response()->json(['data' => $data]);
	}
}

