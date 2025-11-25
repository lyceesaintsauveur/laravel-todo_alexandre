<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Listes;
use App\Models\Todos;
use Illuminate\Http\Request;

class ListesController extends Controller
{
    // Affiche la page de gestion des listes (similaire à TodosController::liste)
    public function index()
    {
        // Charger les listes avec leurs todos et fournir aussi les catégories
        $listes = Listes::all();
        $categories = Categories::all();
        $todos = Todos::with('categories')->where('user_id', auth()->id())->get();
        $todosOrphans = $todos->where('listes_id', null);

        return view('listes', compact('listes', 'categories', 'todos', 'todosOrphans'));
    }

    // Alias pour garder une signature proche de TodosController
    public function liste()
    {
        return $this->index();
    }

    // Enregistre une nouvelle liste
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        Listes::create([
            'libelle' => $request->input('libelle'),
        ]);

        return redirect()->route('listes.index')->with('success', 'Liste créée.');
    }

    // Méthode 'saveListe' pour ressembler à la convention utilisée précédemment
    public function saveListe(Request $request)
    {
        $libelle = $request->input('libelle');

        if ($libelle) {
            $liste = new Listes;
            $liste->libelle = $libelle;
            $liste->save();

            return redirect()->route('listes.index');
        } else {
            return redirect()->route('listes.index')->with('message', 'Veuillez saisir un nom de liste');
        }
    }

    // suppression simple d'une liste
    public function delete($id)
    {
        $liste = Listes::find($id);
        if ($liste) {
            $liste->delete();
        }

        return redirect()->route('listes.index');
    }

    // Attacher un todo à une liste
    public function attachTodo($id, $listeId)
    {
        $todo = Todos::where('id', $id)->where('user_id', auth()->id())->first();
        $liste = Listes::find($listeId);

        if ($todo && $liste) {
            $todo->listes_id = $listeId;
            $todo->save();
        }

        return redirect()->route('listes.index');
    }

    // Voir tous les todos sans liste
    public function viewListeTodo()
    {
        $listes = Listes::all();
        $todosOrphans = Todos::whereNull('listes_id')->where('user_id', auth()->id())->get();

        return view('listes.viewListeTodo', compact('listes', 'todosOrphans'));
    }

    // Attacher plusieurs todos à une liste
    public function attachMultipleTodos(Request $request)
    {
        $todoIds = $request->input('todo_ids', []);
        $listeId = $request->input('liste_id');

        if (! empty($todoIds) && $listeId) {
            // Ne mettre à jour que les todos appartenant à l'utilisateur connecté
            Todos::whereIn('id', $todoIds)
                ->where('user_id', auth()->id())
                ->update(['listes_id' => $listeId]);
        }

        return redirect()->route('listes.index')->with('success', 'Todos attachés avec succès.');
    }
}
