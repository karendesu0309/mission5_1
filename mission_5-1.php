<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charset ="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
    //データベース接続
     //データベース接続
     $dsn = 'データベース名';
     $user = 'ユーザー名';
     $password = 'パスワード';
     $pdo = new PDO($dsn, $user, $password, 
             array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));



    //データベースにテーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);


    


       

        //投稿 もし編集番号がなくてパスワードもない場合
        //（データベースへの書き込み）
        if(empty($_POST["editno"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["pass"])){


                $post_name=$_POST["name"];
                $post_com=$_POST["comment"];
                $post_date=date("Y-m-d H:i:s");

        
        $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, date) VALUES (:name, :comment, :date)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $name = $post_name;
        $comment = $post_com; //好きな名前、好きな言葉は自分で決めること
        $date = $post_date;
        $sql -> execute();
            

           
        //投稿 もし編集番号がなくてパスワードがある場合
        }  else if(empty($_POST["editno"]) && !empty($_POST["name"])  
        && !empty($_POST["comment"]) && !empty($_POST["pass"])){

            $post_name=$_POST["name"];
            $post_com=$_POST["comment"];
            $post_date=date("Y-m-d H:i:s");
            $post_pass = $_POST["pass"];

            $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, date, pass)
                                    VALUES (:name, :comment, :date, :pass)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	        $name = $post_name;
	        $comment = $post_com; 
	        $date = $post_date;
	        $pass = $post_pass;
	        $sql -> execute();
        }   
        


        //削除
        //もし削除フォームにデータがあれば
        if(!empty($_POST["deleteno"]) && !empty($_POST["pass2"])) {

            $delno=$_POST["deleteno"];
            $pass2=$_POST["pass2"];
            
            //入力したデータを抽出
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //rowの中にはカラムの名前が入る
                //もし削除番号とパスワードが一致したら
                if($delno == $row['id'] && $pass2 == $row['pass']){
                    //入力したデータを削除
                    $id = $delno;
                    $sql = 'delete from mission5_1 where id=:id' ; 
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }

 


        //編集    
        if(!empty($_POST["editnum"]) && !empty($_POST["pass3"])){
            $editnum =$_POST["editnum"];
            $pass3 = $_POST["pass3"];

            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();                             
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    $editid = $row['id'];
                    $editname = $row['name'];
                    $editcom = $row['comment'];
                    $editpass = $row['pass'];
                    }
        }
        



        if(!empty($_POST["editno"]) && !empty($_POST["name"])
            && !empty($_POST["comment"]) && !empty($_POST["pass"])){

            $edit_id=$_POST["editno"];   
            $edit_com=$_POST["comment"];
            $edit_name=$_POST["name"];
            $edit_pass=$_POST["pass"];


            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //rowの中にはカラムの名前が入る
                //もし編集番号とパスワードが一致したら
                if($edit_id == $row['id'] && $edit_pass == $row['pass']){
                    //データの編集
                    $id = $edit_id;
                    $name = $edit_name;
                    $comment = $edit_com; //変更したい名前、変更したいコメントは自分で決めること
                    $pass = $edit_pass;
                    $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment, pass=:pass WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }        
       }



                

        $sql = 'SELECT * FROM mission5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';


        echo "<hr>";
        }     
            
        ?>   

    <form action=""method="post">
        <h1>投稿</h1>

        <input type="hidden" name="editno" 
        value="<?php  
                if(!empty($_POST["editnum"])){
               echo $editid;
                }
                ?>">

        <input type="text" name="name" placeholder="名前" 
        value="<?php 
        if(!empty($_POST["editnum"])){
             echo $editname;
    
        }
                    ?>">

        <input type="text" name="comment" placeholder="コメント"
        value="<?php
         if(!empty($_POST["editnum"])){
              echo $editcom;
              } ?>">

        <input type="text" name="pass" placeholder="パスワード"
        value="<?php 
        if(!empty($_POST["editnum"])){
             echo $editpass;
             } ?>">
  

        <input type="submit" name="submit" value="送信">

        <br>
        
        <h1>削除</h1>
        <input type="number" name="deleteno" placeholder="削除対象番号">
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
        <br>
        
        <h1>編集</h1>
        <input type="number" name="editnum" placeholder="編集対象番号">
        <input type="text" name="pass3" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">

    </form>

</body>
</html>


