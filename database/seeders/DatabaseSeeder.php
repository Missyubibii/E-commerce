<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        if (!User::where('email', 'admin@email.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@email.com',
                'password' => Hash::make('123123123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        if (!User::where('email', 'user@email.com')->exists()) {
            User::create([
                'name' => 'Regular User',
                'email' => 'user@email.com',
                'password' => Hash::make('123123123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }

        // Create regular users
        User::factory(5)->create([
            'role' => 'user',
        ]);

        // Create categories
        $categories = [
            'Electronics' => 'Latest electronic gadgets and devices',
            'Clothing' => 'Fashion and apparel for all ages',
            'Books' => 'Wide selection of books and publications',
            'Home & Garden' => 'Everything for your home and garden',
            'Sports' => 'Sports equipment and accessories',
        ];

        foreach ($categories as $name => $description) {
            if (!Category::where('slug', Str::slug($name))->exists()) {
                Category::create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $description,
                ]);
            }
        }

        // Create products for each category
        $categories = Category::all();

        foreach ($categories as $category) {
            \Database\Factories\ProductFactory::new()->count(10)->create([
                'category_id' => $category->id,
            ]);
        }
    }
}
