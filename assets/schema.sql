-- Start a transaction to ensure all operations are atomic
START TRANSACTION;

-- *****************************************************************
-- Procedures for stream cleanup and ID reset
-- Set the delimiter to $$ to allow for procedure definitions that contain multiple statements
DELIMITER $$

-- Procedure to clean up streams by removing those without providers and duplicates
DROP PROCEDURE IF EXISTS `CleanupStreams`$$
CREATE PROCEDURE `CleanupStreams` ()   BEGIN
    START TRANSACTION;
    
    -- Delete streams that have no corresponding provider
    DELETE FROM kptv_streams 
    WHERE NOT EXISTS (
        SELECT 1 
        FROM kptv_stream_providers 
        WHERE kptv_stream_providers.id = kptv_streams.p_id
    );
    
    -- Delete duplicate streams based on s_stream_uri, keeping the one with the highest id
    DELETE s1 
    FROM kptv_streams s1
    LEFT JOIN (
        SELECT MAX(id) as max_id, s_stream_uri
        FROM kptv_streams
        GROUP BY s_stream_uri
    ) s2 ON s1.id = s2.max_id
    WHERE s2.max_id IS NULL;
    
    -- Clear the temporary table
    TRUNCATE TABLE kptv_stream_temp;
    
    COMMIT;
END$$

-- Reset stream IDs to be sequential starting from 1
DROP PROCEDURE IF EXISTS `ResetStreamIDs`$$
CREATE DEFINER=`kptv-devdbuser`@`%` PROCEDURE `ResetStreamIDs` ()   BEGIN
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
    
    -- Prepare and execute the dynamic SQL to set the next auto-increment value
    PREPARE stmt FROM v_sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    -- Re-enable foreign key checks
    SET FOREIGN_KEY_CHECKS = 1;
END$$

-- Reset stream filter IDs to be sequential starting from 1
DELIMITER ;

-- *****************************************************************
-- Create tables

-- Streams table to store stream information
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

-- Table to store stream filters for users
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

-- Table to track missing streams that couldn't be matched to providers
DROP TABLE IF EXISTS `kptv_stream_missing`;
CREATE TABLE `kptv_stream_missing` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `u_id` bigint(20) NOT NULL,
  `p_id` bigint(20) NOT NULL,
  `stream_id` bigint(20) NOT NULL DEFAULT 0,
  `other_id` bigint(20) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table to store stream providers for users
DROP TABLE IF EXISTS `kptv_stream_providers`;
CREATE TABLE `kptv_stream_providers` (
  `id` bigint(20) NOT NULL,
  `u_id` bigint(20) NOT NULL,
  `sp_should_filter` tinyint(1) NOT NULL DEFAULT 1,
  `sp_priority` tinyint(3) NOT NULL DEFAULT 99,
  `sp_name` varchar(256) NOT NULL,
  `sp_cnx_limit` tinyint(4) NOT NULL DEFAULT 1,
  `sp_type` tinyint(1) NOT NULL DEFAULT 0,
  `sp_domain` varchar(256) NOT NULL,
  `sp_username` varchar(1024) DEFAULT NULL,
  `sp_password` varchar(1024) DEFAULT NULL,
  `sp_stream_type` tinyint(1) NOT NULL DEFAULT 0,
  `sp_refresh_period` tinyint(4) NOT NULL DEFAULT 3,
  `sp_last_synced` datetime DEFAULT NULL,
  `sp_added` datetime NOT NULL DEFAULT current_timestamp(),
  `sp_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Temporary stream table
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

-- Users table to store user information
DROP TABLE IF EXISTS `kptv_users`;
CREATE TABLE `kptv_users` (
  `id` bigint(20) NOT NULL,
  `u_role` tinyint(3) NOT NULL DEFAULT 0,
  `u_active` tinyint(1) NOT NULL DEFAULT 0,
  `u_name` varchar(128) NOT NULL,
  `u_pass` char(255) NOT NULL,
  `u_hash` char(255) NOT NULL,
  `u_email` varchar(512) NOT NULL,
  `u_lname` varchar(128) DEFAULT NULL,
  `u_fname` varchar(128) DEFAULT NULL,
  `u_created` datetime NOT NULL DEFAULT current_timestamp(),
  `u_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `login_attempts` tinyint(4) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add indexes and primary keys
ALTER TABLE `kptv_streams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`u_id`),
  ADD KEY `idx_stypeid` (`s_type_id`),
  ADD KEY `idx_pid` (`p_id`),
  ADD KEY `idx_sactive` (`s_active`),
  ADD KEY `idx_main` (`u_id`,`s_active`,`s_type_id`);
  ADD KEY `idx_activetvgid` (`s_active`,`s_tvg_id`(255)),
  ADD KEY `idx_activetvglogo` (`s_active`,`s_tvg_logo`(255)),
  ADD KEY `idx_channel` (`s_channel`),
  ADD KEY `idx_name_updated` (`s_name`(255),`s_updated`);
ALTER TABLE `kptv_streams` ADD FULLTEXT KEY `idx_sname` (`s_name`);
ALTER TABLE `kptv_streams` ADD FULLTEXT KEY `idx_sorigname` (`s_orig_name`);

ALTER TABLE `kptv_stream_filters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`u_id`),
  ADD KEY `idx_sfactive` (`sf_active`),
  ADD KEY `idx_main` (`u_id`,`sf_active`,`sf_type_id`),  
  ADD KEY `idx_sftypeid` (`sf_type_id`);

ALTER TABLE `kptv_stream_missing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`u_id`);

ALTER TABLE `kptv_stream_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`u_id`);

ALTER TABLE `kptv_stream_temp`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `kptv_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_uname` (`u_name`),
  ADD UNIQUE KEY `idx_uemail` (`u_email`),
  ADD KEY `idx_uactive` (`u_active`);


ALTER TABLE `kptv_streams`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `kptv_stream_filters`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `kptv_stream_missing`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `kptv_stream_providers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `kptv_stream_temp`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `kptv_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

-- Commit the transaction to save all changes
COMMIT;
