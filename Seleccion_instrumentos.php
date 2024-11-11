<?php
require("Header_YM.php");
require("RF_Datos_Busqueda_YM.php");
$jsonData = file_get_contents('JSON/instrumentos.json');
$instrumentos = json_decode($jsonData, true)['instrumentos'];
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

<div class="container">
    <div class="row">
        <div class="col-md-6 parte_izquierda_registro p-izq-ins part-izq-red-art mb-4">
            <h2>
                <a class="registro">Registro de Artista</a>
                <hr class="bg-custom-register my-4 barra_register">
            </h2>
            <h4 class="info_datosreg">Ingrese los instrumentos que tocas</h4>
        </div>
        <div class="col-md-8 pref">
            <div class="preferences-container">
                <form id="forminstrumentos" action="RF_Seleccion_instrumentos.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="destinoFinal" name="destinoFinal" value="">
                    <div class="grid-container seleccion-container-ins">
                        <?php foreach ($instrumentos as $instrumento): ?>
                            <div class="grid-item">
                                <div class="form-check form-check-ins">
                                    <input class="form-check-input-ins" type="checkbox" name="instrumentos[]" value="<?php echo htmlspecialchars($instrumento); ?>">
                                    <label class="form-check-label-ins">
                                        <p class="genero-pref"><?php echo htmlspecialchars($instrumento); ?></p>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p id="mensajeErrorInstrumentos" class="text-danger mt-2" style="display: none;">Debe seleccionar al menos un instrumento.</p>
                    <button class="btn btn-primary bot mt-3" type="button" onclick="validarInstrumentos()">Siguiente</button>
                </form>

                <div id="ventanaEmergente" class="modal" tabindex="-1" role="dialog" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p> Su cuenta como artista ya está creada, pero aún no está verificada. Para ser reconocido como artista verificado, se le enviará un correo con ciertos parámetros.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" onclick="enviarFormularioIns()">Finalizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/seleccionar_instrumentos.js"></script>

<?php require("Footer_YM.php"); ?>
