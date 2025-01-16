<?php
/*
Copyright 2011-2024 Cargotrader, Inc. All rights reserved.

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

// Classes/functions for updating single fields in tables that match the template Update $table_name Set $field_name = ? Where glb_id = ?
// Functions exist for special cases

class upd_with_id
{
	public $output = true;
	protected $d = '/^(20[0-3][0-9])-([0-9]|0[0-9]|1[0-2])-([1-9]|[012][0-9]|3[01])([T|\s])([01][0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]{1}))?$/';

	public function prepstr($input, $length=null, $extra=null)
	{
		$extra = (in_array($extra, array('bypass', 'special') ) ) ? $extra : null;

		if ($extra != 'bypass')
		{
			if ($length !== NULL && is_numeric($length) && $length > 0)
			{
				if ($extra == 'special')
					{return htmlspecialchars(mb_substr(trim($input), 0, $length), ENT_QUOTES | ENT_HTML5, "UTF-8", false);	}
				else
					{return htmlentities(mb_substr(trim($input), 0, $length), ENT_QUOTES | ENT_HTML5, "UTF-8", false);	}
				}
			else
			{
				if ($extra == 'special')
					{return htmlspecialchars(trim($input), ENT_QUOTES | ENT_HTML5, "UTF-8", false);}
				else
					{return htmlentities(trim($input), ENT_QUOTES | ENT_HTML5, "UTF-8", false);}
				}
			}
		else
			{return $input;}
		}	# End of prepstr function

	public function regex_chk($new_value, $regexp)
	{
		if ($regexp == 'email' || $regexp == 'EMAIL')
		{
			if (!filter_var($new_value, FILTER_VALIDATE_EMAIL) )
				{return 0;}	# End of email check 
			}
		elseif ($regexp == 'int' || $regexp == 'INT')
		{
			if (!filter_var($new_value, FILTER_VALIDATE_INT) )
				{return 0;}	# End of int check 
			}
		elseif ($regexp == 'float' || $regexp == 'FLOAT')
		{
			if (!filter_var($new_value, FILTER_VALIDATE_FLOAT) )
				{return 0;}	# End of float check 
			}
		elseif ($regexp == 'bool' || $regexp == 'BOOL')
		{
			if (!filter_var($new_value, FILTER_VALIDATE_BOOLEAN) )
				{return 0;}	# End of bool check 
			}
		elseif ($regexp == 'url' || $regexp == 'URL')
		{
			if (substr($new_value, 0, 7) !== 'http://' && substr($new_value, 0, 8) !== 'https://') {$str_start = 'http://';} else {$str_start = '';}
			if (!filter_var($str_start . $new_value, FILTER_VALIDATE_URL) )
				{return 0;}	# End of url check 
			}
		elseif ($regexp == 'ip' || $regexp == 'IP')
		{
			if (!filter_var($new_value, FILTER_VALIDATE_IP) )
				{return 0;}	# End of url check 
			}
		elseif (!empty($regexp) )
		{
			if (!filter_var($new_value, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$regexp ) ) ) )
				{return 0;}	# End of regex check 
			}
		return 1;
		}	# End of function regex_chk

	// If a field needs to be unique for the whole database we check that here by counting existing records of the same value
	public function unique_fld_chk($field_name, $table_name, $field_type, $field_len, $new_value, $rec_id)
	{
		$unique_sql = "Select count(id) From $table_name Where $field_name = ? and id != ?";

		$count = row_pattern($unique_sql, "{$field_type}i", array($this->prepstr($new_value, $field_len), $rec_id), array('ANY_ID_COUNT') );
		
		if ($count > 0)
			{return 0;}
		else
			{return 1;}
		}	# End of unique_fld_chk

	public function update_field($table_name=null, $field_name=null, $field_type=null, $field_len=null, $new_value=null, $rec_id=null, $field_null=null, $extra=null)
	{
		$update_sql = "Update $table_name Set $field_name = ? Where id = ?";

		if ($field_type != 'd' && $field_type != 'i')
			{$new_value = $this->prepstr($new_value, $field_len, $extra);}

		$input = array($new_value, $rec_id);
		$field_types = "{$field_type}i";

		if ($field_null == 1 && ($new_value === "" || $new_value === NULL) )
		{
			$input = array($rec_id);
			$update_sql = "Update $table_name Set $field_name = NULL Where id = ?";
			$new_value = null;
			$field_types = 'i';
			}

		return ins_pattern($update_sql, $field_types, $input);
		}	# End  of update_field
	}	# End of upd_with_id class

//_______________________________________________________________________________________________
class update_standard extends upd_with_id
{
	function std_update($table_name, $fields=array(), $field_len=array(), $field_type="", $field_regex=array(), $field_null="", $field_name, $new_value, $rec_id, $extra=null)
	{
		if (!in_array($field_name, $fields) ) 
			{$this->output = 'error'; return 'error';}

		if ($rec_id === NULL || $this->regex_chk($rec_id, 'int') == 0)
			{$this->output = 'error'; return 'error';}

		$key = array_search($field_name, $fields);
		$field_types = explode(' ', $field_type);
		$field_nulls = explode(' ', $field_null);

		if ( ($new_value === NULL || $new_value === "") && $field_nulls[$key] != 1 && $field_regex[$key] == 'bool')
			{$new_value = 0;}

		if ($new_value === NULL && $field_nulls[$key] != 1)
			{$this->output = 'error'; return 'error';}

		if ($new_value === "" && $field_nulls[$key] != 1)
			{$this->output = 'error'; return 'error';}

		if ($new_value !== NULL && $new_value != "" && $field_regex[$key] !== NULL && $this->regex_chk($new_value, $field_regex[$key]) == 0)
			{$this->output = 'regex error'; return 'regex error';}

		// Now we can update the field in question
		$this->output = $this->update_field($table_name, $field_name, $field_types[$key], $field_len[$key], $new_value, $rec_id, $field_nulls[$key], $extra);
		}	# End of std_update function
	}	# End of update_standard class

//_______________________________________________________________________________________________
class update_diary extends update_standard
{
	function __construct($field_name, $new_value, $rec_id, $extra = null)
	{
		$table_name = "diary";
		$fields = array('note', 'crn_date', 'todo_id');
		$field_len = array(null, null, null);
		$field_type = "s s i";
		$field_regex = array(null, null, 'int');
		$field_null = "1 0 1";

		if ($field_name == 'todo_id' && $new_value == 0) {$new_value = null;}
		
		$this->std_update($table_name, $fields, $field_len, $field_type, $field_regex, $field_null, $field_name, $new_value, $rec_id, $extra);
		}	# End of the construct function
	}	# End of class update_diary

//_______________________________________________________________________________________________
class update_meta_types extends update_standard
{
	function __construct($field_name, $new_value, $rec_id, $extra = null)
	{
		$table_name = "meta_types";
		$fields = array('meta_type_name', 'meta_type_dsr');
		$field_len = array(63, 512);
		$field_type = "s s";
		$field_regex = array(null, null);
		$field_null = "0 0";

		$this->std_update($table_name, $fields, $field_len, $field_type, $field_regex, $field_null, $field_name, $new_value, $rec_id, $extra);
		}	# End of the construct function
	}	# End of class update_meta_types

//_______________________________________________________________________________________________
class update_todolist extends update_standard
{
	function __construct($field_name, $new_value, $rec_id, $extra = null)
	{
		$table_name = "todolist";
		$fields = array('type_id', 'note', 'crn_date', 'dl_date', 'comp_date', 'status_type_id');
		$field_len = array(null, null, null, null, null, null);
		$field_type = "i s s s s i";
		$field_regex = array('int', null, null, null, null, 'int');
		$field_null = "0 1 0 1 1 0";

		$this->std_update($table_name, $fields, $field_len, $field_type, $field_regex, $field_null, $field_name, $new_value, $rec_id, $extra);
		}	# End of the construct function
	}	# End of class update_todolist

//_______________________________________________________________________________________________
class update_types extends update_standard
{
	function __construct($field_name, $new_value, $rec_id, $extra = null)
	{
		$table_name = "types";
		$fields = array('meta_type_id', 'type_name', 'type_dsr', 'spc_ord', 'act_bit');
		$field_len = array(null, 63, 512);
		$field_type = "i s s i i";
		$field_regex = array(null, null, null, 'int', 'bool');
		$field_null = "0 0 0 1 0";

		$this->std_update($table_name, $fields, $field_len, $field_type, $field_regex, $field_null, $field_name, $new_value, $rec_id, $extra);
		}	# End of the construct function
	}	# End of class update_types

//_______________________________________________________________________________________________
//_______________________________________________________________________________________________
//_______________________________________________________________________________________________
												## Useful Functions ##
//_______________________________________________________________________________________________
function update_fields($table, $field_name, $new_value, $rec_id=0, $extra=null)
{
	if (!in_array($table, array('diary', 'meta_types', 'todolist', 'types') ) ) 
		{return "Table Name Error";}

	// You can update a single field in a record, or multiple fields in a record, but not multiple records

	$table = 'update_' . $table;
		
	if (is_array($field_name) && is_array($new_value) && count($field_name) == count($new_value) )
	{
		$output = array();

		foreach ($field_name as $key => $val)
		{
			$obj = new $table($val, $new_value[$key], $rec_id, $extra);

			$output[] = $obj->output;

			unset($obj);
			}
		}
	elseif (!is_array($field_name) && !is_array($new_value) )
	{
		$obj = new $table($field_name, $new_value, $rec_id, $extra);

		$output = $obj->output;

		unset($obj);
		}
	elseif (!is_array($field_name) && $new_value === NULL)
	{
		$obj = new $table($field_name, $new_value, $rec_id, $extra);

		$output = $obj->output;

		unset($obj);
		}
	else
		{$output = "config error";}

	return $output;
	}	# End of update_fields function

//_______________________________________________________________________________________________
function chk_unique_flds($field_name, $table, $field_type, $field_len, $new_value, $glb_id=0)
{
	// Use this to determine if a unique field is indeed unique (use $glb_id = 0 on insert)

	if (	is_array($field_name) && is_array($new_value) && is_array($field_type) && is_array($field_len) && 
		count($field_name) == count($new_value) && 
		count($field_name) == count($field_type) && 
		count($field_name) == count($field_len) )
	{
		$output = array();

		foreach ($field_name as $key => $val)
		{
			$obj = new upd_with_id();

			$output[] = $obj->unique_fld_chk($val, $table, $field_type[$key], $field_len[$key], $new_value[$key], $glb_id);

			unset($obj);
			}
		}
	elseif (!is_array($field_name) && !is_array($new_value)  && !is_array($field_type) )
	{
		$obj = new upd_with_id();

		$output = $obj->unique_fld_chk($field_name, $table, $field_type, $field_len, $new_value, $glb_id);

		unset($obj);
		}
	else
		{$output = "error";}

	return $output;
	}	# End of chk_unique_flds function

//_______________________________________________________________________________________________
//_______________________________________________________________________________________________
									## Useful First Order Variables ##
//_______________________________________________________________________________________________
	$uf = 'update_fields';
	$cuf = 'chk_unique_flds';

?>
