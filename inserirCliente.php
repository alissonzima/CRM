<!DOCTYPE html>
<html>
<head>
	<script src="jquery/jquery-3.5.1.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	</head>
<body>

<?php
include_once("conn.php");
//var_dump($_POST);
	if(isset($_POST['salvarCliente'])) {
		$cpfcnpj = str_replace('-',"",$_POST['inputCPFCNPJ']);
		$cpfcnpj = str_replace('.',"",$cpfcnpj);
		$cpfcnpj = str_replace('/',"",$cpfcnpj);
		if (strlen($cpfcnpj) == 11){
			$cpf = $cpfcnpj;
			$cnpj = null;
		} else {
			$cnpj = $cpfcnpj;
			$cpf = null;
		};
		$nome = $_POST['inputName'];
		$conta = $_POST['inputConta'];
		$email = $_POST['inputEmail'];
		$tel = $_POST['inputTelefone'];
		$cep = str_replace('-',"",$_POST['inputCEP']);
		$rua = $_POST['inputRua'];
		$nr = $_POST['inputNumero'];
		$compl = $_POST['inputComplemento'];
		$bairro = $_POST['inputBairro'];
		$cidade = $_POST['inputCidade'];
		$uf = $_POST['inputUF'];
		$origem = $_POST['inputOrigem'];
		$etapa = $_POST['inputEtapa'];
		$emp = $_POST['inputEmpresa'];
		$vend = $_POST['inputVendedor'];
		
		$sqlEnd = 'BEGIN adicionaEndereco(:cep, :rua, :nr, :comp, :bairro, :cidade, :uf, :endId); end;';
		$stmtEnd = oci_parse($conn,$sqlEnd);
		oci_bind_by_name($stmtEnd,':cep',$cep);
		oci_bind_by_name($stmtEnd,':rua',$rua);
		oci_bind_by_name($stmtEnd,':nr',$nr);
		oci_bind_by_name($stmtEnd,':comp',	$compl);
		oci_bind_by_name($stmtEnd,':bairro',$bairro);
		oci_bind_by_name($stmtEnd,':cidade',$cidade);
		oci_bind_by_name($stmtEnd,':uf',$uf);
		oci_bind_by_name($stmtEnd,':endId',$endId);
		oci_execute($stmtEnd);
		
		$sqlCli = 'BEGIN adicionaCliente(:nome, :conta, :email, :tel, :cpf, :cnpj, :origem, :idvendedor, :idempresa, :idetapa, :idendereco); end;';
		$stmtCli = oci_parse($conn,$sqlCli);
		oci_bind_by_name($stmtCli,':nome',$nome);
		oci_bind_by_name($stmtCli,':conta',$conta);
		oci_bind_by_name($stmtCli,':email',$email);
		oci_bind_by_name($stmtCli,':tel',$tel);
		oci_bind_by_name($stmtCli,':cpf',	$cpf);
		oci_bind_by_name($stmtCli,':cnpj',$cnpj);
		oci_bind_by_name($stmtCli,':origem',$origem);
		oci_bind_by_name($stmtCli,':idvendedor',$vend);
		oci_bind_by_name($stmtCli,':idempresa',$emp);
		oci_bind_by_name($stmtCli,':idetapa',$etapa);
		oci_bind_by_name($stmtCli,':idendereco',$endId);
		oci_execute($stmtCli);
	}
	
if(isset($_POST['evoluirCliente'])) {
	//var_dump($_POST);
	    $kwp = $_POST['inputKwp'];
		$prevFech	= $_POST['inputPrevisaoFechamento'];
		$dtProxAcao = $_POST['inputDtProxAcao']; 
		$prevInicio = $_POST['inputPrevisaoInicio']; 
		$prevEntrega = $_POST['inputPrevisaoEntrega']; 
		$descServico = $_POST['inputDescServico']; 
		$idCliente = $_POST['inputIdCliente'];  
		$idTipoProj = $_POST['inputTipoProjeto'];  
		$idTipoAcao = $_POST['inputTipoAcao']; 
		$idEngenheiro = $_POST['inputEngenheiro'];
		$idEtapa = $_POST['inputEtapa'];
		$idVend = $_POST['inputVendedor'];

		$sqlEvo = "BEGIN adicionaEvolucao(to_number(:valor,'999G999G999D00'), to_number(:kwp,'9999999D00'), to_date(:previsaofechamento,'YYYY-MM-DD'), to_date(:dtproxacao,'YYYY-MM-DD'), to_date(:previsaoinicio,'YYYY-MM-DD'), to_date(:previsaoentrega,'YYYY-MM-DD'), :descservico, :idcliente, :idtipoprojeto, :idtipoacao, :idengenheiro, :idetapa, :idvendedor); end;";
		$stmtEvo = oci_parse($conn,$sqlEvo);
		oci_bind_by_name($stmtEvo,':valor',$valor);
		oci_bind_by_name($stmtEvo,':kwp',$kwp);
		oci_bind_by_name($stmtEvo,':previsaofechamento',$prevFech);
		oci_bind_by_name($stmtEvo,':dtproxacao',$dtProxAcao);
		oci_bind_by_name($stmtEvo,':previsaoinicio',$prevInicio);
		oci_bind_by_name($stmtEvo,':previsaoentrega',$prevEntrega);
		oci_bind_by_name($stmtEvo,':descservico',$descServico);
		oci_bind_by_name($stmtEvo,':idcliente',$idCliente);
		oci_bind_by_name($stmtEvo,':idtipoprojeto',$idTipoProj);
		oci_bind_by_name($stmtEvo,':idtipoacao',$idTipoAcao);
		oci_bind_by_name($stmtEvo,':idengenheiro',$idEngenheiro);
		oci_bind_by_name($stmtEvo,':idetapa',$idEtapa);
		oci_bind_by_name($stmtEvo,':idvendedor',$idVend);
		oci_execute($stmtEvo);
	}	

	/*$stid = oci_parse($conn, '    select c.nome, c.conta, c.email, c.telefone, c.cpf, c.cnpj, c.origem, e.cep, e.rua, e.numero, e.complemento, e.bairro, e.cidade, e.uf, v.nome as VENDEDOR, em.nome as EMPRESA, et.descricao as ETAPA 
						from cliente c inner join endereco e on c.idendereco = e.id 
						inner join vendedor v on c.idvendedor = v.id 
						inner join etapa et on c.idetapa = et.id 
						inner join empresa em on c.idempresa = em.id');
	oci_execute($stid);
	echo "Cadastro efetuado com sucesso<br><br>";
	echo "<table border='1'>\n";
	$ncols = oci_num_fields($stid);
	echo "<tr>\n";
	for ($i = 1; $i <= $ncols; ++$i) {
		$colname = oci_field_name($stid, $i);
		echo "  <th><b>".htmlspecialchars($colname,ENT_QUOTES|ENT_SUBSTITUTE)."</b></th>\n";
	}
	echo "</tr>\n";
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		echo "<tr>\n";
		foreach ($row as $item) {
			echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";*/
?>

	<form method="post" action="exibirCliente.php">
		<div class="modal" tabindex="-1" role="dialog" style="display: block;">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title">Processo concluído</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<p>Processo Efetuado com Sucesso.</p>
			  </div>
			  <div class="modal-footer">
				<button type="submit" class="btn btn-secondary" data-dismiss="modal">Ok</button>
			  </div>
			</div>
		  </div>
		</div>
	</form>
	
	
	<script src="umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<div class="modal-backdrop show"></div>
</body>
</html>