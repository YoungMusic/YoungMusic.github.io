<?php
require("Header_YM.php");
require("RF_Discografica_YM.php");
?>

<nav class="navbar navbar-expand-md nav_index_ym">
    <a class="navbar-brand d-flex align-items-center" href="#">
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

        <div class="botones  text-center text-md-left">
            <div class="btn-group flex-md-row text-center mt-3">
                <button class="btn btn-success" onclick="mostrarEditarPerfil()" aria-label="Editar Perfil"><i class="bi bi-pencil-square"> | </i>Editar Perfil</button>
                <button><a class="btn btn-secondary mt-1 mt-md-0" href="logout.php" aria-label="Cerrar sesión"><i class="bi bi-person-x-fill"> | </i>Cerrar sesión</a></button>
            </div>
        </div>
        <div class="cont-u">

            <div class="imagen_perfil-container">
                <img class="imagen_perfil" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil">
            </div>

            <div class="perfil-content">
                <div class="perfil-details">
                    <h2>| <?php echo htmlspecialchars($nombre); ?></h2>
                    <p>| <?php echo htmlspecialchars($correo); ?></p>
                    <div class="social-icons d-flex justify-content-center">
                        <button class="icons"><a id="tiktok" style="display:none;"><i class="bi bi-tiktok"></i></a></button>
                        <button class="icons"><a id="instagram" style="display:none;"><i class="bi bi-instagram"></i></a></button>
                        <button class="icons"><a id="youtube" style="display:none;"><i class="bi bi-youtube"></i></a></button>
                        <button class="icons"><a id="spotify" style="display:none;"><i class="bi bi-spotify"></i></a></button>
                    </div>
                    <hr class="bg-custom-loginu my-4 barra_loginu">
                    <h5>| Biografia</h5>
                    <p><?php echo htmlspecialchars($biografia); ?></p>
                    <hr class="bg-custom-loginu my-4 barra_loginu">
                </div>

            </div>
                    <!-- Formulario para Editar Perfil -->
        <div class="form-container form-disc fondo_perfil_editar perfil-disc" id="editarPerfil" style="display: none;">
            <h3>Editar Perfil</h3>
            <form action="RF_Discografica_YM.php" method="post" enctype="multipart/form-data" onsubmit="return confirmarContrasena('editar')">
                <div class="form-group">
                    <input type="file" name="nuevaFoto" id="file" class="custom-file-input" accept="image/*" onchange="previewImage(event)">
                    <label for="file" class="custo-label">

                        <img id="preview" class="previe-image-2" alt="Preview" src="<?php echo htmlspecialchars($fotoPerfil); ?>">

                    </label>
                    <br>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="nuevoNombre" placeholder="Nuevo Nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                </div><br>
                <div class="form-group">
                    <textarea class="form-control input_biografia" name="nuevaBiografia" placeholder="Biografía"><?php echo htmlspecialchars($biografia); ?></textarea>
                </div><br>
                <br>
                <div class="form-group">
                    <label for="Red1">
                        <i class="bi bi-instagram icono-redes"></i> Instagram
                    </label>
                    <input type="text" class="form-control" name="Red1" id="Red1" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['Instagram']); ?>">
                </div>
                <br>
                <div class="form-group">
                    <label for="Red2">
                        <i class="bi bi-youtube icono-redes"></i> YouTube
                    </label>
                    <input type="text" class="form-control" name="Red2" id="Red2" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['Youtube']); ?>">
                </div>
                <br>
                <div class="form-group">
                    <label for="Red3">
                        <i class="bi bi-spotify icono-redes"></i> Spotify
                    </label>
                    <input type="text" class="form-control" name="Red3" id="Red3" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['Spotify']); ?>">
                </div>
                <br>
                <div class="form-group">
                    <label for="Red4">
                        <i class="bi bi-tiktok icono-redes"></i> TikTok
                    </label>
                    <input type="text" class="form-control" name="Red4" id="Red4" placeholder="Agregar" value="<?php echo htmlspecialchars($usuario['TikTok']); ?>">
                </div><br>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña Actual" required>
                </div><br>
                <button type="submit" class="btn btn-primary" name="editarPerfil"><i class="bi bi-check"> | </i>Guardar Cambios</button>
                <button class="btn btn-danger mt-2 mt-md-0" onclick="mostrarEliminarPerfil()" aria-label="Eliminar Perfil"><i class="bi bi-x-lg"> | </i>Eliminar Perfil</button>
            </form>
        </div>

        <!-- Formulario para Eliminar Perfil -->
        <div class="form-container form-disc-delete fondo_perfil_editar" id="eliminarPerfil" style="display: none;">
            <h3>Eliminar Perfil</h3>
            <form action="RF_Usuario_YM.php" method="post" onsubmit="return confirmarContrasena('eliminar')">
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Ingrese su Contraseña" required>
                </div><br>
                <button type="submit" class="btn btn-danger" name="eliminarPerfil">Confirmar Eliminación</button>
            </form>
        </div>
        </div>
    </div>
</div>


<footer class="footer-home footer-veralbum">
            <div class="container-fluid">
                <div class="row cont-fot">
                    <div class="col-md-2 contenido-foot contenido-foot-perf">
                        <a href="Home_YM.php" class="nav-link">
                            <span class="icon-foot icon-home"><i class="bi bi-house"></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-perf">
                        <a href="" class="nav-link">
                            <span class="icon-foot icon-clock"><i class="bi bi-clock"></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-perf">
                        <a href="" class="nav-link">
                            <span class="icon-foot icon-fire"><i class="bi bi-fire"></i></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-perf">
                        <a href="" class="nav-link">
                            <span class="icon-foot icon-heart"><i class="bi bi-suit-heart-fill"></i></span>
                        </a>
                    </div>
                    <div class="col-md-2 contenido-foot contenido-foot-perf">
                        <a href="" class="nav-link">
                            <span class="icon-foot icon-person"><i class="bi bi-person-heart"></i></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

<?php require("Footer_YM.php"); ?>