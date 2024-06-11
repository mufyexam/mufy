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

    $query = "SELECT floors.id,
                buildings.inst_id,
                institutions.inst_name,
                floors.campus_id,
                campus.campus_name,
                floors.building_id,
                buildings.building_name,
                floors.floor_name,
                floors.floor_code,
                floors.creation_date
            FROM floors
            INNER JOIN buildings ON floors.building_id = buildings.id
            INNER JOIN campus ON floors.campus_id = campus.id
            INNER JOIN institutions ON buildings.inst_id = institutions.id;";
    $statement = $db->prepare($query);

    $statement->execute();

    $floors = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'floors' => $floors
    ]);