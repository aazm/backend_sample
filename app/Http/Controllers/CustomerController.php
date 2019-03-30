<?php

namespace Turing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Turing\Http\Requests\CustomerProfileUpdateRequest;
use Turing\Services\CustomerServiceInterface;

class CustomerController extends Controller
{

    public function show()
    {
        try {

            return response()->json([
                'success' => true,
                'customer' => resolve(CustomerServiceInterface::class)->getById(Auth::id())
            ]);

        } catch (\Throwable $e) {

            Log::error('Customer show', ['e' => $e]);
            return response(['success' => false], 500);
        }
    }

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
            return response(['success' => false], 500);

        }
    }

}
