<?php

	//Parametro recebido do javascript
	$coluna = $_POST['coluna']; //Onde pesquisar (coluna do banco) 

	//Estabelece conexão com o banco
	try {
		$connect = new PDO('mysql:host=127.0.0.2;dbname=dados_municipios;charset=utf8mb4', 'user', 'user');
	}catch(PDOException $ex) {
		echo ($ex->getMessage());
	}

	//Consulta o banco
	$sql = 'SELECT COD_IBGE, NOME_MUNICIP, UF, REGIAO, ' . $coluna .' FROM municipios_2015';  // Querry
	$db = $connect->prepare($sql); 			//Prepara a consulta 
	$db->execute();							//Executa a consulta 


	//Envia os resultados da consulta para o javascript que chammou
	while ( $row = $db->fetch() ) {
	  	echo $row['COD_IBGE'] . '#' . $row['NOME_MUNICIP'] . '#' . $row['UF'] . '#' . $row['REGIAO'] . '#' . $row[ ($coluna) ] . '#';
	}
?>