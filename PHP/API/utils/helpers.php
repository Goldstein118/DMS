<?php

function generateCustomID($prefix, $table, $column, $conn)
{
    $year = date('y'); // Get last two digits of current year
    $like_pattern = "$prefix$year-%";

    $stmt = $conn->prepare("SELECT MAX($column) AS last_id FROM $table WHERE $column LIKE ?");
    $stmt->bind_param("s", $like_pattern);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $last = $result['last_id'] ?? null;

    // Extract last 7 digits and increment
    $next = $last ? str_pad(((int)substr($last, -7)) + 1, 7, '0', STR_PAD_LEFT) : '0000001';
    
    return "$prefix$year-$next";
}

function validate_1($data, $requiredFields, $defaults = [])
{
    $output = [];
    foreach ($requiredFields as $f) {
        if (!isset($data[$f]) || trim($data[$f]) === '') {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Missing or empty: $f"]);
            exit;
        }
        $output[$f] = trim($data[$f]);
    }

    // Include optional fields (even if empty)
    foreach ($data as $key => $val) {
        if (!array_key_exists($key, $output)) {
            $output[$key] = trim($val);
        }
    }

    foreach ($defaults as $key => $val) {
        if (!isset($output[$key]) || $output[$key] === '') {
            $output[$key] = $val;
        }
    }

    return $output;
}


function validate_2($value, $pattern, $errorMsg)
{
    if (trim($value) !== '' && !preg_match($pattern, $value)) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $errorMsg]);
        exit;
    }
}


function executeInsert($conn, $query, $params, $types)
{
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }
    $stmt->close();
}
