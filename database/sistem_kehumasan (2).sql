-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 03:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_kehumasan`
--

-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `id_aset` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `id_jenis_aset` int(11) NOT NULL,
  `nip` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `halo_pst`
--

CREATE TABLE `halo_pst` (
  `id_halo_pst` int(11) NOT NULL,
  `nama_halo_pst` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `tim` varchar(255) NOT NULL,
  `topik` varchar(255) NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tanggal_penugasan` date NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `keterangan` text NOT NULL,
  `status` int(11) NOT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL,
  `link_instagram` varchar(255) DEFAULT NULL,
  `link_facebook` varchar(255) DEFAULT NULL,
  `link_youtube` varchar(255) DEFAULT NULL,
  `link_website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int(11) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_aset`
--

CREATE TABLE `jenis_aset` (
  `id_jenis_aset` int(11) NOT NULL,
  `nama_jenis_aset` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pic`
--

CREATE TABLE `jenis_pic` (
  `id_jenis_pic` int(11) NOT NULL,
  `nama_jenis_pic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `link`
--

CREATE TABLE `link` (
  `id_link` int(11) NOT NULL,
  `nama_link` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id_media` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `topik` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_sub_jenis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pic`
--

CREATE TABLE `pic` (
  `nip` bigint(20) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_pic` int(11) NOT NULL,
  `id_jenis_pic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppid`
--

CREATE TABLE `ppid` (
  `id_ppid` int(11) NOT NULL,
  `nama_ppid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `id_skill` int(11) NOT NULL,
  `nama_skill` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_jenis`
--

CREATE TABLE `sub_jenis` (
  `id_sub_jenis` int(11) NOT NULL,
  `nama_sub_jenis` varchar(255) NOT NULL,
  `id_jenis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `nip` bigint(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `nomor_telepon` bigint(20) DEFAULT NULL,
  `id_jabatan` int(11) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_halo_pst`
--

CREATE TABLE `user_halo_pst` (
  `nip` bigint(20) NOT NULL,
  `id_halo_pst` int(11) NOT NULL,
  `id_user_halo_pst` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_ppid`
--

CREATE TABLE `user_ppid` (
  `nip` bigint(20) NOT NULL,
  `id_ppid` int(11) NOT NULL,
  `id_user_ppid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_skill`
--

CREATE TABLE `user_skill` (
  `nip` bigint(20) NOT NULL,
  `id_skill` int(11) NOT NULL,
  `id_user_skill` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id_aset`),
  ADD KEY `jenis` (`id_jenis_aset`),
  ADD KEY `aset_ibfk_1` (`nip`);

--
-- Indexes for table `halo_pst`
--
ALTER TABLE `halo_pst`
  ADD PRIMARY KEY (`id_halo_pst`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `jenis_aset`
--
ALTER TABLE `jenis_aset`
  ADD PRIMARY KEY (`id_jenis_aset`);

--
-- Indexes for table `jenis_pic`
--
ALTER TABLE `jenis_pic`
  ADD PRIMARY KEY (`id_jenis_pic`);

--
-- Indexes for table `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id_link`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id_media`),
  ADD KEY `id_sub_jenis` (`id_sub_jenis`);

--
-- Indexes for table `pic`
--
ALTER TABLE `pic`
  ADD PRIMARY KEY (`id_pic`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `nip` (`nip`),
  ADD KEY `id_jenis_pic` (`id_jenis_pic`);

--
-- Indexes for table `ppid`
--
ALTER TABLE `ppid`
  ADD PRIMARY KEY (`id_ppid`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id_skill`);

--
-- Indexes for table `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD PRIMARY KEY (`id_sub_jenis`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `menjabat` (`id_jabatan`),
  ADD KEY `sebagai` (`id_role`);

--
-- Indexes for table `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  ADD PRIMARY KEY (`id_user_halo_pst`),
  ADD KEY `id_halo_pst` (`id_halo_pst`),
  ADD KEY `nip` (`nip`);

--
-- Indexes for table `user_ppid`
--
ALTER TABLE `user_ppid`
  ADD PRIMARY KEY (`id_user_ppid`),
  ADD KEY `id_ppid` (`id_ppid`),
  ADD KEY `nip` (`nip`);

--
-- Indexes for table `user_skill`
--
ALTER TABLE `user_skill`
  ADD PRIMARY KEY (`id_user_skill`),
  ADD KEY `nip` (`nip`),
  ADD KEY `id_skill` (`id_skill`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aset`
--
ALTER TABLE `aset`
  MODIFY `id_aset` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `halo_pst`
--
ALTER TABLE `halo_pst`
  MODIFY `id_halo_pst` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_aset`
--
ALTER TABLE `jenis_aset`
  MODIFY `id_jenis_aset` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pic`
--
ALTER TABLE `jenis_pic`
  MODIFY `id_jenis_pic` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `link`
--
ALTER TABLE `link`
  MODIFY `id_link` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id_media` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pic`
--
ALTER TABLE `pic`
  MODIFY `id_pic` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppid`
--
ALTER TABLE `ppid`
  MODIFY `id_ppid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skill`
--
ALTER TABLE `skill`
  MODIFY `id_skill` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_jenis`
--
ALTER TABLE `sub_jenis`
  MODIFY `id_sub_jenis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  MODIFY `id_user_halo_pst` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_ppid`
--
ALTER TABLE `user_ppid`
  MODIFY `id_user_ppid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_skill`
--
ALTER TABLE `user_skill`
  MODIFY `id_user_skill` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `user` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jenis` FOREIGN KEY (`id_jenis_aset`) REFERENCES `jenis_aset` (`id_jenis_aset`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`id_sub_jenis`) REFERENCES `sub_jenis` (`id_sub_jenis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pic`
--
ALTER TABLE `pic`
  ADD CONSTRAINT `pic_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pic_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `user` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pic_ibfk_3` FOREIGN KEY (`id_jenis_pic`) REFERENCES `jenis_pic` (`id_jenis_pic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_jenis`
--
ALTER TABLE `sub_jenis`
  ADD CONSTRAINT `sub_jenis_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `menjabat` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sebagai` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_halo_pst`
--
ALTER TABLE `user_halo_pst`
  ADD CONSTRAINT `user_halo_pst_ibfk_1` FOREIGN KEY (`id_halo_pst`) REFERENCES `halo_pst` (`id_halo_pst`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_halo_pst_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `user` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_ppid`
--
ALTER TABLE `user_ppid`
  ADD CONSTRAINT `user_ppid_ibfk_1` FOREIGN KEY (`id_ppid`) REFERENCES `ppid` (`id_ppid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ppid_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `user` (`nip`);

--
-- Constraints for table `user_skill`
--
ALTER TABLE `user_skill`
  ADD CONSTRAINT `user_skill_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `user` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_skill_ibfk_2` FOREIGN KEY (`id_skill`) REFERENCES `skill` (`id_skill`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
