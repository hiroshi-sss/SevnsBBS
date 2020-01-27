<?php
session_start();
require('dbconect.php');

// 検索機能の定義
if (isset($_GET['search'])) {
  $search = htmlspecialchars($_GET['search']);
  $search_value = $search;
} else {
  $search = '';
  $search_value = '';
}


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

if (!empty($_POST)) {
  if ($_POST['message'] !== '') {
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');
    $message->execute(array(
      $member['id'],
      $_POST['message'],
      $_POST['reply_post_id']
    ));

    header('Location: ../bbs');
    exit();
  }
}



// サーチ機能を使わない場合
if (!isset($_GET['search'])) {
  $page = $_REQUEST['page'];
  if ($page == '') {
    $page = 1;
  }
  $page = max($page, 1);

  $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
  $cnt = $counts->fetch();
  $maxPage = ceil($cnt['cnt'] / 5);
  $page = min($page, $maxPage);

  $start = ($page - 1) * 5;


  $posts = $db->prepare('SELECT m.name, m.login_id, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,7');
  $posts->bindParam(1, $start, PDO::PARAM_INT);
  $posts->execute();
} else {
// サーチ機能を使う場合
  $page = $_REQUEST['page'];
  if ($page == '') {
    $page = 1;
  }
  $page = max($page, 1);

  $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
  $cnt = $counts->fetch();
  $maxPage = ceil($cnt['cnt'] / 5);
  $page = min($page, $maxPage);

  $start = ($page - 1) * 5;


  $posts = $db->prepare("SELECT m.name, m.login_id, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.message LIKE '%$search%' ORDER BY p.created DESC LIMIT ?,7");
  $posts->bindParam(1, $start, PDO::PARAM_INT);
  $posts->execute();
}


if (isset($_REQUEST['res'])) {
  //返信の処理
  $response = $db->prepare('SELECT m.name, m.picture, p.*FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $message = '@' . $table['name'] . "\n" . $table['message'];
}

// getGood関数の定義
function getGood($p_id) {
  require('dbconect.php');

  try {
    $sql = 'SELECT * FROM good WHERE post_id =:p_id';
    $data = array(':p_id' => $p_id);
    $stmt = $db->prepare($sql);
    $stmt->execute($data);

    if($stmt) {
      return $stmt->fetchAll();
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生：' . $e->getMessage());
  }
}

// isGood関数の定義
function isGood($u_id, $p_id) {
  require('dbconect.php');
  try {
    $sql ='SELECT * FROM good WHERE post_id = :p_id AND user_id = :u_id';
    $data = array(':u_id' => $u_id, ':p_id' => $p_id);
    $stmt = $db->prepare($sql);
    $stmt->execute($data);

    if($stmt->rowCount()) {
      return true;
    } else {
      return false;
    }
  } catch (Exception $e) {
      error_log('エラー発生：' . $e->getMessage());
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
          <!-- アイコン画像ここから -->
          <img src="<?php
          $path = 'member_icon/' . htmlspecialchars($member['picture'], ENT_QUOTES);
          if (file_exists($path)) {
            print($path);
          } else {
            print'../member_icon/bg.jpg';
          }
           ?>" width="15%" height="15%" class="mr-3" alt="icon">
          <!-- アイコン画像ここまで -->
          <p><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?> さん、ようこそ</p>
            <div class="control tweet">
            <form action="" method="post">
                <label for="message">一言ぼやいちゃう</label>
                <textarea name="message" cols="50" rows="5" id="message"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
                <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>">
              </div>
              <div class="control">
                <button type="submit" class="btn btn-primary btn-lg btn-block">投稿する</button>
              </div>
            </form>
            <!-- 掲示板内検索機能 -->
            <form class="form-inline" action="../bbs" method="get">
              <input class="form-control mr-sm-2" id="search" name="search" type="search" placeholder="掲示板内検索" aria-label="search" value="<?php echo $search_value ?>">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索</button>
            </form>



            <hr>


            <div class="form-tweet">
              <?php foreach ($posts as $post): ?>
                <div class="msg">
                  <div class="media">

                  <!-- アイコン画像ここから -->
                  <img src="<?php
                  $path = 'member_icon/' . htmlspecialchars($post['picture'], ENT_QUOTES);
                  if (file_exists($path)) {
                    print($path);
                  } else {
                    print'../member_icon/bg.jpg';
                  }
                   ?>" width="15%" height="15%" class="mr-3" alt="icon">
                  <!-- アイコン画像ここまで -->

                  <div class="media-body">

                    <!-- 名前 -->
                    <h5 class="mt-0"><?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?></h5>

                    <!-- ログインID -->
                    <h6>@<?php print(htmlspecialchars($post['login_id'], ENT_QUOTES)); ?></h6>
                    ・
                    <!-- 投稿時間 -->
                    <h6><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></h6>

                    <!-- メッセージ -->
                    <p><?php print(nl2br(htmlspecialchars($post['message'], ENT_QUOTES))); ?><p>

                    <!-- いいねボタン -->
                    <section class="post" data-postid="<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">
                      <div class="my-good <?php if(isGood($_SESSION['id'], $dbPostData['id'])) echo 'active'; ?>">
                        <i class="fa-heart fa-lg
                        <?php
                          if (isGood($_SESSION['id'],$post['id'])) {
                            echo ' active fas';
                          } else {
                            echo ' far';
                          };
                        ?>
                        "></i><span><?php

                        $dbPostGoodNum = count(getGood($post['id']));
                          echo $dbPostGoodNum; ?></span>
                      </div>
                    </section>


                    <!-- 返信 -->
                    <p class="n">[<a href="../bbs?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">返信する</a>]</p>

                    <!-- 返信元メッセージ -->
                    <?php if ($post['reply_message_id'] > 0): ?>
                      <p class="n">[<a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'])); ?>">返信元のメッセージ</a>]</p>
                    <?php endif; ?>


                    <!-- 削除ボタンここから -->
                    <?php if ($_SESSION['id'] == $post['member_id']): ?>
                    <p class="n"><a href="modal" class="badge badge-danger" data-toggle="modal" data-target="#delete">削除</a></p>
                    <div class="modal fade" id="delete" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">投稿の削除</h5>
                          </div>
                          <div class="modal-body">
                            本当に削除しますか？
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取り消す</button>
                            <button type="button" class="btn btn-danger" onclick="location.href='delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>'">削除する</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php endif; ?>
                    <!-- 削除ボタンここまで -->

                  </div>
                </div>


                  <hr>

              </div>

                <?php endforeach; ?>

          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php if($page > 1): ?>
                <li class="page-item"><a class="page-link" href="../bbs?page=<?php
                if (!isset($_GET['search'])) {
                  print($page-1);
                } else {
                  print($page-1 . '&search=' . $search);
                } ?>">前のページへ</a></li>
              <?php endif; ?>

              <?php if($page < $maxPage): ?>
                <li class="page-item"><a class="page-link" href="../bbs?page=<?php
                if (!isset($_GET['search'])) {
                  print($page+1);
                } else {
                  print($page+1 . '&search=' . $search);
                } ?>">次のページへ</a></li>
              <?php endif; ?>
            </ul>
          </nav>
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
