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


// Select classes

function get_type_list($mtid=null, $dval=null)
{
	// Return a flattened list of type names and ids
	if (!in_array($mtid, array(1, 2) ) ) {$mtid = 1;}
	if (!check_index($dval) ) {$dval = null;}
	
	$query = "Select id, 
		type_name, 
		? 
	From types 
	Where meta_type_id = ? 
	Order by id";
		
	return col_pattern($query, 'ii', array($dval, $mtid), array('id', 'name', 'dval'), true);
	}
	
//________________________________________________________________________________________
function get_todo_type_list()
{
	// Return a flattened list of todo type names and ids

	return get_type_list(1, 1);
	}
	
//________________________________________________________________________________________
function get_status_type_list()
{
	// Return a flattened list of status type names and ids

	return get_type_list(2, 7);
	}
	
//________________________________________________________________________________________
function get_cur_date()
{
	// Return the current date
	$query = "Select Date_Format(now(), '%Y-%m-%d')";
	
	return row_pattern($query, null, null, array('curdate') );
	}
	
//________________________________________________________________________________________
function get_cur_todo_list($id=null)
{
	// Retrieve the last 50 uncompleted to-do entries
	$i = '';
	$input = array();
	
	if (check_index($id) )
	{
		$i .= 'i';
		$input[] = $id;
		
		$extra = "(comp_date IS NULL and 
		note IS Not NULL) or 
		id = ? ";
		}
	else
	{
		$extra = "comp_date IS NULL and 
		note IS Not NULL";
		}

	$query = "Select id, 
		type_id, 
		note, 
		crn_date, 
		dl_date, 
		comp_date, 
		status_type_id, 
		Date_Format(crn_date, '%Y-%m-%d') as crn, 
		(Select type_name From types Where id = type_id Limit 1) as todo_type, 
		(Select type_name From types Where id = status_type_id Limit 1) as status_type 
	From todolist 
	Where $extra 
	Order By crn_date 
	Limit 50";
	
	return tcol_pattern($query, $i, $input, array('note_id', 'type_id', 'note', 'crn_date', 'dl_date', 'comp_date', 'status_type_id', 'crn', 'todo_type', 'status_type') );
	} # End of get_cur_todo_list
		
//________________________________________________________________________________________
function get_todo_list_for_new_dropdown($id=null)
{
	// Get the current todos for the new diary entry dropdown
	$output = array();
	$output['note_id'] = '0__r__';
	$output['note'] = "Not on To-Do List__r__";
	
	$list = get_cur_todo_list($id);
	
	if (isset($list) && is_array($list) && count($list) > 0)
	{
		foreach ($list as $l=>$entry)
		{
			$output['note_id'] .= $entry['note_id'] . '__r__';
			$output['note'] .= (mb_strlen($entry['note'], 'UTF-8') > 100) ? mb_substr($entry['note'], 0, 100, 'UTF-8') . '...' . '__r__' : $entry['note'] . '__r__';
			}	# End of foreach
		}	# End of isset and is_array check
		
	$output['note_id'] = mb_substr($output['note_id'], 0, -5, 'UTF-8');
	$output['note'] = mb_substr($output['note'], 0, -5, 'UTF-8');

	return $output;
	}	# End of get_todo_list_for_new_dropdown
	
//________________________________________________________________________________________
function get_cur_diary_list($id=null)
{
	// Retrieve the last 50 uncompleted to-do entries
	$query = "Select id, 
		note, 
		crn_date, 
		Date_Format(crn_date, '%Y-%m-%d') as crn, 
		todo_id 
	From diary 
	Where note Is Not Null 
	Order By crn_date 
	Limit 50";
	
	return tcol_pattern($query, null, null, array('note_id', 'note', 'crn_date', 'crn', 'todo_id') );
	} # End of get_cur_todo_list
		
//________________________________________________________________________________________





?>