<?php
session_start();
include 'conexion.php';

/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nuevo_usuario = $_POST['nombre_usuario'];
    $nuevo_correo = $_POST['correo'];
    $nuevo_tel = $_POST['tel'];
    $success = true;
    $mensaje = '';

    // Verificar si el nuevo nombre de usuario ya existe (excepto para el usuario actual)
    $check_query = "SELECT pk_usuarios FROM usuarios WHERE usuario = ? AND pk_usuarios != ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("si", $nuevo_usuario, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $success = false;
        $mensaje = 'El nombre de usuario ya está en uso';
    } else {
        // Procesar la foto si se ha subido una
        $foto_path = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $filename = $_FILES['foto']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $nuevo_nombre = uniqid() . '.' . $ext;
                $destino = 'imagenes/perfil/' . $nuevo_nombre;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    $foto_path = $nuevo_nombre;
                }
            }
        }

        // Actualizar la información del usuario
        if ($foto_path) {
            $query = "UPDATE usuarios SET usuario = ?, correo = ?, tel = ?, foto = ? WHERE pk_usuarios = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $nuevo_usuario, $nuevo_correo, $nuevo_tel, $foto_path, $usuario_id);
        } else {
            $query = "UPDATE usuarios SET usuario = ?, correo = ?, tel = ? WHERE pk_usuarios = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $nuevo_usuario, $nuevo_correo, $nuevo_tel, $usuario_id);
        }

        if ($stmt->execute()) {
            $_SESSION['nombre_usuario'] = $nuevo_usuario;
            if ($foto_path) {
                $_SESSION['foto_perfil'] = $foto_path;
            }
            $mensaje = 'Perfil actualizado correctamente';
        } else {
            $success = false;
            $mensaje = 'Error al actualizar el perfil';
        }
    }

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'mensaje' => $mensaje]);
    exit;
}*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nuevo_usuario = $_POST['nombre_usuario'];
    $nuevo_correo = $_POST['correo'];
    $nuevo_tel = $_POST['tel'];
    
    $success = true;
    $mensaje = '';

    // Verificar si el nuevo nombre de usuario ya existe (excepto para el usuario actual)
    $check_query = "SELECT pk_usuarios FROM usuarios WHERE usuario = ? AND pk_usuarios != ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("si", $nuevo_usuario, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $success = false;
        $mensaje = 'El nombre de usuario ya está en uso';
    } else {
        // Procesar la foto si se ha subido una
        $foto_path = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $filename = $_FILES['foto']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $nuevo_nombre = uniqid() . '.' . $ext;
                $destino = 'imagenes/perfil/' . $nuevo_nombre;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    $foto_path = $nuevo_nombre;
                } else {
                    $success = false;
                    $mensaje = 'Error al guardar la foto de perfil';
                }
            } else {
                $success = false;
                $mensaje = 'Formato de imagen no permitido';
            }
        }

        // Actualizar la información del usuario
        if ($foto_path) {
            $query = "UPDATE usuarios SET usuario = ?, correo = ?, tel = ?, imagen = ? WHERE pk_usuarios = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $nuevo_usuario, $nuevo_correo, $nuevo_tel, $foto_path, $usuario_id);
        } else {
            $query = "UPDATE usuarios SET usuario = ?, correo = ?, tel = ? WHERE pk_usuarios = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $nuevo_usuario, $nuevo_correo, $nuevo_tel, $usuario_id);
        }

        if ($stmt->execute()) {
            $_SESSION['nombre_usuario'] = $nuevo_usuario;
            if ($foto_path) {
                $_SESSION['foto_perfil'] = $foto_path;
            }
            $mensaje = 'Perfil actualizado correctamente';
        } else {
            $success = false;
            $mensaje = 'Error al actualizar el perfil';
        }
    }

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'mensaje' => $mensaje]);
    exit;
}

?>
