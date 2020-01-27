<?php

try {
    $db = new PDO('mysql:dbname=trzqcgsd_sevens_bbs;host=localhost;charset=utf8', 'root', '95kpdxz27qmh');
} catch(PDOException $e) {
    print('DB接続エラー: ' . $e>getMessage());
}

?>
