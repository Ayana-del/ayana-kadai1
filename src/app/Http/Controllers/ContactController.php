<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        return view('contact', compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        $validatedData = $request->validated();

        $request->session()->put('contact_data', $validatedData);

        $categoryName = Category::find($validatedData['category_id'])->content;

        return view('confirm',[
            'contact' => $validatedData,
            'category_name' => $categoryName,
        ]);
    }

    public function send(Request $request)
    {
        if ($request->has('back')) {
            return redirect('/contact')->withInput($request->session()->get('contact_data'));
    }
        $contactData = $request->session()->get('contact_data');

        if (!$contactData) {
            return redirect('/contact')->with('error', 'セッションの有効期限が切れました。');
        }

        DB::transaction(function () use ($contactData) {
            Contact::create($contactData);
        });

        $request->session()->forget('contact_data');

        return view('thanks');
    }
}