<?php
class Template {

    var $blocks = array();
    var $vars = array();

    function set_file($filename, $name = "out") {
        if(is_array($filename)) {
            for(reset($filename); list($k, $v) = each($filename); ) {
                $this->_extract_blocks($k, $this->_load_file($v));
            }
        } else {
            $this->_extract_blocks($name, $this->_load_file($filename));
        }
    }
    function set_var($var, $value = "") {
        if(is_array($var)) {
            for(reset($var); list($k, $v) = each($var); ) {
                $this->vars["/\{$k}/"] = $v;
            }
        } else {
            $this->vars["/\{$var}/"] = $value;
        }
    }
    function parse($target, $block = "", $append = true) {
        if($block == "") {
            $block = $target;
        }
        if(isset($this->blocks["/\{$block}/"])) {
            if($append) {
                $this->vars["/\{$target}/"] .= @preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/\{$block}/"]);
            } else {
                $this->vars["/\{$target}/"] = @preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/\{$block}/"]);
            }
        } 
        return $this->vars["/\{$target}/"];
    }
    function pparse($target = "out", $block = "", $append = 1) {
        $this->parse($target, $block, $append);
        $this->_finish($target);
        return print($this->vars["/\{$target}/"]);
    }
    function p($block) {
        $this->_finish($block);
        return print($this->vars["/\{$block}/"]);
    }
    function o($block) {
        $this->finish($block);
        return $this->vars["/\{$block}/"];
    }
    function get_vars() {
        reset($this->vars);
        while(list($k,$v) = each($this->vars)) {
            preg_match('/^{(.+)}$/', $k, $regs);
            $vars[$regs[1]] = $v;
        }
        return $vars;
    }
    function get_var($varname) {
        if(is_array($varname)) {
            for(reset($varname); list(,$k) = each($varname); ) {
                $result[$k] = $this->vars["/\{$k}/"];
            }
            return $result;
        } else {
            return $this->vars["/\{$varname}/"];
        }
    }
    function get($varname) {
        return $this->vars["/\{$varname}/"];
    }
    function _finish($block) {
            $this->vars["/\{$block}/"] = preg_replace('/{\w+}/', "", $this->vars["/\{$block}/"]);
        }

    function _load_file($filename) {
        if(($fh = fopen("$filename", "r"))) {
            $file_content = fread($fh, filesize("$filename"));
            fclose($fh);
        }
        return $file_content;
    }
    function _extract_blocks($name, $block) {
        $level = 0;
        $current_block = $name;
        $blocks = explode("<!--@ ", $block);
        if(list(, $block) = @each($blocks)) {
            $this->blocks["/\{$current_block}/"] .= $block;
            while(list(, $block) = @each($blocks)) {
                preg_match('/^(FILE|BEGIN|END) (.+) -->(.*)$/s', $block, $regs);
                switch($regs[1]) {
                    case "FILE":
                    $this->_extract_blocks($current_block, $this->_load_file($regs[2]));
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
                    break;

                    case "BEGIN":
                    $this->blocks["/\{$current_block}/"] .= "\{$regs[2]}";
                    $block_names[$level++] = $current_block;
                    $current_block = $regs[2];
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
                    break;

                    case "END":
                    $current_block = $block_names[--$level];
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
                    break;

                    default:
                    $this->blocks["/\{$current_block}/"] .= "<!--@ $block";
                    break;
                }
                unset($regs);
            }
        }
    }

}

?>
