<?php																
  require_once  __DIR__  .  '/header.php';										// header.phpを読み込む						
  // コンテンツ部で各ジャンルの画像ファイルをランダムに表示するため、1から5までの乱数を用意する																
  $pc_num = mt_rand(1, 5);																
  $book_num = mt_rand(1, 5);																
  $music_num = mt_rand(1, 5);																
?>																
<p>お好みのジャンルを選択してください。</p>																
<div class="topnavi">						<!-- ３つのジャンルをまとめるための<div>要素 -->										
  <div class="topbox">							<!-- ジャンルPC用の<div>要素 -->									
    <p class="topvalue">PC</p>																
    <hr>																
    <a href="product/product_select.php?genre=pc">																
      <img class="topimage" src="images/pc00<?= $pc_num ?>.jpg">		
    </a>																
  </div>
  <div class="topbox">							<!-- ジャンルBOOK用の<div>要素 -->								
    <p class="topvalue">BOOK</p>															
    <hr>															
    <a href="product/product_select.php?genre=book">															
      <img class="topimage"  src="images/book00<?= $book_num  ?>.jpg">															
    </a>															
  </div>															
  <div class="topbox">							<!-- ジャンルMUSIC用の<div>要素 -->								
    <p class="topvalue">MUSIC</p>															
    <hr>															
    <a href="product/product_select.php?genre=music">															
      <img class="topimage"  src="images/music00<?=$music_num ?>.jpg">															
    </a>															
  </div>
  </div>											
<br><br>											
<?php											
  require_once  __DIR__ . '/footer.php';  										// footer.phpを読み込む	
?>																																										