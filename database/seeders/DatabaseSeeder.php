<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Services\MiscService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $miscService;  // Private property to store the MiscService instance

    public function __construct(MiscService $miscService)
    {
        $this->miscService = $miscService;  // Inject the MiscService instance into the controller
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::truncate();

        User::create([
            'userid' => $this->miscService->generateUserid('Webmaster'),
            'name' => "Webmaster",
            'email' => "webmaster@happyroyal.com",
            'role_id' => 1,
            'password' => bcrypt("@happyroyal0000"),
            'status' => 'ACTIVE',
            'email_verified_at' => now()
        ]);

        $this->call([
            RoleSeeder::class,
            ModuleGroupSeeder::class,
            ModuleSeeder::class,
            BookSeeder::class,
            StudentSeeder::class,
        ]);
    }
}
