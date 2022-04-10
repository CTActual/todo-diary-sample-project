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

// form handling functions

//________________________________________________________________________________________
function post_button_check($p=null, $upd_but_loc=null, $save_but_loc=null, $save_ctx=null)
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	// We need to get the values of $upd_but_loc and $save_but_loc for $save_ctx to get the $_POST index to look for updating or saving a new value
	$post_upd = explode(',', $hfwnrv($p, $upd_but_loc, $save_ctx) );
	$post_save = explode(',', $hfwnrv($p, $save_but_loc, $save_ctx) );
	$ins = null;

	// Here we are going to do different things depending on whether this is an update or save
	if (isset($post_upd[0]) && isset($_POST[$post_upd[0] ]) && isset($post_upd[1]) && $_POST[$post_upd[0]] == $post_upd[1])
		{$upd_type = 'update';}
	elseif (isset($post_save[0]) && isset($_POST[$post_save[0] ]) && isset($post_save[1]) && $_POST[$post_save[0]] == $post_save[1])
		{$upd_type = 'save';}
	else
		{$upd_type = null;}
		
	return $upd_type;
	}	# End of post_button_check function
	
//________________________________________________________________________________________
function form_update_save($p=null, $upd_but_loc=null, $save_but_loc=null, $save_ctx=null, $upd_hid_val_loc=null, $upd_hid_val_ctx=null, $upd_proc_list_loc=null)
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	$ins = null;
	
	// Get the desired update type
	$upd_type = post_button_check($p, $upd_but_loc, $save_but_loc, $save_ctx);
	
	// We need to make sure that the update type is not empty
	if (!empty($upd_type) )
	{
		// We need to get the id of any selected item that needs updating
		$post_hid_val = $hfwnrv($p, $upd_hid_val_loc, $upd_hid_val_ctx);
		$post_hid_id = 0;

		if (!empty($post_hid_val) && !is_array($post_hid_val) && $upd_type == 'update')
		{
			$post_hid_obj = $esrgtac($post_hid_val);
			
			if (isset($post_hid_obj['type']) && $post_hid_obj['type'] == 'hidden' && isset($post_hid_obj['con'])  && isset($post_hid_obj['con']['name']) )
			{
				if (isset($_POST) && isset($_POST[$post_hid_obj['con']['name'] ]) && check_index($_POST[$post_hid_obj['con']['name'] ]) )
					{$post_hid_id = $_POST[$post_hid_obj['con']['name'] ];}
				}
			}
		
		// Get the list of function calls (procedure list) to save a new value
		$upd_list = explode("\r\n", $hfwnrv($p, $upd_proc_list_loc, $save_ctx) );

		if (is_array($upd_list) && count($upd_list) > 0)
		{
			// Pull the first line off, which is set to be the insert query
			$ins_list = array_shift($upd_list);

			// Unpack the insert query
			$ins_func = explode('||', $ins_list, 3);
			
			if (is_array($ins_func) && count($ins_func) > 1)
			{
				// The actual query function
				$func = $ins_func[0];
				
				if (!empty($func) && function_exists($func) )
				{
					// The parameters of the query function
					$tbl = $ins_func[1];
					
					// $newvals contains the $_POST indices
					$newvals = explode('||', $ins_func[2]);
					$newval = array();
				
					// Grab the actual values into an array
					foreach ($newvals as $k=>$v)
					{
						$newval[] = (isset($_POST[$v]) && !empty($_POST[$v]) ) ? $_POST[$v] : null;
						}
						
					if (count($newval) > 0)
					{
						// Insert the new record
						if ($upd_type == 'save') {$ins = $func($tbl, $newval); $id = $ins;}
						elseif (check_index($post_hid_id) ) {$id = $post_hid_id;}

						// Go thru the updates now
						if (check_index($id) )
						{
							foreach ($upd_list as $k=>$line)
							{
								$upds = explode('||', $line);
								$func = $upds[0];
								$tbl = $upds[1];
								$field = $upds[2];
								$updval = (isset($_POST[$upds[3] ]) && !empty($_POST[$upds[3] ]) ) ? $_POST[$upds[3] ] : null;

								$upd = $func($tbl, $field, $updval, $id);
								}	# End of foreach
							}	# End of $id check
						}	# End of newval count
					}	# End of func test
				}	# End of ins_func count
			}	# End of upd_list check
		}	# End of save or update conditional
	return $ins;
	}	# End of form_update_save function
	
//________________________________________________________________________________________
function form_list_update_save($p=null, $upd_but_loc=null, $save_but_loc=null, $but_ctx=null, $save_ctx=null, $loop_obj_loc=null, $loop_obj_ctx=null, $upd_proc_list_loc=null)
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	$ins = null;
	
	// Get the desired update type
	$upd_type = post_button_check($p, $upd_but_loc, $save_but_loc, $but_ctx);

	// We need to make sure that the update type is not empty
	if (!empty($upd_type) )
	{
		// We collect the objects of interest
		$loop_obj_name = $hfwnrv($p, $loop_obj_loc, $loop_obj_ctx);
		$upd_proc_list = $hfwnrv($p, $upd_proc_list_loc, $save_ctx);

		// We need the objects to be real
		if (!empty($loop_obj_name) && !is_array($loop_obj_name) && !empty($upd_proc_list) && !is_array($upd_proc_list) )
		{
			$loop_obj_val = (isset($_POST[$loop_obj_name]) && !empty($_POST[$loop_obj_name]) ) ? $_POST[$loop_obj_name] : null;
			
			// We need to make sure the loop_obj_val is an array
			if (is_array($loop_obj_val) )
			{
				// We get the first line of the update
				$upd_list = explode("\r\n", $upd_proc_list);
				$ins_list = array_shift($upd_list);

				// Unpack the insert query
				$ins_func = explode('||', $ins_list, 3);
				
				// We check the $ins_func array
				if (is_array($ins_func) && count($ins_func) > 1)
				{
					// The actual query function
					$func = $ins_func[0];
					
					// We make sure the func is real
					if (!empty($func) && function_exists($func) )
					{
						// The parameters of the query function
						$tbl = $ins_func[1];
						
						// $newvals contains the $_POST indices
						$newvals = explode('||', $ins_func[2]);

						// We loop through the obj
						foreach ($loop_obj_val as $key=>$val)
						{
							$newval = array();

							// Grab the actual values into an array
							foreach ($newvals as $k=>$v)
							{
								$nv = null;

								if (isset($_POST[$v]) && !empty($_POST[$v]) )
								{
									$nv = (is_array($_POST[$v]) ) ? ( (isset($_POST[$v][$val]) ) ? $_POST[$v][$val] : null) : $_POST[$v];
									}
								$newval[] = (isset($nv) && !empty($nv) ) ? $nv : null;
								}	# End of compiling $newval foreach
								
							if (count($newval) > 0)
							{
								// Insert the new record
								$ins = $func($tbl, $newval);
								}	# End of newval count check
							}	# End of foreach
						}	# End of func check
					}	# End of ins_func check
				}	# End of loop array check
			}	# End of object check
		}	# End of save or update conditional
	return $ins;
	}	# End of form_list_update_save function
	
//________________________________________________________________________________________
function process_post(&$p=null, $pg_id=null, $button=null, $ctx=null, $butloc=null)
{
	// Make sure we are posting data, and the form matches the context
	// $p is short for $_POST
	if (isset($button) && isset($p[$button]) && isset($pg_id) && isset($ctx) && isset($butloc) && $p[$button] == $ctx)
	{
		// Get the list of desired post variables
		// These need to be in the order required by the subsequent procedure call
		$postfieldstr = hfwn_return_value($pg_id, $butloc, $ctx, 'postlist');
		$inputs = array();
		
		if (isset($postfieldstr) )
		{
			$postfields = explode(',', $postfieldstr);
			
			if (is_array($postfields) )
			{
				foreach ($postfields as $i=>$pf)
				{
					$inputs[] = isset($p[$pf]) ? $p[$pf] : null;
					}	# End of foreach
				}	# End of is_array
			}	# End of isset

		// Get the procedure reference
		$func = hfwn_return_value($pg_id, $butloc, $ctx, 'php_func_call');

		// We make sure the func is real
		if (!empty($func) && function_exists($func) )
		{
			// Run func!!
			$func($inputs);
			}
		}
	}	# End of process post
	
//________________________________________________________________________________________
function get_current_values($pg_id=null, $formloc=null, $ctx=null)
{
	if (isset($pg_id) && isset($ctx) && isset($formloc) )
	{
		// Get the procedure reference
		$func = hfwn_return_value($pg_id, $formloc, $ctx, 'php_func_call');

		// We make sure the func is real
		if (!empty($func) && function_exists($func) )
		{
			// Run func!!
			return $func();
			}
		}
	return null;
	}	# End of get_current_values
	
//________________________________________________________________________________________
//________________________________________________________________________________________
$fus = 'form_update_save';
$flus = 'form_list_update_save';


?>