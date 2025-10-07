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
    alamat VARCHAR(255),
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
        alamat VARCHAR(255),
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
        alamat VARCHAR(255),
        no_telp VARCHAR(20),
        ktp VARCHAR(100),
        npwp VARCHAR(100),
        status VARCHAR(20) DEFAULT 'aktif', 
        nitko VARCHAR(100), 
        term_pembayaran INT,
        max_invoice INT, 
        max_piutang DECIMAL(20,2),
        latitude DECIMAL(9,6),
        longitude DECIMAL (9,6),
        channel_id VARCHAR(20), FOREIGN KEY (channel_id) REFERENCES tb_channel(channel_id) ON DELETE RESTRICT,
        pricelist_id VARCHAR (20), FOREIGN KEY (pricelist_id) REFERENCES tb_pricelist(pricelist_id) ON DELETE RESTRICT,
        jenis_customer VARCHAR(20),
        nama_jalan VARCHAR(255),
        rt VARCHAR(255),
        kelurahan VARCHAR(255),
        kecamatan VARCHAR(255)
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
harga_minimal DECIMAL(12,2),
kategori_id VARCHAR(20),
brand_id VARCHAR(20),
stock_awal INT,
satuan_id VARCHAR(20),
FOREIGN KEY (kategori_id) REFERENCES tb_kategori(kategori_id) ON DELETE RESTRICT,
FOREIGN KEY (brand_id) REFERENCES tb_brand(brand_id) ON DELETE RESTRICT,
FOREIGN KEY (satuan_id) REFERENCES tb_satuan(satuan_id) ON DELETE RESTRICT
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

$pricelist = "CREATE TABLE IF NOT EXISTS tb_pricelist(
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

$detail_pricelist = "CREATE TABLE IF NOT EXISTS tb_detail_pricelist(
detail_pricelist_id VARCHAR(20) PRIMARY KEY NOT NULL,
harga DECIMAL(20,2),
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

$gambar_produk = "CREATE TABLE IF NOT EXISTS tb_gambar_produk (
gambar_produk_id VARCHAR(20) PRIMARY KEY NOT NULL,
produk_id VARCHAR(20),
internal_link VARCHAR(255),
external_link VARCHAR(255),
blob_data LONGBLOB,
FOREIGN KEY (produk_id) REFERENCES tb_produk(produk_id) ON DELETE RESTRICT)";

if ($conn->query($gambar_produk)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$armada = "CREATE TABLE IF NOT EXISTS tb_armada (armada_id VARCHAR (20)PRIMARY KEY NOT NULL,
        nama VARCHAR(50),karyawan_id VARCHAR (20),FOREIGN KEY (karyawan_id) REFERENCES tb_karyawan(karyawan_id) ON DELETE RESTRICT)";

if ($conn->query($armada)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$frezzer = "CREATE TABLE IF NOT EXISTS tb_frezzer (frezzer_id VARCHAR(20),kode_barcode VARCHAR(20),tipe VARCHAR(50),status VARCHAR (50) DEFAULT 'ready',
merek VARCHAR (20),size VARCHAR(20))";
if ($conn->query($frezzer)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$promo = "CREATE TABLE IF NOT EXISTS tb_promo (promo_id VARCHAR(20) PRIMARY KEY NOT NULL, nama VARCHAR (50),
        tanggal_berlaku DATE ,tanggal_selesai DATE, jenis_bonus VARCHAR (20) DEFAULT 'barang',
        akumulasi VARCHAR(20),prioritas INT,created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,jenis_diskon VARCHAR(20),
        jumlah_diskon DECIMAL(20,2),quota INT, status VARCHAR(20) DEFAULT 'aktif',satuan_id VARCHAR(20), 
        FOREIGN KEY (satuan_id) REFERENCES tb_satuan(satuan_id) ON DELETE RESTRICT)";

if ($conn->query($promo)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$promo_kondisi = "CREATE TABLE IF NOT EXISTS tb_promo_kondisi 
                  (promo_kondisi_id VARCHAR(20) PRIMARY KEY NOT NULL,promo_id VARCHAR(20),
                  jenis_kondisi VARCHAR (20),kondisi JSON,
                  qty_akumulasi INT, qty_min INT,exclude_include VARCHAR(20),
                  qty_max INT,FOREIGN KEY (promo_id) REFERENCES tb_promo(promo_id) ON DELETE RESTRICT)";
if ($conn->query($promo_kondisi)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$promo_bonus_barang = "CREATE TABLE IF NOT EXISTS tb_promo_bonus_barang(promo_bonus_barang_id VARCHAR(20) PRIMARY KEY NOT NULL,
                       promo_id VARCHAR(20),qty_bonus INT,jenis_diskon VARCHAR(20), jlh_diskon DECIMAL(20,2),
                       produk_id VARCHAR(20),FOREIGN KEY (promo_id) REFERENCES tb_promo(promo_id) ON DELETE RESTRICT)";

if ($conn->query($promo_bonus_barang)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$satuan = "CREATE TABLE IF NOT EXISTS tb_satuan(satuan_id VARCHAR (20) PRIMARY KEY NOT NULL, nama VARCHAR (20),id_referensi VARCHAR(20),qty INT)";

if ($conn->query($satuan)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$pembelian = "CREATE TABLE IF NOT EXISTS tb_pembelian(pembelian_id VARCHAR(20) PRIMARY KEY NOT NULL,tanggal_po DATE,tanggal_pengiriman DATE,tanggal_terima DATE,
supplier_id VARCHAR(20),gudang_id VARCHAR(20),keterangan VARCHAR(255),no_pengiriman VARCHAR(20),total_qty INT,ppn DECIMAL(20,2),nominal_ppn DECIMAL(20,2),diskon DECIMAL(20,2),
nominal_pph DECIMAL(20,2),biaya_tambahan DECIMAL(20,2),sub_total DECIMAL(20,2),grand_total DECIMAL(20,2),created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
created_by VARCHAR(50), status VARCHAR (20),keterangan_cancel VARCHAR(255),cancel_by VARCHAR (100),
FOREIGN KEY (supplier_id) REFERENCES tb_supplier(supplier_id) ON DELETE RESTRICT,FOREIGN KEY (gudang_id) REFERENCES tb_gudang(gudang_id) ON DELETE RESTRICT)
";

if ($conn->query($pembelian)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


$detail_pembelian = "CREATE TABLE IF NOT EXISTS tb_detail_pembelian(detail_pembelian_id VARCHAR(20) PRIMARY KEY NOT NULL,pembelian_id VARCHAR(20), produk_id VARCHAR(20),urutan INT,
qty INT,harga DECIMAL(20,2),diskon DECIMAL(20,2),satuan_id VARCHAR(20),
FOREIGN KEY (pembelian_id) REFERENCES tb_pembelian(pembelian_id) ON DELETE RESTRICT,
FOREIGN KEY (satuan_id) REFERENCES tb_satuan(satuan_id) ON DELETE RESTRICT)";

if ($conn->query($detail_pembelian)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


$data_biaya = "CREATE TABLE IF NOT EXISTS tb_data_biaya(data_biaya_id VARCHAR(20) PRIMARY KEY NOT NULL,nama VARCHAR(50))";

if ($conn->query($data_biaya)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$biaya_tambahan = "CREATE TABLE IF NOT EXISTS tb_biaya_tambahan(biaya_tambahan_id VARCHAR(20) PRIMARY KEY NOT NULL,data_biaya_id VARCHAR(20),pembelian_id VARCHAR(20),
keterangan VARCHAR(255),jlh DECIMAL(20,2),urutan INT,
FOREIGN KEY (data_biaya_id) REFERENCES tb_data_biaya(data_biaya_id) ON DELETE RESTRICT,FOREIGN KEY (pembelian_id) REFERENCES tb_pembelian(pembelian_id) ON DELETE RESTRICT)";

if ($conn->query($biaya_tambahan)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$invoice = "CREATE TABLE IF NOT EXISTS tb_invoice(
  invoice_id VARCHAR(20) PRIMARY KEY NOT NULL,
  tanggal_invoice DATE,
  no_invoice_supplier VARCHAR(20),
  tanggal_input_invoice DATE,
  tanggal_po DATE,
  tanggal_pengiriman DATE,
  tanggal_terima DATE,
  supplier_id VARCHAR(20),
  gudang_id VARCHAR(20),
  pembelian_id VARCHAR(20),
  keterangan VARCHAR(255),
  no_pengiriman VARCHAR(20),
  total_qty INT,
  ppn DECIMAL(20,2),
  nominal_ppn DECIMAL(20,2),
  diskon DECIMAL(20,2),
  nominal_pph DECIMAL(20,2),
  biaya_tambahan DECIMAL(20,2),
  sub_total DECIMAL(20,2),
  grand_total DECIMAL(20,2),
  created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by VARCHAR(50),
  status VARCHAR(20),
  FOREIGN KEY (supplier_id) REFERENCES tb_supplier(supplier_id) ON DELETE RESTRICT,
  FOREIGN KEY (pembelian_id) REFERENCES tb_pembelian(pembelian_id) ON DELETE RESTRICT,
  FOREIGN KEY (gudang_id) REFERENCES tb_gudang(gudang_id) ON DELETE RESTRICT
)";

if ($conn->query($invoice)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$detail_invoice = "CREATE TABLE IF NOT EXISTS tb_detail_invoice(detail_invoice_id VARCHAR(20) PRIMARY KEY NOT NULL,pembelian_id VARCHAR(20),invoice_id VARCHAR(20), produk_id VARCHAR(20),urutan INT,
qty INT,harga DECIMAL(20,2),diskon DECIMAL(20,2),satuan_id VARCHAR(20),
FOREIGN KEY (invoice_id) REFERENCES tb_invoice(invoice_id) ON DELETE RESTRICT,
FOREIGN KEY (satuan_id) REFERENCES tb_satuan(satuan_id) ON DELETE RESTRICT
)";



if ($conn->query($detail_invoice)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


$biaya_tambahan_invoice = "CREATE TABLE IF NOT EXISTS tb_biaya_tambahan_invoice(
biaya_tambahan_invoice_id VARCHAR(20) PRIMARY KEY NOT NULL,data_biaya_id VARCHAR(20),pembelian_id VARCHAR(20),invoice_id VARCHAR(20),
keterangan VARCHAR(255),jlh DECIMAL(20,2),urutan INT,
FOREIGN KEY (data_biaya_id) REFERENCES tb_data_biaya(data_biaya_id) ON DELETE RESTRICT,FOREIGN KEY (invoice_id) REFERENCES tb_invoice(invoice_id) ON DELETE RESTRICT)";

if ($conn->query($biaya_tambahan_invoice)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$invoice_history = "CREATE TABLE IF NOT EXISTS tb_invoice_history(
  invoice_history_id VARCHAR(20) PRIMARY KEY NOT NULL,
  invoice_id VARCHAR(20),
  tanggal_invoice_before DATE,
  no_invoice_supplier_before VARCHAR(20),
  tanggal_input_invoice_before DATE,
  tanggal_po_before DATE,
  tanggal_pengiriman_before DATE,
  tanggal_terima_before DATE,
  supplier_id_before VARCHAR(20),
  pembelian_id_before VARCHAR(20),
  gudang_id_before VARCHAR(20),
  keterangan_before VARCHAR(255),
  no_pengiriman_before VARCHAR(20),
  total_qty_before INT,
  ppn_before DECIMAL(20,2),
  nominal_ppn_before DECIMAL(20,2),
  diskon_before DECIMAL(20,2),
  nominal_pph_before DECIMAL(20,2),
  biaya_tambahan_before DECIMAL(20,2),
  sub_total_before DECIMAL(20,2),
  grand_total_before DECIMAL(20,2),
  created_on_before TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by_before VARCHAR(50),
  status_before VARCHAR(20),  
  tanggal_invoice_after DATE,
  no_invoice_supplier_after VARCHAR(20),
  tanggal_input_invoice_after DATE,
  tanggal_po_after DATE,
  tanggal_pengiriman_after DATE,
  tanggal_terima_after DATE,
  supplier_id_after VARCHAR(20),
  gudang_id_after VARCHAR(20),
  pembelian_id_after VARCHAR(20),
  keterangan_after VARCHAR(255),
  no_pengiriman_after VARCHAR(20),
  total_qty_after INT,
  ppn_after DECIMAL(20,2),
  nominal_ppn_after DECIMAL(20,2),
  diskon_after DECIMAL(20,2),
  nominal_pph_after DECIMAL(20,2),
  biaya_tambahan_after DECIMAL(20,2),
  sub_total_after DECIMAL(20,2),
  grand_total_after DECIMAL(20,2),
  created_on_after TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by_after VARCHAR(50),
  created_status VARCHAR(20),
  status_after VARCHAR(20))
  ";

if ($conn->query($invoice_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$detail_invoice_history = "CREATE TABLE IF NOT EXISTS tb_detail_invoice_history(
detail_invoice_history_id VARCHAR(20) PRIMARY KEY NOT NULL,
invoice_history_id VARCHAR(20),
pembelian_id VARCHAR(20),
invoice_id VARCHAR(20),
produk_id VARCHAR(20),
urutan INT,
qty INT,
harga DECIMAL(20,2),
diskon DECIMAL(20,2),
satuan_id VARCHAR(20),
tipe_detail_invoice_history VARCHAR(10))";

if ($conn->query($detail_invoice_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$biaya_tambahan_invoice_history = "CREATE TABLE IF NOT EXISTS tb_biaya_tambahan_invoice_history(
biaya_tambahan_invoice_history_id VARCHAR(20) PRIMARY KEY NOT NULL,
invoice_history_id VARCHAR(20),
data_biaya_id VARCHAR(20),
pembelian_id VARCHAR(20),
invoice_id VARCHAR(20),
keterangan VARCHAR(255),
jlh DECIMAL(20,2),
urutan INT,
tipe_biaya_tambahan_invoice VARCHAR(10))";
if ($conn->query($biaya_tambahan_invoice_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}



$pembelian_history = "CREATE TABLE IF NOT EXISTS tb_pembelian_history(pembelian_history_id VARCHAR(20) PRIMARY KEY NOT NULL,pembelian_id_before VARCHAR(20),tanggal_po_before DATE,tanggal_pengiriman_before DATE,tanggal_terima_before DATE,
supplier_id_before VARCHAR(20),gudang_id_before VARCHAR(20), keterangan_before VARCHAR(255),no_pengiriman_before VARCHAR(20),total_qty_before INT,ppn_before DECIMAL(20,2),nominal_ppn_before DECIMAL(20,2),diskon_before DECIMAL(20,2),
nominal_pph_before DECIMAL(20,2),biaya_tambahan_before DECIMAL(20,2),sub_total_before DECIMAL(20,2),grand_total_before DECIMAL(20,2),created_on_before timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
created_by_before VARCHAR(50), status_before VARCHAR (20),keterangan_cancel_before VARCHAR(255),cancel_by_before VARCHAR (100),
pembelian_id_after VARCHAR(10),
tanggal_po_after DATE,tanggal_pengiriman_after DATE,tanggal_terima_after DATE,
supplier_id_after VARCHAR(20),gudang_id_after VARCHAR(20),keterangan_after VARCHAR(255),no_pengiriman_after VARCHAR(20),total_qty_after INT,ppn_after DECIMAL(20,2),nominal_ppn_after DECIMAL(20,2),diskon_after DECIMAL(20,2),
nominal_pph_after DECIMAL(20,2),biaya_tambahan_after DECIMAL(20,2),sub_total_after DECIMAL(20,2),grand_total_after DECIMAL(20,2),created_on_after timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
created_by_after VARCHAR(50), status_after VARCHAR (20),keterangan_cancel_after VARCHAR(255),cancel_by_after VARCHAR (100),created_status VARCHAR(20)
)
";
if ($conn->query($pembelian_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}



$detail_pembelian_history = "CREATE TABLE IF NOT EXISTS tb_detail_pembelian_history(detail_pembelian_history_id VARCHAR(20) PRIMARY KEY NOT NULL,pembelian_history_id VARCHAR(20), produk_id VARCHAR(20),urutan INT,
qty INT,harga DECIMAL(20,2),diskon DECIMAL(20,2),satuan_id VARCHAR(20),tipe_detail_pembelian_history VARCHAR(10))";

if ($conn->query($detail_pembelian_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$biaya_tambahan_history = "CREATE TABLE IF NOT EXISTS tb_biaya_tambahan_history(biaya_tambahan_history_id VARCHAR(20) PRIMARY KEY NOT NULL,data_biaya_id VARCHAR(20),pembelian_history_id VARCHAR(20),
keterangan VARCHAR(255),jlh DECIMAL(20,2),urutan INT,tipe_biaya_tambahan_history VARCHAR(10))";

if ($conn->query($biaya_tambahan_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}



$retur_pembelian = "CREATE TABLE IF NOT EXISTS tb_retur_pembelian(
  retur_pembelian_id VARCHAR(20) PRIMARY KEY NOT NULL,
  invoice_id VARCHAR(20),
  tanggal_invoice DATE,
  no_invoice_supplier VARCHAR(20),
  tanggal_input_invoice DATE,
  tanggal_po DATE,
  tanggal_pengiriman DATE,
  tanggal_terima DATE,
  supplier_id VARCHAR(20),
  gudang_id VARCHAR(20),
  pembelian_id VARCHAR(20),
  keterangan VARCHAR(255),
  no_pengiriman VARCHAR(20),
  total_qty INT,
  ppn DECIMAL(20,2),
  nominal_ppn DECIMAL(20,2),
  diskon DECIMAL(20,2),
  nominal_pph DECIMAL(20,2),
  biaya_tambahan DECIMAL(20,2),
  sub_total DECIMAL(20,2),
  grand_total DECIMAL(20,2),
  created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by VARCHAR(50),
  status VARCHAR(20),
  FOREIGN KEY (supplier_id) REFERENCES tb_supplier(supplier_id) ON DELETE RESTRICT,
  FOREIGN KEY (invoice_id) REFERENCES tb_invoice(invoice_id) ON DELETE RESTRICT,
  FOREIGN KEY (gudang_id) REFERENCES tb_gudang(gudang_id) ON DELETE RESTRICT

)";

if ($conn->query($retur_pembelian)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$detail_retur_pembelian = "CREATE TABLE IF NOT EXISTS tb_detail_retur_pembelian(detail_retur_pembelian_id VARCHAR(20) PRIMARY KEY NOT NULL,retur_pembelian_id VARCHAR(20),pembelian_id VARCHAR(20),invoice_id VARCHAR(20), produk_id VARCHAR(20),urutan INT,
qty INT,harga DECIMAL(20,2),diskon DECIMAL(20,2),satuan_id VARCHAR(20),
FOREIGN KEY (retur_pembelian_id) REFERENCES tb_retur_pembelian(retur_pembelian_id) ON DELETE RESTRICT,FOREIGN KEY (satuan_id) REFERENCES tb_satuan(satuan_id) ON DELETE RESTRICT)";



if ($conn->query($detail_retur_pembelian)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


$retur_pembelian_history = "CREATE TABLE IF NOT EXISTS tb_retur_pembelian_history(
  retur_pembelian_history_id VARCHAR(20) PRIMARY KEY NOT NULL,
  retur_pembelian_id_before VARCHAR(20),
  invoice_id_before VARCHAR(20),
  tanggal_invoice_before DATE,
  no_invoice_supplier_before VARCHAR(20),
  tanggal_input_invoice_before DATE,
  tanggal_po_before DATE,
  tanggal_pengiriman_before DATE,
  tanggal_terima_before DATE,
  supplier_id_before VARCHAR(20),
  gudang_id_before VARCHAR(20),
  pembelian_id_before VARCHAR(20),
  keterangan_before VARCHAR(255),
  no_pengiriman_before VARCHAR(20),
  total_qty_before INT,
  ppn_before DECIMAL(20,2),
  nominal_ppn_before DECIMAL(20,2),
  diskon_before DECIMAL(20,2),
  nominal_pph_before DECIMAL(20,2),
  biaya_tambahan_before DECIMAL(20,2),
  sub_total_before DECIMAL(20,2),
  grand_total_before DECIMAL(20,2),
  created_on_before TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by_before VARCHAR(50),
  status_before VARCHAR(20),


 retur_pembelian_id_after VARCHAR(20),
  invoice_id_after VARCHAR(20),
  tanggal_invoice_after DATE,
  no_invoice_supplier_after VARCHAR(20),
  tanggal_input_invoice_after DATE,
  tanggal_po_after DATE,
  tanggal_pengiriman_after DATE,
  tanggal_terima_after DATE,
  supplier_id_after VARCHAR(20),
  gudang_id_after VARCHAR(20),
  pembelian_id_after VARCHAR(20),
  keterangan_after VARCHAR(255),
  no_pengiriman_after VARCHAR(20),
  total_qty_after INT,
  ppn_after DECIMAL(20,2),
  nominal_ppn_after DECIMAL(20,2),
  diskon_after DECIMAL(20,2),
  nominal_pph_after DECIMAL(20,2),
  biaya_tambahan_after DECIMAL(20,2),
  sub_total_after DECIMAL(20,2),
  grand_total_after DECIMAL(20,2),
  created_on_after TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by_after VARCHAR(50),
  status_after VARCHAR(20)


)";

if ($conn->query($retur_pembelian_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$detail_retur_pembelian_history = "CREATE TABLE IF NOT EXISTS tb_detail_retur_pembelian_history(detail_retur_pembelian_history_id VARCHAR(20) PRIMARY KEY NOT NULL,retur_pembelian_history_id VARCHAR(20),retur_pembelian_id VARCHAR(20),pembelian_id VARCHAR(20),invoice_id VARCHAR(20), produk_id VARCHAR(20),urutan INT,
qty INT,harga DECIMAL(20,2),diskon DECIMAL(20,2),satuan_id VARCHAR(20),tipe_retur_pembelian_history VARCHAR(20))";



if ($conn->query($detail_retur_pembelian_history)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}

$penjualan = "CREATE TABLE IF NOT EXISTS tb_penjualan(
penjualan_id VARCHAR(20) PRIMARY KEY NOT NULL,
tanggal_penjualan DATE,
customer_id VARCHAR(20),
supplier_id VARCHAR(20),
gudang_id VARCHAR(20),
keterangan_penjualan VARCHAR(255),
no_pengiriman VARCHAR(20),
total_qty INT,
ppn DECIMAL(20,2),
nominal_ppn DECIMAL(20,2),
diskon DECIMAL(20,2),
nominal_pph DECIMAL(20,2),
biaya_tambahan DECIMAL(20,2),
sub_total DECIMAL(20,2),
grand_total DECIMAL(20,2),
created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
created_by VARCHAR(50),
status VARCHAR (20),
keterangan_cancel VARCHAR(255),
cancel_by VARCHAR (100),
keterangan_invoice VARCHAR(255),
keterangan_pengiriman VARCHAR(255),
keterangan_gudang VARCHAR(255),
FOREIGN KEY (supplier_id) REFERENCES tb_supplier(supplier_id) ON DELETE RESTRICT,
FOREIGN KEY (gudang_id) REFERENCES tb_gudang(gudang_id) ON DELETE RESTRICT,
FOREIGN KEY (customer_id) REFERENCES tb_customer(customer_id) ON DELETE RESTRICT
)";

if ($conn->query($penjualan)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


$detail_penjualan = "CREATE TABLE IF NOT EXISTS tb_detail_penjualan(
detail_penjualan_id VARCHAR(20) PRIMARY KEY NOT NULL,
penjualan_id VARCHAR(20),
produk_id VARCHAR(20),
urutan INT,
qty INT,
harga DECIMAL(20,2),
diskon DECIMAL(20,2),
satuan_id VARCHAR(20),
FOREIGN KEY (penjualan_id) REFERENCES tb_penjualan(penjualan_id) ON DELETE RESTRICT,
FOREIGN KEY (satuan_id) REFERENCES tb_satuan(satuan_id) ON DELETE RESTRICT
)";

if ($conn->query($detail_penjualan)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


$biaya_tambahan_penjualan = "CREATE TABLE IF NOT EXISTS tb_biaya_tambahan_penjualan(
biaya_tambahan_penjualan_id VARCHAR(20) PRIMARY KEY NOT NULL,
data_biaya_id VARCHAR(20),
penjualan_id VARCHAR(20),
keterangan VARCHAR(255),
jlh DECIMAL(20,2),
urutan INT,
FOREIGN KEY (data_biaya_id) REFERENCES tb_data_biaya(data_biaya_id) ON DELETE RESTRICT,
FOREIGN KEY (penjualan_id) REFERENCES tb_penjualan(penjualan_id) ON DELETE RESTRICT)";


if ($conn->query($biaya_tambahan_invoice)) {
    try {
    } catch (Error) {
        echo mysqli_error($conn);
    }
}


mysqli_close($conn);
