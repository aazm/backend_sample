<?php

namespace Turing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Turing\Http\Requests\CustomerProfileUpdateRequest;
use Turing\Services\CustomerServiceInterface;

class CustomerController extends Controller
{

    /**
     * Show customer info
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        try {

            return response()->json([
                'success' => true,
                'customer' => resolve(CustomerServiceInterface::class)->getById(Auth::id())
            ]);

        } catch (\Throwable $e) {

            Log::error('Customer show', ['e' => $e]);
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Update customer info
     *
     * @param CustomerProfileUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CustomerProfileUpdateRequest $request)
    {
        try {

            return response()->json([
                'success' => true,
                'customer' => resolve(CustomerServiceInterface::class)
                    ->update(Auth::id(), $request->all())

            ]);

        } catch (\Throwable $e) {
            Log::error('Customer update', ['e' => $e]);
            return response()->json(['success' => false], 500);

        }
    }

}
