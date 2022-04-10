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

// We need to process any posted forms first
$butloc = 'addnewbut';
$updloc = 'updoldbut';
$addnewbut = hfwn_return_value($pg_id, $butloc, $ctx, 'csv');
$updoldbut = hfwn_return_value($pg_id, $updloc, $ctx, 'csv');

$addbutpost = hfwn_return_value($pg_id, $butloc, $ctx, 'postname');
$updbutpost = hfwn_return_value($pg_id, $updloc, $ctx, 'postname');

process_post($_POST, $pg_id, $addbutpost, $ctx, $butloc);
process_post($_POST, $pg_id, $updbutpost, $ctx, $updloc);

// Get some info
$header = hfwn_return_value($pg_id, 'hdr', $ctx);
$sheader = hfwn_return_value($pg_id, 'shdr', $ctx);
$form = hfwn_return_value($pg_id, 'entryform', $ctx, 'txt');

// Get the current (old) values
$cur_vals = get_current_values($pg_id, 'entryform', $ctx);

// We call the gen form obj row function.
// The first param takes the context array and returns the csv vals for block objects.
// The second param tells gfor where to look for internal setting vals, in this case php function calls.
$newrow = $gfor($hfwngcv($ctx_array, 'block', 'csv'), 'php_func_call');

?>
								<div class="contact-wrap w-100 p-md-5 p-4">
	<!-- div class="contact-wrap w-100 p-md-5 p-4" start ↓ -->
									
									<h3><?php echo $header; ?></h3>
									<p class="mb-4"><?php echo $sheader; ?></p>
<!--										<div id="form-message-warning" class="mb-4"></div> 
										<div id="form-message-success" class="mb-4">Your message was sent, thank you!</div>
-->	
									<form method="POST" id="<?php echo $form; ?>" name="<?php echo $form; ?>" class="contactForm">
<?php
	// We go through each old entry
	if (isset($cur_vals) && is_array($cur_vals) )
	{
		foreach ($cur_vals as $ci=>$cv)
		{
			$oldrow = $gfor($hfwngcv($cur_ctx_array, 'block', 'csv'), 'php_func_call', $cv);
			$rowstr = null;

			echo $aoo('comment', "core=PHP Old Row Generation start ↓");
			
			if (isset($oldrow) && is_array($oldrow) )
			{
				foreach ($oldrow as $n=>$nr)
				{
					$rowstr .= "$nr\n\n";
					}
				}	# End of isset and is_array check
				
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

										<div class="row">
			<!-- PHP New Row Generation start ↓ -->
<?php

	foreach ($newrow as $n=>$nr)
		{echo "$nr\n\n";}

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

