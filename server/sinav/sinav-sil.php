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

    $query = "DELETE FROM exams WHERE id = :exam_id";
    $statement = $db->prepare($query);

    $statement->execute([
        'exam_id' => $id
    ]);

    $query = "DELETE FROM exam_lessons WHERE exam_id = :exam_id";
    $statement = $db->prepare($query);
    $statement->execute([
        'exam_id' => $id
    ]);

    $query = "DELETE FROM exam_locations WHERE exam_id = :exam_id";
    $statement = $db->prepare($query);
    $statement->execute([
        'exam_id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Sınav Silindi'
    ]);