<?php
require("Header_YM.php");
require("RF_Datos_Busqueda_YM.php");
$jsonData = file_get_contents('JSON/generos.json');
$generos = json_decode($jsonData, true)['generos'];
?>

<nav class="navbar navbar-expand-md nav_index_ym">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img class="imagen_perfil_view" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil" style="width: 50px; height: 50px;">
        <span class="ml-2" style="color: white; padding-left:5px;"><?php echo htmlspecialchars($nombre); ?></span>
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            
        </ul>
    </div>
</nav>

<div class="container container-redes-art">
        <div class="row">
            <div class="col-md-6 parte_izquierda_registro part-izq-red-art mb-4">
                <h2>
                    <a class="registro">Registro de Artista</a>
                    <hr class="bg-custom-register my-4 barra_register">
                </h2>
                <h4 class="info_datosreg">
                    Ingresa sus redes sociales para que la gente y disqueras te logren conocer de otra forma (Pon el link de la dirección hacia tu cuenta en tus redes)
                </h4>
            </div>
            <div class="col-md-6 parte_derecha_login">
                <form action="RF_Redes_Arti_YM.php" method="post">
                    <h2 class="text-center">Redes Sociales</h2>
                    <div class="form-group form-group-redesartista">
                        <label for="Red1">
                            <i class="bi bi-instagram icono-redes"></i> Instagram
                        </label>
                        <input type="text" class="form-control" name="Red1" id="Red1" placeholder="Agregar">
                    </div>
                    <div class="form-group form-group-redesartista">
                        <label for="Red2">
                            <i class="bi bi-youtube icono-redes"></i> YouTube
                        </label>
                        <input type="text" class="form-control" name="Red2" id="Red2" placeholder="Agregar">
                    </div>
                    <div class="form-group form-group-redesartista">
                        <label for="Red3">
                            <i class="bi bi-spotify icono-redes"></i> Spotify
                        </label>
                        <input type="text" class="form-control" name="Red3" id="Red3" placeholder="Agregar">
                    </div>
                    <div class="form-group form-group-redesartista">
                        <label for="Red4">
                            <i class="bi bi-tiktok icono-redes"></i> TikTok
                        </label>
                        <input type="text" class="form-control" name="Red4" id="Red4" placeholder="Agregar">
                    </div><br>
                    <div class="form-group botones_registro text-center">
                        <button class="btn btn-secondary mr-2 bot" type="reset">Cancelar</button>
                        <button class="btn btn-primary bot" type="submit" name="envio">Siguiente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

