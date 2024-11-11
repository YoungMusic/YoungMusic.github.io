<?php require("Header_YM.php"); ?>
<div class="container container-recu">
        <div class="row recu-rw">
            <div class="parte-izquierda-recuperacion">
                
                <h4>Se ha enviado el codigo a su correo corectamente, escriba el codigo y su correo para verificar que es usted</h4>
            </div>
            <div class="caja_popup" id="formrecuperar">
<form action="codigo_verificacion.php" method="POST" class="contenedor_popup">
<table>
                        <tr>
                            <th colspan="2">Recuperar contraseña</th>
                            </tr>
                            <tr>
                <td><b><i class="bi bi-envelope correo-recu"></i> Correo Electronico</b></td>
                <td><input class="cajaentradatexto" type="email" name="email" required></td>
                    
                    </tr>
                    <tr>
                    <td for="code"> <b><i class="bi bi-code"></i> Código de Verificación:</b></td>
                    <td><input class="cajaentradatexto" type="text" name="code" required></td>
                
                    
                    </tr>
            <tr> 	
            <td colspan="2" class="text-center">
    
    <input class="botones-recu" type="submit" value="Verificar Código">
</td>
</tr>
        </table>
</form>
</div>
<?php require("Footer_YM.php"); ?>