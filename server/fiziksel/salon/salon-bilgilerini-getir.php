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
                INNER JOIN floors ON rooms.floor_id = floors.id;";
    $statement = $db->prepare($query);

    $statement->execute();

    $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'rooms' => $rooms
    ]);
