<!DOCTYPE html>
<html>
<head>
	<script src="jquery/jquery-3.5.1.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="jquery/jquery.quicksearch.js"></script>
	<script src="jquery/jquery.mask.min.js"></script>		
	<script type="text/javascript" >
		$(document).ready(function(){
	
			var dados;

			$('[data-toggle="tooltip"]').tooltip();
		
			$("#inputDtEvolucao").mask('00/00/0000');
			$("#inputValor").mask('###.###.##0,00', {reverse: true});
			$("#inputKwp").mask('0000,00', {reverse: true});
			
			$(".evolucaoImg").click(function(e){
				
				$(".ti").show();
				$(".engenharia").show();
				$(".solar").show();
				//alert(e.target.id);
				
				$.post("retorna.php", {
					idCliente:e.target.id,
					info:"cliente"
					} ,function(data){
						dados = JSON.parse(data);
						//console.log(dados);
						$("#inputName").val(dados.NOME);
						$("#inputIdCliente").val(dados.ID);
						$("#inputEtapa").val(dados.IDETAPA);
						$("#inputVendedor").val(dados.IDVENDEDOR);
						if (dados.IDEMPRESA == '41'){
							$(".ti").hide();
							$(".engenharia").hide();
						} else if (dados.IDEMPRESA == '42') {
							$(".ti").hide();
							$(".solar").hide();					
						} else {
							$(".engenharia").hide();
							$(".solar").hide();							
						};						
					//alert("Nome: "+data);
					});
				$("#evolucaoModal").modal();
				return false;
			});
			
		});
	</script>
	
</head>
<body>
	<?php $menu = 3; include_once("menu.php"); ?>

	<?php
		include_once("conn.php");
		$stid = oci_parse($conn, '    select c.nome, c.conta, c.email, c.telefone, e.cidade, substr(v.nome,1,instr(v.nome,\' \')-1) as VENDEDOR, em.nome as EMPRESA, et.descricao as ETAPA, et.id as IDETAPA, c.id as ID
										from cliente c inner join endereco e on c.idendereco = e.id 
										inner join vendedor v on c.idvendedor = v.id 
										inner join etapa et on c.idetapa = et.id 
										inner join empresa em on c.idempresa = em.id');
		oci_execute($stid);
	?>
	<br>
	<div class="col-4">
		<div class="form-group input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
			<input name="consulta" id="txt_consulta" placeholder="Pesquisar" type="text" class="form-control">
		</div>
	</div>

	<table id='clientes' class='table table-sm table-bordered'>
		<thead>
			<tr>
				<th scope='col'>AÇÃO</th>
				<?php 
					$ncols = oci_num_fields($stid);
					for ($i = 1; $i <= $ncols; ++$i) {
						$colname = oci_field_name($stid, $i);
						echo "<th scope='col'>".htmlspecialchars($colname,ENT_QUOTES|ENT_SUBSTITUTE)."</th>";
					}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
				while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
					if ($row['IDETAPA'] == '62'){
						echo "<tr class='table-info'>";
					} else if ($row['IDETAPA'] == '67'){
						echo "<tr class='table-danger'>";
					} else if  ($row['IDETAPA'] == '66'){
						echo "<tr class='table-dark'>";
					} else if  ($row['IDETAPA'] == '65'){
						echo "<tr class='table-success'>";
					} else if  ($row['IDETAPA'] == '63'){
						echo "<tr class='table-primary'>";
					} else if  ($row['IDETAPA'] == '64'){
						echo "<tr class='table-warning'>";
					} else {
						echo "<tr>";
					}
					echo "<td class='text-nowrap evolucaoImg'><img src='img/evolucao.png' width='30' alt='teste' data-toggle='tooltip' data-placement='top' title='Atualize suas reuniões com o cliente.' id=".$row['ID']."></td>";
					foreach ($row as $item) {
						echo "<td class='text-nowrap'>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>";
					}
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
	<form method="post" action="inserirCliente.php">
		<div class="modal fade" id="evolucaoModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="evolucaoModal">Atualizar contato com cliente</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<div class="form-row">
				<input type="hidden" id="inputIdCliente" name="inputIdCliente">
					<div class="col-4 mb-4">
						<label for="inputName" class="col-form-label-sm">Nome</label>
						<input class="form-control" type="text" readonly id="inputName" name="inputName">
					</div>			
					<div class="col-3 mb-4">
						<label for="inputValor" class="col-form-label-sm">Valor do Projeto</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">R$</div>
							</div>
							<input class="form-control" type="text" placeholder="Digite o Valor" id="inputValor" name="inputValor">
						</div>
					</div>
					<div class="col-2 mb-4 solar">
						<label for="inputKwp" class="col-form-label-sm">Sistema</label>
						<input class="form-control" type="text" placeholder="em KWP" id="inputKwp" name="inputKwp">
					</div>	
					<div class="col-2 mb-4 engenharia">
						<label for="inputEngenheiro" class="col-form-label-sm">Engenheiro Resp.</label>
						<select class="form-control" id="inputEngenheiro" name="inputEngenheiro">
							<option value="">---</option>
							<?php 
								$sql = 'SELECT e.id as ID, substr(e.nome,1,instr(e.nome,\' \')-1) as NOME FROM engenheiro e order by 2';
								$resultado = oci_parse($conn, $sql);
								oci_execute($resultado);
								while ($linha = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
									//echo "<script>console.log('" . json_encode($linha) . "');</script>";
									echo "<option value='".$linha['ID']."'>".$linha['NOME']."</option>";
								}
							?>
						</select>
					</div>	
					<div class="col-2 mb-4 ti">
						<label for="inputTipoProjeto" class="col-form-label-sm">Tipo de Projeto</label>
						<select class="form-control" id="inputTipoProjeto" name="inputTipoProjeto">
							<option value="">---</option>
							<?php 
								$sql = "SELECT id, descricao FROM tipoprojeto order by descricao";
								$resultado = oci_parse($conn, $sql);
								oci_execute($resultado);
								while ($linha = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
									//echo "<script>console.log('" . json_encode($linha) . "');</script>";
									echo "<option value='".$linha['ID']."'>".$linha['DESCRICAO']."</option>";
								}
							?>
						</select>
					</div>	
					<div class="col-3 mb-4">
					<label for="inputPrevisaoFechamento" class="col-form-label-sm">Previsão de Fechamento</label>
						<input class="form-control" type="date" placeholder="Digite a Data" id="inputPrevisaoFechamento" name="inputPrevisaoFechamento">
					</div>						
				</div>
				<div class="form-row">
					<div class="col-3 mb-4">
					<label for="inputDtProxAcao" class="col-form-label-sm">Data da Próxima Ação</label>
						<input class="form-control" type="date" placeholder="Digite a Data" id="inputDtProxAcao" name="inputDtProxAcao">
					</div>					
					<div class="col-3 mb-5">
						<label for="inputTipoAcao" class="col-form-label-sm">Próxima Ação</label>
						<select class="form-control" id="inputTipoAcao" name="inputTipoAcao">
						<option value="">---</option>
						<?php 
							$sql = "SELECT id, descricao FROM tipoacao order by descricao";
							$resultado = oci_parse($conn, $sql);
							oci_execute($resultado);
							while ($linha = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
								//echo "<script>console.log('" . json_encode($linha) . "');</script>";
								echo "<option value='".$linha['ID']."'>".$linha['DESCRICAO']."</option>";
							}
						?>
						</select>
					</div>				
					<div class="col-3 mb-4">
					<label for="inputPrevisaoInicio" class="col-form-label-sm">Previsão de Início</label>
						<input class="form-control" type="date" placeholder="Digite a Data" id="inputPrevisaoInicio" name="inputPrevisaoInicio">
					</div>	
					<div class="col-3 mb-4">
					<label for="inputPrevisaoEntrega" class="col-form-label-sm">Previsão de Entrega</label>
						<input class="form-control" type="date" placeholder="Digite a Data" id="inputPrevisaoEntrega" name="inputPrevisaoEntrega">
					</div>						
				</div>
				<div class="form-row">
					<div class="col-12 mb-1">
						<label for="inputDescServico" >Informações</label>
						<textarea class="form-control" placeholder="Descreva a reunião com o cliente ou discrimine o serviço efetuado" id="inputDescServico" rows="3" name="inputDescServico"></textarea>
					 </div>				
				</div>
				<div class="form-row">		
					<div class="col-4 mb-2">
						<label for="inputEtapa" class="col-form-label-sm">Nova etapa do lead</label>
							<select class="form-control" id="inputEtapa" name="inputEtapa">
								<?php 
									$sql = "SELECT id, descricao FROM etapa order by id";
									$resultado = oci_parse($conn, $sql);
									oci_execute($resultado);
									while ($linha = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
										//echo "<script>console.log('" . json_encode($linha) . "');</script>";
										echo "<option value='".$linha['ID']."'>".$linha['DESCRICAO']."</option>";
									}
								?>
							</select>
					</div>
					<div class="col-2 mb-2">
						<label for="inputVendedor" class="col-form-label-sm">Vendedor</label>
						<select class="form-control" id="inputVendedor" name="inputVendedor">
						<?php 
							$sql = 'SELECT v.id as ID, substr(v.nome,1,instr(v.nome,\' \')-1) as NOME FROM vendedor v order by 2';
							$resultado = oci_parse($conn, $sql);
							oci_execute($resultado);
							while ($linha = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
								//echo "<script>console.log('" . json_encode($linha) . "');</script>";
								echo "<option value='".$linha['ID']."'>".$linha['NOME']."</option>";
							}
						?>
						</select>
					</div>		
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				<button type="submit" class="btn btn-primary" name="evoluirCliente">Salvar Atualização</button>
			  </div>
			</div>
		  </div>
		</div>
	</form>	
	
	<script>
	$('input#txt_consulta').quicksearch('table#clientes tbody tr');
	</script>	
	<script src="umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>