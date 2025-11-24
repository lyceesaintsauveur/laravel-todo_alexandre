
<!-- On appelle la template qui contient la navbar -->
@extends("template")

@section("title", "Ma Todo List")

@section("content")
<div class="container pt-4">
    <div class="card">
        <div class="card-body">
            <!-- Action -->
            <form action="{{ route('todos.search') }}" method="POST" class="add">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="q" value="{{ $keyword ?? '' }}" placeholder="Mot-clé..." class="form-control mb-3">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    @if (session('message'))
                        <p class="alert alert-danger">{{ session('message') }}</p>
                    @endif
                </div>
            </form>

            @if(isset($todos) && count($todos) > 0)
                <h2 class="mt-4">Résultats :</h2>
                <ul class="list-group">
                    @foreach($todos as $todo)
                        <li class="list-group-item {{ $todo->trashed() ? 'text-muted' : '' }}">
                            {{ $todo->texte }}
                            @if($todo->trashed())
                                <span class="badge bg-secondary">Supprimé</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @elseif(isset($todos))
                <p>Aucun résultat trouvé.</p>
            @endif
        </div>
    </div>
</div>
@endsection