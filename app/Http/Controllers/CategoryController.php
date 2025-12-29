<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display the list of categories.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Filter by Type
        if ($request->has("type") && $request->type != "") {
            $query->where("type", $request->type);
        }

        $categories = $query->orderBy("id")->get();

        // Get unique types for the filter dropdown
        $types = Category::select("type")->distinct()->pluck("type");

        return view("master.categories", compact("categories", "types"));
    }

    /**
     * Show the Create Page.
     */
    public function create()
    {
        // Suggest ID? (Optional logic could go here)
        return view("master.categories_create");
    }

    /**
     * Store new Category.
     */
    public function store(Request $request)
    {
        $request->validate([
            "id" => "required|integer",
            "type" => "required|string|max:50",
            "cat_name" => "required|string|max:255",
            "organization" => "nullable|string|max:255",
            "notes" => "nullable|string",
        ]);

        // Manual Unique Check for Composite Key
        $exists = Category::where("id", $request->id)
            ->where("type", $request->type)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    "id" => "هذا البند (الرقم + النوع) موجود بالفعل.",
                ])
                ->withInput();
        }

        Category::create($request->all());

        return redirect()
            ->route("categories.index")
            ->with("success", "تم إضافة البند بنجاح.");
    }

    /**
     * Show the Edit Page.
     * Note: We accept $id and $type from the URL.
     */
    public function edit($id, $type)
    {
        // Decode type if it contains special characters/spaces from URL
        $type = urldecode($type);

        $category = Category::where("id", $id)
            ->where("type", $type)
            ->firstOrFail();

        return view("master.categories_edit", compact("category"));
    }

    /**
     * Update Category.
     * Note: ID and Type in URL are the "Old" keys.
     * The Request contains the "New" keys (if changed).
     */
    public function update(Request $request, $id, $type)
    {
        $type = urldecode($type);
        $category = Category::where("id", $id)
            ->where("type", $type)
            ->firstOrFail();

        $request->validate([
            "id" => "required|integer",
            "type" => "required|string|max:50",
            "cat_name" => "required|string|max:255",
            "organization" => "nullable|string|max:255",
            "notes" => "nullable|string",
        ]);

        // If PK is changing, check for conflicts
        if ($request->id != $id || $request->type != $type) {
            $exists = Category::where("id", $request->id)
                ->where("type", $request->type)
                ->exists();
            if ($exists) {
                return back()
                    ->withErrors([
                        "id" =>
                            "التعديل الجديد يتعارض مع بند موجود بالفعل (نفس الرقم والنوع).",
                    ])
                    ->withInput();
            }
        }

        // Update logic (Since PK might change, strict update might be tricky with Eloquent's `update()` if PKs are involved)
        // Safer approach for Composite PK change: Update attributes manually.
        // NOTE: Laravel doesn't support updating composite PKs easily via `update()`.
        // We will delete and recreate IF the keys changed, or just update fields if keys didn't.

        if ($request->id != $id || $request->type != $type) {
            // Keys changed: Create new, Delete old
            Category::create($request->all());
            $category->delete();
        } else {
            // Keys same: Just update other fields
            Category::where("id", $id)
                ->where("type", $type)
                ->update([
                    "cat_name" => $request->cat_name,
                    "organization" => $request->organization,
                    "notes" => $request->notes,
                ]);
        }

        return redirect()
            ->route("categories.index")
            ->with("success", "تم تحديث البند بنجاح.");
    }

    /**
     * Delete Category.
     */
    public function destroy($id, $type)
    {
        $type = urldecode($type);
        Category::where("id", $id)->where("type", $type)->delete();

        return redirect()
            ->route("categories.index")
            ->with("success", "تم حذف البند بنجاح.");
    }
}
