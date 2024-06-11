<?php
    require '../../connection.php';
    require '../../common/headers.php';
    require '../../common/service.php';

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode([
            'success' => false,
            'message' => 'JSON Hatalı'
        ]);
        return;
    }

    if (!isset($data["userId"]) || !isset($data["id"])) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $userId = $data["userId"];
    if (!authorize($userId, $db, "admin")) {
        echo json_encode([
            'success' => false,
            'message' => 'Yetkisiz Kullanıcı'
        ]);
        return;
    }

    $id = $data["id"];

    if (empty($id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "DELETE FROM users WHERE id = :id";
    $statement = $db->prepare($query);

    $statement->execute([
        'id' => $id
    ]);

    $query = "DELETE FROM lessons_learned WHERE user_id = :id";
    $statement = $db->prepare($query);

    $statement->execute([
        'id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Kullanıcı başarıyla silindi'
    ]);