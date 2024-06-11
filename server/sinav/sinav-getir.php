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

    if (!isset($data["userId"]) || !isset($data["examId"])) {
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

    $exam_id = $data["examId"];

    if (empty($exam_id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "select * from exams where id = :exam_id";
    $statement = $db->prepare($query);

    $statement->execute([
        'exam_id' => $exam_id
    ]);
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

    $lessons = array();

    foreach ($exams[0]['exam_lessons'] as $lesson) {
        $lessons[] = $lesson['lesson_id'];
    }

    $lesson_ids = implode(",", $lessons);

    $query = "SELECT COUNT(DISTINCT user_id) AS students_count
                FROM lessons_learned
                WHERE lesson_id IN ($lesson_ids)";
    $statement = $db->prepare($query);
    $statement->execute();
    $students_count = $statement->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT rooms.id,
                    buildings.inst_id,
                    institutions.inst_name,
                    rooms.campus_id,
                    campus.campus_name,
                    rooms.building_id,
                    buildings.building_name,
                    rooms.floor_id,
                    floors.floor_name,
                    rooms.room_name,
                    rooms.capacity,
                    rooms.availability,
                    rooms.creation_date
                FROM rooms
                INNER JOIN buildings ON rooms.building_id = buildings.id
                INNER JOIN campus ON rooms.campus_id = campus.id
                INNER JOIN institutions ON buildings.inst_id = institutions.id
                INNER JOIN floors ON rooms.floor_id = floors.id WHERE availability = 1";
    $statement = $db->prepare($query);
    $statement->execute();
    $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

    $query = "select id, email, tcno, role, creation_date, name, surname from users where role = 'teacher'";
    $statement = $db->prepare($query);
    $statement->execute();
    $teachers = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'exam' => $exams,
        'students_count' => $students_count[0]['students_count'],
        'active_rooms' => $rooms,
        'teachers' => $teachers
    ]);