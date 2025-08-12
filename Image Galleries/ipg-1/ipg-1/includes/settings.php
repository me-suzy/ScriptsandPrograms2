<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:				IPG 1.0                                       *
* Copyright 2005 by:			Verosky Media - Edward Verosky                *
* Support, News, Updates at:  	http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the provided license as published by Lewis Media.        *
*                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/

db_connect();
$sql = "SELECT * FROM " . PDB_PREFIX . "configuration";
$result = db_query($sql);
while($row = db_fetch_array($result)){
    define($row['config_key'], $row['config_value']);
}
?>