<?php
/**
* @Author: Rehmat Alam
* @Date:   2017-05-23 02:17:58
* @Last Modified by:   Rehmat Alam
* @Last Modified time: 2017-05-23 02:17:58
*/

class DB {

	private $_pdo, $_query, $_count = 0, $_results = array(), $_lastID = NULL, $_errors = array(), $dbuser, $dbname, $dbpass, $dbhost;

	private static $_instance = NULL;

	public function __construct($con_data = array()) {

		$req_keys = array('dbname', 'dbuser', 'dbpass', 'dbhost');
		foreach($req_keys as $key) {
			if(!is_array($con_data)) {
				die('Connection data is invalid');
			} else if(!array_key_exists($key, $con_data)) {
				die($key . ' not found in the connection data');
			}
		}
		$this->dbhost = $con_data['dbhost'];
		$this->dbname = $con_data['dbname'];
		$this->dbuser = $con_data['dbuser'];
		$this->dbpass = $con_data['dbpass'];

		try {
			
			$this->_pdo = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', $this->dbuser, $this->dbpass);
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		} catch(PDOException $e) {

			$this->_errors[] = $e->getMessage();

			die('Failed connecting to database');

		}

	}

	public function transaction() {

		$this->_pdo->beginTransaction();

		return $this;

	}

	public function roll() {

		$this->_pdo->rollBack();

		return $this;

	}

	public function commit() {

		$this->_pdo->commit();

		return $this;

	}

	public function query($query, $params = array()) {

		$this->_query = NULL;
		$this->_results = NULL;
		$this->_count = 0;
		$this->_lastID = NULL;

		try {

			$this->_query = $this->_pdo->prepare($query);

			if(count($params)) {

				$i = 1;

				foreach($params AS $param) {

					$this->_query->bindValue($i, $param);

					$i++;

				}

			}

			$this->_query->execute();

			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);

			$this->_count = $this->_query->rowCount();

			$this->_lastID = $this->_pdo->lastInsertID();

		} catch(PDOException $e) {

			$this->_errors[] = $e->getMessage();

		}

		return $this;

	}

	private function action($action, $table, $conditions) {

		if(count($conditions) == 3) {

			$column = $conditions[0];

			$operator = $conditions[1];

			$value = $conditions[2];

			$this->query($action . ' `' . $table . '` WHERE `'.$column.'` ' . $operator . ' ?', array($value));

			return $this;

		}

	}

	public function row($value) {

		return $this->results()[0]->$value;

	}

	public function all($table) {

		return $this->query('SELECT * FROM `' . $table . '`')->results();

	}

	public function insert($table, $values) {

		if(count($values)) {

			$query = 'INSERT INTO `' . $table . '` (';

			$keys = array_keys($values);

			$bind = array_values($values);

			$i = 1;

			foreach($keys AS $key) {

				$query .= '`'.$key.'`';

				if($i < count($keys)) {

					$query .= ', ';

				}

				$i++;

			}

			$query .= ') VALUES(';

			for($i = 1; $i <= count($keys); $i++) {

				$query .= '?';

				if($i < count($keys)) {

					$query .= ', ';

				}

			}

			$query .= ')';

			$this->query($query, $bind);

			return $this;

		}

	}

	public function update($table, $values, $conditions) {

		if(count($values) AND count($conditions)) {

			$query = 'UPDATE `' . $table . '` SET ';

			$fields = array_keys($values);

			$i = 1;

			foreach($fields AS $field) {

				$query .= ' `'. $field . '` = ?';

				if($i < count($fields)) {

					$query .= ', ';

				}

				$i++;

			}

			$query .= ' WHERE `' . $conditions[0] . '` ' . $conditions[1] . ' ?';

			$arr = array_values($values);

			array_push($arr, $conditions[2]);

			$this->query($query, $arr);

			return $this;

		}

	}

	public function get($table, $conditions = NULL) {
		
		if($conditions) {

			$this->action('SELECT * FROM', $table, $conditions);
		
		} else {
			
			$this->query('SELECT * FROM `' . $table . '`');
		
		}

		return $this;

	}

	public function del($table, $conditions) {

		$this->action('DELETE FROM', $table, $conditions);

		return $this;

	}

	public function errors() {

		return $this->_errors;

	}

	public function results() {

		return $this->_results;

	}

	public function row_count() {

		return $this->_count;

	}

	public function last_id() {

		return $this->_lastID;

	}

	public function get_pdo() {

		return $this->_pdo;

	}

}
