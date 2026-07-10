<?php
/* ============================================
   PROYECTO: CODE & CREATE
   Taller de Desarrollo Web para Jóvenes
   Backend Seguro - Formulario de Contacto
   
   Autor: Leonel Rondón - CI: 32.079.527
   Universidad Bicentenaria de Aragua
   Servicio Comunitario - Ingeniería de Sistemas
   ============================================ */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Use POST']);
    exit;
}

try {
    $nombre  = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (strlen($nombre) < 2 || strlen($nombre) > 50) {
        throw new Exception('Nombre inválido (debe tener 2-50 caracteres)');
    }
    if (!$email) {
        throw new Exception('Email no válido');
    }
    if (strlen($mensaje) < 10 || strlen($mensaje) > 500) {
        throw new Exception('Mensaje debe tener 10-500 caracteres');
    }

    $pdo = new PDO(
        'mysql:host=localhost;dbname=code_create;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

    $stmt = $pdo->prepare("
        INSERT INTO mensajes (nombre, email, mensaje, ip, fecha) 
        VALUES (:n, :e, :m, :ip, NOW())
    ");
    
    $stmt->execute([
        ':n'  => $nombre,
        ':e'  => $email,
        ':m'  => $mensaje,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ]);

    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => 'Mensaje guardado correctamente. Te contactaré pronto.',
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (PDOException $e) {
    error_log("Error PDO: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error de base de datos. Inténtalo de nuevo.'
    ]);
    
} catch (Exception $e) {
    error_log("Error Validación: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>