<?php
require("Header_YM.php");
require("RF_Usuario_YM.php");
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
                    <hr class="bg-custom-loginu my-4 barra_loginu">
                    <h5>| Biografia</h5>
                    <div class="bio-item">
                        <p><?php echo htmlspecialchars($biografia); ?> </p>
                    </div>



                </div>

            </div>

        </div>

    </div>
    <!-- Formulario para Editar Perfil -->
    <div class="form-container fondo_perfil_editar" id="editarPerfil" style="display: none;">
        <h3>Editar Perfil</h3>
        <form action="RF_Usuario_YM.php" method="post" enctype="multipart/form-data" onsubmit="return confirmarContrasena('editar')">
        <div class="form-group">
                <input type="file" name="nuevaFoto" id="file" class="custom-file-input" accept="image/*" onchange="previewImage(event)">
                <label for="file" class="custo-label">

                        <img id="preview" class="previe-image-2" alt="Preview" src="<?php echo htmlspecialchars($fotoPerfil); ?>">
                    
                </label>
                <br>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="nuevoNombre" placeholder="Nuevo Nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div><br>
            <div class="form-group">
                <textarea class="form-control input_biografia" name="nuevaBiografia" placeholder="Biografía"><?php echo htmlspecialchars($biografia); ?></textarea>
            </div><br>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Contraseña Actual" required>
            </div><br>
            <button type="submit" class="btn btn-primary" name="editarPerfil"><i class="bi bi-check"> | </i>Guardar Cambios</button>
            <button class="btn btn-danger mt-2 mt-md-0" onclick="mostrarEliminarPerfil()" aria-label="Eliminar Perfil"><i class="bi bi-x-lg"> | </i>Eliminar Perfil</button>

        </form>
    </div>
    <!-- Formulario para Eliminar Perfil -->
    <div class="form-container fondo_perfil_editar" id="eliminarPerfil" style="display: none;">
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


<?php require("Footer_YM.php"); ?>