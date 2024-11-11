<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="JS_YM/Script_YM.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    const LIMITE_CANCIONES = <?php echo $limite_canciones; ?>;
    const ALBUM_ID = <?php echo $album_id; ?>;
    const redes = <?php echo json_encode(($redes = [$usuario['Instagram'], $usuario['Youtube'], $usuario['TikTok'], $usuario['Spotify']])); ?>;
</script>
</body>

<?php require_once("Funciones.php");
$showFooter = shouldShowFooter();?>
<?php if ($showFooter): ?>
<footer class="footer">
  <div class="footer-content">
    <div class="footer-section">
      <a href="Home_YM.php" class="footer-icon icon-home">
        <i class="fas fa-home"></i>
      </a>
      <p class="footer-label">Inicio</p>
    </div>
    <div class="footer-section">
      <a href="Recien_llegados.php" class="footer-icon icon-clock">
        <i class="fas fa-clock"></i>
      </a>
      <p class="footer-label">Recién llegados</p>
    </div>
    <div class="footer-section">
      <a href="Populares.php" class="footer-icon icon-fire">
        <i class="fas fa-fire"></i>
      </a>
      <p class="footer-label">Populares</p>
    </div>
    <div class="footer-section">
      <a href="MeGusta.php" class="footer-icon icon-heart">
        <i class="fas fa-heart"></i>
      </a>
      <p class="footer-label">Tus me gustas</p>
    </div>
    <div class="footer-section">
      <a href="Artistas_favoritos.php" class="footer-icon icon-person">
        <i class="bi bi-person-heart"></i>
      </a>
      <p class="footer-label">Artistas Favoritos</p>
    </div>
  </div>
</footer>
<?php endif; ?>
</html>