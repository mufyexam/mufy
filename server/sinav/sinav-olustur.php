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

    if (!isset($data["userId"]) || !isset($data["exam_name"]) || !isset($data["exam_date"]) || !isset($data["exam_lessons"])) {
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

    $exam_name = $data["exam_name"];
    $exam_date = $data["exam_date"];
    $exam_lessons = $data["exam_lessons"];

    if (empty($exam_name) || empty($exam_date) || empty($exam_lessons)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $formattedDate = DateTime::createFromFormat('Y-m-d H:i:s', $exam_date);

    $query = "INSERT INTO exams (exam_name, exam_date, exam_status) VALUES (:exam_name, :exam_date, 1)";
    $statement = $db->prepare($query);
    
    $statement->execute([
        'exam_name' => $exam_name,
        'exam_date' => $formattedDate->format('Y-m-d H:i:s')
    ]);

    $examId = $db->lastInsertId();

    foreach ($exam_lessons as $lesson) {
        $query = "INSERT INTO exam_lessons (exam_id, lesson_id) VALUES (:exam_id, :lesson_id)";
        $statement = $db->prepare($query);
        
        $statement->execute([
            'exam_id' => $examId,
            'lesson_id' => $lesson['id']
        ]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Sınav başarıyla oluşturuldu'
    ]);