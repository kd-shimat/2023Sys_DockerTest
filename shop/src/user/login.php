<?php															
  require_once  __DIR__  .  '/../header.php';														// header.phpの読み込み	
															
  if(isset($_SESSION['login_error'] )){														// ログイン時のエラーメッセージがあれば	
    echo '<p class="error_class">' . $_SESSION['login_error'] . '</p>';														// そのエラーメッセージを表示し、	
    unset($_SESSION['login_error']);														// セッション情報から削除する	
  } else {															
    echo '<p>利用するにあたってはログインしてください。</p>';															
  }															
?>															
<form method="POST" action="./login_db.php">															
  <table>															
    <tr><td>Eメール</td><td><input type="text" name="userId"  required></td></tr>															
    <tr><td>パスワード</td><td><input type="password" name="password" required></td></tr>															
    <tr><td colspan="2"><input type="submit" value="ログイン"></td></tr>															
    </table>															
</form>															
<a href="./signup.php"><span class="button_image">新規登録はこちらから</span></a>															
<?php															
  require_once  __DIR__  .  '/../footer.php';													// footer.phpの読み込み	
?>															