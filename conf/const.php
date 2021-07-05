<?php
// DBアクセスの際のユーザー情報を指定
define('DB_HOST', 'mysql');
define('DB_NAME', 'sample');
define('DB_USER', 'testuser');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8');
define('DSN', 'mysql:dbname=' .DB_NAME. ';host=localhost;charset=utf8'); // DBのDSN情報
define('HTML_CHARACTER_SET', 'UTF-8'); // HTML文字エンコーディング