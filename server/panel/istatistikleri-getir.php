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

    $query = "SELECT COUNT(*) as studentCount FROM users WHERE role = 'student'";
    $statement = $db->prepare($query);
    $statement->execute();
    $studentCount = $statement->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT COUNT(*) as teacherCount FROM users WHERE role = 'teacher'";
    $statement = $db->prepare($query);
    $statement->execute();
    $teacherCount = $statement->fetch(PDO::FETCH_ASSOC);

    // aktif salon sayısı
    $query = "SELECT COUNT(*) as activeClassroomCount FROM rooms WHERE availability = 1";
    $statement = $db->prepare($query);
    $statement->execute();
    $activeClassroomCount = $statement->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'statistics' => [
            'studentCount' => $studentCount['studentCount'],
            'teacherCount' => $teacherCount['teacherCount'],
            'activeClassroomCount' => $activeClassroomCount['activeClassroomCount']
        ]
    ]);