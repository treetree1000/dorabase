<!DOCTYPE html>

<?php
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo= new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS tbtest5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "dt DATETIME,"
	. "password varchar(255) NOT NULL"
	.");";
$stmt = $pdo->query($sql);

//変数の初期化
if(isset($_POST["name"])){ $name = $_POST["name"]; }
if(isset($_POST["delnum"])){ $delid = $_POST["delnum"]; }
if(isset($_POST["editnum"])){ $editid = $_POST["editnum"]; }
if(isset($_POST["editnum2"])){ $editid2 = $_POST["editnum2"]; }
if(isset($_POST["comment"])){ $comment = $_POST["comment"]; }
if(isset($_POST["pass1"])){ $pass1 = $_POST["pass1"]; }
if(isset($_POST["pass2"])){ $pass2 = $_POST["pass2"]; }
if(isset($_POST["pass3"])){ $pass3 = $_POST["pass3"]; }
$date=date('Y-m-d H:i:s');

//編集ボタン
if(isset($editid) && isset($_POST["submit3"])){
    $sql = "SELECT password FROM tbtest5 WHERE id = $editid";
    $stmt = $pdo -> query($sql);
    $results = $stmt -> fetchAll();
    foreach($results as $row){
    }
    if($pass3==$row['password']){
        $sql = "SELECT id,name,comment FROM tbtest5 WHERE id = $editid";
        $stmt = $pdo -> query($sql);
        $results = $stmt -> fetchAll();
        foreach($results as $row){
        $num2=$row['id'];
        $name2=$row['name'];
        $com2=$row['comment'];
        }
    }
}

if(isset($_POST["submit1"])){
    if(empty($editid2)){
        if(isset($pass1)){
        $sql = $pdo -> prepare("INSERT INTO tbtest5 (name, password, comment, dt)
                                VALUES (:name, :password, :comment, :dt)");

        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':password', $pass1, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':dt', $date, PDO::PARAM_STR);
        
        $sql -> execute();
        }
    }
    elseif(!empty($editid2)){
        $sql = "UPDATE tbtest5 SET name=:name, comment=:comment, dt=:dt, password=:password WHERE id=$editid2";

        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt -> bindParam(':dt', $date, PDO::PARAM_STR);
        $stmt -> bindParam(':password', $pass1, PDO::PARAM_STR);
        $stmt -> execute();
        
    }
}

//データの削除
if(isset($_POST["submit2"])){
    $sql = "SELECT password FROM tbtest5 WHERE id=$delid";
    $stmt = $pdo -> query($sql);//実行後にSQLの実行結果に関する情報を得る
    $results = $stmt -> fetchAll();
    
    foreach($results as $row){
    }
    
    if($pass2==$row['password']){
        $sql = 'DELETE FROM tbtest5 WHERE id=:id';
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':id', $delid, PDO::PARAM_INT);
        $stmt -> execute();
    }
    else{
        echo "パスワードが違います。";
    }
}

?>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Mission_5-1</title>
    </head>
    <body>
        <form method="POST" action="">
            お名前：<input type="text" name="name" 
            value="<?php if(isset($_POST["submit3"])&& isset($name2)){ echo $name2; }?>" required><br>
            
            コメント：<input type="text" name="comment"
            value="<?php if(isset($_POST["submit3"]) && isset($com2)){ echo $com2; }?>" required><br>
            
            パスワード：<input type="text" name="pass1" required><br>
            
            <input type="hidden" name="editnum2"
            value="<?php if(isset($_POST["submit3"]) && isset($num2)){ echo $num2; }?>" ><br>
            
            <input type="submit" name="submit1" value="送信"><br><br>
        </form>
        <form action="" method="POST">
            削除対象番号：<input type="number" name="delnum" required><br>
            パスワード：<input type="text" name="pass2" required><br>
            
            <input type="submit" name="submit2" value="削除"><br><br>
        </form>
        <form action="" method="post">
            編集対象番号：<input type="number" name="editnum" required><br>
            パスワード：<input type="text" name="pass3" required><br>
            
            <input type="submit" name="submit3" value="編集"><br><br>
        </form>
    </body>
</html>

<?php
//データの表示
$sql = 'SELECT * FROM tbtest5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){//$rowの中にはテーブルのカラム名が入る
    echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['dt'].'<br>';
	echo $row['password'].',';
	echo "<hr>";
}
?>