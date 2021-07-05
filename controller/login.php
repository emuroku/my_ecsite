<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/login_m.php';
require_once '../model/common.php';

// 変数・配列初期化
$err_msg = array(); // エラーメッセージ表示
$users_data = array(); // 一覧表示用のデータを入れる配列
$username_data = array(); // 登録済みユーザー名の一覧を入れる配列

$input_username = '';
$input_passwd = '';

// ログイン処理
// DB接続
try {
    $dbh = get_db_connect();
    // 登録済みユーザー情報を取得する
    $users_data = get_table_list($dbh, 'gm_users');
    // 既存ユーザー名リストを取得
    $username_data = get_users_name_list($dbh, $users_data);

    // REQUEST_METHOD POSTに値が入っていたら入力値のチェック、入って無ければログインページへリダイレクト
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // 変数に入力値を代入
        $input_username = $_POST['username'];
        $input_passwd = $_POST['password'];

        if ($input_username !== 'admin') {
            // 入力されたアカウント情報のチェック
            // 整合しない場合、エラーメッセージを表示する
            // 入力されたユーザー名が登録u済みユーザー名と一致するかチェック
            if (is_unique_array($input_username, $username_data) === TRUE) {
                $err_msg[] = '登録されていないユーザー名です';
            } else {
                // 一致するユーザー名が存在すれば、gm_usersテーブルから該当のユーザーのパスワード情報を参照
                if (check_passwd($input_username, $input_passwd, $users_data) === FALSE) {
                    $err_msg[] = 'パスワードに誤りがあります';
                }
            }
        } else {
            if ($input_passwd !== 'admin') {
                $err_msg[] = 'パスワードに誤りがあります';
            }
        }
    }
} catch (PDOException $e) {
    // 接続失敗した場合にエラーを返す
    $err_msg['db_connect'] = 'DBエラー' . $e->getMessage();
}

// エラーが含まれる場合はログインページヘリダイレクト
if (count($err_msg) !== 0) {
    include_once  '../view/top_v.php';
} else {
    // ログインに成功した場合はセッションを開始し情報をCookieへ保存
    // セッション開始
    session_start();
    // ユーザー名をCookieへ保存
    setcookie('username', $input_username, time() + 60 * 60 * 24 * 365);

    // セッション変数にusernameを保存
    $_SESSION['username'] = $input_username;

    if ($input_username === 'admin' && $input_passwd === 'admin') {
        // セッション変数にuser_idを保存
        $_SESSION['user_id'] = 0;
        // ログイン済みユーザーのホームページへリダイレクト
        header('Location: admin.php');
        exit;
    } else {
        // IDを取得する
        $user_id = define_id($input_username, $users_data);
        // セッション変数にuser_idを保存
        $_SESSION['user_id'] = $user_id;
        // ログイン済みユーザーのホームページへリダイレクト
        header('Location: itemlist.php');
        exit;
    }
}
