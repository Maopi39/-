<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1><b><u> &lt; 簡易掲示板 &gt; </b></u></h1>
    お好きに投稿くださいませ。
    <hr>
    
    
    
<!-- #-------コメント送信処理コード-------# -->
    
    <?php
    
    // #---DB接続設定---# //
    
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザ名';
    $password = 'パスワード'; 
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS TBboard"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "datetime DATETIME,"
    . "password VARCHAR(255)"
    .");";
    $stmt = $pdo->query($sql);

//初期値の設定
    
        if(empty($nam_new)){
            $nam_new = "";
        }
        if(empty($com_new)){
            $com_new = "";
        }
        if(empty($edin)){
            $edin = "";
        }
        
    
    
  // #---コメント送信処理コード---# //
  
//新規投稿 

    
    if( !empty($_POST["name"]) && !empty($_POST["com"])){
        if(empty($_POST["edic"])) {//この分岐がうまくいかない
        $name = $_POST["name"];
        $com = $_POST["com"];
        $pas = $_POST["pas"];
        if(empty($pas)){
        $pas = "pass";
        }
        $date = date("Y/m/d  H:i:s");
            
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 
                
        $sql = "INSERT INTO TBboard (name, comment, datetime , password) VALUES (:name, :comment, :datetime ,:password)";
        $stmt = $pdo->prepare($sql); 
        $stmt->bindParam(':name', $name, PDO::PARAM_STR); 
        $stmt->bindParam(':comment', $com, PDO::PARAM_STR); 
        $stmt->bindParam(':datetime', $date, PDO::PARAM_STR); 
        $stmt->bindParam(':password', $pas, PDO::PARAM_STR); 
        $stmt->execute(); 
        echo "新規投稿完了です！<br>";
        
    }else{
//編集投稿
        
        $ediK = $_POST["edic"];
        
        $name2 = $_POST["name"];
        $com2 = $_POST["com"];
        $id = $ediK;
        $pas4 = $_POST["pas"];
        if(empty($pas4)){
        $pas4 = "pass";
        }
        $date = date("Y/m/d  H:i:s");
        
    $sql = 'UPDATE TBboard SET name=:name,comment=:comment,datetime=:datetime,password=:password WHERE id=:id ' ;
        $stmt = $pdo->prepare($sql); 
        $stmt->bindParam(':name', $name2, PDO::PARAM_STR); 
        $stmt->bindParam(':comment', $com2, PDO::PARAM_STR); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->bindParam(':datetime',$date, PDO::PARAM_STR);
        $stmt->bindParam(':password', $pas4, PDO::PARAM_INT); 
        $stmt->execute(); 
        
        echo "編集完了です！";
     }
    
    }

    
    
    
    
    
    
    // #---コメント削除処理コード---# //
    
        if(!empty($_POST["deln"]) && !empty($_POST["pas2"])){
            $id = $_POST["deln"];
            $pas2 = $_POST["pas2"];
            
            $sql = 'DELETE from TBboard where id=:id '; 
            $stmt = $pdo->prepare($sql); 
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 

            $stmt->execute(); 
     } 
            
            
        
     // #---コメント編集処理コード---# //
     
        if(!empty($_POST["edit"]) && !empty($_POST["edin"])&& !empty($_POST["pas3"])){
            $edin= $_POST["edin"];
            $pas3 = $_POST["pas3"];
            $sql = 'SELECT*from TBboard WHERE id=:id AND password=:password'; 
            $stmt = $pdo->prepare($sql); 
            $stmt->bindParam(':id',$edin,PDO::PARAM_INT); 
            $stmt->bindParam(':password',$pas3,PDO::PARAM_INT); 
            $stmt->execute();        
            
            $results = $stmt->fetchAll();  
            foreach ($results as $row){ 
            $nam_new = $row['name']; 
            $com_new = $row['comment']; 
            echo "編集中...";
            } 
        
        
        }   
    
        
      ?> 
    
    <!-- #-------コメントフォーム一覧-------# -->
    
    <h2><u>&lt; コメント送信欄 &gt;</u></h2>
    
    <form action="" method="post">
        <input type="text" name="name" value="<?php echo $nam_new; ?>" placeholder="名前" size=10>
        <input type="text" name="pas"  placeholder="パスワード" size=10>
        <input type="submit" name="submit">
        <?php echo  "<br>"; ?>
        <input type="text" name="com" value="<?php echo $com_new; ?>" placeholder="コメント" size=30>

   
    
    <hr style="border-top: 2px dashed black;">
    

    
    <h3><u>&lt; コメント削除欄 &gt;      /              &lt; コメント編集欄 &gt;  </h3></u>
    
    
        <input type="text" name="deln" value="" placeholder="削除対象番号">
        <?php echo  "/"; ?>
        <input type="text" name="edin" value="" placeholder="編集対象番号">
        <br>
        <input type="text" name="pas2" value="" placeholder="パスワード" size=12>
        <input type="submit" name="delete" value ="削除">
        <?php echo  "/"; ?>
        <input type="text" name="pas3" value="" placeholder="パスワード" size=12>
        <input type="submit" name="edit" value ="編集">
        <input type ="text" name="edic" value ="<?php echo$edin?>">
        <?php echo  "<br>"; ?>
        
        </form>
        <i>(未設定時パス:"pass")</i>
        
        
        
        
    
    
    <!-- #-------コメント表示コード-------# -->
    
             
         
    <hr>
    
    

<h2><u>< コメント表示欄 ></u> </h2>
    
    <?php
        $sql = 'SELECT * FROM TBboard'; 
        $stmt = $pdo->query($sql); 
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){ 
          echo $row['id'];
          echo " 名前：".'<span style="color:blue;">'.$row['name']."</span>";
          echo " ".$row['datetime']."<br>"; 
          echo "< ".$row['comment'].'<br>'; 
          echo "<br>"; 
    } 
     
    
    
    ?>
    
     <hr>
</body>
</html>