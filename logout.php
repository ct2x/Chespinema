<?php
// Incluye la lógica de tu controlador
require_once 'controllers/LoginController.php';

// Crea una instancia del controlador
$loginController = new LoginController();

// Llama al método logout
$loginController->logout();
?>
