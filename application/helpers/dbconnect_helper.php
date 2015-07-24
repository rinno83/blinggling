<?php 
	function mongodb_connection($db, $collection)
	{
		$result_db = array();
		
		$result_db['mongodb_client'] = new MongoClient();
		$result_db['mongodb_database'] = $result_db['mongodb_client']->$db;
		$result_db['mongodb_collection'] = $result_db['mongodb_database']->$collection;
		
		return $result_db;
	}
	
	function mongodb_disconnection($db)
	{
		$db['mongodb_client']->close();
	}	
?>