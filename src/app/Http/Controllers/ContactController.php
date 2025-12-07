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

    public function confirmOrSend(ContactRequest $request)
    {
        if($request->has('back')){

            return redirect('/')->withInput($request->session()->get('contact_data'));
        }

        $contactData = $request->session()->get('contact_data');

        if($contactData){
            DB::transaction(function () use ($contactData)
            {
                Contact::create($contactData);
            });

            $request->session()->forget('contact_data');

            return redirect()->route('contact.thanks');
        }

        $validatedData = $request->validated();

        $tel_full = $validatedData['tel1'] . $validatedData['tel2'] . $validatedData['tel3'];
        $validatedData['tel'] = $tel_full;

        unset($validatedData['tel1']);
        unset($validatedData['tel2']);
        unset($validatedData['tel3']);

        $request->session()->put('contact_data', $validatedData);
        
        $categoryName = Category::find($validatedData['category_id'])->content;

        return view('confirm', [
            'contact' => $validatedData,
            'category_name' => $categoryName,
        ]);
    }

    public function thanks()
    {
        return view('thanks');
    }
}