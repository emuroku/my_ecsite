<?php

// アイテムが既にカートに入っているかを調べる。カートに入っている商品の場合、amount数を返す
function check_in_cart($cart_data, $user_id, $item_id)
{

    $result = 0;

    foreach ($cart_data as $line) {
        if ($line['user_id']  == $user_id) {
            if ($line['item_id'] == $item_id) {
                if ($line['amount'] > 0) {
                    $result = $line['amount'];
                }
            }
        }
    }
    return $result;
}


// 新規のアイテムをカートに追加する
function insert_cart_info($dbh, $user_id, $item_id, $amount)
{
    // 実行時刻の取得
    $datetime = date('Y-m-d H:i:s') . "\t";
    // SQL文の作成
    $sql = 'INSERT INTO gm_carts(user_id, item_id, amount, createdate, updatedate) VALUES(?, ?, ?, ?, ?);';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
    $stmt->bindValue(3, $amount, PDO::PARAM_INT);
    $stmt->bindValue(4, $datetime, PDO::PARAM_STR);
    $stmt->bindValue(5, $datetime, PDO::PARAM_STR);

    // SQLを実行
    $stmt->execute();
}

// 指定ユーザーのカート情報を取得する
function get_cart_list($dbh, $user_id)
{

    $data = array();

    // SQL文の作成
    $sql = 'SELECT * FROM gm_carts WHERE user_id = ?';

    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);

    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);

    // SQLを実行
    $stmt->execute();

    $data = $stmt->fetchAll();

    // // 購入に成功したかどうかの判定子をいれるカラムを追加
    // foreach($data as $line){
    //     array_push($line, "check_result");
    // }
    return $data;
}

// カートにはいった商品情報を取得する
function get_cart_item_list($dbh, $cart_data)
{
    $item_info_list = array();
    $tmp_item_id = NULL;

    foreach ($cart_data as $line) {

        $tmp_item_id = $line['item_id'];
        $tmp_item_amount = $line['amount'];
        $tmp_data = array();

        // SQL文の作成
        $sql = 'SELECT * FROM gm_items WHERE id = ?';
        // SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $tmp_item_id, PDO::PARAM_INT);
        // SQLを実行
        $stmt->execute();

        $tmp_data = $stmt->fetchAll();

        $tmp_data['amount'] = $tmp_item_amount;
        $tmp_data['cart_id'] = $line['id'];

        $item_info_list[] = $tmp_data;
    }

    return $item_info_list;
}

// カートに入っているアイテムの数を変更する
function update_amount($dbh, $user_id, $item_id, $amount)
{
    // 実行時刻の取得
    $datetime = date('Y-m-d H:i:s') . "\t";
    // SQL文を作成
    $sql = 'UPDATE gm_carts SET amount = ?, updatedate = ? WHERE user_id = ? AND item_id =  ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $amount, PDO::PARAM_INT);
    $stmt->bindValue(2, $datetime, PDO::PARAM_STR);
    $stmt->bindValue(3, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(4, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
}


// 指定したidの商品のレコードをカートから削除する
function delete_cart_item($dbh, $id)
{
    // SQL文を作成
    $sql = 'DELETE FROM gm_carts WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
}

// カートに入った商品の合計金額を取得する
function get_total_fee($dbh, $data)
{

    $total_fee = 0;

    foreach ($data as $line) {
        $tmp_amount = $line['amount']; // 購入数を取得
        $tmp_price = get_price($dbh, $line['item_id']); // 価格を取得

        $total_fee += $tmp_price * $tmp_amount;
    }

    return $total_fee;
}

// // 購入成功した商品の合計金額を取得する
// function get_total_fee_successed($dbh, $cart_data, $item_data)
// {

//     $total_fee = 0;

//     foreach($item_data['success'] as $line) {

//         foreach ($cart_data as $num) {
//             if ($num['item_id'] === $line['id']) {
//                 $tmp_amount = $num['amount']; // 購入数を取得
//                 $tmp_price = get_price($dbh, $num['item_id']); // 価格を取得

//                 $total_fee += $tmp_price * $tmp_amount;
//             }

//             return $total_fee;
//         }
//     }
// }

// 指定のidの価格を取得する
function get_price($dbh, $id)
{

    // SQL文の作成
    $sql = 'SELECT price FROM gm_items WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();

    // 結果から価格を取得してreturn
    $data = $stmt->fetchAll();
    $price = $data[0]['price'];

    return $price;
}
