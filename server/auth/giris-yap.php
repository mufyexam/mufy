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

    $tcno = $data["tcno"];
    $password = $data["password"];

    if (empty($tcno) && empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "SELECT * FROM users WHERE tcno = :tc";
    $statement = $db->prepare($query);

    $statement->execute([
        ':tc' => $tcno
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Kullanıcı bulunamadı'
        ]);
        return;
    }

    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Şifreler uyuşmuyor'
        ]);  
        return;
    }

    echo json_encode ([
        'success' => true,
        'message' => 'Giriş başarılı',
        'user' => $user
    ]);