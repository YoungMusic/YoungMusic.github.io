<?php
require("Header_YM.php");
require("RF_Artista_YM.php");
if (!isset($_SESSION["email"])) {
    header("Location: Login_YM.php");
    exit();
}
?>

<nav class="navbar navbar-expand-md nav_index_ym">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img class="imagen_perfil_view rounded-circle" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil" style="width: 50px; height: 50px; object-fit: cover;">
        <span class="ml-2" style="color: white; padding-left:5px;"><?php echo htmlspecialchars($nombre); ?></span>
    </a>
    <i class="bi bi-music-note text-white fs-4"></i>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <a href="Busqueda.php" class="text-white"><i class="bi bi-search fs-4"></i></a>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="album-form-container">
        <h1 class="album-title">
            <i class="bi bi-music-note-list me-2"></i>
            Subir Nuevo Álbum
        </h1>

        <form action="RF_Subida_Album.php" method="post" enctype="multipart/form-data" id="albumForm">
            <div class="form-group mb-4">
                <label for="NomAlbum">
                    <i class="bi bi-music-note-beamed me-2"></i>
                    Nombre del Álbum
                </label>
                <input type="text" class="form-control" id="NomAlbum" name="NomAlbum" required
                    placeholder="Ingresa el nombre del álbum">
                <p id="errorNombre" class="text-danger" style="display: none;">El nombre del álbum es obligatorio.</p>
            </div>

            <div class="form-group mb-4">
                <label for="Categoria">
                    <i class="bi bi-collection me-2"></i>
                    Categoría
                </label>
                <select class="form-control custom-select" id="Categoria" name="Categoria" required>
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <option value="Album">Álbum</option>
                    <option value="EP">EP</option>
                    <option value="Sencillo">Sencillo</option>
                </select>
                <p id="errorCategoria" class="text-danger" style="display: none;">La categoría es obligatoria.</p>
            </div>

            <div class="form-group mb-4">
                <label for="FechaLan">
                    <i class="bi bi-calendar-event me-2"></i>
                    Fecha de Lanzamiento
                </label>
                <input type="date" class="form-control" id="FechaLan" name="FechaLan" required>
                <p id="errorFecha" class="text-danger" style="display: none;">La fecha de lanzamiento debe ser válida y anterior o igual a hoy.</p>
            </div>

            <div class="form-group mb-4">
                <label for="ImgAlbu" class="form-label">
                    <i class="bi bi-image me-2"></i>
                    Portada del Álbum
                </label>
                <div class="album-upload-container">
                    <div class="input-group">
                        <input type="file"
                            class="form-control"
                            id="ImgAlbu"
                            name="ImgAlbu"
                            accept="image/*"
                            required>
                        <label class="input-group-text" for="ImgAlbu">
                            <i class="bi bi-upload"></i>
                        </label>
                    </div>
                    <div class="mt-3 text-center">
                        <img id="imagePreview" class="preview-image" src="#" alt="Vista previa de la portada">
                    </div>
                    <p id="errorPortada" class="text-danger" style="display: none;">La portada del álbum es obligatoria.</p>
                </div>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-primary submit-btn" onclick="validarFormulario()">
                    <i class="bi bi-cloud-upload me-2"></i>
                    Subir Álbum
                </button>
            </div>
        </form>
    </div>
</div>


<?php require("Footer_YM.php"); ?>
