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

    if (!isset($data["userId"]) || !isset($data["role"])) {
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

    $role = $data["role"];

    if ($role !== "admin" && $role !== "student" && $role !== "teacher") {
        echo json_encode([
            'success' => false,
            'message' => 'Rol geçersiz'
        ]);
        return;
    }

    $query = "SELECT id, email, tcno, role, creation_date, name, surname FROM users WHERE role = :role";
    $statement = $db->prepare($query);

    $statement->execute([
        'role' => $role
    ]);
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($role === "student") {
        $query = "SELECT ll.id, u.id AS user_id, u.email, u.tcno, u.role, u.creation_date, u.name, u.surname,
                l.id AS lesson_id, l.lesson_code, l.lesson_subject
                FROM lessons_learned ll
                JOIN users u ON ll.user_id = u.id
                JOIN lessons l ON ll.lesson_id = l.id;";
        $statement = $db->prepare($query);
        $statement->execute();
        $lessons = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $key => $user) {
            $users[$key]['lessons'] = [];
            foreach ($lessons as $lesson) {
                if ($lesson['user_id'] === $user['id']) {
                    $users[$key]['lessons'][] = [
                        'lesson_id' => $lesson['lesson_id'],
                        'lesson_code' => $lesson['lesson_code'],
                        'lesson_subject' => $lesson['lesson_subject']
                    ];
                }
            }
        }
    }

    echo json_encode([
        'success' => true,
        'users' => $users
    ]);