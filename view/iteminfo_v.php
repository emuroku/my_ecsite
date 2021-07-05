<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品情報</title>
    <link rel = "stylesheet" href = "../css/common.css" >
    <link rel = "stylesheet" href = "../css/iteminfo_v.css" >
</head>

<body>
    <!--ヘッダーの表示-->
    <div class="header_item">
        <div class = "header_logo_search">
            <a href="../top.php"><img src="../../view/img/structure/logo.png" class = "img_logo"></a>
            <div class = "search_item">
            </div>
        </div>
        <div class = "header_end_item">
            <?php print $user_name; ?>さんのページ
            <div class = "header_icon">
            <a href="../cart.php"><img src="../../view/img/structure/icon_cart.png" class = "header_icon"></a>
            <a href="../logout.php"><img src="../../view/img/structure/icon_logout.png" class = "header_icon"></a>
            </div>
        </div>
    </div>
    <div class = "block_iteminfo">
        <!--画像の表示-->
        <img src ="<?php print $img_dir . $item_data[0]['img']; ?>" class = "item_info_img">
        <!--商品詳細部分-->
        <div class = "block_item_textinfo">
                <div>
                <h2><?php print $item_data[0]['name'];?></h2>
                <p>¥<?php print $item_data[0]['price'];?><p>
                <p>ジャンル: <?php print get_category_name($dbh, $item_data[0]['category']);?></p>
                <small><?php print $item_data[0]['comment'];?></small>
                </div>
                
                <!--在庫数が1以上あればカートに入れるボタンを表示する-->
                <?php if($item_stock > 0){ ?>
                <form method = 'post' action = "../itemlist.php">
                    <input class = "button_cart" type = "submit" name = "add_to_cart" value = "カートに入れる">
                    <input type = "hidden" name = "added_item_id" value = '<?php print $item_data[0]['id']; ?>'>
                </form>
                <?php }else{ ?>
                    <p class = "sold_out">Sold Out</p>
                <?php } ?>    
        </div>
    </div>
</body>
</html>