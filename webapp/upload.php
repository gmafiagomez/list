<?php

/* The List powered by Creative Commons

   Copyright (C) 2014, 2015 Creative Commons Corporation

   based on:

   GNU FM -- a free network service for sharing your music listening habits
   GNU Archie Framework -- a web application framework derived from GNU FM

   Copyright (C) 2009, 2015 Free Software Foundation, Inc

   This program is free software: you can redistribute it and/or modify
   it under the terms of either the GNU Affero General Public License or
   the GNU General Public License as published by the
   Free Software Foundation, either version 3 of the Licenses, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU General Public License and
   the GNU Affero General Public License along with this program.  

   If not, see <http://www.gnu.org/licenses/>.

*/

require_once('database.php');
require 'data/User.php';
require 'data/List.php';

require_once('templating.php');

if ($auth) {

    if (isset($_GET['id'])) {

        $smarty->assign('id', $_GET['id']);
        $smarty->assign('welcome', true);
        $smarty->display('upload.tpl');
    }

    else {

        $smarty->display('nolist.tpl');	
					
    }					

}

else {
    $smarty->display('noauth.tpl');
}

