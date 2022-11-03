<!doctype html>
<?php include_once("conn.php"); ?>
<html>
<head>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="jquery/jquery-3.5.1.min.js"></script>
	<script src="jquery/jquery.mask.min.js"></script>	
	<script type="text/javascript" >
		$(document).ready(function(){
			
			$("#botaoEvolucao").click(function(){
				
				$.post("retorna.php", {
					idCliente:$("#inputId").val(),
					info:"propostas"
					} ,function(data){
						dados = JSON.parse(data);
						console.log(dados);
						$('.modal-title').text(dados[0].NOME);
					//alert("Nome: "+data);
					});
				$("#evolucaoModal").modal();
				return false;
				
			});
		});
	</script>
 <head>
</head>
<body>
	<?php $menu = 4; include_once("menu.php"); ?>
	<form id="formEvolucao">
		<div class="form-group col-md-2">
			<label for="inputId">Id do cliente:</label>
			<input type="text" class="form-control" id="inputId">
		</div>
		<button id="botaoEvolucao" class="btn btn-primary">
		Visualizar Cliente
		</button>
	</form>
	
	<form method="post" action="inserirCliente.php">
		
		<div class="modal fade" id="evolucaoModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title" id="evolucaoModal">NOME DO CLIENTE VEM AQUI</h4>
										<nav aria-label="Page navigation example">
										  <ul class="pagination justify-content-end">
											<li class="page-item disabled">
											  <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
											</li>
											<li class="page-item"><a class="page-link" href="#">1</a></li>
											<li class="page-item"><a class="page-link" href="#">2</a></li>
											<li class="page-item"><a class="page-link" href="#">3</a></li>
											<li class="page-item">
											  <a class="page-link" href="#">Próxima</a>
											</li>
										  </ul>
										</nav>
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
				<button type="submit" class="btn btn-primary" name="evoluirCliente">Salvar evolução</button>
			  </div>
			</div>
		  </div>
		</div>
	</form>
	<script src="umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>		
</body>
</html>