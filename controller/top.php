<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/login_m.php';
require_once '../model/common.php';

$err_msg = array();

// セッション開始
session_start();

// セッション変数からログイン済みか確認
if(isset($_SESSION['username'])){
    // ログイン済みの場合、ホームページへリダイレクト
    header('Location: itemlist.php');
    exit;
}
// Cookie情報からアカウント情報を取得
if(isset($_COOKIE['username'])){
    $username = $_COOKIE['username'];
}else{
    $username = '';
}

// Viewファイルの読み込み
include_once '../view/top_v.php';
