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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

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
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }

        // Create products for each category
        $categories = Category::all();

        foreach ($categories as $category) {
            Product::factory(10)->create([
                'category_id' => $category->id,
            ]);
        }
    }
}
