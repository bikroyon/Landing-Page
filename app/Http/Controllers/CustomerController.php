<?php

namespace App\Http\Controllers;

use App\Helpers\AccessHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        if (!AccessHelper::hasAccess('customers', 'view')) {
            abort(403, 'Unauthorized');
        }
        $query = User::where('role_id', 2); // Only customers

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('customers/Index', [
            'customers' => $customers,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Toggle ban/unban for a customer.
     */
    public function toggleBan(User $customer)
    {
        abort_if($customer->role_id !== 2, 403);

        $customer->update([
            'is_banned' => !$customer->is_banned,
        ]);

        return redirect()->back()->with('success', 'Customer status updated.');
    }

    /**
     * Delete a customer.
     */
    public function destroy(User $customer)
    {
        abort_if($customer->role_id !== 2, 403);

        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }
}
