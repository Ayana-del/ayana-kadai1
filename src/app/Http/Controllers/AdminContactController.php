<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
            ->paginate(7);

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'categories' => $categories,
        ]);
    }

    public final function reset(Request $request)
    {
        return redirect()->route('admin.contacts.index');
    }


    public final function export(Request $request)
    {

        $nameOrEmail = $request->input('name_or_email');
        $gender = $request->input('gender');
        $category = $request->input('category');
        $date = $request->input('date');

        $contacts = Contact::with('category')
            ->nameOrEmailSearch($nameOrEmail)
            ->genderSearch($gender)
            ->categorySearch($category)
            ->dateSearch($date)
            ->get();

        $csvData = $this->generateCsv($contacts);
        $filename = 'contacts_export_' . date('YmdHis') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '";',
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

    private function generateCsv($contacts)
    {
        $csv = 'ID,姓,名,性別,メールアドレス,電話番号,住所,建物名,お問い合わせの種類,お問い合わせ内容,作成日時' . "\n";

        foreach ($contacts as $contact) {
            $genderText = match ($contact->gender) {
                1 => '男性',
                2 => '女性',
                3 => 'その他',
                default => '不明',
            };

            $building = $contact->building ?? '';

            $csv .= implode(',', [
                $contact->id,
                $contact->last_name,
                $contact->first_name,
                $genderText,
                $contact->email,
                $contact->tel,
                $contact->address,
                $building,
                $contact->category->content,
                '"' . str_replace(['"', "\n"], ['""', ' '], $contact->detail) . '"',
                $contact->created_at,
            ]) . "\n";
        }

        return $csv;
    }
}
