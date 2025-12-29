<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // Import Schema Facade
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Disable Foreign Key Checks (Database Agnostic)
        // Works for both MySQL and SQLite
        Schema::disableForeignKeyConstraints();

        // 2. Clear existing data to start fresh
        Category::truncate();

        // 3. Re-enable Foreign Key Checks
        Schema::enableForeignKeyConstraints();

        $organization = "كلية الهندسة بشبرا";

        // --- Data Definitions ---

        // Group 1: المستديم (Permanent) - IDs 1 to 38
        $permanent = [
            [1, "آلات ومعدات ثقيلة خاصة بالمعامل ووسائل النقل"],
            [2, "آلات ومعدات خاصة بالمعامل والعدة"],
            [
                3,
                "أثاث ومعدات المكاتب والاستراحات والكراسى وترابيزات الامتحانات",
            ],
            [4, "أدوات مكتبية"],
            [5, "وسائل نقل"],
            [6, "أختام شعار الجمهورية"],
            [7, "آلات ومعدات خاصة بالورش"],
            [8, "أدوات طبية وأجهزة مستديمة"],
            [9, "الأجهزة العلمية"],
            [10, "زجاجيات"],
            [11, "كيماويات مستديمة"],
            [12, "أدوات ألعاب رياضية"],
            [13, "أجهزة الوقاية من الحريق"],
            [14, "خزن حديد ودواليب صاج"],
            [15, "معدات تعليمية وسمعية وبصرية"],
            [16, "آلات كاتبة"],
            [17, "آلات هندسية ومساحية"],
            [18, "آلات طباعة"],
            [19, "آلات حاسبة وإحصائية"],
            [20, "موازيين ومكاييل"],
            [21, "أجهزة تدفئة وتبريد"],
            [22, "أدوات تنظيم الحدائق"],
            [23, "أدوات تصوير"],
            [24, "أدوات موسيقى"],
            [25, "خيام ومهمات غيط"],
            [26, "أدوات مائدة"],
            [27, "أسلحة و ذخائر"],
            [28, "تجهيزات أخرى"],
            [29, "قطع غيار مهمات للصيانة ( نجارة - حدادة - سباكة - كهرباء )"],
            [30, "مواد ومهمات متنوعة"],
            [31, "مواد تعبئة وتغليف متداولة"],
            [32, "كتب ومجلات ووثائق أخرى"],
            [33, "قطع غيار سيارات"],
            [34, "قطع غيار المعدات الثقيلة"],
            [35, "قطع غيار المعدات التعليمية والسمعية والبصرية"],
            [36, "أصناف مثبتة بالمبنى"],
            [37, "مخزن المستعمل"],
            [38, "وسائل اتصال"],
        ];

        // Group 2: الاستهلاكي (Consumable)
        // Offset: 1000
        $consumable = [
            [1, "أدوات كتابية"],
            [2, "كراسات الامتحانات - نظرى"],
            [222, "كراسات الامتحانات - عملى"],
            [3, "المطبوعات والاستمارات"],
            [4, "أدوات النظافة بجميع انواعها"],
            [5, "الزجاجيات"],
            [6, "أدوات بشرية للطلبة"],
            [622, "أدوات بشرية للمرضى"],
            [7, "أدوات حشرية و بيطرية"],
            [8, "الكيماويات"],
            [9, "مواد البناء والدهان"],
            [10, "الاعلاف بجميع انواعها"],
            [
                11,
                "التغذية الموجودة بالمخازن و المشتراة من بند الميزانية للطلبة",
            ],
            [
                112,
                "التغذية الموجودة بالمخازن و المشتراة من بند الميزانية للمرضى",
            ],
            [12, "الأسمدة"],
            [13, "بنزين"],
            [14, "فحم"],
            [15, "كيروسين"],
            [16, "سولار"],
            [17, "ديزيل"],
            [18, "مازوت"],
            [19, "مواد تزييت وتشحيم"],
            [20, "غاز"],
            [21, "استهلاكى متنوع"],
        ];

        // Group 3: محاصيل زراعية (Agricultural)
        // Offset: 2000
        $agricultural = [
            [1, "الثروة الداجنة والبيض"],
            [2, "صناعات غذائية ومعلبات"],
            [3, "حقول التجارب بجميع أنواعها"],
            [4, "الأشجار المثمرة و أشجار الزينة"],
            [5, "الثروة الحيوانية والسمكية"],
            [6, "المحاصيل الزراعية"],
            [7, "مناحل"],
            [8, "الدواب"],
        ];

        // Group 4: الدفاتر المالية (Financial Books)
        // Offset: 3000
        $financial = [
            [1, "دفاتر مالية ذات قيمة"],
            [2, "شهادات دراسية بجميع أنواعها مدموغة"],
            [3, "شهادات دراسية بجميع أنواعها غير مدموغة"],
            [4, "طوابع بريد و دمغات"],
            [5, "بونات اليونيسكو"],
        ];

        // Group 5: خردة و كهنه (Scrap)
        // Offset: 4000
        $scrap = [[1, "أصناف كهنة"], [2, "أصناف خردة"], [3, "أصناف راكدة"]];

        // --- Insertion Logic ---

        // 1. Insert Permanent (No Offset)
        foreach ($permanent as $cat) {
            Category::create([
                "id" => $cat[0],
                "cat_name" => $cat[1],
                "organization" => $organization,
                "type" => "المستديم",
                "notes" => null,
            ]);
        }

        // 2. Insert Consumable (Offset 1000)
        foreach ($consumable as $cat) {
            Category::create([
                "id" => 1000 + $cat[0],
                "cat_name" => $cat[1],
                "organization" => $organization,
                "type" => "الاستهلاكي",
                "notes" => null,
            ]);
        }

        // 3. Insert Agricultural (Offset 2000)
        foreach ($agricultural as $cat) {
            Category::create([
                "id" => 2000 + $cat[0],
                "cat_name" => $cat[1],
                "organization" => $organization,
                "type" => "محاصيل زراعية",
                "notes" => null,
            ]);
        }

        // 4. Insert Financial (Offset 3000)
        foreach ($financial as $cat) {
            Category::create([
                "id" => 3000 + $cat[0],
                "cat_name" => $cat[1],
                "organization" => $organization,
                "type" => "الدفاتر المالية",
                "notes" => null,
            ]);
        }

        // 5. Insert Scrap (Offset 4000)
        foreach ($scrap as $cat) {
            Category::create([
                "id" => 4000 + $cat[0],
                "cat_name" => $cat[1],
                "organization" => $organization,
                "type" => "خردة و كهنه",
                "notes" => null,
            ]);
        }
    }
}
