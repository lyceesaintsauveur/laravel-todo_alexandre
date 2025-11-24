<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodosController;
//use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Models\Todos;

// Route::bind('todos', function ($value) {
//     return Todos::with('categories')->find($value) ?? abort(404);
// });

// Exemple de route utilisant le binding
// Route::get('/todos/{todos}', function (Todos $todos) {
//     return view('todo.show', compact('todos'));
// });


    Route::get('/compteur', [TodosController::class, 'stats'])->name('todo.compteur');
    Route::get('/search', [TodosController::class, 'search'])->name('todos.search');
    Route::post('/search', [TodosController::class, 'search'])->name('todos.search');
    Route::get('/planning', [TodosController::class, 'planning'])->name('todo.planning');Route::get('/liste', [TodosController::class, 'liste'])->name('listes.liste');
    Route::get('/liste/add', [TodosController::class, 'saveListe'])->name('listes.save');



// Route::view ('/test', 'template');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Route::get('/', [TodosController::class, 'liste']);
    Route::get('/', [TodosController::class, 'liste'])->name('todo.liste');

    // Envoie du formulaire via la méthode POST pour l'enregister dans la base de donnée
    Route::post('/action/add', [TodosController::class, 'saveTodo'])->name('todo.save');

    // On passe l'id de l'élément dans l'URL pour le modifier
    // Ici on utilise GET car on utilise pas de forms dans la page
    // ET on pourra récupérer l'id dans la fonction grace au paramètre: upImportance($id)
    Route::get('/action/up/{id}', [TodosController::class, 'upImportance'])->name('todo.raise');
    Route::get('/action/low/{id}', [TodosController::class, 'downImportance'])->name('todo.lower');

    Route::get('/action/done/{id}', [TodosController::class, 'done'])->name('todo.done');
    Route::get('/action/delete/{id}', [TodosController::class, 'delete'])->name('todo.delete');

    // Pages et actions pour gérer les listes (création simple)
    Route::get('/listes', [\App\Http\Controllers\ListesController::class, 'index'])->name('listes.index');
    Route::post('/listes', [\App\Http\Controllers\ListesController::class, 'store'])->name('listes.store');
    Route::get('/listes/delete/{id}', [\App\Http\Controllers\ListesController::class, 'delete'])->name('listes.delete');
    Route::get('/listes/attach/{id}/{listeId}', [\App\Http\Controllers\ListesController::class, 'attachTodo'])->name('listes.attach');
    Route::get('/listes/view-orphan-todos', [\App\Http\Controllers\ListesController::class, 'viewListeTodo'])->name('listes.viewListeTodo');
    Route::post('/listes/attach-multiple', [\App\Http\Controllers\ListesController::class, 'attachMultipleTodos'])->name('listes.attachMultiple');
    
    // Créer une catégorie via AJAX
    Route::post('/categories', [\App\Http\Controllers\CategoriesController::class, 'store'])->name('categories.store');

    
});

require __DIR__.'/auth.php';
