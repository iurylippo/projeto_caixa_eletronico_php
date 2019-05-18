<?php
session_start();
require 'config.php';

if (isset($_SESSION['banco']) && !empty($_SESSION['banco'])) {
	$id = addslashes($_SESSION['banco']);

	$sql = $pdo->prepare("SELECT * FROM contas WHERE id = :id");
	$sql->bindValue(':id', $id);
	$sql->execute();

	if($sql->rowCount() > 0) {
		$info = $sql->fetch();

		$titular = $info['titular'];
		$agencia = $info['agencia'];
		$conta = $info['conta'];
		$saldo = number_format($info['saldo'], 2, ' , ', ' ') ;
	} else {
	header('Location: login.php');
	exit;
	}
} else {
	header('Location: login.php');
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Conta</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<h1>BANCO XYZ</h1>

	<h4>Titular: <?php echo $titular; ?></h4>

	<h4>Agencia: <?php echo $agencia; ?></h4>

	<h4>Conta: <?php echo $conta; ?></h4>

	<h4>Saldo: R$ <?php echo $saldo; ?></h4>

	<a href="sair.php">Sair</a>
<hr>
	<h3>Movimentação/Extrato</h3>

	<a href="add_transacao.php">Adicionar Transação</a>
	<br>
	<br>
	<table border="1" width="400">
		<tr>
			<th>Data</th>
			<th>Valor</th>
		</tr>
		<?php 
		$sql = $pdo->prepare("SELECT * FROM historico WHERE id_conta = :id_conta");
		$sql->bindValue(':id_conta', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			foreach($sql->fetchAll() as $item):
            ?>
				<tr>
					<td><?php echo date('d/m/Y H:i', strtotime($item['dataoperacao'])); ?></td>
					<td><?php echo ($item['tipo'] == 0)? '<font color="green">+R$'.$item['valor'].'</font>' : '<font color="red">-R$'.$item['valor'].'</font>' ?></td>
				</tr>

			<?php	
			endforeach;
		}
		 ?>
		
	</table>

</body>
</html>