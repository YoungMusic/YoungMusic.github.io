<?php
require("Header_YM.php");
require("RF_Artista_YM.php");
if (!isset($_SESSION["email"])) {
    header("Location: Login_YM.php");
    exit();
}

$album_id = $_GET['album'] ?? null;
$categoria = $_GET['categoria'] ?? null;

if (!$album_id || !$categoria) {
    header("Location: Artista_YM.php");
    exit();
}

$limite_canciones = ($categoria === 'EP') ? 6 : (($categoria === 'Sencillo') ? 1 : 999);
?>
<h2 class="lanza text-center my-4">Agregar Música al Álbum</h2>
<hr class="bg-custom-loginu my-4 barra_loginu">

<div class="container">
    <div class="musica-from-container">
        <br>
        <form id="formMusica" enctype="multipart/form-data">
            <input type="hidden" name="album_id" value="<?php echo htmlspecialchars($album_id); ?>">
            <div class="form-group">
                <label for="NomMusi">Nombre de la Canción:</label>
                <input type="text" class="form-control" id="NomMusi" name="NomMusi" required>
                <div id="errorNomMusi" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="Archivo">Archivo de Audio:</label>
                <input type="file" class="form-control" id="Archivo" name="Archivo" accept="audio/*" required>
                <div id="errorArchivo" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="ImgMusi">Imagen de la Canción:</label>
                <input type="file" class="form-control" id="ImgMusi" name="ImgMusi" accept="image/*" required>
                <div id="errorImgMusi" class="error-message"></div>
            </div>

            <div class="form-group">
                <label>Géneros:</label>
                <div class="grid-container generos_container">
                    <?php
                    $generos = json_decode(file_get_contents('JSON/generos.json'), true);
                    foreach ($generos['generos'] as $genero):
                    ?>
                        <div class="grid-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Generos[]" value="<?php echo htmlspecialchars($genero); ?>" id="genero-<?php echo htmlspecialchars($genero); ?>">
                                <label class="form-check-label form-check-label-subida" for="genero-<?php echo htmlspecialchars($genero); ?>">
                                    <p class="genero-pref"><?php echo htmlspecialchars($genero); ?> </p>
                                </label>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                    <div id="errorGeneros" class="error-message"></div>
                </div>
            </div>

            <div class="botones text-center text-md-left">
                <div class="btn-group flex-md-row text-center mt-3">
                <button type="button" class="btn btn-primary" onclick="validarFormularioMusica()">Agregar Canción</button>

        </form>

        <form action="Artista_YM.php">
            <button type="submit" class="btn btn-primary">Salir</button>
        </form>
    </div>
    <div id="mensaje-estado" class="mensaje-estado" style="display: none;">
        <span id="icono-estado" class="icono-estado">⟳</span>
        <span id="texto-estado">Subiendo canción...</span>
    </div>
</div>

<div id="canciones-agregadas">
    <!-- Aquí se mostrarán las canciones agregadas -->
</div>
</div>
</div>

<?php require("Footer_YM.php"); ?>