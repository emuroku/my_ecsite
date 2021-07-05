<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/register_m.php';
require_once '../model/common.php';

// 変数初期化
$err_msg = array(); // エラーメッセージ表示
$users_data = array(); // 登録済みのユーザー情報を入れる
$username_data = array(); // 登録済みのユーザーIDリストを入れる

// 送信されたパラメータを入れる変数の宣言
$new_user_name = ''; // ユーザー名
$new_passwd = ''; // パスワード
$new_mail = ''; // メールアドレス
$new_sex = 0; // 性別
$magazine_checked = 0; // メルマガを受け取るか

$insert_result = FALSE; // ユーザー登録に成功したか


// DBへ接続
try {
    $dbh = get_db_connect();

    // 登録済みのユーザー情報を取得
    $users_data = get_table_list($dbh, "gm_users");
    // 既存ユーザー名リストを取得
    $username_data = get_users_name_list($dbh, $users_data);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // --------------ここから入力情報のチェック----------------
        
        // POSTされた入力値が正規のものかをチェックする
        // NGの場合、エラーメッセージを追加する
        
        // ユーザー名のチェック：半角英数字6字以上ではない入力、重複ID はNG
        if(validation_registinfo($_POST['user_name']) === FALSE){
            $err_msg[] = 'ユーザー名は6字以上の半角英数字で入力してください';
        } // IDが重複しているかチェックする
        else if(is_unique_array($_POST['user_name'], $username_data) === FALSE){
            $err_msg[] = '既に登録済みのユーザー名のため、登録できません';
        }
        // パスワードのチェック：半角英数字6字以上ではない入力、重複ID はNG
        if(validation_registinfo($_POST['password']) === FALSE){
            $err_msg[] = 'パスワードは6字以上の半角英数字で入力してください';
        }
        // メールアドレスのチェック：メールアドレスに該当しない入力はNG
        if(validation_mail($_POST['mail']) === FALSE){
            $err_msg[] = 'メールアドレスを正しく入力してください';
        }
        // 性別のチェック：正規でない入力はNG
        if(validation_isnumeric($_POST['sex']) === FALSE && $_POST['sex'] !== '0'){
            $err_msg[] = '性別をプルダウンから選択してください';
        }
        // --------------ここまで入力情報のチェック----------------
        
        // エラーメッセージが何もなかった場合は gm_usersテーブルへユーザ情報をINSERTする
        if(count($err_msg) === 0){
            
            // ----------ここからトランザクション処理----------
            $dbh -> beginTransAction();
            try{
                // 入力値を変数に代入
                $new_user_name = $_POST['user_name'];
                $new_passwd = $_POST['password']; // パスワード
                $new_mail = $_POST['mail']; // メールアドレス
                $new_sex = $_POST['sex']; // 性別
                if(isset($_POST['magazine'])){
                    $magazine_checked = $_POST['magazine'];
                }
                // 書き込み処理
                insert_user($dbh, $new_user_name, $new_passwd, $new_mail, $new_sex, $magazine_checked);
                
                // コミット処理
                $dbh -> commit();
                
                $insert_result = TRUE; // 登録完了フラグをTRUEにする
                
            } catch (PDOException $e){
                
                // ロールバック処理
                echo 'トランザクション中のエラーが発生しました。理由: ' . $e -> getMessage();
                $dbh -> rollback();
                // 例外をスロー
                throw $e;
            }
            // ----------ここまでトランザクション処理----------
        }
    }
} catch (PDOException $e) {
    // DB接続に失敗した場合エラーを返す
}

// Viewファイルの読み込み
include_once '../view/register_v.php';

