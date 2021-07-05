<?php
// 設定ファイル読み込み
require_once '../../conf/const.php';
// 関数ファイル読み込み
require_once '../../model/iteminfo_m.php';
require_once '../../model/common.php';

// 変数初期化
$id = 20;
$item_data = array(); // 登録済み商品一覧を取得する配列
$err_msg = array(); // エラーメッセージ
$img_dir = "../../img/";
$item_stock = NULL; // 選択商品の在庫数を取得する

// セッション開始
session_start();

// セッション変数からログイン済みか確認
if(isset($_SESSION['username'])){
    $user_name = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}else{
    // ログアウト済みの場合、トップページへリダイレクト
    header('Location: top.php');
    exit;
}


try {
    // DB接続
    $dbh = get_db_connect();

    // 登録済み公開商品の一覧を取得
    $item_data = get_item_info($dbh, $id);
    
    // 在庫数を取得
    $item_stock = $item_data[0]['stock'];
    
    
} catch (PDOException $e) {
    $err_msg['db_connect'] = 'DBエラー: ' . $e->getMessage();
}
// viewファイル読み込み
include_once '../../view/iteminfo_v.php';
