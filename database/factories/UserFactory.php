<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        // 1. Faker को Indian Locale ('en_IN') पर सेट करें
        $faker = \Faker\Factory::create('en_IN');

        // 2. Indian Name जनरेट करें
        $firstName = $faker->firstName();
        $lastName = $faker->lastName();
        $fullName = $firstName . ' ' . $lastName;

        // 3. नाम से @gmail.com वाली Email ID बनाएँ (बिना स्पेस के और छोटे अक्षरों में)
        // Example: rahul sharma -> rahulsharma452@gmail.com
        $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $fullName));
        $email = $cleanName . mt_rand(100, 9999) . '@gmail.com';

        // 4. Unique ULID जनरेट करें
        do {
            $ulid = 'SS' . mt_rand(1000000, 9999999);
        } while (User::where('ulid', $ulid)->exists());

        // 5. Indian Phone Number ( 7, 8 या 9 से शुरू होने वाला 10 डिजिट का नंबर)
        $phone = $faker->randomElement(['9', '8', '7']) . $faker->numerify('#########');

        return [
            'name'     => $fullName,
            'email'    => $email,
            'phone'    => $phone,
            'ulid'     => $ulid,
            'password' => Hash::make('Admin@123'), 
            'role'     => 'user',
            'status'   => 'inactive', 
        ];
    }
}