<?php
// エラー出力
  ini_set("display_errors", 1);
  error_reporting(E_ALL);

  require_once "./dbc.php";

  // ファイル関連の取得
  $file = $_FILES['img'];
  // 各変数に定義（わかりやすくするため）
  $filename = basename($file['name']);
  $tmp_path = $file['tmp_name'];
  $file_err = $file['error'];
  $filesize = $file['size'];
  $upload_dir = 'images/';
  $save_filename = date('YmdHis') . $filename;
  $err_msgs = array();
  $save_path = $upload_dir . $save_filename;

  // キャプションを取得
  $caption = filter_input(INPUT_POST,'caption',FILTER_SANITIZE_SPECIAL_CHARS);
  // INPUT_POSTでPOSTのデータかを確認
  // 'caption'で送られてきたのがPOSTなのかを確認
  // FILETER_SANITIZE_SPECIAL_CHARSで受け取ったデータをサニタイズする

  // キャプションのバリデーション
  // 未入力の場合
  if(empty($caption)){
    array_push($err_msgs, 'キャプションを入力してください');
  }

  // 140文字か
  if(strlen($caption) > 140){
    array_push($err_msgs, 'captionは140文字以内で入力してください');
  }

  // ファイルのバリデーション
  // ファイルサイズは１MB未満か
  if($filesize > 1048576|| $file_err == 2){
    array_push($err_msgs, 'ファイルサイズは１MB未満にしてください');
  }

  // 拡張子は画像形式か
  // $allow_ext = array('jpg','jpeg','png');
  // $file_ext = pathinfo($filename, PATHINFO_EXTENSHION);

  // if(!in_array(strtolower($file_ext),$allow_ext)){
  //   echo '画像ファイルを添付してください';
  // }

    // エラーがなければ
  if (count($err_msgs) === 0){
    // ファイルはあるかどうか?
    if(is_uploaded_file($tmp_path)){
      if(move_uploaded_file($tmp_path , $save_path)){
        echo $filename . 'を' . $upload_dir . 'にアップしました。';
        // DBに保存する処理（ファイル名、ファイルパス、キャプション）
        $result = fileSave($filename,$save_path,$caption);

        if($result){
          echo 'データベースに保存しました';
        }else{
          echo 'データベースに失敗しました';
        }

      }else{
          echo 'ファイルが保存できませんでした。';
      echo'<br>';
      }
    }else{
      echo'ファイルが選択されていません';
      echo'<br>';
    }
  } else {
    foreach($err_msgs as $msg){
      echo $msg . '<br>';
    }
  }
?>

<a href="./index.php">戻る</a>
