<?php 
session_start();
require 'config.php';

if(isset($_POST['agencia']) && !empty($_POST['agencia'])) {
	$agencia = addslashes($_POST['agencia']);
	$conta = addslashes($_POST['conta']);
	$senha = md5(addslashes($_POST['senha']));

	$sql = $pdo->prepare("SELECT * FROM contas WHERE agencia = :agencia AND conta = :conta AND senha = :senha");
	$sql->bindValue(':agencia', $agencia);
	$sql->bindValue(':conta', $conta);
	$sql->bindValue(':senha', $senha);
	$sql->execute();

	if($sql->rowCount() > 0) {
		$info = $sql->fetch();

		$_SESSION['banco'] = $info['id'];
		header('Location: index.php');
		exit;
	} else {
		header('Location: login.php');
		exit;
	}
}

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		Caixa eletronico
	</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<h1>Login</h1>

	<form method="POST">
		Agencia: <br>
		<input type="text" name="agencia"><br><br>

		Conta: <br>
		<input type="text" name="conta"><br><br>

		Senha: <br>
		<input type="password" name="senha"><br><br>

		<input type="submit" value="Entrar">
	</form>

</body>
</html>


