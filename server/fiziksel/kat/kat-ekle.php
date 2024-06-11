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

    if (!isset($data["inst_id"]) || !isset($data["campus_id"]) || !isset($data["building_id"]) || !isset($data["floor_name"]) || !isset($data["floor_code"]) || !isset($data["userId"])) {
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
    $building_id = $data["building_id"];
    $floor_name = $data["floor_name"];
    $floor_code = $data["floor_code"];

    if (empty($inst_id) && empty($campus_id) && empty($building_id) && empty($floor_name) && empty($floor_code)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "INSERT INTO floors (inst_id, campus_id, building_id, floor_name, floor_code) VALUES (:inst_id, :campus_id, :building_id, :floor_name, :floor_code)";
    $statement = $db->prepare($query);

    $statement->execute([
        ':inst_id' => $inst_id,
        ':campus_id' => $campus_id,
        ':building_id' => $building_id,
        ':floor_name' => $floor_name,
        ':floor_code' => $floor_code
    ]);

    echo json_encode ([
        'success' => true,
        'message' => 'Kat başarıyla eklendi'
    ]);