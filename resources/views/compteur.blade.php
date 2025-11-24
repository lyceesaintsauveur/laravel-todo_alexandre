
<!-- On appelle la template qui contient la navbar -->
@extends("template")

@section("title", "Ma Todo List")

@section("content")
<div class="container pt-4">
    <div class="card">
        <div class="card-header">
            <h4>Statistiques</h4>
        </div>
        <div class="card-body">
            <p><strong>Tâches terminées :</strong> {{ $terminees }}</p>
            <p><strong>Tâches non terminées :</strong> {{ $nonTerminees }}</p>
            <p><strong>Tâches supprimées :</strong> {{ $supprimees }}</p>
            <a href="{{ route('todo.liste') }}" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Retour à la liste</a>
        </div>
    </div>
</div>
@endsection