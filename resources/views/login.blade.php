<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>AdminLTE 4 | Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}" />
</head>

<body class="login-page bg-light">
    <div class="login-box">
        <div class="login-logo">
            <a href="index.php"><b class="text-danger">SISGU - FIEI</b></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <form method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required />
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required />
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </form>
                <p class="mb-1"><a href="#">¿Olvidaste tu contraseña?</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="views/resources/dist/js/adminlte.js"></script>
</body>

</html>
