<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Register;
use App\Models\RegisterPage;
use App\Models\Store;

class RegisterSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        RegisterPage::truncate();
        Register::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Create Registers
        $registersData = [
            "سجل 118 مخازن (حكومي)",
            "سجل 112 عهدة شخصية",
            "سجل 121 استهلاك يومي",
        ];

        $registers = [];
        foreach ($registersData as $name) {
            $registers[] = Register::create(["register_name" => $name]);
        }

        // 2. Ensure we have stores to link pages to
        $stores = Store::all();
        if ($stores->isEmpty()) {
            // Create a dummy store if none exist
            $store = Store::create([
                "name" => "المخزن الرئيسي المؤقت",
                "classification" => "المستديم",
                "custody_type" => "مخزن رئيسي",
            ]);
            $stores = collect([$store]);
        }

        // 3. Create Register Pages (Linking Registers to Stores)
        // Scenario: Each store has allocated pages in these registers
        foreach ($registers as $register) {
            foreach ($stores as $index => $store) {
                // Assign 5 pages per store in each register
                for ($i = 1; $i <= 5; $i++) {
                    // Page number calculation to ensure uniqueness per register
                    $pageNo = $index * 10 + $i;

                    RegisterPage::create([
                        "register_id" => $register->id,
                        "page_number" => $pageNo,
                        "store_id" => $store->id,
                    ]);
                }
            }
        }
    }
}
