<?php
require("Header_YM.php");
require("RF_Artista_YM.php");
require_once("conexion.php");
require_once("RF_Álbumes_YM.php");

$email = $_SESSION["email"];
?>

<nav class="navbar navbar-expand-md nav_index_ym">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img class="imagen_perfil_view" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil" style="width: 50px; height: 50px;">
        <span class="ml-2" style="color: white; padding-left:5px;"><?php echo htmlspecialchars($nombre); ?></span>
    </a>
    <i class="bi bi-music-note"></i>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <a href="Busqueda.php"><i class="bi bi-search"></i></a>
        </ul>
    </div>
</nav>
<div class="container-fluid alb-ym">
    <h1 class="lanza text-center my-4">LANZAMIENTOS</h1>
    <hr class="bg-custom-loginu my-4 barra_loginu">
    <div class="fond-albu p-3">
        <form action="Subida_Album.php">
            <button type="submit" class="btn btn-secondary w-100 mb-4">
                <h3 class="text-center m-0">Nuevo</h3>
                
            </button><hr class="bg-custom-loginu my-4 barra_loginu" style="color:antiquewhite;">
        </form>

        <div class="album-container">

            <?php
            $albumes = obtenerAlbumes($email);
            if (empty($albumes)) {
                echo '<div class="text-center text-white">No hay álbumes para mostrar</div>';
            } else {
                foreach ($albumes as $album) {
            ?>
                    <a href="VerAlbum.php?id=<?php echo htmlspecialchars($album['IdAlbum']); ?>" class="album-link">
                        <div class="album-card">
                            <img src="<?php echo htmlspecialchars($album['ImgAlbu']); ?>"
                                alt="<?php echo htmlspecialchars($album['NomAlbum']); ?>">
                            <div class="album-info">
                                <h4><?php echo htmlspecialchars($album['NomAlbum']); ?></h4>
                                <p><?php echo htmlspecialchars($album['Categoria']); ?></p>
                                <p><?php echo date('d/m/Y', strtotime($album['FechaLan'])); ?></p>
                            </div>
                        </div>
                    </a>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<?php require("Footer_YM.php"); ?>