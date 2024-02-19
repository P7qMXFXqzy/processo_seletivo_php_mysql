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

	//verificar pelo nome se o produto já existe no banco de dados
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
	//caso a função de checagem retorne um valor 0, inserir novo produto no banco de dados. Caso a função retorne o valor 1, atualizar produto no banco de dados	
	function inserir_ou_alterar_produto($produto, $novo_produto, $cor, $tamanho, $deposito, $data_disponibilidade, $quantidade){
		global $PDO;
		$comando_sql = null;
		$execucao = null;
		//definindo qual comando sql será usado e inserindo valores dos parâmetros nos parâmetros do comando SQL
		if(checar_se_produto_existe($produto) == 0){
			$comando_sql = "INSERT INTO estoque (produto, cor, tamanho, deposito, data_disponibilidade, quantidade) VALUES (:produto , :cor , :tamanho , :deposito , :data_disponibilidade , :quantidade );";
			$execucao = $PDO->prepare($comando_sql);
		}
		//o parâmetro "$novo_produto" só será utilizado quando houver edição de um novo produto
		else{
			$comando_sql = "UPDATE estoque SET produto = :novo_produto , cor = :cor , tamanho = :tamanho , deposito = :deposito , data_disponibilidade = :data_disponibilidade , quantidade = :quantidade WHERE produto = :produto";
			$execucao = $PDO->prepare($comando_sql);
			$execucao->bindParam(':novo_produto', $novo_produto );
		}
		$execucao->bindParam(':produto', $produto );
		$execucao->bindParam(':cor', $cor );
		$execucao->bindParam(':tamanho', $tamanho );
		$execucao->bindParam(':deposito', $deposito );
		$execucao->bindParam(':data_disponibilidade', $data_disponibilidade );
		$execucao->bindParam(':quantidade', $quantidade );	
		//passando valores dos argumentos da função para os parâmetros do comando SQL
		//execução do comando
		$resultado = $execucao->execute();
		//mostrar mensagem de erro caso tenha ocorrido um erro durante a execução do comando SQL
		if (! $resultado ) {
			var_dump( $execucao->errorInfo() );
			exit;
		}		
		echo "produto inserido/alterado!";
	}

	inserir_ou_alterar_produto("20.01.0419", "11.22.3333", "01", "GG", "DEP5", "2022-10-05", 15);
?>
</body>
</html>