<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Notifications\ContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function submitForm(ContactRequest $request)
    {
        try{
            $validatedData = $request->validated();
        }catch (\Exception $e){
            dd($e->getMessage());
        }


        $data = $validatedData;

        Contact::create($data);

        Notification::route('mail', env('ADMIN_MAIL'))->notify(new ContactNotification($data));

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
