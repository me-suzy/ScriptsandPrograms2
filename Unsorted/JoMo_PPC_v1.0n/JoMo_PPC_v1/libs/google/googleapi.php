<?

require_once __GOOGLE_DIR.'SOAP_Google.php';

function findGoogle($q, $s, $maxResults, $ie="latin1",$oe="latin1"){

//$q="mail";
//$s=$s+1;

//dprint("here");
$maxResults = $maxResults>10 ? 10: $maxResults;
if ($maxResults<=0) return false;
//dprint($maxResults);

$nr = $maxResults; 
$clef = "DccRTSDhAARiU8EI/B+vuYxWXwZHf/bi";


$google = new SOAP_Google($clef);

$result = $google->search(
  array(
    'query' => $q,
    'start' => $s,
    'maxResults' => $nr,
    'ie' => 'latin1',
    'oe'  => 'latin1'
  )
);

if (false !== $result) 

//if (1==1)
{

   $links = array();

   $res = $result['resultElements'];
   $i = $result['startIndex'];
   foreach ($res as $site) {
        $links[]=array("linkID"=>0, "linkURL"=>$site['URL'], "url"=>$site['URL'], "title"=>$site['title'], "description"=>$site['snippet'], "bid"=>0);
 //       $i++;
//        echo "new site $i<br>";

   }
//   print_r($links);
//   $googleResults ["totalLinks"] = $result['estimatedTotalResultsCount'];
//   $googleResults["links"] = $links;
//   echo "found $v links<br>";
   return array("totalLinks" => $result['estimatedTotalResultsCount'], "links" => $links);
//   return $googleResults;
   //echo "<p>Environ {$result['estimatedTotalResultsCount']}  {$result['searchTime']} secondes.</p>";
   //echo "<p>Résultats {$result['startIndex']} à {$result['endIndex']}.</p>";

   $res = $result['resultElements'];
   
   $i=$result['startIndex'];
   foreach ($res as $site) {
        echo "<p>{$i}. <a href='{$site['URL']}'>{$site['title']}</a><br />       {$site['snippet']}</p>";
        $i++;

   }
   
   $v=$result["estimatedTotalResultsCount"];
   echo "found $v links<br>";
   
   return;
   echo "<div align='center'> here";
   if ($result['startIndex'] >= $nr) {
     $t =  $result['startIndex'] - ($nr+1);
     echo "<p>
     <a href='index.php?q=".urlencode($q)."&amp;s=".$t."'>&lt;&lt;</a>";
   }
   echo " Navigation ";
   if (($result['estimatedTotalResultsCount'] > $result['endIndex'])
               or
               !$result['estimateIsExact'] )
         {
     $t =  $result['endIndex'] ;//+ 1;

     echo "<a href='index.php?q=".urlencode($q)."&amp;s=".
     $t."'>&gt;&gt;</a>";
   }
   echo "</div>";

} 
else {
  echo "<p>Probleme dans la requête.</p>";
}


}

?>