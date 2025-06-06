<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <section class="text-center">
        <div class="p-5 bg-image" style="background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg'); height: 300px;"></div>
        
        <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="margin-top: -100px; backdrop-filter: blur(30px);">
            <div class="card-body py-5 px-md-5">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-5">Registro de Usuario</h2>
                        <form action="register_user.php" method="POST" enctype="multipart/form-data">
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="usuario_nom1" name="usuario_nom1" class="form-control" required />
                                        <label class="form-label" for="usuario_nom1">Primer Nombre</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="usuario_nom2" name="usuario_nom2" class="form-control" required />
                                        <label class="form-label" for="usuario_nom2">Segundo Nombre</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="usuario_ape1" name="usuario_ape1" class="form-control" required />
                                        <label class="form-label" for="usuario_ape1">Primer Apellido</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="usuario_ape2" name="usuario_ape2" class="form-control" required />
                                        <label class="form-label" for="usuario_ape2">Segundo Apellido</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="usuario_dpi" name="usuario_dpi" class="form-control" required />
                                        <label class="form-label" for="usuario_dpi">DPI</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="usuario_tel" name="usuario_tel" class="form-control" required />
                                        <label class="form-label" for="usuario_tel">Teléfono</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <textarea id="usuario_direc" name="usuario_direc" class="form-control" rows="3" required></textarea>
                                <label class="form-label" for="usuario_direc">Dirección</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="email" id="usuario_correo" name="usuario_correo" class="form-control" required />
                                <label class="form-label" for="usuario_correo">Correo Electrónico</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="usuario_contra" name="usuario_contra" class="form-control" required />
                                <label class="form-label" for="usuario_contra">Contraseña</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="confirmar_contra" name="confirmar_contra" class="form-control" required />
                                <label class="form-label" for="confirmar_contra">Confirmar Contraseña</label>
                            </div>

                            <div class="mb-4">
                                <label for="usuario_fotografia" class="form-label">Fotografía</label>
                                <input type="file" id="usuario_fotografia" name="usuario_fotografia" class="form-control" accept="image/*" />
                            </div>

                            <button type="submit" class="btn btn-primary btn-block mb-4">Registrar Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>