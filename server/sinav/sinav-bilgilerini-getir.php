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
    if (!authorize($userId, $db, "admin")) {
        echo json_encode([
            'success' => false,
            'message' => 'Yetkisiz Kullanıcı'
        ]);
        return;
    }

    $query = "select * from exams";
    $statement = $db->prepare($query);

    $statement->execute();
    $exams = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($exams as $key => $exam) {
        $query = "select l.id AS lesson_id, l.lesson_code, l.lesson_subject from exam_lessons el
                join lessons l on el.lesson_id = l.id
                where el.exam_id = :exam_id";
        $statement = $db->prepare($query);
        $statement->execute([
            'exam_id' => $exam['id']
        ]);
        $lessons = $statement->fetchAll(PDO::FETCH_ASSOC);
        $exams[$key]['exam_lessons'] = $lessons;
    }

    echo json_encode([
        'success' => true,
        'exams' => $exams
    ]);