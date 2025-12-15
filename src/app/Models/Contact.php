<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail'
    ];

    // JSONシリアル化時やBladeで使うアクセサを設定
    protected $appends = ['gender_text', 'full_name', 'tel_without_hyphen'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 性別テキスト変換アクセサ (FN025)
    public function getGenderTextAttribute()
    {
        return [
            '1' => '男性',
            '2' => '女性',
            '3' => 'その他',
        ][$this->gender] ?? 'その他';
    }

    // フルネームアクセサ
    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }

    // ハイフンなし電話番号アクセサ (FN006-4a)
    public function getTelWithoutHyphenAttribute()
    {
        return str_replace('-', '', $this->tel ?? '');
    }

    /**
     * スコープ: 名前（姓/名/フルネーム）またはメールアドレスで検索 (FN022)
     */
    public function scopeNameOrEmailSearch($query, $nameOrEmail)
    {
        if (!empty($nameOrEmail)) {
            $nameOrEmail = trim(mb_convert_kana($nameOrEmail, 's'));

            $query->where(function ($q) use ($nameOrEmail) {
                // メールアドレスの部分一致
                $q->where('email', 'like', "%{$nameOrEmail}%");

                // 姓または名の部分一致
                $q->orWhere('last_name', 'like', "%{$nameOrEmail}%")
                    ->orWhere('first_name', 'like', "%{$nameOrEmail}%");

                // フルネーム（スペースなし）での部分一致
                $fullNameWithoutSpace = str_replace(' ', '', $nameOrEmail);
                if (!empty($fullNameWithoutSpace)) {
                    $q->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$fullNameWithoutSpace}%"]);
                }

                // フルネーム（スペースあり）での部分一致
                $q->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$nameOrEmail}%"]);
            });
        }
        return $query;
    }

    // 性別検索スコープ (FN022)
    public function scopeGenderSearch($query, $gender)
    {
        if (!empty($gender) && in_array($gender, ['1', '2', '3'])) {
            $query->where('gender', $gender);
        }
        return $query;
    }

    // カテゴリ検索スコープ (FN022)
    public function scopeCategorySearch($query, $category)
    {
        if (!empty($category)) {
            $query->where('category_id', $category);
        }
        return $query;
    }

    // 日付検索スコープ (FN022)
    public function scopeDateSearch($query, $date)
    {
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }
        return $query;
    }
}
