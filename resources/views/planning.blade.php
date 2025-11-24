@extends("template")

@section("title", "Planning")

@section("content")
<div class="container pt-4">
    <div class="card">
        <div class="card-header">
            <h2>Planning - Mes Tâches Urgentes</h2>
            <p class="text-muted">Tâches non terminées avec date limite, classées par urgence</p>
        </div>
        <div class="card-body">
            @if($todos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tâche</th>
                                <th>Date de fin</th>
                                <th>Jours restants</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todos as $todo)
                                @php
                                    // Precise time-based calculation (minutes/seconds precision)
                                    $dateFin = $todo->date_fin;
                                    $now = now();
                                    // signed seconds remaining (positive => future, negative => past)
                                    $diffSeconds = $now->diffInSeconds($dateFin, false);
                                    $isOverdue = $diffSeconds < 0;
                                    // almost due = within next 3 days (in seconds)
                                    $isAlmostDue = $diffSeconds >= 0 && $diffSeconds <= (3 * 86400);
                                    $absSeconds = abs($diffSeconds);

                                    // human readable duration (days/hours/minutes)
                                    if ($absSeconds >= 86400) {
                                        $d = floor($absSeconds / 86400);
                                        $h = floor(($absSeconds % 86400) / 3600);
                                        $displayDuration = $d . 'j ' . $h . 'h';
                                    } elseif ($absSeconds >= 3600) {
                                        $h = floor($absSeconds / 3600);
                                        $m = floor(($absSeconds % 3600) / 60);
                                        $displayDuration = $h . 'h ' . $m . 'm';
                                    } elseif ($absSeconds >= 60) {
                                        $m = floor($absSeconds / 60);
                                        $displayDuration = $m . 'm';
                                    } else {
                                        $displayDuration = $absSeconds . 's';
                                    }
                                @endphp
                                <tr class="@if($isOverdue) table-danger @elseif($isAlmostDue) table-warning @endif">
                                    <td>
                                        <div>
                                            <strong>{{ $todo->texte }}</strong>
                                            @if($todo->important)
                                                <i class="bi bi-reception-4 text-danger" title="Important"></i>
                                            @endif
                                        </div>
                                        @if(isset($todo->listes) && $todo->listes)
                                            <small class="text-muted">
                                                <i class="bi bi-list-check"></i> {{ $todo->listes->libelle }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $dateFin->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($isOverdue)
                                            <div>
                                                <span class="badge bg-danger">DÉPASSÉE</span>
                                                <small class="text-danger ms-2">{{ $displayDuration }} de retard</small>
                                            </div>
                                        @elseif($isAlmostDue)
                                            <span class="badge bg-warning text-dark">{{ $displayDuration }} restant</span>
                                        @else
                                            <span class="badge bg-info">{{ $displayDuration }} restant</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('todo.done', $todo->id) }}" class="btn btn-sm btn-success" title="Marquer comme terminé">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                        <a href="{{ route('todo.delete', $todo->id) }}" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer la suppression ?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <strong>Aucune tâche planifiée !</strong><br>
                    Vous n'avez pas de tâche non terminée avec une date limite. 
                    <a href="{{ route('todo.liste') }}">Ajouter une tâche</a> ?
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
