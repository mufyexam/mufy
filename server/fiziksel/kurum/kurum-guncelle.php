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

    if (!isset($data["id"]) || !isset($data["inst_name"]) || !isset($data["inst_city"]) || !isset($data["inst_county"]) || !isset($data["inst_address"]) || !isset($data["inst_phone"]) || !isset($data["main_inst"])) {
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
    $inst_name = $data["inst_name"];
    $inst_city = $data["inst_city"];
    $inst_county = $data["inst_county"];
    $inst_address = $data["inst_address"];
    $inst_phone = $data["inst_phone"];
    $main_inst = $data["main_inst"];

    if (empty($id) && empty($inst_name) && empty($inst_city) && empty($inst_county) && empty($inst_address) && empty($inst_phone) && empty($main_inst)) {
        echo json_encode([
            'success' => false,
            'message' => 'Lütfen tüm boşlukları doldurunuz'
        ]);
        return;
    }

    $query = "UPDATE institutions SET inst_name = :inst_name, inst_city = :inst_city, inst_county = :inst_county, inst_address = :inst_address, inst_phone = :inst_phone, main_inst = :main_inst WHERE id = :id";
    $statement = $db->prepare($query);

    $statement->execute([
        ':id' => $id,
        ':inst_name' => $inst_name,
        ':inst_city' => $inst_city,
        ':inst_county' => $inst_county,
        ':inst_address' => $inst_address,
        ':inst_phone' => $inst_phone,
        ':main_inst' => $main_inst
    ]);

    echo json_encode ([
        'success' => true,
        'message' => 'Kurum başarıyla güncellendi'
    ]);