<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <style>
            .logout-btn {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                padding: 0.5rem 1.2rem;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                margin-left: auto;
            }

            .logout-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
                color: white;
            }

            .logout-btn:active {
                transform: translateY(0);
            }

            .logout-btn i {
                margin-right: 0.5rem;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark dark">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-check2-all"></i> Ma Todo List
            </a>
            <div class="navbar-nav ms-auto d-flex gap-2 flex-wrap align-items-center">
                <a class="btn btn-sm btn-outline-light" href="{{ route('todo.liste') }}">
                    <i class="bi bi-list-check"></i> Todo
                </a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('todos.search') }}">
                    <i class="bi bi-search"></i> Rechercher
                </a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('todo.compteur') }}">
                    <i class="bi bi-graph-up"></i> Compteur
                </a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person-circle"></i> Profil
                </a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('listes.index') }}">
                    <i class="bi bi-plus-circle"></i> Liste
                </a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('todo.planning') }}">
                    <i class="bi bi-calendar3"></i> Planning
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm logout-btn text-white">
                        <i class="bi bi-door-left"></i> Déconnexion
                    </button>
                </form>
            </div>
        </nav>

        @yield('content')
        @vite(['resources/js/app.js'])

    </body>
</html>
