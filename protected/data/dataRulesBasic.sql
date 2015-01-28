INSERT INTO `#__osefirewall_basicrules` (`id`, `rule`, `action`, `attacktype`) VALUES
(1, 'OSE_ENABLE_SFSPAM', 1, '["11"]'),
(2, 'BLOCKBL_METHOD', 1, '["1"]'),
(3, 'CHECK_MUA', 1, '["9"]'),
(4, 'checkDOS', 1, '["9"]'),
(5, 'checkDFI', 1, '["6"]'),
(6, 'checkRFI', 1, '["5"]'),
(7, 'checkJSInjection', 1, '["10"]'),
(8, 'checkSQLInjection', 1, '["4"]'),
(9, 'checkTrasversal', 1, '["8"]'),
(10, 'BLOCK_QUERY_LONGER_THAN_255CHAR', 1, '["1"]'),
(11, 'FILE_UPLOAD_VALIDATION', 1, '["13"]'),
(12, 'WORDPRESS_ADMIN_AJAX_PROTECTION', 1, '["6"]');
