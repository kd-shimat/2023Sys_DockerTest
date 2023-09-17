<?php																		
  require_once  __DIR__  .  '/../header.php';														// header.phpを読み込む		
  require_once  __DIR__  .  '/../util.php';														// util.phpを読み込む		 																		
  if(isset($_SESSION['signup_error'])){														// セッション変数signup_errorに値があれば				
    echo '<p class="error_class">' . $_SESSION['signup_error'] . '</p>';					// その内容を画面に表示し				
    unset($_SESSION['signup_error']);														// セッション変数signup_errorを削除				
  }	
  
    // ログイン済みの場合、セッションにユーザー情報が保持されているので、その情報を取り出す  ・・画面に表示するため
  $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';
  $userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';
  $kana = isset($_SESSION['kana']) ? $_SESSION['kana'] : '';
  $zip = isset($_SESSION['zip']) ? $_SESSION['zip'] : '';
  $address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
  $tel = isset($_SESSION['tel']) ? $_SESSION['tel'] : '';
  
    // ゲストがこの画面にアクセス ・・ ユーザー登録処理を行う（データベース処理はinsert）
    // ログイン済みのユーザーがこの画面にアクセス ・・ ユーザー情報確認変更処理を行う（データベース処理はupdate）
    if($userName === "ゲスト"){
      $kubun = "insert";
      $title = "ユーザー情報を登録してください。";
      $userId = '';
      $userName = '';
    } else {
      $kubun = "update";
      $title = "ユーザー情報を確認・変更することができます。";
    }
?>																		
<p><?= $title ?></p>																
<form method="POST" action="./signup_db.php">																		
  <table>																		
    <tr><td>Eメール</td><td><input type="text" name="userId" value="<?= h($userId) ?>" required></td></tr>
    <tr><td>名前</td><td><input type="text" name="userName" value="<?= h($userName) ?>" required></td></tr>
    <tr><td>フリガナ</td><td><input type="text" name="kana" value="<?= h($kana) ?>" required></td></tr>
    <tr><td>郵便番号</td><td><input type="text" name="zip" value="<?= h($zip) ?>" required></td></tr>
    <tr><td>住所</td><td><input type="text" name="address" value="<?= h($address) ?>" required></td></tr>
    <tr><td>電話番号</td><td><input type="text" name="tel" value="<?= h($tel) ?>" required></td></tr>																																		
    <tr><td>パスワード</td><td><input type="password" name="password" required></td></tr>																		
    <tr><td colspan="2"><input type="submit" value="送信"></td></tr>																		
  </table>
  <input type="hidden" name="kubun" value="<?= $kubun ?>">																	
</form>																		
<?php																		
  require_once  __DIR__  .  '/../footer.php';  																		
?>																		