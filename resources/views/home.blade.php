@extends("template")

@section("title", "Ma Todo List")

@section("content")
<div class="container pt-4">
    <div class="card">
        <div class="card-body">

            <!-- Formulaire d'ajout -->
            <form action="{{ route('todo.save') }}" method="POST" class="add">
                @csrf
                
                <!-- Titre du formulaire -->
                <h4 class="mb-3">
                    <i class="bi bi-plus-circle"></i> Ajouter une nouvelle tâche
                </h4>

                <!-- Texte de la tâche -->
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-pencil-square"></i>
                    </span>
                    <input id="texte" name="texte" type="text" class="form-control"
                           placeholder="Décrivez votre tâche..." aria-label="Nouvelle tâche" required>

                    @if (session('message'))
                        <div class="alert alert-danger mt-2">{{ session('message') }}</div>
                    @endif
                </div>

                <!-- Catégories -->
                <div class="form-group mb-3 p-3 bg-light rounded">
                    <label class="form-label fw-bold">
                        <i class="bi bi-boxes"></i> Catégories
                    </label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $categorie)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                       id="cat_{{ $categorie->id }}" value="{{ $categorie->id }}">
                                <label class="form-check-label" for="cat_{{ $categorie->id }}">
                                    {{ $categorie->libelle }}
                                </label>
                            </div>
                        @endforeach
                        <!-- Input rapide pour ajouter une catégorie sans reloader -->
                        <div class="ms-2 d-flex align-items-center" id="new-category-wrapper">
                            <input id="new-category-input" class="form-control form-control-sm me-2" placeholder="Nouvelle catégorie" style="width:160px;">
                            <button type="button" id="add-category-btn" class="btn btn-sm btn-outline-primary">Ajouter</button>
                        </div>
                    </div>
                </div>

                <!-- Priorité -->
                <div class="mb-3 p-3 bg-light rounded">
                    <label class="form-label fw-bold">
                        <i class="bi bi-exclamation-circle"></i> Importance
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priority" id="lowpr" value="0" checked>
                            <label class="form-check-label" for="lowpr">
                                <i class="bi bi-reception-1"></i> Normale
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priority" id="highpr" value="1">
                            <label class="form-check-label" for="highpr">
                                <i class="bi bi-reception-4"></i> Important
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Date de fin -->
                <div class="mb-3 p-3 bg-light rounded border-2" style="border-color: #0d6efd !important;">
                    <label for="date_fin" class="form-label fw-bold">
                        <i class="bi bi-calendar2-check"></i> Date de fin (Optionnel)
                    </label>
                    <p class="text-muted small mb-2">
                        Définissez une date limite pour cette tâche. Cela l'affichera dans votre Planning avec le niveau d'urgence approprié.
                    </p>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="date_fin_date" class="form-text small">Date</label>
                            <input type="date" id="date_fin_date" class="form-control" placeholder="jj/mm/aaaa">
                        </div>
                        <div class="col-md-6">
                            <label for="date_fin_time" class="form-text small">Heure</label>
                            <input type="time" id="date_fin_time" class="form-control" value="09:00">
                        </div>
                    </div>
                    <input type="hidden" id="date_fin" name="date_fin">
                    <small class="text-info mt-2 d-block">
                        <i class="bi bi-info-circle"></i> Cette tâche apparaîtra dans la section Planning
                    </small>
                </div>

                <script>
                    document.getElementById('date_fin_date').addEventListener('change', updateDateFin);
                    document.getElementById('date_fin_time').addEventListener('change', updateDateFin);

                    function updateDateFin() {
                        const date = document.getElementById('date_fin_date').value;
                        const time = document.getElementById('date_fin_time').value;
                        if (date && time) {
                            document.getElementById('date_fin').value = date + 'T' + time;
                        }
                    }
                </script>

                <script>
                    // Ajout AJAX d'une nouvelle catégorie
                    document.getElementById('add-category-btn').addEventListener('click', async function () {
                        const input = document.getElementById('new-category-input');
                        const libelle = input.value.trim();
                        if (!libelle) return;

                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        try {
                            const res = await fetch("{{ route('categories.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ libelle })
                            });

                            if (!res.ok) throw new Error('Erreur');
                            const data = await res.json();

                            // Créer le checkbox et l'ajouter
                            const container = document.querySelector('#new-category-wrapper').parentElement;
                            const wrapper = document.createElement('div');
                            wrapper.className = 'form-check';
                            wrapper.innerHTML = `
                                <input class="form-check-input" type="checkbox" name="categories[]" id="cat_${data.id}" value="${data.id}" checked>
                                <label class="form-check-label" for="cat_${data.id}">${data.libelle}</label>
                            `;
                            container.insertBefore(wrapper, document.getElementById('new-category-wrapper'));

                            // clear input
                            input.value = '';
                        } catch (e) {
                            alert('Impossible d\'ajouter la catégorie');
                        }
                    });
                </script>

                <!-- Bouton d'envoi -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-lg"></i> Créer la tâche
                    </button>
                </div>
            </form>

            <!-- Liste -->
            <div class="d-flex gap-2 mb-3" id="todo-filter-buttons">
                <button type="button" class="btn btn-outline-primary btn-sm {{ (isset($statusFilter) ? $statusFilter : 'all') === 'all' ? 'active' : '' }}" data-filter="all" data-url="{{ route('todo.liste.status', 'all') }}">Toutes</button>
                <button type="button" class="btn btn-outline-primary btn-sm {{ (isset($statusFilter) ? $statusFilter : 'all') === 'pending' ? 'active' : '' }}" data-filter="pending" data-url="{{ route('todo.liste.status', 'pending') }}">En cours</button>
                <button type="button" class="btn btn-outline-primary btn-sm {{ (isset($statusFilter) ? $statusFilter : 'all') === 'done' ? 'active' : '' }}" data-filter="done" data-url="{{ route('todo.liste.status', 'done') }}">Terminées</button>
            </div>

            <ul class="list-group pt-3">
                @forelse ($todos as $todo)
                    <li class="list-group-item" data-termine="{{ $todo->termine }}">

                        <!-- Affichage de la priorité -->
                        @if ($todo->important == 0)
                            <i class="bi bi-reception-1"></i>
                        @else
                            <i class="bi bi-reception-4"></i>
                        @endif

                        <!-- Texte -->
                        <span>{{ $todo->texte }}</span>

                        <!-- Appartenance à une liste -->
                        @if(isset($todo->listes) && $todo->listes)
                            <span class="badge bg-primary ms-2">
                                <i class="bi bi-list-check"></i> {{ $todo->listes->libelle }}
                            </span>
                        @endif

                        <!-- Dates -->
                        <div class="mt-2 text-muted small">
                            @if($todo->created_at)
                                <div>
                                    <i class="bi bi-calendar-event"></i> 
                                    Créé : <strong>{{ $todo->created_at->format('d/m/Y à H:i') }}</strong>
                                </div>
                            @endif
                            @if($todo->date_fin)
                                <div>
                                    <i class="bi bi-calendar2-check"></i> 
                                    À faire avant : <strong>{{ $todo->date_fin->format('d/m/Y à H:i') }}</strong>
                                </div>
                            @endif
                        </div>

                        <!-- Catégories -->
                        @if ($todo->categories && $todo->categories->count() > 0)
                            <div class="form-group mt-2">
                                <label><i class="bi bi-boxes"></i> Catégories :</label>
                                @foreach($todo->categories as $category)
                                    <span class="badge bg-secondary">{{ $category->libelle }}</span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Actions : Terminer / Supprimer -->
                        <div class="mt-2">
                            @if ($todo->termine == 0)
                                <a href="{{ route('todo.done', $todo->id) }}" class="btn btn-success">
                                    <i class="bi bi-check2-square"></i>
                                </a>
                            @else
                                <button type="button" class="btn btn-danger btn-sm btn-delete-todo" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-action="{{ route('todo.delete', $todo->id) }}" data-texte="{{ addslashes($todo->texte) }}">
                                    <i class="bi bi-trash3"></i>
                                </button>

                                @if (session('validation'))
                                    <p class="alert alert-success">{{ session('validation') }}</p>
                                @endif
                            @endif

                            <!-- Actions priorité -->
                            @if ($todo->important == 0)
                                <a href="{{ route('todo.raise', $todo->id) }}">
                                    <i class="bi bi-arrow-up-circle"></i>
                                </a>
                            @else
                                <a href="{{ route('todo.lower', $todo->id) }}">
                                    <i class="bi bi-arrow-down-circle"></i>
                                </a>
                            @endif
                        </div>

                    </li>
                @empty
                    <li class="list-group-item text-center">C'est vide !</li>
                @endforelse
            </ul>

        </div>
    </div>
</div>

<form id="deleteTodoForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer cette tâche ?</p>
                <p class="fw-bold" id="deleteTodoText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-delete-todo').forEach(function(button) {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const texte = this.dataset.texte || 'Cette tâche';
            const form = document.getElementById('deleteTodoForm');
            form.action = action;
            document.getElementById('deleteTodoText').textContent = texte;
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        document.getElementById('deleteTodoForm').submit();
    });

    // Filtrage des todos (Toutes / En cours / Terminées) sans rechargement de page
    const filterButtons = document.querySelectorAll('#todo-filter-buttons button');
    const todoItems = document.querySelectorAll('[data-termine]');

    function applyFilter(filter) {
        todoItems.forEach(item => {
            const termine = item.dataset.termine === '1';

            if (filter === 'all') {
                item.style.display = '';
            } else if (filter === 'pending') {
                item.style.display = termine ? 'none' : '';
            } else if (filter === 'done') {
                item.style.display = termine ? '' : 'none';
            }
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            applyFilter(button.dataset.filter);
        });
    });

    // Initialisation
    applyFilter('all');
</script>

@endsection
