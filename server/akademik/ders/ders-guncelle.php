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

    if (!isset($data["userId"]) || !isset($data["id"]) || !isset($data["lesson_code"]) || !isset($data["lesson_subject"])) {
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
    $lesson_code = $data["lesson_code"];
    $lesson_subject = $data["lesson_subject"];

    if (empty($id) && empty($lesson_code) && empty($lesson_subject)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "UPDATE lessons SET lesson_code = :lesson_code, lesson_subject = :lesson_subject WHERE id = :id";
    $statement = $db->prepare($query);

    $statement->execute([
        ':id' => $id,
        ':lesson_code' => $lesson_code,
        ':lesson_subject' => $lesson_subject
    ]);

    echo json_encode ([
        'success' => true,
        'message' => 'Ders başarıyla güncellendi'
    ]);