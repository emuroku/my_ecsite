<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/itemlist_m.php';
require_once '../model/common.php';
require_once '../model/cart_m.php';

// 変数初期化
$item_data = array(); // 登録済み商品一覧を取得する配列
$public_item_data = array(); // 公開ステータスの商品一覧を取得する配列
$err_msg = array(); // エラーメッセージ
$dialog = array(); // ダイアログメッセージ
$img_dir = "../img/";
$cart_info = array(); // ログイン済みユーザーのカート情報を取得する配列
$amount = NULL; // カートに入っているアイテムの更新用：カートに追加する前の商品選択数
$genre_name = ''; // ジャンル絞り込み表示時に掲載するジャンル名
$tmp_sales_data = array(); // 売上ラベル表示用の一時的なデータ配列

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

    // 登録済み商品の一覧を取得
    $item_data = get_table_list($dbh, "gm_items");

    // 商品一覧配列から公開アイテムのみを抽出する
    // カテゴリ指定なしの場合
    if (count($_GET) === 0) {
        $public_item_data = get_public_item_list($item_data);
        
    }
    // カテゴリ指定がある場合
    else if (isset($_GET['category']) === TRUE) {
        //var_dump($_GET);
        // 指定のカテゴリの公開アイテムのみを抽出する
        $public_item_data = get_public_item_list_category($item_data, $_GET['category']);
        $genre_name = get_category_name($dbh, $public_item_data[0]['category']);
    }

    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 絞り込み検索が実行されたら、商品を絞り込み選択する    
    if (isset($_POST['search_genre']) && isset($_POST['search_budget'])) {
        // 商品リスト絞り込む
        $selected_genre = $_POST['search_genre'];
        $selected_budget = $_POST['search_budget']; // low or middle or high
        
        // セッション変数に文字列を保存
        $_SESSION['search_genre'] = $selected_genre;
        $_SESSION['search_budget'] = $selected_budget;

        // 表示する商品リストの配列を更新
        $public_item_data = refined_search($dbh, $selected_genre, $selected_budget);
    }

    // ワード検索が実行されたら、商品を検索する
    if (isset($_POST['word_search'])) {
        if($_POST['word_search'] !== ''){
        $input_word = $_POST['word_search'];
        
        // セッション変数に文字列を保存
        $_SESSION['search_word'] = $input_word;

        // 商品リストを絞り込む
        // 表示する商品リストの配列を更新
        $public_item_data = freeword_search($dbh, $input_word);
        }
    }
    
    // 人気ラベルを付加
    // 表示する配列の要素数が1以上の場合のみ処理
    if(count($public_item_data) > 0){
    $public_item_data = put_label_sales($public_item_data);
    }
    
    // 該当ユーザーのカート情報を取得
    // gm_cartテーブルの情報を取得
    $cart_info = get_table_list($dbh, "gm_carts");

    // カートに入れるボタンが入力されたらcartテーブルを更新する
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // カート選択のPOSTがきたら、商品のidを取得する
        if (isset($_POST['added_item_id']) === TRUE) {

            // gm_cartsテーブルを更新する
            // ---ここからトランザクション処理---
            $dbh->beginTransAction();
            try {

                // 既にカートに入っている商品の場合
                $amount = check_in_cart($cart_info, $user_id, $_POST['added_item_id']);
                if ($amount > 0) {
                    // gm_cartテーブルをUPDATE
                    update_amount($dbh, $user_id, $_POST['added_item_id'], ($amount + 1));
                } else {
                    // カートに入っていない場合は書き込み処理
                    insert_cart_info($dbh, $user_id, $_POST['added_item_id'], 1);
                    // print $amount;
                }
                // コミット処理
                $dbh->commit();

                // ダイアログメッセージを追加
                $dialog[] = '商品をカートに追加しました。引き続きショッピングをお楽しみください！';
            } catch (PDOException $e) {
                // ロールバック処理
                echo 'トランザクション中のエラーが発生しました。理由: ' . $e->getMessage();
                $dbh->rollback();
                // 例外をスロー
                throw $e;
            }
        }
        // ---ここまでトランザクション処理---

        // ソートが実行されたら、商品を並び替える    
        if (isset($_POST['sort'])) {
            
            // 表示する配列の要素数が1以上の場合のみ処理
            if(count($public_item_data) > 0){
            // 選択されたソート種から表示する商品リストの配列を更新
            $public_item_data = sort_itemlist($public_item_data, $_POST['sort']);
            }
        }
    }
} catch (PDOException $e) {
    $err_msg['db_connect'] = 'DBエラー: ' . $e->getMessage();
}
// viewファイル読み込み
include_once '../view/itemlist_v.php';
