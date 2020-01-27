<?php
session_start();
require('dbconect.php');

if ($_COOKIE['login_id'] !== '') {
  $loginID = $_COOKIE['login_id'];
}

if (!empty($_POST)) {
  $loginID = $_POST['login_id'];
  if ($_POST['login_id'] !== '' && $_POST['password'] !== '') {
    $login = $db->prepare('SELECT * FROM members WHERE login_id=? AND password=?');
    $login->execute(array(
      $_POST['login_id'],
      sha1($_POST['password']
    )));
    $member = $login->fetch();

    if ($member) {
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      if ($_POST['save'] === 'on') {
        setcookie('login_id', $_POST['login_id'], time()+60*60*24*14);
      }

      header('Location: ../bbs');
      exit();
    } else {
      $error['login_id'] = 'failed';
    }
  } else {
    $error['login_id'] = 'blank';
  }
}
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
                  <h1>ログインする</h1>
                  <div class="control">
                    <label for="login_id">ログイン用ID <span>*英数字</span></label>
                    <input type="text" name="login_id" id="login_id" size="20" maxlength="255" value="<?php print(htmlspecialchars($login_id, ENT_QUOTES)); ?>">
                    <?php if ($error['login_id'] === 'blank'): ?>
                      <p class="error">* ログインIDとパスワードを入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['login_id'] === 'failed'): ?>
                      <p class="error">* ログインに失敗しました。正しくご記入ください</p>
                    <?php endif; ?>
                  </div>

                  <div class="control">
                    <label for="password">パスワード <span>*4桁以上</span></label>
                    <input type="password" name="password" id="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
                  </div>

                  <div class="control">
                    <label for="save">ログイン情報の記録</label>
                    <input id="save" type="checkbox" name="save" value="on">
                    <p>次回からは自動的にログインする</p>


                  <div class="control">
                    <button type="submit" class="btn btn-primary">ログインする</button>
                  </div>
          </form>
        </div>
      </div>
    </div>

      <div class="footer-menu">
      <div class="container">
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
