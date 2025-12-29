<?php

use Illuminate\Support\Facades\Route;

// Import your Controllers
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonnelCustodyController;

// --- Dashboard ---
Route::get("/", [DashboardController::class, "index"])->name("dashboard");

Route::post("/logout", function () {
    return redirect()->route("dashboard");
})->name("logout");

// --- Master Data Resources ---

// Items
Route::resource("items", ItemController::class)->except([
    "create",
    "edit",
    "show",
]);

// Departments
Route::resource("departments", DepartmentController::class)->except([
    "create",
    "edit",
    "show",
]);

// Employees
Route::resource("employees", EmployeeController::class)->except([
    "create",
    "edit",
    "show",
]);

// Stores
// FIXED: Removed 'create' and 'edit' from except() so the Add/Edit pages work
Route::resource("stores", StoreController::class)->except(["show"]);

// Registers
Route::resource("registers", RegisterController::class)->except([
    "create",
    "edit",
    "show",
]);

// --- Categories (Custom Routes for Composite Keys) ---
// We do NOT use Route::resource for categories because of the composite key (id + type)

// 1. Index (List)
Route::get("categories", [CategoryController::class, "index"])->name(
    "categories.index",
);

// 2. Create (Page) - THIS WAS MISSING
Route::get("categories/create", [CategoryController::class, "create"])->name(
    "categories.create",
);

// 3. Store (Save Action)
Route::post("categories", [CategoryController::class, "store"])->name(
    "categories.store",
);

// 4. Edit (Page) - Uses composite key {id}/{type}
Route::get("categories/{id}/{type}/edit", [
    CategoryController::class,
    "edit",
])->name("categories.edit");

// 5. Update (Save Action) - Uses composite key {id}/{type}
Route::put("categories/{id}/{type}", [
    CategoryController::class,
    "update",
])->name("categories.update");

// 6. Delete (Action) - Uses composite key {id}/{type}
Route::delete("categories/{id}/{type}", [
    CategoryController::class,
    "destroy",
])->name("categories.destroy");

Route::get('/custody/personnel', [PersonnelCustodyController::class, 'index'])->name('custody.personnel.index');
Route::get('/custody/personnel/create', [PersonnelCustodyController::class, 'create'])->name('custody.personnel.create');
Route::post('/custody/personnel', [PersonnelCustodyController::class, 'store'])->name('custody.personnel.store');

// Details Steps
Route::get('/custody/details/{auditId}', [PersonnelCustodyController::class, 'createDetails'])->name('custody.details.create');
Route::post('/custody/details/{auditId}', [PersonnelCustodyController::class, 'storeDetails'])->name('custody.details.store');
