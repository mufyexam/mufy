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

    if (!isset($data["userId"]) || !isset($data["id"]) || !isset($data["email"]) || !isset($data["tcno"]) || !isset($data["name"]) || !isset($data["surname"]) || !isset($data["role"])) {
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
    $email = $data["email"];
    $tcno = $data["tcno"];
    $name = $data["name"];
    $surname = $data["surname"];
    $role = $data["role"];

    if (!isEmailValid($email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email geçersiz'
        ]);
        return;
    }

    if (empty($id) && empty($email) && empty($tcno) && empty($name) && empty($surname)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    if ($role !== "admin" && $role !== "student" && $role !== "teacher") {
        echo json_encode([
            'success' => false,
            'message' => 'Rol geçersiz'
        ]);
        return;
    }

    $query = "UPDATE users SET email = :email, tcno = :tcno, name = :name, surname = :surname, role = :role WHERE id = :id";
    $statement = $db->prepare($query);

    $statement->execute([
        'id' => $id,
        'email' => $email,
        'tcno' => $tcno,
        'name' => $name,
        'surname' => $surname,
        'role' => $role
    ]);

    if ($role === "student") {
        if (!isset($data["lessons"]) && empty($data["lessons"])) {
            echo json_encode([
                'success' => false,
                'message' => 'Lütfen tüm boşlukları doldurunuz'
            ]);
            return;
        }

        $lessons = $data["lessons"];
        $query = "DELETE FROM lessons_learned WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->execute([
            'user_id' => $id
        ]);

        foreach ($lessons as $lesson) {
            $query = "INSERT INTO lessons_learned (user_id, lesson_id) VALUES (:user_id, :lesson_id)";
            $statement = $db->prepare($query);
            $statement->execute([
                'user_id' => $id,
                'lesson_id' => $lesson['lesson_id']
            ]);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Kullanıcı Güncellendi'
    ]);