<?
//*****************************  PAGES	0.4 *******************************
class pages 
{
	var $start;
	var $lim=10;
	var $zapros;
	var $query;
	var $type="normal";
	var $script;
	var $vars;
	var $titlenext="Ñëåäóþùàÿ ñòðàíèöà";
	var $titleprev="Ïðåäûäóùàÿ ñòðàíèöà";
	var $center=true;
	function pages(){
		if(empty($this->start)){
			global $start;
			$this->start=$start;
			if(empty($this->start)){
				$this->start=0;
			}
		}
		if(empty($this->script)){
			global $PHP_SELF;
			$this->script=$PHP_SELF;
		}
	}
	function check()
	{	
		$query = (empty($this->zapros)? $this->query:$this->zapros);
		$res=mysql_query($query);
		$cnt=mysql_num_rows($res);
		$query_lim=$query." LIMIT $this->start,$this->lim";
		$res_lim=mysql_query($query_lim);
		$do[0]=$res_lim;
		if($cnt > $this->lim){
			$do[1]=true;}
		else{
			$do[1]=false;}	
		$do[2]=$cnt;
		return $do;
	}
	function make_list()
	{
		$wtd=$this->check();
		$start=$this->start;
		$lim=$this->lim;
		$allrec=$wtd[2];
		if($wtd[1]){
			if($this->center) print "<div align=\"center\">";
			$page=($start/$lim)+1;
			$allpages=(intval($allrec/$lim))+1;
			$last = $start-$lim;
			$next = $start+$lim;
			if(is_array($this->vars)){
				foreach($this->vars as $key=>$val)
				{
					$suffix.="&$key=$val";
				} 
			}
			print ($page > 1 ? "<a title=\"$this->titleprev\" href=\"$this->script?start=$last$suffix\">&lt;</a>\n" : "&lt;");
			if($this->type=="extend"){
				for($n=1;$n<=$allpages;$n++){
					$nu=($n-1)*$lim;
					$li=$nu+1;
					$fop=$n*$lim;
					print ($n==$page ? " $li - $fop" : " <a href=\"$this->script?start=$nu$suffix\">".$li." - ".$fop."</a>\n");
				}
			}
			elseif($this->type=="normal"){
				for($n=1;$n<=$allpages;$n++){
					$nu=($n-1)*$lim;
					print ($n==$page ? " $n" : " <a href=\"$this->script?start=$nu$suffix\" title=\"$n\">$n</a>\n");
				}
			}
			print ($page < $allpages ? "<a title=\"$this->titlenext\" href=\"$this->script?start=$next$suffix\">&gt;</a>\n" : " &gt;");
			if($this->center) print "</div><br>";
		}
	}
}
//****************************
?>
