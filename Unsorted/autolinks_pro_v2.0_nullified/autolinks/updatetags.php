<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////
    
  /////////////////////////////////////////////////
  // FUNCTIONS TO SPLIT THE CODE
  /////////////////////////////////////////////////
  
  function gettagquery( $tag )
  {
    global $sitelogin;
  
    $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$sitelogin' LIMIT 1" );
    $site = mysql_fetch_array( $res_site );
	
    switch( $tag[orderby] )
    {
      case "hitsin": $hittable="al_hitin";  $orderby = "hits DESC"; break;
      case "clicks": $hittable="al_hitclk"; $orderby = "hits DESC"; break;
      case "added":  $hittable="al_hitin";  $orderby = "al_ref.added DESC"; break;
      case "name":   $hittable="al_hitin";  $orderby = "al_ref.name"; break;
      case "random": $hittable="al_hitin";  $orderby = "RAND()"; break; 
    }
  
    // add category in query if needed
    if( $tag[category]!=0 )
	{
	  $category = "al_ref.category={$tag[category]}";
	}
	else
	{
	  // select all categories accepted by this site
	  $categorya = explode( ",", $site[categories] );
	  
	  while( list($k,$v) = each($categorya) )
	  {
	    if( isset($category) ) $category .= " OR";
	    $category .= " al_ref.category=$v";
	  }
	  
	  $category = "($category )";
	}

	// do we need to fetch the hitsin information?
	if( $tag[minhits]>0 || $tag[orderby]=="hitsin" || $tag[orderby]=="clicks" )
    { 
	  if( $tag[type]=="text" )
      {
        $query = "SELECT al_ref.*, COUNT(*) AS hits 
				  FROM $hittable, al_ref
				  WHERE al_ref.login=$hittable.ref
				    AND al_ref.status=1
					AND $hittable.site='{$site[login]}'
					AND $category
				  GROUP BY $hittable.ref
				  HAVING hits>={$tag[minhits]}";
      }
      else
      {
        $query = "SELECT al_ref.*, COUNT(*) AS hits
				  FROM $hittable, al_ref, al_img
				  WHERE al_ref.login=$hittable.ref
				    AND al_ref.status=1
					AND al_img.login=al_ref.login
					AND al_img.type='referrer'
					AND al_img.format='{$tag[type]}'
					AND $hittable.site='{$site[login]}'
					AND $category
				  GROUP BY $hittable.ref
				  HAVING hits>={$tag[minhits]}";
      }
	}
	else // we don't count hitsin/clicks
	{
	  if( $tag[type]=="text" )
      {
	    // the 1 is to make the query valid with/without the category
        $query = "SELECT al_ref.*
				  FROM al_ref
				  WHERE al_ref.status=1
				    AND $category";
      }
      else
      {
        $query = "SELECT al_ref.*
				  FROM al_ref, al_img
				  WHERE al_img.login=al_ref.login
				    AND al_ref.status=1
				    AND al_img.type='referrer'
					AND al_img.format='{$tag[type]}'
					AND $category";
      }
	}
	
	$query .= " ORDER BY $orderby LIMIT {$tag[position]}, {$tag[numlinks]}";
	
	return $query;
  }

  function getfontprop( $tag )
  {
    $fontprop = "";
    if( $tag[cssclass]!="" ) $fontprop .= " class='{$tag[cssclass]}'";
    if( $tag[fontsize]!="" ) $fontprop .= " size='{$tag[fontsize]}'";
    if( $tag[fonttype]!="" ) $fontprop .= " face='{$tag[fonttype]}'";
	
	return $fontprop;
  }

  function getmouseover( $tag, $ref )
  {
    global $sitelogin;
  
    // default values
	$description = "";
  	$hitsin = 0; $hitsout = 0;
	
	// attempt to find some hits for this referrer
	$res_hitin = mysql_query( "SELECT COUNT(*) AS hits FROM al_hitin WHERE ref='{$ref[login]}' AND site='{$sitelogin}' LIMIT 1" );
    if( mysql_num_rows($res_hitin) ) { $hitin = mysql_fetch_array( $res_hitin ); $hitsin = $hitin[hits]; }
	
	$res_hitout = mysql_query( "SELECT COUNT(*) AS hits FROM al_hitout WHERE ref='{$ref[login]}' AND site='{$sitelogin}' LIMIT 1" );
    if( mysql_num_rows($res_hitout) ) { $hitout = mysql_fetch_array( $res_hitout ); $hitsout = $hitout[hits]; }
	
	if( $ref[description]!="" )
	{
	  // add slashes for the javascript
	  $description = "- " . addslashes( $ref[description] );
	}

	return  "onmouseover=\"window.status='In: $hitsin Out: $hitsout $description'; return true;\" onmouseout=\"window.status=''; return true;\"";
  }
  
  
  /////////////////////////////////////////////////
  // ACTUAL CODE STARTS HERE
  /////////////////////////////////////////////////
  
  // get the site information
  $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$sitelogin' LIMIT 1" );
  $site = mysql_fetch_array( $res_site );
  
  // create the tags dir if necessary
  $oldumask = umask(0);
  @mkdir( $alpath . "tags", 0777 );
  umask( $oldumask );
  
  // we want to update all tags in database
  $res_tag = mysql_query( "SELECT * FROM al_tag" );

  while( $tag = mysql_fetch_array($res_tag) )
  {
    // open the file for writing, erase any previous data
    $fp = fopen( $alpath . "tags/" . $tag[id] . ".php", "w" );
  
    $query = gettagquery( $tag );
	$res_ref = mysql_query( $query );
    $left = mysql_num_rows( $res_ref );

	$fontprop = getfontprop( $tag );

    fwrite( $fp, "<table width='100%' border='0' cellspacing='0' cellpadding='{$tag[padding]}'>" );

    while( $left > 0 )
    {
      // start a new table row
      fwrite( $fp, "<tr>" );

      for( $j=0; $j<$tag[numcolumns]; $j++ )
      {
	  	// start a new table cell
        fwrite( $fp, "<td align='{$tag[align]}'>" );

        // links left to display?
        if( $left > 0 )
        {
          $ref = mysql_fetch_array( $res_ref );

          $description = ""; $mouseover = "";
	      if( $tag[showdesc] ) $description = $ref[description];
	      if( $tag[mouseover] ) $mouseover = getmouseover( $tag, $ref );

          if( $tag[type]=="text" )
          {
            fwrite( $fp, "<font $fontprop><a href='{$site[alurl]}?o={$ref[login]}' target='_blank' $mouseover>{$ref['name']}</a> $description</font>" );
          }
          else
          {
            // don't take the binaries!!
            $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='referrer' AND login='{$ref[login]}' AND format='{$tag[type]}' LIMIT 1" ); 
			$img = mysql_fetch_array( $res_img );

	    	if( $tag[type]=="banner" )
            {
              fwrite( $fp, "<font {$fontprop}><a href='{$site[alurl]}?o={$ref[login]}' target='_blank' $mouseover><img src='{$site[alurl]}images/referrer/{$tag[type]}/{$ref[login]}.{$img[extension]}' width='468' height='60' alt=\"$description\" border='0'>" );
              if( $description!="" ) fwrite( $fp, "<br>$description" );
              fwrite( $fp, "</a></font>" );
            }
            elseif( $tag[type]=="button" )
            {
              fwrite( $fp, "<a href='{$site[alurl]}?o={$ref[login]}' target='_blank' $mouseover><img src='{$site[alurl]}images/referrer/{$tag[type]}/{$ref[login]}.{$img[extension]}' width='88' height='31' alt=\"$description\" border='0'></a>" );
            }
            elseif( $tag[type]=="thumb" )
            {
			  if( $ref[thumb]!="/" && $ref[thumb]!="" )
			  {
                $thumbname = explode( "/", $ref[thumb], 2 );
			    fwrite( $fp, "<font $fontprop><a href='{$site[alurl]}?o={$ref[login]}' target='_blank' $mouseover>" );
                fwrite( $fp, "{$thumbname[0]}<br>" );
                fwrite( $fp, "<img src='{$site[alurl]}images/referrer/{$tag[type]}/{$ref[login]}.{$img[extension]}' width='66' height='100' alt=\"{$thumbname[0]} {$thumbname[1]}\" border='0'>" );
                fwrite( $fp, "<br>{$thumbname[1]}" );
			    fwrite( $fp, "</a></font>" );
			  }
			  else
			  {
			    fwrite( $fp, "<font $fontprop><a href='{$site[alurl]}?o={$ref[login]}' target='_blank' $mouseover>" );
				fwrite( $fp, "<img src='{$site[alurl]}images/referrer/{$tag[type]}/{$ref[login]}.{$img[extension]}' width='66' height='100' alt=\"$description\" border='0'>" );
				fwrite( $fp, "</a></font>" );
			  }
            }
          }
		  
		  $left--;
        }
        else
        {
          // put a blank space
          fwrite( $fp, "&nbsp;" );
        }

        fwrite( $fp, "</td>" );
      }

      fwrite( $fp, "</tr>" );
    }
	
	fwrite( $fp, "</table>" );
	
	fclose( $fp );
  }

?>