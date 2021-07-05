<?php 

// // 文字列が6文字以上かどうかをチェックする
// function count_str($str){
//     $result = TRUE;
//     if(strlen($str) < 6){
//         $result = FALSE;
//     }
//     return $result;
// }

// 正規表現で「6文字以上の半角英数字」かどうかをチェックする
function validation_registinfo($input){
    $result = TRUE;
    $pattern = '/^([a-zA-Z0-9]{6,})$/';
    if(preg_match($pattern, $input) !== 1){
        $result = FALSE;
    }
    return $result;
}

// 正規表現で「メールアドレスとして正しい値」かどうかをチェックする
function validation_mail($input){
    $result = TRUE;
    $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/';
    if(preg_match($pattern, $input) !== 1){
        $result = FALSE;
    }
    return $result;
}


// チェックが通ったユーザー情報を gm_usersテーブルへINSERTする
function insert_user($dbh, $new_user_name, $new_passwd, $new_mail, $new_sex, $magazine_checked){
    // 実行時刻の取得
    $datetime = date('Y-m-d H:i:s') . "\t";
    
    // SQL文の作成
    $sql = 'INSERT INTO gm_users(username, password, mail, sex, magazine, createdate) VALUES(?, ?, ?, ?, ?, ?);';
    
    // SQL文を実行する準備
    $stmt = $dbh -> prepare($sql);
    
    // SQL文のプレースホルダに値をバインド
    $stmt -> bindValue(1, $new_user_name, PDO::PARAM_STR);
    $stmt -> bindValue(2, $new_passwd, PDO::PARAM_STR);
    $stmt -> bindValue(3, $new_mail, PDO::PARAM_STR);
    $stmt -> bindValue(4, $new_sex, PDO::PARAM_INT);
    $stmt -> bindValue(5, $magazine_checked, PDO::PARAM_INT);
    $stmt -> bindValue(6, $datetime, PDO::PARAM_STR);
    
    // SQLを実行
    $stmt -> execute();
}









