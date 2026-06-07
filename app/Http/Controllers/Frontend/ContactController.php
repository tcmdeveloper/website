<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;


// -----------------------------------------------------
// CONTACT CONTROLLER (FRONTEND)
// -----------------------------------------------------

class ContactController extends Controller
{

    // -----------------------------------------------------
    // SHOW CONTACT FORM
    // -----------------------------------------------------

    public function show()
    {
        return view('contact.show');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email:rfc,dns', 'max:150'],
            'subject'   => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('status', [
            'type' => 'success',
            'message' => 'Your message was sent to Metrix!',
        ]);

    }
    
}