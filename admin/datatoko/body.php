<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">


                <div class="table-responsive table-toko">
                    <br>
                    <table class="table table-bordered">
                        <?php 
	if(isset($_GET['pesan'])){
		$pesan = $_GET['pesan'];
		if($pesan == "input"){
			echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Data berhasil di input.<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
		}else if($pesan == "update"){
			echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Data berhasil di edit.<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
		}else if($pesan == "hapus"){
			echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Data berhasil di hapus.<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
		}
	}
	?>
                            <tr class="text-center">
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Toko</th>
                                <th rowspan="2">Alamat</th>
                                <th colspan="2">Titik Koordinat</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th>Latitude</th>
                                <th>Longitude</th>

                            </tr>
                            <?php include "../config.php";
                        
                        $query_mysql = mysql_query("SELECT * FROM tb_daftar_toko ORDER BY id_toko DESC")or die(mysql_error());
                        $id = 1;
                        while($data = mysql_fetch_array($query_mysql)){
                        ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $id++; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['nama']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['alamat']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['lat']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['lng']; ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-warning" href="edit.php?id=<?php echo $data['id_toko']; ?>"><span class="fa fa-pencil"></span></a> |

                                        <?php if ($data['id_toko'] != 1){ ?>
										<a onclick="return confirm('Apakah anda yakin untuk menghapus toko tersebut ?')" href="datatoko/delete.php?id=<?php echo $data['id_toko']; ?>">
                                            <button class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                                        </a>
										<?php } ?>
                                    </td>
                                    <?php include('button.php'); ?>
                                </tr>
                                <?php } ?>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <?php include "modal-tambah.php"; ?>