console.log("register.js cargado");

/**
 * Espera a que el DOM esté listo y añade el listener al formulario de registro.
 * @event DOMContentLoaded
 */
document.addEventListener('DOMContentLoaded', function() {
  /**
   * Maneja el envío del formulario de registro de usuario.
   * Envía los datos al backend y muestra alertas según el resultado.
   * @async
   * @param {Event} e - El evento submit del formulario.
   */
  document.querySelector('.form-container form')?.addEventListener('submit', async function(e) {
    e.preventDefault(); // Evita el envío tradicional

    // Obtiene los valores de los campos del formulario
    const nombre_completo = document.getElementById('nombre_completo').value;
    const email = document.getElementById('email').value;
    const telefono = document.getElementById('telefono').value;
    const fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    const pais = document.getElementById('pais').value;
    const genero = document.getElementById('genero').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('confirm-password').value;

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