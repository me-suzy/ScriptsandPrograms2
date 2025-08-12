<?php
function geticons($itemid,$itemicons2,$itemicon_def2,$aorb){
	//SO.. if you have just an item id, call as 
	//geticons($item[id],0,0)
	//but, if you have icon and icon_def sets to show as pics, like 0,0,1,0,2,4, call as
	//geticons(0,$icons,$icon_def)
	$icons[0]=stab;
	$icons[1]=slash;
	$icons[2]=arrow;
	$icons[3]=fire;
	$icons[4]=water;
	$icons[5]=lightning;
	if(!$itemicons2&&!$itemicon_def2){
		$item=mysql_fetch_array(mysql_query("select * from items where id='$itemid' limit 1"));
		$itemicons2=$item[icons];
		$itemicon_def2=$item[icon_def];
	}
	$itemicons=explode(",",$itemicons2);
	$itemicon_def=explode(",",$itemicon_def2);
	$returnstrA="";
	$returnstrB="";
	$returnstr="";
	$i=0;
	while($i<=5){
		if($itemicons[$i]>0){
			$countdown[$i]=1;
			while($countdown[$i]<=$itemicons[$i]){
				$returnstrA.="<img src=/img/icon_"."$icons[$i]".".gif>";
				$countdown[$i]=$countdown[$i]+1;
			}
		}
		if($itemicon_def[$i]>0){
			$countdown2[$i]=1;
			while($countdown2[$i]<=$itemicon_def[$i]){
				$returnstrB.="<img src=/img/icon_"."$icons[$i]"."_def.gif>";
				$countdown2[$i]=$countdown2[$i]+1;
			}
		}
		$i=$i+1;
	}
	if($aorb=="A"){
		$returnstr=$returnstrA;
	}elseif($aorb=="B"){
		$returnstr=$returnstrB;
	}else{
		$returnstr="$returnstrA $returnstrB";
	}
	return $returnstr;
}
?>