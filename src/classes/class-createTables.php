<?php

/**
* Class to create and reference db tables
*/

class createTables
{
	/**
	 * $db the db connections
	 * @var object
	 */
	protected $db;

	/**
	 * $tables static array the holds the current created tables
	 * @var array
	 */
	private static $tables;

	function __construct($name = '', $sql = '')
	{
		// gain access to slim phps app container
		global $app;

		// check if the static tables variable has been set yet
		if (!isset(self::$tables)) {
			self::$tables = [];
		}

		// get and set the db connection
		$this->db = $app->getContainer()->get('db');

		// check if we are creating a new table
		if (!empty($name) && !empty($sql)) {

			$this->add($name, $sql);

			
		}
		// run the setup of the tables
		$this->setup();


	}
	/**
	 * add add a new table
	 * @param string $name the name of the table
	 * @param string $sql  the sql query
	 */
	private function add($name = '', $sql = ''){
		// add to the array of tables
		self::$tables[$name] = [
			'sql' => $sql,
			'exists' => $this->exists($name),
		];

	}
	/**
	 * setup run the sql queries of the tables in the array
	 * @return void
	 */
	private function setup(){

		foreach(self::$tables as $name => $sql_data){
			// only prepare the sql query if the table does not exist
			if ($sql_data['exists'] === false) {

				$query = $this->db->prepare($sql_data['sql']);

				$query->execute();

			}
		}
	}

	/**
	 * exists check if a table exists in the main db
	 * @param  string $table [description]
	 * @return [type]        [description]
	 */
	private function exists($table = '') {
		// get the config for the db
		$secrets = json_decode(file_get_contents(__DIR__ . '../../secrets.json'));

		$db_config = $secrets->db;

		$sql = "
			SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES 
			WHERE TABLE_NAME = '$table'
			AND TABLE_SCHEMA = '$db_config->dbname'
			";

		$query = $this->db->prepare($sql);

		$query->execute();

		$result = $query->fetchAll()[0];

		// if anything is returned we can assume that the table exists
		if ($result['count'] >= 1) {
			return true;
		}
		else{
			return false;
		}

	}

	/**
	 * getTables get the current tables
	 * @return array | bool 
	 */
	public function getTables(){

		if (isset(self::$tables)) {

			return self::$tables;

		}
		else{

			return false;

		}
	}

}