<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show(string $id)
    {
        $category = Category::with('barangs')->find($id);
        if (!$category) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        return response()->json(['success' => true, 'data' => $category]);
    }
}
