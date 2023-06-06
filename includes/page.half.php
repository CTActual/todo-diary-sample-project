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
// This include is reused several times by various "pages".
// It makes use of both standard HFW calls and calls made
// specifically for the To-Do List project which are not included
// with stock HFW code.

// The two halves are similar in some ways:  both are lists that contain old information at the top and 
// new entries are made at the bottom.  They are also different in that their columns and data are not alike.
// Also the formats do not truely match.  Likewise, when only one half is presentated in full page mode, the 
// format adjusts.  This is not magic.  Each case must be delineated.  However, the formalism allows for very 
// good code segregation and clear project organization.  

// Most of the code here is the deconstructed HTML template.

// We need to process any posted forms first.
// Here we are insisting on the location names in the code.
$butloc = 'addnewbut';
$updloc = 'updoldbut';
$addnewbut = hfwn_return_value($pg_id, $butloc, $ctx, 'csv');
$updoldbut = hfwn_return_value($pg_id, $updloc, $ctx, 'csv');

// We can get the Post-Get-Request Name (postname) entries from the database.
// This is a bit more flexible.
$addbutpost = hfwn_return_value($pg_id, $butloc, $ctx, 'postname');
$updbutpost = hfwn_return_value($pg_id, $updloc, $ctx, 'postname');
$filbutpost = hfwn_return_value($pg_id, $updloc, 'todo_fbc', 'postname');

// Any new entries or updates must be dealt with.
// process_post is from the form handling classes library.
// It has the form process_post(&$p=null, $pg_id=null, $button=null, $ctx=null, $butloc=null)
process_post($_POST, $pg_id, $addbutpost, $ctx, $butloc);
process_post($_POST, $pg_id, $updbutpost, $ctx, $updloc);
$todosets = process_post($_REQUEST, $pg_id, $filbutpost, 'todo_fbc', $updloc);

// Get some info.
$header = hfwn_return_value($pg_id, 'hdr', $ctx);
$sheader = hfwn_return_value($pg_id, 'shdr', $ctx);
$form = hfwn_return_value($pg_id, 'entryform', $ctx, 'txt');

// Arrow Nav row
// See below for $gfor description.
// The $hfwngcv is part of the standard HFW export lib function set.
// It gets named context values from hfwn_get_ctx_vals.
// Full description is in the library code.  It retrieves values from the HFW database
// and returns an array.
$arr_row = $gfor($hfwngcv(array('arrows'), 'block', 'csv', $pg, $pg), 'php_func_call');

// Filter row (To-Do List page only)
$filter_row = $gfor($hfwngcv(array('def_ctx'), 'block', 'csv', $pg, $pg), 'php_func_call', $todosets);

// Get the current (old) values.
// get_current_values is from the form handling classes library.
$cur_vals = get_current_values($pg_id, 'entryform', $sort_ctx);

// We call the gen form obj row ($gfor) function from the object handling classes library.
// The first param takes the context array and returns the csv vals for block objects.
// The second param tells $gfor where to look for internal setting vals, in this case php function calls.
// It's the alternative setting type, set up as a pair with the original setting type in the GUI.
// We filter on 'rod' to start at the top of the html for each row object (row outer div).
$newrow = $gfor($hfwngcv($ctx_array, 'block', 'csv', $pg, $pg, true, 'rod'), 'php_func_call');

$hdr_row = $gfor($hfwngcv($hdr_array, 'block', 'csv', $pg, $pg, true, 'rod'), 'php_func_call');

	$hdrstr = null;
			
	// $hdr_row comes from using $gfor to look up objects in the HFW database, if any.
	if (isset($hdr_row) && is_array($hdr_row) && count($hdr_row) > 0 && isset($cur_vals) && is_array($cur_vals) && count($cur_vals) > 0)
	{
		$hdrstr = implode("\n\n", $hdr_row);
		}	# End of isset and is_array check
	
$new_hdr_row = $gfor($hfwngcv($new_hdr_array, 'block', 'csv', $pg, $pg, true, 'rod'), 'php_func_call');

	$newhdrstr = null;
			
	// $hdr_row comes from using $gfor to look up objects in the HFW database, if any.
	if (isset($new_hdr_row) && is_array($new_hdr_row) && count($new_hdr_row) > 0)
	{
		$newhdrstr = implode("\n\n", $new_hdr_row);
		}	# End of isset and is_array check
	
?>
								<div class="contact-wrap w-100 p-md-5 p-4">
	<!-- div class="contact-wrap w-100 p-md-5 p-4" start ↓ -->
									
									<h3><?php echo $header; ?></h3>
<!--									<h4>&#8739;&#11207;&nbsp;&#11207;&nbsp;&nbsp;&#11208;&nbsp;&#11208;&#8739;</h4>-->
									<?php if (isset($arr_row[0]) && !empty($arr_row[0]) ) {echo $arr_row[0];} ?>

									<form method="POST" id="<?php echo $form; ?>" name="<?php echo $form; ?>" class="contactForm">
									
									<?php if (isset($filter_row[0]) && !empty($filter_row[0]) ) {echo $filter_row[0] . "<br>";} ?>

									<p class="mb-4"><?php echo $sheader; ?></p>
<?php
	// Column headers, if any.
	// $aoo comes from the included HTML object classes library.
	echo $aoo('comment', "core=PHP Column Headers Row Generation start ↓");
	
	// Here we use $aoo to output the HTML.
	echo $aoo('div', "class=row;\ncore=$hdrstr\n\n");
	
	$rowstr = null;
	
	echo $aoo('comment', "core=PHP Column Headers Row Generation end ↑");
			
	// We go through each old entry.
	if (isset($cur_vals) && is_array($cur_vals) )
	{
		foreach ($cur_vals as $ci=>$cv)
		{
			// Here we use $gfor for the population of each row of current values ($cv).
			// "php_func_call" tells $gfor what setting type in HFW to get the info on string swaps.
			$oldrow = $gfor($hfwngcv($cur_ctx_array, 'block', 'csv', $pg, $pg, true, 'rod'), 'php_func_call', $cv);
			$rowstr = null;

			echo $aoo('comment', "core=PHP Old Row Generation start ↓");
			
			// We build up the row as a string from the $oldrow array.
			if (isset($oldrow) && is_array($oldrow) )
			{
				$rowstr = implode("\n\n", $oldrow);
				}	# End of isset and is_array check
				
			// Here we use $aoo to output the HTML.
			echo $aoo('div', "class=row;\ncore=$rowstr\n\n");
			
			$rowstr = null;
			
			echo $aoo('comment', "core=PHP Old Row Generation end ↑");
			
			echo $aoo('comment', "core=div class=\"row mb-4\" start ↓");
			echo $aoo('div', "class=row mb-4;\ncore=&nbsp;");
			echo $aoo('comment', "core=div class=\"row mb-4\" end ↑");
			}	# End of cur_vals foreach
		}	# End of isset and is_array check
	else
	{
		echo $aoo('comment', "core=div class=\"row mb-4\" start ↓");
		echo $aoo('div', "class=row mb-4;\ncore=&nbsp;");
		echo $aoo('comment', "core=div class=\"row mb-4\" end ↑");
		}
?>										
				<!-- Button Row ↓ -->
										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<?php if (isset($cur_vals) && is_array($cur_vals) && count($cur_vals) > 0) {echo $esroo($updoldbut);} ?>
													<div class="submitting"></div>
												</div>
											</div>
											
										</div>
				<!-- Button Row ↑ -->

<?php

	// Column headers, if any
	echo $aoo('comment', "core=PHP Column Headers Row Generation start ↓");
			
	echo $aoo('div', "class=row;\ncore=$newhdrstr\n\n");
	$newhdrstr = null;
	echo $aoo('comment', "core=PHP Column Headers Row Generation end ↑");
			
?>
										<div class="row">
			<!-- PHP New Row Generation start ↓ -->
<?php

	if (isset($newrow) && is_array($newrow) )
	{
		echo implode("\n\n", $newrow);
		}
		
?>											
			<!-- PHP New Row Generation end ↑ -->									
										</div>
										
		<!-- div class="row mb-4" start ↓ -->
									<div class="row mb-4">
									</div>
		<!-- div class="row mb-4" end ↑ -->
				<!-- Button Row ↓ -->
										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<?php echo $esroo($addnewbut); ?>
													<div class="submitting"></div>
												</div>
											</div>
											
										</div>
				<!-- Button Row ↑ -->
										
									</form>
									
	<!-- div class="contact-wrap w-100 p-md-5 p-4" end ↑ -->
									</div>

