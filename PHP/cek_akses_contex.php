<?php

require_once '../cek_akses.php'; // To reuse your existing `checkAccess()` function
function checkContextAccess($conn, $userId, $context)
{
    $dependencyMap = [
        'tb_user'     => ['tb_karyawan'],
        'tb_karyawan' => ['tb_role'],
        'tb_customer' => ['tb_channel', 'tb_pricelist'],
        'tb_produk'   => ['tb_kategori', 'tb_brand', 'tb_pricelist', 'tb_satuan'],
        'tb_pricelist' => ['tb_produk'],
        'tb_armada'  => ['tb_karyawan'],
        'tb_promo'   => ['tb_brand', 'tb_customer', 'tb_produk', 'tb_channel', 'tb_satuan'],
        'tb_pembelian' => ['tb_supplier', 'tb_satuan', 'tb_produk', 'tb_data_biaya'],
        'tb_invoice' => ['tb_pembelian']

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
        'tb_divisi'   => 36,
        'tb_gudang'   => 40,
        'tb_pricelist' => 44,
        'tb_armada'   => 48,
        'tb_frezzer'  => 52,
        'tb_promo'    => 56,
        'tb_satuan'   => 60,
        'tb_pembelian' => 64,
        'tb_data_biaya' => 68,
        'tb_invoice' => 72,
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
