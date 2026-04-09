<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserCoupon;

class FakeUserSeeder extends Seeder
{
    public function run()
    {
        // 1. Check if at least ONE user exists in the system to act as the first sponsor/parent
        $firstUser = User::orderBy('id', 'asc')->first();

        if (!$firstUser) {
            $this->command->error("Please create at least 1 user manually (e.g. Admin) before running this seeder!");
            return;
        }

        $this->command->info('Generating 1000 fake users. This might take a minute...');

        $totalUsersToCreate = 1000;

        for ($i = 0; $i < $totalUsersToCreate; $i++) {
            
            // 1. Find the LAST user in the database to make them the parent (Single Leg Logic)
            $lastUser = User::orderBy('id', 'desc')->first();
            $parentId = $lastUser ? $lastUser->ulid : $firstUser->ulid;

            // 2. Set Sponsor ID (Option A: Make the very first user the sponsor for EVERYONE)
            $sponsorId = $firstUser->ulid; 
            
            // OR (Option B: Pick a random existing user as a sponsor)
            // $sponsorId = User::inRandomOrder()->first()->ulid;

            // 3. Create the User using Factory
            $newUser = User::factory()->create([
                'parent_id' => $parentId,
                'sponsor_id' => $sponsorId,
            ]);

            // 4. Create Coupons for the New User
            UserCoupon::create([
                'user_id'         => $newUser->id,
                'user_ulid'       => $newUser->ulid,
                'coupon_quantity' => 10,
                'coupon_value'    => 10.00,
            ]);

            // 5. Handle Sponsor Coupons
            $sponsor = User::where('ulid', $sponsorId)->first();
            if ($sponsor) {
                $coupon = UserCoupon::where('user_id', $sponsor->id)->first();
                if ($coupon) {
                    $coupon->increment('coupon_quantity', 10);
                } else {
                    UserCoupon::create([
                        'user_id'         => $sponsor->id,
                        'user_ulid'       => $sponsor->ulid,
                        'coupon_quantity' => 10,
                        'coupon_value'    => 10.00,
                    ]);
                }
            }

            // Print progress every 50 users
            if (($i + 1) % 50 == 0) {
                $this->command->info(($i + 1) . ' users created...');
            }
        }

        $this->command->info('Successfully created 1000 users with Single Leg mapping!');
    }
}