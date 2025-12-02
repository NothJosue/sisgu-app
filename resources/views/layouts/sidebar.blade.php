<aside class="app-sidebar bg-[#1a1a1a] shadow-xl h-screen flex flex-col fixed left-0 top-0 bottom-0 overflow-hidden" style="width: 250px; transition: all 0.3s;" data-bs-theme="dark">
    
    {{-- LOGO --}}
    <div class="sidebar-brand bg-black/20 border-b border-gray-700 h-16 flex items-center justify-center shrink-0">
        <a href="#" class="brand-link flex items-center gap-3 text-decoration-none">
            <div class="bg-white p-1 rounded-full h-8 w-8 flex items-center justify-center shadow-lg">
                <span class="text-[#FF7F00] font-bold text-lg">V</span>
            </div>
            <span class="brand-text font-bold text-white tracking-wide text-lg">SISGU <span class="text-[#FF7F00]">FIEI</span></span>
        </a>
    </div>

    {{-- MENU WRAPPER --}}
    <div class="sidebar-wrapper flex-1 overflow-y-auto custom-scrollbar">
        <nav class="mt-4 px-2 pb-20"> {{-- pb-20 para dar espacio al scroll final --}}
            <ul class="nav sidebar-menu flex-column gap-1" role="navigation">

                {{-- ======================================================= --}}
                {{-- MENÚ DE ADMINISTRADOR --}}
                {{-- ======================================================= --}}
                @if (Auth::user()->rol == 'Admin')
                    <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-4 mt-2">
                        Administración
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF7F00] text-white shadow-md' : 'text-gray-300 hover:bg-white/10' }} rounded-lg px-4 py-2.5 flex items-center transition-all duration-200">
                            <i class="nav-icon bi bi-speedometer2 mr-3 text-lg"></i>
                            <p>Dashboard Admin</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.matriculas.index') }}"
                           class="nav-link {{ request()->routeIs('admin.matriculas.*') ? 'bg-[#FF7F00] text-white shadow-md' : 'text-gray-300 hover:bg-white/10' }} rounded-lg px-4 py-2.5 flex items-center transition-all duration-200">
                            <i class="nav-icon bi bi-card-checklist mr-3 text-lg"></i>
                            <p>Gestión Matrículas</p>
                        </a>
                    </li>

                    {{-- Placeholder para Usuarios (Sin ruta aún) --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link text-gray-300 hover:bg-white/10 rounded-lg px-4 py-2.5 flex items-center transition-all duration-200">
                            <i class="nav-icon bi bi-people-fill mr-3 text-lg"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                @endif

                {{-- ======================================================= --}}
                {{-- MENÚ DE ESTUDIANTE --}}
                {{-- ======================================================= --}}
                @if (Auth::user()->rol == 'Estudiante')
                    <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-4 mt-2">
                        Zona Estudiante
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('estudiante.dashboard') }}"
                           class="nav-link {{ request()->routeIs('estudiante.dashboard') ? 'bg-[#FF7F00] text-white shadow-md' : 'text-gray-300 hover:bg-white/10' }} rounded-lg px-4 py-2.5 flex items-center transition-all duration-200">
                            <i class="nav-icon bi bi-person-workspace mr-3 text-lg"></i>
                            <p>Mi Panel</p>
                        </a>
                    </li>

                    {{-- MENÚ DESPLEGABLE DE TRÁMITES --}}
                    {{-- Lógica: Si la URL contiene 'matricula', añadimos 'menu-open' Y 'bg-white/5' al padre --}}
                    <li class="nav-item {{ request()->is('estudiante/matricula*') ? 'menu-open' : '' }}">
                        
                        <a href="#" class="nav-link {{ request()->is('estudiante/matricula*') ? 'active bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5' }} rounded-lg px-4 py-2.5 flex items-center justify-between transition-all duration-200" data-lte-toggle="treeview">
                            <div class="flex items-center">
                                <i class="nav-icon bi bi-journal-text mr-3 text-lg"></i>
                                <p>Trámites de Matrícula</p>
                            </div>
                            <i class="nav-arrow bi bi-chevron-right text-xs transition-transform duration-300 {{ request()->is('estudiante/matricula*') ? 'rotate-90' : '' }}"></i>
                        </a>

                        {{-- SUB-MENÚ: Usamos 'block' si está activo para forzar que se vea --}}
                        <ul class="nav nav-treeview pl-3 mt-1 space-y-1 {{ request()->is('estudiante/matricula*') ? 'block' : 'hidden' }}">
                            
                            {{-- 1. Matrícula Regular --}}
                            <li class="nav-item">
                                <a href="{{ route('matricula.regular.create') }}" 
                                   class="nav-link {{ request()->routeIs('matricula.regular.*') ? 'text-[#FF7F00] font-bold bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg px-4 py-2 flex items-center">
                                    <i class="nav-icon bi bi-circle{{ request()->routeIs('matricula.regular.*') ? '-fill' : '' }} text-[10px] mr-3"></i>
                                    <p class="text-sm">Matrícula Regular</p>
                                </a>
                            </li>

                            {{-- 2. Reserva --}}
                            <li class="nav-item">
                                <a href="{{ route('estudiante.matricula.reserva') }}" 
                                   class="nav-link {{ request()->routeIs('estudiante.matricula.reserva') ? 'text-[#FF7F00] font-bold bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg px-4 py-2 flex items-center">
                                    <i class="nav-icon bi bi-circle text-[10px] mr-3"></i>
                                    <p class="text-sm">Reservar Matrícula</p>
                                </a>
                            </li>

                            {{-- 3. Ampliación --}}
                            <li class="nav-item">
                                <a href="{{ route('estudiante.matricula.ampliacion') }}" class="nav-link text-gray-400 hover:text-white hover:bg-white/5 rounded-lg px-4 py-2 flex items-center">
                                    <i class="nav-icon bi bi-circle text-[10px] mr-3"></i>
                                    <p class="text-sm">Ampliar Créditos</p>
                                </a>
                            </li>

                            {{-- 4. Reactualización --}}
                            <li class="nav-item">
                                <a href="{{ route('estudiante.matricula.reactualizacion') }}" class="nav-link text-gray-400 hover:text-white hover:bg-white/5 rounded-lg px-4 py-2 flex items-center">
                                    <i class="nav-icon bi bi-circle text-[10px] mr-3"></i>
                                    <p class="text-sm">Reactualizar</p>
                                </a>
                            </li>

                            {{-- 5. Rectificación --}}
                            <li class="nav-item">
                                <a href="{{ route('estudiante.matricula.rectificacion') }}" class="nav-link text-gray-400 hover:text-white hover:bg-white/5 rounded-lg px-4 py-2 flex items-center">
                                    <i class="nav-icon bi bi-circle text-[10px] mr-3"></i>
                                    <p class="text-sm">Rectificar Matrícula</p>
                                </a>
                            </li>

                             {{-- 6. Retiro --}}
                             <li class="nav-item">
                                <a href="{{ route('estudiante.matricula.retiro') }}" class="nav-link text-gray-400 hover:text-white hover:bg-white/5 rounded-lg px-4 py-2 flex items-center">
                                    <i class="nav-icon bi bi-circle text-[10px] mr-3"></i>
                                    <p class="text-sm">Retiro de Matrícula</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- OTROS --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link text-gray-300 hover:bg-white/10 rounded-lg px-4 py-2.5 flex items-center transition-all duration-200">
                            <i class="nav-icon bi bi-exclamation-triangle mr-3 text-lg"></i>
                            <p>Suspensión</p>
                        </a>
                    </li>
                @endif

                {{-- ======================================================= --}}
                {{-- MENÚ DE PROFESOR --}}
                {{-- ======================================================= --}}
                @if (Auth::user()->rol == 'Profesor')
                    <li class="nav-header text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-4 mt-2">
                        Zona Docente
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('profesor.dashboard') }}"
                           class="nav-link {{ request()->routeIs('profesor.dashboard') ? 'bg-[#FF7F00] text-white shadow-md' : 'text-gray-300 hover:bg-white/10' }} rounded-lg px-4 py-2.5 flex items-center transition-all duration-200">
                            <i class="nav-icon bi bi-easel2-fill mr-3 text-lg"></i>
                            <p>Panel Docente</p>
                        </a>
                    </li>
                @endif

                {{-- LOGOUT --}}
                <li class="nav-item mt-8 border-t border-gray-700 pt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="nav-link text-red-400 hover:bg-red-500/10 hover:text-red-300 w-full text-left rounded-lg px-4 py-2.5 flex items-center transition-all duration-200">
                            <i class="nav-icon bi bi-box-arrow-left mr-3 text-lg"></i>
                            <p>Cerrar Sesión</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>