<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class AdminContactController extends Controller
{
    // お問い合わせ一覧表示と検索 (FN022)
    public function index(Request $request)
    {
        $categories = Category::all();

        $nameOrEmail = $request->input('name_or_email');
        $gender = $request->input('gender');
        $category = $request->input('category');
        $date = $request->input('date');

        // スコープを適用してデータを取得
        $contacts = Contact::with('category')
            ->nameOrEmailSearch($nameOrEmail)
            ->genderSearch($gender)
            ->categorySearch($category)
            ->dateSearch($date)
            // FN021: 7件ごとのページネーションと検索条件の引き継ぎ
            ->paginate(7)
            ->appends($request->query());

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'categories' => $categories,
            'search_params' => $request->query(),
        ]);
    }

    // リセットボタン機能 (FN022)
    public final function reset(Request $request)
    {
        return redirect()->route('admin.contacts.index');
    }


    // エクスポート機能（応用要件, FN024）
    public final function export(Request $request)
    {
        $nameOrEmail = $request->input('name_or_email');
        $gender = $request->input('gender');
        $category = $request->input('category');
        $date = $request->input('date');

        // 検索にて絞り込んだ後のデータ一覧も対象
        $contacts = Contact::with('category')
            ->nameOrEmailSearch($nameOrEmail)
            ->genderSearch($gender)
            ->categorySearch($category)
            ->dateSearch($date)
            ->get();

        if ($contacts->isEmpty()) {
            return redirect()->back()->with('error_message', 'エクスポートするデータが見つかりませんでした。');
        }

        $csvData = $this->generateCsv($contacts);
        $filename = 'contacts_export_' . date('YmdHis') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '";',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    // モーダル表示のための詳細データ取得（JSON APIとして利用, FN025）
    public function show(Contact $contact)
    {
        $contact->load('category');
        return response()->json($contact);
    }

    // お問い合わせ削除機能 (FN026)
    public final function destroy(Contact $contact)
    {
        $contact->delete();

        session()->flash('success_message', 'お問い合わせを削除しました。');

        return redirect()->route('admin.contacts.index');
    }

    /**
     * 問い合わせデータからCSV文字列を生成する
     */
    private function generateCsv($contacts)
    {
        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM

        $csv .= 'ID,姓,名,性別,メールアドレス,電話番号,住所,建物名,お問い合わせの種類,お問い合わせ内容,作成日時' . "\n";

        foreach ($contacts as $contact) {
            $genderText = $contact->gender_text;
            $building = $contact->building ?? '';

            // お問い合わせ内容 (detail) はダブルクォートで囲み、改行とダブルクォートをエスケープ 
            $detail = str_replace(['"', "\n", "\r"], ['""', ' ', ' '], $contact->detail);

            $csv .= implode(',', [
                $contact->id,
                $contact->last_name,
                $contact->first_name,
                $genderText,
                $contact->email,
                $contact->tel,
                $contact->address,
                $building,
                $contact->category->content ?? 'その他', // Categoryモデルのカラム名に合わせて修正
                '"' . $detail . '"',
                $contact->created_at,
            ]) . "\n";
        }

        return $csv;
    }
}
