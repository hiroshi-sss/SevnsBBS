<?php
session_start();
require('dbconect.php');

// ログイン情報の確認
// 不正にアクセスされた場合にログイン画面に戻す
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  header('Location: ../login');
  exit();
}

$date = $member['created'];


$goods = $db->prepare('SELECT COUNT(*) FROM good WHERE user_id=?');
$goods->execute(array($_SESSION['id']));
$good = $goods->fetch();

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <title>SevenS BBS - Portfolio</title>
  <link rel="stylesheet" href="../css/style.css?ver=48">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="../js/jquery-3.4.1.min.js"></script>

</head>
<body>
  <header>
    <div class="container">
      <div class="row h-menu">
        <div id="container" class="col-md-6 header-left">
          <div>
            <a class="title-pc" href="../"><h1>Seventh Sense BBS</h1></a>
            <a class="title-smart" href="../"><h1>SevenS BBS</h1></a>
          </div>
          <div id="nav-toggle">
            <div>
                <span></span>
                <span></span>
                <span></span>
            </div>
          </div>
          <div id="gloval-nav">
              <nav>
                  <ul>
                      <li><a href="../profile?=<?php print(htmlspecialchars($member['login_id'], ENT_QUOTES)); ?>">マイページ</a></li>
                      <li><a href="../logout">ログアウト</a></li>
                  </ul>
              </nav>
          </div>
          <!-- /#gloval-nav -->
        </div>
        <div class="col-md-2 text-center header-right">
          <a href="../profile?=<?php print(htmlspecialchars($member['login_id'], ENT_QUOTES)); ?>">マイページ</a>
        </div>
        <div class="col-md-2 text-center header-right">
          <a href="../logout">ログアウト</a>
        </div>
      </div>
    </div>
  </header>

      <div class="container">
        <div class="form">
          <p><a href="../bbs">掲示板に戻る</a></p>
          <form name="mainForm" action="" method="post" class="profile_ajax" data-profileid="<?php print($_SESSION['id']); ?>" enctype="multipart/form-data">
          <div class="card" style="width: 18rem;">
            <div class="card-body">
              <h4 class="card-text">プロフィール</h4>
            </div>
            <img src="<?php
            $path = 'member_icon/' . htmlspecialchars($member['picture'], ENT_QUOTES);
            if (file_exists($path)) {
              print($path);
            } else {
              print'../member_icon/bg.jpg';
            }
             ?>" class="card-img-top" alt="...">
            <div class="card-body">

              <h5 class="card-title names button_area"><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?></h5>
              <input type="text" class="form-control button_area names" name="names" id="names" size="25" maxlength="30" value="<?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>">
              <h6 class="card-title"><?php
              print(htmlspecialchars('@' . $member['login_id'], ENT_QUOTES)); ?></h6>

              <p class="card-text">一言：<span class="my_pr button_area"><?php print(htmlspecialchars($member['my_pr'], ENT_QUOTES)); ?></span></p>
              <input type="text" class="form-control my_prs button_area"  name="my_prs" id="my_prs" size="50" maxlength="140" value="<?php print(htmlspecialchars($member['my_pr'], ENT_QUOTES)); ?>">
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">合計いいね：<i class="fa-heart fas active fa-lg"></i><span><?php print($good["COUNT(*)"]); ?></span></li>
              <li class="list-group-item">アカウント作成日：<br>[ <?php print(date('Y年m月d日',  strtotime($date))); ?> ]</li>
            </ul>
            <?php if($member['id'] == $_SESSION['id']): ?>
            <div class="card-body">
              <button type="button" id="edit" class="btn btn-primary btn-lg btn-block toggle_btn button_area" data-area=".button_area">プロフィール編集</button>
              <button type="button" id="done" class="btn btn-success btn-lg btn-block toggle_btn button_area" data-area=".button_area">完了</button>
              <button type="button" id="cancel" class="btn btn-secondary btn-lg btn-block toggle_btn button_area" data-area=".button_area">中止</button>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="footer-menu">
        <div class="row">
          <div class="col-md-2 offset-md-2 text-center">
            <a href="../">ホーム</a>
          </div>
          <div class="col-md-2 text-center">
            <a href="../introduction">自己紹介</a>
          </div>
          <div class="col-md-2 text-center">
            <a href="../portfolio">作品一覧</a>
          </div>
          <div class="col-md-2 text-center last">
            <a href="../contact">お問い合わせ</a>
          </div>
        </div>
      </div>

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-8 offset-4 text-right f-menu">
            <p>&copy2019 Seventh Sense</p>
          </div>
        </div>
      </div>
    </footer>
    <script src="../js/jquery.js?ver=7"></script>
    <script src="../js/ajax.js?ver=1"></script>
  </body>
</html>
