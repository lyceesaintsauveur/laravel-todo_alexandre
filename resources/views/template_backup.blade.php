<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark dark">
            <a class="navbar-brand" href="/">Ma Todo List</a>
            <a class="navbar-brand btn btn-primary" href="{{ route('todo.liste') }}">Todo</a>
            <a class="navbar-brand btn btn-primary" href="{{ route('todos.search') }}">Rechercher</a>
            <a class="navbar-brand btn btn-danger" href="{{ route('todo.compteur') }}">Compteur</a>
            <a class="navbar-brand btn btn-danger" href="{{ route('profile.edit') }}">Profil</a>
            <a class="navbar-brand btn btn-success" href="{{ route('listes.index') }}">Ajouter une liste</a>
            <a class="navbar-brand btn btn-info" href="{{ route('todo.planning') }}">Planning</a>
            <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </nav>

        @yield('content')
        @vite(['resources/js/app.js'])

    </body>
</html>
