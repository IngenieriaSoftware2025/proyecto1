<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">ASIGNACIÓN DE PERMISOS</h3>
                    </div>
                    <form id="formPermiso" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="permiso_id" name="permiso_id">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_id" class="form-label">Usuario</label>
                                <select class="form-control form-control-lg" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccione un usuario</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="app_id" class="form-label">Aplicación</label>
                                <select class="form-control form-control-lg" id="app_id" name="app_id" required>
                                    <option value="">Seleccione una aplicación</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="permiso_clave" class="form-label">Clave del Permiso</label>
                                <input type="text" class="form-control form-control-lg" id="permiso_clave" name="permiso_clave" placeholder="Ej: USUARIO_CREAR" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_permiso" class="form-label">Tipo de Permiso</label>
                                <select class="form-control form-control-lg" id="tipo_permiso" name="tipo_permiso" required>
                                    <option value="FUNCIONAL">FUNCIONAL</option>
                                    <option value="ADMIN">ADMIN</option>
                                    <option value="LECTURA">LECTURA</option>
                                    <option value="ESCRITURA">ESCRITURA</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="permiso_desc" class="form-label">Descripción</label>
                                <input type="text" class="form-control form-control-lg" id="permiso_desc" name="permiso_desc" placeholder="Descripción del permiso" required>
                            </div>
                            <div class="col-md-6">
                                <label for="motivo" class="form-label">Motivo</label>
                                <input type="text" class="form-control form-control-lg" id="motivo" name="motivo" placeholder="Motivo de la asignación" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarPermisos">
                                <i class="bi bi-search me-2"></i>Buscar Permisos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Permisos asignados en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TablePermisos" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Usuario</th>
                                    <th>DPI</th>
                                    <th>Aplicación</th>
                                    <th>Permiso</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/permisos/index.js') ?>"></script>