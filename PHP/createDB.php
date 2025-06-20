<?php
require_once __DIR__ . '/env_loader.php';
$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

$conn = mysqli_connect($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection Failed :" . $conn->connect_error);
}
$sql = "CREATE DATABASE IF NOT EXISTS data_DB";
if (mysqli_query($conn, $sql)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}
mysqli_select_db($conn, "data_DB");
$role = "CREATE TABLE IF NOT EXISTS tb_role (
        role_id VARCHAR(20) PRIMARY KEY NOT NULL,
        nama VARCHAR(100),
        akses VARCHAR(100)
    )";

if (!$conn->query($role)) {
    die("Error creating tb_role: " . mysqli_error($conn));
}

$karyawan = "CREATE TABLE IF NOT EXISTS tb_karyawan (
    karyawan_id VARCHAR(20) PRIMARY KEY NOT NULL,
    nama VARCHAR(100) NOT NULL,
    role_id VARCHAR(20),
    departement VARCHAR(20) DEFAULT 'lainnya',
    no_telp VARCHAR(20),
    alamat VARCHAR(100),
    ktp VARCHAR(100),
    npwp VARCHAR(100),
    status VARCHAR(20) DEFAULT 'aktif',
    FOREIGN KEY (role_id) REFERENCES tb_role(role_id) ON DELETE RESTRICT
)";

if ($conn->query($karyawan)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$user = "CREATE TABLE IF NOT EXISTS tb_user (
    user_id VARCHAR(20) PRIMARY KEY NOT NULL, level VARCHAR(10) DEFAULT 'user',
    karyawan_id VARCHAR(20), FOREIGN KEY (karyawan_id) REFERENCES tb_karyawan(karyawan_id) ON DELETE RESTRICT

    )";
if ($conn->query($user)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$supplier = "CREATE TABLE IF NOT EXISTS tb_supplier (
        supplier_id VARCHAR(20) PRIMARY KEY NOT NULL, 
        nama VARCHAR(100) NOT NULL,
        alamat VARCHAR(100),
        no_telp VARCHAR(20),
        ktp VARCHAR(100),
        npwp VARCHAR(100),
        status VARCHAR(20) DEFAULT 'aktif'
        )";
if ($conn->query($supplier)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$customer = "CREATE TABLE IF NOT EXISTS tb_customer (
        customer_id VARCHAR(20) PRIMARY KEY NOT NULL, 
        nama VARCHAR(100) NOT NULL,
        alamat VARCHAR(100),
        no_telp VARCHAR(20),
        ktp VARCHAR(100),
        npwp VARCHAR(100),
        status VARCHAR(20) DEFAULT 'aktif', 
        nitko VARCHAR(100), 
        term_pembayaran VARCHAR(100),
        max_invoice VARCHAR(20), 
        max_piutang VARCHAR(20),
        latitude DECIMAL(9,6),
        longitude DECIMAL (9,6),
        channel_id VARCHAR(20), FOREIGN KEY (channel_id) REFERENCES tb_channel(channel_id) ON DELETE RESTRICT,
        pricelist_id VARCHAR (20), FOREIGN KEY (pricelist_id) REFERENCES tb_pricelist(pricelist_id) ON DELETE RESTRICT
        )";
if ($conn->query($customer)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$channel = "CREATE TABLE IF NOT EXISTS tb_channel(
            channel_id VARCHAR(20) PRIMARY KEY NOT NULL, 
            nama VARCHAR(100)
)";
if ($conn->query($channel)) {
    try {
    } catch (Error) {
        mysqli_error($conn);
    }
}

$kategori = "CREATE TABLE IF NOT EXISTS tb_kategori(
kategori_id VARCHAR(20) PRIMARY KEY NOT NULL, 
nama VARCHAR(100)
)";
if ($conn->query($kategori)) {
    try {
    } catch (Error) {
        mysqli_error($conn);
    }
}
$brand = "CREATE TABLE IF NOT EXISTS tb_brand(
brand_id VARCHAR(20) PRIMARY KEY NOT NULL,
nama VARCHAR(100)
)";
if ($conn->query($brand)) {
    try {
    } catch (Error) {
        mysqli_error($conn);
    }
}

$produk = "CREATE TABLE IF NOT EXISTS tb_produk(
produk_id VARCHAR(20) PRIMARY KEY NOT NULL, 
nama VARCHAR(100),
no_sku VARCHAR(100),
status VARCHAR(20) DEFAULT 'aktif',
harga_minimal VARCHAR(20),
kategori_id VARCHAR(20),
brand_id VARCHAR(20),
FOREIGN KEY (kategori_id) REFERENCES tb_kategori(kategori_id) ON DELETE RESTRICT,
FOREIGN KEY (brand_id) REFERENCES tb_brand(brand_id) ON DELETE RESTRICT
)";

if ($conn->query($produk)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


$divisi = "CREATE TABLE IF NOT EXISTS tb_divisi(
divisi_id VARCHAR(20) PRIMARY KEY NOT NULL,
nama VARCHAR (50),
bank VARCHAR(20),
nama_rekening VARCHAR (50),
no_rekening VARCHAR (30))";

if ($conn->query($divisi)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$gambar = "CREATE TABLE IF NOT EXISTS tb_gambar (
    gambar_id VARCHAR(20) PRIMARY KEY NOT NULL,
    tipe ENUM('ktp', 'npwp') NOT NULL,
    customer_id VARCHAR(20) NOT NULL,
    internal_link VARCHAR(255),
    external_link VARCHAR(255),
    blob_data LONGBLOB,
    FOREIGN KEY (customer_id) REFERENCES tb_customer(customer_id) ON DELETE RESTRICT
)";
if ($conn->query($gambar)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$gudang = "CREATE TABLE IF NOT EXISTS tb_gudang(
gudang_id VARCHAR (20) PRIMARY KEY NOT NULL,
nama VARCHAR(50),
status VARCHAR(20) DEFAULT 'aktif'
)";
if ($conn->query($gudang)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$pricelist ="CREATE TABLE IF NOT EXISTS tb_pricelist(
pricelist_id VARCHAR(20) PRIMARY KEY NOT NULL,
nama VARCHAR (50),
harga_default VARCHAR(10) DEFAULT 'ya',
status VARCHAR(20) DEFAULT 'aktif',
tanggal_berlaku DATE
)";

if ($conn->query($pricelist)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$detail_pricelist ="CREATE TABLE IF NOT EXISTS tb_detail_pricelist(
detail_pricelist_id VARCHAR(20) PRIMARY KEY NOT NULL,
harga VARCHAR(20),
pricelist_id VARCHAR(20),
produk_id VARCHAR(20),
FOREIGN KEY (pricelist_id) REFERENCES tb_pricelist(pricelist_id) ON DELETE RESTRICT,
FOREIGN KEY (produk_id) REFERENCES tb_produk(produk_id) ON DELETE RESTRICT 
)";

if ($conn->query($detail_pricelist)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}
mysqli_close($conn);
