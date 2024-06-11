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
    
    if (!isset($data["userId"]) || !isset($data["inst_id"]) || !isset($data["campus_id"]) || !isset($data["building_name"]) || !isset($data["building_code"])) {
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

    $inst_id = $data["inst_id"];
    $campus_id = $data["campus_id"];
    $building_name = $data["building_name"];
    $building_code = $data["building_code"];

    if (empty($inst_id) && empty($campus_id) && empty($building_name) && empty($building_code)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "INSERT INTO buildings (inst_id, campus_id, building_name, building_code) VALUES (:inst_id, :campus_id, :building_name, :building_code)";
    $statement = $db->prepare($query);

    $statement->execute([
        ':inst_id' => $inst_id,
        ':campus_id' => $campus_id,
        ':building_name' => $building_name,
        ':building_code' => $building_code
    ]);

    echo json_encode ([
        'success' => true,
        'message' => 'Bina başarıyla eklendi'
    ]);