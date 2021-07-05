<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>購入完了</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/cart_v.css">
</head>

<body>
    <!--<?php var_dump($result_item_list); ?>-->
    <!--<?php var_dump($cart_data); ?>-->
    
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
        <?php if($result_item_list['success'] !== NULL){ ?>
        <div class="dialog_cart">
                <p>下記の商品の注文が完了しました。ご購入ありがとうございました。</p>
        </div>
        
        <!--<?php var_dump($cart_data); ?>-->
        
        <!--カートに入った商品情報一覧の部分-->
        
        <?php foreach ($result_item_list['success'] as $line) { ?>
            <!--選択された商品のブロック-->
                <div class="block_cartitem">
                    <!--商品画像-->
                    <img class = "img_cart" src="<?php print $img_dir . $line['img']; ?>">
                    <!--商品情報（商品名＋価格）-->
                    <div class="item_info">
                        <div><?php print $line['name']; ?></div>
                        <div>¥<?php print $line['price']; ?></div>
                        <div>x <?php foreach($cart_data as $num){
                            if($num['item_id'] === $line['id']){
                        print $num['amount'];
                            }
                        }
                        ?></div>
                    </div>
                </div>
        <?php } ?>
        <div class = "total_fee">合計金額: ¥<?php print $total_fee; ?></div>
        <?php }
        ?>
        
        <!--ダイアログ表示部分-->
        <?php if($result_item_list['failure'] !== NULL){ ?>
        <div class="dialog_cart">
                <p>下記の商品は、在庫切れもしくは非公開商品のため、購入できませんでした。</p>
        </div>
        
        <!--<?php var_dump($result_item_list); ?>-->
        
        <!--カートに入った商品情報一覧の部分-->
        
        <?php foreach ($result_item_list['failure'] as $line) { ?>
            <!--選択された商品のブロック-->
                <div class="block_cartitem">
                    <!--商品画像-->
                    <img class = "img_cart" src="<?php print $img_dir . $line['img']; ?>">
                    <!--商品情報（商品名＋価格）-->
                    <div class="item_info">
                        <div><?php print $line['name']; ?></div>
                        <div>¥<?php print $line['price']; ?></div>
                        <div>x <?php foreach($cart_data as $num){
                            if($num['item_id'] === $line['id']){
                        print $num['amount'];
                            }
                        }
                        ?></div>
                    </div>
                </div>
        <?php }
        }
        ?>
        
        </div>
    <a href = "itemlist.php" class = "back_to_list"><< ショッピングに戻る</a>
</body>
</html>