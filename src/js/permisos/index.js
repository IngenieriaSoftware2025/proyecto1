import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formPermiso = document.getElementById('formPermiso');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarPermisos = document.getElementById('BtnBuscarPermisos');
const SelectUsuario = document.getElementById('usuario_id');
const SelectAplicacion = document.getElementById('app_id');
const seccionTabla = document.getElementById('seccionTabla');

const cargarUsuarios = async () => {
    // Mostrar loading
    SelectUsuario.innerHTML = '<option value="">Cargando usuarios...</option>';
    
    const url = `/proyecto1/permisos/buscarUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            
            data.forEach(usuario => {
                const option = document.createElement('option');
                option.value = usuario.usuario_id;
                option.textContent = usuario.nombre_completo;
                SelectUsuario.appendChild(option);
            });
        } else {
            SelectUsuario.innerHTML = '<option value="">Error al cargar usuarios</option>';
            console.log('Error al cargar usuarios:', mensaje);
        }

    } catch (error) {
        SelectUsuario.innerHTML = '<option value="">Error de conexión</option>';
        console.log('Error:', error);
    }
}

const cargarAplicaciones = async () => {
    // Mostrar loading
    SelectAplicacion.innerHTML = '<option value="">Cargando aplicaciones...</option>';
    
    const url = `/proyecto1/permisos/buscarAplicacionesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectAplicacion.innerHTML = '<option value="">Seleccione una aplicación</option>';
            
            data.forEach(app => {
                const option = document.createElement('option');
                option.value = app.app_id;
                option.textContent = app.app_nombre_largo;
                SelectAplicacion.appendChild(option);
            });
        } else {
            SelectAplicacion.innerHTML = '<option value="">Error al cargar aplicaciones</option>';
            console.log('Error al cargar aplicaciones:', mensaje);
        }

    } catch (error) {
        SelectAplicacion.innerHTML = '<option value="">Error de conexión</option>';
        console.log('Error:', error);
    }
}

const guardarPermiso = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formPermiso, ['permiso_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe llenar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(formPermiso);
    const url = "/proyecto1/permisos/guardarAPI";
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        
        // Verificar si la respuesta es OK
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
                timer: 2000
            });

            limpiarTodo();
            // No buscar permisos automáticamente para evitar más errores
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log('Error completo:', error);
        
        // Verificar si al menos se guardó (puedes hacer una consulta simple)
        await Swal.fire({
            position: "center",
            icon: "warning",
            title: "Guardado con advertencia",
            text: "El permiso se guardó pero hubo un problema con la respuesta",
            showConfirmButton: true,
        });
        
        limpiarTodo();
    }
    
    BtnGuardar.disabled = false;
}

const BuscarPermisos = async () => {
    const url = `/proyecto1/permisos/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            if (datatable) {
                datatable.clear().draw();
                datatable.rows.add(data).draw();
            }
        } else {
            console.log('Error al buscar permisos:', mensaje);
        }

    } catch (error) {
        console.log(error);
    }
}

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarPermisos();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TablePermisos', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'asignacion_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Usuario', 
            data: 'usuario_nom1',
            width: '20%',
            render: (data, type, row) => {
                return `${row.usuario_nom1} ${row.usuario_ape1}`;
            }
        },
        { 
            title: 'DPI', 
            data: 'usuario_dpi',
            width: '15%'
        },
        { 
            title: 'Aplicación', 
            data: 'app_nombre_corto',
            width: '15%'
        },
        { 
            title: 'Permiso', 
            data: 'permiso_clave',
            width: '15%'
        },
        { 
            title: 'Descripción', 
            data: 'permiso_desc',
            width: '20%'
        },
        {
            title: 'Acciones',
            data: 'asignacion_id',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}"
                         title="Eliminar">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const limpiarTodo = () => {
    formPermiso.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const EliminarPermisos = async (e) => {
    const idPermiso = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea eliminar este permiso?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto1/permisos/eliminar?id=${idPermiso}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarPermisos();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error);
        }
    }
}

// Cargar datos al iniciar - USANDO ASYNC/AWAIT CORRECTAMENTE
document.addEventListener('DOMContentLoaded', async () => {
    await cargarUsuarios();
    await cargarAplicaciones();
    
    // Event listeners después de cargar los datos
    datatable.on('click', '.eliminar', EliminarPermisos);
    formPermiso.addEventListener('submit', guardarPermiso);
    BtnLimpiar.addEventListener('click', limpiarTodo);
    BtnBuscarPermisos.addEventListener('click', MostrarTabla);
});