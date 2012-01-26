<?php
class MySQL
{
	var $host;
	var $database;
	var $user;
	var $pass;
	
	var $handle;
	var $result;
	
	public function __construct()
	{
		switch( $_SERVER['SERVER_NAME'] )
		{
			default:
			case "localhost":
				$this->host 	= "localhost";
				$this->database = "";
				$this->user 	= "";
				$this->pass 	= "";
			break;
		}
		
		$this->connect();
		$this->select_db();
	}
	
	// CORE
	public function connect()
	{
		$this->handle = @mysql_connect( $this->host, $this->user, $this->pass ) or $this->error( mysql_error() );
	}
	
	public function select_db()
	{
		@mysql_select_db( $this->database ) or $this->error( mysql_error() );
	}
	
	public function query($query)
	{
		$this->result = @mysql_query($query);
		if (mysql_errno()) {
		  $error = "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$query\n<br>";
		  print_r($error);
		 } 
		return $this->last_id();
	}	
	
	public function result()
	{
		return $this->result;
	}
	
	public function results()
	{
		$results = array();
        while( $item = @mysql_fetch_object( $this->result ) ) array_push( $results, $item );
		return $results;
	}	
	
	public function singleResult()
	{
		$results = $this->results();
		if( sizeof($results)>0 )
			return $results[0];		
		else
			return false;
	}
	
	public function num_rows()
	{
		return @mysql_num_rows( $this->result ); 
	}
	
	public function affected_rows()
	{
		return @mysql_affected_rows(); 
	}
	
	public function modified_rows() 
	{
        $info_str = mysql_info();
        $a_rows = @mysql_affected_rows();
        ereg("Rows matched: ([0-9]*)", $info_str, $r_matched);
        return ($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows;
    }
	
	public function last_id()
	{
		return @mysql_insert_id();
	}	
	
	public function info()
	{
		return @mysql_info(); 
	}	
	
	public function close()
	{
		@mysql_close( $this->handle );
	}
	
	public function error( $error )
	{
		echo $error;
		exit();
	}
	
	// HELPERS
	public function select( $table, $where = NULL )
	{
		$query = ( is_null( $where ) ) ? "SELECT * FROM $table WHERE active='1'" : "SELECT * FROM $table WHERE active='1' and $where";
		$this->query( $query );
	}
	
	
	public function select_all( $table, $where = NULL )
	{
		$query = ( is_null( $where ) ) ? "SELECT * FROM $table WHERE 1" : "SELECT * FROM $table WHERE $where";
		$this->query( $query );
	}
	
	public function insert( $table, $data, $password_field = NULL ) 
	{
		foreach( $data as $field => $value ) 
		{
			$fields[] = '`' . $field . '`';
			if ( $field == $password_field )
				$values[] = "PASSWORD('" . mysql_real_escape_string($value) . "')";
			else
				$values[] = "'" . mysql_real_escape_string($value) . "'";
		}
		$field_list = join( ',', $fields );
		$value_list = join( ', ', $values );
		$query = "INSERT INTO `" . $table . "` (" . $field_list . ") VALUES (" . $value_list . ")";
		return $this->query( $query );
	}

	
	public function update($table, $data, $id_field, $id_value) 
	{
		foreach ($data as $field => $value) $fields[] = sprintf("`%s` = '%s'", $field, mysql_real_escape_string($value));
		$field_list = join(',', $fields);
		$query = sprintf("UPDATE `%s` SET %s WHERE `%s` = %s", $table, $field_list, $id_field, intval($id_value));
		$this->query( $query );
	}
	
	public function destroy( $table, $where = NULL )
	{
		if( $where != NULL && $where!="1" )
		{
			$query = "DELETE FROM $table WHERE $where";
			$this->query( $query );
		}
	}
	
	public function delete($table, $id_field, $id_value) 
	{
		$query = "UPDATE $table SET active='0' WHERE $id_field='$id_value'";
		$this->query( $query );
	}
	
	public function row_counter( $table, $where = NULL )
	{
		$query = ( is_null( $where ) ) ? "SELECT * FROM $table WHERE active='1'" : "SELECT * FROM $table WHERE active='1' and $where";
		return @mysql_num_rows( @mysql_query( $query ) );
	}
	
	public function self_query( $query )
	{
		return @mysql_query( $query );
	}
	
	public function results_table($columns,$actions=NULL)
	{
		$table  = '<table class="sortableTable" width="100%" border="0">'."\n";
		$table .= '	<thead>'."\n";
		$table .= '		<tr>'."\n";
		
		foreach( $columns as $column => $column_name ) 
			$table .= '<th scope="col">'.$column_name.'</th>'."\n";
			
		if( !is_null( $actions ) )	$table .= '<th scope="col" align="center">Actions</th>'."\n";
		
		$table .= '		</tr>'."\n";
		$table .= '	</thead>'."\n";
		$table .= '	<tbody>'."\n";
		
		foreach( $this->results() as $row => $row_contents )
		{
			$table .= '		<tr>'."\n";
			foreach( $columns as $column => $column_name )
			{
				$value = $row_contents->$column;
				$table .= '			<td>'.$value.'</td>'."\n";
			}
			if( !is_null( $actions ) )
			{
				$table .= '			<td align="center">';	
				$i = 0;
				foreach( $actions as $action => $action_object )
				{
					$i++;
					$action_text = $action_object->text;
					$action_url = $action_object->url;
					$action_db_ref = $action_object->db_ref;
					$table .= '<a href="'.$action_url.$row_contents->$action_db_ref.'">'.$action_text.'</a>';
					$table .= ( $i == sizeof($actions) ) ? "" : " | ";
					

				}
				$table .= '</td>'."\n";				
			}
			$table .= '		</tr>'."\n";  
		}
		
		$table .= '	</tbody>'."\n";
		$table .= '</table>'."\n";
		
		return $table;
	}

	public function safe($value)
	{
		return mysql_real_escape_string($value);
	}
	
}
?>