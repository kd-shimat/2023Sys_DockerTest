<?php															
  // スーパークラスであるDbDataを利用するため															
  require_once __DIR__ . '/dbdata.php';															
															
  class User extends DbData{															
    // ログイン認証処理															
    public function authUser($userId, $password){															
      $sql = "select * from users where userId = ? and password = ?";													// SQL文を定義		
      $stmt= $this->query($sql, [$userId, $password]);													// DbDataクラスのquery( )メソッドを呼び出す		
      return $stmt->fetch();													// fetch( )メソッドでデータを取り出す		
    }
    
    // ゲストからログインした場合、カート内に商品が入っていれば、ログイン後のユーザーID(メールアドレス)に変更する	
    public function changeCartUserId($tempId, $userId){	
      // Cartオブジェクトを生成し、カート内の商品のユーザーIDを変更する	
      require_once __DIR__ . '/cart.php';
      $cart =	new Cart();
      $cart->changeUserId($tempId,$userId);
    }
    
    // ユーザー登録処理																	
    public function signUp($userId, $userName, $kana, $zip, $address, $tel, $password, $tempId){																	
      $sql = "select * from users where userId = ?";			// userIdを条件とするSELECT文の定義					
      $stmt= $this->query($sql, [$userId]);								// dbdata.phpのquery()メソッドの実行					
      $result = $stmt->fetch();												// 抽出したデータを取り出す					
      // 登録しようとしているユーザーID（Eメール）が既に登録されている場合																	
      if( $result ){																	
        return 'この' . $userId . ' は既に登録されています。';																	
      }																	
      $sql = "insert into users(userId, userName, kana, zip, address, tel, password) values(?, ?, ?, ?, ?, ?, ?)";
      $result = $this->exec($sql, [$userId, $userName, $kana, $zip, $address, $tel, $password]);																	
                                  
      if($result){																	
        // 登録に成功した場合、cart内に保存されている商品があれば登録したユーザーIDに変更する（ログイン時と同じ処理）																	
        $this->changeCartUserId($tempId,  $userId);																	
        return '';							// ここも空文字を返すので「''」はシングルクォーテーションが２つ										
      } else {																	
        // 何らかの原因で失敗した場合																	
        return '新規登録できませんでした。管理者にお問い合わせください。';																	
      }																	
    }
    
    // ユーザー情報更新処理
    public function updateUser($userId, $userName, $kana, $zip, $address, $tel, $password, $tempId){
      $sql = "update users set userId=?, userName=?, kana=?, zip=?, address=?, tel=?, password=? where userId = ?";
      $result = $this->exec($sql, [$userId, $userName, $kana, $zip, $address, $tel, $password, $tempId]);

      if( $result ){
        // 更新に成功したが、Cart内に仮のユーザーIDの商品が入っていた場合、新しいユーザーIDに置き換える
        // また、過去の注文履歴のユーザーIDも新しいユーザーIDに置き換える
        if($userId !== $tempId){
          $this->changeCartUserId($tempId, $userId);
          $this->changeOrderHistoryUserId($tempId, $userId);
        }
        return '';
      } else {
        return 'ユーザー情報の更新ができませんでした。管理者にお問い合わせください。';
      }
    }

  // ユーザーID（Eメール)を変更した場合、過去の注文履歴のユーザーID（Eメール）を新しいユーザーIDに変更する
  public function changeOrderHistoryUserId($tempId, $userId){
    // Orderオブジェクトを生成し、注文履歴のユーザーID（Eメール）を変更する
    require_once  __DIR__  .  '/order.php';
    $order = new Order( );
    $order->changeUserId($tempId, $userId);
  }  
  }															