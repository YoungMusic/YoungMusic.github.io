<?php require("Header_YM.php"); ?>
<div class="container container-recu">
        <div class="row recu-rw">
            <div class="parte-izquierda-recuperacion">
                <h4>Ingrese su correo al cual se le enviará un código</h4>
            </div>
            <div class="caja_popup" id="formrecuperar">
                <form action="recuperar.php" class="contenedor_popup" method="POST">
                    <table>
                        <tr>
                            <th colspan="2">Recuperar contraseña</th>
                        </tr>
                        <tr>
                            <td><b><i class="bi bi-envelope correo-recu"></i> Correo</b></td>
                            <td><input type="email" name="txtcorreo" required class="cajaentradatexto"></td>
                            </tr>
                            <tr>
                            <td colspan="2" class="text-center">
                                <button type="button" class="btn btn-secondary botones-recu">Cancelar</button>
                                <input class="btn btn-primary botones-recu" type="submit" name="btnrecuperar" value="Enviar" onClick="javascript: return confirm('¿Deseas enviar tu contraseña a tu correo?');">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje">
                    <strong><?php echo $mensaje; ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php require("Footer_YM.php"); ?>