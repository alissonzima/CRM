<?php 
include_once("conn.php");
/*if ($_POST['info'] == 'cliente') {
	$idCliente = $_POST['idCliente'];
	$stid = oci_parse($conn, 'select c.nome as NOME from cliente c where c.id = '.$idCliente);
	oci_execute($stid);
	$resultado = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
	$saida = json_encode($resultado['NOME']);
	echo($saida);
};*/
if ($_POST['info'] == 'cliente') {
	$idCliente = $_POST['idCliente'];
	$stid = oci_parse($conn, 'select c.nome as NOME, c.id as ID, c.idetapa as IDETAPA, c.idvendedor as IDVENDEDOR, c.idempresa as IDEMPRESA from cliente c where c.id = '.$idCliente);
	oci_execute($stid);
	$resultado = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
	$saida = json_encode($resultado);
	echo($saida);
};
if ($_POST['info'] == 'propostas') {
	$i = 0;
	$idCliente = $_POST['idCliente'];
	$stid = oci_parse($conn, 'select c.nome as NOME, e.id as IDPROPOSTA from cliente c inner join evolucao e on c.id = e.idcliente where c.id = '.$idCliente.' order by 1');
	oci_execute($stid);
	while ($linha = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		$resultado[$i++] = $linha;
	}
	$saida = json_encode($resultado);
	echo($saida);
};
?>