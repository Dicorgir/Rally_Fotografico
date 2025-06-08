console.log("register.js cargado");

/**
 * Espera a que el DOM esté listo y añade el listener al formulario de registro.
 * @event DOMContentLoaded
 */
document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('.form-container form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Obtiene los valores de los campos del formulario
    const nombre_completo = document.getElementById('nombre_completo').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const fecha_nacimiento = document.getElementById('fecha_nacimiento').value.trim();
    const pais = document.getElementById('pais').value.trim();
    const genero = document.getElementById('genero').value.trim();
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('confirm-password').value;

    // Validaciones frontend
    if (!nombre_completo || !email || !password || !password_confirmation) {
      alert('Todos los campos obligatorios deben completarse');
      return;
    }
    // Email válido
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('El correo no es válido');
      return;
    }
    // País máximo 50 caracteres
    if (pais && pais.length > 50) {
      alert('El país no puede tener más de 50 caracteres');
      return;
    }
    // Validación de contraseña fuerte
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordRegex.test(password)) {
      alert('La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, un número y un carácter especial.');
      return;
    }
    if (password !== password_confirmation) {
      alert('Las contraseñas no coinciden.');
      return;
    }

    try {
      // Envía los datos al backend usando fetch
      const response = await fetch('http://localhost/Rally_Fotografico/Backend/register.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nombre_completo,
          email,
          telefono,
          fecha_nacimiento,
          pais,
          genero,
          password,
          password_confirmation
        })
      });

      const data = await response.json();
      if (response.ok) {
        alert('Registro exitoso');
        e.target.reset(); // Limpia el formulario
      } else {
        alert('Error: ' + (data.message || JSON.stringify(data.errors)));
      }
    } catch (err) {
      alert('Error de conexión');
    }
  });
});