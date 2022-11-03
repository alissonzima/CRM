<?php $dashboard =''; $cadastro = ''; $exibir = ''; $evolucao = '';
	if ($menu == 1) {
		$dashboard = 'active';
	} else if ($menu == 2){
		$cadastro = 'active';
	} else if ($menu == 3){
		$exibir = 'active';
	} else if ($menu == 4){
		$evolucao = 'active';
	}
	echo '<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link '.$dashboard.' " href="dashboard.php" id="menuDashboard">Dashboard</a>
			</li>
			<li class="nav-item">
				<a class="nav-link '.$cadastro.' " href="cadastrarCliente.php" id="menuCadastro">Cadastrar Cliente</a>
			</li>
			<li class="nav-item">
				<a class="nav-link '.$exibir.' " href="exibirCliente.php" id="menuExibir">Exibir Clientes</a>
			</li>
			<li class="nav-item">
				<a class="nav-link '.$evolucao.' " href="evoluirCliente.php" id="evoluirCliente">Visualizar Cliente</a>
			</li>			
		  </ul>';
	//var_dump($cadastro);
?>