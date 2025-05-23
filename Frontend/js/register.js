console.log("register.js cargado");

document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('.form-container form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const nombre_completo = document.getElementById('nombre_completo').value;
    const email = document.getElementById('email').value;
    const telefono = document.getElementById('telefono').value;
    const fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    const pais = document.getElementById('pais').value;
    const genero = document.getElementById('genero').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('confirm-password').value;

    try {
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
        e.target.reset();
      } else {
        alert('Error: ' + (data.message || JSON.stringify(data.errors)));
      }
    } catch (err) {
      alert('Error de conexión');
    }
  });
});