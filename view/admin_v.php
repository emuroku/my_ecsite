<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>商品管理ページ</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <?php
    ?>
    <h1>ごほうびマート 商品管理ページ</h1>
    <a href = "../controller/admin_users.php">ユーザ情報一覧</a>
    <a href="logout.php">ログアウト</a>
    <h2>新規商品追加</h2>
    <?php // 完了ダイアログを表示
    print $dialog;
    ?>
    <ul>
        <?php
        // エラーメッセージを表示
        // var_dump($err_msg);
        foreach ($err_msg as $line) { ?>
            <li><?php print $line; ?></li>
        <?php } ?>
    </ul>
    <form method="post" enctype="multipart/form-data">
        <div>名前:
            <input type="text" name="name" value="">
        </div>
        <div>値段:
            <input type="text" name="price" value="">
        </div>
        <div>個数:
            <input type="text" name="stock" value="">
        </div>
        <div>商品画像:
            <input type="file" name="img">
        </div>
        <div>ステータス:
            <select name="status">
                <option value="0" selected>非公開</option>
                <option value="1">公開</option>
            </select>
        </div>
        <div>カテゴリ:
            <select name="category">
                <option value=""></option>
                <option value="101">グルメ（肉・魚介）</option>
                <option value="102">グルメ（スイーツ）</option>
                <option value="103">グルメ（その他）</option>
                <option value="104">お酒</option>
                <option value="105">ゲーム・おもちゃ</option>
                <option value="106">本・写真集</option>
                <option value="107">エンタメグッズ</option>
                <option value="108">インテリア</option>
                <option value="109">ケアアイテム</option>
                <option value="110">ヒーリンググッズ</option>
                <option value="111">バスグッズ</option>
                <option value="112">体験</option>
            </select>
        </div>
        <div>ジャンル:
            <select name="genre">
                <option value=""></option>
                <option value="gourmet">グルメ</option>
                <option value="drink">お酒</option>
                <option value="entertainment">エンタメ</option>
                <option value="healing">ヒーリング</option>
            </select>
        </div>
        <div>商品説明:
            <input type="text" name="comment" value="商品の説明テキストを入力">
        </div>
        <input type="submit" value="商品を登録する">
        <input type="hidden" name="sql_order" value="insert">
    </form>
    <h2>商品情報変更</h2>
    <h3>商品一覧</h3>
    <table>
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>ステータス</th>
            <th>カテゴリ</th>
            <th>ジャンル</th>
            <th>売上総数</th>
            <th>商品説明</th>
            <th>操作</th>
        </tr>
        <?php foreach ($items_data as $line) {
            // ステータスで背景色を出し分ける
            if ($line['status'] === '0') { ?>
                <tr class="private_data">
                <?php } else { ?>
                <tr>
                <?php } ?>
                <td><img src="<?php print $img_dir . $line['img']; ?>" width=200px;> </td>
                <td><?php print $line['name']; ?></td>
                <td><?php print $line['price']; ?>円</td>
                <td>
                    <form method="post">
                        <input type="text" name="update_stock_num" value="<?php print $line['stock'] ?>" style="width: 30px;">個
                        <input type="submit" value="変更">
                        <input type="hidden" name="sql_order" value="update"> <!-- SQL文がUPDATE（在庫数更新）になることを送信する -->
                        <input type="hidden" name="update_id" value="<?php print $line['id'] ?>"> <!-- 在庫数を更新したdrink_idを送信する -->
                    </form>
                </td>
                <td><?php
                    if ($line['status'] === '0') {
                        print "非公開"; ?>
                        <form method="post">
                            <input type="submit" value="非公開→公開">
                            <input type="hidden" name="sql_order" value="update_status">
                            <input type="hidden" name="update_status" value="1">
                            <input type="hidden" name="update_id" value="<?php print $line['id'] ?>"> <!-- ステータスを更新したdrink_idを送信する-->
                        </form>
                    <?php } else {
                        print "公開"; ?>
                        <form method="post">
                            <input type="submit" value="公開→非公開">
                            <input type="hidden" name="sql_order" value="update_status">
                            <input type="hidden" name="update_status" value="0">
                            <input type="hidden" name="update_id" value="<?php print $line['id'] ?>"> <!-- ステータスを更新したdrink_idを送信する -->
                        </form>
                    <?php } ?>
                </td>
                <td><?php print get_category_name($dbh, $line['category']);?></td>
                <td><?php print $line['genre']; ?></td>
                <td><?php print $line['sales']; ?></td>
                <td><?php print $line['comment']; ?></td>
                <td>
                    <form method="post">
                    <input type="submit" value="削除">
                    <input type="hidden" name="sql_order" value="delete">
                    <input type="hidden" name="delete_id" value="<?php print $line['id'] ?>"> <!-- 削除する商品のidを送信する -->
                    </form>
                </td>
                </tr>
            <?php
        }
            ?>
    </table>
</body>

</html>