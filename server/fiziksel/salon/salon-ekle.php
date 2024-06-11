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

    if (!isset($data["userId"]) || !isset($data["inst_id"]) || !isset($data["campus_id"]) || !isset($data["building_id"]) || !isset($data["floor_id"]) || !isset($data["room_name"]) || !isset($data["capacity"]) || !isset($data["availability"])) {
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
    $floor_id = $data["floor_id"];
    $room_name = $data["room_name"];
    $capacity = $data["capacity"];
    $availability = $data["availability"];

    if (empty($inst_id) && empty($campus_id) && empty($building_id) && empty($floor_id) && empty($room_name) && empty($capacity) && empty($availability)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "INSERT INTO rooms (inst_id, campus_id, building_id, floor_id, room_name, capacity, availability) VALUES (:inst_id, :campus_id, :building_id, :floor_id, :room_name, :capacity, :availability)";
    $statement = $db->prepare($query);

    $statement->execute([
        ':inst_id' => $inst_id,
        ':campus_id' => $campus_id,
        ':building_id' => $building_id,
        ':floor_id' => $floor_id,
        ':room_name' => $room_name,
        ':capacity' => $capacity,
        ':availability' => $availability
    ]);

    echo json_encode ([
        'success' => true,
        'message' => 'Salon başarıyla eklendi'
    ]);