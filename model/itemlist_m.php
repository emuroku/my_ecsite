<?php

// 配列からステータスが公開のものだけを抽出する
function get_public_item_list($data){
    
    $public_data = array();
    foreach($data as $line){
        if($line['status'] === 1){
            $public_data[] = $line; 
        }
    }
    return $public_data;
}

// 配列からステータスが公開かつ指定のカテゴリのものだけを抽出する
function get_public_item_list_category($data, $category_id){
    
    $public_data = array();
    foreach($data as $line){
        if($line['status'] === 1 )
            if($line['category'] == $category_id){
                $public_data[] = $line; 
        }
    }
    return $public_data;
}

// 絞り込み検索した結果（条件に一致する公開済みの商品情報）を抽出する
function refined_search($dbh, $genre, $budget){
    
    $result_data = array();
    
    // SQL文の作成
    if($budget === 'low'){
        $sql = 'SELECT * FROM gm_items WHERE genre = ? AND price <= 2000';
    }else if($budget === 'middle'){
         $sql = 'SELECT * FROM gm_items WHERE genre = ? AND price BETWEEN 2000 AND 5001';
    }else if($budget === 'high'){
         $sql = 'SELECT * FROM gm_items WHERE genre = ? AND price > 5000';
    }
    
    // SQL文を実行する準備
    $stmt = $dbh -> prepare($sql);
    
    // SQL文のプレースホルダに値をバインド
    $stmt -> bindValue(1, $genre, PDO::PARAM_STR);
    
    // SQLを実行
    $stmt -> execute();
    
    $data = $stmt -> fetchAll();
    
    // // 購入に成功したかどうかの判定子をいれるカラムを追加
    // foreach($data as $line){
    //     array_push($line, "check_result");
    // }
    return $data;
}

// ワード検索した結果（条件に一致する公開済みの商品情報）を抽出する
function freeword_search($dbh, $str){
    
    $result_data = array();
    
    // SQL文の作成
    $sql = 'SELECT * FROM gm_items WHERE name LIKE ? OR comment LIKE ?';
    
    // SQL文を実行する準備
    $stmt = $dbh -> prepare($sql);
    
    // SQL文のプレースホルダに値をバインド
    $stmt -> bindValue(1, '%' . addcslashes($str, '\_%') . '%', PDO::PARAM_STR);
    $stmt -> bindValue(2, '%' . addcslashes($str, '\_%') . '%', PDO::PARAM_STR);
    
    // SQLを実行
    $stmt -> execute();
    
    $data = $stmt -> fetchAll();
    
    return $data;
    
}

// 入力されたソートタイプから、商品リストを並び替える
function sort_itemlist($data, $type){
    
    if($type == '0'){
        // foreachで1つずつ値を取り出す
        foreach((array)$data as $key => $value) {
        $sales[$key] = $value['sales'];
        }
 
    // array_multisortで'id'の列を昇順に並び替える
    array_multisort($sales, SORT_DESC, $data);
    }
    
    if($type == '1'){
        // foreachで1つずつ値を取り出す
        foreach((array)$data as $key => $value) {
        $price[$key] = $value['price'];
        }
 
    // array_multisortで'id'の列を昇順に並び替える
    array_multisort($price, SORT_DESC, $data);
    }
    
    if($type == '2'){
        // foreachで1つずつ値を取り出す
        foreach((array)$data as $key => $value) {
        $price[$key] = $value['price'];
        }
 
    // array_multisortで'id'の列を昇順に並び替える
    array_multisort($price, SORT_ASC, $data);
    }
    
    return $data;
}    

// 商品データをsales順にしたときに1～3位にラベルをつける
function put_label_sales($data){
    
    // foreachで1つずつ値を取り出す
    foreach((array)$data as $key => $value) {
    $sales[$key] = $value['sales'];
    }
    // array_multisortで'id'の列を昇順に並び替える
    array_multisort($sales, SORT_DESC, $data);
    
    // 1-3番目にラベルを追加
    if(count($data)>0){
        $data[0]['label'] = '1';
        if(count($data)>1){
            $data[1]['label'] = '2';
            if(count($data)>2){
                $data[2]['label'] = '3';
            }
        }
    }
    
    // 順番をもとに戻す
    // foreachで1つずつ値を取り出す
    foreach((array)$data as $key => $value) {
    $origin[$key] = $value['id'];
    }
    // array_multisortで'id'の列を昇順に並び替える
    array_multisort($origin, SORT_ASC, $data);
    
    return $data;
}

