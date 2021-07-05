<?php 

// 未入力を弾くバリデーション
// 文字列を受け取り、未入力もしくはスペースのみ入力された場合にFalse、そうでない場合にTrueを返す
function validation_empty($input)
{   
    $result = TRUE;
    // スペースのみ入力された名前を弾く
    $pattern = "^(\s|　)+$"; // 正規表現のパターン

    if ($input === "" || mb_ereg_match($pattern, $input) === TRUE) {
        $result = FALSE;
    }
    return $result;
}

//画像ファイルの拡張子を取得する
function get_extenstion($file)
{
    // 拡張子を取得
    $extension = pathinfo($file['img']['name'], PATHINFO_EXTENSION);
    return $extension;
}


// 画像ファイルの拡張子をチェックする。指定の拡張子出ない場合はFALSEを返す
function extension_check($file)
{
    $result = TRUE;
    // 拡張子を取得
    $extension = get_extenstion($file);
    // 指定の拡張子であるかチェック
    if ($extension !== 'jpeg' && $extension !== 'png' && $extension !== 'jpg') {
        $result = FALSE;
    }
    return $result;
}

// 画像ファイルをアップロードしてファイル名を返す
function create_filename($file)
{
    // ファイル名の初期化
    // 拡張子の取得
    $extension = get_extenstion($file);
    // 保存する新しいファイル名の生成
    $new_img_filename = sha1(uniqid(mt_rand(), true)) . '.' . $extension;

    return $new_img_filename;
}

// 画像ファイル名を受け取ってファイルを保存する
function file_upload($file, $err_msg, $filename, $img_dir)
{
    // 同名ファイルが存在するかチェックし、アップロード出来ない場合はエラーメッセージ配列にメッセージを入れて返す
    if (is_file($img_dir . $filename) !== TRUE) {
        // 同名ファイルが無ければ、ファイルを指定ディレクトリへ保存
        if (move_uploaded_file($file['img']['tmp_name'], $img_dir . $filename) !== TRUE) {
            $err_msg[] = 'ファイルアップロードに失敗しました';
        }
    } else {
        $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
    }
    return $err_msg;
}

// 特殊文字をHTMLエンティティに変換する
function entity_str($str)
{
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

// 特殊文字をHTMLエンティティに変換して2次元配列ごと返す
function entity_assoc_array($assoc_array)
{
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}

// クエリを実行しその結果を配列で取得する
function get_as_array($dbh, $sql)
{
    try {
        // SQLを実行する準備
        $stmt = $dbh->prepare($sql);

        // SQLを実行
        $stmt->execute();

        // レコードの取得
        $data = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $data;
}


// POSTされたデータをbindValueしてgm_itemsテーブルへINSERTする
function insert_items($dbh, $name, $price, $img, $status, $stock, $category, $genre, $comment)
{
    // 実行時刻の取得
    $datetime = date('Y-m-d H:i:s') . "\t";
    // SQL文の作成
    $sql = 'INSERT INTO gm_items(name, price, img, status, stock, category, genre, comment, createdate, updatedate) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $name, PDO::PARAM_STR);
    $stmt->bindValue(2, $price, PDO::PARAM_INT);
    $stmt->bindValue(3, $img, PDO::PARAM_STR);
    $stmt->bindValue(4, $status, PDO::PARAM_INT);
    $stmt->bindValue(5, $stock, PDO::PARAM_INT);
    $stmt->bindValue(6, $category, PDO::PARAM_STR);
    $stmt->bindValue(7, $genre, PDO::PARAM_STR);
    $stmt->bindValue(8, $comment, PDO::PARAM_STR);
    $stmt->bindValue(9, $datetime, PDO::PARAM_STR);
    $stmt->bindValue(10, $datetime, PDO::PARAM_STR);

    // SQLを実行
    $stmt->execute();
}

// 登録済み商品の在庫数を更新するSQLを作成しクエリを実行する
function update_stock($dbh, $id, $stock)
{
    // 実行時刻の取得
    $datetime = date('Y-m-d H:i:s') . "\t";
    // SQL文を作成
    $sql = 'UPDATE gm_items SET stock = ?, updatedate = ? WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $stock, PDO::PARAM_INT);
    $stmt->bindValue(2, $datetime, PDO::PARAM_STR);
    $stmt->bindValue(3, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
}

// 登録済み商品のステータスを更新するSQLを作成しクエリを実行する
function update_status($dbh, $id, $status)
{
    // 実行時刻の取得
    $datetime = date('Y-m-d H:i:s') . "\t";
    // SQL文を作成
    $sql = 'UPDATE gm_items SET status = ?, updatedate = ? WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $status, PDO::PARAM_INT);
    $stmt->bindValue(2, $datetime, PDO::PARAM_STR);
    $stmt->bindValue(3, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
}

// 投入金額が正規の値かをチェックする
function validation_insert_coin($insert_coin, $err_msg)
{
    if(preg_match('/^[1-9][0-9]*$/', $insert_coin) !== 1){
        if ($insert_coin !== "" && $insert_coin !== '0') {
            $err_msg[] = 'お金は0以上の半角の整数で入力してください';
        }
    }
    return $err_msg;
}

// 選択した商品が公開商品かをチェックする
function check_status($status, $err_msg)
{
    if ($status === 0) {
        $err_msg[] = '非公開の商品のため購入できません';
    }
    return $err_msg;
}

// 選択した商品の在庫があるかをチェックする
function check_stock($stock, $err_msg)
{
    if ($stock === 0) {
        $err_msg[] = '選択した商品は売り切れです';
    }
    // var_dump($err_msg);
    return $err_msg;
}

// 指定のdrink_idの在庫数を取得する
function get_stock($dbh, $id)
{
    // SQL文生成
    $sql = 'SELECT drink_stock.stock FROM drink_master JOIN drink_stock ON drink_master.drink_id = drink_stock.drink_id  WHERE drink_master.drink_id = ?';
    // SQLを実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    $data = $stmt->fetchAll();
    return $data;
}

// 指定したidの商品のレコードを削除する
function delete_item($dbh, $table_name, $id){
    // SQL文を作成
    $sql = 'DELETE FROM ' . $table_name . ' WHERE id = ?';
    // SQL文を実行する準備
    $stmt = $dbh -> prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt -> bindValue(1, $id, PDO::PARAM_INT);
    // SQLを実行
    $stmt -> execute();
}


// 商品詳細ページ読み込みファイルをテンプレから生成
function create_new_iteminfo($dbh){
    
        $files = file_get_contents('iteminfo/template.php');
                    
        // 商品idを取得
        $item_id = $dbh -> lastInsertId();
                    
        // ファイル名に商品idを挿入
        $filename = "../controller/iteminfo/item_".$item_id.'.php';
                    
        // テンプレから変更点の書き換え
        $files = str_replace('$new_item_id', $item_id, $files);
                    
        // ファイル生成、書き込み
        $handle = fopen($filename, 'w');
                    
        fwrite($handle, $files);
        fclose($handle);
}
