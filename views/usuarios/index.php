<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
</head>
<body>

<section class="h-100 bg-dark">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col">
        <div class="card card-registration my-4">
          <div class="row g-0">
            <div class="col-xl-6 d-none d-xl-block">
              <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/img4.webp"
                alt="Sample photo" class="img-fluid"
                style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
            </div>
            <div class="col-xl-6">
              <div class="card-body p-md-5 text-black">
                <h3 class="mb-5 text-uppercase">Registrar Usuario</h3>

                <form id="formUsuario" name="formUsuario">
                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="text" id="usuario_nom1" name="usuario_nom1" class="form-control form-control-lg" placeholder=" " required />
                        <label class="form-label" for="usuario_nom1">Primer Nombre</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="text" id="usuario_nom2" name="usuario_nom2" class="form-control form-control-lg" placeholder=" " required />
                        <label class="form-label" for="usuario_nom2">Segundo Nombre</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="text" id="usuario_ape1" name="usuario_ape1" class="form-control form-control-lg" placeholder=" " required />
                        <label class="form-label" for="usuario_ape1">Primer Apellido</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="text" id="usuario_ape2" name="usuario_ape2" class="form-control form-control-lg" placeholder=" " required />
                        <label class="form-label" for="usuario_ape2">Segundo Apellido</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="tel" id="usuario_tel" name="usuario_tel" class="form-control form-control-lg" placeholder=" " required />
                        <label class="form-label" for="usuario_tel">Teléfono</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="text" id="usuario_dpi" name="usuario_dpi" class="form-control form-control-lg" placeholder=" " maxlength="13" required />
                        <label class="form-label" for="usuario_dpi">DPI</label>
                      </div>
                    </div>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="usuario_direc" name="usuario_direc" class="form-control form-control-lg" placeholder=" " maxlength="150" required />
                    <label class="form-label" for="usuario_direc">Dirección</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="usuario_correo" name="usuario_correo" class="form-control form-control-lg" placeholder=" " maxlength="100" required />
                    <label class="form-label" for="usuario_correo">Correo Electrónico</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="usuario_contra" name="usuario_contra" class="form-control form-control-lg" placeholder=" " required />
                    <label class="form-label" for="usuario_contra">Contraseña</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="confirmar_contra" name="confirmar_contra" class="form-control form-control-lg" placeholder=" " required />
                    <label class="form-label" for="confirmar_contra">Confirmar Contraseña</label>
                  </div>

                  <div class="mb-4">
                    <label for="usuario_fotografia" class="form-label">Fotografía (Opcional)</label>
                    <input type="file" id="usuario_fotografia" name="usuario_fotografia" class="form-control form-control-lg" accept="image/*" />
                  </div>

                  <div class="form-check d-flex justify-content-center mb-5">
                    <input class="form-check-input me-2" type="checkbox" value="" id="terminos_condiciones" required />
                    <label class="form-check-label" for="terminos_condiciones">
                      Acepto todos los términos y condiciones del <a href="#!" class="text-body"><u>Servicio</u></a>
                    </label>
                  </div>

                  <div class="d-flex justify-content-end pt-3">
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-success btn-lg">Registrar Usuario</button>
                  </div>

                  <p class="text-center text-muted mt-5 mb-0">
                    ¿Ya tienes una cuenta? 
                    <a href="#!" class="fw-bold text-body"><u>Inicia sesión aquí</u></a>
                  </p>

                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= asset('./build/js/registro/index.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>