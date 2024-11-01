<?php
require_once 'controllers/RegistroController.php';

$registroController = new RegistroController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registroController->registrarUsuario();
} else {
    $registroController->mostrarFormulario();
}
