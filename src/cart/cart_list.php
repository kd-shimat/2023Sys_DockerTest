<?php
require_once  __DIR__  .  '/../header.php';                    // ヘッダー部（header.php）を読み込んでいる

if (!isset($cart)) {                    // $cartに必ずCartオブジェクトをセットするため
  require_once  __DIR__  .  '/../classes/cart.php';            // cart.phpを読み込む
  $cart = new Cart;                    // Cartオブジェクトを生成して$cartにセットする
}
// カート内のすべてのデータを取り出す
$cartItems = $cart->getItems($_SESSION['userId']);
if (empty($cartItems)) {
  echo '<h4>お客様のショッピングカートに商品はございません。</h4>';
  //echo '<a href="../index.php">ジャンル選択に戻る</a>';
} else {
?>
  <table>
    <tr>
      <th>&nbsp;</th>
      <th>商品名</th>
      <th>メーカー・著者<br>アーティスト</th>
      <th>価格</th>
      <th>注文数</th>
      <th>金額</th>
      <th>削除</th>
    </tr>
    <?php
    $total = 0;
    foreach ($cartItems as $item) {
      $total += $item['price'] * $item['quantity'];
    ?>
      <tr>
        <td class="td_mini_img"><img class="mini_img" src="../images/<?= $item['image'] ?>"></td>
        <td class="td_item_name"><?= $item['name'] ?></td>
        <td class="td_item_maker"><?= $item['maker'] ?></td>
        <td class="td_right">&yen;<?= number_format($item['price']) ?></td>
        <td>
          <form method="POST" action="cart_change.php">
            <select name="quantity">
              <?php
              for ($i = 1; $i <= 10; $i++) {
                echo '<option value="' . $i . '"';
                if ($i == $item['quantity']) {
                  echo ' selected>';
                } else {
                  echo '>';
                }
                echo $i . '</option>';
              }
              ?>
            </select>
            <input type="hidden" name="ident" value="<?= $item['ident'] ?>">&nbsp;
            <input type="submit" value="変更">
          </form>
        </td>
        <td class="td_right">&yen;<?= number_format($item['price'] * $item['quantity']) ?></td>
        <td>
          <form method="POST" action="cart_delete.php">
            <input type="hidden" name="ident" value="<?= $item['ident'] ?>">
            <input type="submit" value="削除">
          </form>
        </td>
      </tr>
    <?php
    }
    ?>
    <tr>
      <th colspan="5">合計金額</th>
      <td class="td_right">&yen;<?= number_format($total) ?></td>
      <td>
        &nbsp;
      </td>
    </tr>
  </table>
  <br>
  <a href="../order/order_confirm.php"><span class="button_image2">注文する</span></a>
<?php
}
?>
<?php
require_once  __DIR__ . '/../footer.php';
?>