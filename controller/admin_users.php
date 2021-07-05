<?php

// 設定ファイル読み込み
require_once '../conf/const.php';
//print (dirname(__FILE__).'/./conf/') ;
// 関数ファイル読み込み
require_once '../model/admin_m.php';
require_once '../model/common.php';

// 変数初期化
$users_data = array();

session_start();

// セッション変数にusernameがsetされていない、もしくはadminユーザーでない場合に、topページへリダイレクトする
if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin' ){
    // ログインページへリダイレクト
 header('Location: top.php');
 exit;
}

// DB接続
try {
    // DBへ接続
    $dbh = get_db_connect();

    // ユーザ情報一覧リストの取得
    $users_data = get_table_list($dbh, "gm_users");
    
}catch (PDOException $e) {
    // 接続失敗した場合にエラーを返す
    $err_msg['db_connect'] = 'DBエラー: ' . $e->getMessage();
}
// Viewファイルの読み込み
include_once '../view/admin_users_v.php';
?>