-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 07:11 AM
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
-- Database: `absensi_magang`
--
CREATE DATABASE IF NOT EXISTS `absensi_magang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `absensi_magang`;

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` int(11) NOT NULL,
  `jabatan` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `jabatan`) VALUES
(21, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(255) NOT NULL,
  `alamat_lokasi` varchar(255) NOT NULL,
  `tipe_lokasi` varchar(255) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius` int(11) NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `tipe_lokasi`, `latitude`, `longitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES
(1, 'PN Makassar', 'Jl. Niggacucimotor', 'Pusat', '-5.136298727688664', '119.41358871534342', 100, 'WITA', '07:30:00', '16:30:00'),
(7, 'rumah', 'Jl. Niggacucimotor', 'Cabang', '-5.143396426308132', '119.4531742264116', 100, 'WITA', '07:30:00', '15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(50) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `no_handphone` varchar(50) NOT NULL,
  `divisi` varchar(50) NOT NULL,
  `universitas` varchar(50) NOT NULL,
  `lokasi_presensi` varchar(225) NOT NULL,
  `foto` varchar(223) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `jenis_kelamin`, `alamat`, `no_handphone`, `divisi`, `universitas`, `lokasi_presensi`, `foto`) VALUES
(1, '13020220005 ', 'Ilham', 'Laki-laki', 'BTP', '08123456789', 'PTIP', 'UMI', 'Kantor Pengadilan', 'akbar.png'),
(2, '13020220023', 'Rehan', 'Perempuan', 'Urip', '08912', 'PTIP', 'UMI', 'Kantor Pengadilan', 'akbar.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_mahasiswa` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `status` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_mahasiswa`, `username`, `password`, `status`, `role`) VALUES
(1, 1, 'Tizar', '$2y$10$rjMufkGRZvQLvICHBktYJOFMl9xPYlu5tJyuob8hVfdrUxj09sPT.', 'Aktif', 'admin'),
(2, 2, 'Rehan', '$2y$10$rjMufkGRZvQLvICHBktYJOFMl9xPYlu5tJyuob8hVfdrUxj09sPT.', 'Aktif', 'mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_pegawai` (`id_mahasiswa`),
  ADD KEY `id_pegawai_2` (`id_mahasiswa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Database: `chat_app`
--
CREATE DATABASE IF NOT EXISTS `chat_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `chat_app`;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, 1, 'hai', '2024-12-13 06:24:20'),
(2, 1, 'hai', '2024-12-13 06:24:29'),
(3, 1, 'hai', '2024-12-13 06:25:02'),
(4, 1, 'hai', '2024-12-13 06:29:30'),
(5, 1, 'tu', '2024-12-13 06:29:45'),
(6, 1, 'vjj', '2024-12-13 06:29:51'),
(7, 1, 'hai', '2024-12-13 06:40:17'),
(8, 1, 'hai', '2024-12-13 06:40:34'),
(9, NULL, 'hai', '2024-12-13 06:46:24'),
(10, NULL, 'hai', '2024-12-13 06:46:43'),
(11, 1, 'hai', '2024-12-13 06:49:32'),
(12, 1, 'hai', '2024-12-13 06:49:48'),
(13, 2, 'hawo', '2024-12-13 06:51:28'),
(14, 2, 'hawo', '2024-12-13 06:51:37'),
(17, 3, 'hai', '2024-12-13 06:58:26'),
(18, 2, 'hawoo', '2024-12-13 06:58:30'),
(19, 2, 'hawoo', '2024-12-13 06:58:50'),
(20, 4, 'hai', '2024-12-13 07:00:59'),
(21, 2, 'hawoo', '2024-12-13 07:01:11'),
(22, 4, 'hai', '2024-12-13 07:10:20'),
(23, 2, 'hawoo', '2024-12-13 07:10:36'),
(24, 4, 'hai', '2024-12-13 07:14:09'),
(25, 2, 'hawo', '2024-12-13 07:14:43'),
(26, 4, 'tu', '2024-12-13 07:16:39'),
(27, 2, 'hawo', '2024-12-13 07:17:41'),
(28, 4, 'hai', '2024-12-13 07:27:29'),
(29, 4, 'hai', '2024-12-13 07:28:31'),
(30, 4, 'hai', '2024-12-13 07:28:37'),
(31, 4, 'hai', '2024-12-13 07:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `created_at`) VALUES
(1, 'rey', '2024-12-13 06:24:12'),
(2, 'edoo', '2024-12-13 06:51:18'),
(3, 'Ilham', '2024-12-13 06:58:18'),
(4, 'Reyayonima', '2024-12-13 07:00:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"sipetras\",\"table\":\"pengelola\"},{\"db\":\"sipetras\",\"table\":\"users\"},{\"db\":\"sipetras\",\"table\":\"nasabah\"},{\"db\":\"sipetras\",\"table\":\"laporan\"},{\"db\":\"sipetras\",\"table\":\"sampah\"},{\"db\":\"sipetras\",\"table\":\"transaksi\"},{\"db\":\"sister_chat\",\"table\":\"users\"},{\"db\":\"sister_chat\",\"table\":\"messages\"},{\"db\":\"chat_app\",\"table\":\"users\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

--
-- Dumping data for table `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'sipetras', 'nasabah', '{\"sorted_col\":\"`nasabah`.`email` ASC\"}', '2024-12-13 16:04:36');

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2024-12-14 11:23:27', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `sipetras`
--
CREATE DATABASE IF NOT EXISTS `sipetras` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sipetras`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `IdAdmin` varchar(6) NOT NULL,
  `namaAdmin` varchar(30) NOT NULL,
  `usernameAdmin` varchar(20) NOT NULL,
  `passwordAdmin` varchar(20) NOT NULL,
  `level` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`IdAdmin`, `namaAdmin`, `usernameAdmin`, `passwordAdmin`, `level`) VALUES
('ADM002', 'Raihan Apriansyah', 'Reyy', '123456', 'Admin'),
('ADM003', 'Ilham Nur', 'Ilham', '123456', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `admin_reports`
--

CREATE TABLE `admin_reports` (
  `idReport` int(11) NOT NULL,
  `idAdmin` varchar(6) NOT NULL,
  `reportDate` datetime NOT NULL DEFAULT current_timestamp(),
  `reportType` enum('Setoran','Penarikan','Transaksi','Saldo') NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penarikan`
--

CREATE TABLE `penarikan` (
  `idTarik` varchar(6) NOT NULL,
  `idUser` varchar(6) NOT NULL,
  `namaUser` varchar(30) NOT NULL,
  `tglTarik` date NOT NULL,
  `jmlPenarikan` int(11) NOT NULL,
  `createdBy` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penarikan`
--

INSERT INTO `penarikan` (`idTarik`, `idUser`, `namaUser`, `tglTarik`, `jmlPenarikan`, `createdBy`) VALUES
('TRK001', 'USR001', '', '2024-12-21', 10000, 'ADM003');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `idJual` varchar(6) NOT NULL,
  `idSampah` varchar(6) NOT NULL,
  `berat` varchar(15) NOT NULL,
  `tglPenjualan` date NOT NULL,
  `namaPembeli` varchar(30) NOT NULL,
  `nomorPembeli` varchar(13) NOT NULL,
  `harga` int(11) NOT NULL,
  `totalPendapatan` int(11) NOT NULL,
  `createdBy` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`idJual`, `idSampah`, `berat`, `tglPenjualan`, `namaPembeli`, `nomorPembeli`, `harga`, `totalPendapatan`, `createdBy`) VALUES
('JUL001', 'SMP008', '30', '2024-12-25', 'Akbar', '08123456789', 3000, 90000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `saldo_bank`
--

CREATE TABLE `saldo_bank` (
  `idTransaksi` varchar(6) NOT NULL,
  `aksi` enum('Penambahan','Pengurangan','','') NOT NULL,
  `tanggal` date NOT NULL,
  `aktor` varchar(6) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `totalSaldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saldo_bank`
--

INSERT INTO `saldo_bank` (`idTransaksi`, `aksi`, `tanggal`, `aktor`, `jumlah`, `totalSaldo`) VALUES
('SLD001', 'Penambahan', '2024-12-18', 'ADM001', 20000, 20000),
('SLD002', 'Pengurangan', '2024-12-18', 'ADM001', 20000, 0),
('SLD003', 'Pengurangan', '2024-12-18', 'ADM001', 20000, -20000),
('SLD004', 'Pengurangan', '2024-12-18', 'ADM001', 20000, -40000),
('SLD005', 'Pengurangan', '2024-12-21', 'ADM001', 20000, -60000),
('SLD006', 'Penambahan', '2024-12-21', 'ADM001', 27000, -33000),
('SLD007', 'Pengurangan', '2024-12-21', 'ADM001', 27000, -60000),
('SLD008', 'Pengurangan', '2024-12-21', 'ADM001', 27000, -87000),
('SLD009', 'Pengurangan', '2024-12-21', 'USR001', 10000, -97000),
('SLD010', 'Penambahan', '2024-12-25', 'ADM001', 90000, -7000);

-- --------------------------------------------------------

--
-- Table structure for table `sampah`
--

CREATE TABLE `sampah` (
  `idSampah` varchar(6) NOT NULL,
  `jenisSampah` varchar(15) NOT NULL,
  `namaSampah` varchar(30) NOT NULL,
  `satuan` varchar(5) NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(200) NOT NULL,
  `deskripsi` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sampah`
--

INSERT INTO `sampah` (`idSampah`, `jenisSampah`, `namaSampah`, `satuan`, `harga`, `gambar`, `deskripsi`) VALUES
('SMP001', 'Anorganik', 'Kresek', 'KG', 200, '60d468d995875.jpg', 'Semua jenis kresek (warna apapun)'),
('SMP002', 'Anorganik', 'Plastik', 'KG', 600, '60c0a74d10227.jpg', 'Semua jenis plastik'),
('SMP003', 'Anorganik', 'Karah warna', 'KG', 600, '60c0a75ce594a.jpg', 'Yang dapat dikumpulkan seperti sampah bekas shampoo, sabun, handbody, dll.'),
('SMP004', 'Anorganik', 'botol mineral plastik', 'KG', 1500, '60c0a6224066b.jpg', 'Semua jenis botol plastik yang berbahan plastik.'),
('SMP005', 'Anorganik', 'Botol mineral kaca', 'KG', 200, '60c0a77d59f11.jpg', 'Semua jenis botol yang berbahan kaca.'),
('SMP006', 'Anorganik', 'Gelas mineral plastik', 'KG', 1500, '60c0a7992a1af.jpg', 'Semua jenis gelas mineral yang berbahan plastik.'),
('SMP007', 'Anorganik', 'Kaleng', 'KG', 600, '60c0a7a9ce00e.jpg', 'Semua jenis kaleng.'),
('SMP008', 'Anorganik', 'Kardus/Karton', 'KG', 1100, 'kertas.jpg', 'Semua jenis kardus/karton.'),
('SMP011', 'Anorganik', 'Besi', 'KG', 1000, '60c0a7e0df741.jpg', 'Semua jenis besi.'),
('SMP012', 'Anorganik', 'Baja', 'KG', 1500, '60c0a7f2891ef.jfif', 'Semua jenis baja.'),
('SMP013', 'Anorganik', 'Tembaga', 'KG', 45000, '60c0a801c1069.jpg', 'Semua jenis tembaga.'),
('SMP014', 'Anorganik', 'Aluminium', 'KG', 7000, '60c0a80e7a6cb.jpg', 'Semua jenis aluminium.'),
('SMP015', 'Anorganik', 'Zeng', 'KG', 250, '60c0a8236ab5a.png', 'Semua jenis zeng.'),
('SMP016', 'Anorganik', 'Kain', 'KG', 200, '60c0a8309477f.jpg', 'Semua jenis kain.'),
('SMP017', 'Anorganik', 'Sandal dan Sepatu', 'KG', 85, '60c0a8411719a.jpg', 'Semua jenis dan merek sandal sepatu.'),
('SMP018', 'Anorganik', 'Lampu', 'KG', 100, '60c0a84f6efcf.jpg', 'Semua jenis lampu.');

-- --------------------------------------------------------

--
-- Table structure for table `setoran`
--

CREATE TABLE `setoran` (
  `idSetor` varchar(6) NOT NULL,
  `idUser` varchar(6) NOT NULL,
  `idSampah` varchar(6) NOT NULL,
  `tglSetor` date NOT NULL,
  `berat` varchar(15) NOT NULL,
  `total` int(11) NOT NULL,
  `createdBy` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setoran`
--

INSERT INTO `setoran` (`idSetor`, `idUser`, `idSampah`, `tglSetor`, `berat`, `total`, `createdBy`) VALUES
('STR001', 'USR001', 'SMP008', '2024-12-21', '40', 44000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_sampah`
--

CREATE TABLE `stock_sampah` (
  `idStock` varchar(6) NOT NULL,
  `namaSampah` varchar(30) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_sampah`
--

INSERT INTO `stock_sampah` (`idStock`, `namaSampah`, `stock`) VALUES
('STK001', 'Kresek', 0),
('STK002', 'Plastik', 0),
('STK003', 'Karah warna', 0),
('STK004', 'botol mineral plastik', 0),
('STK005', 'Botol mineral kaca', 0),
('STK006', 'Gelas mineral plastik', 0),
('STK007', 'Kaleng', 0),
('STK008', 'Kardus/Karton', 20),
('STK011', 'Besi', 0),
('STK012', 'Baja', 0),
('STK013', 'Tembaga', 0),
('STK014', 'Aluminium', 0),
('STK015', 'Zeng', 0),
('STK016', 'Kain', 0),
('STK017', 'Sandal dan Sepatu', 0),
('STK018', 'Lampu', 0),
('STK019', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUser` varchar(6) NOT NULL,
  `namaUser` varchar(30) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `telepon` varchar(13) NOT NULL,
  `username` varchar(20) NOT NULL,
  `passwordUser` varchar(20) NOT NULL,
  `jmlSetoran` int(11) NOT NULL,
  `jmlPenarikan` int(11) NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idUser`, `namaUser`, `gambar`, `nik`, `alamat`, `telepon`, `username`, `passwordUser`, `jmlSetoran`, `jmlPenarikan`, `saldo`) VALUES
('USR001', 'Raihan ', '676692f33efa4.png', '731119738127631', 'Jl.baiturrahman', '081342325951', 'raihan', '123456', 1, 1, 34000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`IdAdmin`);

--
-- Indexes for table `admin_reports`
--
ALTER TABLE `admin_reports`
  ADD PRIMARY KEY (`idReport`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indexes for table `penarikan`
--
ALTER TABLE `penarikan`
  ADD PRIMARY KEY (`idTarik`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`idJual`),
  ADD KEY `idSampah` (`idSampah`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `saldo_bank`
--
ALTER TABLE `saldo_bank`
  ADD PRIMARY KEY (`idTransaksi`);

--
-- Indexes for table `sampah`
--
ALTER TABLE `sampah`
  ADD PRIMARY KEY (`idSampah`);

--
-- Indexes for table `setoran`
--
ALTER TABLE `setoran`
  ADD PRIMARY KEY (`idSetor`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idSampah` (`idSampah`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `stock_sampah`
--
ALTER TABLE `stock_sampah`
  ADD PRIMARY KEY (`idStock`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_reports`
--
ALTER TABLE `admin_reports`
  MODIFY `idReport` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_reports`
--
ALTER TABLE `admin_reports`
  ADD CONSTRAINT `admin_reports_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `admins` (`IdAdmin`) ON DELETE CASCADE;

--
-- Constraints for table `penarikan`
--
ALTER TABLE `penarikan`
  ADD CONSTRAINT `idUser` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON UPDATE CASCADE,
  ADD CONSTRAINT `penarikan_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `admins` (`IdAdmin`) ON DELETE SET NULL;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`idSampah`) REFERENCES `sampah` (`idSampah`),
  ADD CONSTRAINT `penjualan_ibfk_2` FOREIGN KEY (`createdBy`) REFERENCES `admins` (`IdAdmin`) ON DELETE SET NULL;

--
-- Constraints for table `setoran`
--
ALTER TABLE `setoran`
  ADD CONSTRAINT `setoran_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`),
  ADD CONSTRAINT `setoran_ibfk_2` FOREIGN KEY (`idSampah`) REFERENCES `sampah` (`idSampah`),
  ADD CONSTRAINT `setoran_ibfk_3` FOREIGN KEY (`createdBy`) REFERENCES `admins` (`IdAdmin`) ON DELETE SET NULL;
--
-- Database: `sister_chat`
--
CREATE DATABASE IF NOT EXISTS `sister_chat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sister_chat`;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
