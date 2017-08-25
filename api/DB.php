<?php

/*
 * DB Class
 * This class is used for database related (connect, get, insert, update, and delete) operations
 * with PHP Data Objects (PDO)
 */

class DB {

    // Database credentials
    private $dbHost = 'localhost';
    private $dbUsername = 'root';
    private $dbPassword = 'root';
    private $dbName = 'vuejs';
    public $db;

    /*
     * Connect to the database and return db connecction
     */

    public function __construct() {
	if (!isset($this->db)) {
	    // Connect to the database
	    try {
		$conn = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUsername, $this->dbPassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db = $conn;
	    } catch (PDOException $e) {
		die("Failed to connect with MySQL: " . $e->getMessage());
	    }
	}
    }

    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */

    public function getRows($table, $conditions = array()) {
	$sql = 'SELECT ';
	$sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
	$sql .= ' FROM ' . $table;
	if (array_key_exists("where", $conditions)) {
	    $sql .= ' WHERE ';
	    $i = 0;
	    foreach ($conditions['where'] as $key => $value) {
		$pre = ($i > 0) ? ' AND ' : '';
		$sql .= $pre . $key . " = '" . $value . "'";
		$i++;
	    }
	}

	if (array_key_exists("order_by", $conditions)) {
	    $sql .= ' ORDER BY ' . $conditions['order_by'];
	}

	if (array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
	    $sql .= ' LIMIT ' . $conditions['start'] . ',' . $conditions['limit'];
	} elseif (!array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
	    $sql .= ' LIMIT ' . $conditions['limit'];
	}
	$query = $this->db->prepare($sql);
	$query->execute();

	if (array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all') {
	    switch ($conditions['return_type']) {
		case 'count':
		    $data = $query->rowCount();
		    break;
		case 'single':
		    $data = $query->fetch(PDO::FETCH_ASSOC);
		    break;
		default:
		    $data = '';
	    }
	} else {
	    if ($query->rowCount() > 0) {
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
	    }
	}
	return !empty($data) ? $data : false;
    }
    
     public function login($table, $conditions = array()) {
	$sql = 'SELECT ';
	$sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
	$sql .= ' FROM ' . $table;
	if (array_key_exists("where", $conditions)) {
	    $sql .= ' WHERE ';
	    $i = 0;
	    foreach ($conditions['where'] as $key => $value) {
		$pre = ($i > 0) ? ' AND ' : '';
		$sql .= $pre . $key . " = '" . $value . "'";
		$i++;
	    }
	}

	if (array_key_exists("order_by", $conditions)) {
	    $sql .= ' ORDER BY ' . $conditions['order_by'];
	}

	if (array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
	    $sql .= ' LIMIT ' . $conditions['start'] . ',' . $conditions['limit'];
	} elseif (!array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
	    $sql .= ' LIMIT ' . $conditions['limit'];
	}
	$query = $this->db->prepare($sql);
	$query->execute();

	$data = $query->fetchColumn();
	return !empty($data) ? $data : false;
    }


}