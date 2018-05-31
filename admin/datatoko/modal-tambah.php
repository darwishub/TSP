<!-- The Modal -->
<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form action="datatoko/proses-tambah.php" name="modal_popup" method="POST">
                    
                    <div class="form-group">
                        <input class="form-control" name="nama" type="text" placeholder="Masukkan Nama Toko">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="alamat" type="text" placeholder="Masukkan Alamat">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="lat" type="text" placeholder="Masukkan Latitude">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="lng" type="text" placeholder="Masukkan Longitude">
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
</div>