<?php
include 'db.php';
// DB table to use
$table = 'tb_karyawan';
 
// Table's primary key
$primaryKey = 'karyawan_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'karyawan_id', 'dt' => 0 ),
    array( 'db' => 'nama',  'dt'       => 1 ),
    array( 'db' => 'role_id','dt'      => 2 ),
    array( 'db' => 'divisi', 'dt'      => 3 ),
    array('db'  => 'noTelp','dt'       => 4,),
    array('db'  => 'alamat','dt'       => 5,),
    array('db' =>  'ktp',    'dt'       =>6,),
    array('db'=>'   npwp',  'dt'        =>7,),
    array('db'=>   'status', 'dt'       =>8)
);
 
// SQL server connection information
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $conn, $table, $primaryKey, $columns ));
?>