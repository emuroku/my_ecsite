<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/admin_m.php';
require_once '../model/common.php';

// 変数・配列初期化
$err_msg = array(); // エラーメッセージ表示
$dialog = ''; // 完了メッセージを表示
$items_data = array(); // 一覧表示用のデータを入れる配列
$users_data = array(); // ユーザー一覧表示用のデータを入れる配列
$img_dir = '../img/'; // アップロードした画像ファイルの保存ディレクトリ
$new_img_filename = ''; // アップロードした画像ファイル名
$sql_order = ''; // SQL文をINSERTするかSELECTするか分岐する

// データ登録時のパラメータ
$new_name = '';
$new_price = '';
$new_stock = '';
$new_status = 0; // デフォルトは非公開
$new_category = '';
$new_genre = '';
$new_comment = '';

$registerd_id = 0;

// 在庫数、ステータスのデータ更新時のパラメータ
$update_id = ""; // 更新する商品のdrink_id
$update_stock = ""; // 更新する在庫数
$update_status = ""; // 更新するステータス

// DB接続
try {
    // DBへ接続
    $dbh = get_db_connect();

    // REQUEST_METHOD POSTに値が入っていたら入力値のチェック
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['sql_order']) === TRUE) {
            // hidden で送られてきたSQL文の分岐フラグを代入
            $sql_order = $_POST['sql_order'];
        }
        // INSERTフラグ有効の場合の処理　～入力値のバリデーション・画像ファイルのチェック　→　エラーメッセージがなければINSERTする
        if ($sql_order === 'insert') {
            //　商品の新規登録の入力があった場合の処理
            // 入力値のバリデーション
            
            // 商品名が入力されているかチェック
            $result_post = validation_empty($_POST['name']);
            if($result_post === FALSE){
                $err_msg[] = '商品名を入力してください';
            }
            // 数値が0以上の整数であるかチェック
                // 価格
            $result_post = validation_isnumeric($_POST['price']);
            if($result_post === FALSE){
                $err_msg[] = '価格は正の半角整数で入力してください';
            }
                // 在庫数
            $result_post = validation_isnumeric($_POST['stock']);
            if($result_post === FALSE){
                $err_msg[] = '在庫数は正の半角整数で入力してください';
            }
                // ステータス
            if($_POST['status'] !== '1' && $_POST['status'] !== '0'){
                $err_msg[] = 'ステータスをプルダウンから選択してください';
            }
                // カテゴリ
            $result_post = validation_empty($_POST['category']);
            if($result_post === FALSE){
                $err_msg[] = 'カテゴリをプルダウンから選択してください';
            }
                // ジャンル
            $result_post = validation_empty($_POST['genre']);
            if($result_post === FALSE){
                $err_msg[] = 'ジャンルをプルダウンから選択してください';
            }

            // アップロード画像ファイルのチェックと保存
            if (is_uploaded_file($_FILES['img']['tmp_name']) === TRUE) {
                // 画像ファイルのチェック
                $result_post = extension_check($_FILES);
                if($result_post === FALSE){
                     $err_msg[] = "ファイル形式が異なります。画像ファイルはPNGもしくはJPEGのみ利用可能です。";
                }
                // 画像ファイルの書き込み 拡張子が適切であれば、ファイルを移動して保存
                else {
                    // ファイル名の生成
                    $new_img_filename = create_filename($_FILES);
                    // ファイルの書き込み
                    $err_msg = file_upload($_FILES, $err_msg, $new_img_filename, $img_dir);
                }
            } else {
                $err_msg[] = 'ファイルを選択してください';
            }

            // バリデーションエラーがなければ登録処理をする
            if (count($err_msg) === 0) {
                // 画像ファイルの書き込みをここにいれる

                // ---ここからトランザクション処理---
                $dbh->beginTransAction();
                try {
                    // 入力値を変数に代入
                    $new_name = $_POST['name'];
                    $new_price = $_POST['price'];
                    $new_stock = $_POST['stock'];
                    $new_status = $_POST['status'];
                    $new_category = $_POST['category'];
                    $new_genre = $_POST['genre'];
                    $new_comment = $_POST['comment'];

                    // 書き込み処理
                    //   商品登録
                    //   gm_itemsテーブルへのINSERTクエリを作成し実行
                    insert_items($dbh, $new_name, $new_price, $new_img_filename, $new_status, $new_stock, $new_category, $new_genre, $new_comment);

                    // コミット処理
                    $dbh->commit();
                    $dialog = '商品データの登録が完了しました';
                } catch (PDOException $e) {
                    // ロールバック処理
                    echo 'トランザクション中のエラーが発生しました。理由: ' . $e->getMessage();
                    $dbh->rollback();
                    // 例外をスロー
                    throw $e;
                }
                // ---ここまでトランザクション処理---    

            }
        }
        // 登録済み商品の在庫を更新する場合
        if ($sql_order === 'update') {
            // 未入力の場合エラーメッセージを追加
            if(validation_isnumeric($_POST['update_stock_num']) === FALSE && $_POST['update_stock_num'] !== '0'){
                $err_msg[] = '更新する在庫数を正の半角整数で入力してください';
            }

            // エラーがなければ、入力された在庫数を代入
            if (count($err_msg) === 0) {
                $update_stock = $_POST['update_stock_num'];
                // 在庫数を更新する商品のidをhiddenで取得
                $update_id = $_POST['update_id'];
                
                // 在庫数の更新書き込み処理
                //   drink_stockテーブルへのUPDATEクエリを作成し実行
                update_stock($dbh, $update_id, $update_stock);
                $dialog =  '在庫数の更新が完了しました';
            }
        }

        // 登録済み商品のステータスを更新する場合
        if ($sql_order === 'update_status') {
            // 正規の値が送られてきているかをチェック
            if($_POST['update_status'] !== '1' && $_POST['status'] !== '0'){
                $err_msg[] = 'ステータスの更新は更新ボタンを選択してください';
            }

            if (count($err_msg) === 0) {
                // 入力された更新後のステータスを代入
                $update_status = $_POST['update_status'];
                $update_id = $_POST['update_id'];
                // ステータスの更新書き込み処理
                //  masterテーブルへのUPDATEクエリを作成し実行
                update_status($dbh, $update_id, $update_status);
                $dialog = 'ステータスの更新が完了しました';
            }
        }
        
        // 登録済み商品のレコードを削除する場合
        if ($sql_order === 'delete'){
            delete_item($dbh, 'gm_items', $_POST['delete_id']);
            $dialog = '商品の削除が完了しました';
        }
    }
    // 既存の商品リストの取得
    $items_data = get_table_list($dbh, "gm_items");

    // 商品リストの文字列の特殊文字をHTMLエンティティに変換
    $items_data = entity_assoc_array($items_data);
} catch (PDOException $e) {
    // 接続失敗した場合にエラーを返す
    $err_msg['db_connect'] = 'DBエラー: ' . $e->getMessage();
}
// Viewファイルの読み込み
include_once '../view/admin_v.php';
