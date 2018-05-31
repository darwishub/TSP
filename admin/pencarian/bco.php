<?php
// kelas untuk menampung data toko
Class Toko{
	Public $id = 0;
	Public $nama = "";
	Public $alamat = "";
	Public $lat = "";
	Public $lng = "";

	public static function newToko($m_id, $m_nama, $m_alamat, $m_lat, $m_lng){
		$instance = new self();	
		$instance->id = $m_id;
		$instance->nama = $m_nama;
		$instance->alamat = $m_alamat;
		$instance->lat = $m_lat;
		$instance->lng = $m_lng;
		return $instance;
	}
}

// kelas lebah sebagai pencari jalur
Class lebah{
	Public $status = 0;            		//jika berisi nilai 0 berarti nonAktif, 1 berarti aktif, 2 berarti pencari
	Public $jalur = array();            //jalur yang ditempuh oleh lebah tersebut
	Public $nilaiJalur = 0.0;	        //nilai jarak yang ditempuh, semakin rendah semakin baik
	Public $jumlahPerjalanan = 0;		//menghitung berapa kali lebah sudah melakukan perjalanan

	public static function newLebah($m_status, $m_jalur, $m_nilaiJalur, $m_jumlahPerjalanan){
		$instance = new self();	
		$instance->status = $m_status;
		$instance->jalur = $m_jalur;
		$instance->nilaiJalur = $m_nilaiJalur;
		$instance->jumlahPerjalanan = $m_jumlahPerjalanan;	
		return $instance;
	}
}
	
// kelas untuk sarang lebah
Class sarangLebah{
	Public $daftarToko = array();

	Public $totalLebah = 0;
	Public $totalLebahNonaktif = 0;
	Public $totalLebahAktif = 0;
	Public $totalLebahPencari = 0;

	Public $totalIterasi = 0;

	Public $totalPerjalanan = 0;       	//jumlah maksimal lebah boleh melakukan perjalanan untuk mencari titik tetangga dengan solusi lebih baik
	Public $probTerbujuk = 0.9;			//kemungkinan lebah nonaktif untuk menerima solusi yang diberikan sewaktu tarian waggle
	Public $probKesalahan = 0.01;   	//kemungkinan lebah aktif menolak solusi yang lebih baik atau menerima solusi yang lebih buruk

	Public $daftarLebah = array();
	Public $jalurTerbaik = array();
	Public $nilaiJalurTerbaik = 0.0;
	Public $daftarindeksLebahNonaktif = array();	//daftar indeks untuk lebah nonaktif
	
	Public $teks = "";
	Public $daftarOutput = array();
	Public $idxOutput = 0;

	public static function newSarangLebah($m_totalLebah, $m_totalLebahNonaktif, $m_totalLebahAktif, $m_totalLebahPencari, $m_totalPerjalanan, $m_totalIterasi, $m_daftarToko, $m_teks){
		$instance = new self();	
		$instance->totalLebah = $m_totalLebah;
		$instance->totalLebahNonaktif = $m_totalLebahNonaktif;
		$instance->totalLebahAktif = $m_totalLebahAktif;
		$instance->totalLebahPencari = $m_totalLebahPencari;
		$instance->totalPerjalanan = $m_totalPerjalanan;
		$instance->totalIterasi = $m_totalIterasi;
		
		$instance->daftarToko = $m_daftarToko;
		$instance->teks = $m_teks;

		$instance->teks .= "Inisialisasi populasi dengan jalur acak" . "<br />";
		$instance->teks .= "Populasi awal" . "<br />";

		$instance->daftarLebah = array();
		$instance->jalurTerbaik = $instance->TentukanJalurAcak();
		$instance->nilaiJalurTerbaik = $instance->nilaiJalur($instance->jalurTerbaik);
		
		$instance->teks .= "Lebah terbaik secara acak memiliki jalur = " . "<br />";
		$instance->teks .= $instance->cetakJalur($instance->jalurTerbaik) . "<br />";
		$instance->teks .= "dengan nilai jalur = " . $instance->nilaiJalurTerbaik . "<br />";
		$instance->daftarOutput[$instance->idxOutput][0] = "Lebah Terbaik Acak";
		$instance->daftarOutput[$instance->idxOutput][1] = $instance->cetakJalur($instance->jalurTerbaik);
		$instance->daftarOutput[$instance->idxOutput][2] = $instance->nilaiJalurTerbaik;
		$instance->idxOutput += 1;
		
		$instance->daftarindeksLebahNonaktif = array();			//menampung indeks lebah-lebah yang sedang nonaktif
		
		For ($i = 0; $i <= $instance->totalLebah - 1; $i++){
			$statusLebah = 0;
			If ($i < $instance->totalLebahNonaktif){
				$statusLebah = 0; 								//lebah nonAktif
				$instance->daftarindeksLebahNonaktif[$i] = $i; 	//lebah dengan indeks i adalah lebah nonaktif
			} ElseIf ($i < $instance->totalLebahNonaktif + $instance->totalLebahPencari){
				$statusLebah = 2; 								//lebah pencari
			} Else {
				$statusLebah = 1; 								//lebah aktif
			}

			$jalurAcak = $instance->TentukanJalurAcak();
			$mq = $instance->nilaiJalur($jalurAcak);
			$jumlahPerjalanan = 0;
			
			$instance->daftarLebah[$i] = lebah::newLebah($statusLebah, $jalurAcak, $mq, $jumlahPerjalanan);
			$instance->teks .= "Lebah " . ($i+1) . " memiliki jalur = " . "<br />";
			$instance->teks .= $instance->cetakJalur($jalurAcak) . "<br />";
			$instance->teks .= "dengan nilai jalur = " . $mq . "<br />";
			$instance->daftarOutput[$instance->idxOutput][0] = "Lebah " . ($i+1);
			$instance->daftarOutput[$instance->idxOutput][1] = $instance->cetakJalur($jalurAcak);
			$instance->daftarOutput[$instance->idxOutput][2] = $mq;
			$instance->idxOutput += 1;
			
			//Apakah lebah ini memiliki solusi lebih baik dari solusi umum?
			//Jika benar, maka ambil jalur ini sebagai jalur terbaik sementara
			If ($instance->daftarLebah[$i]->nilaiJalur < $instance->nilaiJalurTerbaik){
				$instance->jalurTerbaik = $instance->daftarLebah[$i]->jalur;
				$instance->nilaiJalurTerbaik = $instance->daftarLebah[$i]->nilaiJalur;
			}
		}
		
		$instance->teks .= "<br />" . "Lebah terbaik sementara memiliki jalur = " . "<br />";
		$instance->teks .= $instance->cetakJalur($instance->jalurTerbaik) . "<br />";
		$instance->teks .= "dengan nilai jalur = " . $instance->nilaiJalurTerbaik . "<br />";
		$instance->daftarOutput[$instance->idxOutput][0] = "Lebah Terbaik Sementara";
		$instance->daftarOutput[$instance->idxOutput][1] = $instance->cetakJalur($instance->jalurTerbaik);
		$instance->daftarOutput[$instance->idxOutput][2] = $instance->nilaiJalurTerbaik;
		$instance->idxOutput += 1;
		
		return $instance;
	}

	Public Function TentukanJalurAcak(){
		// Inisialisasi slot sebanyak jumlah data toko + 1
		// Perlu diingat bahwa titik awal dan titik akhir harus berakhir pada Nida Food dengan id = 1
		//----------------------------------------------------------------------
		
		// Ambil semua nilai selain Nida Food
		$slot = array();
		for ($i = 0; $i <= sizeof($this->daftarToko) - 1; $i++){
			if ($this->daftarToko[$i]->id != 1) $slot[sizeof($slot)+1] = $this->daftarToko[$i];
		}
		
		// Teknik pengacakan titik menggunakan Fisher-Yates (Knuth) 
		for ($i = 1; $i <= sizeof($slot); $i++){
			$r = rand($i, sizeof($slot));
			$tmp = $slot[$r];
			$slot[$r] = $slot[$i];
			$slot[$i] = $tmp;
		}
		
		// Tambahkan toko Nida Food (id = 1) pada awal dan akhir slot
		$slot[0] = $this->daftarToko[0];
		$slot[sizeof($slot)] = $this->daftarToko[0];
		ksort($slot);
		//----------------------------------------
		
		// echo var_dump($slot);
		// die;
		
		Return $slot;
	}

	Public Function TentukanJalurBaru($slot){
		//cari indeks acak selain titik awal dan titik akhir
		$idx1 = 1; $idx2 = 1;
		if (sizeof($slot) > 3){
			while ($idx1 == $idx2){
				$idx1 = rand(1, sizeof($slot) - 2);
				$idx2 = rand(1, sizeof($slot) - 2);
			}
		}
		
		// lakukan pertukaran data antara kedua indeks tersebut
		$tmp = $slot[$idx1];
		$slot[$idx1] = $slot[$idx2];
		$slot[$idx2] = $tmp;
		//----------------------------------------
		
		// echo var_dump($slot);
		// die;

		Return $slot;
	}

	//Gunakan fungsi ini untuk menghitung nilai jalur dari slot
	Public Function nilaiJalur($slot){
		$nilai = 0;
		for($i = 0; $i <= sizeof($slot) - 2; $i++){
			$posisiAwal = array($slot[$i]->lat, $slot[$i]->lng);
			$posisiTujuan = array($slot[$i+1]->lat, $slot[$i+1]->lng);
			$jarak = $this->HitungJarak($posisiAwal, $posisiTujuan);
			$nilai += $jarak;
		}
		Return $nilai;
	}
			
	// Gunakan fungsi ini untuk menghitung jarak antara posisi yang terpilih
	// parameter posisi awal dan posisi tujuan sudah berisi koordinat latitude dan longitude pada posisi tersebut
	function HitungJarak($posisiAwal, $posisiTujuan){
		$latitudeX = $posisiAwal[0];
		$longitudeX = $posisiAwal[1];
		$latitudeY = $posisiTujuan[0];
		$longitudeY = $posisiTujuan[1];
        // Rumus Mencari Jarak
		$jarak = (69 * sqrt(pow($longitudeX - $longitudeY, 2) + pow($latitudeX - $latitudeY, 2))) * 1.60934;
		return $jarak;
	}
	
	// Gunakan fungsi ini untuk menampilkan jalur pada layar
	function cetakJalur($jalur){
		$namaJalur = "";
		for($i = 0; $i <= sizeof($jalur) - 1; $i++){
			$namaJalur .= ($namaJalur == "" ? "" : " - ") . $jalur[$i]->nama;
		}
		
		return $namaJalur;
	}
	//--------------------------------------------------------------------------
	
	//Inti proses perhitungan algoritma	
	Public Function prosesPerhitungan(){
		$this->teks .= "<br />" . "Memulai proses perhitungan algoritma Artifical Bee Colony" . "<br />";

		//Lakukan proses perulangan sebanyak jumlah iterasi untuk semua lebah yang berada pada daftar lebah
		//Lakukan proses perhitungan sesuai dengan status lebah yang sedang diproses
		$iterasi = 0;
		While ($iterasi < $this->totalIterasi){
			For ($i = 0; $i <= $this->totalLebah - 1; $i++){
				//Jika lebah ini lebah aktif, maka lakukan proses perhitungan untuk lebah aktif
				If ($this->daftarLebah[$i]->status == 1){
					$this->ProsesLebahAktif($i, $iterasi);

				//Jika lebah ini lebah pencari, maka lakukan proses perhitungan untuk lebah pencari
				} ElseIf ($this->daftarLebah[$i]->status == 2){
					$this->ProsesLebahPencari($i, $iterasi);

				//Jika lebah ini lebah nonaktif, maka lakukan proses perhitungan untuk lebah nonaktif
				} ElseIf ($this->daftarLebah[$i]->status == 0){
					$this->ProsesLebahNonaktif($i, $iterasi);
				}
			}
			$iterasi += 1;
		}
		
		$this->teks .= "Selesai" . "<br />";
	}

	//Gunakan fungsi ini apabila lebah yang sedang diproses adalah lebah aktif
	//Penjelasan lebih detail tentang fungsi ini dapat dilihat pada penjelasan skrip dibawah ini
	Private Function ProsesLebahAktif($i, $iterasi){
		//Tentukan jalur baru untuk lebah ini
		$jalurBaru = $this->TentukanJalurBaru($this->daftarLebah[$i]->jalur);

		//Hitung nilai jalur untuk jalur yang baru ditemukan
		$nilaiJalurBaru = $this->nilaiJalur($jalurBaru);

		$prob = rand() / getrandmax();              //Ambil nilai acak untuk menentukan apakah lebah ini melakukan kesalahan; bandingkan dengan variabel probKesalahan yang memiliki nilai rendah (~0.01)
		$AmbilJalurBaru = False;                  	//digunakan untuk menentukan apakah lebah perlu melakukan tarian waggle
		$jumlahPerjalananMelebihiMaks = False;    	//digunakan untuk menentukan apakah lebah ini akan statusnya berubah menjadi lebah nonaktif

		//Jika jalur yang baru ternyata lebih baik dari jalur lebah tersebut
		If ($nilaiJalurBaru < $this->daftarLebah[$i]->nilaiJalur){
			//Tentukan apakah nilai acak kurang dari nilai probabilitas kesalahan
			//Jika benar maka Lebah melakukan kesalahan, sehingga menolak solusi yang lebih baik
			If ($prob < $this->probKesalahan){
				$this->daftarLebah[$i]->jumlahPerjalanan += 1;                  //Tidak mengambil jalur tersebut, tetapi menambah jumlah perjalanan
				If ($this->daftarLebah[$i]->jumlahPerjalanan > $this->totalPerjalanan){
					$jumlahPerjalananMelebihiMaks = True;
				}

			//Jika tidak maka lebah tidak melakukan kesalahan, sehingga menerima solusi yang baru
			} Else {
				$this->daftarLebah[$i]->jalur = $jalurBaru;						//ambil jalur baru sebagai jalur lebah tersebut
				$this->daftarLebah[$i]->nilaiJalur = $nilaiJalurBaru;
				$this->daftarLebah[$i]->jumlahPerjalanan = 0;                   //reset jumlah perjalanan lebah tersebut
				$AmbilJalurBaru = True;                                         //Lakukan tarian waggle pada saat lebah ini kembali ke sarang
			}

		//Jika jalur yang baru ternyata tidak lebih baik dari jalur lebah tersebut
		} Else {
			//Tentukan apakah nilai acak kurang dari nilai probabilitas kesalahan
			//Jika benar maka lebah melakukan kesalahan, sehingga menerima solusi yang lebih buruk
			If ($prob < $this->probKesalahan){
				$this->daftarLebah[$i]->jalur = $jalurBaru;						//ambil jalur baru sebagai jalur lebah tersebut
				$this->daftarLebah[$i]->nilaiJalur = $nilaiJalurBaru;
				$this->daftarLebah[$i]->jumlahPerjalanan = 0;                   //reset jumlah perjalanan lebah tersebut
				$AmbilJalurBaru = True;                                         //Lakukan tarian waggle pada saat lebah ini kembali ke sarang

			//Jika tidak maka lebah tidak melakukan kesalahan, sehingga menolak solusi yang baru
			} Else {
				$this->daftarLebah[$i]->jumlahPerjalanan += 1;                  //Tidak mengambil jalur tersebut, tetapi menambah jumlah perjalanan
				If ($this->daftarLebah[$i]->jumlahPerjalanan > $this->totalPerjalanan){
					$jumlahPerjalananMelebihiMaks = True;
				}
			}
		}

		//Setelah perhitungan tersebut, maka lebah akan kembali ke sarang 
		//ada 3 kemungkinan keadaan pada saat lebah sudah berada di sarang:

		//Jika jumlah perjalanan lebah tersebut sudah melebihi maksimum perjalanan, maka lebah ini akan berubah menjadi lebah nonaktif
		If ($jumlahPerjalananMelebihiMaks == True){
			$this->daftarLebah[$i]->status = 0;                           		//lebah akan berubah menjadi lebah nonaktif
			$this->daftarLebah[$i]->jumlahPerjalanan = 0;                 		//reset jumlah perjalanan lebah tersebut
			$x = rand(0, $this->totalLebahNonaktif - 1);      					//ambil lebah nonaktif secara acak
			$this->daftarLebah[$this->daftarindeksLebahNonaktif[$x]]->status = 1;	//ubah status lebah tersebut menjadi lebah aktif
			$this->daftarindeksLebahNonaktif[$x] = $i;                       	//Catat lebah ini kedalam daftar lebah nonaktif

		//Jika jalur baru diterima oleh lebah tersebut, maka lakukan pengecekan apakah jalur baru ini lebih baik dari solusi umum. Kemudian lakukan tarian waggle
		} ElseIf ($AmbilJalurBaru == True){
			//Jika jalur lebah yang baru ternyata lebih baik dari solusi umum
			If ($this->daftarLebah[$i]->nilaiJalur < $this->nilaiJalurTerbaik){
				$this->jalurTerbaik = $this->daftarLebah[$i]->jalur;			//ambil Jalur ini sebagai jalur terbaik
				$this->nilaiJalurTerbaik = $this->daftarLebah[$i]->nilaiJalur;
				
				$this->teks .= "Iterasi " . ($iterasi+1) . ", jalur terbaik sementara = " . "<br />";
				$this->teks .= $this->cetakJalur($this->jalurTerbaik) . "<br />";
				$this->teks .= "dengan nilai jalur terbaik sementara = " . $this->nilaiJalurTerbaik . "<br />";
				$this->daftarOutput[$this->idxOutput][0] = "Iterasi " . ($iterasi+1);
				$this->daftarOutput[$this->idxOutput][1] = $this->cetakJalur($this->jalurTerbaik);
				$this->daftarOutput[$this->idxOutput][2] = $this->nilaiJalurTerbaik;
				$this->idxOutput += 1;
			}

			//Lakukan Tarian Waggle
			//Penjelasan lebih detail tentang fungsi ini dapat dilihat pada penjelasan skrip dibawah ini
			$this->TarianWaggle($i);

		//Tidak terjadi apa-apa (lebah hanya kembali ke sarang)
		} Else {
			Return;
		}
	}

	//Gunakan fungsi ini apabila lebah yang sedang diproses adalah lebah pencari
	//Penjelasan lebih detail tentang fungsi ini dapat dilihat pada penjelasan skrip dibawah ini
	Private Function ProsesLebahPencari($i, $iterasi){
		//Tentukan jalur acak untuk lebah ini
		$jalurAcak = $this->TentukanJalurAcak();           						//Lebah pencari mencari jalur acak
		
		//Hitung nilai jalur untuk jalur acak tersebut
		$nilaiJalurAcak = $this->nilaiJalur($jalurAcak);    					//tentukan jarak yang ditempuh

		//Jika nilai jalur acak ternyata lebih rendah dari nilai jalur terpendek lebah tersebut
		//maka lebah pencari menemukan solusi yang lebih baik dari solusi yang pernah ditemukan sebelumnya
		If ($nilaiJalurAcak < $this->daftarLebah[$i]->nilaiJalur){
			//Ambil jalur ini sebagai jalur terbaik lebah tersebut
			$this->daftarLebah[$i]->jalur = $jalurAcak;
			$this->daftarLebah[$i]->nilaiJalur = $nilaiJalurAcak;

			//Apabila jalur acak ternyata lebih baik dari solusi umum
			//maka Ambil jalur ini sebagai solusi umum
			If ($this->daftarLebah[$i]->nilaiJalur < $this->nilaiJalurTerbaik){
				$this->jalurTerbaik = $this->daftarLebah[$i]->jalur;			//ambil Jalur ini sebagai jalur terbaik
				$this->nilaiJalurTerbaik = $this->daftarLebah[$i]->nilaiJalur;
				
				$this->teks .= "Iterasi " . ($iterasi+1) . ", jalur terbaik sementara = " . "<br />";
				$this->teks .= $this->cetakJalur($this->jalurTerbaik) . "<br />";
				$this->teks .= "dengan nilai jalur terbaik sementara = " . $this->nilaiJalurTerbaik . "<br />";
				$this->daftarOutput[$this->idxOutput][0] = "Iterasi " . ($iterasi+1);
				$this->daftarOutput[$this->idxOutput][1] = $this->cetakJalur($this->jalurTerbaik);
				$this->daftarOutput[$this->idxOutput][2] = $this->nilaiJalurTerbaik;
				$this->idxOutput += 1;
			}

			//Lakukan Tarian Waggle
			$this->TarianWaggle($i);
		}
	}

	//Gunakan fungsi ini apabila lebah yang sedang diproses adalah lebah non aktif
	Private Function ProsesLebahNonaktif($i, $iterasi){
		//Tidak ada yang perlu dilakukan
		Return;
	}

	//Lakukan fungsi ini jika jalur baru telah diterima oleh lebah
	//Dalam dunia nyata, tarian waggle dilakukan oleh lebah untuk mengirimkan informasi kepada teman lebahnya mengenai sumber makanan yang lebih baik
	//Penjelasan lebih detail tentang fungsi ini dapat dilihat pada penjelasan skrip dibawah ini
	Private Function TarianWaggle($i){
		//Lakukan perulangan untuk setiap lebah nonaktif
		For ($ii = 0; $ii <= $this->totalLebahNonaktif - 1; $ii++){
			$b = $this->daftarindeksLebahNonaktif[$ii];  						//indeks lebah nonaktif
			If ($this->daftarLebah[$b]->status <> 0){
				Throw New Exception("Terjadi kesalahan: lebah ini bukan lebah nonaktif");
			}
			If ($this->daftarLebah[$b]->jumlahPerjalanan <> 0){
				Throw New Exception("Terjadi kesalahan: ditemukan lebah nonaktif dengan jumlah perjalanan tidak sama dengan 0");
			}

			//Jika jalur lebah pencari lebih baik dari jalur lebah nonaktif
			//Cari nilai acak untuk dibandingkan dengan probabilitas terbujuk
			//Jika nilai acak tersebut kurang dari probabilitas terbujuk, maka ambil jalur dari lebah pencari sebagai jalur lebah nonaktif
			If ($this->daftarLebah[$i]->nilaiJalur < $this->daftarLebah[$b]->nilaiJalur){
				$p = rand() / getrandmax();
				If ($this->probTerbujuk > $p){          						//apakah lebah nonAktif akan terbujuk oleh lebah pencari? (biasanya terbujuk, karena nilai probTerbujuk sangat tinggi, ~0.90)
					$this->daftarLebah[$b]->jalur = $this->daftarLebah[$i]->jalur;
					$this->daftarLebah[$b]->nilaiJalur = $this->daftarLebah[$i]->nilaiJalur;
				}
			}
		}
	}
}

try{
	session_start();
	include "../../config.php";
	
	// echo "<pre>";
	$teks = "Dengan menggunakan Algoritma Artificial Bee Colony" . "<br />";
	set_time_limit(300);
	// srand(0);
	
	// Tentukan jumlah lebah yang digunakan dalam perhitungan
	// Dalam dunia nyata, dalam 1 sarang lebah, biasanya terdapat 5.000 - 20.000 lebah
	// Diasumsikan dalam kasus ini, jumlah lebah hanya ada 10, karena semakin besar angkanya, semakin lama perhitungannya
	$totalLebah = 10;
	$teks .= "Total Lebah: " . $totalLebah . "<br />";
	
	// Tentukan jumlah lebah aktif, lebah nonaktif, dan lebah pencari
	// Jumlah ketiga jenis lebah ini harus sama dengan variabel jumlah lebah diatas
	// Dalam dunia nyata, perbandingan lebah aktif : lebah nonaktif : lebah pencari adalah kira-kira 75% : 10% : 15%
	// Diasumsikan dalam kasus ini, perbandingan tersebut akan digunakan untuk mendeklarasikan jumlah masing-masing jenis lebah
	$totalLebahAktif = 7;
	$totalLebahNonaktif = 1;
	$totalLebahPencari = 2;	
	$teks .= "Total Lebah Aktif: " . $totalLebahAktif . "<br />";
	$teks .= "Total Lebah Nonaktif: " . $totalLebahNonaktif . "<br />";
	$teks .= "Total Lebah Pencari: " . $totalLebahPencari . "<br />";
	
	// Tentukan jumlah maksimal perjalanan yang dapat dilakukan lebah sebelum harus kembali ke sarangnya
	// Ini mencegah seekor lebah keluar terlalu lama karena tidak mendapatkan perjalanan yang lebih baik dari perjalanan yang sudah ditempuhnya.
	// Diasumsikan dalam kasus ini, jumlah maksimal perjalanan adalah 10
	$totalPerjalanan = 10;
	$teks .= "Jumlah Maksimal Perjalanan: " . $totalPerjalanan . "<br />";
	
	// Tentukan jumlah iterasi yang dilakukan untuk masing-masing lebah
	// Semakin besar angkanya, semakin optimal hasil perhitungannya, tetapi semakin lama perhitungannya
	// Diasumsikan dalam kasus ini, jumlah iterasi adalah 500
	$totalIterasi = 500;
	$teks .= "Jumlah Iterasi: " . $totalIterasi . "<br />";
	$teks .= "<br />";

	// ambil data toko yang sudah dipilih menggunakan centang sebelumnya
	$pidToko = $_GET['idToko'];

	// simpan data toko ke dalam variabel
	$daftarToko = array();
	$idxToko=0;
	$sql=mysql_query("SELECT * from tb_daftar_toko WHERE id_toko in (".$pidToko.")");
	while ($row=mysql_fetch_array($sql)){
		$daftarToko[$idxToko] = Toko::newToko(
			$row["id_toko"], 
			$row["nama"], 
			$row["alamat"], 
			$row["lat"], 
			$row["lng"]);
		$idxToko++;
	}
	
	// Inisialisasi sarang lebah beserta semua obyek yang ada di dalamnya
	// Buat lebah sebanyak parameter total Lebah
	// Beri status pada masing-masing lebah sesuai dengan parameter total lebah aktif, total lebah nonaktif, total lebah pencari
	// Tentukan jalur acak pada masing-masing lebah, kemudian cari jejak terpendek sementara yang diperoleh secara acak 
	// Simpan jalur terbaik sementara berdasarkan jalur dengan jarak terpendek
	// Penjelasan lebih detail tentang fungsi ini dapat dilihat pada penjelasan skrip dibawah ini
	$sarangLebah = sarangLebah::newSarangLebah($totalLebah, $totalLebahNonaktif, $totalLebahAktif, $totalLebahPencari, $totalPerjalanan, $totalIterasi, $daftarToko, $teks);

	// Lakukan proses perhitungan sebanyak jumlah perulangan
	$sarangLebah->prosesPerhitungan();
	
	// Dapatkan teks untuk ditampilkan pada layar
	// $teks = $sarangLebah->teks;
	
	$teksKesimpulan = "Jalur terbaik telah ditemukan = " . "<br />";
	$teksKesimpulan .= $sarangLebah->cetakJalur($sarangLebah->jalurTerbaik) . "<br />";
	$teksKesimpulan .= "dengan nilai jalur terbaik = " . $sarangLebah->nilaiJalurTerbaik;
	
	// $teks .= "<br />" . $teksKesimpulan;
	
	// variabel untuk nantinya dapat dibaca oleh javascript
	$output = array(
        'pesan_kesalahan' => '',
        'pesan_tampil' => '',
        'jalurTerbaik' => '',
        'kesimpulan' => '',
        'teks' => '',
	);	
	
	$output["pesan_tampil"] = "Proses perhitungan telah selesai";
	$output["jalurTerbaik"] = $sarangLebah->jalurTerbaik;
	
	$isiTabel = "<table class='table table-bordered'>";
	$isiTabel .= "<thead>";
	$isiTabel .= "<tr>";
	$isiTabel .= "<th>Proses</th>";
	$isiTabel .= "<th>Jalur</th>";
	$isiTabel .= "<th>Nilai Jalur</th>";
	$isiTabel .= "</tr>";
	$isiTabel .= "</thead>";
	$isiTabel .= "<tbody>";

	For ($i = 0; $i < sizeof($sarangLebah->daftarOutput); $i++){
		if ($i == 0 || $i > $totalLebah){
			$isiTabel .= "<tr style='color:green'>";
		} else {
			$isiTabel .= "<tr>";
		}
		
		$isiTabel .= "<td>" . $sarangLebah->daftarOutput[$i][0] . "</td>";
		$isiTabel .= "<td>" . $sarangLebah->daftarOutput[$i][1] . "</td>";
		$isiTabel .= "<td>" . $sarangLebah->daftarOutput[$i][2] . "</td>";
		$isiTabel .= "</tr>";
	}
			
	$isiTabel .= "</tbody>";
	$isiTabel .= "</table>";
	
	// echo $isiTabel; //"<pre"; var_dump($isiTabel);
	// die;
	
	$output["kesimpulan"] = "<pre>" . $teksKesimpulan . "</pre>";
	$output["teks"] = "<pre>" . $teks . "</pre>" . $isiTabel;
	
    echo json_encode($output);
	
} catch (Exception $e){
	// $_SESSION['pesan_kesalahan']="Terjadi kesalahan saat melakukan proses. <br/>Pesan Kesalahan: " . $e->getMessage();
	// echo "Terjadi kesalahan saat melakukan proses. <br/>Pesan Kesalahan: " . $e->getMessage();
}
?>