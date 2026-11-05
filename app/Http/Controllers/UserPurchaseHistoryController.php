<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserPurchaseHistoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => ['sometimes', 'required', Rule::in(['subscription', 'book'])],
        ]);

        $user = $request->user();
        $type = $validated['type'] ?? 'subscription';
        $subscriptionPurchases = $type === 'subscription'
            ? $user->subscriptionPackageUsers()
                ->with('subscriptionPackage')
                ->latest()
                ->paginate(10)
                ->withQueryString()
            : null;

        return view('user.purchase-history', [
            'user' => $user,
            'checkoutCount' => $user->checkouts()->count(),
            'subscriptionCount' => $user->subscriptionPackageUsers()->count(),
            'type' => $type,
            'subscriptionPurchases' => $subscriptionPurchases,
        ]);
    }
}
