<html lang="ja">
    <body>
    <form action=""method="post">
            【投稿フォーム】<br>
            <input type="text" name="name" placeholder="名前を入力">
            <br>
            <input type="text" name="comment" placeholder="コメントを入力">
            <br>
            <input type="password" name="pass" placeholder="パスワードを入力">
            <input type="submit" name="submit">
            <br>
            <br>
            【削除フォーム】<br>
            <input type="number" name="delnum" placeholder="削除対象番号を入力">
            <br>
            <input type="password" name="dpass" placeholder="パスワードを入力">
            <input type="submit" name="submit" value="削除">
            <br>
            <br>
            【編集フォーム】<br>
            <input type="number" name="edinum" placeholder="編集対象番号を入力">
            <br>
            <input type="password" name="epass" placeholder="パスワードを入力">
            <input type="submit" name="submit" value="編集">
     </form>
     
    <?php
    //DB接続
    $dsn='mysql:dbname=データベース名;host=localhost';
    $user='ユーザ名';
    $password='パスワード';
    //PDOクラス
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

    $sql="CREATE TABLE IF NOT EXISTS mission5" //テーブル作成
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"  //投稿番号
    ."name char(32),"  //名前
    ."comment TEXT,"  //コメント
    ."date char(32),"  //日付
    ."password char(32)"  //パスワード
    .");";
    $stmt = $pdo->query($sql);
    
    //新規
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && empty($_POST["delnum"]) && empty($_POST["edinum"])){
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["pass"];
        
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
        //実行
        $sql -> execute();
    }
    
    //削除
    if(!empty($_POST["delnum"]) && !empty($_POST["dpass"])){
        $delnum=$_POST["delnum"];
        $dpass=$_POST["dpass"];
        //テーブル上すべて選択
        $sql='SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        //繰り返し処理で投稿番号と削除対象番号比較
        foreach($results as $row){
            if($delnum == $row["id"] && $dpass==$row["password"]){
                 $sql = 'delete from mission5 where id=:id';
                 $stmt = $pdo->prepare($sql);
                 $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);
                 $stmt->execute();
            }
        }
    }
    
    //編集
    if(!empty($_POST["edinum"]) && !empty($_POST["epass"]) && !empty($_POST["name"]) && !empty($_POST["comment"])){
        $edinum=$_POST["edinum"];
        $epass=$_POST["epass"];
        $ediname=$_POST["name"];
        $edicomment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        
        //テーブル上すべて選択
        $sql='SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        //繰り返し処理で投稿番号と編集対象番号比較
        foreach($results as $row){
            //編集対象番号と投稿番号、およびパスワードが一致したとき編集
            if($edinum == $row["id"] && $epass == $row["password"]){
            $sql = 'UPDATE mission5 SET name=:name,comment=:comment, date=:date, password=:password WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':name', $ediname, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $edicomment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $edinum, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $epass, PDO::PARAM_STR);
            //実行
            $stmt->execute();    
            }
        }
    }
    
    //表示
    $sql='SELECT * FROM mission5';
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    foreach($results as $row){
        echo $row["id"].",";
        echo $row["name"].",";
        echo $row["comment"].",";
        echo $row["date"]."<hr>";
    }
    ?>
    </body>
</html>