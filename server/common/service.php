<?php

    function isEmailValid($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function getFullDate() {
        $current_time = time();
        $formatted_date = date("d.m.Y H:i", $current_time);
    
        return $formatted_date;
    }

    function authorize($userId, $db, $role) {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute([
            ':id' => $userId
        ]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return false;
        }

        if ($user["role"] !== $role) {
            return false;
        }

        return true;
    }