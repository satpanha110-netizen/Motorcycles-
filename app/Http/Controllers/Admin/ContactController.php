<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $messages = Contact::with(['motorcycle', 'seller', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.messages.index', compact('messages'));
    }

    public function markRead(Contact $contact): RedirectResponse
    {
        $contact->update(['status' => 'read']);

        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return back()->with('success', 'Message deleted.');
    }
}
