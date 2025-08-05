<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <p style="font-size: 18px;"><strong>¡Enhorabuena!</strong><br>Tu perfil en Hotspania ha sido creado</p>
    
    <p>Ya falta poco para poder activarte en el sitio</p>
    
    <p>Ingresa a <strong>MI CUENTA</strong>, donde tendrás control total sobre tus datos y ficha, y desde donde podrás usar las herramientas y opciones para más visibilidad</p>
    
    <p>
        <strong>Correo:</strong> {{ $correo }}<br>
        <strong>Contraseña:</strong> {{ $contrasena }}
    </p>

    <p style="margin-top: 25px; text-align: center;">
        <table border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
            <tr>
                <td style="
                    background: #f36e00;
                    border-radius: 50px;
                    padding: 0;
                    box-shadow: 0 6px 20px rgba(243, 110, 0, 0.4);
                ">
                    <a href="{{ $loginUrl }}" target="_blank" style="
                        display: inline-block;
                        padding: 16px 40px;
                        color: white;
                        text-decoration: none;
                        font-size: 18px;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        font-family: Arial, sans-serif;
                        border-radius: 50px;
                    ">
                        <span style="font-size: 16px; margin-right: 8px;">🚀</span>
                        ACCESO
                    </a>
                </td>
            </tr>
        </table>
    </p>
    
</div>

<style>
    .email-button:hover {
        background: linear-gradient(45deg, #ff8c42, #f36e00) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(243, 110, 0, 0.6) !important;
    }
</style>