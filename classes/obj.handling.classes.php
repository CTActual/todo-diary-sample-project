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

// Object handling functions


function get_info_by_ids($p=null, $loc=null, $ctx=null)
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	$id_info = null;
	
	// Get the value of infobyid and explode into an array
	// We do func||input1,input2,input3...||output1,output2,output3...
	$infobyid = explode("||", $hfwnrv($p, $loc, $ctx) );

	// Get the info by id for any selected item
	if (is_array($infobyid) && count($infobyid) > 0 && isset($infobyid[0]) && !empty($infobyid[0]) )
	{
		// This is the function to call for the query
		$func = $infobyid[0];

		if (!empty($func) && function_exists($func) )
		{
			// We get the $_POST fields indexes for the inputs, if any
			if (isset($infobyid[1]) && !empty($infobyid[1]) )
			{
				$input_fields = explode(',', $infobyid[1]);
				
				$field_count = count($input_fields);
				
				// Set up each id as an input value
				foreach ($input_fields as $fk=>$field)
				{
					$inf[$fk] = (!empty($field) && isset($_POST[$field]) && check_index($_POST[$field]) ) ? $_POST[$field] : null;
					}	# End of foreach
					
				// We run the infobyid $func and the results go into $id_info
				if ($field_count == 1) {$id_info = $func($inf[0]);}
				elseif ($field_count == 2) {$id_info = $func($inf[0], $inf[1]);}
				elseif ($field_count == 3) {$id_info = $func($inf[0], $inf[1], $inf[2]);}
				}	# End of [1] check
			else
			{
				$id_info = $func();
				}	# End of no input field call
			}	# End of $func check
		}	# End of $infobyid check
	return $id_info;
	}	# End of get_info_by_ids function
	
//________________________________________________________________________________________
function object_text_swapper($objs=array(), $subs=array(), $alt='__')
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	// We process and object with a simple text swap and return the output
	
	$output = array();
	if (empty($alt) ) {$alt = '__';}
	
	if (is_array($objs) )
	{
		foreach ($objs as $key=>$obj)
		{
			extract($obj);
			
			$output[] = val_text_swapper($val, $subs, $alt);
			}	 # End of foreach
		}	# End of object check
	return $output;
	}	# End of object_text_swapper function
	
//________________________________________________________________________________________
function val_text_swapper($val=null, $subs=array(), $alt='__')
{
	if (empty($alt) ) {$alt = '__';}
	
	// We find all instances of {$alt}anystring{$alt}, default __anystring__, in value and put them in matches.
	// We need the $matches to be indexes for $subs to do the subs.
	// Of course, without $subs we don't have to do anything
	if (isset($subs) && is_array($subs) && count($subs) > 0)
	{
		preg_match_all("~(?<={$alt}).+?(?={$alt})~", $val, $matches, PREG_PATTERN_ORDER);

		if (is_array($matches[0]) && count($matches[0]) > 0)
		{
			foreach ($matches[0] as $k=>$m)
			{
				if ($k % 2 === 0)
				{
					if (isset($subs[$m]) )
					{
						// We replace __string__ with the value from info['string'] in val
						$val = str_replace("$alt{$m}$alt", $subs[$m], $val);
						}	# End of id_info index check
					}	# Skip odd indices since they are invalid checks, starting with 0.
				}	# End of foreach
			}	# End of $matches check
		}	# End of subs check
	return $val;
	}	# End of function val_text_swapper
	
//________________________________________________________________________________________
function input_form_obj_compiler($form_objs=array(), $id_info=array(), $wrapper='string', $bypass=false)
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	// We process form objects that get passed to $esroo with $id_info text swapping
	$in_field_str = '';
	$data = array();
	
	// Bypass allows us to retrieve the value of a recently saved or updated object rather than the selected object
	$bypass = force_boolean($bypass, false);
	
	if (empty($wrapper) ) {$wrapper = 'string';}
	
	if (is_array($form_objs) )
	{
		foreach ($form_objs as $key=>$form_obj)
		{
			// put the $form_obj array into individual variables for convenience.
			// This is generally returned from hfwn_get_ctx_vals in the export library.
			// Refer to the help from that function to see its output params.
			extract($form_obj);
			
			// If the setting type label for the value is csv then we can go ahead and parse it
			if ($set_type_lbl == 'csv')
			{
				// $esrgtac($val) will return the 'type' and the 'con' from setup_con by parsing the pdm_input string
				// $val comes from extracting $form_obj, ie, the pdm_input string is $val as the value returned
				$info = $esrgtac($val);
				
				if ($info['type'] == 'option')
				{
					// We need the location of the object to get the list values from the value key
					if (isset($info['con']['value']) && (isset($info['con']['label']) || isset($info['con']['core']) ) )
					{
						// This is the string that gets swapped by the name list
						$list_val_lbl = (isset($info['con']['core']) ) ? $info['con']['core'] : $info['con']['label'];
						
						// This is the string that gets swapped by the id list
						$list_val_loc_str = $info['con']['value'];

						// This is the function call (query) to retrieve the id and name values
						$list_val_func = (isset($info['con']['data-list-val-func']) && !empty($info['con']['data-list-val-func']) ) ? $info['con']['data-list-val-func'] : null;

						// This should match the index key of the query returned array from $id_info that needs to be selected.
						$list_val_tval_id = (isset($info['con']['data-list-val-tval_id']) && !empty($info['con']['data-list-val-tval_id']) ) ? $info['con']['data-list-val-tval_id'] : null;
						
						// Here we call the correct function through a first class var
						// to fill in the values and labels, or just fail with null.
						if (!empty($list_val_func) && function_exists($list_val_func) )
						{
							$list_vals = $list_val_func();

							$data[$list_val_loc_str] = $list_vals['id'];
							$data[$list_val_lbl] = $list_vals['name'];
							}
						else
						{
							$data[$list_val_loc_str] = null;
							$data[$list_val_lbl] = null;
							}
						}
					
					// Since we need the info submitting by the select element
					// We need to require it be +1 next in the ordering after the option.
					$core_info = $esrgtac($form_objs[$key+1]['val']);
					
					// This should match the index key of the query returned array from $id_info that needs to be selected.
					$data["__{$core_info['con']['name']}__"] = (isset($id_info) && isset($list_val_tval_id) && isset($id_info[$list_val_tval_id]) ) ? $id_info[$list_val_tval_id] : null;

					$option = $esroo($val, $data);
					}
				else
				{
					// The 'con' from setup_con might have an element name tag value, which got $_POST'ed, so we get value of the $_POST from that match
					// However, we generally don't want to repeat values after a savenew
					// Bypass allows us to retrieve the value of a recently saved or updated object

					if (isset($_POST[$info['con']['name'] ]) && !empty($_POST[$info['con']['name'] ]) && !$bypass)
					{
						$data["__{$info['con']['name']}__"] = $ps2($_POST[$info['con']['name'] ]);
						}
					elseif (isset($id_info) && is_array($id_info) && isset($id_info[$info['con']['name'] ]) )
					{
						// Deal with the selected value info
						$data["__{$info['con']['name']}__"] = $ps2($id_info[$info['con']['name'] ]);
						}
					else
					{
						// Deal with the default value
						if ($info['type'] == 'hidden')
						{
							$data["__{$info['con']['name']}__"] = (isset($info['con']['dval']) ) ? $info['con']['dval'] : null;
							}
						else
						{
							$data["__{$info['con']['name']}__"] = '';
							}
						}
						
					if ($info['type'] == 'select')
					{
						$data["__C_{$info['con']['name']}__"] = (isset($option) ) ? $option : null;
						unset($option);
						}
					$in_field_str .= $aoo($wrapper, "core={$esroo($val, $data)}");
					}
				$data = array();
				}	# End of csv check
			}	# End of foreach
		}	# End of is_array conditional
	return $in_field_str;
	} # End of input_form_obj_compiler function
	
//________________________________________________________________________________________
function gen_form_obj($obj=null, $func_call=null, $is_core=false, $alt='__', $set_type_lbl=null, $ext_vals=null)
{
	global $poa, $csvhfwnrv, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	// The object comes in as a val.
	// $func_call is the setting value of the obj returned from gfor, which needs to be run to get the final values.
	// Info comes in as an array of index=>vals.
	// We can run the object through $ots to do string replace.
	// We can perform $esrgtac and investigate the internals of the object.
	// We can use data-internal-only=true to not compile the object.
	// We can use data-core=obj to compile the core.
	// We can use data-wrapper to wrap the object.
	// The inputs for hfwnrv are ($pagenum=null, $location=null, $named_ctx=null, $named_type=null, $def=false)
	// passed to $csvhfwnrv.
	
	if (empty($obj) ) {return null;}
	$is_core = force_boolean($is_core, false);
	$info = null;

	// Run the swapper for external values, if any
	if (isset($ext_vals) && is_array($ext_vals) && count($ext_vals) > 0)
	{
		if (strpos($obj, $alt) !== false) {$obj = val_text_swapper($obj, $ext_vals, $alt);}
		if (isset($func_call) && !empty($func_call) && strpos($obj, $alt) !== false) {$func_call = val_text_swapper($func_call, $ext_vals, $alt);}
		}

	// We need to go get the possible swap info.
	// $gssi returns the final query values after running the func_call
	$info = get_string_swap_info($func_call);

	// Run the swapper
	if (isset($info) && is_array($info) && strpos($obj, $alt) !== false)
		{$obj = val_text_swapper($obj, $info, $alt);}
	unset($info);

	// Break up the object
	if (isset($obj) )
	{
		$object = $esrgtac($obj);
		
		$type = (isset($object['type']) ) ? $object['type'] : 'generic';
		$con = (isset($object['con']) ) ? $object['con'] : array();
		unset($object);
		}
	else
		{return null;}
		
	// We will ignore data-internal-only=true objects unless $is_core=true
	if (isset($con['data-internal-only']) && $con['data-internal-only'] == 'true' && $is_core == false)
		{return null;}
		
	// We need the wrapper, if there is one
	$wrapper = (isset($con['data-wrapper']) ) ? $con['data-wrapper'] : null;
	
	// We use data-core to recursively generate core values, relying on the framework
	if (isset($con['data-core']) && !empty($con['data-core']) ) 
	{
		// We can have multiple core references with ';\\'
		$cores = explode(';\\', $con['data-core']);
		
		if (is_array($cores) )
		{
			// This will overwrite any existing core value
			$con['core'] = null;
			
			foreach ($cores as $c=>$core)
			{
				// $esrgtac returns __comma__ for commas, so we need to pass that as the alt separator.
				// We force $is_core = true to process the object fully, but not before all its internal data-cores are processed first.
				$con['core'] .= gen_form_obj($csvhfwnrv($core, '__comma__'), $csvhfwnrv($core, '__comma__', $set_type_lbl), true, $alt, $set_type_lbl, $ext_vals);	
				}	# End of foreach
			}	# End of is_array
		}
		
	unset($obj);
	unset($con['data-internal-only']);
	unset($con['data-core']);
	unset($con['data-wrapper']);
	
	// With those out of the way, we can generate the object directly
	$output = gen_obj_output($type, $con);
	
	unset($type);
	unset($con);
	return gen_form_obj_wrapper($output, $wrapper);
	}	# End of gen_form_obj function
	
//________________________________________________________________________________________
function gen_form_obj_wrapper($obj=null, $wrapstr=null, $subs=null, $split='!!', $alt='~~')
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv;
	
	// The object comes in as already processed by $esroo or other compilers.
	// The wrapper comes in as a preprossed string, ready for $esroo.
	// The subs come in as an array.
	// The wrapper needs an alternative parsing syntax from || and ;\n.
	// We default to !! and ~~.
	
	if (empty($wrapstr) ) {return $obj;}
	
	// Break up the wrapper
	$wrapper = $esrgtac($wrapstr, false, $subs, $split='!!', $alt='~~');

	// Append the object to the wrapper core
	if (isset($wrapper['con']['core']) )
		{$wrapper['con']['core'] = $wrapper['con']['core'] . $obj;}
	else
		{$wrapper['con']['core'] = $obj;}
		
	// Take the object and wrap it with the wrap string object
	return gen_obj_output($wrapper['type'], $wrapper['con']);
	}	# End of gen_form_obj_wrapper function
	
//________________________________________________________________________________________
function get_string_swap_info($func_call=null, $input_array=array() )
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $csvhfwnrv, $hfwnrv;

	// This function returns the final values than can be swapped out for.
	// We break apart the function call.
	// Then it is run to return results.
	
	// We can ignore if empty
	if (empty($func_call) ) {return null;}
	$final = array();
	$results = null;

	// The function call is split into three rows: function name, inputs, outputs
	$setup = explode("\r\n", $func_call);

	if (is_array($setup) && count($setup) == 3)
	{
		$func = $setup[0];
		
		if (!empty($func) && function_exists($func) )
		{
			$inputs = explode(',', $setup[1]);
			$outputs = explode(',', $setup[2]);

			// We count the number of inputs
			$icount = count($inputs);

			// We match up the inputs with the input_array, if necessary
			if ($icount == 0 || empty($inputs) || ($icount == 1 && empty($inputs[0]) ) )
			{
				$results = $func();
				}	# End of icount = 0
			elseif ($icount == 1 && !empty($inputs[0]) )
			{
				$results = $func($inputs[0]);
				}	# End of icount = 1
			elseif ($icount == 2 && !empty($inputs[0]) )
			{
				$i2 = (!empty($inputs[1]) ) ? $inputs[1] : null;
				
				$results = $func($inputs[0], $i2);
				}	# End of icount = 2
			elseif ($icount == 3 && !empty($inputs[0]) )
			{
				$i2 = (!empty($inputs[1]) ) ? $inputs[1] : null;
				$i3 = (!empty($inputs[2]) ) ? $inputs[2] : null;
				
				$results = $func($inputs[0], $i2, $i3);
				}	# End of icount = 3
				
			// Deal with filling the output array
			if (is_array($results) )
			{
				$i = 0;

				// We swap out the query indexes with our own
				foreach($results as $r=>$val)
				{
					$final[$outputs[$i] ] = $val;
					$i++;
					}
				}	# End of results is_array test
			else
				{$final[$outputs[0] ] = $results;}
				
			}	# End of func test
		}	# End of is_array test
	else {return null;}
	return $final;
	}	# End of get_string_swap_info function

//________________________________________________________________________________________
function gen_form_obj_row($objs=null, $alt_type_lbl=null, $ext_vals=null, $alt='__')
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $csvhfwnrv;
	
	$output = array();
	
	// We process a row of objects
	if (is_array($objs) )
	{
		foreach ($objs as $key=>$obj)
		{
			// put the $obj array into individual variables for convenience.
			// This is generally returned from hfwn_get_ctx_vals in the export library.
			// Refer to the help from that function to see its output params.

			extract($obj);
			$func_call = null;
			
			// We need to see if there is a string swap value.
			// We use the alternative setting value type.
			// This is then passed as a string to the csv get val function so we don't have to deal with mismatched params.
			if (!empty($alt_type_lbl) ) {$func_call = implode(',', array($set_val_pg_id, $asc_pg_obj_loc, $ctx_lbl, $alt_type_lbl) );}

			// We need to send $set_type_lbl for any string swaps to search for other obj set vals
			// $val comes from extracting $obj.
			// We get the inside value in the second param.
			// Real world external values come from $ext_vals
			$fo = gen_form_obj($val, $csvhfwnrv($func_call), null, $alt, $alt_type_lbl, $ext_vals);
			
			// We compile the objects in the row for later unspooling.
			if ($fo !== NULL) {$output[] = $fo;}
			
			unset($fo);
			unset($obj);
			}	# End of foreach
		}	# End of is_array check
		
	return $output;
	}	# End of gen_form_obj_row function
	
//________________________________________________________________________________________
//________________________________________________________________________________________
function gen_faux_table($p=null, $cl_loc=null, $tbl_loc=null, $ctx=null, $row_type='form', $id_info=array(), $set_type='csv')
{
	global $poa, $ps, $ps2, $aoo, $aoa, $esrgtac, $esroo, $hfwnrv, $csvhfwnrv;
	
	if ($row_type !== 'form' && $row_type !== 'div') {$row_type = 'form';}
	
	// We default to 'csv' for the setting type, which must be consistent for all recovered objects
	$set_type = (!empty($set_type) ) ? $set_type : 'csv';
	
	// Get the lists of current values and table layout details
	// Cur list is func,input--with one input param allowed the index key of $id_info of interest
	$cur_list = explode(",", $hfwnrv($p, $cl_loc, $ctx, $set_type) );
	$tbl_dets = explode("\r\n\r\n", $hfwnrv($p, $tbl_loc, $ctx, $set_type) );
	$row_count = 0;

	// We arranged for the table details values to have three array elements, each on a different topic
	if (is_array($cur_list) && count($cur_list) > 0 && is_array($tbl_dets) && count($tbl_dets) == 3 && !empty($tbl_dets[0]) && !empty($tbl_dets[1]) )
	{
		// The first element of the $cur_list array contains the get query
		$func = $cur_list[0];
		$param = (isset($cur_list[1]) ) ? $cur_list[1] : null;
		if (isset($id_info) && is_array($id_info) && isset($id_info[$param]) ) {$param_val = $id_info[$param];} else {$param_val = null;}

		if (!empty($func) && function_exists($func) )
		{
			// Get the current values
			$cur_vals = $func($param_val);

			// Start building the table with the caption/header
			// We need to use a variety of delimiters
			$tbl_caps = explode("||", $tbl_dets[0]);	# Table column captions (cap1||cap2||cap3...)
			$tbl_tds = explode("\r\n", $tbl_dets[1]);		# The td contents
			$tbl_cls = explode("||", $tbl_dets[2]);		# The td class calls for layout

			$ths = array();
			$trs = array();
			
			if (is_array($tbl_caps) )
			{
				foreach ($tbl_caps as $j=>$cap)
				{
					$ths[] = "class=th;\ncore=$cap";
					}
				}
				
			// We can't use an actual table with all the row forms, so emulate one.
			// This function depends on certain named classes existing with the correct values to work
			$theadrow = $aoo('div', "class=tr;\ncore={$aoa('span', $ths)}");
			$thead = $aoo('div', "class=thead;\ncore=$theadrow");
			
			// Start to compile the table of current values
			if (is_array($cur_vals) )
			{
				foreach ($cur_vals as $k=>$cur_val)
				{
					// We can build the substitution table up
					$sub = null;
					
					foreach ($cur_val as $m=>$cv)
					{
						$sub["__{$m}__"] = $cv;
						}

					// We can build up the rows
					$tds = array();
					
					foreach ($tbl_tds as $n=>$td_obj)
					{
						// We can stuff more than one element into a td with ;;;
						$es = explode(";;;", $td_obj);		# The td contents
						$core = '';
						
						// Compile the core for the td
						// Anything starting with ** is evaluated with $hfwnrv thru $csvhfwnrv
						foreach ($es as $r=>$e)
						{
							if (substr($e, 0, 2) === '**')
								{$e = $csvhfwnrv(substr($e, 2) );}
								
							$core .= $esroo($e, $sub);
							}
							
						$tds[] = "class=td {$tbl_cls[$n]};\ncore={$core}";
						}
						
					$trs[] = "class=tr;\ncore={$aoa('span', $tds)}";
					}	# End of cur_vals foreach
				}	# End of is_array conditional
				
			// We count the rows so that we can use this for setting the next special order value, etc.
			$row_count = count($trs);
			
			if ($row_count > 0)
			{
				$tbody = $aoo('div', "class=tbody;\ncore={$aoa($row_type, $trs)}");
				$table = $aoo('div', "class=table;\ncore={$thead}$tbody");
				return array('rows'=>$row_count, 'table'=>$table);
				}
			else {return null;}
			}	# End of func test
		}	# End of dets conditional
	}	# End of gen_faux_table
	
//________________________________________________________________________________________
function get_form_obj_name($obj=null)
{
	global $esrgtac;
	
	$info = $esrgtac($obj);
	
	if (isset($info) && isset($info['con']) && isset($info['con']['name']) )
	{
		return $info['con']['name'];
		}
	return null;	
	}	# End of get_form_obj_name function
	
//________________________________________________________________________________________
$ots = 'object_text_swapper';
$ifoc = 'input_form_obj_compiler';
$gibi = 'get_info_by_ids';
$gft = 'gen_faux_table';
$gfon = 'get_form_obj_name';
$gfo = 'gen_form_obj';
$gfow = 'gen_form_obj_wrapper';
$gfor = 'gen_form_obj_row';

?>