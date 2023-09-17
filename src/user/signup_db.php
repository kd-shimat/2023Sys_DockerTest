<?php																		
  // 送られてきたデータを受けとる
  $kubun    = $_POST['kubun'];																		
  $userId   = $_POST['userId'];
  $userName = $_POST['userName'];
  $kana     = $_POST['kana'];
  $zip      = $_POST['zip'];
  $address  = $_POST['address'];
  $tel      = $_POST['tel'];
  $password = $_POST['password'];																	
																		
  session_start( );																		
																		
  // バリデーションはメールアドレスと郵便番号のみとする																		
  // メールアドレスのバリデーションはfilter_var() を使い、RFCに準拠しないメルアドはエラーとする																		
  if(!filter_var($userId, FILTER_VALIDATE_EMAIL)){																		
    $_SESSION['signup_error'] = '正しいメールアドレスを入力してください。';
    header('Location: ./signup.php');
    exit();																	
  }  																		
																		
  // 郵便番号は半角整数の7桁かどうかだけチェックする																		
  if(!is_numeric($zip) || strlen($zip) !== 7 ){																		
    $_SESSION['signup_error'] = '正しい郵便番号を入力してください。';	
    header('Location: ./signup.php');	
    exit();    	   																		
  } 																		
																		
  // Userオブジェクトを生成し、 ユーザー登録処理を行うsignUp( )メソッドを呼び出し、その結果のメッセージを受け取る																		
  require_once  __DIR__  .  '/../classes/user.php';			
  $user = new User( );
  // $kubunの値が「insert」ならば「登録」、「update」ならば「更新」処理を行う
  if($kubun === 'insert'){		
    $result = $user->signUp($userId, $userName, $kana, $zip, $address, $tel, $password, $_SESSION['userId']);
  }else{
    $result = $user->updateUser($userId, $userName, $kana, $zip, $address, $tel, $password, $_SESSION['userId']);
  }
  
  // 登録に失敗した場合、エラーメッセージをセッションに保存し、ユーザー登録画面(signup.php)に遷移する																		
  if($result !== '') {								// 「''」は「空文字」のチェックなので、シングルクォーテーションが２つ										
    $_SESSION['signup_error']  = $result;   
    header('Location: ./signup.php');															// signup.phpへ遷移する			
    exit();																		
  }																		
																		
  // ユーザー情報をセッションに保持する																		
  $_SESSION['userId']   = $userId;		
  $_SESSION['userName'] = $userName;		
  $_SESSION['kana']     = $kana;		
  $_SESSION['zip']      = $zip;		
  $_SESSION['address']  = $address;		
  $_SESSION['tel']      = $tel;																				
																		
  // ユーザーIDと名前をクッキーに保存する　・・　有効期限を2週間に設定(time() + 60 * 60 * 24 * 14)																		
  setcookie("userId", $userId, time() + 60 * 60 * 24 * 14 , '/');
  setcookie("userName", $userName, time() + 60 * 60 * 24 * 14, '/');																	
																		
  require_once  __DIR__  .  '/../util.php';															// util.phpを読み込む			
  require_once  __DIR__  .  '/../header.php';															// header.phpを読み込む			
?>																		
ユーザー情報を登録・更新しました。<br>																		
<table>																		
  <tr><td>Eメール</td><td><?= h($userId) ?></td></tr>	
  <tr><td>名前</td><td><?= h($userName) ?></td></tr>	
  <tr><td>フリガナ</td><td><?= h($kana) ?></td></tr>	
  <tr><td>郵便番号</td><td><?= mb_substr($zip, 0, 3) ?>-<?= mb_substr($zip, 3) ?></td></tr>	
  <tr><td>住所</td><td><?= h($address) ?></td></tr>	
  <tr><td>電話番号</td><td><?= h($tel) ?></td></tr>																			
</table>																		
<?php																		
  require_once  __DIR__  .  '/../footer.php';  																		
?>																		