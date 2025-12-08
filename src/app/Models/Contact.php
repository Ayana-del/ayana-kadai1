<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id' , 'first_name' , 'last_name' , 'gender' , 'email' , 'tel' , 'address' , 'building' , 'detail'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * スコープ: 名前またはメールアドレスで検索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $nameOrEmail
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNameOrEmailSearch($query, $nameOrEmail)
    {
        if (!empty($nameOrEmail)) {
            $keywords = preg_split('/[\s,]+/', $nameOrEmail, -1, PREG_SPLIT_NO_EMPTY);

            $query->where(function ($q) use ($keywords, $nameOrEmail) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('first_name', 'like', "%{$keyword}%");
                }
                $q->orWhere('email', 'like', "%{$nameOrEmail}%");
            });
        }
        return $query;
    }

    /**
     * スコープ: 性別で検索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $gender
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGenderSearch($query, $gender)
    {
        if (!empty($gender) && ($gender == '1' || $gender == '2')) {
            $query->where('gender', $gender);
        }
        return $query;
    }

    /**
     * スコープ: お問い合わせの種類（カテゴリID）で検索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategorySearch($query, $category)
    {
        if (!empty($category)) {
            $query->where('category_id', $category);
        }
        return $query;
    }

    /**
     * スコープ: 作成日時（日付）で検索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateSearch($query, $date)
    {
        // $date は 'YYYY-MM-DD' 形式を期待
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }
        return $query;
    }
}

