<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;


class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categoryIds = Category::pluck('id')->all();

        return [
            'category_id' => $this->faker->randomElement($categoryIds),
            //氏名・性別・連絡先
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->numberBetween(1, 2),
            'email' => $this->faker->unique()->safeEmail,
            'tel' => $this->faker->phoneNumber,

            //住所
            'address' => $this->faker->streetAddress,
            'building' => $this->faker->secondaryAddress,

            //お問い合わせ内容
            'detail' => $this->faker->realText(200),

            //過去の日付でランダム
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
