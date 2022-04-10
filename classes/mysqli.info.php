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
* 
* Change the default passwords below and in hfw.db.info.php for added security
* 
*/

// DB connection info

		$dsn = array(
			'hostspec' => 'localhost', 
			'username' => 'tdlall', 
			'password' => 'aishdiha&*Y(*G(&^G(jg&*(*8769876t', 
			'database' => 'todolist',
			'port' => null,
			'socket' => '', 
			'flags'=>'');

	if ($user_type == 'delete') 
		{$dsn['username'] = 'tdldelete'; $dsn['password'] = '8yihsdf8ykjkjh*&T(&^(&GIUYG*&^h78y98y98ya0s8df';}
	elseif ($user_type == 'select') 
		{$dsn['username'] = 'tdlselect'; $dsn['password'] = '98ys0d98fjskd*&T&(^FG(&T(&^';}
	elseif ($user_type == 'add') 
		{$dsn['username'] = 'tdladd'; $dsn['password'] = 'kajsdf9hasp9dfh#^%#^$*(^)*()(*)&()*&jdklj09';}

?>
