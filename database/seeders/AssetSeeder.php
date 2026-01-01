<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data extracted from assets.pdf
        // Null values are replaced with rand(1000, 500000)
        $data = [
            [
                'id' => 1,
                'name' => 'جرد المكتلت',
                'value' => 1762463,
            ],
            [
                'id' => 2,
                'name' => 'أراضي مقام عليها مباني',
                'value' => 72478561,
            ],
            [
                'id' => 3,
                'name' => 'المباني والمنشآت',
                'value' => 846571,
            ],
            [
                'id' => 4,
                'name' => 'اراضي زراعية مملوكة',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 5,
                'name' => 'اراضي زراعية مؤجرة',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 6,
                'name' => 'ثروة حيوانية ومائية',
                'value' => 0,
            ],
            [
                'id' => 7,
                'name' => 'متاحف ومعارض',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 8,
                'name' => 'اصناف منتجة عن طريق الورش انتاج تام',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 9,
                'name' => 'اصناف منتجة عن طريق الورش انتاج غير تام',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 10,
                'name' => 'اصناف منتجة عن طريق المطابع انتاج تام',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 11,
                'name' => 'اصناف منتجة عن طريق المطابع انتاج غير تام',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 12,
                'name' => 'اعتمادات مستندية لشراء بضائع لم ترد',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
            [
                'id' => 13,
                'name' => 'مشروعات تحت التنفيذ',
                'value' => rand(1000, 500000), // Was null in PDF
            ],
        ];

        DB::table('assets')->insert($data);
    }
}
