<?
class Pager{
	function Pager($rows=0,$count=0){
		$this->html = array();
		if($count==0)return;
		$this->postget = $_POST?$_POST:$_GET;
		
		$this->rows = ($rows==0)?$count:$rows;
		$this->count = $count;
		$offset = (isset($this->postget["offset"]))?(int)$this->postget["offset"]:0;
		
		if(isset($this->postget["direction"]))
			$direction = $this->postget["direction"];
			else 
			if(!isset($this->postget["offset"]))
				$direction = 'f';
				
		$offset_last = (int)($this->count / $this->rows) * $this->rows;
		if($offset_last == $this->count)
			$offset_last -= $this->rows;
							
		switch ($direction){
			case 'f': $offset = 0; break;
			case 'p': $offset-= $this->rows; break;
			case 'n': $offset+= $this->rows; break;
			case 'l': $offset = $offset_last;break;
		}
		
		if($offset > $offset_last )
			$offset = $offset_last;
		if($offset < 0)
			$offset = 0;
		
		$html["link"]["f"] = $html["link"]["p"] =  ($offset == 0)?"0":"1";
		$html["link"]["l"] = $html["link"]["n"] = ($this->count - $offset >  $this->rows)?"1":"0";
		
		if($offset == $offset_last || $html["link"]["l"] == 0)
				$html["last_page"] = true;
				else 
				$html["last_page"] = false;

		$html['offset'] = $offset;
		$html['direction'] = $direction;	

		$html['pages'] = array();
//		echo '<hr>($this->count / $this->rows):'."(int)($this->count / $this->rows)=".(int)($this->count / $this->rows);
//		echo '<hr>($this->count % $this->rows):'.($this->count % $this->rows);
		$pages = (int)($this->count / $this->rows) ;
		if($this->count % $this->rows >0)$pages++;
		for($i=0;$i< $pages;$i++){
			$html['pages'][$i]['title'] = $i+1;
			$html['pages'][$i]['offset'] = $i*$this->rows;
			$html['pages'][$i]['active'] = ($i*$this->rows != $offset)?'true':'false';
		}
		//var_dump($html['pages']);
		$this->html = $html;
		$this->offset = $offset;
		$this->items = $this->rows;
	}
	
}
?>