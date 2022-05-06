<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'code' => 'water-softeners',
                'name' => 'Water Softeners',
                'name_ru' => 'Умягчители воды',
                'discount' => 0,
            ],
            [
                'id' => 2,
                'code' => 'iron-removal',
                'name' => 'Iron Removal',
                'name_ru' => 'Обезжелезивания',
                'discount' => 10,
            ],
        ]);

        DB::table('products')->insert([
            [
                'id' => 1,
                'code' => 'W001',
                'name' => 'Water Softener W001 Light',
                'price' => 96,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 1
            ],
            [
                'id' => 2,
                'code' => 'W002',
                'name' => 'Water Softener W002 Pro',
                'price' => 115,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 1
            ],
            [
                'id' => 3,
                'code' => 'I001',
                'name' => 'Iron Remover I001 Regular',
                'price' => 102,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 2
            ],
        ]);

        DB::table('usages')->insert([
            ['id' => 1, 'name' => 'Water Softening'],
            ['id' => 2, 'name' => 'Iron Removal']
        ]);

        DB::table('parameters')->insert([
            ['id' => 1, 'name' => 'Resin volume (l)'],
            ['id' => 2, 'name' => 'Exchange capacity (eq)'],
            ['id' => 3, 'name' => 'Quantity of media (l)'],
            ['id' => 4, 'name' => 'Service flow (m3/h)'],
            ['id' => 5, 'name' => 'Max flow (m3/h)']
        ]);

        DB::table('product_parameters')->insert([
            ['value' => 20, 'product_id' => 1, 'parameter_id' => 1, 'usage_id' => 1],
            ['value' => 28, 'product_id' => 1, 'parameter_id' => 2, 'usage_id' => 1],
            ['value' => 0.5, 'product_id' => 1, 'parameter_id' => 4, 'usage_id' => 1],
            ['value' => 0.8, 'product_id' => 1, 'parameter_id' => 5, 'usage_id' => 1],

            ['value' => 25, 'product_id' => 2, 'parameter_id' => 1, 'usage_id' => 1],
            ['value' => 35, 'product_id' => 2, 'parameter_id' => 2, 'usage_id' => 1],
            ['value' => 0.8, 'product_id' => 2, 'parameter_id' => 4, 'usage_id' => 1],
            ['value' => 1, 'product_id' => 2, 'parameter_id' => 5, 'usage_id' => 1],

            ['value' => 25, 'product_id' => 3, 'parameter_id' => 3, 'usage_id' => 2],
            ['value' => 0.4, 'product_id' => 3, 'parameter_id' => 4, 'usage_id' => 2],
            ['value' => 0.7, 'product_id' => 3, 'parameter_id' => 5, 'usage_id' => 2],
        ]);
    }
}
