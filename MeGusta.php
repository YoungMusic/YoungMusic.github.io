<?php
require("Header_YM.php");
require("RF_Datos_Busqueda_YM.php");
require_once("Funciones.php");
$paginaPerfil = determinarTipoUsuario($email);
require_once("RF_MeGusta.php");

$paginaPerfil = determinarTipoUsuario($email);
?>

<nav class="navbar navbar-expand-md nav_index_ym">
    <a class="navbar-brand d-flex align-items-center" href="<?php echo htmlspecialchars($paginaPerfil); ?>">
        <img class="imagen_perfil_view" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil" style="width: 50px; height: 50px;">
        <span class="ml-2" style="color: white; padding-left:5px;"><?php echo htmlspecialchars($nombre); ?></span>
    </a>
    <?php if (isset($_SESSION["email"]) && esAdmin($_SESSION["email"])): ?><a class="incognito" href="Panel_Admin_YM.php"><i class="bi bi-incognito"></i></a><?php endif; ?>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <a href="Busqueda.php"><i class="bi bi-search"></i></a>
        </ul>
    </div>
</nav>

<div >
    
        <button id="toggleSidebar" class="btn btn-custom-slidebar">
            
      
    
</div>

<!-- Temas con Me Gusta -->
<div class="carousel-container">
    <h2 class="carousel-title">Tus Me Gusta</h2>
    <div class="carousel-wrapper">
        <button class="carousel-arrow prev">
            <i class="bi bi-chevron-left"></i>
        </button>

        <div class="carousel-track">
            <?php 
            $temasConLike = obtenerTemasConLike($_SESSION['email']);
            if (empty($temasConLike)): 
            ?>
                <div class="no-likes-message">
                    <p>Aún no has dado Me Gusta a ninguna canción</p>
                </div>
            <?php else: 
                foreach ($temasConLike as $tema):
            ?>
                <div class="carousel-slide" data-music-id="<?php echo htmlspecialchars($tema['IdMusi']); ?>">
                    <div class="carousel-card">
                        <div class="card-image">
                            <img src="<?php echo htmlspecialchars($tema['ImgMusi']); ?>" alt="Portada del tema">
                        </div>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($tema['NomMusi']); ?></h3>
                            <p class="fecha">Lanzado: <?php echo date('d/m/Y', strtotime($tema['FechaLan'])); ?></p>
                            <div class="artist-info">
                                <a href="Ver_artista_YM.php?correo=<?php echo htmlspecialchars($tema['CorrArti']); ?>" class="Link">
                                    <img src="<?php echo htmlspecialchars($tema['FotoPerf']); ?>" alt="Foto del artista" class="artist-avatar">
                                    <p class="artist"><?php echo htmlspecialchars($tema['NombArtis']); ?></p>
                                </a>
                            </div>
                            <div class="card-actions">
                                <audio id="audio-<?php echo htmlspecialchars($tema['IdMusi']); ?>" class="music-player">
                                    <source src="<?php echo htmlspecialchars($tema['Archivo']); ?>" type="audio/mpeg">
                                </audio>
                                <button class="play-btn" data-music-id="<?php echo htmlspecialchars($tema['IdMusi']); ?>">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                                <button class="like-btn active" data-music-id="<?php echo htmlspecialchars($tema['IdMusi']); ?>">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            endif; 
            ?>
        </div>

        <button class="carousel-arrow next">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
    <div class="carousel-dots"></div>
</div>

<footer class="footer-home">
            <div class="container-fluid">
                <div class="row cont-fot">
                    <div class="col-md-2 contenido-foot contenido-foot-bus">
                        <a href="Home_YM.php" class="nav-link">
                            <span class="icon-foot icon-home"><i class="bi bi-house"></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-bus">
                        <a href="Recien_llegados.php" class="nav-link">
                            <span class="icon-foot icon-clock"><i class="bi bi-clock"></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-bus">
                        <a href="Populares.php" class="nav-link">
                            <span class="icon-foot icon-fire"><i class="bi bi-fire"></i></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-bus">
                        <a href="MeGusta.php" class="nav-link">
                            <span class="icon-foot icon-heart"><i class="bi bi-suit-heart-fill"></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-bus">
                        <a href="Artistas_favoritos.php" class="nav-link">
                            <span class="icon-foot icon-person"><i class="bi bi-person-heart"></i></i></span>
                        </a>
                    </div>
                    
                </div>
            </div>
        </footer>

<?php require("Footer_YM.php"); ?>
