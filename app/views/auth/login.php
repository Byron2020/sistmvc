<div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
  <div class="card shadow p-4" style="width:350px">
    <h4 class="text-center mb-3">Iniciar sesión</h4>

    <form method="POST" action="index.php?action=login">
      <!-- Usuario -->
      <div class="mb-3">
        <label>Usuario</label>
        <input type="text" name="usuario" class="form-control">
      </div>

      <!-- Contraseña con botón de ojo -->
      <div class="mb-3 position-relative">
        <label>Contraseña</label>
        <div class="input-group">
          <input type="password" id="password" name="password" class="form-control">
          <button type="button" class="btn btn-outline-secondary" id="togglePassword">
            <i class="bi bi-eye-fill"></i>
          </button>
        </div>
      </div>

      <button class="btn btn-dark w-100">Ingresar</button>
    </form>
  </div>
</div>

<!-- Script para mostrar/ocultar contraseña -->
<script>
  const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#password');

  togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    // Cambiar ícono
    togglePassword.innerHTML = type === 'password' 
      ? '<i class="bi bi-eye-fill"></i>' 
      : '<i class="bi bi-eye-slash-fill"></i>';
  });
</script>
