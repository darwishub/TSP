<?php
  include "../config.php";
?>
<script>
var daftarMarker = [];
var marker;
var map;
var gPath;

function initialize() {
	var mapCanvas = document.getElementById('map-canvas');
	var mapOptions = {
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	map = new google.maps.Map(mapCanvas, mapOptions);
	
	var infoWindow = new google.maps.InfoWindow;
	var bounds = new google.maps.LatLngBounds();


	function bindInfoWindow(marker, map, infoWindow, html) {
		google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		});
	}

	function addMarker(id, lat, lng, info) {
		var pt = new google.maps.LatLng(lat, lng);
		bounds.extend(pt);
		
		var marker = new google.maps.Marker({
			map: map,
			position: pt
		});
		
		// Tambahkan parameter id
		marker.set("id", id);
		
		// Untuk titik pertama (Nida Food) tampilkan markernya dan ganti menjadi warna biru, selain itu sembunyikan markernya
		if (id == 1){
			marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png')
			marker.setVisible(true);
		} else {
			marker.setVisible(false);
		}
		
		map.fitBounds(bounds);
		var listener = google.maps.event.addListener(map, "idle", function() {
			map.setZoom(12);
			google.maps.event.removeListener(listener);
		});
		bindInfoWindow(marker, map, infoWindow, info);
		
		// masukkan dalam daftar marker untuk digunakan pada saat proses pemberian centang
		daftarMarker.push(marker);
	}
	
	// hapus semua marker yang tersedia pada peta
	for (var i = 0; i < daftarMarker.length; i++ ) {
		daftarMarker[i].setMap(null);
	}

	<?php
		$query = mysql_query("select * from tb_daftar_toko");
		while ($data = mysql_fetch_array($query)) {
			$id = $data['id_toko'];
			$lat = $data['lat'];
			$lon = $data['lng'];
			$nama = $data['nama'];
			$alamat = $data['alamat'];  
			
			// Tambahkan marker pada peta
			echo ("addMarker($id, $lat, $lon, '<b>$nama<br><br>$alamat</b>');\n");                        
		}
	?>
}

// fungsi untuk mengubah centang pada saat diklik
function toggleCheckbox(id) {
	try {
		var checkbox = document.getElementById('data'+id);
		var getUrl = window.location;
		var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
		var src = checkbox.src.replace(baseUrl,'');
		
		if (src == "admin/images/uncheck.png"){
			checkbox.src = "images/check.png";
			updateMarker(id, true);
		} else {
			checkbox.src = "images/uncheck.png";
			updateMarker(id, false);
		}
		
	} catch (e) {
		alert(e);
	}
}

// fungsi untuk mengupdate tampilan marker pada peta
function updateMarker(id, state) {
	for (var i = 0; i < daftarMarker.length; i++) {
		var marker = daftarMarker[i];
		var idMarker = marker.get("id");
		
		if (idMarker == id) {
			marker.setVisible(state);
			break;
		}
	}
}

// fungsi untuk mengubah semua centang pada saat diklik
function toggleCheckboxAll() {
	try {
		var checkboxAll = document.getElementById('checkAll');
		var getUrl = window.location;
		var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
		var src = checkboxAll.src.replace(baseUrl,'');
		
		if (src == "admin/images/uncheck.png"){
			checkboxAll.src = "images/check.png";
		} else {
			checkboxAll.src = "images/uncheck.png";
		}

		// ubah semua centang kecuali baris pertama (Nida Food)
		var tabel = document.getElementById("tblDataToko");
		var jumlahBaris = tabel.rows.length;
		for (var i = 3; i < jumlahBaris; i++) {
			var barisData = document.getElementById("tblDataToko").rows[i].cells;
			var id = barisData[1].innerHTML;
			var checkbox = document.getElementById('data'+id);
			checkbox.src = checkboxAll.src;
			
			var src = checkbox.src.replace(baseUrl,'');
			if (src == "admin/images/check.png"){
				updateMarker(id, true);
			} else {
				updateMarker(id, false);
			}
		}
	} catch (e) {
		alert(e);
    }
}

// fungsi untuk melakukan validasi semua input sebelum dilakukan proses perhitungan
// setiap pesan kesalahan akan tampil selama 2 detik sebelum menutup sendiri
function validasi() {
	try {
		// tampilkan pesan kesalahan apabila tidak ada toko yang dipilih
		var isKosong = true;
		var jumlahToko = 0;
		
		var tabel = document.getElementById("tblDataToko");
		var jumlahBaris = tabel.rows.length;
		
		var getUrl = window.location;
		var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";

		for (var i = 2; i < jumlahBaris; i++) {
			var barisData = document.getElementById("tblDataToko").rows[i].cells;
			var id = barisData[1].innerHTML;
			var checkbox = document.getElementById('data'+id);
			var src = checkbox.src.replace(baseUrl,'');
			
			if (src == "admin/images/check.png"){
				isKosong = false;
				jumlahToko = jumlahToko + 1;
			}
		}
		
		if (isKosong){
			document.getElementById('labelPesanError').innerHTML = 'Pilih Toko';
			$( "div.pesanError" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
			return false;
		}
		
		// Tampilkan pesan kesalahan hanya 1 toko yang dipilih
		if (jumlahToko <= 1){
			document.getElementById('labelPesanError').innerHTML = 'Jumlah Toko harus lebih dari satu';
			$( "div.pesanError" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
			return false;
		}
		
		prosesPerhitungan();
		
	} catch (e) {
		alert(e);
    }
}

// fungsi untuk melakukan proses pencarian jalur
// fungsi ini akan memanggil file lain pada alamat bco.php dengan parameter tertentu
// hasil perhitungan akan ditampilkan pada layar
function prosesPerhitungan() {
	try {
		if (window.XMLHttpRequest) { 
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function() {
			if (this.readyState==4 && this.status==200) {
				var output;
				
				// untuk mengambil variabel hasil perhitungan dari php
				if(this.responseText) {
					try {
						output = JSON.parse(this.responseText);
						
						// tampilkan pesan kesalahan apabila ditemukan
						if (output['pesan_kesalahan'] != ""){
							document.getElementById('labelPesanError').innerHTML = output['pesan_kesalahan'];
							$( "div.pesanError" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
							document.getElementById("kesimpulan").innerHTML = "";
							document.getElementById("hasilPerhitungan").innerHTML = "";
						
						// tampilkan pesan sukses apabila ditemukan
						} else if (output['pesan_tampil'] != ""){
							document.getElementById('labelPesanSukses').innerHTML = output['pesan_tampil'];
							$( "div.pesanSukses" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );
							document.getElementById("kesimpulan").innerHTML = output['kesimpulan'];
							document.getElementById("hasilPerhitungan").innerHTML = output['teks'];
						}
						
						// tampung nilai latitude dan longitude pada jalur terbaik untuk digambar pada peta
						var jalurTerbaik = output['jalurTerbaik'];
						
						var poly = [];
						for (var j=0;j<jalurTerbaik.length;j++){
							var pos = new google.maps.LatLng(parseFloat(jalurTerbaik[j].lat),parseFloat(jalurTerbaik[j].lng))
							poly.push(pos);
						}
						
						// Hapus semua garis yang sudah digambar sebelumnya
						if(typeof gPath !== "undefined") gPath.setMap(null);
						
						gPath = new google.maps.Polyline({
						  path: poly,
						  geodesic: true,
						  strokeColor: '#FF0000',
						  strokeOpacity: 1.0,
						  strokeWeight: 2
						});
						gPath.setMap(map);
						
					} catch(ex) {
						// alert(ex); // error in the above string (in this case, yes)!
						document.getElementById("hasilPerhitungan").innerHTML=this.responseText;
					}
				}
			}
		}
		
		var tabel = document.getElementById("tblDataToko");
		var jumlahBaris = tabel.rows.length;
		
		var getUrl = window.location;
		var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
		
		var daftaridToko = [];
		for (var i = 2; i < jumlahBaris; i++) {
			var barisData = document.getElementById("tblDataToko").rows[i].cells;
			var id = barisData[1].innerHTML;
			var checkbox = document.getElementById('data'+id);
			var src = checkbox.src.replace(baseUrl,'');
			if (src == "admin/images/check.png"){
				daftaridToko.push(id);
			}
		}
		
		xmlhttp.open("GET","pencarian/bco.php?idToko="+daftaridToko,true);
		xmlhttp.send();
	} catch (e) {
		alert(e);
    }
}
</script>

<div class="content-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<!-- Nav tabs-->
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Cari</a>
						<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Output</a>
					</div>
				</nav>
				
				<!--Nav Content -->
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
						<div id="map-canvas" style="width: 100%;height: 470px"></div>
						<br>
		
						<table id="tblDataToko" name="tblDataToko" class="table table-bordered">					
                            <tr class="text-center">
								<th rowspan="2" style="vertical-align:middle">
									<a onclick="toggleCheckboxAll();" style="cursor:pointer;">
										<img border="0" id="checkAll" src="images/uncheck.png" align="absmiddle">
									</a>
								</th>
                                <th rowspan="2" style="vertical-align:middle">Nama Toko</th>
                                <th rowspan="2" style="vertical-align:middle">Alamat</th>
                                <th colspan="2">Titik Koordinat</th>
                            </tr>
                            <tr class="text-center">
                                <th>Latitude</th>
                                <th>Longitude</th>
                            </tr>
                            <?php 
								// query yang digunakan untuk mengambil data toko
								$query = "SELECT * FROM tb_daftar_toko ORDER BY id_toko";
								$sql=mysql_query($query);
								while ($row=mysql_fetch_array($sql)){
							?>
                                <tr>
									<!-- checkbox untuk menampilkan marker pada google maps -->
                                    <td class="text-center">
										<a <?php if ($row["id_toko"] != 1) {?> onclick="toggleCheckbox(<?=$row["id_toko"]?>);" <?php }?> style="cursor:pointer;">
											<img border="0" id="data<?=$row["id_toko"]?>" src="images/<?=($row["id_toko"] != 1 ? "un" : "")?>check.png" align="absmiddle">
										</a>
                                    </td>
									<td style="display:none"><?=$row["id_toko"]?></td>
									<td><?=$row["nama"]?></td>
									<td><?=$row["alamat"]?></td>
									<td><?=$row["lat"]?></td>
									<td><?=$row["lng"]?></td>
                                </tr>
							<?php 
								}
							?>
						</table>
						<br>
						<button class="btn btn-primary" onclick="validasi();">Cari Jalur</button>
						<br>
						<!-- kesimpulan jalur terdekat-->
						<div id="kesimpulan" class="col-md-12 col-sm-12">
						</div>
						
						<!-- tampilan pesan kesalahan saat terjadi kesalahan -->
						<div class="col-md-12 col-sm-12 alert-box pesanError"><label id="labelPesanError">&nbsp;</label></div>
		
						<!-- tampilan pesan sukses saat proses perhitungan berhasil -->
						<div class="col-md-12 col-sm-12 alert-box pesanSukses"><label id="labelPesanSukses">&nbsp;</label></div>
						
						
					</div>
					
					<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
						<!-- output proses perhitungan manual dari algoritma BCO -->
						<div id="hasilPerhitungan" class="col-md-12 col-sm-12">
							<!--
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Proses</th>
										<th>Jalur</th>
										<th>Nilai Jalur</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Lebah 1</td>
										<td>Nida Food - Tambah Toko - Toko Serba Carica - Toko Carica Indah - Toko Lancar Jaya - Nida Food</td>
										<td>64.291092416663</td>
									</tr>
									<tr>
										<td>Lebah 2</td>
										<td>Nida Food - Tambah Toko - Toko Carica Indah - Toko Serba Carica - Toko Lancar Jaya - Nida Food</td>
										<td>64.212113156248</td>
									</tr>
									<tr>
										<td>Lebah 3</td>
										<td>Nida Food - Tambah Toko - Toko Serba Carica - Toko Carica Indah - Toko Lancar Jaya - Nida Food</td>
										<td>64.291092416663</td>
									</tr>
								</tbody>
							</table>
							-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-JpweDJ7_cA9-KiEq-iMjQzlluOemnWo&callback=initialize" type="text/javascript"></script>

<!-- css tambahan agar tag <pre> bersifat word wrap -->
<style>
pre {
    white-space: pre-wrap;       /* Since CSS 2.1 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word;       /* Internet Explorer 5.5+ */
}
</style>