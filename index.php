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

require_once("rel.path.inc.php");
require_once($incpath . 'include.classes.inc.php');

// We give the page name, pg_id and context to start out
$pg = 'index';
$pg_id = 1;
$ctx = 'def_ctx';

// We grab important page values using the pg_id and context
include_once ($incpath . 'header.vars.inc.php');

// We use some of the values we grabbed to start loading HTML
include_once ($incpath . 'std.html.contact.header.inc.php');

// We use $esroo on the header.nav include.
// This comes from the included html object classes library file.
//  $esroo takes a compact form of HTML, performs string substitutions as needed
// and then outputs HTML5.
// Here the HFW stores one button to the full To-Do List page as
// a||href=todo.php;\ncore=Full To-Do List
// and another button to the Full Diary page as
// a||href=diary.php;\ncore=Full Diary List.
// The syntax for the compact HTML is not discussed here in detail.
include_once ($incpath . 'header.nav.inc.php');

?>
							<div class="col-lg-6">
	<!-- d6 ↓ Left Half of Page class=col-lg-6 -->
<?php

// The page is split into two halves.
// The left half is the To-Do List.
// This is started here by provding a new context.
// Also needed are six columns of arbitrary context
// for both existing entries (old) and any new ones (new).
$ctx = 'todo';
$sort_ctx = $ctx;
$cur_ctx_array = array('lc1o', 'lc2o', 'lc3o', 'lc4o', 'lc5o', 'lc6o');
$ctx_array = array('lc1n', 'lc2n', 'lc3n', 'lc4n', 'lc5n', 'lc6n');
$hdr_array = array();
$new_hdr_array = array();

// We process the left half of the page.
include ($incpath . 'page.half.php');

?>

	<!-- d6 ↑ Left Half of Page -->
								</div>
						
							<div class="col-lg-6">
	<!-- d6 ↓ Right Half of Page class=col-lg-6 -->
	
<?php

// We proceed to the right side of the page, or 'Diary'.
// It has three columns instead of six.
$ctx = 'diary';
$sort_ctx = $ctx;
$cur_ctx_array = array('rc1o', 'rc2o', 'rc3o');
$ctx_array = array('rc1n', 'rc2n', 'rc3n');
$hdr_array = array();
$new_hdr_array = array();

// We can reuse the code for the right half.
include ($incpath . 'page.half.php');

?>

	<!-- d6 ↑ Right Half of Page class=col-lg-6 -->
								</div>
<?php

include_once ($incpath . 'footer.inc.php');

?>