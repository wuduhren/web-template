CREATE TABLE IF NOT EXISTS `php_session` (
  `id` char(32) CHARACTER SET latin1 NOT NULL,
  `expiry` int(11) UNSIGNED NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;
