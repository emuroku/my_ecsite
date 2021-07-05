<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/finish_m.php';
require_once '../model/cart_m.php';
require_once '../model/common.php';

// 変数初期化
$cart_data = array(); // 購入を選択したカート情報を取得する配列
$result_data = array(); // 購入に成功したかの判定が完了した商品一覧を取得する配列
$item_data = array(); // 選択した商品のマスター情報を取得する配列
$err_msg = ''; // エラーメッセージ
$img_dir = "../img/";
$dialog = ''; // ダイアログメッセージ
$total_fee = 0; // 合計金額


// セッション開始
session_start();

// セッション変数からログイン済みか確認
if (isset($_SESSION['username'])) {
    $user_name = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
} else {
    // ログアウト済みの場合、トップページへリダイレクト
    header('Location: top.php');
    exit;
}

try {
    // DB接続
    $dbh = get_db_connect();
    
    // ログイン済みユーザーのカート情報を取得する
    $cart_data = get_cart_list($dbh, $user_id);
    
    // 商品マスターテーブルの情報を取得する
    $item_data = get_table_list($dbh, 'gm_items');

    //カート情報から、選択した商品情報を商品マスターテーブルから取得する
    $cart_item_data = get_cart_item_list($dbh, $cart_data);
    
    // 商品ごとに在庫、ステータスをチェックし、購入に成功したらitem_idを成功データへ、失敗したら失敗データへ格納
    $result_data = check_purchase($dbh, $cart_data);
    
    // 購入成功、失敗した各idのデータを取得する
    $result_item_list = get_result_item_list($result_data, $item_data);
    
    // $total_fee = get_total_fee_successed($dbh, $cart_data, $result_item_list);
    
    // 購入処理 成功した商品の在庫を減らす
    
    // -----ここからトランザクション処理-----
    $dbh -> beginTransaction();
    
    try{
        // 現在日時を取得
        $date = date('Y-m-d H:i:s');
        
        // カート情報、商品リスト、日時、ユーザーIDを渡して商品テーブルの在庫数と売上を更新する＆成功したらカートテーブルから削除
        $total_fee = update_inventory($dbh, $cart_data, $result_item_list, $date, $user_id);
        
        // コミット処理
        $dbh -> commit();
        
    }catch(PDOException $e){
        // ロールバック処理
        $dbh -> rollback();
        // 例外をスロー
        throw $e;
    }
} catch (PDOException $e) {
    $err_msg['db_connect'] = 'DBエラー: ' . $e->getMessage();
}
// viewファイル読み込み
include_once '../view/finish_v.php';
