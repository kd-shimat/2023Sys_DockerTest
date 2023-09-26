
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
        $dsn = "mysql:host=db;dbname=shop;charset=utf8";
        $user = "shopping";
        $password = "site";
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

        // =========================================ログイン==============================================
        // ログインリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[3]->click();

        // ログイン処理を実施
        $element_input = $this->driver->findElements(WebDriverBy::tagName('input'));
        $element_input[0]->sendKeys("kobe@denshi.net");
        $element_input[1]->sendKeys("kobedenshi");
        $element_input[2]->submit();

        // =========================================カート追加1回目==============================================
        // トップページリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[1]->click();

        // トップページ画面のpcリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[5]->click();

        // ジャンル別商品一覧画面の詳細リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[5]->click();

        // 商品詳細画面の注文数を「2」にし、「カートに入れる」をクリック
        $selector = $this->driver->findElement(WebDriverBy::tagName('select'));
        $selector->click();
        $this->driver->getKeyboard()->sendKeys("2");
        $selector->click();
        $selector->submit();

        // =========================================カート追加2回目==============================================
        // トップページリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[1]->click();

        // トップページ画面のbookリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[6]->click();

        // ジャンル別商品一覧画面の詳細リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[5]->click();

        // 商品詳細画面の注文数を「2」にし、「カートに入れる」をクリック
        $selector = $this->driver->findElement(WebDriverBy::tagName('select'));
        $selector->click();
        $this->driver->getKeyboard()->sendKeys("3");
        $selector->click();
        $selector->submit();

        // =========================================カート追加3回目==============================================
        // トップページリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[1]->click();

        // トップページ画面のbookリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[7]->click();

        // ジャンル別商品一覧画面の詳細リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[5]->click();

        // 商品詳細画面の注文数を「2」にし、「カートに入れる」をクリック
        $selector = $this->driver->findElement(WebDriverBy::tagName('select'));
        $selector->click();
        $this->driver->getKeyboard()->sendKeys("4");
        $selector->click();
        $selector->submit();
        // =========================================注文==============================================

        // カート画面の注文リンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[5]->click();

        // 注文確認画面の注文を確定するリンクをクリック
        $element_a = $this->driver->findElements(WebDriverBy::tagName('a'));
        $element_a[5]->click();


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
