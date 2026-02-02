<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterCustomer::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                  ->orWhere('kode_customer', 'like', "%{$search}%")
                  ->orWhere('email_customer', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->where('kategori_customer', $request->kategori);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->orderBy('nama_customer')->paginate(15);

        // Get statistics
        $stats = [
            'total' => MasterCustomer::count(),
            'active' => MasterCustomer::active()->count(),
            'vip' => MasterCustomer::active()->where('kategori_customer', 'vip')->count(),
            'new_this_month' => MasterCustomer::where('created_at', '>=', now()->startOfMonth())->count()
        ];

        return view('master-data.customers.index', compact('customers', 'stats'));
    }

    public function create()
    {
        return view('master-data.customers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_customer' => 'required|string|max:50|unique:master_customers',
            'nama_customer' => 'required|string|max:255',
            'email_customer' => 'required|email|max:255',
            'telepon_customer' => 'required|string|max:20',
            'alamat_customer' => 'required|string',
            'kategori_customer' => 'required|in:vip,regular,new',
            'payment_terms' => 'required|in:cod,30_days,45_days,60_days',
            'credit_limit' => 'nullable|numeric|min:0',
            'contact_person' => 'nullable|string|max:255',
            'phone_contact_person' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        MasterCustomer::create($request->all());

        return redirect()->route('master-customer.index')
                        ->with('success', 'Customer berhasil ditambahkan');
    }

    public function show(MasterCustomer $masterCustomer)
    {
        // Load related data
        $masterCustomer->load(['customerComplaints' => function ($query) {
            $query->latest()->limit(10);
        }]);

        // Get customer statistics
        $stats = [
            'total_complaints' => $masterCustomer->customerComplaints()->count(),
            'pending_complaints' => $masterCustomer->customerComplaints()->where('status', 'pending')->count(),
            'resolved_complaints' => $masterCustomer->customerComplaints()->where('status', 'resolved')->count(),
            'total_orders_value' => 0 // This would come from order data if available
        ];

        return view('master-data.customers.show', compact('masterCustomer', 'stats'));
    }

    public function edit(MasterCustomer $masterCustomer)
    {
        return view('master-data.customers.edit', compact('masterCustomer'));
    }

    public function update(Request $request, MasterCustomer $masterCustomer)
    {
        $validator = Validator::make($request->all(), [
            'kode_customer' => 'required|string|max:50|unique:master_customers,kode_customer,' . $masterCustomer->id,
            'nama_customer' => 'required|string|max:255',
            'email_customer' => 'required|email|max:255',
            'telepon_customer' => 'required|string|max:20',
            'alamat_customer' => 'required|string',
            'kategori_customer' => 'required|in:vip,regular,new',
            'payment_terms' => 'required|in:cod,30_days,45_days,60_days',
            'credit_limit' => 'nullable|numeric|min:0',
            'contact_person' => 'nullable|string|max:255',
            'phone_contact_person' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $masterCustomer->update($request->all());

        return redirect()->route('master-customer.index')
                        ->with('success', 'Customer berhasil diupdate');
    }

    public function destroy(MasterCustomer $masterCustomer)
    {
        // Check if customer has related records
        if ($masterCustomer->customerComplaints()->exists()) {
            return back()->with('error', 'Customer tidak dapat dihapus karena masih memiliki complaint terkait');
        }

        $masterCustomer->delete();

        return redirect()->route('master-customer.index')
                        ->with('success', 'Customer berhasil dihapus');
    }

    public function toggleStatus(MasterCustomer $masterCustomer)
    {
        $masterCustomer->update([
            'is_active' => !$masterCustomer->is_active
        ]);

        $status = $masterCustomer->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Customer berhasil {$status}");
    }

    public function export(Request $request)
    {
        // Implementation for exporting customer data to Excel
        // This would use Laravel Excel or similar package
        return response()->json(['message' => 'Export functionality coming soon']);
    }
}
