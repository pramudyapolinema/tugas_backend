<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $guarded = [];

    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Elektronik',
        ]);
        DB::table('categories')->insert([
            'name' => 'Fashion Pria',
        ]);
        DB::table('categories')->insert([
            'name' => 'Fashion Wanita',
        ]);
        DB::table('categories')->insert([
            'name' => 'Handphone & Tablet',
        ]);
        DB::table('categories')->insert([
            'name' => 'Olahraga',
        ]);
    }
}
