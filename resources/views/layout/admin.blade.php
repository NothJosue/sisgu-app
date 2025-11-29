<!doctype html>
<html lang="es">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SISGU - FIEI UNFV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Colores Institucionales en Meta Tags -->
    <meta name="theme-color" content="#FF7F00" />

    <!-- Fuentes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    
    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    
    <!-- Estilos AdminLTE Base -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}" />
    
    <!-- Overlays Scrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />

    <!-- CONEXIÓN VITE (TAILWIND CSS) -->
    <!-- Esto cargará tus estilos personalizados de app.css -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <!--end::Head-->
  
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-gray-50">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      
      <!--begin::Header-->
      @include('layout.header')
      <!--end::Header-->
      
      <!--begin::Sidebar-->
      @include('layout.sidebar')
      <!--end::Sidebar-->
      
      <!--begin::App Main-->
      <main class="app-main">
        <!-- AQUÍ SE INYECTA EL CONTENIDO DINÁMICO -->
        <!-- Las vistas dashboard.blade.php o matricula/index.blade.php se mostrarán aquí -->
        @yield('content')
      </main>
      <!--end::App Main-->
      
      <!--begin::Footer-->
      @include('layout.footer')
      <!--end::Footer-->
      
    </div>
    <!--end::App Wrapper-->
    
    <!--begin::Script-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    
    <!-- Configuración del Scrollbar -->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    @yield('scripts')
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>