<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>じぶんギフト ごほうびマート 商品一覧</title>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>-->
    <!--<script type="text/javascript">-->
    <!--/*global $*/-->
    <!--    $(document).ready(function() {-->
    <!--        $('.slider').bxSlider({-->
    <!--            auto: true,-->
    <!--            pause: 5000,-->
    <!--        });-->
    <!--    });-->
    <!--</script>-->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>-->

    <!--<script>-->
    <!--    $(document).ready(function() {-->
    <!--        $('.slider').bxSlider();-->
    <!--    });-->
    <!--</script>-->
    
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/itemlist_v.css">
</head>

<body>
    <!--ヘッダーの表示-->
    <!--<?php var_dump($public_item_data); ?>-->
    <div class="header_item">
        <div class="header_logo_search">
            <a href="../controller/top.php"><img src="../view/img/structure/logo.png" class="img_logo"></a>
            <div class="search_item">
                <form method="post" action="itemlist.php?search=true">
                    <input type="text" name="word_search" placeholder="キーワードで検索">
                    <input type="submit" value="ギフトを探す">
                </form>
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
    <!--ダイアログメッセージ-->
    <div class="dialog">
        <?php foreach ($dialog as $line) {
            print $line;
        }
        ?>
    </div>

    <?php if (count($_GET) === 0) { ?>
        <!--ヘッダーバナー-->
        <div class="header_banner">
            <!--<a href=""><img src="../view/img/banner/banner_sample.png" width=840></a>-->
            <!--<a href = "itemlist.php">テキストリンク</a>-->
            <!--<div class="slider">-->
            <!--    <a href="itemlist.php?category=102"><div class="slider__content"><img src="../view/img/banner/banner_pickup_01.png" alt="slider_image1" class="slider__img"></div></a>-->
            <!--    <div class="slider__content"><a href = "itemlist.php?category=112"><img src="../view/img/banner/banner_pickup_02.png" alt="slider_image1" class="slider__img"></a></div>-->
            <!--    <div class="slider__content"><a href = "itemlist.php"><img src="../view/img/banner/banner_sample.png" alt="slider_image1" class="slider__img"></a></div>-->
            <!--</div>-->
            
            <!--bootstrapカルーセルサンプル-->
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                  <ol class="carousel-indicators">
                    <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></li>
                  </ol>
                  <div class="carousel-inner">
                    <div class="carousel-item active">
                      <a href = "itemlist.php?category=102"><img src="../view/img/banner/banner_pickup_01.png" class="d-block w-100" alt="..."></a>
                      <div class="carousel-caption d-none d-md-block">
                        <!--<h5>First slide label</h5>-->
                        <!--<p>Some representative placeholder content for the first slide.</p>-->
                      </div>
                    </div>
                    <div class="carousel-item">
                      <a href = "itemlist.php?category=112"><img src="../view/img/banner/banner_pickup_02.png" class="d-block w-100" alt="...">
                      <div class="carousel-caption d-none d-md-block">
                        <!--<h5>Second slide label</h5>-->
                        <!--<p>Some representative placeholder content for the second slide.</p>-->
                      </div>
                    </div>
                    <div class="carousel-item">
                      <img src="../view/img/banner/banner_sample.png" class="d-block w-100" alt="...">
                      <div class="carousel-caption d-none d-md-block">
                        <!--<h5>Third slide label</h5>-->
                        <!--<p>Some representative placeholder content for the third slide.</p>-->
                      </div>
                    </div>
                  </div>
                  <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </a>
                </div>
        </div>

    <?php } ?>

    <!--中央コンテンツ（ナビゲーション＋メインコンテンツ）-->
    <div class="contents">
        <!--ナビゲーション-->
        <nav>
            <ul class="ul_nav">
                <h3>カテゴリから探す</h3>
                <li class="list_category"><a href="itemlist.php">すべてのジャンル</a></li>
                <li class="list_category"><a href="itemlist.php?category=101">グルメ（肉・魚介）</a></li>
                <li class="list_category"><a href="itemlist.php?category=102">グルメ（スイーツ）</a></li>
                <li class="list_category"><a href="itemlist.php?category=103">グルメ（その他）</a></li>
                <li class="list_category"><a href="itemlist.php?category=104">お酒</a></li>
                <li class="list_category"><a href="itemlist.php?category=105">ゲーム・おもちゃ</a></li>
                <li class="list_category"><a href="itemlist.php?category=106">本・写真集</a></li>
                <li class="list_category"><a href="itemlist.php?category=107">エンタメグッズ</a></li>
                <li class="list_category"><a href="itemlist.php?category=108">インテリア</a></li>
                <li class="list_category"><a href="itemlist.php?category=109">ケアアイテム</a></li>
                <li class="list_category"><a href="itemlist.php?category=110">ヒーリンググッズ</a></li>
                <li class="list_category"><a href="itemlist.php?category=111">バスグッズ</a></li>
                <li class="list_category"><a href="itemlist.php?category=112">体験</a></li>
            </ul>
        </nav>
        <!--メインコンテンツ（ヘッダー＋絞り込み検索＋商品一覧）-->
        <div class="main_contents">


            <!--絞り込み検索部分-->
            <?php if (isset($_GET['category']) !== TRUE) { ?>
                <div class="refined_search">
                    <div class="refined_search_title">
                        <p>どんなじぶんギフトをお探しですか？</p>
                    </div>
                    <form method="post" action="itemlist.php?search=true" class="form_refined_search">
                        <div>ジャンルを選択:　
                            <input type="radio" name="search_genre" value="gourmet" checked>グルメを堪能
                            <input type="radio" name="search_genre" value="drink">お疲れ様の一杯！
                            <input type="radio" name="search_genre" value="entertainment">心おどる体験
                            <input type="radio" name="search_genre" value="healing">やすらぎ・疲労回復
                        </div>
                        <div>予算を選択:　
                            <input type="radio" name="search_budget" value="low" checked>お気軽ごほうび（～￥2,000）
                            <input type="radio" name="search_budget" value="middle">プチ贅沢 （￥2,001～￥5,000）
                            <input type="radio" name="search_budget" value="high">ちょっと贅沢（￥5,001～）
                        </div>
                        <div>表示順:　
                            <input type="radio" name="sort" value="0" checked>人気順
                            <input type="radio" name="sort" value="1">価格高い順
                            <input type="radio" name="sort" value="2">価格安い順
                        </div>
                        <div><input type="submit" value="じぶんギフトを探す" class="button_refined_search"></div>
                    </form>
                </div>
            <?php } ?>

            <!--商品一覧ヘッダー-->
            <div class="item_list_header">

            </div>
            <!--商品一覧部分-->

            <div class="item_list_top">
                <h3>おすすめギフト</h3>
                <?php if (isset($_GET['search']) !== TRUE) { ?>
                    <form method="post" class="sort">
                        <select name="sort" class="sort_select">
                            <option value="">表示順</option>
                            <option value="0">人気順</option>
                            <option value="1">価格高い順</option>
                            <option value="2">価格安い順</option>
                        </select>
                        <input type="submit" value="並び替え" class="sort_select">
                    </form>
                <?php } ?>
            </div>
            <?php if (isset($_GET['category'])) { ?>
                <div class="genre">ジャンル: <?php print $genre_name; ?></div>
            <?php } ?>
            <div class="item_list">
                <?php
                // 商品リスト配列が空っぽの場合はメッセージを表示
                if (count($public_item_data) === 0) {
                ?><p>現在購入できる商品がありません。</p>
                <?php  }
                foreach ($public_item_data as $line) {
                ?>
                    <div class="block_item">
                        <!--画像-->
                        <a href="iteminfo/item_<?php print $line['id']; ?>.php">
                            <!--画像と人気ラベルを表示-->
                            <div class="img_with_label">
                                <img src="<?php print $img_dir . $line['img']; ?>" class="item_list_img">
                                <?php if (isset($line['label'])) {
                                    if ($line['label'] === '1') { ?>
                                        <img src="../view/img/label/label_gold.png" class="label">
                                    <?php } else if ($line['label'] === '2') { ?>
                                        <img src="../view/img/label/label_silver.png" class="label">
                                    <?php } else if ($line['label'] === '3') { ?>
                                        <img src="../view/img/label/label_cu.png" class="label">
                                <?php }
                                }
                                ?>
                            </div>
                            <!--商品購入情報部分-->
                            <div class="purchase_info">
                                <!--ポップアップ部分（商品名＋値札）-->
                                <div class="pop_up">
                                    <?php print $line['name']; ?><br>
                                    ¥<?php print $line['price']; ?>
                                </div>
                            </div>
                            </href>
                            <?php if ($line['stock'] <= 0) { ?>
                                <span class="soldout">Sold Out</span>
                            <?php } ?>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
    <!--フッター    -->
    <footer>
        <div class=footer_item>
            <ul class="ul_footer">
                <li class="list_menu"><a href="#">サイトマップ</a></li>
                <li class="list_menu"><a href="#">プライバシーポリシー</a></li>
                <li class="list_menu"><a href="#">お問い合わせ</a></li>
                <li class="list_menu"><a href="#">ご利用ガイド</a></li>
            </ul>
            <p class=copyright>Copyright &copy; GohoubiCompany All Rights Reserved.</p>
        </div>
    </footer>
    </form>
</body>

</html>