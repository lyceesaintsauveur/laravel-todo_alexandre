<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Affiche la liste des catégories.
     *
     * @return \Illuminate\Http\Response
     */
    public function listeCatégories()
    {
        return view('home', ['categories' => Categories::all()]);
    }

    /**
     * Store a newly created category (AJAX)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        $category = Categories::create(['libelle' => $data['libelle']]);

        return response()->json(['id' => $category->id, 'libelle' => $category->libelle]);
    }
}
