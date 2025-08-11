<?php

	require("cfg.php");
		$sql = "SELECT * FROM ".$db_prefix."members WHERE id='".$_GET['id']."' LIMIT 1";
		$query = mysql_query($sql);
		while ($read=mysql_fetch_array($query)) 
		{
			$picture = $read['picture'];
			$name = $read['name'];
			$age = $read['age'];
			$location = $read['location'];
			$position	= $read['position'];
			$favplayer = $read['favplayer'];
			$favclan = $read['favclan'];
			$favdrink = $read['favdrink'];
			$playertype = $read['playertype'];
			$cpu = $read['cpu'];
			$gfx = $read['gfx'];
			$mouse = $read['mouse'];
			$mousepad = $read['mousepad'];
			$keyboard = $read['keyboard'];
			$headset = $read['headset'];
		}
?>
      <img src="<?=$picture?>" style="width:83px; height:100px; border: 1px solid black;" align="left" />Name: <?=$name?><br/>
      Age: <?=$age?><br/>
      Lives: <?=$location?><br/>
      Position: <?=$position?> 
       <br/><br/>
        Fav. player: <?=$favplayer?><br/>
        Fav. clan: <?=$favclan?><br/>
        Fav. drink: <?=$favdrink?><br/>
        Playertype: <?=$playertype?>
      <p>Computer <?php echo"$cpu - $gfx"; ?><br/>
        Mouse / Mousepad: <?php echo"$mouse / $mousepad"; ?><br/>
        Tangentbord: <?=$keyboard?><br/>
        Headset: <?=$headset?><br/>
        <br/>
        </p>

