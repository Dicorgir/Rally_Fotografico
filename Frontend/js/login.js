document.querySelector('.form-container form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
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
        if (response.ok && data.usuario && data.usuario.nombre_completo) {
            // Guardar el rol y nombre en localStorage
            localStorage.setItem('usuarioRol', data.usuario.rol);
            localStorage.setItem('usuarioNombre', data.usuario.nombre_completo);
            localStorage.setItem('usuarioEmail', email);
            alert('Bienvenido, ' + data.usuario.nombre_completo);
            window.location.href = "index.html";
        } else {
            alert('Error: ' + (data.message || 'Error desconocido'));
        }
    } catch (err) {
        alert('Error de conexión');
    }
});