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

<div class="container reg-art">
    <div class="row">
        <div class="col-md-6 parte_izquierda_registro part-izq-reg-art">
            <h2>
                <a class="registro">Registro de Artista</a>
                <hr class="bg-custom-register my-4 barra_register">
            </h2>
            <h4 class="info_datosreg">Coloque el Nombre por el cual quiere ser Identificado</h4>
        </div>

        <div class="col-md-6 parte_derecha_login">
            <form id="registroArtistaForm" action="RF_Registro_Artista_YM.php" method="post">
                <div class="form-group form-group-regart"><br>
                    <input class="form-control" type="text" name="nombre_a" id="nombre_a" placeholder="Ingrese su Nombre artístico o el de la banda">
                </div><br>
                <div class="form-group form-group-regart">
                    <label for="fecha">Ingrese su fecha de nacimiento</label>
                    <input class="form-control" type="date" name="fecha" id="fecha">
                    <p id="mensajeError" class="text-danger mt-2" style="display: none;">Debes ser mayor de 12 años/Introducir una fecha valida para registrarte.</p>
                </div><br>
                <div class="form-group botones_registro text-center">
                    <button class="btn btn-secondary bot" type="reset">Cancelar</button>
                    <button class="btn btn-primary bot" type="button" onclick="validarEdad()">Siguiente</button>
                </div><br>
            </form>
        </div>
    </div>
</div>

<?php require("Footer_YM.php"); ?>
