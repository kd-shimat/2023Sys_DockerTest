<?php															
  require_once  __DIR__  .  '/../header.php';															
  // セッションに保存されている情報（ユーザーID、名前、フリガナ、郵便番号等）を空にし、															
  // クッキーに保存されているセッションID（PHPSESID）も無効にし、セッションを破棄する															
  $_SESSION = [ ];															
  if (isset($_COOKIE[session_name()])) {												// session_name( )はセッションID名を返す関数			
    setcookie(session_name(), '', time() - 1000, '/');												// '' は「シングルクォーテーション２つ」			
  }															
  session_destroy( );															
															
  // ユーザーIDと名前のクッキー情報も破棄する															
  setcookie('userId', '', time()-1000, '/');												// '' は「シングルクォーテーション２つ」			
  setcookie('userName', '', time()-1000, '/');												// '' は「シングルクォーテーション２つ」			
															
  // ジャンル選択画面（トップページ）に遷移する															
  header( "Location: " . $index_php ) ;															