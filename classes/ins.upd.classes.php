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

// Insert-Update combined classes

function new_todo_entry($inputs=array() )
{
	global $iis, $uf;
	
	// Inputs must be sent in the correct order
	// [0] => type_id
	// [1] => status_id
	// [2] => note
	// [3] => deadline date
	// [4] => completion date
	
	if (is_array($inputs) && count($inputs) > 0)
	{
		$new = $iis('todolist', array($inputs[0], $inputs[1]) );
		$out = (isset($inputs[2]) ) ? $uf('todolist', 'note', $inputs[2], $new) : null;
		$out = (isset($inputs[3]) ) ? $uf('todolist', 'dl_date', $inputs[3], $new) : null;
		$out = (isset($inputs[4]) ) ? $uf('todolist', 'comp_date', $inputs[4], $new) : null;		
		}
	} # End of new_todo_entry
	
//_______________________________________________________________________________________________
function upd_todo_entry($inputs=array() )
{
	global $iis, $uf;
	
	// Inputs must be sent in the correct order
	// [0] => type_id
	// [1] => status_type_id
	// [2] => note
	// [3] => deadline date
	// [4] => completion date
	// [5] => note_id
	
	if (is_array($inputs) && count($inputs) > 0 && check_index($inputs[5]) )
	{
		$id = $inputs[5];
		$out = (isset($inputs[0]) ) ? $uf('todolist', 'type_id', $inputs[0], $id) : null;
		$out = (isset($inputs[1]) ) ? $uf('todolist', 'status_type_id', $inputs[1], $id) : null;
		$out = (isset($inputs[2]) ) ? $uf('todolist', 'note', $inputs[2], $id) : ( (!isset($inputs[2]) || empty($inputs[2]) ) ? $uf('todolist', 'note', null, $id) : null);
		$out = (isset($inputs[3]) ) ? $uf('todolist', 'dl_date', $inputs[3], $id) : ( (!isset($inputs[3]) || empty($inputs[3]) ) ? $uf('todolist', 'dl_date', null, $id) : null);
		$out = (isset($inputs[4]) ) ? $uf('todolist', 'comp_date', $inputs[4], $id) : ( (!isset($inputs[4]) || empty($inputs[4]) ) ? $uf('todolist', 'comp_date', null, $id) : null);		
		}
	} # End of upd_todo_entry
	
//_______________________________________________________________________________________________
function upd_todo_entries($inputs=array() )
{
	process_entries($inputs, 'upd_todo_entry');
	} # End of upd_todo_entries
	
//_______________________________________________________________________________________________
function new_diary_entry($inputs=array() )
{
	global $iis, $uf;
	
	$table = 'diary';
	
	// Inputs must be sent in the correct order
	// [0] => note
	// [1] => todo_id
	
	if (is_array($inputs) && count($inputs) > 0)
	{
		$new = $iis($table, array($inputs[0]) );
		$out = (isset($inputs[1]) ) ? $uf($table, 'todo_id', $inputs[1], $new) : null;
		}
	} # End of new_diary_entry
	
//_______________________________________________________________________________________________
function upd_diary_entry($inputs=array() )
{
	global $iis, $uf, $poa;
	
	$table = 'diary';
	
	// Inputs must be sent in the correct order
	// [0] => note
	// [1] => todo_id
	// [2] => note_id
	
	if (is_array($inputs) && count($inputs) > 0 && check_index($inputs[2]) )
	{
		$id = $inputs[2];
		$out = (isset($inputs[0]) ) ? $uf($table, 'note', $inputs[0], $id) : ( (!isset($inputs[0]) || empty($inputs[0]) ) ? $uf($table, 'note', null, $id) : null);
		$out = (isset($inputs[1]) ) ? $uf($table, 'todo_id', $inputs[1], $id) : ( (!isset($inputs[1]) || !check_index($inputs[1]) ) ? $uf($table, 'todo_id', null, $id) : null);
		}
	} # End of upd_diary_entry
	
//_______________________________________________________________________________________________
function upd_diary_entries($inputs=array() )
{
	process_entries($inputs, 'upd_diary_entry');
	} # End of upd_diary_entries
	
//_______________________________________________________________________________________________
function process_entries($inputs=array(), $func=null)
{
	if (isset($inputs) && is_array($inputs) && function_exists($func) )
	{
		$temp = transpose_output($inputs);		
		
		foreach ($temp as $t=>$output)
		{
			$output[] = $t;

			$func($output);
			}
		}
	}	# End of process_entries
//_______________________________________________________________________________________________

?>