<aside class="app-sidebar bg-[#1a1a1a] shadow-xl h-screen flex flex-col" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand bg-black/20 border-b border-gray-700 h-16 flex items-center justify-center shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="brand-link flex items-center gap-3 text-decoration-none">
            <!-- Logo FIEI / UNFV -->
            <div class="bg-white p-1 rounded-full h-8 w-8 flex items-center justify-center">
                <span class="text-[#FF7F00] font-bold text-lg">V</span>
            </div>
            <span class="brand-text font-bold text-white tracking-wide text-lg">SISGU <span class="text-[#FF7F00]">FIEI</span></span>
        </a>
    </div>
    
    <div class="sidebar-wrapper flex-1 overflow-y-auto">
        <nav class="mt-4 px-2 pb-4">
            <ul class="nav sidebar-menu flex-column gap-1" role="navigation">
                
                <!-- Panel Principal -->
                <li class="nav-item mb-4">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF7F00] text-white shadow-lg shadow-orange-500/30' : 'text-gray-300 hover:bg-white/5' }} rounded-lg flex items-center px-4 py-3 transition-all">
                        <i class="nav-icon bi bi-grid-1x2-fill text-lg mr-3"></i>
                        <p class="font-medium">Panel Principal</p>
                    </a>
                </li>

                <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-4">Gestión Académica</li>

                <!-- Matricula -->
                <li class="nav-item">
                    <a href="{{ route('admin.matriculas.index') }}" class="nav-link {{ request()->routeIs('admin.matriculas.*') ? 'bg-[#FF7F00] text-white shadow-md' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center">
                        <i class="nav-icon bi bi-card-checklist mr-3 {{ request()->routeIs('admin.matriculas.*') ? 'text-white' : 'text-[#FF7F00]' }}"></i>
                        <p>Matrícula</p>
                    </a>
                </li>

                <!-- Estudiante -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('estudiantes*') ? 'bg-[#FF7F00] text-white shadow-md' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center">
                        <i class="nav-icon bi bi-mortarboard-fill mr-3 text-blue-400"></i>
                        <p>Estudiantes</p>
                    </a>
                </li>

                <!-- Profesores -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('profesores*') ? 'bg-[#FF7F00] text-white shadow-md' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center">
                        <i class="nav-icon bi bi-person-video3 mr-3 text-green-400"></i>
                        <p>Profesores</p>
                    </a>
                </li>

                <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2 px-4">Curricular</li>

                <!-- Cursos -->
                <li class="nav-item">
                    <a href="#" class="nav-link rounded-lg px-4 py-2.5 flex items-center text-gray-300 hover:bg-white/5">
                        <i class="nav-icon bi bi-book-half mr-3"></i>
                        <p>Cursos</p>
                    </a>
                </li>

                <!-- Asignaturas -->
                <li class="nav-item">
                    <a href="#" class="nav-link rounded-lg px-4 py-2.5 flex items-center text-gray-300 hover:bg-white/5">
                        <i class="nav-icon bi bi-collection mr-3"></i>
                        <p>Asignaturas</p>
                    </a>
                </li>

                <!-- Horarios -->
                <li class="nav-item">
                    <a href="#" class="nav-link rounded-lg px-4 py-2.5 flex items-center text-gray-300 hover:bg-white/5">
                        <i class="nav-icon bi bi-calendar-week mr-3"></i>
                        <p>Horarios</p>
                    </a>
                </li>

                <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2 px-4">Sistema</li>

                <!-- Configuraciones -->
                <li class="nav-item">
                    <a href="#" class="nav-link rounded-lg px-4 py-2.5 flex items-center text-gray-300 hover:bg-white/5">
                        <i class="nav-icon bi bi-gear-fill mr-3 text-gray-400"></i>
                        <p>Configuraciones</p>
                    </a>
                </li>
                
                <!-- Logout -->
                <li class="nav-item mt-6 border-t border-gray-700 pt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-red-400 hover:bg-red-500/10 w-full text-left rounded-lg px-4 py-2.5 flex items-center transition-colors">
                            <i class="nav-icon bi bi-box-arrow-left mr-3"></i>
                            <p>Cerrar Sesión</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>