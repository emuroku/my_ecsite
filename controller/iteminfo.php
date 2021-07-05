<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/iteminfo_m.php';
require_once '../model/common.php';

// 変数初期化
$item_info_data = array(); // 商品詳細情報を取得する配列
$err_msg = array(); // エラーメッセージ
$img_dir = "../../img/";
$item_stock = NULL; // 選択した商品の在庫数

// セッション開始
session_start();
if(isset ($_SESSION['username'])){
    $user_name = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}else{
    require_once 'logout.php';
}

try{
    // DB接続
    $dbh = get_db_connect();
    
    // 商品情報を取得
    $item_info_data = get_item_info($dbh, $item_id);
    
    // 在庫数を取得
    $item_stock = $item_info_data['stock'];
    


}catch(PDOException $e){
    $err_msg['db_connect'] = 'DBエラー: ' . $e -> getMessage(); 
}
// viewファイル読み込み
include_once '../view/iteminfo_v.php';

