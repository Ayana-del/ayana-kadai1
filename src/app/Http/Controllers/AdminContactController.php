<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $nameOrEmail = $request->input('name_or_email');
        $gender = $request->input('gender');
        $category = $request->input('category');
        $date = $request->input('date');

        $contacts = Contact::with('category')
            ->nameOrEmailSearch($nameOrEmail)
            ->genderSearch($gender)
            ->categorySearch($category)
            ->dateSearch($date)
            ->paginate(10);

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Contact $contact
     */
    public function show(Contact $contact)
    {
        return view('admin.contacts.show', [
            'contact' => $contact,
        ]);
    }

    /**
     * 指定されたお問い合わせを削除する
     *
     * @param Contact $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public final function destroy(Contact $contact)
    {
        $contact->delete();

        session()->flash('success_message', 'お問い合わせを削除しました。');

        return redirect()->route('admin.contacts.index');
    }
}
