<?php
require('htmlparser.inc');
class htmlTabletoDb
{
	function ParseTable($Table)
	{
		$_var='';
		$htmlText = $Table;
		$parser = new HtmlParser ($htmlText);
		while ($parser->parse()) {
			if(strtolower($parser->iNodeName)=='table')
			{
				if($parser->iNodeType == NODE_TYPE_ENDELEMENT)
					$_var .='/::';
				else
					$_var .='::';
			}
	
			if(strtolower($parser->iNodeName)=='tr')
			{
				if($parser->iNodeType == NODE_TYPE_ENDELEMENT)
					$_var .='!-:'; //opening row
				else
					$_var .=':-!'; //closing row
			}
			if(strtolower($parser->iNodeName)=='td' && $parser->iNodeType == NODE_TYPE_ENDELEMENT)
			{
				$_var .='#,#';
			}
			if ($parser->iNodeName=='Text' && isset($parser->iNodeValue))
			{
				$_var .= $parser->iNodeValue;
			}
		}
		$elems = split(':-!',str_replace('/','',str_replace('::','',str_replace('!-:','',$_var)))); //opening row
		foreach($elems as $key=>$value)
		{
			if(trim($value)!='')
			{
				$elems2 = split('#,#',$value);
				array_pop($elems2);
				$data[] = $elems2;
			}
		}
		return $data;
	}

}
?>
