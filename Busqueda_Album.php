<?php
require("Header_YM.php");
require("RF_Datos_Busqueda_YM.php");
$paginaPerfil = determinarTipoUsuario($email);
require_once("Funciones.php");
?>
<nav class="navbar navbar-expand-md nav_index_ym">
    <a class="navbar-brand d-flex align-items-center" href="<?php echo htmlspecialchars($paginaPerfil); ?>">
        <img class="imagen_perfil_view" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil" style="width: 50px; height: 50px;">
        <span class="ml-2" style="color: white; padding-left:5px;"><?php echo htmlspecialchars($nombre); ?></span>
    </a>
    <?php if (isset($_SESSION["email"]) && esAdmin($_SESSION["email"])): ?><a class="incognito" href="Panel_Admin_YM.php"><i class="bi bi-incognito"></i></a><?php endif; ?>
    <button class="navbar-toggler desplazador-busqueda" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-search icono-busqueda"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto"></ul> <!-- Espacio vacío hacia la izquierda -->
        <form class="d-flex ms-auto" role="search" id="form-buscar-Album"> <!-- ms-auto para margen a la izquierda -->
            <input class="form-control me-2" type="text" name="usuario" id="Album" placeholder="Ingrese el nombre a buscar" aria-label="Search">
            <input type="submit" value="Buscar" name="envio" class="btn btn-primary" onclick="consultar_en_tiempo_real_Album()">
        </form>
    </div>

</nav>

<div class="container contenido-bus">
    <div class="flex justify-center space-x-4 mb-6">
        <a href="Busqueda.php" class="LinkB"><button class="text-white py-2 px-4 rounded-full boton-bus">Artista
            </button>
            <a href="Busqueda_musica.php" class="LinkB"><button class="text-white py-2 px-4 rounded-full boton-bus">música
                </button></a>
            <a href="#" class="LinkB"><button class="text-white py-2 px-4 rounded-full boton-bus">Álbum
                </button></a>
    </div>
</div>
<div class="container container-busqueda">

    <div class="row resultado" id="resultado_album" style=" overflow-y: auto;"></div>

</div>


<?php require("Footer_YM.php"); ?>