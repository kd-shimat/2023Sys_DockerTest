<?php													
  // 送られてきたユーザーIDとパスワードを受け取る													
  $userId   = $_POST['userId'];													
  $password = $_POST['password'];													
													
  // Userオブジェクトを生成し、「authUser()メソッド」を呼び出し、認証結果を受け取る													
  require_once __DIR__  .  '/../classes/user.php' ;				// user.phpを読み込む		
  $user = new User( );											// UserクラスからUserオブジェクトを生成する		
  $result = $user->authUser($userId, $password);											// authUser()メソッドを呼び出し、認証結果を受け取る		
													
  session_start();													
  // ログインに失敗した場合、エラーメッセージをセッションに保存し、ログイン画面(login.php)に遷移する													
  if(empty($result['userId'])) {											// 認証に成功しているとユーザーIDの値が格納されている		
    $_SESSION['login_error'] = 'ユーザーID、パスワードを確認してください。';													
    header('Location:./login.php');											// ログイン画面(login.php)に遷移する		
    exit( );													
  }													
													
  // データベースから名前を取り出す													
  $userName = $result['userName'];
  
  // cartテーブルに仮のuserIdで保存された商品があれば正式なログインユーザーのuserIdに変更する														
  $user->changeCartUserId($_SESSION['userId'],$userId);		// changeCartUserId( )メソッドを呼び出す		
													
  // ユーザー情報をセッションに保持する　・・　今回は個別に保持する方法をとる													
  $_SESSION['userId']   = $userId;										// 送られてきたデータ			
  $_SESSION['userName'] = $userName;									// データベースから取り出した名前			
  $_SESSION['kana']     = $result['kana'];									// $reslutから取り出す			
  $_SESSION['zip']      = $result['zip'];								// $reslutから取り出す			
  $_SESSION['address']  = $result['address'];								// $reslutから取り出す			
  $_SESSION['tel']      = $result['tel'];										// $reslutから取り出す			
													
  // ユーザーIDと名前をクッキーに保存する　・・　有効期限を2週間(= 60 * 60 * 24 * 14 秒)に設定													
  setcookie("userId", $userId, time( ) + 60 * 60 * 24 * 14, '/');													
  setcookie("userName", $userName, time() + 60 * 60 * 24 * 14, '/');											
													
  require_once  __DIR__  .  '/../header.php';											// header.phpを読み込む		
  require_once  __DIR__  .  '/../util.php';											// util.phpを読み込む		
?>													
<p>こんにちは、<?=h($userName) ?>さん。</p>									<!-- エスケープ処理をした上で、ユーザー名を表示する	-->	
<p>ショッピングをお楽しみください。</p>													
<?php													
  require_once  __DIR__  .  '/../footer.php';  													
?>													