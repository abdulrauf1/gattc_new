<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display contact messages.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Read filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('read')) {

            if ($request->read === 'unread') {
                $query->whereNull('read_at');
            }

            if ($request->read === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $messages = $query
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $messageTotal = ContactMessage::count();

        $messageUnread = ContactMessage::whereNull('read_at')->count();

        $messageRead = ContactMessage::whereNotNull('read_at')->count();

        return view('admin.contact-messages.index', compact(
            'messages',
            'messageTotal',
            'messageUnread',
            'messageRead'
        ));
    }

    /**
     * Show a single contact message.
     */
    public function show(ContactMessage $contactMessage)
    {
        /*
        |--------------------------------------------------------------------------
        | Automatically mark as read when administrator opens it.
        |--------------------------------------------------------------------------
        */
        if (!$contactMessage->read_at) {
            $contactMessage->update([
                'read_at' => now(),
            ]);
        }

        return view(
            'admin.contact-messages.show',
            compact('contactMessage')
        );
    }

    /**
     * Mark message as read.
     */
    public function markRead(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'read_at' => now(),
        ]);

        return back()->with(
            'success',
            'Message marked as read.'
        );
    }

    /**
     * Mark message as unread.
     */
    public function markUnread(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'read_at' => null,
        ]);

        return back()->with(
            'success',
            'Message marked as unread.'
        );
    }

    /**
     * Update administrator notes.
     */
    public function updateNotes(
        Request $request,
        ContactMessage $contactMessage
    ) {
        $validated = $request->validate([
            'admin_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $contactMessage->update($validated);

        return back()->with(
            'success',
            'Administrator notes updated.'
        );
    }

    /**
     * Delete message.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with(
                'success',
                'Contact message deleted.'
            );
    }
}