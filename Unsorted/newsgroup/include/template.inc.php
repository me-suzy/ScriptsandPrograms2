<?php
/*
 * Template Class for PHP3
 *
 * (C) Copyright 1999-2000 NetUSE GmbH
 *                    Kristian Koehntopp
 *
 * $Id: template.txt,v 1.7 2001/08/10 01:55:54 rha Exp $
 *
 */

/*
 * Change log since version 7.2c
 *
 * Bug fixes to version 7.2c compiled by Richard Archer <rha@juggernaut.com.au>:
 * (credits given to first person to post a diff to phplib mailing list)
 * set_file failed to check for empty strings if passed an array of filenames (rha)
 * set_block unnecessarily required a newline in the template file (Marc Tardif & rha)
 * set_var was missing a set of braces (rha)
 * '$' in replacement text was being stripped in PHP 4.0.4+ (John Mandeville)
 * pparse now calls this->finish to replace undefined vars (Layne Weathers)
 * get_vars now uses a call to get_var rather than this->varvals (rha)
 * get_var now checks for unset varnames (NickM & rha)
 * in finish, the replacement string referenced an unset variable (rha)
 * loadfile does not need to use filename() as set_file already does. (Sebastien Barre)
 * remove @ from call to preg_replace in subst -- report errors if there are any. (NickM)
 * in get_undefined, only match non-whitespace to avoid stripping javascript and style sheets. (Layne Weathers & rha)
 * parse does not append correctly if passed an array (Brian)
 * added optional braces to if statements in: get_undefined, loadfile and halt (rha)
 *
 * Changes since version 7.2c
 * debug output contained raw HTML -- is now escaped with htmlentities (rha)
 * changed debug handling so set, get and internals can be tracked separately (rha)
 * added debug statements throughout to track most function calls :) (rha)
 * Simplified logic in loadfile that determines whether or not to load a file (rha)
 * Alter regex in set_block to remove more whitespace around BEGIN/END tags to improve HTML layout (rha)
 * Add "append" option to set_var, works just like append in parse (dale at linuxwebpro.com, rha)
 * Converted comments and documentation to phpdoc style. (rha)
 *
 */

class Template {
  var $classname = "Template";

  /* if set, echo assignments
   * 1 = debug set
   * 2 = debug get
   * 4 = debug internals
   */
  var $debug     = false;

  /* $file[varname] = "filename"; */
  var $file  = array();

  /* relative filenames are relative to this pathname */
  var $root   = "";

  /* $varkeys[key] = "key"; $varvals[key] = "value"; */
  var $varkeys = array();
  var $varvals = array();

  /* "remove"  => remove undefined variables
   * "comment" => replace undefined variables with comments
   * "keep"    => keep undefined variables
   */
  var $unknowns = "remove";

  /* "yes" => halt, "report" => report error, continue, "no" => ignore error quietly */
  var $halt_on_error  = "yes";

  /* last error message is retained here */
  var $last_error     = "";


  /***************************************************************************/
  /* public: Constructor.
   * root:     template directory.
   * unknowns: how to handle unknown variables.
   */
  function Template($root = ".", $unknowns = "remove") {
    if ($this->debug & 4) {
      echo "<p><b>Template:</b> root = $root, unknowns = $unknowns</p>\n";
    }
    $this->set_root($root);
    $this->set_unknowns($unknowns);
  }


  /***************************************************************************/
  /* public: set_root(pathname $root)
   * root:   new template directory.
   */
  function set_root($root) {
    if ($this->debug & 4) {
      echo "<p><b>set_root:</b> root = $root</p>\n";
    }
    if (!is_dir($root)) {
      $this->halt("set_root: $root is not a directory.");
      return false;
    }

    $this->root = $root;
    return true;
  }


  /***************************************************************************/
  /* public: set_unknowns(enum $unknowns)
   * unknowns: "remove", "comment", "keep"
   *
   */
  function set_unknowns($unknowns = "remove") {
    if ($this->debug & 4) {
      echo "<p><b>unknowns:</b> unknowns = $unknowns</p>\n";
    }
    $this->unknowns = $unknowns;
  }


  /***************************************************************************/
  /* public: set_file(array $filelist)
   * filelist: array of varname, filename pairs.
   *
   * public: set_file(string $varname, string $filename)
   * varname: varname for a filename,
   * filename: name of template file
   */
  function set_file($varname, $filename = "") {
    if (!is_array($varname)) {
      if ($this->debug & 4) {
        echo "<p><b>set_file:</b> (with scalar) varname = $varname, filename = $filename</p>\n";
      }
      if ($filename == "") {
        $this->halt("set_file: For varname $varname filename is empty.");
        return false;
      }
      $this->file[$varname] = $this->filename($filename);
    } else {
      reset($varname);
      while(list($h, $f) = each($varname)) {
        if ($this->debug & 4) {
          echo "<p><b>set_file:</b> (with array) varname = $h, filename = $f</p>\n";
        }
        if ($f == "") {
          $this->halt("set_file: For varname $h filename is empty.");
          return false;
        }
        $this->file[$h] = $this->filename($f);
      }
    }
    return true;
  }


  /***************************************************************************/
  /* public: set_block(string $parent, string $varname, string $name = "")
   * extract the template $varname from $parent,
   * place variable {$name} instead.
   */
  function set_block($parent, $varname, $name = "") {
    if ($this->debug & 4) {
      echo "<p><b>set_block:</b> parent = $parent, varname = $varname, name = $name</p>\n";
    }
    if (!$this->loadfile($parent)) {
      $this->halt("set_block: unable to load $parent.");
      return false;
    }
    if ($name == "") {
      $name = $varname;
    }

    $str = $this->get_var($parent);
    $reg = "/[ \t]*<!--\s+BEGIN $varname\s+-->\s*?\n?(\s*.*?\n?)\s*<!--\s+END $varname\s+-->\s*?\n?/sm";
    $str = ereg_replace( '\$([0-9])', '&#36;\1', $str );
    preg_match_all($reg, $str, $m);
    $str = preg_replace($reg, "{" . "$name}", $str);
    $this->set_var($varname, $m[1][0]);
    $this->set_var($parent, $str);
  }


  /***************************************************************************/
  /* public: set_var(array $values, $dummy_var, boolean $append)
   * values: array of variable name, value pairs.
   * dummy_var: ignored variable to make append the third parameter for consistency
   * append: append to target varname
   *
   * public: set_var(string $varname, string $value, boolean $append)
   * varname: name of a variable that is to be defined
   * value:   value of that variable
   * append: append to target varname
   */
  function set_var($varname, $value = "", $append = false) {
    if (!is_array($varname)) {
      if (!empty($varname)) {
        if ($this->debug & 1) {
          printf("<b>set_var:</b> (with scalar) <b>%s</b> = '%s'<br>\n", $varname, htmlentities($value));
        }
        $value = ereg_replace( '\$([0-9])', '&#36;\1', $value );
        $this->varkeys[$varname] = "/".$this->varname($varname)."/";
        if ($append && isset($this->varvals[$varname])) {
          $this->varvals[$varname] .= $value;
        } else {
          $this->varvals[$varname] = $value;
        }
      }
    } else {
      reset($varname);
      while(list($k, $v) = each($varname)) {
        if (!empty($k)) {
          if ($this->debug & 1) {
            printf("<b>set_var:</b> (with array) <b>%s</b> = '%s'<br>\n", $k, htmlentities($v));
          }
          $v = ereg_replace( '\$([0-9])', '&#36;\1', $v );
          $this->varkeys[$k] = "/".$this->varname($k)."/";
          if ($append && isset($this->varvals[$k])) {
            $this->varvals[$k] .= $v;
          } else {
            $this->varvals[$k] = $v;
          }
        }
      }
    }
  }


  /***************************************************************************/
  /* public: subst(string $varname)
   * varname: varname of template where variables are to be substituted.
   */
  function subst($varname) {
    if ($this->debug & 4) {
      echo "<p><b>subst:</b> varname = $varname</p>\n";
    }
    if (!$this->loadfile($varname)) {
      $this->halt("subst: unable to load $varname.");
      return false;
    }

    $str = $this->get_var($varname);
    $str = preg_replace($this->varkeys, $this->varvals, $str);
    return $str;
  }


  /***************************************************************************/
  /* public: psubst(string $varname)
   * varname: varname of template where variables are to be substituted.
   */
  function psubst($varname) {
    if ($this->debug & 4) {
      echo "<p><b>psubst:</b> varname = $varname</p>\n";
    }
    print $this->subst($varname);

    return false;
  }


  /***************************************************************************/
  /* public: parse(string $target, string $varname, boolean append)
   * public: parse(string $target, array  $varname, boolean append)
   * target: varname of variable to generate
   * varname: varname of template to substitute
   * append: append to target varname
   */
  function parse($target, $varname, $append = false) {
    if (!is_array($varname)) {
      if ($this->debug & 4) {
        echo "<p><b>parse:</b> (with scalar) target = $target, varname = $varname, append = $append</p>\n";
      }
      $str = $this->subst($varname);
      if ($append) {
        $this->set_var($target, $this->get_var($target) . $str);
      } else {
        $this->set_var($target, $str);
      }
    } else {
      reset($varname);
      while(list($i, $h) = each($varname)) {
        if ($this->debug & 4) {
          echo "<p><b>parse:</b> (with array) target = $target, i = $i, varname = $h, append = $append</p>\n";
        }
        $str = $this->subst($h);
        if ($append) {
          $this->set_var($target, $this->get_var($target) . $str);
        } else {
          $this->set_var($target, $str);
        }
      }
    }

    if ($this->debug & 4) {
      echo "<p><b>parse:</b> completed</p>\n";
    }
    return $str;
  }


  /***************************************************************************/
  function pparse($target, $varname, $append = false) {
    if ($this->debug & 4) {
      echo "<p><b>pparse:</b> passing parameters to parse...</p>\n";
    }
    print $this->finish($this->parse($target, $varname, $append));
    return false;
  }


  /***************************************************************************/
  /* public: get_vars()
   * return all variables as an array (mostly for debugging)
   */
  function get_vars() {
    if ($this->debug & 4) {
      echo "<p><b>get_vars:</b> constructing array of vars...</p>\n";
    }
    reset($this->varkeys);
    while(list($k, $v) = each($this->varkeys)) {
      $result[$k] = $this->get_var($k);
    }
    return $result;
  }


  /***************************************************************************/
  /* public: get_var(string varname)
   * varname: name of variable.
   *
   * public: get_var(array varname)
   * varname: array of variable names
   */
  function get_var($varname) {
    if (!is_array($varname)) {
      $tmp = isset($this->varvals[$varname]) ? $this->varvals[$varname] : "";
      if ($this->debug & 2) {
        printf ("<b>get_var</b> (with scalar) <b>%s</b> = '%s'<br>\n", $varname, htmlentities($tmp));
      }
      return $tmp;
    } else {
      reset($varname);
      while(list($k, $v) = each($varname)) {
        $tmp = isset($this->varvals[$v]) ? $this->varvals[$v] : "";
        if ($this->debug & 2) {
          printf ("<b>get_var:</b> (with array) <b>%s</b> = '%s'<br>\n", $v, htmlentities($tmp));
        }
        $result[$v] = $tmp;
      }
      return $result;
    }
  }


  /***************************************************************************/
  /* public: get_undefined($varname)
   * varname: varname of a template.
   */
  function get_undefined($varname) {
    if ($this->debug & 4) {
      echo "<p><b>get_undefined:</b> varname = $varname</p>\n";
    }
    if (!$this->loadfile($varname)) {
      $this->halt("get_undefined: unable to load $varname.");
      return false;
    }

    preg_match_all("/{([^ \t\r\n}]+)}/", $this->get_var($varname), $m);
    $m = $m[1];
    if (!is_array($m)) {
      return false;
    }

    reset($m);
    while(list($k, $v) = each($m)) {
      if (!isset($this->varkeys[$v])) {
        if ($this->debug & 4) {
         echo "<p><b>get_undefined:</b> undefined: $v</p>\n";
        }
        $result[$v] = $v;
      }
    }

    if (count($result)) {
      return $result;
    }
    else {
      return false;
    }
  }


  /***************************************************************************/
  /* public: finish(string $str)
   * str: string to finish.
   */
  function finish($str) {
    switch ($this->unknowns) {
      case "keep":
      break;

      case "remove":
        $str = preg_replace('/{[^ \t\r\n}]+}/', "", $str);
      break;

      case "comment":
        $str = preg_replace('/{([^ \t\r\n}]+)}/', "<!-- Template variable \\1 undefined -->", $str);
      break;
    }

    return ereg_replace('&#36;([0-9])','$\1',$str);
  }


  /***************************************************************************/
  /* public: p(string $varname)
   * varname: name of variable to print.
   */
  function p($varname) {
    print $this->finish($this->get_var($varname));
  }
  
  /***************************************************************************/
  /* public: p_file(string $varname)
   * varname: name of variable to print.
   * Txarly :) Hack!! Print the complete output to a file!!
   */
  function p_file($varname , $path , $file_name) {
    // Open the file
  	$fp = fopen ($path.$file_name, "w+");
	fwrite($fp,$this->finish($this->get_var($varname)));
	fclose($fp);
    //print $this->finish($this->get_var($varname));
  }


  /***************************************************************************/
  function get($varname) {
    return $this->finish($this->get_var($varname));
  }


  /***************************************************************************/
  /* private: filename($filename)
   * filename: name to be completed.
   */
  function filename($filename) {
    if ($this->debug & 4) {
      echo "<p><b>filename:</b> filename = $filename</p>\n";
    }
    if (substr($filename, 0, 1) != "/") {
      $filename = $this->root."/".$filename;
    }

    if (!file_exists($filename)) {
      $this->halt("filename: file $filename does not exist.");
    }
    return $filename;
  }


  /***************************************************************************/
  /* private: varname($varname)
   * varname: name of a replacement variable to be protected.
   */
  function varname($varname) {
    return preg_quote("{".$varname."}");
  }


  /***************************************************************************/
  /* private: loadfile(string $varname)
   * varname:  load file defined by varname, if it is not loaded yet.
   */
  function loadfile($varname) {
    if ($this->debug & 4) {
      echo "<p><b>loadfile:</b> varname = $varname</p>\n";
    }

    if (!isset($this->file[$varname])) {
      // This varname does not reference a file
      if ($this->debug & 4) {
        echo "<p><b>loadfile:</b> varname $varname does not reference a file</p>\n";
      }
      return true;
    }

    if (isset($this->varvals[$varname])) {
      // This varname is already loaded
      if ($this->debug & 4) {
        echo "<p><b>loadfile:</b> varname $varname is already loaded</p>\n";
      }
      return true;
    }
    $filename = $this->file[$varname];

    /* use @file here to avoid leaking filesystem information if there is an error */
    $str = implode("", @file($filename));
    if (empty($str)) {
      $this->halt("loadfile: While loading $varname, $filename does not exist or is empty.");
      return false;
    }

    if ($this->debug & 4) {
      printf("<b>loadfile:</b> loaded $filename into $varname<br>\n");
    }
    $this->set_var($varname, $str);

    return true;
  }


  /***************************************************************************/
  /* public: halt(string $msg)
   * msg:    error message to show.
   */
  function halt($msg) {
    $this->last_error = $msg;

    if ($this->halt_on_error != "no") {
      $this->haltmsg($msg);
    }

    if ($this->halt_on_error == "yes") {
      die("<b>Halted.</b>");
    }

    return false;
  }


  /***************************************************************************/
  /* public, override: haltmsg($msg)
   * msg: error message to show.
   */
  function haltmsg($msg) {
    printf("<b>Template Error:</b> %s<br>\n", $msg);
  }

}
?>
