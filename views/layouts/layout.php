<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>DemoApp</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <a class="navbar-brand" href="/<?= $_ENV['APP_NAME'] ?>/inicio">
            <img src="<?= asset('./images/cit.png') ?>" width="35px" alt="cit">
            Aplicaciones
        </a>
        
        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                
                <?php if (isset($_SESSION['ADMIN'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/<?= $_ENV['APP_NAME'] ?>/inicio">
                        <i class="bi bi-house-fill me-2"></i>Inicio
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link px-3" href="/<?= $_ENV['APP_NAME'] ?>/usuarios">
                        <i class="bi bi-people-fill me-2"></i>Usuarios
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3" href="/<?= $_ENV['APP_NAME'] ?>/aplicacion">
                        <i class="bi bi-grid-fill me-2"></i>Aplicaciones
                    </a>
                </li>

                <?php if (isset($_SESSION['ADMIN'])): ?>
                <li class="nav-item">
                    <a class="nav-link px-3" href="/<?= $_ENV['APP_NAME'] ?>/permisos">
                        <i class="bi bi-shield-lock-fill me-2"></i>Permisos
                    </a>
                </li>
                <?php endif; ?>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-2"></i>Dropdown
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" style="margin: 0;">
                        <li>
                            <a class="dropdown-item nav-link text-white" href="#">
                                <i class="ms-lg-0 ms-2 bi bi-plus-circle me-2"></i>Subitem
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
            
            <?php if (isset($_SESSION['nombre'])): ?>
            <div class="d-flex align-items-center text-white me-3">
                <span>Bienvenido: <?= $_SESSION['nombre'] ?></span>
            </div>
            <?php endif; ?>
            
            <div class="col-lg-1 d-grid mb-lg-0 mb-2">
                <a href="/<?= $_ENV['APP_NAME'] ?>/logout" class="btn btn-danger">
                    <i class="bi bi-arrow-bar-left"></i>SALIR
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="progress fixed-bottom" style="height: 6px;">
    <div class="progress-bar progress-bar-animated bg-danger" id="bar"></div>
</div>

<div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
    <?php echo $contenido; ?>
</div>

<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="col-12">
            <p style="font-size:xx-small; font-weight: bold;">
                Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
            </p>
        </div>
    </div>
</div>
</body>
</html>