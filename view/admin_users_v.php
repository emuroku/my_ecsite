<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ユーザ管理ページ</title>
    <link rel="stylesheet" href="../controller/css/admin.css">
</head>

<body>
    <h1>ごほうびマート ユーザ管理ページ</h1>
    <a href="../controller/admin.php">商品管理ページ</a>
    <a href="logout.php">ログアウト</a>
    <h2>ユーザ情報一覧</h2>
    <table>
        <tr>
            <th>ユーザID</th>
            <th>メールアドレス</th>
            <th>性別</th>
            <th>最新情報</th>
            <th>登録日</th>
        </tr>
        <?php foreach ($users_data as $line) { ?>
        <tr>
            <td><?php print $line['username']; ?></td>
            <td><?php print $line['mail']; ?></td>
            <td><?php if ($line['sex'] === 0) {
                    print 'male';
                } else if ($line['sex'] === 1) {
                    print 'female';
                } else if ($line['sex'] === 2) {
                    print 'other';
                } ?></td>
            <td><?php if($line['magazine'] === 1){
                print '受け取る'; }
                else{
                print '受け取らない';
                } 
                ?></td>    
            <td><?php print $line['createdate']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

</html>