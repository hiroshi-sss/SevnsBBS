<?php
session_start();
require('../dbconect.php');

if(!isset($_SESSION['register'])) {
  header('Location: ../register');
  exit();
}

if(!empty($_POST)) {
  $statement = $db->prepare('INSERT INTO members SET name=?, login_id=?, password=?, picture=?, created=NOW()');
  $statement->execute(array(
    $_SESSION['register']['name'],
    $_SESSION['register']['login_id'],
    sha1($_SESSION['register']['password']),
    $_SESSION['register']['my_image']
  ));
  unset($_SESSION['register']);

  header('Location: done.php');
  exit();
}

$path = '../member_icon/' . htmlspecialchars($_SESSION['register']['my_image'], ENT_QUOTES);

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
                      <li><a href="../register">新規登録</a></li>
                      <li><a href="../bbs">ログイン</a></li>
                  </ul>
              </nav>
          </div>
          <!-- /#gloval-nav -->
        </div>
        <div class="col-md-2 text-center header-right">
          <a href="../register">新規登録</a>
        </div>
        <div class="col-md-2 text-center header-right">
          <a href="../bbs">ログイン</a>
        </div>
      </div>
    </div>
  </header>

      <div class="container">
        <div class="form">
          <form action="" method="post">
            <input type="hidden" name="action" value="submit">

            <dl>
              <dt>ニックネーム</dt>
              <dd><?php print(htmlspecialchars($_SESSION['register']['name'], ENT_QUOTES)); ?></dd>
              <dt>ログインID</dt>
              <dd><?php print(htmlspecialchars($_SESSION['register']['login_id'], ENT_QUOTES)); ?></dd>
              <dt>パスワード</dt>
              <dd>セキュリティにより表示されません</dd>
              <dt>アイコン画像</dt>
              <dd>
                <?php if ($_SESSION['register']['my_image'] !== ''): ?>
                  <img src="<?php
                    if (file_exists($path)) {
                      echo ($path);
                    } else {
                      echo '../member_icon/bg.jpg';
                    }
                   ?>" width="30%" height="30%">
                <?php endif; ?>
              </dd>

            <div><button type="button" class="btn btn-light" onclick="location.href='../register?action=rewrite'">書き直す</button> | <button type="submit" class="btn btn-primary" value="">登録する</button></div>
          </form>
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
  </body>
</html>
