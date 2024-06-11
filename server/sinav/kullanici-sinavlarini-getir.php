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

    $query = "SELECT 
                  el.user_id,
                  u.email AS user_email,
                  u.tcno,
                  u.role,
                  u.name,
                  u.surname,
                  el.exam_id,
                  e.exam_name,
                  e.exam_date,
                  el.room_id,
                  r.room_name,
                  r.capacity,
                  r.availability,
                  b.inst_id,
                  i.inst_name,
                  i.inst_address,
                  i.inst_phone,
                  r.campus_id,
                  c.campus_name,
                  c.address AS campus_address,
                  r.building_id,
                  b.building_name,
                  b.building_code,
                  r.floor_id,
                  f.floor_name,
                  f.floor_code,
                  r.creation_date
              FROM 
                  exam_locations el
              JOIN 
                  users u ON el.user_id = u.id
              JOIN 
                  exams e ON el.exam_id = e.id
              JOIN 
                  rooms r ON el.room_id = r.id
              JOIN 
                  buildings b ON r.building_id = b.id
              JOIN 
                  campus c ON r.campus_id = c.id
              JOIN 
                  institutions i ON b.inst_id = i.id
              JOIN 
                  floors f ON r.floor_id = f.id
              WHERE 
                  e.exam_status = 0
                  AND r.availability = 1
                  AND u.id= :userId;";
    $statement = $db->prepare($query);
    $statement->execute([
        'userId' => $userId
    ]);

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    //sınavın derslerini getir
    $query = "select l.id AS lesson_id, l.lesson_code, l.lesson_subject from exam_lessons el
                join lessons l on el.lesson_id = l.id
                where el.exam_id = :exam_id";
    $statement = $db->prepare($query);

    foreach ($result as $key => $value) {
        $statement->execute([
            'exam_id' => $value['exam_id']
        ]);
        $exam_lessons = $statement->fetchAll(PDO::FETCH_ASSOC);
        $result[$key]['exam_lessons'] = $exam_lessons;
    }

    echo json_encode([
        'success' => true,
        'user_exams' => $result
    ]);