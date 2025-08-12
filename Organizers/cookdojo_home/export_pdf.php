<?php
	include ('useall.php');
	
	if (!isset($ebook_title) || empty($ebook_title))
		$ebook_title = "Cookdojo.com's Cook Book";
	
	if (!isset($ebook_by) || empty($ebook_by))
		$ebook_by = "Cookdojo.com";
	
	if (!isset($ebook_category) || empty($ebook_category))
	{
		$i = 0;
		$q = "SELECT * FROM catrecipe ORDER by cat_title ASC";
		$result = mysql_query($q);
		while ($row = mysql_fetch_object($result))
		{
			$ebook_category[$i] = $row->catID;
			$i++;
		}
	}
		
	
	define('FPDF_FONTPATH', 'font/');
	require('mc_table.php');
	
	$pdf=new PDF_MC_Table('P','mm','a4');
	$pdf->AliasNbPages();
	$pdf->Open();
	$pdf->SetMargins(20,30,20);
	
	//1st page	
	$pdf->AddPage();
	//$pdf->ln (50);
	$pdf->SetFont('Arial', 'B', 28);
	$pdf->SetTextColor(0,51,102);
	$pdf->MultiCell (170, 15, "$ebook_title",'' ,'C');
	$pdf->ln (40);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetTextColor(0,0,0);
	$pdf->MultiCell (170, 15, "Compiled by $ebook_by",'' ,'C');
	$pdf->SetTextColor(114,56,56);
	$pdf->ln (70);
	$pdf->SetFont('Arial', '', 8);
	$pdf->MultiCell (170, 15, "Powered by CookDojo Home Edition - Web Based Version",'' ,'C');
	$pdf->Image ("images/logo_white.jpg", 87, 145, 30,'', '','http://www.cookdojo.com');
	
		
	//2nd page
	$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->MultiCell (170, 15, "List of Recipes",'' ,'C');
	$pdf->Ln(15);
	
	for ($i = 0; $i < count ($ebook_category); $i++)
	{
		$cat_title = "";
		$q = "SELECT cat_title FROM catrecipe WHERE catID = '$ebook_category[$i]'";
		mysql_first_data ($q, "cat_title");
		$cat_title = htmlreserve ($cat_title);
		
		$pdf->SetTextColor(16, 88, 14);
		$pdf->SetFont('Arial', 'BU', 10);	
		$link = "link_cat_$ebook_category[$i]";
		$$link = $pdf->AddLink();
		$pdf->Cell (170, 5, "$cat_title",'','','','', $$link);
		$pdf->Ln(6);
		
		$q = "SELECT recipeID, recipe_title FROM recipe WHERE catID = '$ebook_category[$i]'";
		$result = mysql_query ($q);
		while ($row = mysql_fetch_object($result))
		{
			$recipe_title = htmlreserve ($row->recipe_title);
			$recipeID = $row->recipeID;
				
			$pdf->SetFont('Arial', 'U', 10);
			$link = "link_rec_$recipeID";
			$$link = $pdf->AddLink();
			$pdf->Cell (5 , 5, "");
			$pdf->Cell (170, 5, "$recipe_title",'','','','', $$link);
			$pdf->Ln(6);
		}
		$pdf->Ln(6);
		
		
	}
	
	
	
	
	//page 3 - last page
	for ($i = 0; $i < count ($ebook_category); $i++)
	{
		$cat_title = "";
		$q = "SELECT cat_title FROM catrecipe WHERE catID = '$ebook_category[$i]'";
		mysql_first_data ($q, "cat_title");
		$cat_title = htmlreserve ($cat_title);
		
		$pdf->AddPage();
		$link = "link_cat_$ebook_category[$i]";
		$pdf->SetLink($$link);
		
		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->MultiCell (170, 15, "$cat_title",'' ,'C');
		$pdf->Ln(15);
		
		$q = "SELECT * FROM recipe WHERE catID = '$ebook_category[$i]'";
		$result = mysql_query ($q);
		while ($row = mysql_fetch_object($result))
		{
			$recipeID = $row->recipeID;
			$recipe_title = htmlreserve ($row->recipe_title);
			$recipe_ingredients = htmlreserve ($row->recipe_ingredients);
			$recipe_method = htmlreserve ($row->recipe_method);
			$recipe_note = htmlreserve ($row->recipe_note);
			
			$pdf->SetFont('Arial', 'B', 12);
			$pdf->Cell (170, 15, "$recipe_title");
			
			$link = "link_rec_$recipeID";
			$pdf->SetLink($$link , -1);
				
			$pdf->Ln(10);
						
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetTextColor(114,56,56);
			$pdf->Cell (170, 15, "Ingredients");
			$pdf->Ln(10);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);
			$pdf->MultiCell (170, 5, "$recipe_ingredients");
			
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetTextColor(114,56,56);
			$pdf->Cell (170, 15, "Method");
			$pdf->Ln(10);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);
			$pdf->MultiCell (170, 5, "$recipe_method");
			
			if (!empty($row->recipe_note))
			{
				$pdf->SetFont('Arial', '', 10);
				$pdf->SetTextColor(114,56,56);
				$pdf->Cell (170, 15, "Note");
				$pdf->Ln(10);
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetTextColor(0,0,0);
				$pdf->MultiCell (170, 5, "$recipe_note");
			
			}
			
			$pdf->Ln(20);			
		}
		
		
	}
	
	
	
	
	
	$pdf->SetTitle("$ebook_title");
	$pdf->SetAuthor("$ebook_by");
	$pdf->SetCreator("Powered by Cookdojo Home Edition - http://www.cookdojo.com");
	$pdf->Output(urlencode($ebook_title) .".pdf", "I");
?>