-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 02 Feb 2026 pada 08.26
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
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `id_jenis_aset` int NOT NULL,
  `nip` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `halo_pst`
--

CREATE TABLE `halo_pst` (
  `id_halo_pst` int NOT NULL,
  `nama_halo_pst` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `halo_pst`
--

INSERT INTO `halo_pst` (`id_halo_pst`, `nama_halo_pst`) VALUES
(1, 'Kemiskinan'),
(3, 'Ketenagakerjaan'),
(4, 'Pelayanan Umum'),
(5, 'Pertanian'),
(6, 'Statistik Sektoral'),
(7, 'Pojok Statistik'),
(8, 'Desa Cantik'),
(9, 'Pertumbuhan Ekonomi'),
(10, 'Big Data'),
(11, 'Data Science');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL,
  `nama_jabatan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(1, 'Kepala BPS Kabupaten Bangkalan'),
(2, 'Statistisi Terampil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `tim` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `topik` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_penugasan` date NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  `dokumentasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `tim`, `topik`, `judul_kegiatan`, `tanggal_penugasan`, `tanggal_rilis`, `keterangan`, `status`, `dokumentasi`) VALUES
(1, 'PPID', 'Jumat Berkah', 'Acara Jumat Berkah Bulan Agustus', '2026-01-20', '2026-01-23', 'Kasih banyak MBG yah', 0, NULL),
(2, 'PPID', 'Jumat Berkah', 'Acara Jumat Berkah Bulan Agustus', '2026-01-21', '2026-01-21', 'aaaaaaaaaaaaaaaa', 0, NULL),
(3, 'Ya', 'Ya', 'Ya', '2026-01-15', '2026-01-31', 'Ya', 0, NULL),
(4, 'Ya2', 'Ya2', 'Ya2', '2026-01-28', '2026-01-31', 'Ya2', 1, '../uploads/dokumentasi/1770015184_Logo TIF.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_link`
--

CREATE TABLE `jadwal_link` (
  `id_jadwal_link` int NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_jenis_link` int NOT NULL,
  `id_jadwal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `nama_jenis`) VALUES
(1, 'Template Medsos'),
(2, 'Dokumentasi'),
(4, 'Pembinaan Kehumasan'),
(5, 'Kebutuhan Broadcast'),
(6, 'Galeri Foto'),
(7, 'Galeri Video'),
(8, 'Laporan'),
(9, 'Ruang Humas'),
(10, 'Brankas Humas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_aset`
--

CREATE TABLE `jenis_aset` (
  `id_jenis_aset` int NOT NULL,
  `nama_jenis_aset` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_aset`
--

INSERT INTO `jenis_aset` (`id_jenis_aset`, `nama_jenis_aset`) VALUES
(1, 'Visual');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_link`
--

CREATE TABLE `jenis_link` (
  `id_jenis_link` int NOT NULL,
  `nama_jenis_link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_pic`
--

CREATE TABLE `jenis_pic` (
  `id_jenis_pic` int NOT NULL,
  `nama_jenis_pic` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_pic`
--

INSERT INTO `jenis_pic` (`id_jenis_pic`, `nama_jenis_pic`) VALUES
(1, 'Narasi'),
(2, 'Medsos'),
(3, 'Design'),
(4, 'Ya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `link`
--

CREATE TABLE `link` (
  `id_link` int NOT NULL,
  `nama_link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `link`
--

INSERT INTO `link` (`id_link`, `nama_link`, `gambar`, `link`) VALUES
(8, 'BPS Sampang', '', 'https://bangkalankab.bps.go.id/id');

-- --------------------------------------------------------

--
-- Struktur dari tabel `media`
--

CREATE TABLE `media` (
  `id_media` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `topik` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `link` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_sub_jenis` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `media`
--

INSERT INTO `media` (`id_media`, `judul`, `topik`, `deskripsi`, `link`, `created_at`, `id_sub_jenis`) VALUES
(1, 'ayyaya', 'oii', 'wihhh', 'https://bangkalankab.bps.go.id/id', '2026-01-29 07:19:34', 5),
(2, 'ayoyo', 'Hari Berkah', 'intinya itu lah', 'https://youtu.be/0ULADG6Iq-s?si=wXrTld-ykTSCeoGA', '2026-01-30 06:57:38', 5),
(4, 'ahh malas', 'malass', 'oiii', 'https://drive.google.com/drive/u/0/folders/1IqVL27bOUIIqJqOGc-nAMSy3cjQwNJIk', '2026-01-30 07:26:43', 3),
(12, 'sensus 2026', 'sensus', 'aaa', 'https://drive.google.com/file/d/1qn1jZ0NG5m6EarnnC-tdqnu0JVga76tF/view?usp=sharing', '2026-02-02 08:02:13', 8),
(13, 'pendataan sensus', '2026', 'www', 'https://drive.google.com/drive/folders/1iForWwZ54uUgvTPPCGZHznTSmqwONbm-?usp=sharing', '2026-02-01 03:35:56', 9),
(15, 'landmark bangkalan', 'landmark bangkalan', 'aaaa', 'https://drive.google.com/drive/folders/1P3FWTYgH54fqwgOdaOtoTXB1okSkR6RA?usp=sharing', '2026-02-01 06:46:32', 14),
(16, 'Gedung kantor', 'Gedung', 'aaa', 'https://drive.google.com/drive/folders/1EJAun7zsJhfjXi7Iue9bcwFMWNjHRdOg?usp=sharing', '2026-02-01 07:17:20', 13),
(17, 'Sensus Ekonomi 2026', 'sensus', 'aaa', 'https://drive.google.com/drive/folders/1DMC9g_cYH9kQAPFMuEPscrl1sooykICT?usp=sharing', '2026-02-01 07:18:07', 12),
(18, 'Pegawai', 'Pegawai', 'aaa', 'https://drive.google.com/drive/folders/10kDigwyu6FhbkT0EDhK06OOfIyuWS2fm?usp=sharing', '2026-02-01 07:18:51', 11),
(19, 'Pimpinan', 'Pak Insaf', 'aaa', 'https://drive.google.com/drive/folders/17qHv-slqvUHwaxqCWHqXU3eNISoWDljU?usp=sharing', '2026-02-01 07:19:54', 12),
(20, 'capeknya', 'capek', 'aaa', 'https://drive.google.com/drive/folders/1DMC9g_cYH9kQAPFMuEPscrl1sooykICT?usp=sharing', '2026-02-01 13:00:35', 12),
(21, 'ihhh', 'capek', 'aaa', 'https://drive.google.com/drive/folders/1DMC9g_cYH9kQAPFMuEPscrl1sooykICT?usp=sharing', '2026-02-01 13:08:13', 12),
(22, 'capeknya', 'sensus', 'qqq', 'https://drive.google.com/drive/folders/1DPS7SXg0RKjgmcJ2orELz3-eEdW-TcUW?usp=sharing', '2026-02-01 14:39:46', 17),
(23, 'uiiii', 'sensus', 'qqq', 'https://drive.google.com/drive/folders/1DPS7SXg0RKjgmcJ2orELz3-eEdW-TcUW?usp=sharing', '2026-02-01 14:54:10', 17),
(24, 'asikkk', 'capek', 'aaaa', 'https://drive.google.com/drive/folders/1RcCoLxXfsNqGXJc2dEiyCbK_pn7oQYNr?usp=sharing', '2026-02-01 17:09:06', 21),
(25, 'aaa', 'adlah pokoknya', 'aaaa', 'https://drive.google.com/drive/folders/1k5WKGHiKni2VXQPgVoOUCiG2zSuKHQ63?usp=sharing', '2026-02-02 03:57:17', 6),
(26, 'aaaa', 'ayyy', 'awwww', 'https://drive.google.com/drive/folders/1YfxK8VSypHrnnFWGrAvVNtio8JlbiJxB?usp=sharing', '2026-02-02 04:41:44', 7),
(27, 'Organigram Humas', 'Organigram', 'BPS Bangkalan', '<div style=\"position: relative; width: 100%; height: 0; padding-top: 100.0000%;  padding-bottom: 0; box-shadow: 0 2px 8px 0 rgba(63,69,81,0.16); margin-top: 1.6em; margin-bottom: 0.9em; overflow: hidden;  border-radius: 8px; will-change: transform;\">   <iframe loading=\"lazy\" style=\"position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0;margin: 0;\"     src=\"https://www.canva.com/design/DAG_ftpWi2k/KOCL7HumwJahW8SKt23LTw/view?embed\" allowfullscreen=\"allowfullscreen\" allow=\"fullscreen\">   </iframe> </div> <a href=\"https:&#x2F;&#x2F;www.canva.com&#x2F;design&#x2F;DAG_ftpWi2k&#x2F;KOCL7HumwJahW8SKt23LTw&#x2F;view?utm_content=DAG_ftpWi2k&amp;utm_campaign=designshare&amp;utm_medium=embeds&amp;utm_source=link\" target=\"_blank\" rel=\"noopener\">struktur humas</a> oleh Nadiatul Khoir', '2026-02-02 07:01:41', 22),
(28, 'nadia', 'udah capek', 'aaaa', 'https://drive.google.com/drive/folders/1WWx2mtsOU6_PitIFzZd8oqTqv2EqI-qZ', '2026-02-02 07:50:49', 23);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `nip` bigint NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int NOT NULL,
  `nomor_telepon` bigint DEFAULT NULL,
  `id_jabatan` int NOT NULL,
  `id_role` int NOT NULL,
  `id_ppid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`nip`, `nama`, `password`, `email`, `foto_profil`, `status`, `nomor_telepon`, `id_jabatan`, `id_role`, `id_ppid`) VALUES
(19920410, 'Ahmad Wijaya', '$2y$10$rRoUya61NE5wJ1AiOK7gH.edIno7X7LS/sAjCHYn85wLdTc8HnEnK', 'ahmad@bps.go.id', NULL, 1, 83456789012, 2, 2, 2),
(230411100156, 'Kamila Mulya Fadila', '$2y$10$XBhAyXq1IWlRJhmE0kMpVuD89YOlkcleljbe.QNsuz.kAz9M4DEsS', 'fadilakamila21@gmail.com', '1769412076_230411100156.jpeg', 1, 87722539067, 2, 1, 3),
(230411100157, 'Aliya Zulfa Syafitri', '$2y$10$8t0wzZE1uV3DMs6RV.AiOOlhY5IQ5ccnPTdTA9oKwHWNR99UKabne', 'aliyazulfa123@gmail.com', NULL, 1, NULL, 2, 2, 1),
(230411100184, 'Nadiatul Khoir', '$2y$10$7WSs6w6hCVCb1dTpl0P8A.Igr58BhuWzB6hGaVdyEtdm2F8OaAf8G', 'khoirnadiatul@gmail.com', NULL, 1, 87722539067, 2, 2, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pic`
--

CREATE TABLE `pic` (
  `nip` bigint NOT NULL,
  `id_jadwal` int NOT NULL,
  `id_pic` int NOT NULL,
  `id_jenis_pic` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pic`
--

INSERT INTO `pic` (`nip`, `id_jadwal`, `id_pic`, `id_jenis_pic`) VALUES
(19920410, 3, 8, 1),
(19920410, 3, 9, 2),
(19920410, 3, 10, 3),
(19920410, 3, 11, 4),
(230411100184, 4, 12, 1),
(230411100156, 4, 13, 2),
(230411100156, 4, 14, 3),
(230411100156, 4, 15, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ppid`
--

CREATE TABLE `ppid` (
  `id_ppid` int NOT NULL,
  `nama_ppid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ppid`
--

INSERT INTO `ppid` (`id_ppid`, `nama_ppid`) VALUES
(1, 'Umum'),
(2, 'Sosial'),
(3, 'Produksi'),
(4, 'Distribusi'),
(5, 'Neraca'),
(6, 'Statistik Sektoral');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` int NOT NULL,
  `nama_role` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id_role`, `nama_role`) VALUES
(1, 'Admin'),
(2, 'Pegawai'),
(3, 'Developer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `skill`
--

CREATE TABLE `skill` (
  `id_skill` int NOT NULL,
  `nama_skill` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `skill`
--

INSERT INTO `skill` (`id_skill`, `nama_skill`) VALUES
(1, 'Data Contributor'),
(2, 'Content Creator'),
(5, 'Editor Photo & Layout'),
(6, 'Editor Video'),
(7, 'Photo & Videographer'),
(8, 'Talent'),
(9, 'Project Manager'),
(10, 'Copywriting '),
(11, 'Protokol'),
(12, 'MC'),
(13, ' Operator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_jenis`
--

CREATE TABLE `sub_jenis` (
  `id_sub_jenis` int NOT NULL,
  `nama_sub_jenis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_jenis` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sub_jenis`
--

INSERT INTO `sub_jenis` (`id_sub_jenis`, `nama_sub_jenis`, `id_jenis`) VALUES
(1, 'Potrait (4:5)', 1),
(2, 'Reels (9:16)', 1),
(3, 'Landscape (16:9)', 1),
(4, 'Pedoman Visual Medsos BPS', 1),
(5, 'Pembinaan Kehumasan', 4),
(6, 'Video Operator', 5),
(7, 'Template OBS Rilis', 5),
(8, 'Kegiatan BPS Bangkalan', 2),
(9, 'Pendataan Sensus Ekonomi 2026', 2),
(10, 'Pimpinan', 6),
(11, 'Pegawai', 6),
(12, 'Sensus Ekonomi 2026', 6),
(13, 'Gedung Kantor', 6),
(14, 'Landmark Bangkalan', 6),
(15, 'Kantor BPS Bangkalan', 7),
(16, 'Landmark Bangkalan', 7),
(17, 'Sensus Ekonomi 2026', 7),
(18, 'Pemanfaatan Adobe', 8),
(19, 'Konten SE2026', 8),
(20, 'Humas Bulanan', 8),
(21, 'Humas Tahunan', 8),
(22, 'Struktur Humas', 9),
(23, 'Brankas Humas', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_halo_pst`
--

CREATE TABLE `user_halo_pst` (
  `nip` bigint NOT NULL,
  `id_halo_pst` int NOT NULL,
  `id_user_halo_pst` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_halo_pst`
--

INSERT INTO `user_halo_pst` (`nip`, `id_halo_pst`, `id_user_halo_pst`) VALUES
(230411100157, 3, 23),
(19920410, 1, 29),
(230411100184, 11, 32),
(230411100184, 1, 33),
(230411100184, 3, 34),
(230411100156, 1, 35);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_skill`
--

CREATE TABLE `user_skill` (
  `nip` bigint NOT NULL,
  `id_skill` int NOT NULL,
  `id_user_skill` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_skill`
--

INSERT INTO `user_skill` (`nip`, `id_skill`, `id_user_skill`) VALUES
(19920410, 2, 27),
(230411100184, 2, 30),
(230411100184, 1, 31),
(230411100156, 1, 32);

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id_aset`),
  ADD KEY `jenis` (`id_jenis_aset`),
  ADD KEY `aset_ibfk_1` (`nip`);

--
-- Indeks untuk tabel `halo_pst`
--
ALTER TABLE `halo_pst`
  ADD PRIMARY KEY (`id_halo_pst`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indeks untuk tabel `jadwal_link`
--
ALTER TABLE `jadwal_link`
  ADD PRIMARY KEY (`id_jadwal_link`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_jenis_link` (`id_jenis_link`);

--
-- Indeks untuk tabel `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indeks untuk tabel `jenis_aset`
--
ALTER TABLE `jenis_aset`
  ADD PRIMARY KEY (`id_jenis_aset`);

--
-- Indeks untuk tabel `jenis_link`
--
ALTER TABLE `jenis_link`
  ADD PRIMARY KEY (`id_jenis_link`);

--
-- Indeks untuk tabel `jenis_pic`
--
ALTER TABLE `jenis_pic`
  ADD PRIMARY KEY (`id_jenis_pic`);

--
-- Indeks untuk tabel `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id_link`);

--
-- Indeks untuk tabel `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id_media`),
  ADD KEY `id_sub_jenis` (`id_sub_jenis`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `menjabat` (`id_jabatan`),
  ADD KEY `sebagai` (`id_role`),
  ADD KEY `bagian` (`id_ppid`);

--
-- Indeks untuk tabel `pic`
--
ALTER TABLE `pic`
  ADD PRIMARY KEY (`id_pic`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `nip` (`nip`),
  ADD KEY `id_jenis_pic` (`id_jenis_pic`);

--
-- Indeks untuk tabel `ppid`
--
ALTER TABLE `ppid`
  ADD PRIMARY KEY (`id_ppid`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id_skill`);

--
-- Indeks untuk tabel `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD PRIMARY KEY (`id_sub_jenis`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indeks untuk tabel `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  ADD PRIMARY KEY (`id_user_halo_pst`),
  ADD KEY `id_halo_pst` (`id_halo_pst`),
  ADD KEY `nip` (`nip`);

--
-- Indeks untuk tabel `user_skill`
--
ALTER TABLE `user_skill`
  ADD PRIMARY KEY (`id_user_skill`),
  ADD KEY `nip` (`nip`),
  ADD KEY `id_skill` (`id_skill`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aset`
--
ALTER TABLE `aset`
  MODIFY `id_aset` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `halo_pst`
--
ALTER TABLE `halo_pst`
  MODIFY `id_halo_pst` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `jadwal_link`
--
ALTER TABLE `jadwal_link`
  MODIFY `id_jadwal_link` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `jenis_aset`
--
ALTER TABLE `jenis_aset`
  MODIFY `id_jenis_aset` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jenis_link`
--
ALTER TABLE `jenis_link`
  MODIFY `id_jenis_link` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jenis_pic`
--
ALTER TABLE `jenis_pic`
  MODIFY `id_jenis_pic` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `link`
--
ALTER TABLE `link`
  MODIFY `id_link` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `media`
--
ALTER TABLE `media`
  MODIFY `id_media` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `pic`
--
ALTER TABLE `pic`
  MODIFY `id_pic` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `ppid`
--
ALTER TABLE `ppid`
  MODIFY `id_ppid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `skill`
--
ALTER TABLE `skill`
  MODIFY `id_skill` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `sub_jenis`
--
ALTER TABLE `sub_jenis`
  MODIFY `id_sub_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  MODIFY `id_user_halo_pst` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `user_skill`
--
ALTER TABLE `user_skill`
  MODIFY `id_user_skill` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jenis` FOREIGN KEY (`id_jenis_aset`) REFERENCES `jenis_aset` (`id_jenis_aset`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_link`
--
ALTER TABLE `jadwal_link`
  ADD CONSTRAINT `jadwal_link_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_link_ibfk_2` FOREIGN KEY (`id_jenis_link`) REFERENCES `jenis_link` (`id_jenis_link`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`id_sub_jenis`) REFERENCES `sub_jenis` (`id_sub_jenis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `bagian` FOREIGN KEY (`id_ppid`) REFERENCES `ppid` (`id_ppid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menjabat` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sebagai` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pic`
--
ALTER TABLE `pic`
  ADD CONSTRAINT `pic_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pic_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pic_ibfk_3` FOREIGN KEY (`id_jenis_pic`) REFERENCES `jenis_pic` (`id_jenis_pic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD CONSTRAINT `sub_jenis_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  ADD CONSTRAINT `user_halo_pst_ibfk_1` FOREIGN KEY (`id_halo_pst`) REFERENCES `halo_pst` (`id_halo_pst`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_halo_pst_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_skill`
--
ALTER TABLE `user_skill`
  ADD CONSTRAINT `user_skill_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_skill_ibfk_2` FOREIGN KEY (`id_skill`) REFERENCES `skill` (`id_skill`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
