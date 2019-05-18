<?php 
session_start();
require 'config.php';

if(isset($_POST['tipo'])) {
	$tipo = addslashes($_POST['tipo']);
	$valor = str_replace(",", ".", ($_POST['valor']));
	$valor = floatval($valor);

	$sql = $pdo->prepare("INSERT INTO historico (id_conta, tipo, valor, dataoperacao) VALUES(:id_conta, :tipo, :valor, NOW())");
	$sql->bindValue(':id_conta', $_SESSION['banco']);
	$sql->bindValue(':tipo', $tipo);
	$sql->bindValue(':valor', $valor);
	$sql->execute();

	if($tipo == '0') {
		//Deposito
		$sql = $pdo->prepare("UPDATE contas SET saldo = saldo + :valor WHERE id = :id");
		$sql->bindValue(':valor', $valor);
		$sql->bindValue(':id', $_SESSION['banco']);
		$sql->execute();
	} else {
		//Saque
		$sql = $pdo->prepare("UPDATE contas SET saldo = saldo - :valor WHERE id = :id");
		$sql->bindValue(':valor', $valor);
		$sql->bindValue(':id', $_SESSION['banco']);
		$sql->execute();
	}

	header('Location: index.php');
	exit;
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		adicionar transação
	</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<h1>Login</h1>

	<form method="POST">
		Tipo de transação: <br>
		<select name="tipo">
			<option value="0">Depósito</option>
			<option value="1">Retirada</option>
		</select>
		<br><br>
		valor: <br>
		<input type="text" name="valor" pattern="[0-9.,]{1,}"><br><br>

		<input type="submit" value="Adicionar">
	</form>

</body>
</html>