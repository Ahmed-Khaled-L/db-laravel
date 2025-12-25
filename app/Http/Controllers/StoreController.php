<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Employee;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('responsibleEmployee')->get();
        $employees = Employee::all();
        return view('master.stores', compact('stores', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'classification' => 'required|in:Asset,Consumable',
            'custody_type' => 'required',
            'responsible_employee_id' => 'required|exists:employees,id'
        ]);

        Store::create($data);
        return redirect()->route('stores.index')->with('success', 'Store created.');
    }

    // Update and Destroy follow the same pattern...
}
