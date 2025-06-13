import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';

const FormLogin = document.getElementById('FormLogin');
const BtnIniciar = document.getElementById('BtnIniciar');

const login = async (e) => {
    e.preventDefault();
    BtnIniciar.disabled = true;

    if (!validarFormulario(FormLogin, [''])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnIniciar.disabled = false;
        return;
    }

    try {
        const body = new FormData(FormLogin);
        const url = '/proyecto1/API/login';  // ← URL CORREGIDA
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje } = data;

        if (codigo == 1) {
            await Swal.fire({
                title: 'Éxito',
                text: mensaje,
                icon: 'success',
                showConfirmButton: true,
                timer: 1500
            });

            FormLogin.reset();
            location.href = '/proyecto1/inicio';
        } else {
            Swal.fire({
                title: 'Error',
                text: mensaje,
                icon: 'warning',
                showConfirmButton: true,
                timer: 1500
            });
        }

    } catch (error) {
        console.log(error);
    }

    BtnIniciar.disabled = false;
}

FormLogin.addEventListener('submit', login);