<?php

require_once '../cek_akses.php'; // To reuse your existing `checkAccess()` function
function checkContextAccess($conn, $userId, $context) {
    $dependencyMap = [
        'tb_user'     => ['tb_karyawan'],
        'tb_karyawan' => ['tb_role'],
        'tb_customer' =>['tb_channel'],
        'tb_produk'   => ['tb_kategori', 'tb_brand'],

        // Add more mappings as needed
    ];

    $tableActionBaseIndex = [
        'tb_karyawan' => 0,
        'tb_user'     => 4,
        'tb_role'     => 8,
        'tb_supplier' => 12,
        'tb_customer' => 16,
        'tb_channel'  => 20,
        'tb_kategori' => 24,
        'tb_brand'    => 28,
        'tb_produk'   => 32,
        // Add more tables and their base indices here
    ];

    $actionOffsetMap = [
        'view'   => 0,
        'create' => 1,
        'edit'   => 2,
        'delete' => 3,
    ];

    $target       = $context['target'] ?? '';
    $relatedTable = $context['table'] ?? '';
    $action       = $context['action'] ?? 'view';

    if (!isset($dependencyMap[$target])) return false;
    if (!in_array($relatedTable, $dependencyMap[$target])) return false;
    if (!isset($actionOffsetMap[$action])) return false;
    if (!isset($tableActionBaseIndex[$relatedTable])) return false;

    $permissionIndex = $tableActionBaseIndex[$relatedTable] + $actionOffsetMap[$action];

    try {
        checkAccess($conn, $userId, $target, $permissionIndex);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

?>