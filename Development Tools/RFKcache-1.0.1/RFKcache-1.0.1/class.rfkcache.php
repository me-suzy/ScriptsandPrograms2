<?php
/*
   RFKcache v1.0.1 - A PHP caching engine
   Copyright (C) 2001 RFKsolutions <rfksolutions@users.sourceforge.net>

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
*/

if( !defined( "_RFKcache_v1_0_1_" ) ):
	define( "_RFKcache_v1_0_1_" , 1 );

	class Cache {
		var $classname = "Cache";
		var $cName;

		function Cache( $id, $cPath="./" ) {
			$this->cName = $cPath . chop( trim( addslashes( $id ) ) );
		}

		function Cache_Read() {
			$fp = fopen( $this->cName, "r" );
			$this->contents = fread( $fp, filesize( $this->cName ) );
			fclose( $fp );
		}

		function Cache_Write( $contents ) {
			$fp = fopen( $this->cName, "w" );
			fwrite( $fp, $contents );
			fclose( $fp );
		}

		function Cache_Eval_Yesterday() {
			if ( date( "Ymd", filemtime( $this->cName ) ) < date( "Ymd" ) ) {
				return true;
			{
			return false;
		}

		function Cache_Eval_ExpireIf( $exptime ) {
			if ( filemtime( $this->cName ) + $exptime > time() )
				return true;
			return false;
		}

		function Cache_Fetch() {
			return $this->contents;
		}

		function Cache_Output() {
			echo $this->contents;
		}

		function Cache_OutputHTML() {
			Header( "Content-type: text/html" );
			echo $this->contents;
		}

		function Cache_Delete() {
			if ( file_exists( $this->cName ) ) {
				unlink( $this->cName );
			}
		}

	}
endif;
?>
