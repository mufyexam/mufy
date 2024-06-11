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

    if (!isset($data["userId"]) || !isset($data["exam"]) || !isset($data["selectedRooms"]) || !isset($data["selectedTeachers"])) {
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

    $exam = $data["exam"];
    $selectedRooms = $data["selectedRooms"];
    $selectedTeachers = $data["selectedTeachers"];

    if (empty($exam) || empty($selectedRooms) || empty($selectedTeachers)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $lessons = array();

    foreach ($exam['exam_lessons'] as $lesson) {
        $lessons[] = $lesson['lesson_id'];
    }

    $lesson_ids = implode(",", $lessons);

    $query = "SELECT DISTINCT user_id
            FROM lessons_learned
            WHERE lesson_id IN ($lesson_ids);";
    $statement = $db->prepare($query);
    $statement->execute();
    $students = $statement->fetchAll(PDO::FETCH_ASSOC);

    $students = array_map(function($student) {
        return $student['user_id'];
    }, $students);
    $students = array_values($students);

    foreach ($selectedRooms as $room) {
        for ($i = 0; $i < $room['capacity']; $i++) {
            if (count($students) == 0) {
                break;
            }
            $randomIndex = rand(0, count($students) - 1);
            $studentId = $students[$randomIndex];

            $query = "INSERT INTO exam_locations (user_id, exam_id, room_id)
                    VALUES (:user_id, :exam_id, :room_id)";
            $statement = $db->prepare($query);
            $statement->execute([
                'user_id' => $studentId,
                'exam_id' => $exam['id'],
                'room_id' => $room['id']
            ]);

            unset($students[$randomIndex]);
            $students = array_values($students);
        }
    }

    foreach ($selectedRooms as $room) {
        if (count($selectedTeachers) == 0) {
            break;
        }

        $randomIndex = rand(0, count($selectedTeachers) - 1);
        $teacherId = $selectedTeachers[$randomIndex]['id'];

        $query = "INSERT INTO exam_locations (user_id, exam_id, room_id)
                VALUES (:user_id, :exam_id, :room_id)";
        $statement = $db->prepare($query);
        $statement->execute([
            'user_id' => $teacherId,
            'exam_id' => $exam['id'],
            'room_id' => $room['id']
        ]);

        unset($selectedTeachers[$randomIndex]);
    }
    
    $query = "UPDATE exams
                SET exam_status = 0
                WHERE id = :exam_id";
    $statement = $db->prepare($query);
    $statement->execute([
        'exam_id' => $exam['id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Sınav Başarıyla Başlatıldı'
    ]);