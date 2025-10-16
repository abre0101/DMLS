<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer),
        ]);
    }
}
