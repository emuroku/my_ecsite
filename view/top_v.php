<?php
$username = '';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="../controller/css/common.css">
    <link rel="stylesheet" href="../controller/css/login_v.css">
</head>

<body>
    <div class="header_item">
        <div class = "header_logo_search">
            <a href="../controller/top.php"><img src="../view/img/structure/logo.png" class = "img_logo"></a>
        </div>
    </div>
    <div class="content_login">
                <form action="../controller/login.php" method="post">
                <div> <input type="text" name="username" placeholder="ユーザー名" value="<?php print $username; ?>">
                </div>
                <div><input type="password" name="password" placeholder="パスワード"></div>
                <div><input type="submit" value="ログイン">
                    <a href="../controller/register.php">
                        <button type="button">新規登録</button>
                    </a>
                </div>
                </form>
    </div>
    <div class="content_login">
        <ul>
            <?php
            foreach ($err_msg as $line) {
            ?>
                <li>
                    <?php print $line; ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</body>

</html>