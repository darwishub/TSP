<?php 
include "../../config.php";

//print_r($_POST);
// break;

$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
 
mysql_query("INSERT INTO tb_daftar_toko 
             SET nama       = '$nama',
                 alamat     = '$alamat',
                 lat        = '$lat',
                 lng        = '$lng' 
             ");
 header('location: ../datatoko.php?pesan=input');
?>