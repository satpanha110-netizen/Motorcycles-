<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\Motorcycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function submit(StoreContactRequest $request): RedirectResponse
    {
        Contact::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
            'status' => 'new',
        ]);

        return back()->with('success', 'Thank you for reaching out! Our team will get back to you shortly.');
    }

    /**
     * "Contact seller" form on the motorcycle details page.
     */
    public function contactSeller(StoreContactRequest $request, Motorcycle $motorcycle): RedirectResponse
    {
        Contact::create([
            'user_id' => Auth::id(),
            'motorcycle_id' => $motorcycle->id,
            'seller_id' => $motorcycle->seller_id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
            'status' => 'new',
        ]);

        return back()->with('success', 'Your message has been sent to the seller.');
    }
}
