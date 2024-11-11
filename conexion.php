    <?php

    function conectar_bd()
    {

        $servidor = "localhost";
        $bd = "bd_ym_proyect";
        $usuario = "root";
        $pass = "";

        $conn = mysqli_connect($servidor, $usuario, $pass, $bd);


        if (!$conn) {
            die("Error de conexion " . mysqli_connect_error());
        }

        return $conn;
    }


    $con = conectar_bd();
