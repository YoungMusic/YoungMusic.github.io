<?php 
require("Header_YM.php");
require_once("RF_Datos_Busqueda_YM.php");
$paginaPerfil = determinarTipoUsuario($email);
require("RF_Ver_Artista_YM.php");
require_once("Funciones.php");
?>

<nav class="navbar navbar-expand-md nav_index_ym">
    <a class="navbar-brand d-flex align-items-center" href="<?php echo htmlspecialchars($paginaPerfil); ?>">
        <img class="imagen_perfil_view" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil" style="width: 50px; height: 50px;">
        <span class="ml-2" style="color: white; padding-left:5px;"><?php echo htmlspecialchars($nombre); ?></span>
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <a href="Busqueda.php"><i class="bi bi-search"></i></a>
        </ul>
    </div>
</nav>
<!-- Contenido del Perfil -->
<div class="container mt-4 md container-perfil">
    <div class="row justify-content-left">
        <div class="botones text-center text-md-left">
            <?php if ($tipoPerfil === 'artista' && $correoArtista!==$correoOyente): ?>
                <div class="btn-group flex-md-row text-center mt-3">
                    <form method="POST" id="formSeguir">
                        <?php if ($sigueAlArtista): ?>
                            <input type="hidden" name="dejarDeSeguir" value="1">
                            <button type="button" class="btn btn-danger" onclick="dejarDeSeguirArtista()">
                                <i class="bi bi-person-dash"></i> Dejar de Seguir
                            </button>
                        <?php else: ?>
                            <input type="hidden" name="seguir" value="1">
                            <button type="button" class="btn btn-primary" onclick="seguirArtista()">
                                <i class="bi bi-person-plus"></i> Seguir
                            </button>
                        <?php endif; ?>
                    </form>
                    <?php endif; ?>
                    <!-- Botón de Eliminar Perfil -->
                    <?php if(isset($_SESSION["email"]) && esAdmin($_SESSION["email"])): ?>
                    <form method="POST" id="formEliminar" class="ml-2" onsubmit="return confirmarEliminacion()">
                        <input type="hidden" name="eliminarPerfil" value="1">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Perfil
                        </button>
                    </form>
                </div>
                <?php endif; ?>
        </div>
        <div class="cont-u">
            <div class="imagen_perfil-container">
                <img class="imagen_perfil" src="<?php echo htmlspecialchars($fotoPerfilA); ?>" alt="Foto de Perfil">
            </div>

            <div class="perfil-content">
                <div class="perfil-details">
                    <h2>| <?php echo htmlspecialchars($nombreA); ?></h2>
                    <p>| <?php echo htmlspecialchars($correoA); ?></p>

                    <?php if ($tipoPerfil === 'artista' || $tipoPerfil === 'discografica'): ?>
                        <div class="social-icons d-flex justify-content-center">
                        <button class="icons"><a id="tiktok" style="display:none;color: white;""><i class="bi bi-tiktok"></i></a><br></button>
                        <button class="icons"><a id="instagram" style="display:none;color: white;""><i class="bi bi-instagram"></i><br></a></button>
                        <button class="icons"><a id="youtube" style="display:none;color: white;""><i class="bi bi-youtube"></i></a><br></button>
                        <button class="icons"><a id="spotify" style="display:none;color: white;"><i class="bi bi-spotify"></i></a><br></button>
                    </div>
                    <?php endif; ?>

                    <?php if ($tipoPerfil === 'artista'): ?>
                        <hr class="bg-custom-loginu my-4 barra_loginu">
                        <h5>| Biografia</h5>
                        <p><?php echo htmlspecialchars($biografiaA); ?></p>
                        <hr class="bg-custom-loginu my-4 barra_loginu">
                        <h3>| Instrumentos</h3>
                        <?php foreach ($usuario['instrumentos'] as $instrumento): ?>
                            <li><?php echo htmlspecialchars($instrumento); ?></li>
                        <?php endforeach; ?>
                       
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div   style="display: none;">
                    <input type="text" class="icons form-control" name="Red1"  id="Red1" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['Instagram']); ?>">
           
                    <input type="text" class="iconsform-control" name="Red2"  id="Red2" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['Youtube']); ?>">
        
                    <input type="text" class="icons form-control" name="Red3"   id="Red3" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['Spotify']); ?>">
                
                    <input type="text" class="icons form-control" name="Red4"  id="Red4" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['TikTok']); ?>">
             
 </div>
</div>


 
<div class="container mt-4 md album-preview container-perfil">

<div class="row perf-rw justify-content-left">
<div class="container-fluid alb-ym">
    <h1 class="lanza text-center my-4">LANZAMIENTOS</h1>
    <hr class="bg-custom-loginu my-4 barra_loginu">
    <div class="fond-albu p-3">

                            <?php if (!empty($albumes)): ?>
                                <?php foreach ($albumes as $album): ?>
                                    <a href="VerAlbum.php?id=<?php echo htmlspecialchars($album['IdAlbum']); ?>" class="album-link-ym">
                                        <div class="album-card">
                                            <img src="<?php echo htmlspecialchars($album['ImgAlbu']); ?>" alt="<?php echo htmlspecialchars($album['NomAlbum']); ?>" class="album-image-ym">
                                            <div class="album-info">
                                                <h4 class="album-title"><?php echo htmlspecialchars($album['NomAlbum']); ?></h4>
                                                <p class="album-category"><?php echo htmlspecialchars($album['Categoria']); ?></p>
                                                <p class="album-date"><?php echo date('d/m/Y', strtotime($album['FechaLan'])); ?></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No hay lanzamientos disponibles.</p>
                            <?php endif; ?>
                        </div>
                        </div>
                        </div>     
</div>
<?php require("Footer_YM.php"); ?>
