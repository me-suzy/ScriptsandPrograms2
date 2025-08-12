<?php
	Require("Includes/i_Includes.php");
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Require("SendECard/i_ECard.php");
	L_Display_ECard(Trim(Request("iCardUnq")));
	Echo "<BR>";
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
?>