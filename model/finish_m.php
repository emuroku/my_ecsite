<?php
// 商品ごとに在庫、ステータスをチェックし、購入に成功したら成功データへ、失敗したら失敗データへ格納
function check_purchase($dbh, $cart_data)
{

    // 結果を入れる配列を初期化
    $result_data = array();
    $result_data['success'] = NULL;
    $result_data['failure'] = NULL;

    foreach ($cart_data as $line) {
        // 商品の在庫を取得
        $item_stock = check_stock($dbh, $line['item_id']);
        // 在庫数が足りているかチェックする
        // 購入できない場合はfailureへ
        if (check_is_available($item_stock, $line['amount']) === FALSE || check_status($dbh, $line['item_id']) === FALSE) {
            $result_data['failure']['id'][] = $line['item_id'];
        } else {
            $result_data['success']['id'][] = $line['item_id'];
        }
    }
    return $result_data;
}

// 指定の商品の在庫数を取得する
function check_stock($dbh, $id)
{

    $result_stock = 0;

    // SQL文の作成
    $sql = 'SELECT stock FROM gm_items WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();

    $tmp_data = $stmt->fetchAll();
    // var_dump($tmp_data);
    $result_stock = $tmp_data[0]['stock'];

    return $result_stock;
}

// 指定の商品の売上を取得する
function check_sales($dbh, $id)
{

    $result_sales = 0;

    // SQL文の作成
    $sql = 'SELECT sales FROM gm_items WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();

    $tmp_data = $stmt->fetchAll();
    $result_sales = $tmp_data[0]['sales'];

    return $result_sales;
}

// 選択した商品の在庫数が購入数に足りているかチェックする
function check_is_available($stock, $amount)
{
    $result = TRUE;
    if ($stock < $amount) {
        $result = FALSE;
    }
    return $result;
}

// 選択した商品が公開されているかチェックする：公開ならTRUE、非公開ならFALSEを返す
function check_status($dbh, $id)
{

    $result = TRUE;

    // SQL文の作成
    $sql = 'SELECT status FROM gm_items WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();

    $result_data = $stmt->fetchAll();
    if ($result_data[0]['status'] === 0) {
        $result = FALSE;
    }
    return $result;
}

// 購入チェック済みのitem_idリストを受け取り、各商品情報をsuccess/failureに分けて格納して返す
function get_result_item_list($id_list, $item_list)
{

    $result_data = array();
    $result_data['success'] = NULL;
    $result_data['failure'] = NULL;

    // NULLチェック
    if ($id_list['success'] !== NULL) {
        foreach ($id_list['success']['id'] as $line) {
            foreach ($item_list as $data) {
                if ($line === $data['id']) {
                    $result_data['success'][] = $data;
                }
            }
        }
    }

    // NULLチェック
    if ($id_list['failure'] !== NULL) {
        foreach ($id_list['failure']['id'] as $line) {
            foreach ($item_list as $data) {
                if ($line === $data['id']) {
                    $result_data['failure'][] = $data;
                }
            }
        }
    }

    return $result_data;
}

// 購入処理 購入が成功した商品の在庫と売上数を更新する ついでに合計金額を返す
function update_inventory($dbh, $cart_data, $item_data, $date, $user_id)
{
    $total_fee = 0; // 合計金額
    
    if (count($item_data['success']) > 0) {
        foreach ($item_data['success'] as $line) {

            $tmp_item_id = $line['id']; // 対象の商品id
            $tmp_amount = 0;

            // 対象の商品の購入数を取得する
            foreach ($cart_data as $num) {
                if ($num['item_id'] === $tmp_item_id) {
                    // cart_dataにあるitem_idが一致する商品のamountをcart_dataから取得
                    $tmp_amount = $num['amount'];
                }
            }
            // 合計金額に加算
            $total_fee += $tmp_amount * $line['price'];

            // 現在の在庫を取得
            $tmp_stock = check_stock($dbh, $tmp_item_id);
            // 現在の売上数を取得
            $tmp_sales = check_sales($dbh, $tmp_item_id);

            // 更新後のストック
            $updated_stock = $tmp_stock - $tmp_amount;
            // 更新後の売上
            $updated_sales = $tmp_sales + $tmp_amount;

            // 在庫数と売上を更新するSQL文の作成
            $sql = 'UPDATE gm_items SET stock = ?, sales = ?, updatedate = ? WHERE id = ?';
            // SQL文を実行する準備
            $stmt = $dbh->prepare($sql);
            // SQL文のプレースホルダに値をバインド
            $stmt->bindValue(1, $updated_stock, PDO::PARAM_INT);
            $stmt->bindValue(2, $updated_sales, PDO::PARAM_INT);
            $stmt->bindValue(3, $date, PDO::PARAM_STR);
            $stmt->bindValue(4, $tmp_item_id, PDO::PARAM_INT);
            // SQLを実行
            $stmt->execute();


            // cartテーブルから購入に成功した商品を削除する
            // cartのidの取得
            // 対象の商品の購入数を取得する
            foreach ($cart_data as $num) {
                $tmp_id = null;
                if ($num['user_id'] === $user_id) {
                    // cart_dataにあるitem_idが一致する商品の購入idをcart_dataから取得
                    if ($num['item_id'] === $tmp_item_id) {
                        $tmp_id = $num['id'];
                    }
                }

                // SQL文を作成
                $sql = 'DELETE FROM gm_carts WHERE id = ?';
                // SQL文を実行する準備
                $stmt = $dbh->prepare($sql);
                // SQL文のプレースホルダに値をバインド
                $stmt->bindValue(1, $tmp_id, PDO::PARAM_INT);
                // SQLを実行
                $stmt->execute();
            }
        }
    }
    return $total_fee;
}
