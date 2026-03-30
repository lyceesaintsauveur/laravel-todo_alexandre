<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Listes;
use App\Models\Todos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodosController extends Controller
{
    public function liste(Request $request, $status = 'all')
    {
        $user = auth()->user();

        // Autoriser filtre via query param ou segment d'URL
        $status = $request->query('status', $status);
        $status = in_array($status, ['all', 'pending', 'done']) ? $status : 'all';

        $query = $user->todos()->with('categories', 'listes');

        if ($status === 'pending') {
            $query->where('termine', 0);
        } elseif ($status === 'done') {
            $query->where('termine', 1);
        }

        $todos = $query->get();

        return view('home', [
            'todos' => $todos,
            'categories' => Categories::all(),
            'listes' => Listes::all(),
            'statusFilter' => $status,
        ]);
    }

    public function saveTodo(Request $request)
    {
        $texte = $request->input('texte');
        $priority = $request->input('priority');
        $listeId = $request->input('listes_id');
        $dateFin = $request->input('date_fin');
        $categories = $request->input('categories', []);
        // dd($request->input('priority')); // fonction de débug

        $validator = Validator::make($request->all(), [
            'texte' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('todo.liste')->with('message', 'Erreur dans la saisie du texte');

        }

        if ($texte) {
            // création d'un nouvel élément Todos et enregistrement dans la base de donnée
            $todo = new Todos;
            $todo->texte = $texte;
            $todo->termine = 0;
            $todo->important = $priority;
            // lier à une liste si fournie
            if ($listeId) {
                $todo->listes_id = $listeId;
            }
            // assigner la date de fin si fournie
            if ($dateFin) {
                $todo->date_fin = $dateFin;
            }

            // assigner le ToDo à l'utilisateur connecté
            $todo->user_id = auth()->id();

            // save() pour mettre a jour et insérer des éléments dans la base
            $todo->save();

            // Attacher les catégories sélectionnées
            if (! empty($categories)) {
                $todo->categories()->attach($categories);
            }

            // Si la requête vient de la page listes, retourner sur listess.index
            if ($request->input('from_listes')) {
                return redirect()->route('listes.index');
            }

            // après la modification on retourne sur notre vue "home" qui a comme nom "todo.liste"
            return redirect()->route('todo.liste');
        } else {
            // Si la requête vient de la page listes, rediriger vers listes.index
            if ($request->input('from_listes')) {
                return redirect()->route('listes.index')->with('message', 'Veuillez saisir une note à ajouter');
            }

            return redirect()->route('todo.liste')->with('message', 'Veuillez saisir une note à ajouter');
        }

    }

    public function upImportance($id)
    {
        $todo = auth()->user()->todos()->findOrFail($id);
        $todo->important = 1;
        $todo->save();

        return redirect()->route('todo.liste');
    }

    public function downImportance($id)
    {
        $todo = auth()->user()->todos()->findOrFail($id);
        $todo->important = 0;
        $todo->save();

        return redirect()->route('todo.liste');
    }

    public function done(Request $request, $id)
    {
        $todo = auth()->user()->todos()->findOrFail($id);
        $todo->termine = ! $todo->termine;
        $todo->save();
        if ($request->input('from_listes')) {
            return redirect()->route('listes.index');
        }

        return redirect()->route('todo.liste');
    }

    public function delete(Request $request, $id)
    {
        $todo = auth()->user()->todos()->findOrFail($id);
        if ($todo->termine) {
            $todo->delete();
            if ($request->input('from_listes')) {
                return redirect()->route('listes.index');
            }

            return redirect()->route('todo.liste');
        } else {
            if ($request->input('from_listes')) {
                return redirect()
                    ->route('listes.index')
                    ->with('message', 'Veuillez terminé la tache avant de la supprimer');
            }

            return redirect()
                ->route('todo.liste')
                ->with('message', 'Veuillez terminé la tache avant de la supprimer');
        }
    }

    public function stats()
    {
        $userId = auth()->id();
        $terminees = Todos::forUser($userId)->where('termine', 1)->count();
        $nonTerminees = Todos::forUser($userId)->where('termine', 0)->count();
        $supprimees = Todos::onlyTrashed()->forUser($userId)->count();

        return view('compteur', compact('terminees', 'nonTerminees', 'supprimees'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $todos = [];

        if ($keyword) {
            $todos = Todos::forUser(auth()->id())->where('texte', 'LIKE', "%{$keyword}%")->get();
        }

        return view('search', compact('todos', 'keyword'));
    }

    public function planning()
    {
        // Récupérer les todos non terminés avec une date de fin, classés par urgence (du plus urgent au moins urgent)
        $todos = auth()->user()->todos()
            ->where('termine', 0)
            ->whereNotNull('date_fin')
            ->orderBy('date_fin', 'ASC')
            ->get();

        return view('planning', compact('todos'));
    }
}
