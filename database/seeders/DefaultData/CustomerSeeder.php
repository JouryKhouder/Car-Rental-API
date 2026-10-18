<?php

namespace Database\Seeders\DefaultData;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Symfony\Component\HttpKernel\Profiler\Profile;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Customer::factory()->create([
                'user_id' => $user->id
            ]);
        }
    }
}
