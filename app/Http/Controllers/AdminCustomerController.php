<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Mengelola operasi CRUD (Create, Read, Update, Delete) untuk data pelanggan (customer) di panel Admin.
 */
class AdminCustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_customer' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer berhasil ditambahkan');
    }

    public function show(Customer $customer): View
    {
        $customer->load('salesTransactions');
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'nama_customer' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->salesTransactions()->exists()) {
            return back()->with('error', 'Customer tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer berhasil dihapus');
    }
}
