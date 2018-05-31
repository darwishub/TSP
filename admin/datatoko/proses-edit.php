<?php
	include "../../config.php";
 
	$id=$_GET['id'];
    
//    print_r($_POST);
//    break;

	$nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
 
    mysql_query("UPDATE tb_daftar_toko set nama='$nama', alamat='$alamat', lat='$lat', lng='$lng' where id_toko='$id'");

     header('location: ../datatoko.php?pesan=update');
 
?>