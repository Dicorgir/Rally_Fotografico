/**
 * Añade un listener al formulario de login.
 * Envía los datos al backend y gestiona la autenticación del usuario.
 * @async
 * @param {Event} e - El evento submit del formulario.
 */
document.querySelector('.form-container form')?.addEventListener('submit', async function(e) {
    e.preventDefault(); // Evita el envío tradicional del formulario

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        // Envía los datos al backend usando fetch y espera la respuesta
        const response = await fetch('http://localhost/Rally_Fotografico/Backend/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email,
                password
            })
        });

        const data = await response.json();
        // Si el login es correcto, guarda datos en localStorage y redirige
        if (response.ok && data.usuario && data.usuario.nombre_completo) {
            localStorage.setItem('usuarioRol', data.usuario.rol);
            localStorage.setItem('usuarioNombre', data.usuario.nombre_completo);
            localStorage.setItem('usuarioEmail', email);
            alert('Bienvenido, ' + data.usuario.nombre_completo);
            window.location.href = "index.html";
        } else {
            // Si hay error, muestra mensaje
            alert('Error: ' + (data.message || 'Error desconocido'));
        }
    } catch (err) {
        alert('Error de conexión');
    }
});