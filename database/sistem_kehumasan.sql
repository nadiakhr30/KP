-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 07 Jan 2026 pada 02.13
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `sistem_kehumasan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aset`
--

CREATE TABLE `aset` (
  `id_aset` int NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `jenis` int DEFAULT NULL,
  `pemegang` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `tim` int DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `judul_kegiatan` varchar(255) DEFAULT NULL,
  `tanggal_penugasan` date DEFAULT NULL,
  `target_rilis` date DEFAULT NULL,
  `keterangan` text,
  `pic_desain` int DEFAULT NULL,
  `pic_narasi` int DEFAULT NULL,
  `pic_medsos` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL,
  `link_instagram` varchar(255) DEFAULT NULL,
  `link_facebook` varchar(255) DEFAULT NULL,
  `link_youtube` varchar(255) DEFAULT NULL,
  `link_website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int NOT NULL,
  `nama_jenis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `media`
--

CREATE TABLE `media` (
  `id_media` int NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `link` varchar(255) DEFAULT NULL,
  `id_sub_jenis` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_jenis`
--

CREATE TABLE `sub_jenis` (
  `id_sub_jenis` int NOT NULL,
  `id_jenis` int DEFAULT NULL,
  `nama_sub_jenis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` int DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `status` int DEFAULT NULL,
  `nip` bigint DEFAULT NULL,
  `role_humas` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `nomor_telepon` bigint DEFAULT NULL,
  `skill_data_contributor` int DEFAULT NULL,
  `skill_content_creator` int DEFAULT NULL,
  `skill_editor_photo_layout` int DEFAULT NULL,
  `skill_editor_video` int DEFAULT NULL,
  `skill_photo_videographer` int DEFAULT NULL,
  `skill_talent` int DEFAULT NULL,
  `skill_project_manager` int DEFAULT NULL,
  `skill_copywriting` int DEFAULT NULL,
  `skill_protokol` int DEFAULT NULL,
  `skill_mc` int DEFAULT NULL,
  `skill_operator` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `password`, `role`, `email`, `foto_profil`, `status`, `nip`, `role_humas`, `jabatan`, `nomor_telepon`, `skill_data_contributor`, `skill_content_creator`, `skill_editor_photo_layout`, `skill_editor_video`, `skill_photo_videographer`, `skill_talent`, `skill_project_manager`, `skill_copywriting`, `skill_protokol`, `skill_mc`, `skill_operator`) VALUES
(1, 'Administrasi', '123', 1, 'bps35260@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Insaf Santoso SST, M.Si.', '123', 2, 'insaf.bps3526@gmail.com\r\n', '', 1, 197701221999011001, 'Teknisi', 'Kepala BPS Kabupaten Bangkalan', 85746552414, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0),
(3, 'Zhoemaroh SE, MM\r\n', '123', 2, 'zhoe.bps3526@gmail.com\r\n', NULL, 1, 196902141994012001, NULL, 'Kepala Subbagian Umum Kabupaten Bangkalan\r\n', 81937370792, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(4, 'Abdul Mokti\r\n', '123', 2, 'abdul.mokti.bps3526@gmail.com\r\n\r\n', NULL, 1, 196805042012121006, NULL, 'Staf Subbagian Umum\r\n', 81934601802, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1),
(5, 'Nia Nurma Faiza A.Md, SE\r\n', '123', 2, 'nianurma.bps3526@gmail.com\r\n', NULL, 1, 199102052012122004, NULL, 'Pranata Keuangan APBN Mahir\r\n', 82229854422, 1, 0, 0, 0, 0, 1, 0, 1, 1, 0, 1),
(6, 'Ach. Haris Sidik SE\r\n', '123', 2, 'haris.sidik.bps3526@gmail.com\r\n', NULL, 1, 196810261989021001, NULL, 'Statistisi Penyelia\r\n', 81937284486, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0),
(7, 'Alfin Niam Habibi A.Md. Stat\r\n', '123', 2, 'alfin.habibi.bps3526@gmail.com\r\n', NULL, 1, 199811112022031006, NULL, 'Statistisi Terampil\r\n', 89509565626, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, 1),
(8, 'Anggraini Nur Agustina A.Md.Stat.\r\n', '123', 2, 'anggraini.nur.bps3526@gmail.com\r\n', NULL, 1, 199408312022032007, NULL, 'Statistisi Terampil\r\n', 85735431001, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1),
(9, 'Aris Kuswantoro SE.,M.M.\r\n', '123', 2, 'akuswantoro.bps3526@gmail.com\r\n', NULL, 1, 197402041994011001, NULL, 'Statistisi Ahli Muda\r\n', 8165430340, 1, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0),
(10, 'Citra Dian Etika S.Si\r\n', '123', 2, 'citra.dian.bps3526@gmail.com\r\n', NULL, 1, 198510152011012015, NULL, 'Statistisi Ahli Muda\r\n', 85396242182, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(11, 'Dhony Susfantori S.M.\r\n', '123', 2, 'dhonys.bps3526@gmail.com\r\n', NULL, 1, 197912162006041012, NULL, 'Statistisi Terampil\r\n', 83850177151, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1),
(12, 'Dwi Muklis SST\r\n', '123', 2, 'dwimuklis.bps3526@gmail.com\r\n', NULL, 1, 199009032014101001, NULL, 'Statistisi Ahli Pertama\r\n', 81337322721, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(13, 'Dwi Widianis SST, SE.,M.Si\r\n', '123', 2, 'widianis.bps3526@gmail.com\r\n', NULL, 1, 198203202006021001, NULL, 'Statistisi Ahli Muda\r\n', 81353077919, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id_aset`),
  ADD KEY `pemegang` (`pemegang`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `pic_desain` (`pic_desain`),
  ADD KEY `pic_narasi` (`pic_narasi`),
  ADD KEY `pic_medsos` (`pic_medsos`);

--
-- Indeks untuk tabel `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indeks untuk tabel `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id_media`),
  ADD KEY `id_sub_jenis` (`id_sub_jenis`);

--
-- Indeks untuk tabel `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD PRIMARY KEY (`id_sub_jenis`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`pemegang`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`pic_desain`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`pic_narasi`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`pic_medsos`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`id_sub_jenis`) REFERENCES `sub_jenis` (`id_sub_jenis`);

--
-- Ketidakleluasaan untuk tabel `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD CONSTRAINT `sub_jenis_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
