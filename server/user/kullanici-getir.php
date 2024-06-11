<?php
    require '../connection.php';
    require '../common/headers.php';
    require '../common/service.php';

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode([
            'success' => false,
            'message' => 'JSON Hatalı'
        ]);
        return;
    }

    if (!isset($data["userId"])) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $userId = $data["userId"];
    
    $query = "SELECT * FROM users WHERE id = :userId";
    $statement = $db->prepare($query);

    $statement->execute([
        'userId' => $userId
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Kullanıcı Bulunamadı'
        ]);
        return;
    }

    echo json_encode([
        'success' => true,
        'user' => $user
    ]);