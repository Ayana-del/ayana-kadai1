<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['content' => '商品のお届けについて'],
            ['content' => '商品の交換について'],
            ['content' => 'ショップへのお問い合わせ'],
            ['content' => '商品トラブル'],
            ['content' => 'その他'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
