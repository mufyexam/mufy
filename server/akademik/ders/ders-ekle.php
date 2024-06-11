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

    if (!isset($data["userId"]) || !isset($data["lesson_code"]) || !isset($data["lesson_subject"])) {
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

    $lesson_code = $data["lesson_code"];
    $lesson_subject = $data["lesson_subject"];

    if (empty($lesson_code) && empty($lesson_subject)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "INSERT INTO lessons (lesson_code, lesson_subject) VALUES (:lesson_code, :lesson_subject)";
    $statement = $db->prepare($query);

    $statement->execute([
        ':lesson_code' => $lesson_code,
        ':lesson_subject' => $lesson_subject
    ]);

    echo json_encode ([
        'success' => true,
        'message' => 'Ders başarıyla eklendi'
    ]);