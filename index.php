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

require_once("rel.path.inc.php");
require_once($incpath . 'include.classes.inc.php');

$pg = 'index';
$pg_id = 1;

$title = hfwn_return_value($pg_id, 'title', 'def_ctx');
$favicon = hfwn_return_value($pg_id, 'favicon', 'def_ctx');
$metadesc = hfwn_return_value($pg_id, 'metadesc', 'def_ctx');
$metakeys = hfwn_return_value($pg_id, 'metakeys', 'def_ctx');
$metaview = hfwn_return_value($pg_id, 'metaview', 'def_ctx');
$stylesheets = hfwn_return_value($pg_id, 'stylesheets', 'def_ctx');
$header = hfwn_return_value($pg_id, 'hdr', 'def_ctx');

include_once ($incpath . 'std.html.contact.header.inc.php');

?>
	<body>
	<section class="ftco-section">
<!-- s1 ↓ -->
		<div class="container-fluid">
<!-- d1 ↓ -->			
			<div class="row justify-content-center"><div class="col-md-6 text-center mb-5"><h2 class="heading-section"><?php echo $header; ?></h2></div></div>
			
			<div class="row justify-content-center">
<!-- d2 ↓ -->
				<div class="col-md-12">
<!-- d3 ↓ -->
					<div class="wrapper">
<!-- d4 ↓ -->
						<div class="row no-gutters">
<!-- d5 ↓ Contains Both Halves of the page -->
							<div class="col-lg-6">
	<!-- d6 ↓ Left Half of Page class=col-lg-6 -->
	
<?php

$ctx = 'todo';
$cur_ctx_array = array('lc1o', 'lc2o', 'lc3o', 'lc4o', 'lc5o', 'lc6o');
$ctx_array = array('lc1n', 'lc2n', 'lc3n', 'lc4n', 'lc5n', 'lc6n');

include ($incpath . 'page.half.php');

?>

	<!-- d6 ↑ Left Half of Page -->
								</div>
						
							<div class="col-lg-6">
	<!-- d6 ↓ Right Half of Page class=col-lg-6 -->
	
<?php

$ctx = 'diary';
$cur_ctx_array = array('rc1o', 'rc2o', 'rc3o');
$ctx_array = array('rc1n', 'rc2n', 'rc3n');

include ($incpath . 'page.half.php');

?>

	<!-- d6 ↑ Right Half of Page class=col-lg-6 -->
								</div>
<!-- d5 ↑ Contains Both Halves of the page -->
						</div>
<!-- d4 ↑ -->
					</div>
<!-- d3 ↑ -->
				</div>
<!-- d2 ↑ -->
			</div>
<!-- d1 ↑ -->	
		</div>
<!-- s1 ↑ -->
	</section>
<!--
	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.validate.min.js"></script>
	<script src="js/main.js"></script>
-->
	</body>
</html>

