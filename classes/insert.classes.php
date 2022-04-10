<?php
/*
Copyright 2011-2022 Cargotrader, Inc. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are
permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice, this list of
      conditions and the following disclaimer.

   2. Redistributions in binary form must reproduce the above copyright notice, this list
      of conditions and the following disclaimer in the documentation and/or other materials
      provided with the distribution.

THIS SOFTWARE IS PROVIDED BY Cargotrader, Inc. ''AS IS'' AND ANY EXPRESS OR IMPLIED
WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL Cargotrader, Inc. OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those of the
authors and should not be interpreted as representing official policies, either expressed
or implied, of Cargotrader, Inc.
* 
* While functional, this website code is not documented sufficiently to explain to how it can be used outside
* this demonstration website, or for purposes intended or not along such lines, or to further enhance this
* website.  The website itself, while functional, is built for demonstration purposes only and will probably be 
* inadequate to address any practical need.
* 
*/

// Classes for inserting records into misc tables.
// This assumes that the mysqli class is already loaded.

class insert_into_table
{
	private $query;
	private $validate_string;
	private $field_size = array();
	private $mysqli_obj;
	public $values = array();
	public $exec_bool = false;
	public $new_id = -1;

	function __construct($table_name, $value_array=array(), $execute=true)
	{
		if (!in_array($table_name, array('diary', 'meta_types', 'todolist', 'types') ) )
			{return 'table name error';}
		$fnc_name = "setup_$table_name";
		$this->$fnc_name();

		if (!is_array($value_array) ) {return 'value array not an array';}

		if (strlen($this->validate_string) != count($value_array) ) {return 'value array size error';}

		$type_validate = $this->validate_string;

		foreach ($this->field_size as $key => $val)
		{
			if ($type_validate[$key] == 's') 
				{$value_array[$key] = $this->prepstr($value_array[$key], $val);}
			elseif ($type_validate[$key] == 'd')
				{$value_array[$key] = (float) $value_array[$key];}
			elseif ($type_validate[$key] == 'i')
				{$value_array[$key] = (int) $value_array[$key];}
			}

		$this->values = $value_array;

		$this->mysqli_obj = new mysqli_obj('add');
		$this->mysqli_obj->auto_init_p_and_b($this->query, $this->validate_string, $this->values);

		if ($execute) {$this->ex($table_name);}
		}	# End of construct function

	public function ex($table_name)
	{
		$this->mysqli_obj->e();

		if ($this->mysqli_obj->mysqli_exec_row_count > 0)
		{
			$this->new_id = $this->mysqli_obj->mysqli_insert_id;
			$this->exec_bool = true;
			unset($this->mysqli_obj); 
			return 1;
			}
		else
			{unset($this->mysqli_obj); return 0;}

		}	# End of the ex for execute function

	public function bind($value_array=array() )
	{
		if (!is_array($value_array) ) {return 'value array not an array';}

		if (strlen($this->validate_string) != count($value_array) ) {return 'value array size error';}

		$type_validate = $this->validate_string;

		foreach ($this->field_size as $key => $val)
		{
			if ($type_validate[$key] == 's') 
				{$value_array[$key] = $this->prepstr($value_array[$key], $val);}
			elseif ($type_validate[$key] == 'd')
				{$value_array[$key] = (float) $value_array[$key];}
			elseif ($type_validate[$key] == 'i')
				{$value_array[$key] = (int) $value_array[$key];}
			}

		$this->values = $value_array;

		$this->mysqli_obj->rebind($value_array);
		}	# End of the bind function

	private function prepstr($input, $length=null)
	{
		if ($length !== NULL)
			{return htmlentities(mb_substr(trim($input), 0, $length), ENT_QUOTES, "UTF-8", false);}
		else
			{return htmlentities(trim($input), ENT_QUOTES, "UTF-8", false);}
		}	# End of prepstr function

	private function setup_diary()
	{
		$this->query = "Insert Ignore Into diary (note, crn_date) 
					Values (?, now() )";

		$this->validate_string = "s";
		$this->field_size = array(null);
		} # End of function setup_cats

	private function setup_meta_types()
	{
		$this->query = "Insert Ignore Into meta_types (meta_type_name, 
						meta_type_dsr) 
					Values (?, ?)";

		$this->validate_string = "ss";
		$this->field_size = array(63, 512);
		} # End of function setup_prods

	private function setup_todolist()
	{
		$this->query = "Insert Ignore Into todolist (type_id, 
						crn_date, 
						status_type_id) 
					Values (?, now(), ?)";
		$this->validate_string = "ii";
		$this->field_size = array(null, null);
		} # End of function setup_pts

	private function setup_types()
	{
		$this->query = "Insert Ignore Into types (meta_type_id, 
						type_name, 
						type_dsr) 
					Values (?, ?, ?)";
		$this->validate_string = "iss";
		$this->field_size = array(null, 63, 512);
		} # End of function setup_parts

	}	# End of insert_into_table class

//_______________________________________________________________________________________________
									## Useful Functions ##
//_______________________________________________________________________________________________
function insert_into_something($table_name, $value_array=array(), $execute=true)
{
	if (!is_array($value_array) ) {return 'value array not an array';}

	$obj = new insert_into_table($table_name, $value_array, $execute);

	$new_id = $obj->new_id;

	unset($obj);
	
	return $new_id;
	}

//_______________________________________________________________________________________________
									## Useful First Order Variables ##
//_______________________________________________________________________________________________

	$iis = 'insert_into_something';
	$iit = 'insert_into_table';		# Class call only
?>
