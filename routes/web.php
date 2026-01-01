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
use App\Http\Controllers\InventoryCustodyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditReportController;
use App\Http\Controllers\AssetController;

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

Route::post('stores/{id}/assign-item', [StoreController::class, 'assignItem'])->name('stores.assignItem');
Route::delete('stores/{id}/remove-item/{itemId}', [StoreController::class, 'removeItem'])->name('stores.removeItem');

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

// NEW: Edit, Update, and Delete Routes
Route::get('/custody/personnel/{id}/edit', [PersonnelCustodyController::class, 'edit'])->name('custody.personnel.edit');
Route::put('/custody/personnel/{id}', [PersonnelCustodyController::class, 'update'])->name('custody.personnel.update');
Route::delete('/custody/personnel/{id}', [PersonnelCustodyController::class, 'destroy'])->name('custody.personnel.destroy');

// NEW: Routes for handling Details CRUD from the Index Modal
Route::post('/custody/details/single/{auditId}', [PersonnelCustodyController::class, 'storeSingleDetail'])->name('custody.details.storeSingle');
Route::put('/custody/details/single/update', [PersonnelCustodyController::class, 'updateSingleDetail'])->name('custody.details.updateSingle');
Route::delete('/custody/details/single/delete', [PersonnelCustodyController::class, 'destroySingleDetail'])->name('custody.details.destroySingle');

// Details Steps
Route::get('/custody/details/{auditId}', [PersonnelCustodyController::class, 'createDetails'])->name('custody.details.create');
Route::post('/custody/details/{auditId}', [PersonnelCustodyController::class, 'storeDetails'])->name('custody.details.store');


// Inventory Custody Routes
Route::get('/custody/inventory', [InventoryCustodyController::class, 'index'])->name('custody.inventory.index');
Route::get('/custody/inventory/create', [InventoryCustodyController::class, 'create'])->name('custody.inventory.create');
Route::post('/custody/inventory', [InventoryCustodyController::class, 'store'])->name('custody.inventory.store');
Route::get('/custody/inventory/{id}/edit', [InventoryCustodyController::class, 'edit'])->name('custody.inventory.edit');
Route::put('/custody/inventory/{id}', [InventoryCustodyController::class, 'update'])->name('custody.inventory.update');
Route::delete('/custody/inventory/{id}', [InventoryCustodyController::class, 'destroy'])->name('custody.inventory.destroy');

// Inventory Details Steps (if using the multi-step page)
Route::get('/custody/inventory/details/{auditId}', [InventoryCustodyController::class, 'createDetails'])->name('custody.inventory.details');
// Note: storeDetails method in InventoryController for bulk add
Route::post('/custody/inventory/details/{auditId}', [InventoryCustodyController::class, 'storeDetails'])->name('custody.inventory.storeDetails');

Route::get('/reports/custody', [ReportController::class, 'custodyReport'])->name('reports.custody');



Route::get('/audit/report', [AuditReportController::class, 'index'])->name('audit.report');





Route::resource('assets', AssetController::class)->except(['create', 'show', 'edit']);
Route::get('/reports/assets', [AssetController::class, 'report'])->name('reports.assets');
