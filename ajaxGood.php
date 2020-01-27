<?php
require('dbconect.php');
session_start();

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

// postがある場合
if(isset($_POST['postId'])){
    $p_id = $_POST['postId'];

    try{
        // goodテーブルから投稿IDとユーザーIDが一致したレコードを取得するSQL文
        $sql = 'SELECT * FROM good WHERE post_id = :p_id AND user_id = :u_id';
        $data = array(':p_id' => $p_id, 'u_id' => $_SESSION['id']);
        // クエリ実行
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        $resultCount = $stmt->rowCount();
        // レコードが1件でもある場合
        if(!empty($resultCount)){

            // レコードを削除する
            $sql = 'DELETE FROM good WHERE post_id = :p_id AND user_id = :u_id';
            $data = array(':p_id' => $p_id, ':u_id' => $_SESSION['id']);
            // クエリ実行
            $stmt = $db->prepare($sql);
            $stmt->execute($data);
            echo count(getGood($p_id));
        }else{


            // レコードを挿入する
            $sql = 'INSERT INTO good (post_id, user_id, created_date) VALUES (:p_id, :u_id, :date)';
            $data = array(':p_id' => $p_id, ':u_id' => $_SESSION['id'], ':date' => date('Y-m-d H:i:s'));
            // クエリ実行
            $stmt = $db->prepare($sql);
            $stmt->execute($data);
            echo count(getGood($p_id));
        }

    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }

}

?>
