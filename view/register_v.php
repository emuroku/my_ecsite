<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>アカウント新規登録</title>
    <link rel="stylesheet" href="../controller/css/common.css">
    <link rel="stylesheet" href="../controller/css/register_v.css">
</head>

<body>
    <?php
    if($insert_result === TRUE){
        include_once 'registered_v.php';
    }
    else{ ?>
    <div class="header_item">
        <div class = "header_logo_search">
            <a href="../controller/top.php"><img src="../view/img/structure/logo.png" class = "img_logo"></a>
        </div>
    </div>
    <div class="content_register">
        <h3>アカウント新規登録</h3>
        <form method = "post">
                <p class="input_item"><label for="user_name">ユーザー名</label>
                    <input type="text" id="user_name" name="user_name" placeholder="6字以上の半角英数字">
                </p>
                <p class="input_item"><label for="passwd">パスワード</label>
                    <input type="password" id="passwd" name="password" placeholder="6字以上の半角英数字">
                </p>
                <p class="input_item">
                    <label for="sex">性別</label>
                    <select name="sex">
                        <option value="">選択</option>
                        <option value='0'>男性</option>
                        <option value='1'>女性</option>
                        <option value='2'>その他</option>
                    </select>
                </p>
                <p class="input_item">
                    <label for="mail">メールアドレス</label>
                    <input type="email" name="mail" id="mail">
                </p>
                <p>
                    <input type="checkbox" name="magazine" value="1" checked>お得な最新情報を受け取る
                </p>
                <input type="submit" class="button" value="登録する">
                </form>
                <?php foreach ($err_msg as $line) {
                ?>
                    <ul>
                        <?php print $line; ?>
                    </ul>
                <?php } ?>
                <a href = "../controller/top.php">ログインページへ</a>
    </div>
    
</body>
<?php } ?>

</html>