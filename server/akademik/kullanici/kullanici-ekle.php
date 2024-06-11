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

    if (!isset($data["userId"]) || !isset($data["email"]) || !isset($data["password"]) || !isset($data["tcno"]) || !isset($data["name"]) || !isset($data["surname"]) || !isset($data["role"])) {
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

    $email = $data["email"];
    $password = $data["password"];
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

    if (empty($email) && empty($password) && empty($tcno) && empty($name) && empty($surname)) {
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

    $query = "INSERT INTO users (id, email, password, tcno, name, surname, role) VALUES (:id, :email, :password, :tcno, :name, :surname, :role)";
    $statement = $db->prepare($query);

    $statement->execute([
        ':id' => uniqid('user_'),
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_BCRYPT),
        ':tcno' => $tcno,
        ':name' => $name,
        ':surname' => $surname,
        ':role' => strtolower($role),
    ]);

    if ($role === "student") {
        if (!isset($data["selectedLessons"])) {
            echo json_encode([
                'success' => false,
                'message' => 'Dersler belirtilmedi'
            ]);
            return;
        }
        
        $selectedLessons = $data["selectedLessons"];
        if (!is_array($selectedLessons)) {
            echo json_encode([
                'success' => false,
                'message' => 'Dersler geçersiz'
            ]);
            return;
        }

        foreach ($selectedLessons as $lesson) {
            if (!isset($lesson["id"])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dersler geçersiz'
                ]);
                return;
            }

            $query = "select * from users where tcno = :tcno";
            $statement = $db->prepare($query);
            $statement->execute([
                ':tcno' => $tcno
            ]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);
                
            $lessonId = $lesson["id"];
            
            $query = "INSERT INTO lessons_learned (user_id, lesson_id) VALUES (:user_id, :lesson_id)";
            $statement = $db->prepare($query);
            $statement->execute([
                ':user_id' => $user['id'],
                ':lesson_id' => $lessonId
            ]);
        }
    }
    

    echo json_encode([
        'success' => true,
        'message' => 'Kullanıcı Oluşturuldu.'
    ]);