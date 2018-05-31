-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 23 Apr 2018 pada 15.07
-- Versi Server: 5.6.24
-- PHP Version: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `akun`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_daftar_toko`
--

CREATE TABLE IF NOT EXISTS `tb_daftar_toko` (
  `id_toko` int(11) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `alamat` varchar(70) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_daftar_toko`
--

INSERT INTO `tb_daftar_toko` (`id_toko`, `nama`, `alamat`, `lat`, `lng`) VALUES
(1, 'Nida Food', 'Mudal, Mojotengah, Kabupaten Wonosobo, Jawa Tengah', -7.334210, 109.916000),
(20, 'Toko Sadina', ' Jl. Raya Wonosobo - Kertek No.90, Kertek, Wonosobo', -7.387544, 109.962189),
(24, 'Toko Bu Lukito', 'Kauman Sel, Wonosobo Tim, Wonosobo', -7.355452, 109.902679),
(25, 'Toko Tambi', ' Jl Tumenggung Jogonegoro No.39, Jaraksari, Wonosobo', -7.378833, 109.897552),
(26, 'Toko Sabar Makmur 1', 'Sudungdewo, Kertek, Kabupaten Wonosobo', -7.371065, 109.939262),
(27, 'Toko Sabar Makmur 2', 'Sudungdewo, Kertek, Kabupaten Wonosobo, Jawa Tengah, Indonesia', -7.372464, 109.939430),
(28, 'Toko Sri Rejeki 1', 'Binangun, Watumalang, Kabupaten Wonosobo, Jawa Tengah, Indonesia', -7.371485, 109.933342),
(30, 'Toko Sri Rejeki 2', 'Jl. Mayjen Bambang Sugeng, Kabupaten Wonosobo, Jawa Tengah, Indonesia', -7.364985, 109.918877),
(31, 'Toko Sekar Raos', 'Jl. Mayjen Bambang Sugeng, Km. 3, Mendolo, Bumireso, Kec. Wonosobo, Ka', -7.366198, 109.924652),
(32, 'Toko Pak Rujito', 'Jl. Dieng No.66 Kalianget Kec. Wonosobo Kabupaten Wonosobo', -7.346125, 109.907219),
(33, 'Toko Mahkota', 'Bojasari Kertek Kabupaten Wonosobo', -7.385865, 109.946892),
(34, 'Toko Harmoni', ' Jl. Raya Kertek KM 6, Siono, Bojasari, Kretek, Kabupaten Wonosobo', -7.377911, 109.950912),
(35, 'Toko Sumber Rejeki', 'Jl Kertek-Wonosobo Km 1, Krakal Dawung, Kertek, Karangluhur, Wonosobo', -7.383573, 109.957970),
(36, 'Toko Anugrah', 'Karangluhur, Kertek, Kabupaten Wonosobo, Jawa Tengah, Indonesia', -7.383436, 109.957016),
(37, 'Toko Ratu', ' JL. Raya Kertek Km. 6, Wonosobo', -7.381444, 109.952278),
(38, 'Toko Bintar Jaya', 'Jl. Banyumas Kabupaten Wonosobo', -7.409246, 109.886353),
(39, 'Toko Bu Siti', ' Jalan Kertek - Wonosobo Km. 1, Kertek, Karangluhur, Kabupaten Wonosob', -7.373550, 109.957916),
(40, 'Toko Bintang', ' JL Raya Kertek - Wonosobo, No. 33, Krakal, Kertek, Wonosobo', -7.386436, 109.960632),
(41, 'Toko Tiga Putri', ' Jl. Raya Kertek No.45, Bumireso, Kec. Wonosobo', -7.366383, 109.925362),
(42, 'Toko R.M selera rames', ' Jalan Jenderal Gatot Subroto No.63, Kertek, Kabupaten Wonosobo', -7.386678, 109.961159),
(43, 'Toko Optik Lucky', ' Jl Kertek- Wonosobo Km 1 No 42, Kertek, Kabupaten Wonosobo', -7.386324, 109.960197),
(44, 'Toko Amanda', ' Jl. Raya Kertek No. 157, Bumireso, Kertek, Kabupaten Wonosobo', -7.386699, 109.960815),
(45, 'Toko Carica Sumbing', ' Reco, Kertek, Kabupaten Wonosobo', -7.345713, 110.025589),
(46, 'Toko Agripina', ' Jalan Bismo No.77, Wonosobo Barat, Wonosobo', -7.356320, 109.901283),
(47, 'Toko Amalia', ' Jalan Wonosobo - Purworejo No.41, Karangluhur, Kertek, Wonosobo', -7.386646, 109.960953),
(48, 'Toko Wahyu', ' Jl Kertek - Wonosobo, Jambusari Rt. 3/ Rw. 7, Kertek, Kabupaten Wonos', -7.387079, 109.961800);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_sales`
--

CREATE TABLE IF NOT EXISTS `tb_sales` (
  `id_sales` varchar(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_sales`
--

INSERT INTO `tb_sales` (`id_sales`, `username`, `password`) VALUES
('1', 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_daftar_toko`
--
ALTER TABLE `tb_daftar_toko`
  ADD PRIMARY KEY (`id_toko`);

--
-- Indexes for table `tb_sales`
--
ALTER TABLE `tb_sales`
  ADD PRIMARY KEY (`id_sales`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_daftar_toko`
--
ALTER TABLE `tb_daftar_toko`
  MODIFY `id_toko` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
