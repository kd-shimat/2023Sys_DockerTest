
<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\WebDriverBy;

class SampleTest extends TestCase
{
    protected $pdo; // PDOオブジェクト用のプロパティ(メンバ変数)の宣言
    protected $driver;

    public function setUp(): void
    {
        // PDOオブジェクトを生成し、データベースに接続
        $dsn = "mysql:host=db;dbname=minishop;charset=utf8";
        $user = "mini";
        $password = "shop";
        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (Exception $e) {
            echo 'Error:' . $e->getMessage();
            die();
        }

        #XAMPP環境で実施している場合、$dsn設定を変更する必要がある
        //ファイルパス
        $rdfile = __DIR__ . '/../src/classes/dbdata.php';
        $val = "host=db;";

        //ファイルの内容を全て文字列に読み込む
        $str = file_get_contents($rdfile);
        //検索文字列に一致したすべての文字列を置換する
        $str = str_replace("host=localhost;", $val, $str);
        //文字列をファイルに書き込む
        file_put_contents($rdfile, $str);

        // chrome ドライバーの起動
        $host = 'http://172.17.0.1:4444/wd/hub'; #Github Actions上で実行可能なHost
        // chrome ドライバーの起動
        $this->driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
    }

    public function testOrderNow()
    {
        // 指定URLへ遷移 (Google)
        $this->driver->get('http://php/src/index.php');

        // =========================================カート追加1回目==============================================
        // ラジオボタンをクリック
        $this->driver->findElement(WebDriverBy::xpath("//input[@type='radio' and @name='genre' and @value='pc']"))->click();

        // inputタグの要素を取得
        $element_input = $this->driver->findElements(WebDriverBy::tagName('input'));

        // 画面遷移実行
        $element_input[3]->submit();

        // ジャンル別商品一覧画面の詳細リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[0]->click();

        // 注文数を「2」にし、「カートに入れる」をクリック
        $selector = $this->driver->findElement(WebDriverBy::tagName('select'))
            ->findElement(WebDriverBy::cssSelector("option[value='1']"))
            ->click();

        // 画面遷移実行
        $selector->submit();

        // カート画面の「ジャンル選択に戻る」リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[0]->click();

        // =========================================カート追加2回目==============================================
        // ラジオボタンをクリック
        $this->driver->findElement(WebDriverBy::xpath("//input[@type='radio' and @name='genre' and @value='book']"))->click();

        // inputタグの要素を取得
        $element_input = $this->driver->findElements(WebDriverBy::tagName('input'));

        // 画面遷移実行
        $element_input[3]->submit();

        // ジャンル別商品一覧画面の詳細リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[0]->click();

        // 注文数を「2」にし、「カートに入れる」をクリック
        $selector = $this->driver->findElement(WebDriverBy::tagName('select'))
            ->findElement(WebDriverBy::cssSelector("option[value='2']"))
            ->click();

        // 画面遷移実行
        $selector->submit();

        // カート画面の「ジャンル選択に戻る」リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[0]->click();

        // =========================================カート追加3回目==============================================
        // ラジオボタンをクリック
        $this->driver->findElement(WebDriverBy::xpath("//input[@type='radio' and @name='genre' and @value='music']"))->click();

        // inputタグの要素を取得
        $element_input = $this->driver->findElements(WebDriverBy::tagName('input'));

        // 画面遷移実行
        $element_input[3]->submit();

        // ジャンル別商品一覧画面の詳細リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[0]->click();

        // 注文数を「2」にし、「カートに入れる」をクリック
        $selector = $this->driver->findElement(WebDriverBy::tagName('select'))
            ->findElement(WebDriverBy::cssSelector("option[value='3']"))
            ->click();

        // 画面遷移実行
        $selector->submit();
        // =========================================ここからは注文するリンクをクリック==============================================

        // ジャンル別商品一覧画面の詳細リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[1]->click();

        //データベースの値を取得
        $sql = 'select * from orderdetails order by itemId asc';       // SQL文の定義
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([]);
        $orderdetails = $stmt->fetchAll();
        $cnt = 0;
        $itemId = array(1, 6, 11);
        foreach ($orderdetails as $orderdetail) {
            $this->assertEquals($itemId[$cnt], $orderdetail['itemId'], '注文処理に誤りがあります。');
            $cnt++;
        }

        //cartテーブルが消えているか確認
        $sql = 'select * from cart';       // SQL文の定義
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([]);
        $count = $stmt->rowCount();    // レコード数の取得
        $this->assertEquals(0, $count, 'カート削除処理に誤りがあります。');
    }
}
