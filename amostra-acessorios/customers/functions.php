<?php
require_once('../config.php');
require_once(DBAPI);
$credores = null;
$credor = null;
/**
 *  Listagem de Clientes
 */
function index() {
	global $credores;
	$credores = find_all('credor');
}

/**
 *  Cadastro de Clientes
 */
function add() {
	if (!empty($_POST['credor'])) {
	  
	  $today = 
		date_create('now', new DateTimeZone('America/Sao_Paulo'));
	  $credor = $_POST['credor'];
	  $credor['modified'] = $credor['created'] = $today->format("Y-m-d H:i:s");
	  
	  save('credores', $credor);
	  header('location: index.php');
	}
  }
/**
*  Insere um registro no BD
*/
function save($table = null, $data = null) {
	$database = open_database();
	$columns = null;
	$values = null;
	//print_r($data);
	foreach ($data as $key => $value) {
	  $columns .= trim($key, "'") . ",";
	  $values .= "'$value',";
	}
	// remove a ultima virgula
	$columns = rtrim($columns, ',');
	$values = rtrim($values, ',');
	
	$sql = "INSERT INTO " . $table . "($columns)" . " VALUES " . "($values);";
	try {
	  $database->query($sql);
	  $_SESSION['message'] = 'Registro cadastrado com sucesso.';
	  $_SESSION['type'] = 'success';
	
	} catch (Exception $e) { 
	
	  $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
	  $_SESSION['type'] = 'danger';
	} 
	close_database($database);
  }

/**
 *	Atualizacao/Edicao de Cliente
 */
function edit() {
	$now = date_create('now', new DateTimeZone('America/Sao_Paulo'));
	if (isset($_GET['id'])) {
	  $id = $_GET['id'];
	  if (isset($_POST['customer'])) {
		$customer = $_POST['customer'];
		$customer['modified'] = $now->format("Y-m-d H:i:s");
		update('customers', $id, $customer);
		header('location: index.php');
	  } else {
		global $customer;
		$customer = find('customers', $id);
	  } 
	} else {
	  header('location: index.php');
	}
  }
