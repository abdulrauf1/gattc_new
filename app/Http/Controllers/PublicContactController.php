<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class PublicContactController extends Controller
{
    /**
     * Display public contact page.
     */
    public function create()
    {
        return view('public.contact');
    }

    /**
     * Save a message submitted from the public website.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->route('public.contact')
            ->with(
                'success',
                'Thank you. Your message has been sent successfully.'
            );
    }
}