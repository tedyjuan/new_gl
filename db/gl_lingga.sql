/*
SQLyog Ultimate v10.42 
MySQL - 8.0.30 : Database - gl_lingga
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `audit_list` */

CREATE TABLE `audit_list` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_create` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_depo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` enum('CREATED','UPDATED','DELETED','SYSTEM_UPDATED','UPDATE_STATUS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_id` int DEFAULT NULL,
  `latest_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_item_id` int DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `audit_list_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `chart_of_accounts` */

CREATE TABLE `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_coa` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` int NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_coa` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_type` enum('asset','liability','equity','income','expense','retained earning account') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_method` enum('debit','credit') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_group` enum('kas','bank','inventory','sales') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_center_type` enum('all','depo','unit') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tbag_1` int DEFAULT NULL,
  `tbag_2` int DEFAULT NULL,
  `tbag_3` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_uuid_unique` (`uuid`),
  KEY `chart_of_accounts_code_coa_index` (`code_coa`),
  KEY `chart_of_accounts_account_number_index` (`account_number`),
  KEY `chart_of_accounts_code_company_index` (`code_company`),
  KEY `chart_of_accounts_header_coa_index` (`header_coa`),
  KEY `chart_of_accounts_reference_id_index` (`reference_id`),
  KEY `chart_of_accounts_account_type_index` (`account_type`),
  KEY `chart_of_accounts_account_method_index` (`account_method`),
  KEY `chart_of_accounts_account_group_index` (`account_group`),
  KEY `chart_of_accounts_cost_center_type_index` (`cost_center_type`),
  KEY `chart_of_accounts_tbag_1_index` (`tbag_1`),
  KEY `chart_of_accounts_tbag_2_index` (`tbag_2`),
  KEY `chart_of_accounts_tbag_3_index` (`tbag_3`),
  CONSTRAINT `chart_of_accounts_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `companies` */

CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_data` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_uuid_unique` (`uuid`),
  UNIQUE KEY `companies_code_company_unique` (`code_company`),
  UNIQUE KEY `companies_name_unique` (`name`),
  KEY `group_index_I` (`name`,`code_company`,`status_data`),
  KEY `companies_status_data_index` (`status_data`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cost_centers` */

CREATE TABLE `cost_centers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_cost_center` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_team` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_depo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_department` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_divisi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_segment` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_area` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_data` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cost_centers_uuid_unique` (`uuid`),
  KEY `group_index_I` (`code_cost_center`,`code_depo`,`code_divisi`,`code_department`,`code_segment`),
  KEY `cost_centers_code_cost_center_index` (`code_cost_center`),
  KEY `cost_centers_group_team_index` (`group_team`),
  KEY `cost_centers_code_depo_index` (`code_depo`),
  KEY `cost_centers_code_department_index` (`code_department`),
  KEY `cost_centers_code_divisi_index` (`code_divisi`),
  KEY `cost_centers_code_segment_index` (`code_segment`),
  KEY `cost_centers_code_company_index` (`code_company`),
  KEY `cost_centers_code_area_index` (`code_area`),
  CONSTRAINT `cost_centers_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_department_foreign` FOREIGN KEY (`code_department`) REFERENCES `departments` (`code_department`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_depo_foreign` FOREIGN KEY (`code_depo`) REFERENCES `depos` (`code_depo`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_divisi_foreign` FOREIGN KEY (`code_divisi`) REFERENCES `divisions` (`code_divisi`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_segment_foreign` FOREIGN KEY (`code_segment`) REFERENCES `segments` (`code_segment`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=942 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `departments` */

CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_department` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `status_data` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_uuid_unique` (`uuid`),
  KEY `departments_code_department_index` (`code_department`),
  KEY `departments_alias_index` (`alias`),
  KEY `departments_code_company_index` (`code_company`),
  KEY `departments_status_data_index` (`status_data`),
  CONSTRAINT `departments_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `depos` */

CREATE TABLE `depos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_depo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `npwp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'area kode',
  `fiscal_year` int DEFAULT NULL,
  `status_depo` enum('pusat','depo') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_data` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `depos_uuid_unique` (`uuid`),
  KEY `group_index_I` (`code_depo`,`code_company`),
  KEY `depos_code_depo_index` (`code_depo`),
  KEY `depos_alias_index` (`alias`),
  KEY `depos_code_company_index` (`code_company`),
  KEY `depos_code_area_index` (`code_area`),
  KEY `depos_status_depo_index` (`status_depo`),
  KEY `depos_status_data_index` (`status_data`),
  CONSTRAINT `depos_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `detail_audit_list` */

CREATE TABLE `detail_audit_list` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audit_list_id` int NOT NULL,
  `journal_id` int DEFAULT NULL,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `detail_audit_list_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `divisions` */

CREATE TABLE `divisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_divisi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `status_data` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_uuid_unique` (`uuid`),
  KEY `divisions_code_company_foreign` (`code_company`),
  KEY `divisions_code_divisi_index` (`code_divisi`),
  KEY `divisions_alias_index` (`alias`),
  KEY `divisions_status_data_index` (`status_data`),
  CONSTRAINT `divisions_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `fiscal_periods` */

CREATE TABLE `fiscal_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `depo_id` bigint NOT NULL,
  `year` int NOT NULL,
  `period` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('closed','open','undefined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'undefined',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fiscal_periods_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `journal_items` */

CREATE TABLE `journal_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `journal_id` int NOT NULL,
  `cost_center_id` int NOT NULL,
  `chart_of_account_id` int NOT NULL,
  `product_mapped_id` int NOT NULL,
  `journal_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit` bigint NOT NULL,
  `credit` bigint NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bku_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `dpp` bigint DEFAULT NULL,
  `ppn` bigint DEFAULT NULL,
  `total` bigint DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `previous_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequence_number` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_items_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `journal_sources` */

CREATE TABLE `journal_sources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_journal_source` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_depo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_data` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_sources_uuid_unique` (`uuid`),
  KEY `group_index_I` (`code_journal_source`,`code_depo`,`code_company`),
  KEY `journal_sources_code_journal_source_index` (`code_journal_source`),
  KEY `journal_sources_code_depo_index` (`code_depo`),
  KEY `journal_sources_code_company_index` (`code_company`),
  KEY `journal_sources_status_data_index` (`status_data`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `journals` */

CREATE TABLE `journals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `depo_id` int NOT NULL,
  `journal_source_id` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('posted','unposted','temporary') COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `total` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journals_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `posting_balances` */

CREATE TABLE `posting_balances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `depo_id` int NOT NULL,
  `year` int NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_center_id` int NOT NULL,
  `coa_id` int NOT NULL,
  `opening_balance` bigint NOT NULL,
  `debit` bigint NOT NULL,
  `credit` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posting_balances_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `product_mapped` */

CREATE TABLE `product_mapped` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int NOT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operational_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_center_id` int NOT NULL,
  `coa_id` int NOT NULL,
  `kind` enum('debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `do_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_mapped_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `products` */

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coa_id` int DEFAULT NULL,
  `company_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `roles` */

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `segments` */

CREATE TABLE `segments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_segment` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `status_data` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `segments_uuid_unique` (`uuid`),
  KEY `segments_code_segment_index` (`code_segment`),
  KEY `segments_alias_index` (`alias`),
  KEY `segments_code_company_index` (`code_company`),
  KEY `segments_status_data_index` (`status_data`),
  CONSTRAINT `segments_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trial_balance_account_group_1` */

CREATE TABLE `trial_balance_account_group_1` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_type` enum('asset','liability','equity','income','expense','retained earning account') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_balance_account_group_1_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trial_balance_account_group_2` */

CREATE TABLE `trial_balance_account_group_2` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tbag_1` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_balance_account_group_2_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trial_balance_account_group_3` */

CREATE TABLE `trial_balance_account_group_3` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tbag_2` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_balance_account_group_3_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_password_default` tinyint(1) NOT NULL DEFAULT '0',
  `token_reset_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
