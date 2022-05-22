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
                'code' => 'pressure-vessels',
                'name' => 'Pressure Vessels',
                'name_ru' => 'Корпуса Фильтров',
                'discount' => 10,
            ],
            [
                'id' => 2,
                'code' => 'brine-tanks',
                'name' => 'Brine Tanks',
                'name_ru' => 'Солевые Баки',
                'discount' => 0,
            ],
            [
                'id' => 3,
                'code' => 'control-valves',
                'name' => 'Control Valves',
                'name_ru' => 'Управляющие Клапаны',
                'discount' => 0,
            ],
            [
                'id' => 4,
                'code' => 'filtration-media',
                'name' => 'Filtration Media',
                'name_ru' => 'Фильтрующая среда',
                'discount' => 0,
            ],
        ]);

        DB::table('categories')->insert([
            [
                'id' => 5,
                'code' => 'ion-exchange-resins',
                'name' => 'Ion Exchange Resins',
                'name_ru' => 'Ion Exchange Resins',
                'discount' => 0,
                'parent_id' => 4,
            ],
            [
                'id' => 6,
                'code' => 'quartz-sand',
                'name' => 'Quartz Sand',
                'name_ru' => 'Quartz Sand',
                'discount' => 0,
                'parent_id' => 4,
            ],
            [
                'id' => 7,
                'code' => 'manganese-dioxide',
                'name' => 'Manganese Dioxide',
                'name_ru' => 'Manganese Dioxide',
                'discount' => 0,
                'parent_id' => 4,
            ],
        ]);

        DB::table('products')->insert([
            [
                'code' => 'PV001',
                'name' => 'Pressure vessel PV001 Light',
                'price' => 96,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 1
            ],
            [
                'code' => 'PV002',
                'name' => 'Pressure vessel PV002 Medium',
                'price' => 115,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 1
            ],
            [
                'code' => 'PV003',
                'name' => 'Pressure vessel PV003 Pro',
                'price' => 148,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 1
            ],
            [
                'code' => 'PV004',
                'name' => 'Pressure vessel PV004 Industrial',
                'price' => 350,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 1
            ],
            [
                'code' => 'BT001',
                'name' => 'Brine Tank 25l Small',
                'price' => 45,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 2
            ],
            [
                'code' => 'BT002',
                'name' => 'Brine Tank 100l Medium',
                'price' => 75,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 2
            ],
            [
                'code' => 'BT003',
                'name' => 'Brine Tank 500l Large',
                'price' => 480,
                'currency' => 'EUR',
                'unit' => 'unit',
                'category_id' => 2
            ],
            [
                'code' => 'CV-1',
                'name' => 'Control Valve CV-1 Home 1\'',
                'price' => 250,
                'currency' => 'USD',
                'unit' => 'unit',
                'category_id' => 3
            ],
            [
                'code' => 'CV-1-25',
                'name' => 'Control Valve CV-1-25 Small 1.25\'',
                'price' => 310,
                'currency' => 'USD',
                'unit' => 'unit',
                'category_id' => 3
            ],
            [
                'code' => 'CV-2',
                'name' => 'Control Valve CV-2 Standard 2\'',
                'price' => 450,
                'currency' => 'USD',
                'unit' => 'unit',
                'category_id' => 3
            ],
            [
                'code' => 'CV-3',
                'name' => 'Control Valve CV-3 Pro 3\'',
                'price' => 780,
                'currency' => 'USD',
                'unit' => 'unit',
                'category_id' => 3
            ],
            [
                'code' => 'IONB-25',
                'name' => 'Ion Exchange Resin ION Basic 25l',
                'price' => 75,
                'currency' => 'USD',
                'unit' => '25l bag',
                'category_id' => 5
            ],
            [
                'code' => 'IONA-25',
                'name' => 'Ion Exchange Resin ION Advanced 25l',
                'price' => 90,
                'currency' => 'USD',
                'unit' => '25l bag',
                'category_id' => 5
            ],
            [
                'code' => 'IONB-50',
                'name' => 'Ion Exchange Resin ION Basic 50l',
                'price' => 140,
                'currency' => 'USD',
                'unit' => '50l bag',
                'category_id' => 5
            ],
            [
                'code' => 'IONA-50',
                'name' => 'Ion Exchange Resin ION Advanced 50l',
                'price' => 170,
                'currency' => 'USD',
                'unit' => '50l bag',
                'category_id' => 5
            ],
            [
                'code' => 'QS-25',
                'name' => 'Quartz Sand QS 25l',
                'price' => 65,
                'currency' => 'EUR',
                'unit' => '25l bag',
                'category_id' => 6
            ],
            [
                'code' => 'QS-50',
                'name' => 'Quartz Sand QS 50l',
                'price' => 120,
                'currency' => 'EUR',
                'unit' => '50l bag',
                'category_id' => 6
            ],
            [
                'code' => 'MD-25',
                'name' => 'Manganese Dioxide MD 25l',
                'price' => 70,
                'currency' => 'EUR',
                'unit' => '25l bag',
                'category_id' => 7
            ],
            [
                'code' => 'MD-50',
                'name' => 'Manganese Dioxide MD 50l',
                'price' => 125,
                'currency' => 'EUR',
                'unit' => '50l bag',
                'category_id' => 7
            ],
        ]);

        DB::table('usages')->insert([
            ['id' => 1, 'name' => 'Water Softening'],
            ['id' => 2, 'name' => 'Iron Removal'],
            ['id' => 3, 'name' => 'Manganese removal']
        ]);

        DB::table('parameters')->insert([
            ['id' => 1, 'name' => 'Quantity of material (mg/l)'],
            ['id' => 2, 'name' => 'Service flow (m3/h)'],
            ['id' => 3, 'name' => 'Max flow (m3/h)'],
            ['id' => 4, 'name' => 'Quantity of media (l)']
        ]);
    }
}
