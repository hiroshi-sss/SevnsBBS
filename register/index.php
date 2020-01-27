<?php
session_start();
require('../dbconect.php');

if (!empty($_POST)) {
  // 名前が入力されているか確認
  if ($_POST['name'] === '') {
    $error['name'] = 'blank';
  }
  if ($_POST['login_id'] === '') {
    $error['login_id'] = 'blank';
  }
  if (strlen($_POST['password']) < 4) {
    $error['password'] = 'length';
  }
  if ($_POST['password'] === '') {
    $error['password'] = 'blank';
  }

  $fileName = $_FILES['my_image']['name'];
  if (!empty($fileName)) {
    $ext = substr($fileName, -4);
		if ($ext != '.jpg' && $ext != '.gif' && $ext != '.png' && $ext != 'jpeg') {
			$error['my_image'] = 'type';
    }
  }

// アカウントの重複チェック
  if (empty($error)) {
    $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE login_id=?');
    $member->execute(array($_POST['login_id']));
    $record = $member->fetch();
    if ($record['cnt'] > 0) {
      $error['login_id'] = 'duplicate';
    }
  }

if (empty($error)) {

  $image = date('YmdHis') . $_FILES['my_image']['name'];
  move_uploaded_file($_FILES['my_image']['tmp_name'], '../member_icon/' . $image);

  $_SESSION['register'] = $_POST;
  $_SESSION['register']['my_image'] = $image;
  header('Location: confirmation.php');
  exit();
  }
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['register'])) {
  $_POST = $_SESSION['register'];
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
          <h1>アカウント登録</h1>
          <form action="" method="post" enctype="multipart/form-data">
                  <div class="control">
                    <label for="name">ニックネーム</label>
                    <input type="text" name="name" id="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>">
                    <?php if ($error['name'] === 'blank'): ?>
                      <p class="text-danger">* ニックネームを入力してください</p>
                    <?php endif; ?>
                  </div>

                  <div class="control">
                    <label for="login_id">ログイン用ID <span class="badge badge-pill badge-danger">*英数字</span></label>
                    <input type="text" name="login_id" id="login_id" size="20" maxlength="255" value="<?php print(htmlspecialchars($_POST['login_id'], ENT_QUOTES)); ?>">
                    <?php if ($error['login_id'] === 'blank'): ?>
                      <p class="text-danger">* ログインIDを記入してください</p>
                    <?php endif; ?>
                    <?php if ($error['login_id'] === 'duplicate'): ?>
                      <p class="text-danger">* 指定されたログインIDは既に使用されています</p>
                    <?php endif; ?>
                  </div>

                  <div class="control">
                    <label for="password">パスワード <span class="badge badge-pill badge-danger">*4桁以上</span></label>
                    <input type="password" name="password" id="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
                    <?php if ($error['password'] === 'length'): ?>
                      <p class="text-danger">* ４文字以上でパスワードを設定してください</p>
                    <?php endif; ?>
                    <?php if ($error['password'] === 'blank'): ?>
                      <p class="text-danger">* パスワードを入力してください</p>
                    <?php endif; ?>
                  </div>


                  <div class="form-group">
                      <label for="my_image">アイコン用画像</label>
                      <div id="my_image" class="input-group">
                          <div class="custom-file">
                              <input type="file" id="cutomfile" class="custom-file-input" name="my_image" lang="ja">
                              <label class="custom-file-label" for="customfile">ファイル選択...</label>
                          </div>
                      </div>
                      <?php if ($error['my_image'] === 'type'): ?>
                        <p class="text-danger">* 拡張子を正しく選択してください [.jpg, jpeg, gif, png]</p>
                      <?php endif; ?>
                      <?php if (!empty($error)): ?>
                        <p class="text-danger">* 恐れ入りますが、画像を再選択してください</p>
                      <?php endif; ?>
                  </div>

                  <div class="control">
                    <button type="submit" class="btn btn-primary">登録確認へ</button>
                  </div>

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
