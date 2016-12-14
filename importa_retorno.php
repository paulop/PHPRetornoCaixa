<?php
// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = 'retorno/';

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('ret');

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = false;

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

	// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
	if ($_FILES['arquivo']['error'] != 0) {
		die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
	exit; // Para a execução do script
	}

	// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar

	// Faz a verificação da extensão do arquivo
	$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
	if (array_search($extensao, $_UP['extensoes']) === false) {
		echo "Por favor, envie arquivos com as seguintes extensões: .ret";
	}
	// Faz a verificação do tamanho do arquivo
	else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
		echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
	}
	// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
	else {
		// Primeiro verifica se deve trocar o nome do arquivo
		if ($_UP['renomeia'] == true) {
			// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
			$nome_final = time().'.ret';
		} else {
			// Mantém o nome original do arquivo
			$nome_final = $_FILES['arquivo']['name'];
		}
		// Depois verifica se é possível mover o arquivo para a pasta escolhida
		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
			// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
			echo "Upload efetuado com sucesso!<br>";
			//echo '<br /><a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';
			
			$lendo = @fopen($_UP['pasta'] . $nome_final,"r");
			if (!$lendo){
				echo "Erro ao abrir a URL.";
				exit;
			}
			$i = 0;
			while (!feof($lendo)){
				$i++;
				$linha = fgets($lendo,9999);
				$t_u_segmento = substr($linha,13,1);//Segmento T ou U
				$t_tipo_reg = substr($linha,7,1);//Tipo de Registro
				if($t_u_segmento == 'T'){
					$t_cod_banco = substr($linha,0,3);//Código do banco na compensação
					$t_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
					$t_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
					$t_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
					$t_cod_conv_banco = substr($linha,23,6);//Código do convênio no banco - Código fornecido pela CAIXA, através da agência de relacionamento do cliente. Deve ser preenchido com o código do Cedente (6 posições).
					$t_n_banco_sac = substr($linha,32,3);//Numero do banco de sacados
					$t_mod_nosso_n = substr($linha,39,2);//Modalidade nosso número
					$t_id_titulo_banco = substr($linha,41,15);//Identificação do titulo no banco - Número adotado pelo Banco Cedente para identificar o Título.
					$t_cod_carteira = substr($linha,57,1);//Código da carteira - Código adotado pela FEBRABAN, para identificar a característica dos títulos. 1=Cobrança Simples, 3=Cobrança Caucionada, 4=Cobrança Descontada
					$t_num_doc_cob = substr($linha,58,11);//Número do documento de cobrança - Número utilizado e controlado pelo Cliente, para identificar o título de cobrança.
					$t_dt_vencimento = substr($linha,73,8);//Data de vencimento do titulo - Data de vencimento do título de cobrança.
					$t_v_nominal = substr($linha,81,13);//Valor nominal do titulo - Valor original do Título. Quando o valor for expresso em moeda corrente, utilizar 2 casas decimais.
					$t_cod_banco2 = substr($linha,96,3);//Código do banco
					$t_cod_ag_receb = substr($linha,99,5);//Codigo da agencia cobr/receb - Código adotado pelo Banco responsável pela cobrança, para identificar o estabelecimento bancário responsável pela cobrança do título.
					$t_dv_ag_receb = substr($linha,104,1);//Dígito verificador da agencia cobr/receb
					$t_id_titulo_empresa = substr($linha,105,25);//identificação do título na empresa - Campo destinado para uso da Empresa Cedente para identificação do Título. Informar o Número do Documento - Seu Número.
					$t_cod_moeda = substr($linha,130,2);//Código da moeda
					$t_tip_inscricao = substr($linha,132,1);//Tipo de inscrição - Código que identifica o tipo de inscrição da Empresa ou Pessoa Física perante uma Instituição governamental: 0=Não informado, 1=CPF, 2=CGC / CNPJ, 9=Outros.
					$t_num_inscricao = substr($linha,133,15);//Número de inscrição - Número de inscrição da Empresa (CNPJ) ou Pessoa Física (CPF).
					$t_nome = substr($linha,148,40);//Nome - Nome que identifica a entidade, pessoa física ou jurídica, Cedente original do título de cobrança.
					$t_v_tarifa_custas = substr($linha,198,13);//Valor da tarifa/custas
					$t_id_rejeicoes = substr($linha,213,10);//Identificação para rejeições, tarifas, custas, liquidação e baixas
				}
				if($t_u_segmento == 'U'){
					$t_id_titulo_banco;
					$u_cod_banco = substr($linha,0,3);//Código do banco na compensação
					$u_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
					$u_tipo_reg = substr($linha,7,1);//Tipo de Registro - Código adotado pela FEBRABAN para identificar o tipo de registro: 0=Header de Arquivo, 1=Header de Lote, 3=Detalhe, 5=Trailer de Lote, 9=Trailer de Arquivo.
					$u_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
					$u_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
					$u_juros_multa = substr($linha,17,15);//Jurus / Multa / Encargos - Valor dos acréscimos efetuados no título de cobrança, expresso em moeda corrente.
					$u_desconto = substr($linha,32,15);//Valor do desconto concedido - Valor dos descontos efetuados no título de cobrança, expresso em moeda corrente.
					$u_abatimento = substr($linha,47,15);//Valor do abat. concedido/cancel. - Valor dos abatimentos efetuados ou cancelados no título de cobrança, expresso em moeda corrente.
					$u_iof = substr($linha,62,15);//Valor do IOF recolhido - Valor do IOF - Imposto sobre Operações Financeiras - recolhido sobre o Título, expresso em moeda corrente.
					$u_v_pago = substr($linha,77,15);//Valor pago pelo sacado - Valor do pagamento efetuado pelo Sacado referente ao título de cobrança, expresso em moeda corrente.
					$u_v_liquido = substr($linha,92,15);//Valor liquido a ser creditado - Valor efetivo a ser creditado referente ao Título, expresso em moeda corrente.
					$u_v_despesas = substr($linha,107,15);//Valor de outras despesas - Valor de despesas referente a Custas Cartorárias, se houver.
					$u_v_creditos = substr($linha,122,15);//Valor de outros creditos - Valor efetivo de créditos referente ao título de cobrança, expresso em moeda corrente.
					$u_dt_ocorencia = substr(substr($linha,137,8),4,4).'-'.substr(substr($linha,137,8),2,2).'-'.substr(substr($linha,137,8),0,2);//Data da ocorrência - Data do evento que afeta o estado do título de cobrança.
					$u_dt_efetivacao = substr($linha,145,8);//Data da efetivação do credito - Data de efetivação do crédito referente ao pagamento do título de cobrança.
					$u_dt_debito = substr($linha,157,8);//Data do débito da tarifa
					$u_cod_sacado = substr($linha,167,15);//Código do sacado no banco
					$u_cod_banco_comp = substr($linha,210,3);//Cód. Banco Correspondente compens - Código fornecido pelo Banco Central para identificação na Câmara de Compensação, do Banco ao qual será repassada a Cobrança do Título.
					$u_nn_banco = substr($linha,213,20);//Nosso Nº banco correspondente - Código fornecido pelo Banco Correspondente para identificação do Título de Cobrança. Deixar branco (Somente para troca de arquivos entre Bancos).
 
					$u_juros_multa = substr($u_juros_multa,0,13).'.'.substr($u_juros_multa,13,2);
					$u_desconto = substr($u_desconto,0,13).'.'.substr($u_desconto,13,2);
					$u_abatimento = substr($u_abatimento,0,13).'.'.substr($u_abatimento,13,2);
					$u_iof = substr($u_iof,0,13).'.'.substr($u_iof,13,2);
					$u_v_pago = substr($u_v_pago,0,13).'.'.substr($u_v_pago,13,2);
					$u_v_liquido = substr($u_v_liquido,0,13).'.'.substr($u_v_liquido,13,2);
					$u_v_despesas = substr($u_v_despesas,0,13).'.'.substr($u_v_despesas,13,2);
					$u_v_creditos = substr($u_v_creditos,0,13).'.'.substr($u_v_creditos,13,2);
 
					$data_agora = date('Y-m-d');
					$hora_agora = date('H:i:s');
					$insert = "UPDATE financeiro SET valor_pago_fn='$u_v_pago', pagamento_fn='$u_dt_ocorencia', status_fn='Pago', financ_data_fn='$data_agora', financ_hora_fn='$hora_agora', retorno_fn='$nome' WHERE id_fn='$t_id_titulo_banco'";
					//$query = mysql_query($insert);
					echo $insert.'<br>';
				}
			}
			fclose($lendo);
			
			
		} else {
			// Não foi possível fazer o upload, provavelmente a pasta está incorreta
			echo "Não foi possível enviar o arquivo, tente novamente";
		}
}

?>
