<div class="row mb-3">
    <div class="col text-center">
        <h1>¡Bienvenido al Sistema!</h1>
        <p>Has iniciado sesión correctamente.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Panel de Control</h5>
                <p class="card-text">Desde aquí puedes acceder a todas las funcionalidades del sistema.</p>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="/proyecto1/usuarios" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-people-fill"></i><br>
                            Usuarios
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/proyecto1/aplicacion" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-grid-fill"></i><br>
                            Aplicaciones
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/proyecto1/permisos" class="btn btn-warning btn-lg w-100">
                            <i class="bi bi-shield-lock-fill"></i><br>
                            Permisos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-lg-4">
        <img src="<?= asset('images/cit.png') ?>" width="100%" alt="Logo" class="img-fluid">
    </div>
</div>

<script src="<?= asset('build/js/inicio.js') ?>"></script>