<?php
/*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2003  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972

This module is the interface to paymentclearing.com's gateway.
*/

// for some reason they want the month spelled out
$mname[] = '';			// eat the 0th entry
$mname[] = 'January';
$mname[] = 'February';
$mname[] = 'March';
$mname[] = 'April';
$mname[] = 'May';
$mname[] = 'June';
$mname[] = 'July';
$mname[] = 'August';
$mname[] = 'September';
$mname[] = 'October';
$mname[] = 'November';
$mname[] = 'December';

// make sure ccexp_month is an int
$ccexp_month = (int)$ccexp_month;

$query_string  = '';
$query_string .= '&vendor_id=YOUR_MERCHANT_ID';
$query_string .= '&email='.urlencode($billing_email);
$query_string .= '&first_name='.urlencode($billing_first);
$query_string .= '&last_name='.urlencode($billing_last);
$query_string .= '&address='.urlencode($billing_address1);
$query_string .= '&city='.urlencode($billing_city);
$query_string .= '&state='.urlencode($billing_state);
$query_string .= '&zip='.urlencode($billing_zip);
$query_string .= '&country='.urlencode($billing_country);
$query_string .= '&phone='.urlencode($billing_phone);
$query_string .= '&ccnum='.urlencode($cc_number);
$query_string .= '&ccmo='.urlencode( $mname[$ccexp_month] );
$query_string .= '&ccyr='.urlencode($ccexp_year);
$query_string .= '&sfname='.urlencode($shipping_first);
$query_string .= '&slname='.urlencode($shipping_last);
$query_string .= '&saddr='.urlencode($shipping_address1);
$query_string .= '&scity='.urlencode($shipping_city);
$query_string .= '&sstate='.urlencode($shipping_state);
$query_string .= '&szip='.urlencode($shipping_zip);
$query_string .= '&sctry='.urlencode($shipping_country);
$query_string .= '&home_page='.$nsecurl;
$query_string .= '&ret_mode=redirect';
$query_string .= '&ret_addr=https://'.$securl.$secdir.'/orderfinal.php';
$query_string .= '&1_desc='.urlencode('COMPANY Order Total');
$query_string .= '&1_cost='.urlencode(sprintf("%.2f",$ttotal));
$query_string .= '&1_qty=1';
$query_string .= '&';

exec('curl -i -d '.$query_string.' https://secure.paymentclearing.com/cgi-bin/rc/ord.cgi', $pay_result, $ret);

$j = count($pay_result);

// debug display of returned array
//echo "result display from curl, count $j<br><pre>"; flush();
//for ( $i = 0; $i < $j; $i++ ) { echo "$i: $pay_result[$i]<br>\n"; flush(); }
//echo "</pre>"; flush();

$pmt_success = 0;
for ( $i = 0; $i < $j; $i++ ) {
	if( preg_match("/^Location:.*orderfinal.php/",$pay_result[$i]) ){
	    // assume good results

		$pmt_success = 1;
		break;

	}elseif( preg_match("/Code:\s+(\w+)\s+([^<]*)/",$pay_result[$i],$matches) ){
		// error, get the code
		$err_code = $matches[1];
		$err_text = $matches[2];
?>

We are sorry, but your order cannot be processed at this time;
either your card was not accepted or an error has occurred.  The
error code and error message returned below.  Thank you.
<p>
Code: <?php echo $err_code ?><br>
Text: <?php echo $err_text ?>
<p>
<a href="<?php echo $nsecurl; ?>">COMPANY Home Page</a>
<br>

<?php 
		$fcoc->rollback();
		if ( 'mysql' == 'mysql' ){
			$flck->query("unlock tables");
		}
 		exit;
	}
}
if( !$pmt_success ){
?>
We are sorry, but your order cannot be processed at this time; an 
unspecified error has occurred.  Please try again later; thank you.
<p>
<a href="<?php echo $nsecurl; ?>">COMPANY Home Page</a>
<br>
<?php
	exit;
}
?>
