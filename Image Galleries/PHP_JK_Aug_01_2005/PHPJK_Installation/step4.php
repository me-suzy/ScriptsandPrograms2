<?php
	Require("includes.php");
	Require("../Includes/i_Includes.php");
	Require("SQL/index.php");
	
	
	OpenPage();
	Main();
	ClosePage();
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		?>
		<b>Successfully installed the PHP JackKnife Gallery System!</b>
		<br><br>
		Please remove the PHPJK_Installation folder from your website.
		<bR><br>
		<a href='../Admin/ManageGalleries/'>Click Here</a> to manage your gallery.
		<br><br>
		<b>If you would like to sell your images as <u>prints, t-shirts or gift items</u>, OR, if you 
		would like to purchase prints or gift items for yourself, please visit 
		<a href='http://www.aricaur.com' target='_blank'>Aricaur.com</a> for more information.
		<?php
	}
	//************************************************************************************
?>