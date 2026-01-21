    </div> <!-- col -->
    </div> <!-- row -->
    </div> <!-- container -->

    <footer class="bg-dark text-center text-light py-2">
      <small>©2026 Todos los derechos reservados | Guaranda - Ecuador</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    

    </body>
    <?php if (!empty($_SESSION['swal'])): ?> 
      <script>
        Swal.fire({
          icon:'<?= $_SESSION['swal']['icon'] ?>',
          title:'<?= $_SESSION['swal']['title'] ?>',
          text:'<?= $_SESSION['swal']['text'] ?>',
          timer:'<?= $_SESSION['swal']['timer'] ?>',
          showConfirmButton:true
        });
        </script>
      <?php unset($_SESSION['swal']); ?>
    <?php endif; ?>
    </html>