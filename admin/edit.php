<?php include '../config.php'; ?>
<?php include 'navbar.php'; ?>

<?php
    
    $id = $_GET['id'];

    $sql = mysql_query("SELECT * FROM tb_daftar_toko WHERE id_toko='$id' ")or die(mysql_error());

    $row = mysql_fetch_array($sql);
?>
  
   <div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 mx-auto">
              
              <p>Edit Data Toko</p>

              <form action="datatoko/proses-edit.php?id=<?php echo $id; ?>" name="modal_popup" method="POST">
                    
                    <div class="form-group">
                        <input class="form-control" name="nama" type="text" value="<?php echo $row['nama']; ?>" placeholder="Masukkan Nama Toko">
                  </div>
                    <div class="form-group">
                        <input class="form-control" name="alamat" type="text" value="<?php echo $row['alamat']; ?>" placeholder="Masukkan Alamat">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="lat" type="text" value="<?php echo $row['lat']; ?>" placeholder="Masukkan Latitude">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="lng" type="text" value="<?php echo $row['lng']; ?>" placeholder="Masukkan Longitude">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-danger btn-block" data-dismiss="modal">Batal</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success btn-block" type="submit">Simpan</button>
                        </div>
                    </div>
                    
                </form>

            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>