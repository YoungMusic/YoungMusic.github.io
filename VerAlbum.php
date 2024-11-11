<?php
require("Header_YM.php");
require_once("RF_VerAlbum.php");
require_once("RF_Datos_Busqueda_YM.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de álbum no proporcionado</div>";
    header("Location: Home_YM.php");
    exit();
}

$albumId = intval($_GET['id']);

if (!albumExiste($albumId)) {
    echo "<div class='alert alert-danger'>El álbum no existe</div>";
    header("Location: Home_YM.php");
    exit();
}

$album = obtenerDetallesAlbum($albumId);
$canciones = obtenerCancionesAlbum($albumId);

if (!$album) {
    echo "<div class='alert alert-danger'>Error al obtener los detalles del álbum</div>";
    header("Location: Albumes.php");
    exit();
}
$profileLink = isset($_SESSION['email']) ? determinarTipoUsuario($_SESSION['email']) : "Login_YM.php";
?>

<div class="container-fluid bg-dark text-white py-4">

    <div id="confirmationContainer" class="mb-4"></div>
    
    <div id="messageContainer" class="mb-4"></div>

    <div id="messageContainer" class="mb-4"></div>
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="<?php echo htmlspecialchars($album['ImgAlbu']); ?>"
                alt="<?php echo htmlspecialchars($album['NomAlbum']); ?>"
                class="img-fluid portada-album mb-3">
        </div>
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1><?php echo htmlspecialchars($album['NomAlbum']); ?></h1>
                    <?php
                    $artistProfileLink = isset($_SESSION['email']) && $_SESSION['email'] === $album['CorrArti']
                        ? 'artista_YM.php'
                        : 'Ver_artista_YM.php?correo=' . urlencode($album['CorrArti']);
                    ?>
                    <h3>Artista: <a href="<?php echo $artistProfileLink; ?>" class="Link">
                            <?php echo htmlspecialchars($album['NombArtis']); ?>
                        </a></h3>
                    <p>Categoría: <?php echo htmlspecialchars($album['Categoria']); ?></p>
                    <p>Fecha de lanzamiento: <?php echo date('d/m/Y', strtotime($album['FechaLan'])); ?></p>
                </div>
                <?php if (isset($_SESSION['email']) && ($_SESSION['email'] === $album['CorrArti'] || esAdmin($_SESSION['email']))): ?>
                    <button class="btn btn-danger delete-album-btn" data-album-id="<?php echo $albumId; ?>">
                        <i class="fas fa-trash"></i> Eliminar Álbum
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h2 class="mb-4">Canciones</h2>
                <div class="song-list" id="songList">
    <?php if (empty($canciones)): ?>
        <div class="alert alert-info">No hay canciones en este álbum.</div>
    <?php else: ?>
        <?php foreach ($canciones as $cancion): 
            $isLiked = verificarLike($cancion['IdMusi'], $_SESSION['email']); // Función para verificar si el usuario ya dio "like"
        ?>
            <div class="song-item d-flex align-items-center p-3 mb-3 border rounded">
                <img src="<?php echo htmlspecialchars($cancion['ImgMusi']); ?>"
                    alt="<?php echo htmlspecialchars($cancion['NomMusi']); ?>"
                    class="song-thumbnail mr-3">
                <div class="song-info flex-grow-1">
                    <h4 class="mb-0"><?php echo htmlspecialchars($cancion['NomMusi']); ?></h4>
                    <p class="mb-0">Géneros: <?php echo htmlspecialchars($cancion['Generos']); ?></p>
                </div>
                <button class="Like-boton <?php echo $isLiked ? 'liked' : ''; ?>"
                        data-song-id="<?php echo $cancion['IdMusi']; ?>">
                    <i class="fas fa-heart"></i>
                </button>
                <div class="audio-player-container">
                    <audio class="custom-audio-player" data-song-id="<?php echo $cancion['IdMusi']; ?>">
                        <source src="<?php echo htmlspecialchars($cancion['Archivo']); ?>" type="audio/mpeg">
                        Tu navegador no soporta el elemento de audio.
                    </audio>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
            </div>
        </div>

        <!-- Sección de Comentarios -->
        <div class="row mt-4">
            <div class="col-12">
                <h2 class="mb-4">Comentarios</h2>

                <?php if (isset($_SESSION["email"])): ?>
                    <!-- Formulario para agregar comentarios -->
                    <form id="commentForm" class="mb-4" data-album-id="<?php echo $albumId; ?>">
                        <div class="form-group">
                            <textarea class="form-control" id="comentario" rows="3"
                                placeholder="Escribe tu comentario aquí..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Publicar Comentario</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info">
                        Debes iniciar sesión para poder comentar.
                    </div>
                <?php endif; ?>

                <!-- Lista de comentarios -->
                <div id="commentsList">
                    <?php
                    $comentarios = obtenerComentarios($albumId, isset($_SESSION["email"]) ? $_SESSION["email"] : null);
                    foreach ($comentarios as $comentario):
                        $commenterLink = $_SESSION['email'] === $comentario['CorrUsu']
                            ? $profileLink
                            : 'Ver_artista_YM.php?correo=' . urlencode($comentario['CorrUsu']);
                    ?>
                        <div class="comment-item p-3 mb-3 rounded" data-comment-id="<?php echo $comentario['IdComentario']; ?>">
                            <div class="d-flex">
                                <a href="<?php echo $commenterLink; ?>" class="Link">
                                    <img src="<?php echo htmlspecialchars($comentario['FotoPerf']); ?>"
                                        alt="<?php echo htmlspecialchars($comentario['NomrUsua']); ?>"
                                        class="comment-user-img rounded-circle mr-3">
                                </a>
                                <div class="flex-grow-1">
                                    <div class="comment-header">
                                        <div class="comment-user-info">
                                            <h5 class="mb-1">
                                                <a href="<?php echo $commenterLink; ?>" class="Link">
                                                    <?php echo htmlspecialchars($comentario['NomrUsua']); ?>
                                                </a>
                                            </h5>
                                            <p class="comment-date mb-1">
                                                <?php echo date('d/m/Y H:i', strtotime($comentario['FechaCom'])); ?>
                                            </p>
                                        </div>
                                        <?php if ($comentario['puedeEliminar']): ?>
                                            <div class="comment-actions">
                                                <button type="button"
                                                    class="delete-comment-btn"
                                                    data-comment-id="<?php echo $comentario['IdComentario']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="comment-content mb-0">
                                        <?php echo nl2br(htmlspecialchars($comentario['Comentario'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php require("Footer_YM.php"); ?>