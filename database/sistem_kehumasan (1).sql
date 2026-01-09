-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Jan 2026 pada 00.46
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
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `jenis` int DEFAULT NULL,
  `pemegang` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aset`
--

INSERT INTO `aset` (`id_aset`, `nama`, `link`, `keterangan`, `jenis`, `pemegang`) VALUES
(1, 'Kamera', NULL, 'Merk Canon EOS 1500D', 1, 7),
(2, 'Printer Kantor', NULL, 'HP LaserJet Pro M404dn', 2, 4),
(3, 'Infografis Kemiskinan', 'https://bangkalan.bps.go.id', 'Desain Adobe Illustrator', 1, 8),
(4, 'Lisensi Microsoft Office', 'https://www.microsoft.com', 'Office 2021 Professional Plus', 3, 7),
(5, 'Video Profil BPS', 'https://youtube.com', 'Resolusi Full HD 1080p', 1, 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `tim` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `topik` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_penugasan` date DEFAULT NULL,
  `target_rilis` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `pic_desain` int DEFAULT NULL,
  `pic_narasi` int DEFAULT NULL,
  `pic_medsos` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `dokumentasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link_instagram` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link_facebook` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link_youtube` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link_website` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `tim`, `topik`, `judul_kegiatan`, `tanggal_penugasan`, `target_rilis`, `keterangan`, `pic_desain`, `pic_narasi`, `pic_medsos`, `status`, `dokumentasi`, `link_instagram`, `link_facebook`, `link_youtube`, `link_website`) VALUES
(1, 'Humas', 'Publikasi', 'Rilis Berita Statistik Bulanan', '2026-01-05', '2026-01-10', 'Konten rilis statistik Januari', 1, 4, 5, 0, 'belum', NULL, NULL, NULL, NULL),
(2, 'Produksi', 'Survei', 'Kegiatan Survei Lapangan', '2026-01-06', '2026-01-15', 'Dokumentasi kegiatan survei', 4, 5, 6, 1, 'belum', NULL, NULL, NULL, NULL),
(3, 'Distribusi', 'Infografis', 'Penyebaran Infografis Inflasi', '2026-01-07', '2026-01-12', 'Distribusi konten inflasi', 5, 6, 7, 1, 'belum', 'https://instagram.com/post2', 'https://facebook.com/post2', NULL, NULL),
(4, 'IPDS', 'Data', 'Pembaruan Metadata Statistik', '2026-01-08', '2026-01-20', 'Update metadata website', 6, 7, 1, 0, 'belum', NULL, NULL, NULL, 'https://bps.go.id/metadata'),
(5, 'Sosial', 'Sosialisasi', 'Sosialisasi Data Kemiskinan', '2026-01-09', '2026-01-18', 'Materi sosialisasi publik', 7, 1, 4, 2, 'ada', 'https://instagram.com/post3', NULL, 'https://youtube.com/video1', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `nama_jenis`) VALUES
(1, 'Template Medsos'),
(2, 'Dokumentasi'),
(3, 'Galeri Foto'),
(4, 'Galeri Video'),
(5, 'Laporan'),
(6, 'Pembinaan Kehumasan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `media`
--

CREATE TABLE `media` (
  `id_media` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `topik` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_sub_jenis` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `media`
--

INSERT INTO `media` (`id_media`, `judul`, `topik`, `deskripsi`, `link`, `id_sub_jenis`, `created_at`) VALUES
(1, 'Template Infografis Inflasi', 'Template Medsos', 'Template portrait untuk konten inflasi bulanan', 'https://drive.google.com/template-inflasi', 1, '2026-01-04 19:00:00'),
(2, 'Reels Sensus Ekonomi 2026', 'Sensus Ekonomi', 'Template reels untuk promosi Sensus Ekonomi 2026', 'https://instagram.com/reels/se2026', 2, '2026-01-05 20:15:00'),
(3, 'Dokumentasi Kegiatan BPS Bangkalan', 'Dokumentasi', 'Foto dan video kegiatan resmi BPS Bangkalan', 'https://drive.google.com/dokumentasi-bps', 5, '2026-01-06 23:30:00'),
(4, 'Galeri Foto Landmark Bangkalan', 'Galeri Foto', 'Kumpulan foto landmark Bangkalan resolusi tinggi', 'https://bps.go.id/galeri-landmark', 11, '2026-01-08 00:00:00'),
(5, 'Laporan Humas Bulanan Januari', 'Laporan', 'Laporan kegiatan kehumasan bulan Januari', 'https://bps.go.id/laporan-humas-jan', 17, '2026-01-08 18:45:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_jenis`
--

CREATE TABLE `sub_jenis` (
  `id_sub_jenis` int NOT NULL,
  `id_jenis` int DEFAULT NULL,
  `nama_sub_jenis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sub_jenis`
--

INSERT INTO `sub_jenis` (`id_sub_jenis`, `id_jenis`, `nama_sub_jenis`) VALUES
(1, 1, 'Portrait (4:5)'),
(2, 1, 'Reels (9:16)'),
(3, 1, 'Landscape (16:9)'),
(4, 1, 'Pedoman Visual Medsos BPS'),
(5, 2, 'Kegiatan BPS Bangkalan'),
(6, 2, 'Pendataan Sensus Ekonomi 2026'),
(7, 3, 'Pimpinan'),
(8, 3, 'Pegawai'),
(9, 3, 'Sensus Ekonomi 2026'),
(10, 3, 'Gedung Kantor'),
(11, 3, 'Landmark Bangkalan'),
(12, 4, 'Kantor BPS Bangkalan'),
(13, 4, 'Landmark Bangkalan'),
(14, 4, 'Sensus Ekonomi 2026'),
(15, 5, 'Pemanfaatan Adobe'),
(16, 5, 'Konten SE2026'),
(17, 5, 'Humas Bulanan'),
(18, 5, 'Humas Tahunan'),
(19, 6, 'Pembinaan Kehumasan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` int DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int DEFAULT NULL,
  `nip` bigint DEFAULT NULL,
  `role_humas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `skill_operator` int DEFAULT NULL,
  `reset_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_expired` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `password`, `role`, `email`, `foto_profil`, `status`, `nip`, `role_humas`, `jabatan`, `nomor_telepon`, `skill_data_contributor`, `skill_content_creator`, `skill_editor_photo_layout`, `skill_editor_video`, `skill_photo_videographer`, `skill_talent`, `skill_project_manager`, `skill_copywriting`, `skill_protokol`, `skill_mc`, `skill_operator`, `reset_token`, `reset_expired`) VALUES
(1, 'Administrasi', '123', 1, 'bps35260@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Insaf Santoso SST, M.Si.', '123', 2, 'insaf.bps3526@gmail.com', '', 1, 197701221999011001, 'Teknisi', 'Kepala BPS Kabupaten Bangkalan', 85746552414, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, NULL),
(3, 'Zhoemaroh SE, MM', '123', 2, 'zhoe.bps3526@gmail.com', NULL, 1, 196902141994012001, NULL, 'Kepala Subbagian Umum Kabupaten Bangkalan', 81937370792, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, NULL, NULL),
(4, 'Abdul Mokti', '123', 2, 'abdul.mokti.bps3526@gmail.com', NULL, 1, 196805042012121006, NULL, 'Staf Subbagian Umum', 81934601802, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, NULL, NULL),
(5, 'Nia Nurma Faiza A.Md, SE', '123', 2, 'nianurma.bps3526@gmail.com', NULL, 1, 199102052012122004, NULL, 'Pranata Keuangan APBN Mahir', 82229854422, 1, 0, 0, 0, 0, 1, 0, 1, 1, 0, 1, NULL, NULL),
(6, 'Ach. Haris Sidik SE', '123', 2, 'haris.sidik.bps3526@gmail.com', NULL, 1, 196810261989021001, NULL, 'Statistisi Penyelia', 81937284486, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, NULL),
(7, 'Alfin Niam Habibi A.Md. Stat', '123', 2, 'alfin.habibi.bps3526@gmail.com', NULL, 1, 199811112022031006, NULL, 'Statistisi Terampil', 89509565626, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, NULL, NULL),
(8, 'Anggraini Nur Agustina A.Md.Stat.', '123', 2, 'anggraini.nur.bps3526@gmail.com', NULL, 1, 199408312022032007, NULL, 'Statistisi Terampil', 85735431001, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, NULL, NULL),
(9, 'Aris Kuswantoro SE.,M.M.', '123', 2, 'akuswantoro.bps3526@gmail.com', NULL, 1, 197402041994011001, NULL, 'Statistisi Ahli Muda', 8165430340, 1, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, NULL, NULL),
(10, 'Citra Dian Etika S.Si', '123', 2, 'citra.dian.bps3526@gmail.com', NULL, 1, 198510152011012015, NULL, 'Statistisi Ahli Muda', 85396242182, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(11, 'Dhony Susfantori S.M.', '123', 2, 'dhonys.bps3526@gmail.com', NULL, 1, 197912162006041012, NULL, 'Statistisi Terampil', 83850177151, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, NULL, NULL),
(12, 'Dwi Muklis SST', '123', 2, 'dwimuklis.bps3526@gmail.com', NULL, 1, 199009032014101001, NULL, 'Statistisi Ahli Pertama', 81337322721, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, NULL, NULL),
(13, 'Dwi Widianis SST, SE.,M.Si', '123', 2, 'widianis.bps3526@gmail.com', NULL, 1, 198203202006021001, NULL, 'Statistisi Ahli Muda', 81353077919, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(14, 'Erlisa Wahyu Pratiwi S.Si', '123', 2, 'Statistisi Ahli Muda', NULL, 1, 198302122009022009, NULL, 'Statistisi Ahli Muda', 85230313534, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(15, 'Hendra Adhikara S.ST, MM', '123', 2, 'adhikara.bps3526@gmail.com', NULL, 1, 197711071998031004, NULL, 'Pranata Komputer Ahli Muda', 81336023023, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, NULL, NULL),
(16, 'Heru Priambodo SE', '123', 2, 'hpriam.bps3526@gmail.com', NULL, 1, 197301212007011005, NULL, 'Statistisi Ahli Pertama', 81279666677, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, NULL, NULL),
(17, 'Hizbullah Gunawan SE., MM', '123', 2, 'hizbullah.bps3526@gmail.com', NULL, 1, 197304072006041022, NULL, 'Statistisi Ahli Muda', 81357766663, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, NULL, NULL),
(18, 'Indah Putri Rahayu S.Si.', '123', 2, 'indahp.bps3526@gmail.com', NULL, 1, 199212112019032001, NULL, 'Statistisi Ahli Pertama', 8993663690, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, NULL, NULL),
(19, 'Ir. Hariyanto MM', '123', 2, 'hariyanto4.bps3526@gmail.com', NULL, 1, 196711231994011001, NULL, 'Statistisi Ahli Muda', 8133104182, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, NULL),
(20, 'Istian Hendriyanto A.Md', '123', 2, 'istian.bps3526@gmail.com', NULL, 1, 198611082011011012, NULL, 'Statistisi Mahir', 85234577787, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(21, 'Linda Kuncasari S.Tr.Stat.', '123', 2, 'linda.kuncasari.bps3526@gmail.com', NULL, 1, 199702282019122003, NULL, 'Statistisi Ahli Pertama', 87753473530, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, NULL, NULL),
(22, 'Mohammad Sakir SE, M.M', '123', 2, 'moch.sakir.bps3526@gmail.com', NULL, 1, 196901011991011001, NULL, 'Statistisi Penyelia', 81703303373, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(23, 'Mohammad Soleh TC SE', '123', 2, 'solehtc.bps3526@gmail.com', NULL, 1, 197805071999031001, NULL, 'Statistisi Mahir', 82345697578, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(24, 'Mohlis S.E.', '123', 2, 'mohlis.bps3526@gmail.com', NULL, 1, 198006252009111001, NULL, 'Statistisi Ahli Pertama', 82231066373, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(25, 'Ridnu Witardi S.E.', '123', 2, 'ridnuw.bps3526@gmail.com', NULL, 1, 198503272007101002, NULL, 'Statistisi Ahli Pertama', 85731395983, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(26, 'Tatok Mulyo Mintartok S.Sos', '123', 2, 'tatokm.bps3526@gmail.com', NULL, 1, 197301162006041011, NULL, 'Statistisi Ahli Muda', 82334415041, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(27, 'Tedy Wahyudi SE, MM', '123', 2, 'tedy.wahyudi.bps3526@gmail.com', NULL, 1, 197204061994011001, NULL, 'Statistisi Ahli Muda', 82135370036, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(28, 'Whistra Pariata Utama A.Md', '123', 2, 'whistra.bps3526@gmail.com', NULL, 1, 198702272009021003, NULL, 'Statistisi Mahir', 81390534514, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL),
(29, 'Yeni Arisanti SE, MM.', '123', 2, 'y.arisanti.bps3526@gmail.com', NULL, 1, 197501171994012001, NULL, 'Analis Pengelolaan Keuangan APBN Ahli Muda', 85258444515, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(30, 'Radita Nareswari Mumpuni Putri S.Tr.Stat.', '123', 2, 'radita.nareswari.bps3526@gmail.com', NULL, 1, 199705152019012001, NULL, 'Statistisi Ahli Pertama', 81252356456, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(31, 'Peni Dwi Wahyu Winarsi S.Stat.', '456', 2, 'peni.dwi.bps3526@gmail.com', '1767848138_31.JPG', 1, 197205211991012001, '1', 'Statistisi Ahli Muda', 82220807770, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, NULL, NULL),
(32, 'Nadia', '456', 2, 'khoirnadiatul@gmail.com', '1767859957_32.JPG', 1, 230411100184, '1', 'Mahasiswa', 82330822320, 0, 0, 1, 0, 1, 0, 1, 0, 1, 0, 0, 'b810b719f674c200f1e3e7e21d1ec85e5f9374da541a918507532f68ddc839c3', '2026-01-09 07:55:31');

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
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aset`
--
ALTER TABLE `aset`
  MODIFY `id_aset` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `media`
--
ALTER TABLE `media`
  MODIFY `id_media` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sub_jenis`
--
ALTER TABLE `sub_jenis`
  MODIFY `id_sub_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
