<?php 

class DBDriver
{

	public $connection;
	
	public function __construct() {

		$host = gatorconf::get('db_host');
		$username = gatorconf::get('db_username');
		$password = gatorconf::get('db_password');
		$database = gatorconf::get('db_database');

		$this->open($host, $database, $username, $password);
	}

	
	function open($host,$database,$username,$password,$charset='utf8')
	{
		$this->connection = mysqli_connect($host, $username, $password, $database);

		if ( mysqli_connect_errno() )
		{
			echo "Error connecting to database: " . mysqli_connect_error();
			throw new Exception("Error connecting to database: " . mysqli_connect_error());
		}

		if ($charset)
		{
			mysqli_set_charset($this->connection,$charset);
				
			if ( mysqli_connect_errno() )
			{
				echo "Error connecting to database: " . mysqli_connect_error();
				throw new Exception("Error connecting to database: " . mysqli_connect_error());
			}
		}

		return $this->connection;
	}


	function close()
	{
		@mysqli_close($this->connection);
	}


	function query($sql)
	{
		if ( !$rs = @mysqli_query($this->connection,$sql) )
		{
			echo "Mysql error: " . mysqli_error($this->connection);
			throw new Exception(mysqli_error($this->connection));
		}

		return $rs;
	}

	function execute($sql)
	{
		if ( !$result = @mysqli_query($this->connection,$sql) )
		{
			echo "Mysql error: " . mysqli_error($this->connection);
			throw new Exception(mysqli_error($this->connection));
		}

		return mysqli_affected_rows($this->connection);
	}


	function fetchAll($rs)
	{
		$rows = array();
		
		while($row = mysqli_fetch_assoc($rs)) {
			$rows[]=$row;
		}
		
		return $rows;
	}
	
	function fetch($rs)
	{
		return mysqli_fetch_assoc($rs);
	}


	function getLastInsertId()
	{
		return (mysqli_insert_id($this->connection));
	}
	

	function getLastError()
	{
		return mysqli_error($this->connection);
	}

	
	function release($rs)
	{
		mysqli_free_result($rs);
	}
	

	function escape($val)
	{
		return mysqli_real_escape_string($this->connection, $val);
	}

}
