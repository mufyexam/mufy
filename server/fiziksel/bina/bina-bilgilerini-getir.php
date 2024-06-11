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

    $query = "SELECT buildings.id,
                buildings.inst_id,
                institutions.inst_name,
                buildings.campus_id,
                campus.campus_name,
                buildings.building_name,
                buildings.building_code,
                buildings.creation_date
            FROM buildings
            INNER JOIN campus ON buildings.campus_id = campus.id
            INNER JOIN institutions ON buildings.inst_id = institutions.id;";
    $statement = $db->prepare($query);

    $statement->execute();
    $buildings = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'buildings' => $buildings
    ]);