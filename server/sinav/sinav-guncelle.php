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

    if (!isset($data["userId"]) || !isset($data["id"]) || !isset($data["exam_name"]) || !isset($data["exam_date"]) || !isset($data["exam_lessons"])) {
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
    $exam_name = $data["exam_name"];
    $exam_date = $data["exam_date"];
    $exam_lessons = $data["exam_lessons"];

    if (empty($id) || empty($exam_name) || empty($exam_date) || empty($exam_lessons)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $formattedDate = DateTime::createFromFormat('Y-m-d H:i:s', $exam_date);
    
    $query = "UPDATE exams SET exam_name = :exam_name, exam_date = :exam_date WHERE id = :exam_id";
    $statement = $db->prepare($query);

    $statement->execute([
        'exam_id' => $id,
        'exam_name' => $exam_name,
        'exam_date' => $formattedDate->format('Y-m-d H:i:s'),
    ]);

    $query = "DELETE FROM exam_lessons WHERE exam_id = :exam_id";
    $statement = $db->prepare($query);
    $statement->execute([
        'exam_id' => $id
    ]);

    foreach ($exam_lessons as $lesson) {
        $query = "INSERT INTO exam_lessons (exam_id, lesson_id) VALUES (:exam_id, :lesson_id)";
        $statement = $db->prepare($query);
        
        $statement->execute([
            'exam_id' => $id,
            'lesson_id' => $lesson['id']
        ]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Sınav başarıyla güncellendi'
    ]);