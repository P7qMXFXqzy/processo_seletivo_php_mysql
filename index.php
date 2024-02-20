<!DOCTYPE html>
<html>
<head>
    <title>Processo Seletivo Geovendas</title>
</head>
<body>

<?php
	//definição dos dados para fazer conexão com o banco de dados MySQL
	define( 'MYSQL_HOST', 'localhost' );
	define( 'MYSQL_USER', 'root' ); //substituir pelo seu usuário
	define( 'MYSQL_PASSWORD', 'abcd' ); //substituir pela sua senha do MySQL
	define( 'MYSQL_DB_NAME', 'processo_seletivo' ); //substituir pelo nome do seu banco de dados
	//tentar conexão com o banco de dados através dos dados fornecidos acima
	try{
		$PDO = new PDO( 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );
	}
	catch (PDOException $e){echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();}

	//verificar pelo nome do produto se ele já existe no banco de dados
	function checar_se_produto_existe($produto_inserido){
		global $PDO;
		$comando = "SELECT produto FROM estoque WHERE produto= '". $produto_inserido."';";
		$resultado_pesquisa = $PDO->query( $comando );
		$valores_encontrados = $resultado_pesquisa->fetchAll( PDO::FETCH_ASSOC );
		//0 = não encontrado, 1 = produto encontrado
		if(empty($valores_encontrados)){return 0;}
		else{return 1;}
	}

	//inserir ou alterar um produto no banco de dados com base nos dados fornecidos
	function inserir_ou_alterar_produto($produto, $alterar_produto, $cor, $tamanho, $deposito, $data_disponibilidade, $quantidade){
		global $PDO;
		$comando_sql = null;
		$execucao = null;
		//inserir um novo produto caso ele ainda não exista no banco de dados
		if(checar_se_produto_existe($produto) == 0 && checar_se_produto_existe($alterar_produto) == 0){
			$comando_sql = "INSERT INTO estoque (produto, cor, tamanho, deposito, data_disponibilidade, quantidade) VALUES (:produto , :cor , :tamanho , :deposito , :data_disponibilidade , :quantidade );";
			$execucao = $PDO->prepare($comando_sql);
		}
		//atualizar um produto caso ele tenha sido encontrado no banco de dados
		else{
			$comando_sql = "UPDATE estoque SET produto = :alterar_produto , cor = :cor , tamanho = :tamanho , deposito = :deposito , data_disponibilidade = :data_disponibilidade , quantidade = :quantidade WHERE produto = :produto";
			$execucao = $PDO->prepare($comando_sql);
			$execucao->bindParam(':alterar_produto', $alterar_produto );
		}
		//passando o valor de todos os parâmetros da função para os parâmetros do comando SQL (estes estão fora de um bloco if-else pois todos serão utilizados em todos os comandos SQL)
		$execucao->bindParam(':produto', $produto );
		$execucao->bindParam(':cor', $cor );
		$execucao->bindParam(':tamanho', $tamanho );
		$execucao->bindParam(':deposito', $deposito );
		$execucao->bindParam(':data_disponibilidade', $data_disponibilidade );
		$execucao->bindParam(':quantidade', $quantidade );	
		//execução do comando
		try{
			$resultado = $execucao->execute();
			echo '<br>'."produto inserido/alterado com sucesso!";
		}
		//mostrar mensagem de erro caso tenha ocorrido um erro durante a execução do comando SQL
		catch(Exception $e){echo '<br>'."Algo está errado com seu arquivo JSON! Verifique se já não existe um produto com o mesmo nome que você tentou inserir ou se algum dado está marcado como \"null\"! Nome do produto: ".$alterar_produto;}
	}

	//receber e converter dados dentro do arquivo "dados_inseridos.json" para um array 
	$dados_json = file_get_contents("dados_inseridos.json");
	$dados_json_convertidos = json_decode($dados_json, true);
	//inserir ou editar no banco de dados cada endereço/grupo de dados dentro do array
	for($i = 0; $i < count($dados_json_convertidos); $i++){
		//checar se a linha "alterar_produto" existe neste endereço no arquivo json e se ela não tem um valor "null", se sim, o algoritmo interpretará que o usuário quer alterar o nome atual do produto pelo valor da linha "alterar_produto".
		if(isset($dados_json_convertidos[$i]["alterar_produto"]) && $dados_json_convertidos[$i]["alterar_produto"] != null){
			inserir_ou_alterar_produto($dados_json_convertidos[$i]["produto"], $dados_json_convertidos[$i]["alterar_produto"], $dados_json_convertidos[$i]["cor"], $dados_json_convertidos[$i]["tamanho"], $dados_json_convertidos[$i]["deposito"], $dados_json_convertidos[$i]["data_disponibilidade"], $dados_json_convertidos[$i]["quantidade"]);
		}
		//caso o usuário não tenha inserido um valor para "alterar_produto" (ou se tiver um valor "null"), reutilizará o nome inicial do produto, indicando que o produto em questão não terá seu nome alterado
		else{
			inserir_ou_alterar_produto($dados_json_convertidos[$i]["produto"], $dados_json_convertidos[$i]["produto"], $dados_json_convertidos[$i]["cor"], $dados_json_convertidos[$i]["tamanho"], $dados_json_convertidos[$i]["deposito"], $dados_json_convertidos[$i]["data_disponibilidade"], $dados_json_convertidos[$i]["quantidade"]);
		}
	}
?>
</body>
</html>