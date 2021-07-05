<?php

// ユーザー名とパスワードを受け取って整合するかチェックする
function check_passwd($username, $passwd, $data){
    
    $target_passwd = '';
    $result = FALSE;
    
    foreach($data as $line){
        $tmp_username = $line['username'];
        // ユーザー名が入力値と一致するidのパスワードを入力値と照合
        if($tmp_username === $username){
            $target_passwd = $line['password'];
            if($target_passwd === $passwd){
                $result = TRUE;
            }
        }
    }
    return $result;
}


// 指定したユーザー名と合致するidを取得する
function define_id($username, $data){
    
    $id = NULL;
    
    foreach($data as $line){
        $tmp_username = $line['username'];
        if($tmp_username === $username){
            $id = $line['id'];
        }
    }
    return $id;
}