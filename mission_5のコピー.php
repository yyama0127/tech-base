<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>mission_5</title>
</head>
<body>
<?php
//データベースへの接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));	//array~のやつでエラーがあればそれを表示させる

//投稿機能
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["number"]) && !empty($_POST["password"])){
	$sql2 = $pdo -> prepare("INSERT INTO tb1(name, comment, post_date, password) VALUES(:name, :comment, :post_date, :password)");
	$name=$_POST["name"];
	$comment=$_POST["comment"];
	$post_date=date("Y-m-d H:i:s");
	$password=$_POST["password"];
	$sql2 -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql2 -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql2 -> bindParam(':post_date', $post_date, PDO::PARAM_STR);
	$sql2 -> bindParam(':password', $password, PDO::PARAM_STR);
	$sql2 -> execute();
}

//削除機能
if(!empty($_POST["id_delete"]) && !empty($_POST["password_delete"])){
	$password_delete = $_POST["password_delete"];
	$id_delete = $_POST["id_delete"];
	$sql3 = 'SELECT * FROM tb1';
	$stmt3 = $pdo -> query($sql3);
	$results3 = $stmt3 -> fetchall();
	foreach($results3 as $row3){
		if($row3['password'] == $password_delete){
			$sql4 = 'delete from tb1 where id = :id_delete';
			$stmt4 = $pdo -> prepare($sql4);
			$stmt4 -> bindParam(':id_delete', $id_delete, PDO::PARAM_INT);
			$stmt4 -> execute();
		}elseif($row3['password'] != $password_delete){
			echo "パスワードが違います";
		}
	}	
}

//編集選択
if(!empty($_POST["id_edit"]) && !empty($_POST["password_edit"])){
	$id_edit = $_POST["id_edit"];
	$password_edit1 = $_POST["password_edit"];
	$sql5 = 'SELECT * FROM tb1';
	$stmt5 = $pdo -> query($sql5);
	$results5 = $stmt5 -> fetchall();
	foreach($results5 as $row5){
		if($row5['id'] == $id_edit && $row5['password'] == $password_edit1){
			$edit_element_number=$row5['id'];
        	$edit_element_name=$row5['name'];
        	$edit_element_comment=$row5['comment'];
		}elseif($row5['password'] != $password_edit1){
			echo "パスワードが違います";
		}
	}
}

//編集実行
if(!empty($_POST["number"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
	$number_edit = $_POST["number"];
	$name_edit = $_POST["name"];
	$comment_edit = $_POST["comment"];
	$date_edit = date("Y-m-d H:i:s");
	$password_edit2 = $_POST["password"];
	$sql6 = 'update tb1 set name = :name_edit, comment = :comment_edit, post_date = :date_edit, password = :password_edit2 where id = :id';
	$stmt6 = $pdo -> prepare($sql6);
	$stmt6 -> bindParam(':name_edit', $name_edit, PDO::PARAM_STR);
	$stmt6 -> bindParam(':comment_edit', $comment_edit, PDO::PARAM_STR);
	$stmt6 -> bindParam(':date_edit', $date_edit, PDO::PARAM_STR);
	$stmt6 -> bindParam(':password_edit2', $password_edit2, PDO::PARAM_STR);
	$stmt6 -> bindParam(':id', $number_edit, PDO::PARAM_INT);
	$stmt6 -> execute();
}
?>

<form action="mission_5.php" method="post">
	<p>【投稿フォーム】<br>
	<input type="text" name="name" placeholder="名前" value="<?php if(!empty($_POST['id_edit']) && !empty($_POST['password_edit'])){
																													echo $edit_element_name;
																												}
																												?>" ><br>
	<input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($_POST['id_edit']) && !empty($_POST['password_edit'])){
																																echo $edit_element_comment;
																															}
																															?>" >
	<input type="hidden" name="number" value="<?php if(!empty($_POST['id_edit']) && !empty($_POST['password_edit'])){
																							echo $edit_element_number;
																						}
																						?>"><br>
	<input type="text" name="password" placeholder="パスワード"><br>
	<input type="submit" name="send"></p>

	<p>【 削除フォーム 】<br>
	<input type="text" name="id_delete" placeholder="削除対象番号"><br>
	<input type="text" name="password_delete" placeholder="パスワード"><br>
  	<input type="submit" name="send_delete" value="削除"></p>

	<p>【編集フォーム】<br>
	<input type="text" name="id_edit" placeholder="編集対象番号"><br>
	<input type="text" name="password_edit" placeholder="パスワード"><br>	
	<input type="submit" name="send_edit" value="編集"></p>
</form>

<?php
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//表示機能
$sql7 = 'SELECT * FROM tb1';
$stmt7 = $pdo -> query($sql7);
$results7 = $stmt7 -> fetchall();
foreach($results7 as $row7){
	echo $row7['id']." ".$row7['name']." ".$row7['comment']." ".$row7['post_date']."<br>";
}
?>
</body>
</html>