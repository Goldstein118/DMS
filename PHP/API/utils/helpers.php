<?php
function generateCustomID($prefix, $table, $column, $conn) {
    $month = date('m');
    $year = date('y');
    $like_pattern = "$prefix$month$year-%";

    $stmt = $conn->prepare("SELECT MAX($column) AS last_id FROM $table WHERE $column LIKE ?");
    $stmt->bind_param("s", $like_pattern);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $last = $result['last_id'] ?? null;

    $next = $last ? str_pad(((int)substr($last, -3)) + 1, 3, '0', STR_PAD_LEFT) : '001';
    return "$prefix$month$year-$next";
}

function validate_1($data, $fields, $defaults = []) {
    $output = [];
    foreach ($fields as $f) {
        if (!isset($data[$f]) || trim($data[$f]) === '') {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Missing or empty: $f"]);
            exit;
        }
        $output[$f] = trim($data[$f]);
    }
    foreach ($defaults as $key => $val) {
        if (!isset($output[$key]) || $output[$key] === '') {
            $output[$key] = $val;
        }
    }
    return $output;
}

function validate_2($value, $pattern, $errorMsg) {
    if (!preg_match($pattern, $value)) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $errorMsg]);
        exit;
    }
}
?>