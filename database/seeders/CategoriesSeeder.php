<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('categories')->insert([
            [
                'name' => 'Apartment',
                'description' => 'A self-contained housing unit that occupies part of a building.',
            ],
            [
                'name' => 'House',
                'description' => 'A single-family dwelling that is typically larger than an apartment.',
            ],
            [
                'name' => 'Condo',
                'description' => 'A type of housing where individuals own their units but share common areas.',
            ],
            [
                'name' => 'Villa',
                'description' => 'A large and luxurious country house.',
            ],
            [
                'name' => 'Studio',
                'description' => 'A small apartment that combines living and sleeping space.',
            ],
            [
                'name' => 'Loft',
                'description' => 'A large, open space that is often converted from industrial use.',
            ],
            [
                'name' => 'Penthouse',
                'description' => 'A luxurious apartment located on the top floor of a building.',
            ],
            [
                'name' => 'Duplex',
                'description' => 'A house divided into two separate living units.',
            ],
        ]);
    }
}
