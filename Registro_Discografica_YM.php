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
            <!-- Agrega elementos de navegación si es necesario -->
        </ul>
    </div>
</nav>

<div class="container cont-regd">
    <div class="row reg-disc-rw">


        <div class="col-md-6 parte_izquierda_registro_discog text-center mb-4">
            <h2>
                <a class="registro">Registro de Discográfica</a>
                <hr class="bg-custom-register my-4 barra_register">
            </h2>
            <h4 class="info_datosreg">
                Coloque el Nombre por el cual quiere ser Identificado
            </h4>
        </div>

        <div class="col-md-6 parte_derecha_login">
            <form action="RF_Registro_Discografica_YM.php" method="post">
                <div class="form-group"><br>

                    <input class="form-control form-control-input-dis" type="text" name="nombre_d" id="nombre_d" placeholder="Ingrese su Nombre de la Discográfica">
                </div><br>
                <div class="form-group botones_registro text-center">
                    <button class="btn btn-secondary mr-2 bot" type="reset">Cancelar</button>
                    <button class="btn btn-primary bot" type="submit" name="envio">Siguiente</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require("Footer_YM.php"); ?>