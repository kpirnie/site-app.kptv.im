-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 07, 2026 at 05:29 PM
-- Server version: 11.8.5-MariaDB-deb13
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kptv-db`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `CleanupStreams`$$
CREATE DEFINER=`kptv-dbuser`@`%` PROCEDURE `CleanupStreams` ()   BEGIN
    START TRANSACTION;
    
    
    DELETE FROM kptv_streams 
    WHERE NOT EXISTS (
        SELECT 1 
        FROM kptv_stream_providers 
        WHERE kptv_stream_providers.id = kptv_streams.p_id
    );
    
    
    DELETE s1 
    FROM kptv_streams s1
    LEFT JOIN (
        SELECT MAX(id) as max_id, s_stream_uri
        FROM kptv_streams
        GROUP BY s_stream_uri
    ) s2 ON s1.id = s2.max_id
    WHERE s2.max_id IS NULL;
    
    
    TRUNCATE TABLE kptv_stream_temp;
    
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `ResetStreamIDs`$$
CREATE DEFINER=`kptv-dbuser`@`%` PROCEDURE `ResetStreamIDs` ()   BEGIN
    DECLARE v_max_id BIGINT;
    DECLARE v_next_val BIGINT;
    DECLARE v_sql TEXT;
    
    -- Disable foreign key checks
    SET FOREIGN_KEY_CHECKS = 0;
    
    -- 1. First check if there are multiple auto-increment columns
    -- Remove auto-increment from ALL columns first
    ALTER TABLE kptv_streams MODIFY id BIGINT NOT NULL;
    
    -- 2. Drop primary key if it exists
    ALTER TABLE kptv_streams DROP PRIMARY KEY;
    
    -- 3. Renumber all rows starting from 1
    SET @counter = 0;
    UPDATE kptv_streams 
    SET id = (@counter := @counter + 1)
    ORDER BY id;
    
    -- 4. Restore auto-increment and primary key
    ALTER TABLE kptv_streams 
    MODIFY id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY;
    
    -- 5. Set next auto-increment value
    SELECT COALESCE(MAX(id), 0) INTO v_max_id FROM kptv_streams;
    SET v_next_val = v_max_id + 1;
    SET v_sql = CONCAT('ALTER TABLE kptv_streams AUTO_INCREMENT = ', v_next_val);
    
    PREPARE stmt FROM v_sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    -- Re-enable foreign key checks
    SET FOREIGN_KEY_CHECKS = 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `kptv_streams`
--

DROP TABLE IF EXISTS `kptv_streams`;
CREATE TABLE `kptv_streams` (
  `id` bigint(20) NOT NULL,
  `u_id` bigint(20) NOT NULL,
  `p_id` bigint(20) NOT NULL DEFAULT 0,
  `s_type_id` tinyint(4) NOT NULL DEFAULT 0,
  `s_active` tinyint(1) NOT NULL DEFAULT 0,
  `s_channel` varchar(32) NOT NULL DEFAULT '0',
  `s_name` varchar(1024) NOT NULL,
  `s_orig_name` varchar(1024) NOT NULL,
  `s_stream_uri` varchar(2048) NOT NULL DEFAULT '',
  `s_tvg_id` varchar(1024) DEFAULT NULL,
  `s_tvg_group` varchar(1024) DEFAULT NULL,
  `s_tvg_logo` varchar(2048) DEFAULT NULL,
  `s_extras` varchar(2048) DEFAULT NULL,
  `s_created` datetime NOT NULL DEFAULT current_timestamp(),
  `s_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kptv_stream_filters`
--

DROP TABLE IF EXISTS `kptv_stream_filters`;
CREATE TABLE `kptv_stream_filters` (
  `id` bigint(20) NOT NULL,
  `u_id` bigint(20) NOT NULL,
  `sf_active` tinyint(1) NOT NULL DEFAULT 1,
  `sf_type_id` tinyint(4) NOT NULL DEFAULT 0,
  `sf_filter` varchar(1024) NOT NULL,
  `sf_created` datetime NOT NULL DEFAULT current_timestamp(),
  `sf_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kptv_stream_missing`
--

DROP TABLE IF EXISTS `kptv_stream_missing`;
CREATE TABLE `kptv_stream_missing` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `u_id` bigint(20) NOT NULL,
  `p_id` bigint(20) NOT NULL,
  `stream_id` bigint(20) NOT NULL DEFAULT 0,
  `other_id` bigint(20) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kptv_stream_providers`
--

DROP TABLE IF EXISTS `kptv_stream_providers`;
CREATE TABLE `kptv_stream_providers` (
  `id` bigint(20) NOT NULL,
  `u_id` bigint(20) NOT NULL,
  `sp_should_filter` tinyint(1) NOT NULL DEFAULT 1,
  `sp_priority` int(11) NOT NULL DEFAULT 99,
  `sp_name` varchar(256) NOT NULL,
  `sp_cnx_limit` int(11) NOT NULL DEFAULT 1,
  `sp_type` tinyint(1) NOT NULL DEFAULT 0,
  `sp_domain` varchar(256) NOT NULL,
  `sp_username` varchar(1024) DEFAULT NULL,
  `sp_password` varchar(1024) DEFAULT NULL,
  `sp_stream_type` tinyint(1) NOT NULL DEFAULT 0,
  `sp_refresh_period` int(11) NOT NULL DEFAULT 3,
  `sp_last_synced` datetime DEFAULT NULL,
  `sp_added` datetime NOT NULL DEFAULT current_timestamp(),
  `sp_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kptv_stream_temp`
--

DROP TABLE IF EXISTS `kptv_stream_temp`;
CREATE TABLE `kptv_stream_temp` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `u_id` bigint(20) NOT NULL,
  `p_id` bigint(20) NOT NULL,
  `s_type_id` tinyint(4) NOT NULL DEFAULT 0,
  `s_orig_name` varchar(1024) NOT NULL,
  `s_stream_uri` varchar(2048) NOT NULL DEFAULT '',
  `s_tvg_id` varchar(512) DEFAULT NULL,
  `s_tvg_logo` varchar(2048) DEFAULT NULL,
  `s_extras` varchar(2048) DEFAULT NULL,
  `s_group` varchar(1024) DEFAULT NULL,
  `s_orig_name_lower` varchar(255) GENERATED ALWAYS AS (lcase(`s_orig_name`)) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kptv_streams`
--
ALTER TABLE `kptv_streams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `s_type_id` (`s_type_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `s_active` (`s_active`),
  ADD KEY `idx_s_name` (`s_name`(255)),
  ADD KEY `idx_s_orig_name` (`s_orig_name`(255)),
  ADD KEY `idx_active_tvgid` (`s_active`,`s_tvg_id`(255)),
  ADD KEY `idx_active_tvglogo` (`s_active`,`s_tvg_logo`(255)),
  ADD KEY `idx_channel` (`s_channel`),
  ADD KEY `idx_name_updated` (`s_name`(255),`s_updated`);
ALTER TABLE `kptv_streams` ADD FULLTEXT KEY `s_stream_uri` (`s_stream_uri`);
ALTER TABLE `kptv_streams` ADD FULLTEXT KEY `s_name` (`s_name`);
ALTER TABLE `kptv_streams` ADD FULLTEXT KEY `s_orig_name` (`s_orig_name`);

--
-- Indexes for table `kptv_stream_filters`
--
ALTER TABLE `kptv_stream_filters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `sf_active` (`sf_active`),
  ADD KEY `sf_type_id` (`sf_type_id`),
  ADD KEY `idx_user_active_type` (`u_id`,`sf_active`,`sf_type_id`);

--
-- Indexes for table `kptv_stream_missing`
--
ALTER TABLE `kptv_stream_missing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lower_name_pid` (`p_id`),
  ADD KEY `stream_id` (`stream_id`),
  ADD KEY `other_id` (`other_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `kptv_stream_providers`
--
ALTER TABLE `kptv_stream_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `sp_name` (`sp_name`);

--
-- Indexes for table `kptv_stream_temp`
--
ALTER TABLE `kptv_stream_temp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kptv_streams`
--
ALTER TABLE `kptv_streams`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kptv_stream_filters`
--
ALTER TABLE `kptv_stream_filters`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kptv_stream_missing`
--
ALTER TABLE `kptv_stream_missing`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kptv_stream_providers`
--
ALTER TABLE `kptv_stream_providers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kptv_stream_temp`
--
ALTER TABLE `kptv_stream_temp`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
