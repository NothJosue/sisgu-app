<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/img/user2-160x160.jpg') }}" class="user-image rounded-circle shadow" alt="User Image" />
                    <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Usuario' }}</span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        <img src="{{ asset('assets/img/user2-160x160.jpg') }}" class="rounded-circle shadow" alt="User Image" />
                        <p>
                            {{ Auth::user()->name ?? 'Usuario' }}
                            <small>{{ Auth::user()->rol }}</small>
                        </p>
                    </li>
                    
                    <li class="user-footer">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-default btn-flat float-end">Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>