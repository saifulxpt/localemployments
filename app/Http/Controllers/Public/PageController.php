<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('public.about');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function terms()
    {
        return view('public.terms');
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'phone'   => ['required', 'string'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        \App\Models\ContactMessage::create($request->only('name', 'phone', 'message'));

        return back()->with('success', 'আপনার বার্তা পাঠানো হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।');
    }
}
