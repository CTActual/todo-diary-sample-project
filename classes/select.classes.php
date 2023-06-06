<?php
/*
Copyright 2011-2023 Cargotrader, Inc. All rights reserved.

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

// Globals
$pagination_limit = 25;

// Select classes

//________________________________________________________________________________________
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
function get_todo_type_list($any=false)
{
	// Return a flattened list of todo type names and ids

	return add_any_to_list(get_type_list(1, 1), $any);
	}
	
//________________________________________________________________________________________
function get_status_type_list($any=false)
{
	// Return a flattened list of status type names and ids

	return add_any_to_list(get_type_list(2, 7), $any);
	}
	
//________________________________________________________________________________________
function add_any_to_list($list=array(), $any=false)
{
	if (!is_array($list) ) {return null;}
	
	$any = force_boolean($any, false);
	
	if ($any) 
	{
		if (isset($list['dval']) && !empty($list['dval']) )
		{
			$dval = explode('__r__', $list['dval']);
			$list['dval'] = implode('__r__', array_fill(0, count($dval) + 1, '0') );
			}
		
		if (isset($list['id']) && !empty($list['id']) )
		{
			$list['id'] = '0__r__' . $list['id'];
			}
		
		if (isset($list['name']) && !empty($list['name']) )
		{
			$list['name'] = 'Any__r__' . $list['name'];
			}
		}	# End of $any = true
	return $list;
	}
	
//________________________________________________________________________________________
//________________________________________________________________________________________
function get_cur_date()
{
	// Return the current date
	$query = "Select Date_Format(now(), '%Y-%m-%d')";
	
	return row_pattern($query, null, null, array('curdate') );
	}
	
//________________________________________________________________________________________
function get_cur_todo_list($id=null, $limit=25)
{
	if (!check_index($limit) ) {$limit = $GLOBALS['pagination_limit'];}
	
	// Retrieve the last N uncompleted to-do entries
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

	$i .= 'i';
	$input[] = $limit;

	$query = "Select * 
	From (Select id, 
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
		Order By crn_date DESC 
		Limit ?) as last 
	Order By crn_date";
	
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
function get_cur_diary_list($limit=25)
{
	if (!check_index($limit) ) {$limit = $GLOBALS['pagination_limit'];}
	
	// Retrieve the last N diary entries
	$query = "Select * 
		From (Select id, 
			note, 
			crn_date, 
			Date_Format(crn_date, '%Y-%m-%d') as crn, 
			todo_id 
		From diary 
		Where note Is Not Null 
		Order By crn_date DESC 
		Limit ?) as last 
	Order By crn_date";
	
	return tcol_pattern($query, 'i', array('limit'=>$limit), array('note_id', 'note', 'crn_date', 'crn', 'todo_id') );
	} # End of get_cur_todo_list
		
//________________________________________________________________________________________
function get_arb_diary_list($pg=1, $limit=25, $g=null)
{
	if (!check_index($limit) ) {$limit = $GLOBALS['pagination_limit'];}
	
	$pgs = get_num_diary_pgs($limit);
	
	if (!check_index($pg) ) {$pg = get_cur_pg($g, 'diary');}
	if (!check_index($pg) || $pg > $pgs) {$pg = $pgs;}
	
	// Retrieve the last N diary entries
	$query = "Select id, 
			note, 
			crn_date, 
			Date_Format(crn_date, '%Y-%m-%d') as crn, 
			todo_id, 
			?, 
			?, 
			?, 
			?  
		From diary 
		Where note Is Not Null 
		Order By crn_date 
		Limit ? Offset ?";
	
	$input = array('pg'=>$pg, 'pgs'=>$pgs, 'lim'=>$limit, 'off'=>($pg-1)*$limit, 'limit'=>$limit, 'offset'=>($pg-1)*$limit);

	return tcol_pattern($query, 'iiiiii', $input, array('note_id', 'note', 'crn_date', 'crn', 'todo_id', 'pg', 'pgs', 'limit', 'offset') );
	}
	
//________________________________________________________________________________________
function get_todo_sort_context()
{
	// Default contexts for column headers
	$hdr_array = array('ch1', 'ch2', 'ch3', 'ch4', 'ch5', 'ch6');
	$sort_ctx = 'todo';
	
	if (isset($_GET) && isset($_GET['sort'])  && in_array($_GET['sort'], array('datedesc', 'deadasc', 'deaddesc', 'compasc', 'compdesc') ) )
	{
		switch ($_GET['sort'])
		{
			case 'datedesc':
				$hdr_array = array('ach1', 'ch2', 'ch3', 'ch4', 'ch5', 'ch6');
				$sort_ctx = 'todo_dtds';
				break;
			case 'deadasc':
				$hdr_array = array('ch1', 'ch2', 'ch3', 'ch4', 'ch5', 'ch6');
				$sort_ctx = 'todo_dlas';
				break;
			case 'deaddesc':
				$hdr_array = array('ch1', 'ch2', 'ch3', 'ch4', 'ach5', 'ch6');
				$sort_ctx = 'todo_dlds';
				break;
			case 'compasc':
				$hdr_array = array('ch1', 'ch2', 'ch3', 'ch4', 'ch5', 'ch6');
				$sort_ctx = 'todo_cdas';
				break;
			case 'compdesc':
				$hdr_array = array('ch1', 'ch2', 'ch3', 'ch4', 'ch5', 'ach6');
				$sort_ctx = 'todo_cdds';
				break;
			}
		}

	return array('hdr_array'=>$hdr_array, 'sort_ctx'=>$sort_ctx);
	}

//________________________________________________________________________________________
function get_todo_filter_string()
{
	// Get the url filter string for the column sorts links
	$sets = get_todo_filter_sets();
	
	return "&amp;type={$sets['type']}&amp;status={$sets['status']}";
	}
	
//________________________________________________________________________________________
function get_arb_todo_list($pg=1, $limit=25, $g=null, $sort='crn_date')
{
	if (!check_index($limit) ) {$limit = $GLOBALS['pagination_limit'];}
	
	$pgs = get_num_todo_pgs($limit);
	
	if (!check_index($pg) ) {$pg = get_cur_todo_pg($g);}
	if (!check_index($pg) || $pg > $pgs) {$pg = $pgs;}
	
	if (!in_array($sort, array('crn_date', 'crn_date desc', 'dl_date', 'dl_date desc', 'comp_date', 'comp_date desc') ) ) {$sort = 'crn_date';}
	
	$g = get_page_sort_type_status('todo');
	
	$i = 'iiiiii';
	$input = array('pg'=>$pg, 'pgs'=>$pgs, 'lim'=>$limit, 'off'=>($pg-1)*$limit);
	$extra = null;
	
	if (check_Index($g['type']) )
	{
		$i .= 'i';
		$input['type'] = $g['type'];
		$extra .= 'and type_id = ? ';
		}

	if (check_Index($g['status']) )
	{
		$i .= 'i';
		$input['status'] = $g['status'];
		$extra .= 'and status_type_id = ? ';
		}
		
	$input['limit'] = $limit;
	$input['offset'] = ($pg-1)*$limit;

	$query = "Select id, 
			type_id, 
			note, 
			crn_date, 
			dl_date, 
			comp_date, 
			status_type_id, 
			Date_Format(crn_date, '%Y-%m-%d') as crn, 
			(Select type_name From types Where id = type_id Limit 1) as todo_type, 
			(Select type_name From types Where id = status_type_id Limit 1) as status_type, 
			?, 
			?, 
			?, 
			? 
		From todolist 
		Where note Is Not Null 
		$extra
		Order By $sort 
		Limit ? Offset ?";
	
	return tcol_pattern($query, $i, $input, array('note_id', 'type_id', 'note', 'crn_date', 'dl_date', 'comp_date', 'status_type_id', 'crn', 'todo_type', 'status_type', 'pg', 'pgs', 'limit', 'offset') );
	}
	
//________________________________________________________________________________________
//________________________________________________________________________________________
function get_num_pgs($limit=25, $f='diary')
{
	if (!check_index($limit) ) {$limit = $GLOBALS['pagination_limit'];}
	
	if (!in_array($f, array('diary', 'todo') ) ) {$f = 'diary';}
	
	if ($f == 'diary')
		{return get_num_diary_pgs($limit);}
	else
		{return get_num_todo_pgs($limit);}
	}
	
//________________________________________________________________________________________
function get_num_diary_pgs($limit=25)
{
	if (!check_index($limit) ) {$limit = $GLOBALS['pagination_limit'];}
	
	// Get the total number of pages with N entries in the diary
	$query = "Select count(id) 
	From diary";
	
	$count = row_pattern($query, null, null, array('count') );
	
	return ceil($count/$limit);
	}
	
//________________________________________________________________________________________
function get_num_todo_pgs($limit=25)
{
	if (!check_index($limit) ) {$limit = $GLOBALS['pagination_limit'];}
	
	$g = get_page_sort_type_status('todo');
	
	$i = null;
	$input = null;
	$extra = null;
	
	if (check_Index($g['type']) && !check_Index($g['status']) )
	{
		$i = 'i';
		$input = array('type'=>$g['type']);
		$extra = ' Where type_id = ?';
		}
	elseif (!check_Index($g['type']) && check_Index($g['status']) )
	{
		$i = 'i';
		$input = array('status'=>$g['status']);
		$extra = ' Where status_type_id = ?';
		}
	elseif (check_Index($g['type']) && check_Index($g['status']) )
	{
		$i = 'ii';
		$input = array('type'=>$g['type'], 'status'=>$g['status']);
		$extra = ' Where type_id = ? and 
						status_type_id = ?';
		}
	
	// Get the total number of pages with N entries in the todolist table
	$query = "Select count(id) 
	From todolist{$extra}";
	
	$count = row_pattern($query, $i, $input, array('count') );
	
	return ceil($count/$limit);
	}
	
//________________________________________________________________________________________
//________________________________________________________________________________________
function get_cur_pg($g=null, $f='diary')
{
	if (!in_array($f, array('diary', 'todo') ) ) {$f = 'diary';}
	
	if ($f == 'diary')
		{return get_cur_diary_pg($g);}
	else
		{return get_cur_todo_pg($g);}
	}
	
//________________________________________________________________________________________
function get_cur_diary_pg($g=null)
{
	// Pull in the $_GET variable needed, or assume last page
	if (isset($_GET[$g]) && !empty($_GET[$g]) && check_index($_GET[$g]) ) {$pg = $_GET[$g];} else {$pg = get_num_diary_pgs();}

	return $pg;
	}
	
//________________________________________________________________________________________
function get_cur_todo_pg($g=null)
{
	// Pull in the $_GET variable needed, or assume last page
	if (isset($_GET[$g]) && !empty($_GET[$g]) && check_index($_GET[$g]) ) {$pg = $_GET[$g];} else {$pg = get_num_todo_pgs();}

	return $pg;
	}
	
//________________________________________________________________________________________
function get_page_sort_type_status($f='diary')
{
	if (!in_array($f, array('todo', 'diary') ) ) {$f = 'diary';}
	
	$sort = (isset($_REQUEST) && isset($_REQUEST['sort']) && !empty($_REQUEST['sort']) ) ? $_REQUEST['sort'] : 'dateasc';
	$type = (isset($_REQUEST) && isset($_REQUEST['type']) && is_numeric($_REQUEST['type']) ) ? $_REQUEST['type'] : 0;
	$status = (isset($_REQUEST) && isset($_REQUEST['status']) && is_numeric($_REQUEST['status']) ) ? $_REQUEST['status'] : 0;
	
	return array('f'=>$f, 'sort'=>$sort, 'type'=>$type, 'status'=>$status);
	}
	
//________________________________________________________________________________________
function get_todo_filter_sets()
{
	$sets = get_page_sort_type_status('todo');
	
	return array('type'=>$sets['type'], 'status'=>$sets['status']);
	}
	
//________________________________________________________________________________________

// Below are the arrow functions as seen in HFW

//________________________________________________________________________________________
//________________________________________________________________________________________
function get_first_pg($f='diary')
{
	$gets = get_page_sort_type_status($f);
	
	$pg = get_cur_pg('pg', $gets['f']);

	if ($pg <= 1) {$class = 'faintlink';} else {$class = 'arrowlink';}
	
	return array($class, $gets['sort'], $gets['type'], $gets['status']);
	}
	
//________________________________________________________________________________________
function get_prev_pg($f='diary')
{
	$gets = get_page_sort_type_status($f);

	$pg = get_cur_pg('pg', $gets['f']);
	
	if ($pg <= 1) {$class = 'faintlink'; $p = 1;} else {$class = 'arrowlink'; $p = $pg - 1;}
	
	return array($class, $p, $gets['sort'], $gets['type'], $gets['status']);
	}
	
//________________________________________________________________________________________
function get_next_pg($f='diary')
{
	$gets = get_page_sort_type_status($f);
	
	$pg = get_cur_pg('pg', $gets['f']);
	
	$pgs = get_num_pgs($gets['f']);
	
	if ($pg >= $pgs) {$class = 'faintlink'; $p = $pgs;} else {$class = 'arrowlink'; $p = $pg + 1;}
	
	return array($class, $p, $gets['sort'], $gets['type'], $gets['status']);
	}
	
//________________________________________________________________________________________
function get_last_pg($f='diary')
{
	$gets = get_page_sort_type_status($f);
	
	$pg = get_cur_pg('pg', $gets['f']);
	
	$pgs = get_num_pgs($gets['f']);
	
	if ($pg >= $pgs) {$class = 'faintlink'; $p = $pg;} else {$class = 'arrowlink'; $p = $pgs;}
	
	return array($class, $p, $gets['sort'], $gets['type'], $gets['status']);
	}
	
//________________________________________________________________________________________




?>