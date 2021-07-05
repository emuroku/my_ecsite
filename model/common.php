<?php

// DBハンドルを取得
function get_db_connect(){
    // MySQL用のDSN文字列
    $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
    try {
      // データベースに接続
      $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      exit('接続できませんでした。理由：'.$e->getMessage() );
    }
    return $dbh;
  }

// 指定のテーブルからすべての登録済みの情報を配列で取得する
function get_table_list($dbh, $table_name)
{
    // SQL文生成
    $sql = 'SELECT * FROM '. $table_name . ';';
    // SQL文を実行する準備
    try {
        $stmt = $dbh->prepare($sql);
        // SQL文を実行
        $stmt->execute();
        // レコードの取得
        $data = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $data;
}

// 入力値が正の半角整数になっているかをチェックし、該当しない場合はFalseを返す
function validation_isnumeric($input)
{   
    $result = TRUE;
    if(preg_match('/^[1-9][0-9]*$/', $input) !== 1){
        $result = FALSE;
    }
    return $result;
}

// 登録済ユーザーテーブルの情報からユーザー名の情報のみを取得する
function get_users_name_list($dbh, $array){
    $username_data = array();
    foreach($array as $line){
        $username_data[] = $line['username'];
    }
    return $username_data;
}

// 入力値の文字列が指定の配列の要素に対してユニークであるかどうかチェックする
function is_unique_array($input, $array){
    $result = TRUE;
    // 配列に入力値を付加する
    $array[] = $input;
    // 配列の要素数
    $count = count($array);
    // 重複削除した状態の配列の要素数と比較する
    if($count !== count(array_unique($array))){
        $result = FALSE;
    }
    return $result;
}

// カテゴリIDからカテゴリ名を取得する
function get_category_name($dbh, $id)
{
    // SQL文生成
    $sql = 'SELECT category_name FROM gm_categories WHERE category_id = ?';
    // SQL文を実行する準備
    try {
        $stmt = $dbh->prepare($sql);
        
        $stmt -> bindValue(1, $id, PDO::PARAM_INT);
        // SQL文を実行
        $stmt->execute();
        // レコードの取得
        $data = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        throw $e;
    }
    
    $name = $data[0]['category_name'];
    
    return $name;
}