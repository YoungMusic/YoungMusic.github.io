<?php


session_start();
session_unset();
session_destroy();
header('Location: Registro_YM.php');
