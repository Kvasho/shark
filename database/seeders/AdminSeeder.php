<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * ქმნის ერთ ადმინისტრატორს.
     * ხელახლა გაშვებისას დუბლიკატს არ ქმნის — არსებულს აახლებს.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['username' => config('admin.username')],
            [
                'name' => config('admin.name'),
                'email' => config('admin.email'),
                'password' => '12345678',
            ],
        );

        $this->command?->info("ადმინისტრატორი მზადაა: {$admin->username}");
    }
}
