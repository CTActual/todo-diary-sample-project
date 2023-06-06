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

// We grab important page values using the pg_id and context.
// hfwn_return_value returns a value directly from the HFW database.
// This is the simplest use case.
// The location names are fixed in the code here.
$title = hfwn_return_value($pg_id, 'title', $ctx);
$favicon = hfwn_return_value($pg_id, 'favicon', $ctx);
$metadesc = hfwn_return_value($pg_id, 'metadesc', $ctx);
$metakeys = hfwn_return_value($pg_id, 'metakeys', $ctx);
$metaview = hfwn_return_value($pg_id, 'metaview', $ctx);
$stylesheets = hfwn_return_value($pg_id, 'stylesheets', $ctx);
$header = hfwn_return_value($pg_id, 'hdr', $ctx);
$navleft = hfwn_return_value($pg_id, 'navbtnleft', $ctx);
$navright = hfwn_return_value($pg_id, 'navbtnright', $ctx);
// The copyright notice is a footer object
$crnote = hfwn_return_value($pg_id, 'crnote', $ctx);

?>
