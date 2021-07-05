<?php
function get_item_info($dbh, $id){
    $item_data = array();
    
    // SQL文作成
    $sql = 'SELECT * FROM gm_items WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh -> prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt -> bindValue(1, $id, PDO::PARAM_INT);
    
    // SQL文を実行
    $stmt -> execute();
    
    $item_data = $stmt -> fetchAll();
    
    return $item_data;
}