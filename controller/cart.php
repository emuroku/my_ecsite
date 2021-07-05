<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/cart_m.php';
require_once '../model/common.php';

// 変数初期化
$cart_data = array(); // カートに入っている商品一覧を取得する配列
$err_msg = array(); // エラーメッセージ
$img_dir = "../img/";
$sql_order = ''; // POSTされたオーダーを代入する
$dialog = ''; // ダイアログメッセージ


$count_cartitem = 0; // カートに入っている商品の数
$update_amount = ''; // 購入個数を更新する場合、更新後の個数を入れる
$update_cart_id = ''; // 購入個数を更新するアイテムのidを入れる
$total_fee = 0; // 合計金額を入れる

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

    // REQUEST_METHODに値が入っていたら入力値のチェック
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['sql_order']) === TRUE) {
            $sql_order = $_POST['sql_order'];
            // echo $sql_order;
        }

        // 選択済み商品のamountを変更する場合
        if ($sql_order === 'amount_update') {
            // 正規の値が送られてきているかをチェック
            if (validation_isnumeric($_POST['update_amount']) === FALSE) {
                $err_msg[] = '個数は正の半角整数で入力してください';
            }
            // エラーがなければ、入力された個数を代入
            if (count($err_msg) === 0) {
                $update_amount = $_POST['update_amount'];
                // 個数を更新する商品idをhiddenで取得
                $update_id = $_POST['update_id'];

                // カートテーブルへUPDATEクエリを作成し実行
                update_amount($dbh, $user_id, $update_id, $update_amount);
                $dialog = '購入個数を変更しました';
            }
        }

        // カートに入れた商品を削除する場合
        if ($sql_order === 'delete') {
            $update_cart_id = $_POST['delete_id'];
            delete_cart_item($dbh, $update_cart_id);
            $dialog = '商品をカートから削除しました';
        }
        
    }

    // ログイン済みユーザーのカート情報を取得する
    $cart_data = get_cart_list($dbh, $user_id);

    // 表示用：カートの商品種類数をカウントする
    $count_cartitem = count($cart_data);

    //カート情報から、選択した 商品情報を商品マスターテーブルから取得する
    $cart_item_data = get_cart_item_list($dbh, $cart_data);
    
    // カートの合計金額を取得する
    $total_fee = get_total_fee($dbh, $cart_data);
    
    
} catch (PDOException $e) {
    $err_msg['db_connect'] = 'DBエラー: ' . $e->getMessage();
}
// viewファイル読み込み
include_once '../view/cart_v.php';
