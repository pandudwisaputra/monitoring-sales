<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json([
            'data' => Customer::all()
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama_customer' => 'required',

            'no_hp' => 'required',

            'alamat' => 'required'
        ]);

        $customer = Customer::create($validated);

        return response()->json([

            'message' => 'Customer berhasil ditambahkan',

            'data' => $customer
        ]);
    }

    // DETAIL
    public function show($id)
    {
        return response()->json([
            'data' => Customer::findOrFail($id)
        ]);
    }

    // UPDATE
    public function update(
        Request $request,
        $id
    ) {

        $customer = Customer::findOrFail($id);

        $validated = $request->validate([

            'nama_customer' => 'required',

            'no_hp' => 'required',

            'alamat' => 'required'
        ]);

        $customer->update($validated);

        return response()->json([

            'message' => 'Customer berhasil diupdate',

            'data' => $customer
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Customer berhasil dihapus'
        ]);
    }
}