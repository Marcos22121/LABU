<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labu - Login y Registro</title>
    <!-- Incluir la fuente Inter -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="Estilos/login.css">
</head>
<body>

    <div class="container">
        <img src="Img/labu.png" alt="Logo de Labu" class="logo">

        <div class="form-toggle">
            <button id="tab-login" class="tab-button active">Iniciar Sesión</button>
            <button id="tab-register" class="tab-button">Registrarse</button>
        </div>

        <!-- Formulario de Login -->
        <div id="form-login" class="form-content active">
            <h2>Iniciar Sesión</h2>
            <form action="Modelos/login.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="input-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="login-password">Contraseña</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>

        <!-- Formulario de Registro -->
        <div id="form-register" class="form-content">
            <h2>Crear Cuenta</h2>
            <form action="Modelos/login.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="input-group">
                    <label for="register-nombre">Nombre</label>
                    <input type="text" id="register-nombre" name="nombre" required>
                </div>
                <div class="input-group">
                    <label for="register-apellido">Apellido</label>
                    <input type="text" id="register-apellido" name="apellido" required>
                </div>
                <div class="input-group">
                    <label for="register-dni">DNI</label>
                    <input type="text" id="register-dni" name="dni" required>
                </div>
                <div class="input-group">
                    <label for="register-fecha-nacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="register-fecha-nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="input-group">
                    <label for="register-genero">Género</label>
                    <select id="register-genero" name="genero" required>
                        <option value="">Selecciona tu género</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="register-telefono">Teléfono</label>
                    <input type="tel" id="register-telefono" name="telefono">
                </div>
                <div class="input-group">
                    <label for="register-email">Email</label>
                    <input type="email" id="register-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="register-password">Contraseña</label>
                    <input type="password" id="register-password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="register-provincia">Provincia</label>
                    <select id="register-provincia" name="id_provincia" required>
                        <option value="">Selecciona tu provincia</option>
                        <!-- Opciones de ejemplo para un archivo estático -->
                        <option value="1">Buenos Aires</option>
                        <option value="2">Córdoba</option>
                        <option value="3">Santa Fe</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="register-localidad">Localidad</label>
                    <select id="register-localidad" name="id_localidad" required disabled>
                        <option value="">Selecciona tu localidad</option>
                    </select>
                </div>
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>

    <!-- Script para manejar la interfaz y la carga dinámica -->
    <script>
        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');
        const formLogin = document.getElementById('form-login');
        const formRegister = document.getElementById('form-register');
        const provinciaSelect = document.getElementById('register-provincia');
        const localidadSelect = document.getElementById('register-localidad');

        const toggleForms = (formToShow) => {
            if (formToShow === 'login') {
                formLogin.classList.add('active');
                formRegister.classList.remove('active');
                tabLogin.classList.add('active');
                tabRegister.classList.remove('active');
            } else {
                formLogin.classList.remove('active');
                formRegister.classList.add('active');
                tabLogin.classList.remove('active');
                tabRegister.classList.add('active');
            }
        };

        tabLogin.addEventListener('click', () => toggleForms('login'));
        tabRegister.addEventListener('click', () => toggleForms('register'));

        provinciaSelect.addEventListener('change', async () => {
            const provinciaId = provinciaSelect.value;
            localidadSelect.innerHTML = '<option value="">Cargando localidades...</option>';
            localidadSelect.disabled = true;

            if (provinciaId) {
                try {
                    const response = await fetch(`Modelos/get_localidades.php?id_provincia=${provinciaId}`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const localidades = await response.json();
                    
                    localidadSelect.innerHTML = '<option value="">Selecciona tu localidad</option>';
                    localidades.forEach(localidad => {
                        const option = document.createElement('option');
                        option.value = localidad.id_localidad;
                        option.textContent = localidad.nombre_localidad;
                        localidadSelect.appendChild(option);
                    });
                    localidadSelect.disabled = false;
                } catch (error) {
                    console.error("Error al cargar las localidades:", error);
                    localidadSelect.innerHTML = '<option value="">Error al cargar</option>';
                }
            } else {
                localidadSelect.innerHTML = '<option value="">Selecciona tu localidad</option>';
                localidadSelect.disabled = true;
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            toggleForms('login');
        });
    </script>
</body>
</html>
