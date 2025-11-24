@extends('template')

@section('title', 'Gestion des listes')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-body">
            <h4>Créer une nouvelle liste</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('message'))
                <p class="alert alert-danger">{{ session('message') }}</p>
            @endif

            <form action="{{ route('listes.store') }}" method="POST">
                @csrf
                <div class="input-group mb-2">
                    <input type="text" name="libelle" class="form-control" placeholder="Nom de la liste" required>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Créer</button>
                    </div>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>

            <hr>

            <!-- Section Todos Orphelins -->
            @if(isset($todosOrphans) && $todosOrphans->count() > 0)
                <div class="mt-4">
                    <h5>Ajouter des todos</h5>
                    <form action="{{ route('listes.attachMultiple') }}" method="POST" class="card p-3">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label fw-bold" for="selectAll">
                                    Sélectionner tous les todos
                                </label>
                            </div>
                        </div>

                        <ul class="list-group mb-3">
                            @foreach($todosOrphans as $todo)
                                <li class="list-group-item d-flex gap-2 align-items-center">
                                    <input class="form-check-input todo-checkbox" type="checkbox" value="{{ $todo->id }}" name="todo_ids[]" id="todo-{{ $todo->id }}">
                                    <label class="form-check-label flex-grow-1" for="todo-{{ $todo->id }}">
                                        @if ($todo->important == 1)
                                            <i class="bi bi-reception-4"></i>
                                        @else
                                            <i class="bi bi-reception-1"></i>
                                        @endif
                                        {{ $todo->texte }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                        <div class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <label for="liste_id" class="form-label">Ajouter à la liste :</label>
                                <select name="liste_id" id="liste_id" class="form-select" required>
                                    <option value="">-- Choisir une liste --</option>
                                    @foreach($listes as $liste)
                                        <option value="{{ $liste->id }}">{{ $liste->libelle }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Ajouter les sélectionnés</button>
                        </div>
                    </form>
                </div>

                <hr>
            @endif

            <h5>Listes existantes</h5>
            <ul class="list-group">
                @forelse($listes as $liste)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $liste->libelle }}</strong>
                            <div>
                                <a href="{{ route('listes.delete', ['id' => $liste->id]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette liste ?')">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Liste -->
                        <ul class="list-group list-group-flush mt-2 mb-2">
                            @php $hasTodos = false; @endphp
                            @foreach($todos as $todo)
                                @if($todo->listes_id == $liste->id)
                                    @php $hasTodos = true; @endphp
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            @if ($todo->important == 1)
                                                <i class="bi bi-reception-4"></i>
                                            @else
                                                <i class="bi bi-reception-1"></i>
                                            @endif
                                            {{ $todo->texte }}
                                        </span>
                                        <div>
                                            @if ($todo->termine === 0)
                                                <a href="{{ route('todo.done', ['id' => $todo->id, 'from_listes' => 1]) }}" class="btn btn-sm btn-success"><i class="bi bi-check2-square"></i></a>
                                            @else
                                                <a href="{{ route('todo.delete', ['id' => $todo->id, 'from_listes' => 1]) }}" class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></a>
                                            @endif
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                            @if(! $hasTodos)
                                <li class="list-group-item text-muted">Aucun todo dans cette liste.</li>
                            @endif
                        </ul>

                        <!-- Formulaire d'ajout de todo -->
                        <form action="{{ route('todo.save') }}" method="POST" class="d-flex flex-wrap gap-2">
                            @csrf
                            <input type="hidden" name="listes_id" value="{{ $liste->id }}">
                            <input type="hidden" name="from_listes" value="1">
                            <input type="text" name="texte" class="form-control" placeholder="Ajouter un todo..." required style="flex: 1;">
                            <select name="priority" class="form-select" style="width:120px">
                                <option value="0" selected>Bas</option>
                                <option value="1">Haut</option>
                            </select>
                            <input type="datetime-local" name="date_fin" class="form-control" style="flex: 1; max-width: 250px;" title="Date de fin (optionnel)">
                            <button class="btn btn-primary" type="submit">Ajouter</button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item text-center">Aucune liste pour le moment.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = Array.from(document.querySelectorAll('.todo-checkbox'));

        if (!selectAll) return;

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });

        checkboxes.forEach(cb => cb.addEventListener('change', function () {
            if (!cb.checked) {
                selectAll.checked = false;
            } else if (checkboxes.every(ch => ch.checked)) {
                selectAll.checked = true;
            }
        }));
    });
</script>

@endsection
