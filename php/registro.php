<?php
/* ============================================
   PROYECTO: CODE & CREATE
   Backend - Registro de Participantes
   Con Número de Confirmación Único
   Autor: Leonel Rondón - CI: 32.079.527
   ============================================ */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
    $edad = intval($_POST['edad'] ?? 0);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $github_user = htmlspecialchars(trim($_POST['github_user'] ?? ''), ENT_QUOTES, 'UTF-8');

    // Validaciones
    if (strlen($nombre) < 3 || strlen($nombre) > 100) {
        throw new Exception('Nombre inválido (3-100 caracteres)');
    }
    if ($edad < 15 || $edad > 30) {
        throw new Exception('Edad debe estar entre 15 y 30 años');
    }
    if (!$email) {
        throw new Exception('Email no válido');
    }

    // Conexión a BD
    $pdo = new PDO(
        'mysql:host=localhost;dbname=code_create;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    // Verificar email duplicado
    $stmt = $pdo->prepare("SELECT id FROM participantes WHERE email = :email");
    $stmt->execute([':email' => $email]);
    
    if ($stmt->fetch()) {
        throw new Exception('Este email ya está registrado');
    }

    // Verificar cupo máximo
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM participantes");
    $stmt->execute();
    $resultado = $stmt->fetch();
    
    if ($resultado['total'] >= 6) {
        throw new Exception('El taller está lleno. Máximo 6 participantes.');
    }

    // Insertar participante
    $stmt = $pdo->prepare("
        INSERT INTO participantes (nombre, edad, email, github_user, torre, fecha_registro) 
        VALUES (:n, :e, :em, :g, 'Guayacán', NOW())
    ");
    
    $stmt->execute([
        ':n' => $nombre,
        ':e' => $edad,
        ':em' => $email,
        ':g' => $github_user
    ]);

    $nuevo_id = $pdo->lastInsertId();

    // 🎫 GENERAR NÚMERO DE CONFIRMACIÓN ÚNICO
    // Formato: CC-YYYYMMDD-XXXX (ej: CC-20260704-7842)
    $fecha = date('Ymd');
    $aleatorio = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $codigo_confirmacion = "CC-{$fecha}-{$aleatorio}";

    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => '¡Registro exitoso!',
        'id' => $nuevo_id,
        'nombre' => $nombre,
        'codigo_confirmacion' => $codigo_confirmacion,
        'cupos_restantes' => 6 - $resultado['total'] - 1
    ]);

} catch (PDOException $e) {
    error_log("Error PDO: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>