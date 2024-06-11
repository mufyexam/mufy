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

    if (!isset($data["inst_id"]) || !isset($data["campus_name"]) || !isset($data["city"]) || !isset($data["county"]) || !isset($data["address"]) || !isset($data["userId"])) {
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
    $campus_name = $data["campus_name"];
    $city = $data["city"];
    $county = $data["county"];
    $address = $data["address"];

    if (empty($inst_id) && empty($campus_name) && empty($city) && empty($county) && empty($address)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "INSERT INTO campus (inst_id, campus_name, city, county, address) VALUES (:inst_id, :campus_name, :city, :county, :address)";
    $statement = $db->prepare($query);

    $statement->execute([
        ':inst_id' => $inst_id,
        ':campus_name' => $campus_name,
        ':city' => $city,
        ':county' => $county,
        ':address' => $address
    ]);

    echo json_encode ([
        'success' => true,
        'message' => 'Kampüs başarıyla eklendi'
    ]);