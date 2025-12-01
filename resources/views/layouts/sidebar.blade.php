<aside class="app-sidebar bg-[#1a1a1a] shadow-xl h-screen flex flex-col" data-bs-theme="dark">
    <div class="sidebar-brand bg-black/20 border-b border-gray-700 h-16 flex items-center justify-center shrink-0">
        <a href="#" class="brand-link flex items-center gap-3 text-decoration-none">
            <div class="bg-white p-1 rounded-full h-8 w-8 flex items-center justify-center">
                <span class="text-[#FF7F00] font-bold text-lg">V</span>
            </div>
            <span class="brand-text font-bold text-white tracking-wide text-lg">SISGU <span
                    class="text-[#FF7F00]">FIEI</span></span>
        </a>
    </div>

    <div class="sidebar-wrapper flex-1 overflow-y-auto">
        <nav class="mt-4 px-2 pb-4">
            <ul class="nav sidebar-menu flex-column gap-1" role="navigation">

                {{-- ======================================================= --}}
                {{-- MENÚ DE ADMINISTRADOR --}}
                {{-- ======================================================= --}}
                @if (Auth::user()->rol == 'Admin')
                    <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-4">
                        Administración</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF7F00] text-white' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center">
                            <i class="nav-icon bi bi-speedometer2 mr-3"></i>
                            <p>Dashboard Admin</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.matriculas.index') }}"
                            class="nav-link {{ request()->routeIs('admin.matriculas.*') ? 'bg-[#FF7F00] text-white' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center">
                            <i class="nav-icon bi bi-card-checklist mr-3"></i>
                            <p>Gestión Matrículas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#"
                            class="nav-link text-gray-300 hover:bg-white/5 rounded-lg px-4 py-2.5 flex items-center">
                            <i class="nav-icon bi bi-people-fill mr-3"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                @endif

                {{-- ======================================================= --}}
                {{-- MENÚ DE ESTUDIANTE --}}
                {{-- ======================================================= --}}
                @if (Auth::user()->rol == 'Estudiante')
                    <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-4">Zona
                        Estudiante</li>

                    <li class="nav-item">
                        <a href="{{ route('estudiante.dashboard') }}"
                            class="nav-link {{ request()->routeIs('estudiante.dashboard') ? 'bg-[#FF7F00] text-white' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center">
                            <i class="nav-icon bi bi-person-workspace mr-3"></i>
                            <p>Mi Panel</p>
                        </a>
                    </li>

                    {{-- 2. MENÚ DESPLEGABLE DE MATRÍCULA (Basado en tu diagrama) --}}
                    {{-- La clase 'menu-open' se añade si estamos en alguna ruta de matrícula --}}
                    <li class="nav-item {{ request()->is('estudiante/matricula*') ? 'menu-open' : '' }}">

                        <a href="#"
                            class="nav-link {{ request()->is('estudiante/matricula*') ? 'active bg-white/10' : 'text-gray-300' }}" data-lte-toggle="treeview">
                            <i class="nav-icon bi bi-journal-text"></i>
                            <p>
                                Trámites de Matrícula
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        {{-- Sub-menú --}}
                        <ul class="nav nav-treeview">

                            {{-- Caso de uso: Matricular / Verificar Matrícula --}}
                            <li class="nav-item">
                                <a href="{{ route('estudiante.matricula.regular') }}" class="nav-link {{ request()->routeIs('estudiante.matricula.regular') ? 'active text-[#FF7F00]' : '' }}">
                                    <i class="nav-icon bi bi-circle text-xs"></i>
                                    <p>Matrícula Regular</p>
                                </a>
                            </li>

                            {{-- Caso de uso: Reservar matrícula --}}
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-circle text-xs"></i>
                                    <p>Reservar Matrícula</p>
                                </a>
                            </li>

                            {{-- Caso de uso: Ampliar créditos --}}
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-circle text-xs"></i>
                                    <p>Ampliar Créditos</p>
                                </a>
                            </li>

                            {{-- Caso de uso: Reactualizar matrícula --}}
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-circle text-xs"></i>
                                    <p>Reactualizar Matrícula</p>
                                </a>
                            </li>

                            {{-- Caso de uso: Rectificar matrícula --}}
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-circle text-xs"></i>
                                    <p>Rectificar Matrícula</p>
                                </a>
                            </li>

                            {{-- Caso de uso: Retirar matrícula --}}
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-circle text-xs"></i>
                                    <p>Retiro de Matrícula</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                {{-- Ejemplo de ruta futura --}}
                                <a href="#"
                                    class="nav-link text-gray-300 hover:bg-white/5 rounded-lg px-4 py-2.5 flex items-center">
                                    <i class="nav-icon bi bi-journal-check mr-3"></i>
                                    <p>Mis Notas</p>
                                </a>
                            </li>
                            {{-- Generar Boleta (Del diagrama) --}}
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-receipt"></i>
                                    <p>Mi Boleta de Notas</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    {{-- 3. Otros trámites (Suspensión / Periodo de observación) --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link text-gray-300">
                            <i class="nav-icon bi bi-exclamation-triangle"></i>
                            <p>Suspensión / Retiro</p>
                        </a>
                    </li>
                @endif

                {{-- ======================================================= --}}
                {{-- MENÚ DE PROFESOR --}}
                {{-- ======================================================= --}}
                @if (Auth::user()->rol == 'Profesor')
                    <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-4">Zona
                        Docente</li>

                    <li class="nav-item">
                        <a href="{{ route('profesor.dashboard') }}"
                            class="nav-link {{ request()->routeIs('profesor.dashboard') ? 'bg-[#FF7F00] text-white' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center">
                            <i class="nav-icon bi bi-easel2-fill mr-3"></i>
                            <p>Panel Docente</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#"
                            class="nav-link text-gray-300 hover:bg-white/5 rounded-lg px-4 py-2.5 flex items-center">
                            <i class="nav-icon bi bi-pencil-square mr-3"></i>
                            <p>Ingresar Notas</p>
                        </a>
                    </li>
                @endif

                {{-- ======================================================= --}}
                {{-- OPCIONES COMUNES (LOGOUT) --}}
                {{-- ======================================================= --}}
                <li class="nav-item mt-6 border-t border-gray-700 pt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="nav-link text-red-400 hover:bg-red-500/10 w-full text-left rounded-lg px-4 py-2.5 flex items-center transition-colors">
                            <i class="nav-icon bi bi-box-arrow-left mr-3"></i>
                            <p>Cerrar Sesión</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>
