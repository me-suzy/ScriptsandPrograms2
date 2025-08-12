<?php
	include('config.php');
?>
<style>
	.dice-div {
		text-align:center;
	}
	.dice-div a {
		font-size:10px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#666666;
	}
</style>
<div class="dice-div" />
<img src="http://<?php echo $dice_absolute_url; ?>/online-dice/dice.php?style=<?php echo $dice_style; ?>&amp;dice=<?php echo $dice_num; ?>" alt="Dice by Online-Game-Rules.com" /><br />
<a href="http://www.online-game-rules.com">Online-Game-Rules.com</a>
</div>