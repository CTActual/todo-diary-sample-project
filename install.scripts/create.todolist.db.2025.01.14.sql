-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 14, 2025 at 11:09 PM
-- Server version: 8.0.40-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

--
-- Database: todolistpublic
--
CREATE DATABASE IF NOT EXISTS todolist DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE todolist;

-- --------------------------------------------------------

--
-- Table structure for table diary
--

CREATE TABLE IF NOT EXISTS diary (
  id int UNSIGNED NOT NULL AUTO_INCREMENT,
  note text COMMENT 'Diary Entry',
  crn_date datetime NOT NULL COMMENT 'Date of Entry',
  todo_id int UNSIGNED DEFAULT NULL COMMENT 'Association with a to-do entry',
  PRIMARY KEY (id),
  KEY crn_date (crn_date),
  KEY todo_id (todo_id)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Stores diary entries';

--
-- Dumping data for table diary
--

INSERT IGNORE INTO diary (id, note, crn_date, todo_id) VALUES
(1, 'Added licensing information to website pages&period;', '2022-03-07 11:39:55', 4),
(2, 'Added example entries into database using UI&period;', '2022-03-07 11:40:40', 4),
(3, 'Worked on putting pages together along with install scripts and readme instructions&period;', '2022-03-07 11:44:52', 4),
(4, 'Published www&period;hooplaframework&period;com&excl;', '2022-04-10 15:50:46', NULL),
(5, 'Published The Hoopla Framework to Github&excl;', '2022-04-10 15:52:22', NULL),
(6, 'Published Todolist to Github&excl;', '2022-04-10 15:52:38', NULL),
(7, 'Added pagination to full diary page&period;', '2022-06-23 13:12:56', 3),
(8, 'Started work on full to-do list page&period;', '2022-06-23 14:20:19', 2),
(9, 'Added column sorting on full To-Do List page&period;', '2023-05-17 11:56:41', 5),
(10, 'Added Specific Contexts to the HFW for better context and value management', '2023-05-17 18:48:12', NULL),
(11, 'Got sorting and filters working on full to-do list page.', '2023-06-05 15:52:45', 2),
(12, 'Added new fields to types table for sorting.', '2024-12-29 23:04:32', 3),
(13, 'Cleaned up code for public release by removing code from the base template that was never needed.', '2025-01-14 22:45:32', 4),
(14, 'Updated sample db entries and installer.', '2025-01-14 23:04:32', 4);

-- --------------------------------------------------------

--
-- Table structure for table meta_types
--

CREATE TABLE IF NOT EXISTS meta_types (
  id int UNSIGNED NOT NULL AUTO_INCREMENT,
  meta_type_name varchar(63) NOT NULL COMMENT 'The Meta Type Name',
  meta_type_dsr varchar(512) NOT NULL COMMENT 'The Meta Type Description',
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Stores the various meta types';

--
-- Dumping data for table meta_types
--

INSERT IGNORE INTO meta_types (id, meta_type_name, meta_type_dsr) VALUES
(1, 'Todo Types', 'Types of to-dos'),
(2, 'Status Types', 'To-do status types');

-- --------------------------------------------------------

--
-- Table structure for table todolist
--

CREATE TABLE IF NOT EXISTS todolist (
  id int UNSIGNED NOT NULL AUTO_INCREMENT,
  type_id int UNSIGNED NOT NULL COMMENT 'To Do Type ID',
  note text COMMENT 'To Do Entry',
  crn_date datetime NOT NULL COMMENT 'Date-Time of Entry',
  dl_date date DEFAULT NULL COMMENT 'Deadline',
  comp_date date DEFAULT NULL COMMENT 'Completion Date',
  status_type_id int UNSIGNED NOT NULL COMMENT 'Status of the Item',
  PRIMARY KEY (id),
  KEY type_id (type_id),
  KEY crn_date (crn_date),
  KEY deadline (dl_date),
  KEY comp_date (comp_date)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Stores the To-Do list entries';

--
-- Dumping data for table todolist
--

INSERT IGNORE INTO todolist (id, type_id, note, crn_date, dl_date, comp_date, status_type_id) VALUES
(1, 1, 'Publish To-Do Website to Github', '2022-03-07 11:33:59', '2022-03-25', '2022-04-10', 11),
(2, 1, 'Add Page for Managing To-Do list in full&period;', '2022-03-07 11:34:44', NULL, '2023-06-05', 11),
(3, 1, 'Add Page for Managing Diary in full&period;', '2022-03-07 11:35:07', NULL, '2023-06-05', 11),
(4, 1, 'Get To-Do Website ready for publishing&period;', '2022-03-06 11:35:53', '2022-03-11', '2022-04-10', 11),
(5, 1, 'Add options to list by deadline&comma; show completed by completion date and paginate lists&period;', '2022-03-07 11:38:16', NULL, '2023-06-05', 11),
(6, 1, 'Update installers', '2023-06-05 16:28:56', NULL, NULL, 9),
(7, 1, 'Update github files', '2023-06-05 16:29:13', NULL, NULL, 9),
(8, 1, 'Update Hooplaframework&period;com', '2023-06-05 16:29:32', NULL, NULL, 9),
(9, 6, 'Write up book reviews on Amazon&period;com', '2023-06-05 16:31:34', NULL, NULL, 8);

-- --------------------------------------------------------

--
-- Table structure for table types
--

CREATE TABLE IF NOT EXISTS `types` (
  id int UNSIGNED NOT NULL AUTO_INCREMENT,
  meta_type_id int UNSIGNED NOT NULL COMMENT 'ID of the Meta Type',
  type_name varchar(63) NOT NULL COMMENT 'Type Name',
  type_dsr varchar(512) NOT NULL COMMENT 'Type Description',
  spc_ord tinyint UNSIGNED DEFAULT NULL COMMENT 'Custom order types',
  act_bit tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Turn types on or off',
  PRIMARY KEY (id),
  KEY meta (meta_type_id),
  KEY spc_ord (spc_ord),
  KEY act_bit (act_bit)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Stores the various types';

--
-- Dumping data for table types
--

INSERT IGNORE INTO types (id, meta_type_id, type_name, type_dsr, spc_ord, act_bit) VALUES
(1, 1, 'Work Related', '', 1, 1),
(2, 1, 'Shopping', '', 2, 1),
(3, 1, 'Exercise', '', 3, 1),
(4, 1, 'Taxes', '', 4, 1),
(5, 1, 'Medical', '', 5, 1),
(6, 1, 'Fun', '', 6, 1),
(7, 1, 'Chore', '', 7, 1),
(8, 2, 'Unstarted', '', 1, 1),
(9, 2, 'In Progress', '', 2, 1),
(10, 2, 'On Hold', '', 3, 1),
(11, 2, 'Completed', '', 4, 1),
(12, 2, 'Cancelled', '', 5, 1),
(13, 2, 'No Longer Applicable', '', 6, 1),
(14, 2, 'Ended Badly', '', 7, 1),
(15, 1, 'T.B.D.', 'To be determined.', 8, 1);
