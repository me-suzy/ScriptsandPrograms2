<?php
require_once( 'ec-proxy.php' );

if (isset($_GET['debug']) && ($_GET['debug']))
	define('XMLRPC_DEBUG', 1);
 
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title> Electoral and Parlianmentary Tools - XML RPC Client</title>
<meta name="Generator" content="UltraEdit">
<meta name="Author" content="John M. Calvert">
<meta name="Keywords" content="canada election riding electoral district XML RPC">
<meta name="Description" content="Demonstrate electoral tools using XML RPC">
</head>
<body onLoad="JavaScript:document.all.main.postal_code.focus();">
<?php 
if ( $_GET ) {
	$success = False;
	$success_mp = False;
	unset($ec_err);

	$jurisdiction = $_GET['jurisdiction'];

	switch ($_GET['SearchType']) {
	case 'PostalCode':
		$postal_code = $_GET['postal_code'];

		switch ($jurisdiction) {
		case 'CA-CA':
			$success = district_details_from_postal_code($jurisdiction, $postal_code, $district_id, $district_name_eng, $district_name_fre, $adjacent_districts, $district_map_url);
			if (! $success) break;

			$success_mp = parl_details_from_postalcode( $jurisdiction, $postal_code, $MP_name, $MP_district, $MP_photo_URL, $MP_hill_address, $MP_district_address );

			break;

		case 'CA-ON':
			$success = district_details_from_postal_code($jurisdiction, $postal_code, $district_id, $district_name_eng, $district_name_fre, $adjacent_districts, $district_map_url);
			if (! $success) break;

			// $success_mp = parl_details_from_postalcode( $jurisdiction, $postal_code, $MP_name, $MP_district, $MP_photo_URL, $MP_hill_address, $MP_district_address );

			break;

		case 'CA-QC':
			$street_number = $_GET['street_number'];

			$success = district_details_from_postal_code_qc($postal_code, $street_number, $district_id, $district_name_eng, $district_name_fre, $adjacent_districts, $district_map_url);
			// $success_mp = parl_details_from_postalcode( $jurisdiction, $postal_code, $MP_name, $MP_district, $MP_photo_URL, $MP_hill_address, $MP_district_address );

			break;

		default:
			return False;

			break;
			
		}

		break;

	case 'PostalCodeIDOnly':
		$postal_code = $_GET['postal_code'];
	
		$success = district_id_from_postal_code($jurisdiction, $postal_code, $district_id);

		break;

	case 'DistrictID':
		$district_id = $_GET['district_id'];
		
		$success = district_details_from_district_id($jurisdiction, $district_id, $district_name_eng, $district_name_fre, $adjacent_districts, $district_map_url);

		break;

	case 'Address':
		$street_number = $_GET['street_number'];
		$street_name = $_GET['street_name'];
		$street_type = $_GET['street_type'];
		$city = $_GET['city'];
		$province = $_GET['province'];

		$success = district_details_from_address($jurisdiction, $street_number, $street_name, $street_type, $city, $province, $district_id, $district_name_eng, $district_name_fre, $adjacent_districts, $district_map_url);
		if (! $success) break;

		$success = $success && postal_code_from_address($street_number, $street_name, $street_type, $city, $province, $postal_code);
		if (! $success) break;

		switch ($jurisdiction) {
		case 'CA-CA':			
			$success_mp = parl_details_from_postalcode( $jurisdiction, $postal_code, $MP_name, $MP_district, $MP_photo_URL, $MP_hill_address, $MP_district_address );
			break;
		}

		break;

	default:
		$success = False;
		$response['faultString'] = 'Internal error - Invalid FORM SearchType';
		break;
	}
    /* If all went well, show the article */

    if ($success) {
		?>
		<p>Electoral District / Circonscription électorale<br>
		<table border=1 cellborder=1>
		<tr>
			<td>Jurisdiction</td><td>
			<?php
			echo jurisdiction_desc($jurisdiction);
			?>
			</td>
		</tr>
		<tr>
		<td>ID</td><td><?php echo $district_id; ?></td>
		</tr>
		<tr>
		<td>Name (Eng)<br>Nom (Ang)</td><td><?php if ($district_name_eng != '') echo $district_name_eng; else echo 'Not available';?>&nbsp;</td>
		</tr>
		<tr>
		<td>Name (Fre)<br>Nom (Fra)</td><td><?php if ($district_name_fre != '') echo $district_name_fre; else echo 'Non-disponible';?>&nbsp;</td>
		</tr>
		</table>
		</p>
		<p>Adjacent Districts / Circonscription adjacente<br>
		<table border=1 cellborder=1>
		<tr><th>ID</th><th>Name (Eng)<br>Nom (Ang)</th><th>Name (Fre)<br>Nom (Fra)</th></tr>
		<?php
		if (is_array($adjacent_districts) && (count($adjacent_districts) > 0)) {
			while (list ($id, $district) = each ($adjacent_districts)) {
				echo "<tr><td><a href=\"client.php?SearchType=DistrictID&jurisdiction=$jurisdiction&district_id=$district[0]\">" . $district[0] . "</a></td><td>" . $district[1] . "</td><td>" . $district[2] . "</td></tr>\n";
			}		
		} elseif (is_array($adjacent_districts)) {
			echo "<tr><td colspan=3 align=center>None / Aucune</td><tr>\n";
		} else {
			echo "<tr><td colspan=3 align=center>Not available / Non-disponible</td><tr>\n";
		}
		?>
		</table>
		</p>
		<p>Map / Carte<br>
		<?php
		switch ($jurisdiction) {
		case 'CA-CA':
		case 'CA-ON':
			if ($district_map_url != '') {
				?><a href="<?php echo $district_map_url;?>"><img src="<?php echo $district_map_url;?>" alt="Map of <?php echo $district_name_eng; ?> / Carte de <?php echo $district_name_fre; ?>" width="350" height="420" border="0"></a><br>
				<?php
			}
			else
				echo 'Not available / Non-disponible';
			break;
		case 'CA-QC':
			if ($district_map_url != '') {
				?><a href="<?php echo $district_map_url;?>">Link to external site / Lien au site extern</a><br>
				<?php
			}
			else
				echo 'Not available / Non-disponible';
			break;
			break;
		default :
			?>Not available / Non-disponible<?php
			break;
		}
		?>
		</p>
		<p>Profile / Profil<br> 
		<?php
		switch ($jurisdiction) {
		case 'CA-CA':
			?>
			<a href="http://www.elections.ca/scripts/eddb2/Default.asp?L=E&Page=Map&ED=<?php echo $district_id;?>">Federal Electoral District Profile</a><br>
			<a href="http://www.elections.ca/scripts/eddb2/Default.asp?L=F&Page=Map&ED=<?php echo $district_id;?>">Profil de circonscription électorale fédérale</a><br>
			<?php
			break;
		case 'CA-ON':
			?>
			<a href="http://www.electionsontario.on.ca/DistrictDetails.asp?sMenuId=44&district=<?php echo $district_id;?>&flag=E&layout=T">Electoral District Profile</a><br>
			<a href="http://www.electionsontario.on.ca/DistrictDetails.asp?sMenuId=44&district=<?php echo $district_id;?>&flag=F&layout=T">Profil de circonscription électorale provincial</a><br>			
			<?php
			break;
		case 'CA-QC':
			?>
			<a href="http://www.dgeq.qc.ca/cgi-bin/evenement/ds_cand_gen_an.cgi?circon=<?php echo $district_id;?>">Electoral District Profile</a><br>
			<a href="http://www.dgeq.qc.ca/cgi-bin/evenement/ds_cand_gen_fr.cgi?circon=<?php echo $district_id;?>">Profil de circonscription électorale provincial</a><br>			
			<?php
			break;
		default :
			?>Not available / Non-disponible<?php
			break;
		}
		?>
		</p>
		<p>Statistics Canada / Statistiques Canada<br>
		<?php
		switch ($jurisdiction) {
		case 'CA-CA':
			?>
			<a href="http://www12.statcan.ca/fedprofil/Eng/actionRetrieveTable_E.cfm?GeoCode=<?php echo $district_id;?>">Federal Electoral District Profile</a><br>
			<a href="http://www12.statcan.ca/fedprofil/Fre/actionRetrieveTable_F.cfm?GeoCode=<?php echo $district_id;?>">Profil de circonscription électorale fédérale</a><br>
			<?php
			break;
		default :
			?>Not available / Non-disponible<?php
			break;
		}
		?>
		</p>
		<?php
	}
	if ($success_mp) {
		switch ($_GET['SearchType']) {
		case 'Address':
		case 'PostalCode':
			?>
			<table border=1 cellborder=1>
			<tr><th colspan=2>MP Details for district</th></tr>
			<tr>
			<td>Name:</td><td><?php echo $MP_name ;?></td>
			</tr>
			<tr>
			<td>Parliamentary Address</td><td><?php echo $MP_hill_address ;?></td>
			</tr>
			<tr>
			<td>Riding Address</td><td><?php echo $MP_district_address ;?></td>
			</tr>
			<tr>
			<td colspan=2 align=center><img src="<?php echo $MP_photo_URL; ?>"></td>
			</tr>
			</table>
			<?php
			break;

		}

    /* Else display the error */
	}

 	if (!($success && $success_mp) && isset($ec_err)) {
		?>
		<h3>Error</h3>
		<p>There was a problem executing your search request. If this problem persists, please contact the EC Tools team 
		<a href="mailto:ectools@hotmail.com">ectools@hotmail.com</a> to request further help.<br>
		Remember, this web service is experimental and may encounter interruptions or errors from time to time. We will make every effort to
		correct problems as we are made aware of them.<br>
		<br>
		Thanks for your interest.<br>
		EC Tools team.<br>
		</p>
		<p>
        <?php echo ( "<p>Error: " . $ec_err['faultCode'] . " - " . nl2br ( $ec_err['faultString'] ) ); ?>
        </p>
        <?php
    }

	if ($_GET['debug'])
		XMLRPC_debug_print(); 

}
else {
	display_form();
}
?>
</body>
</html>

<?php
function display_form() {
	?>
	<p><b>Determine Electoral District and Parliamentary representative / Determiner circonscription électorale et représentatif(ive) législatif(ive)</b>
	<hr>

	<form action="client.php" method="GET" name="main">
	<input type="hidden" name="SearchType" value="PostalCode">
	<table>
	<tr><td>Postal Code / Code postal:</td>
		<td><input type="text" name="postal_code" maxlength="7" value=""> 
		<input type="submit" value="Search / Recherche"></td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td>Jurisdiction / Juridiction:</td><td><input type="radio" name="jurisdiction" value="CA-CA" checked>Federal / Fédéral</td></tr>
	<tr><td></td><td><input type="radio" name="jurisdiction" value="CA-ON">Ontario / Ontario</td></tr>
	<tr><td></td><td><input type="radio" name="jurisdiction" value="CA-QC">Quebec / Québec&nbsp;(Civic address number / numéro d'adresse de domicile: <input type="text" name="street_number" size="10" maxlength="10">)</td></tr>
	<tr><td>&nbsp;</td><td>Note: Civic address numbers are applicable to individuals only. Search will fail for business addresses.</td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td colspan="2"><input type="checkbox" name="debug"> Show debug info / Afficher infos debug</td></tr>
	</table>
	</form>
	<hr>
	
	<form action="client.php" method="GET">
	<input type="hidden" name="SearchType" value="DistrictID">
	<table>
	<tr><td>District ID / Circonscription ID:</td>
		<td><input type="text" name="district_id" maxlength="5" value=""> 
		<input type="submit" value="Search / Recherche"></td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td>Jurisdiction / Juridiction:</td><td><input type="radio" name="jurisdiction" value="CA-CA" checked>Federal / Fédéral</td></tr>
	<tr><td></td><td><input type="radio" name="jurisdiction" value="CA-ON">Ontario / Ontario</td></tr>
	<tr><td></td><td><input type="radio" name="jurisdiction" value="CA-QC">Quebec / Québec</td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td colspan=2><input type="checkbox" name="debug"> Show debug info / Afficher infos debug</td></tr>
	</table>
	</form>
	<hr>

	<form action="client.php" method="GET">
	<input type="hidden" name="SearchType" value="Address">
	<table>
	<tr><td> Street Number:</td><td><input type="text" name="street_number" size="10" maxlength="10"></td></tr>
	<tr><td> Street Name:</td><td><input type="text" name="street_name" size="30" maxlength="40"></td></tr>
	<tr><td> Street Type:</td><td><input type="text" name="street_type" size="10" maxlength="30"></td></tr>
    <tr><td>Municipality (City, Town, etc.):</td><td><input type="text" name="city" size="30" maxlength="30"></td></tr>
    <tr><td>Province:</td>
    	<td><select size="1" name="province">
          <option selected value="NULL">Select</option>
          <option value="AB">AB - Alberta</option>
          <option value="BC">BC - British Columbia</option>
          <option value="MB">MB - Manitoba</option>
          <option value="NB">NB - New Brunswick</option>
          <option value="NL">NL - Newfoundland and Labrador</option>
          <option value="NS">NS - Nova Scotia</option>
          <option value="NT">NT - Northwest Territories</option>
          <option value="NU">NU - Nunavut</option>
          <option value="ON">ON - Ontario</option>
          <option value="PE">PE - Prince Edward Island</option>
          <option value="QC">QC - Quebec</option>
          <option value="SK">SK - Saskatchewan</option>
          <option value="YT">YT - Yukon</option>
			</select>
		<input type="submit" value="Search / Recherche">
		</td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td>Jurisdiction / Juridiction:</td><td><input type="radio" name="jurisdiction" value="CA-CA" checked>Federal / Fédéral</td></tr>
	<tr><td></td><td><input type="radio" name="jurisdiction" value="CA-ON">Ontario / Ontario</td></tr>
	<tr><td></td><td><input type="radio" name="jurisdiction" value="CA-QC">Quebec / Québec (Street Number is civic address number / numéro d'adresse de domicile)</td></tr>
	<tr><td>&nbsp;</td><td>Note: Civic address numbers are applicable to individuals only. Search will fail for business addresses.</td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td colspan=2><input type="checkbox" name="debug"> Show debug info / Afficher infos debug</td></tr>
	</table>
	</form>
	<hr>

	<h3>Test area</h3>
	<form action="client.php" method="GET">
	<input type="hidden" name="SearchType" value="PostalCodeIDOnly">
	<table>
	<tr><td>Postal Code / Code postal:</td>
		<td><input type="text" name="postal_code" maxlength="7" value=""> 
		<input type="submit" value="Search / Recherche"></td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td colspan=2><input type="checkbox" name="debug" checked> Show debug info / Afficher infos debug</td></tr>
	</table>
	</form>
	<hr>
	
	<table>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<!-- <tr><td colspan=2><input type="checkbox" name="debug"> Show debug info / Afficher infos debug</td></tr> -->
	</table>
	</p>
	<?php
}

function jurisdiction_desc($jurisdiction) {

	switch ($jurisdiction) {
	case 'CA-CA':
		return 'Federal / Fédérale'; 
		break;
	case 'CA-ON':
		return 'Ontario / Ontario'; 
		break;
	case 'CA-QC':
		return 'Quebec / Québec'; 
		break;
	default:
		return 'Unknown / Inconnue';
		break;
	}
}

/* Function to give us back a nice date */
function convert_date ( $date ) {
    $date = date ( "D M y H:i:s",
                    XMLRPC_convert_iso8601_to_timestamp ( $date ) );
    return ( $date );
}
?>
