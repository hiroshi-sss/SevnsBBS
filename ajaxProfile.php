<?php
require('dbconect.php');
session_start();

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}



if(isset($_POST['prId'])) {
  $name_id = $_POST['nameId'];
  $mypr_id = $_POST['my_prId'];

  $sql = 'UPDATE members SET name = :name, my_pr = :my_pr WHERE members . id = :id';
  $data = array(':name' => $name_id, ':my_pr' => $mypr_id, ':id' => $_SESSION['id']);
  $stmt = $db->prepare($sql);
  $stmt->execute($data);
}

if(isset($_POST['prId2'])) {
  $img_id = $_POST['imgId'];

  $sql = 'UPDATE members SET picture = :picture WHERE members . id = :id';
  $data = array(':picture' => 'NoImage', ':id' => $_SESSION['id'] );
  $stmt = $db->prepare($sql);
  $stmt->execute($data);
}


if(isset($_FILES['my_image']['name'])) {
  // $image = date('YmdHis') . $_FILES['my_image']['name'];
  // move_uploaded_file($_FILES['my_image']['tmp_name'], '../member_icon' . $image);
  //
  // var_dump($_FILES);
  // 一時アップロード先ファイルパス
$file_tmp  = $_FILES["my_image"]["tmp_name"];

// 正式保存先ファイルパス
$file_save = "../member_icon" . $_FILES["my_image"]["name"];

// ファイル移動
$result = @move_uploaded_file($file_tmp, $file_save);
if ( $result === true ) {
    echo "UPLOAD OK";
} else {
    echo "UPLOAD NG";
}
var_dump($_FILES);
}


// $image = date('YmdHis') . $_FILES['my_image']['name'];
// move_uploaded_file($_FILES['my_image']['tmp_name'], '../member_icon/' . $image);
//
// console_log ($_POST);


?>
