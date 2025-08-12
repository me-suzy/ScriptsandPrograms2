<?php
/******************************************************************************
 * classi algseks autoriks on php.ee kasutaja stepz
 * http://php.ee/foorum/index.php?post=12085
 *
 * Tehtud muudatused ja täiendused <lauri_k@mail.ru>
 * -----------------------------------------------------------------------------
 * 20.07.2003 - fiksitud bug seoses viimase errori näitamisega (funktsioonis puudus "SQL::")
 * 21.07.2003 02:00 - lisatud funktsioon hosti, kasutajanime ja parooli korraga saatmiseks
 * 02.08.2003 14:03 - fixitud bug funktsioonis _pass()
 * 29.10.2004 18:18 - php5 compatible + muudetud setConnData funktiooni
 * 25.06.2005 20:47 - otsast peale ümberkirjutatud, tehtud lihtsamaks ning lühemaks
 * 19.07.2005 20:08 - esceipimisele lisatud case magic quotesei puhuks
 *****************************************************************************/

class SQL
{
	/**
	 * Ühendumine andmebaasiga
	 * nt: SQL::connect('mysql://user:pass@localhost/db');
	 */

	function connect($path)
	{
		$url = parse_url($path);
		$pass = isset($url['pass']) ? $url['pass'] : '';

		SQL::mem('driver', $url['scheme']);
		SQL::mem('host', $url['host']);
		SQL::mem('user', $url['user']);
		SQL::mem('pass', $pass);
		SQL::mem('db', substr($url['path'], 1));

		SQL::_connect();
		SQL::_selectDB();
	}

	/**
	 * Tagastab ühe rea SELECT päringust välja nimede järgi indekseeritud
	 * massiivis või FALSE vea korral
	 * 
	 * Vaikimisi esimese rea, mittekohustuslik parameeter on rea indeks
	 */
	function getAssoc($Query, $Row = 0)
	{
		$t = SQL::microtime();

		$res = @mysql_query($Query, SQL::mem('conn'));
		if (!$res) 
		{
			SQL::_debug("Get row query failed for '$Query': " . SQL::_MySQLerror()); 
			return false;
		}

		if (@mysql_num_rows($res) < ($Row + 1))
		{
			SQL::_debug("Get row query failed for '$Query': row $Row requested, ".@mysql_num_rows($res)." available"); return false; 
		}

		if (!@mysql_data_seek($res, $Row)) 
		{
			SQL::_debug("Get row query failed to seek to row for '$Query': ". SQL::_MySQLerror());
			return false;
		}
		$row = @mysql_fetch_assoc($res);
		@mysql_free_result($res);

		$time = SQL::microtime() - $t;
		SQL::_debug('getAssoc: ' . $Query, $time );

		return $row;
	}

	/**
	 * Tagastab massiivi, mille võtmeks on SELECT päringust üks väli ja
	 * väärtuseks teine väli
	 * Kui päring ei anna ühtegi rida, siis tagastab tühja massiivi.
	 * 
	 * Vaikimisi võtmeks esimene väli, väärtuseks teine väli
	 */
	function getAssocList($Query, $KeyField = 0, $ValueField = 1)
	{
		$t = SQL::microtime();
		$res = @mysql_query($Query, SQL::mem('conn'));

		if (!$res)
		{
			SQL::_debug("Get list query failed for '$Query': ". SQL::_MySQLerror()); 
			return false; 
		}

		if (@mysql_num_fields($res) < (max($KeyField, $ValueField)+1))
		{
            SQL::_debug("Get associative list query failed for '$Query': field ".max($KeyField, $ValueField)." requested, ".@mysql_num_fields($res)." available");
            return false;
		}

		$list = array();
		while ($row = @mysql_fetch_row($res))
		{
			$list[$row[$KeyField]] = $row[$ValueField];
		}

		@mysql_free_result($res);

		$time = SQL::microtime() - $t;
		SQL::_debug('getAssocList: ' . $Query, $time );

		return $list;        
	}

	/**
	 * Tagastab kogu SELECT päringu kahemõõtmelises massiivis/tulemuse objektis
	 * või FALSE vea korral.
	 * Vaikimisi on ridade massiivid välja nime järgi indekseeritud
	 * Teine parameeter võib omada konstandi väärtusi:
	 *        SQL_NUMERIK            väljad on numbriliselt indekseeritud
	 *        SQL_ASSOC            väljad on välja nime järgi indekseeritud
	 *        SQL_RESULTOBJECT    tagastab objekti, vt. allpool
	 *
	 * Kui päring ei anna ühtegi rida, siis tagastab tühja massiivi, .
	 */    
	function &getAll($Query, $Type = 1)
	{
		$t = SQL::microtime();

		$res = @mysql_query($Query, SQL::mem('conn'));
		if (!$res)
		{
			SQL::_debug("Get all query failed for '$Query': ". SQL::_MySQLerror());
			return false;
		}

		$arr = array();

		switch ($Type)
		{
			case 0:
				while($row = @mysql_fetch_row($res)) $arr[] = $row;
					@mysql_free_result($res);
			break;
			
			case 1:
				while($row = @mysql_fetch_assoc($res)) $arr[] = $row;
					@mysql_free_result($res);
			break;
		}

		$time = SQL::microtime() - $t;
		SQL::_debug('getAll: ' . $Query, $time );

		return $arr;        
	}
	
	/**
	 * Tagastab SELECT päringu, mille väljanimesid on töödeldud
	 * Parameetrid on
	 *        Päring, string
	 *            SELECT päring, mida töödelda
	 *        Väljade vastavus, array
	 *            array("Nimi massiivis" => "Otsitav väli", ...)
	 *            Otsitav väli on kas numbriline indeks päringusse
	 *            või välja nimi
	 *        Alamgrupi definitsioon, array, mittekohustuslik
	 *            array("grupi nimi" => array("Nimi massiivis" => "Otsitav väli", ...))
	 *        Grupeerimise väljad, array, mittekohustuslik
	 *            array("väli mille järgi grupeerida")
	 *
	 *    Näide:
	 *        SQL::map(
	 *            "SELECT cds.cd_id, cds.cdname, tracks.nr, tracks.trackname
	 *            FROM cds, tracks WHERE cds.cd_id = tracks.cd_id
	 *            ORDER BY cds.name, tracks.nr",
	 *            array("ID" => "cd_id", "Name" => "cdname"),
	 *            array("Tracks" => array("Nr" => "nr", "Name" => "trackname")),
	 *            array("cd_id")
	 *        );
	 */    
/*	function map($Query, $Map, $Group = array(), $GroupBy = false)
	{
		if (!is_array($Map) || !count($Map)) 
		{
			SQL::_debug("Get map query failed for '$Query': Invalid Map");
		}
	
		$obj = SQL::getAll($Query, SQL_RESULTOBJECT);
		if (!$obj)
		{
			return false;
		}

		$arr = $obj->map($Map, $Group, $GroupBy);
		$obj->free();
		return $arr;
	}
*/

	/**
	 * Teeb INSERT päringu andmebaasi, kui see tekitab AUTO_INCREMENT väljas
	 * uue väärtuse, siis tagastab selle, muidu tagastab TRUE, kui päring
	 * on vigane, tagastab false
	 */
	function insert($Query)
	{
		$t = SQL::microtime();

		if (!@mysql_query($Query, SQL::mem('conn')))
		{
			SQL::_debug("Insert query failed for '$Query': ". SQL::_MySQLerror()); 
			return false;
		}

		if (@mysql_affected_rows(SQL::mem('conn')) < 1) 
		{
			SQL::_debug("Insert query failed for '$Query': no rows inserted");
			return false;
		}
      
		if ($id = mysql_insert_id(SQL::mem('conn')))
		{
			$time = SQL::microtime() - $t;
			SQL::_debug('insert: ' . $Query, $time );
			return $id;
		}

		$time = SQL::microtime() - $t;
		SQL::_debug('insert: ' . $Query, $time );

		return true;
	}
    
	/**
	 * Teeb mitmekordse INSERT päringu
	 * "INSERT INTO ... VALUES (..., ...), (..., ...)"
	 * Tagastab õnnestunud INSERT'ide arvu, või FALSE päringu vea korral
	 */    
	function bulkInsert($Query)
	{
 		$t = SQL::microtime();

		if (!@mysql_query($Query, SQL::mem('conn')))
		{
			SQL::_debug("Insert query failed for '$Query': ". SQL::_MySQLerror());
			return false;
		}

		$time = SQL::microtime() - $t;
		SQL::_debug('bulkInsert: ' . $Query, $time );

		return @mysql_affected_rows(SQL::mem('conn'));
	}

	/**
	 * Teeb UPDATE päringu, tagastab muudetud ridade arvu
	 */    
	function update($Query)
	{
		$t = SQL::microtime();

		if (!@mysql_query($Query, SQL::mem('conn')))
		{
			SQL::_debug("Update query failed for '$Query': ". SQL::_MySQLerror());
			return false;
		}

		$time = SQL::microtime() - $t;
		SQL::_debug('update: ' . $Query, $time );

		return @mysql_affected_rows(SQL::mem('conn'));
	}
    
	/**
	 * Teeb DELETE päringu, tagastab kustutatud ridade arvu
	 * Kui WHERE ei ole lisatud tagastab 0
	 */    
	function delete($Query)
	{
		$t = SQL::microtime();

		if (!@mysql_query($Query, SQL::mem('conn')))
		{
			SQL::_debug("Delete query failed for '$Query': ". SQL::_MySQLerror());
			return false;
		}

		$time = SQL::microtime() - $t;
		SQL::_debug('delete: ' . $Query, $time );
		
		return @mysql_affected_rows(SQL::mem('conn'));
	}

	/**
	 * Teeb REPLACE päringu, tagastab TRUE õnnestumise korral
	 */    
	function replace($Query)
	{
		$t = SQL::microtime();

		if (!@mysql_query($Query, SQL::mem('conn')))
		{
			SQL::_debug("Replace query failed for '$Query': ". SQL::_MySQLerror());
			return false;
		}

		$time = SQL::microtime() - $t;
		SQL::_debug('replace: ' . $Query, $time );

		return true;
	}

	/**
	 * Tagastab stringi formateerituna sobivalt päringus kasutamiseks
	 */
	function esc($string)
	{
		if(get_magic_quotes_gpc() == true) 
		{ 
			$string = stripslashes($string); 
		}

		if(!function_exists('mysql_real_escape_string'))
		{
			return mysql_escape_string($string);
		}

		return mysql_real_escape_string($string, SQL::mem('conn'));
	}
	 
	/**
	 * Tegelik serveriga ühendumine
	 */
	function _connect()
	{
		if(!SQL::mem('conn', @mysql_connect(SQL::mem('host'), SQL::mem('user'), SQL::mem('pass'))))
		{
			SQL::_debug('connecting to ' . SQL::mem('host') .' fails: ' . SQL::_MySQLerror());
		}
	}

	/**
	 * Andmebaasi valimine
	 */
	function _selectDB()
	{
		if(!@mysql_select_db(SQL::mem('db'), SQL::mem('conn')))
		{
			SQL::_debug('selecting db ' . SQL::mem('db') .' fails: ' . SQL::_MySQLerror());
		}
	}

	/**
	 * Salvestab ning tagastab veateated ning päringud
	 */
	function _debug($action = NULL, $time = 0)
	{
		static $actions;
		if($action!==NULL) 
		{
			$actions[] = array($action, $time);
		}
		return $actions;
	}

	function microtime()
	{
		return array_sum(explode(' ', microtime()));
	}

	/**
	 * Tagastab viimase veateate
	 */    
	function getLastQuery()
	{
		return @join(' : ', @array_pop(SQL::_debug()));
	}

	/**
	 * Tagastab veateated loendina või väljastab ekraanile
	 */
	function getQueries($print = true)
	{
		$result = '';
		$errors = SQL::_debug();
		if(!empty($errors))
		{
			$result = "<ol>\n";
			foreach($errors as $err)
			{
				$result .= "<li>{$err[0]}, {$err[1]}</li>\n";
			}
			$result .= "</ol>\n";
		}

		if($print)
		{
			echo $result;
		}
		else
		{
			return $result;
		}
	}
	
	/**
	 * Tagastab või väljastab päringutele kulunud aja
	 */
	function getTime($print = true)
	{
		$time = 0;
		$qry = SQL::_debug();
		if(!empty($qry))
		{
			foreach($qry as $q)
			{
				$time += $q[1];
			}
		}

		if($print)
		{
			echo $time;
		}
		else
		{
			return $time;
		}
	}

	/**
	 * Tagastab MySQL veateate
	 */
	function _MySQLerror()
	{
		return @mysql_error(SQL::mem('conn'));
	}

	/**
	 * Tagastab $name-le vastava väärtuse või kui antud on
	 * teine parameeter siis salvestab selle $name-sse
	 */
	function mem($name)
	{
		static $data;
		       
		if (func_num_args() > 1)
		{
			$value = func_get_arg(1);
			$data[$name] = $value;
		}

		return $data[$name];
	}
}

?>