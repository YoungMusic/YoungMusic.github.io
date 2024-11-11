<?php require("Header_YM.php") ?>
<div class="bg-mask min-vh-100">
    <!-- Logo -->
    <div class="logo-container position-absolute top-0 start-1 m-4">
        <img class="logo-reg" 
             style="max-width: 120px;"
             src="https://res.cloudinary.com/dlii53bu7/image/upload/v1729654882/Subida/rcoe0yvyz6hvjabfqkcy.webp" 
             alt="Logo">
    </div>

    <div class="container-fluid h-100 contenedor-registro-general">
        <div class="row h-100 registro-ym-rw">
            <!-- Parte Izquierda -->
            <div class="col-lg-6 parte_izquierda_registro d-flex flex-column justify-content-center align-items-center text-center p-4">
                <h2><a class="registro text-decoration-none" href="#">REGISTRARSE</a></h2>
                <h4><a class="login text-decoration-none" href="Login_YM.php">LOGIN</a></h4>
                <h4 class="info_datosreg text-light mt-4 w-75">
                    Ingrese los datos requeridos para crearte un usuario y tener una mejor experiencia en nuestro sitio web.
                </h4>
            </div>

            <!-- Parte Derecha -->
            <div class="col-lg-6 parte_derecha_registro d-flex align-items-center justify-content-center">
                <div class="registration-card">
                    <form action="RF_Registro_YM.php" method="post" enctype="multipart/form-data" class="p-4" id="form-Registro">
                        <!-- Avatar circular -->
                        <div class="text-center mb-4">
                            <div class="avatar-container">
                                <input type="file" name="file" id="file" 
                                       class="custom-file-input" 
                                       accept="image/*" 
                                       onchange="previewImage(event)">
                                <label for="file" class="avatar-label">
                                    <img id="preview" class="preview-image" alt="Preview" style="display: none;">
                                    <i class="bi bi-person-circle"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Campos del formulario -->
                        <div class="form-group input_derecha movement">
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input class="form-control" type="text" name="nombre" id="nombre" 
                                           placeholder="Nombre de Usuario">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input class="form-control" type="email" name="email" id="email" 
                                           placeholder="Ingrese su Correo">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input class="form-control" type="password" name="pass" id="pass" 
                                           placeholder="Ingrese su contraseña">
                                </div>
                            </div>

                            <div class="mb-3">
                                <select class="form-select selectores" id="Ubicación" name="Ubicación">
                                    <option value="">Seleccione un país</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <textarea class="form-control input_biografia" 
                                      name="biografia" 
                                      id="biografia" 
                                      rows="4"
                                      placeholder="Biografía">Sin biografía</textarea>
                        </div>

                        <div class="form-group botones_registro d-flex gap-2">
                            <button class="btn btn-secondary mr-2 col-6 bot" type="reset">Cancelar</button>
                            <button class="btn btn-primary flex-grow-1 bot" type="submit" 
                                    onclick="Verificar()" name="envio">Siguiente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require("Footer_YM.php"); ?>