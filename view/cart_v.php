<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>カート</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/cart_v.css">
</head>

<body>
    <!--ヘッダーの表示-->
    <div class="header_item">
        <div class="header_logo_search">
            <a href="../controller/top.php"><img src="../view/img/structure/logo.png" class="img_logo"></a>
            <div class="search_item">
            </div>
        </div>
        <div class="header_end_item">
            <?php print $user_name; ?>さんのページ
            <div class="header_icon">
                <a href="cart.php"><img src="../view/img/structure/icon_cart.png" class="header_icon"></a>
                <a href="logout.php"><img src="../view/img/structure/icon_logout.png" class="header_icon"></a>
            </div>
        </div>
    </div>

    <div class="contents_cart">
        <!--ダイアログ表示部分-->
        <div class="dialog_cart">
            <?php if ($count_cartitem === 0) { ?>
                <p>現在カートには商品が入っていません。</p>
            <?php } else { ?>
                <p>現在<?php print $count_cartitem; ?>点の商品がカートに入っています。</p>
            <?php } ?>
                <p class = "cart_dialog"><?php print $dialog; ?></p>
            <?php if(count($err_msg) > 0){
                    foreach($err_msg as $line){ ?>
                <p class = "err_msg"><?php print $line; ?></p>
            <?php }
            } ?>
        </div>
        
        <!--<?php var_dump($cart_item_data); ?>-->
        
        <!--カートに入った商品情報一覧の部分-->
        <?php foreach ($cart_item_data as $line) { ?>
            <!--選択された商品のブロック-->
                <div class="block_cartitem">
                    <!--商品画像-->
                    <img class = "img_cart" src="<?php print $img_dir . $line[0]['img']; ?>">
                    <!--商品情報（商品名＋価格）-->
                    <div class="item_info">
                        <div><?php print $line[0]['name']; ?></div>
                        <div>¥<?php print $line[0]['price']; ?></div>
                    </div>
                    <!--サイドボタン（削除ボタン＋個数変更ボックス＆ボタン）-->
                    <div class="side_button">
                        <!--削除ボタン-->
                        <form class = "delete_button" method="post">
                            <input type="submit" value="削除" class = "button">
                            <input type="hidden" name="sql_order" value="delete">
                            <input type="hidden" name="delete_id" value="<?php print $line['cart_id'] ?>"> <!-- 削除する商品のidを送信する -->
                        </form>
                        <!--個数変更ボックス&ボタン-->
                        <form class="quantity" method = "post">
                            x <input type = "text" name = "update_amount" value = "<?php print $line['amount']; ?>" class = "input_quantity">
                            <input type = "hidden" name = "sql_order" value = "amount_update">
                            <input type = "hidden" name = "update_id" value = "<?php print $line[0]['id']; ?>">
                            <input type="submit" value="変更" class = "button">
                        </form>
                    </div>
                </div>
        <?php } ?>
        <?php if(count($cart_item_data) !== 0){ ?>
        <!--合計金額を表示-->
        <div class = "total_fee">合計金額: ¥<?php print $total_fee; ?></div>
        <form method = "post" action = "finish.php" class = "buy">
            <input type = "submit" value = "購入する" class = "button_buy">
            <input type = "hidden" name = "sql_order" value = "buy">
        </form>
        <?php } ?>
        </div>
    <a href = "itemlist.php" class = "back_to_list"><< ショッピングに戻る</a>
</body>
</html>