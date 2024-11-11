<?php require 'vendor/autoload.php'; // Asegurarse de cargar el autoload de Composer

use Cloudinary\Configuration\Configuration;

// Configurar Cloudinary
 Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dlii53bu7',
        'api_key'    => '473661389259698',
        'api_secret' => 'yhogWukmWHE6Gl-2ZIDOUIiCFJ4',
    ],
    'url' => [
        'secure' => true
    ]
]);


?>