<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Register', [
            'prefill' => [
                'name' => $request->query('name'),
                'email' => $request->query('email'),
                'phone' => $request->query('phone'),
                'address' => $request->query('address'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate password only
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->input('email');
        $phone = $request->input('phone');

        $user = null;

        if ($email) {
            $user = User::where('email', $email)->first();
        } elseif ($phone) {
            $user = User::where('phone', $phone)->first();
        }

        if ($user) {
            // Update password for existing customer
            $user->update(['password' => Hash::make($request->password)]);
        } else {
            // Validate full info
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
        }
        // ✅ Link any guest orders with this email or phone to the new user
        Order::whereNull('user_id')
            ->where(function ($query) use ($user) {
                $query->where('customer_email', $user->email)
                      ->orWhere('customer_phone', $user->phone);
            })
            ->update(['user_id' => $user->id]);

        Auth::login($user);

        return to_route('dashboard');
    }
        public static function customerExists(string $email = null, string $phone = null): bool
    {
        $query = User::query();

        if ($email) {
            $query->orWhere('email', $email);
        }

        if ($phone) {
            $query->orWhere('phone', $phone);
        }

        return $query->exists();
    }
}
