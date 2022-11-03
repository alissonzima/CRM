<!doctype html>
<?php include_once("conn.php"); ?>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="jquery/jquery-3.5.1.min.js"></script>
	<script src="jquery/jquery.mask.min.js"></script>
	
	<script type="text/javascript" >
		
        $(document).ready(function() {
			
			// máscaras
			var options = {
				onKeyPress : function(cpfcnpj, e, field, options) {
					var masks = ['000.000.000-000', '00.000.000/0000-00'];
					var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
					$("#inputCPFCNPJ").mask(mask, options);
					}
			};
			$("#inputCPFCNPJ").mask('000.000.000-000', options);
			$("#inputCEP").mask('00000-000');			
			//SCRIPT DE CEP
			//digitar endereço
			$("#enderecoManual").click(function() { 
				if ( $("#enderecoManual").is( ':checked' ) ) {
					$("#inputRua").removeAttr('readonly');
					$("#inputBairro").removeAttr('readonly');
					$("#inputCidade").removeAttr('readonly');
					$("#inputUF").removeAttr('readonly');
				} else {
					$("#inputRua").prop("readonly", true);
					$("#inputBairro").prop("readonly", true);
					$("#inputCidade").prop("readonly", true);
					$("#inputUF").prop("readonly", true);
				}
			});
			
            //Quando o campo cep perde o foco.
            $("#inputCEP").blur(function() {
                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');
                //Verifica se campo cep possui valor informado.
                if (cep != "") {
                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;
                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#inputRua").val("...");
                        $("#inputBairro").val("...");
                        $("#inputCidade").val("...");
                        $("#inputUF").val("...");
                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#inputRua").val(dados.logradouro);
                                $("#inputBairro").val(dados.bairro);
                                $("#inputCidade").val(dados.localidade);
                                $("#inputUF").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                //limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        //limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    //limpa_formulário_cep();
                }
            });
        });
    </script>
	
	<title>Cadastrar Cliente</title>
</head>
<body>
	<?php $menu = 2; include_once("menu.php"); ?>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cadastroModal">
	Cadastrar Cliente
	</button>
	
	<!-- Modal -->
	<form method="post" action="inserirCliente.php">
		<div class="modal fade" id="cadastroModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="cadastroModal">Criar Contato</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<div class="form-row">
					<div class="col-5 mb-1">
						<label for="inputConta" class="col-form-label">Nome na Conta</label>
						<input class="form-control" type="text" placeholder="Que nome consta na conta?" id="inputConta" name="inputConta">
					</div>			  
				</div>					
				<div class="form-row">
					<div class="col-5 mb-3">
						<label for="inputName" class="col-form-label-sm">Nome</label>
						<input class="form-control" type="text" placeholder="Digite o nome completo" id="inputName" name="inputName">
					</div>
					<div class="col-4 mb-3">
						<label for="inputName" class="col-form-label-sm">E-mail</label>
						<input class="form-control" type="email" placeholder="Digite o e-mail" id="inputEmail" name="inputEmail">
					</div>				
					<div class="col-3 mb-3">
						<label for="inputTelefone" class="col-form-label-sm">Telefone</label>
						<input class="form-control" type="tel" placeholder="Digite o telefone" id="inputTelefone" name="inputTelefone">
					</div>
				</div>
				<div class="form-row">
					<div class="col-2 mb-5">
						<label for="inputCEP" class="col-form-label-sm">CEP</label>
						<input class="form-control" type="text" placeholder="Digite o CEP" id="inputCEP" name="inputCEP">
					</div>
					<div class="col-7 mb-5">
						<label for="inputRua" class="col-form-label-sm">Rua</label>
						<input class="form-control" type="text" placeholder="Rua" id="inputRua" readonly name="inputRua">
					</div>
					<div class="col-1 mb-5">
						<label for="inputNumero" class="col-form-label-sm">Número</label>
						<input class="form-control" type="text" id="inputNumero" name="inputNumero">
					</div>
					<div class="col-2 mb-5">
						<label for="inputComplemento" class="col-form-label-sm">Complemento</label>
						<input class="form-control" type="text" id="inputComplemento" name="inputComplemento">
					</div>				
				</div>
				<div class="form-row">
					<div class="col-4 mb-4">
						<label for="inputBairro" class="col-form-label-sm">Bairro</label>
						<input class="form-control" type="text" placeholder="Bairro" id="inputBairro" readonly name="inputBairro">
					</div>
					<div class="col-5 mb-4">
						<label for="inputCidade" class="col-form-label-sm">Cidade</label>
						<input class="form-control" type="text" placeholder="Cidade" id="inputCidade" readonly name="inputCidade">
					</div>
					<div class="col-1 mb-4">
						<label for="inputUF" class="col-form-label-sm">UF</label>
						<input class="form-control" type="text" placeholder="UF" id="inputUF" readonly name="inputUF">
					</div>
					  <div class="form-check col-2 mb-4" style="text-align: center;">
						<label class="col-form-label-sm" for="enderecoManual">Digitar Endereço</label>
						<input type="checkbox" class="form-control" id="enderecoManual">
					</div>
				</div>
				<div class="form-row">
					<div class="col-3 mb-4">
						<label for="inputCPFCNPJ" class="col-form-label-sm">CPF/CNPJ</label>
						<input class="form-control" type="text" placeholder="Digite o cpf ou cnpj" id="inputCPFCNPJ" name="inputCPFCNPJ">
					</div>
					<div class="col-5 mb-4">
						<label for="inputOrigem" class="col-form-label-sm">Origem</label>
						<input class="form-control" type="text" placeholder="Qual a origem do lead" id="inputOrigem" name="inputOrigem">
					</div>
					<div class="col-4 mb-4">
						<label for="inputEtapa" class="col-form-label-sm">Etapa do lead</label>
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
				</div>
				<div class="form-row">
					<div class="col-7 mb-2">
						<label for="inputEmpresa" class="col-form-label-sm">Empresa</label><br>
						<div class="align-items-center" style="text-align: center;">
							<?php
								$sql = "SELECT id, nome FROM empresa order by id";
								$resultado = oci_parse($conn, $sql);
								oci_execute($resultado);
								while ($linha = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
									//echo "<script>console.log('" . json_encode($linha) . "');</script>";
									echo    "<div class='form-check form-check-inline'>
											  <input class='form-check-input' type='radio' name='inputEmpresa' id='radio".$linha['ID']."' value='".$linha['ID']."'>
											  <label class='form-check-label' for='radio".$linha['ID']."'>".$linha['NOME']."</label>
											</div>";
								}
							?>
						</div>
					</div>
					<div class="col-5 mb-2">
						<label for="inputVendedor" class="col-form-label-sm">Vendedor</label>
						<select class="form-control" id="inputVendedor" name="inputVendedor">
						<?php 
							$sql = "SELECT id, nome FROM vendedor order by nome";
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
				<button type="submit" class="btn btn-primary" name="salvarCliente">Salvar cliente</button>
			  </div>
			</div>
		  </div>
		</div>
	</form>
	<script src="umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>