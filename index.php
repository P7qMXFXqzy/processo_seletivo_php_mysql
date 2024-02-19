<!DOCTYPE html>
<html>
<head>
    <title>Processo Seletivo Geovendas</title>
</head>
<body>

<?php
	//definição dos dados para fazer conexão com o banco de dados MySQL
	define( 'MYSQL_HOST', 'localhost' );
	define( 'MYSQL_USER', 'root' );
	define( 'MYSQL_PASSWORD', 'abcd' );
	define( 'MYSQL_DB_NAME', 'processo_seletivo' );
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
	//caso a função "checar_se_produto_existe" retorne um valor false, inserir novo produto no banco de dados. Caso a função retorne um valor true, atualizar produto no banco de dados	
	function inserir_ou_alterar_produto($produto, $cor, $tamanho, $deposito, $data_disponibilidade, $quantidade){
		
	}

	echo (checar_se_produto_existe("11.01.0419"));
?>
</body>
</html>