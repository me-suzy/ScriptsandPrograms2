<?php
	include ('useall.php');
	
	if (!isset($recID))
		$recID = "";
		
	if ($size == "4x6")
		$width = 80;
	else
		$width = 105;
	
	$recipe_title = "";
	$recipe_ingredients  = "";
	$recipe_method = "";
	$recipe_note = "";
	$q = "SELECT * FROM recipe WHERE recipeID = '$recID'";
	mysql_first_data ($q, "recipe_title|recipe_ingredients|recipe_method|recipe_note");
	
	$recipe_title = htmlreserve($recipe_title);
	$recipe_ingredients  = htmlreserve($recipe_ingredients);
	$recipe_method = htmlreserve($recipe_method);
	$recipe_note = htmlreserve($recipe_note);
	
	
	define('FPDF_FONTPATH', 'font/');
	require('mc_table_indeks.php');
	
	$pdf=new PDF_MC_Table('P','mm',$size);
	$pdf->AliasNbPages();
	$pdf->Open();
	$pdf->SetMargins(13,13,10);
	
	//1st page	
	$pdf->AddPage();
	$pdf->ln(1);
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->MultiCell ($width, 5, $recipe_title,'0' ,'L');
	
	//Ingredients
	$pdf->ln (5);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetTextColor(114,56,56);
	$pdf->Cell ($width, 5, "Ingredients");
	$pdf->ln (5);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetTextColor(0);
	$pdf->MultiCell ($width, 3, $recipe_ingredients,'0' ,'L');
	
	//Method
	$pdf->ln (5);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetTextColor(114,56,56);
	$pdf->Cell ($width, 5, "Method");
	$pdf->ln (5);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetTextColor(0);
	$pdf->MultiCell ($width, 3, $recipe_method,'0' ,'J');
	
	//Note
	if (!empty($recipe_note))
	{	
		$pdf->ln (5);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(114,56,56);
		$pdf->Cell ($width, 5, "Note");
		$pdf->ln (5);
		$pdf->SetFont('Arial', '', 6);
		$pdf->SetTextColor(0);
		$pdf->MultiCell ($width, 3, $recipe_note,'0' ,'J');
	}
	
	$pdf->SetDisplayMode('real');
	$pdf->SetTitle("$recipe_title");
	$pdf->SetAuthor("Cookdojo.com");
	$pdf->SetCreator("Powered by Cookdojo Home Edition - http://www.cookdojo.com");
	$pdf->Output("recipe-$recID-$size.pdf", "I");
?>