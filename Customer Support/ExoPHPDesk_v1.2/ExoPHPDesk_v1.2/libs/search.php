<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> SEARCH . PHP File - For Searching Tickets
// >> Started : December 08, 2003
// >> Edited  : June 17, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

switch( $_GET['type'] )
{
	case '':

		if( SUBM == "" AND !isset ( $_GET['page'] ) )
		{
			_parse( $tpl_dir . 'search.tpl' );
			$READ = getBlock( $class->read, 'TICKET' );

			$MEMB = ( $L_TYPE != 'members' ) ? '<tr><td>Member Based:</td><td><input type="text" name="membased"></td></tr>' : '';
			
			$READ = template( $READ, $T_ST . 'search', '/#search]' );
			$READ = rpl( '^membased^', $MEMB, $READ );
				
			echo $READ;
		}
		else
		{
			$_POST = $_REQUEST;
				
			if( !isset ( $_POST['keyword'] ))
			{
				echo 'NO KEYWORD';
			}
			else
			{
				// IF DAY SEARCH ENABLED
				if ( $_POST['created'] != 0 )
				{
					$INTERVAL = intval( $_POST['created'] );
					$INTERVAL = time() - ( 86400 * $INTERVAL );
					$END = '`opened`>='. $INTERVAL .' AND ';
					$END2 = '`posted`>='. $INTERVAL .' AND ';
				}
					
				// IF EMPTY KEYWORD
				if ( empty ( $_POST['keyword'] ) )
				{
					$_POST['response'] = NULL;
				}
					
				// IF NO page QUERY_STRING IN URL
				if( !isset ( $_GET['page'] ) )
				{
					$_POST['keyword'] = '%'. rpl ( '*', '%', $_POST['keyword'] ) . '%';
				}
				else
				{
					$_POST['keyword'] = $_POST['keyword'];
				}
				
				// IF MEMBER IS SEARCHING FOR TICKETS
				if ( $L_TYPE == 'members' )
				{
					$EXT = "`admin_user` = '". USER ."' AND ";
				}
					
				// FIND OUT IF MEMBER BASED SEARCH OR NOT?
				$_POST['membased'] = ( $_POST['membased'] == "" ) ? $_GET['membased'] : trim( $_POST['membased'] );

				if( trim ( $_POST['membased'] ) != "" )
				{
					$END .= "`admin_user` = '" . $_POST['membased'] . "' AND";
					$END2 .= "`sname` = '" . $_POST['membased'] . "' AND";
				}
					
				$_POST['response'] = ( $_POST['response'] == "" ) ? $_GET['response'] : $_POST['response'];
					
				// PREPARE SQL QUERY
				$Q = $db->query ( "SELECT * FROM `phpdesk_tickets` WHERE ". $END . $EXT ."
								`title` LIKE '". $_POST['keyword'] ."' OR ". $END . $EXT ."
								`text` LIKE '". $_POST['keyword'] ."' ORDER by `opened` DESC,
								`priority` DESC" );						

				$COUNT1 = $db->num ( $Q );
					
				if ( $_POST['response'] == 'Yes' )
				{
					$Q2 = $db->query ( "SELECT * FROM `phpdesk_responses` WHERE ". $END2 ." `comment`
									  LIKE '". $_POST['keyword'] ."' " );
					
					$COUNT2 = $db->num ( $Q2 );										  
				}
					
				$ALLCOUNT = $COUNT1 + $COUNT2;
			
				if ( !$db->num ( $Q ) && !$db->num ( $Q2 ) )
				{
					echo "NO RECORDS";
				}
				else
				{
					$OLD_LT = $L_TYPE;
					$L_TYPE = ( $L_TYPE == 'members' ) ? 'index' : $L_TYPE;

					// Parse ticket tpl file
					_parse( $tpl_dir.'search.tpl' );
					$READ = getBlock( $class->read, 'TICKET' );
					$READ = template( $READ, $T_ST . 'results', '/#results]' );
				
					$L_TYPE = $OLD_LT;
						
					// TEMPLATE VARIABLES
					$LIST = template ( $READ, $T_ST, $T_ED );
					$END  = template ( $READ, $T_ED, NULL );
					$READ = template ( $READ, NULL, $T_ST );
						
					// USER's TICKETs/PAGE
					$TPPAGE = $_F['tppage'];
						
					if( !isset( $_GET['page'] ) )
					{
						$START = 0;
						$FINISH = $TPPAGE;
					}
					else
					{
						$START = $_GET['last'];
						$FINISH = $TPPAGE * $_GET['page'];
					}
						
					echo $READ;
					
					$CT = $X = $T_COUNT = 0;

					// UNSET VARIABLES
					$TMP_IN = $OVERALL_OUT = NULL;

					while ( $CT <= $ALLCOUNT )
					{
						$CT++;
							
						if ( $CT >= $COUNT1 && $COUNT2 > 0 )
						{
							$Q = $Q2;
						}
							
						while( $F = $db->fetch ( $Q ) )
						{
							// CHECK IF ITS A RESPONSE
							if ( !empty ( $F['tid']) )
							{
								$__Q = $db->query ( $sel_ticket. " WHERE `id` = '$F[tid]'" );
								$__F = $db->fetch ( $__Q );
									
								// IF TICKET DOESNT BELONGS TO USER
								if ( $L_TYPE == 'members' && $__F['admin_user'] != USER )
								{
									CONTINUE;
								}
									
								// IF MEMBERBASED SEARCH
								if ( $L_TYPE == 'members' && $__F['admin_user'] != $_POST['membased'] )
								{
									CONTINUE;
								}									

								// IF ALREADY IN LIST
								if ( preg_match ( '/(id=\b'. $__F['id'] .'\b)/i', $TMP_IN ) )
								{
									CONTINUE;
								}
								$F = $__F;
									
							}
								
							// TEMP LIST OF IDS
							$TMP_IN .= 'id='.$F['id'].'|||';

							$T_COUNT++;	
							$X++;
							
							if ( $X >= $START && $X <= $FINISH )
							{
								// FIND OUT WHICH BACKGROUND CLASS TO USE
								$BG = ( is_float( $X / 2 ) ) ? 'tdbg1' : 'tdbg2';
			
								// SET COLOR ACCORDING TO PRIORITY
								$COLOR = ( $F['priority'] == '1' ) ? $tpl['font_rd'] : ( ( $F['priority'] == '2' ) ? $tpl['font_gr'] : $tpl['font_bk'] );
								$color = $COLOR . $F['title'] . $tpl['font_en'];
								
								// OPEN OR CLOSE?				
								$LIST = ( $F['status'] == 'Closed' ) ? rpl ( '^closeoropen^', 'Open', $LIST ) : rpl ( '^closeoropen^', 'Close', $LIST );
											
								// Find How much time has passed since the ticket was created.
								$opened = opened( $F['opened'] );
								
								// NUMBER OF REPLIES MADE TO THE TICKET				
								$Q1 = $db->query( $sel_response." WHERE tid = '".$F['id']."'" );
								$REPLIES = $db->num( $Q1 );
			
								// WAITING CONTENT COLORS
								if( $F['waiting'] == 'Staff' )
								{
									$F['waiting'] = '<font color="red">'.$F['waiting'].'</font>';
								}
									
								// PREPARE PAGE TO BE SENT OUT USING TEMPLATE VARS
								$OUT = rpl( '^tdbg^', $BG, rpl( '^id^', $F['id'], rpl( '^title^', $color, $LIST ) ) );
								$OUT = rpl( '^replies^', $REPLIES, rpl( '^department^', $F['group'], $OUT ) );

								$OUT = rpl( '^status^', $F['waiting'], rpl( '^opened^', $opened, $OUT ) );
								$OUT = rpl( '^del^', '', $OUT );
									
								// SET VARS TO NULL
								$NO_TEMP = $opened = NULL;
								$OVERALL_OUT .= $OUT;
				
							}
						}
					} // END LOOP
						
					// PREPARE PAGE NUMBERS
					if($T_COUNT > $TPPAGE)
					{	
						$PAGES = NULL;
							
						$DO_IT = ceil( ( $T_COUNT / $TPPAGE ) );
						
						$X = 1;
								
						while($X <= $DO_IT)
						{
							$X++;
							$_GET['last'] = ( $TPPAGE * ( $X - 2 ) ) + 1;
							$PAGES .= ' [ <a href="'.$_SERVER['PHP_SELF'].'&l_type='.$L_TYPE.'&keyword='.$_POST['keyword'].'&created='
									  .$_POST['created'].'&page='.( $X - 1 ).'&view='.$_GET['view'].'&last='.$_GET['last']
									  .'&response='.$_POST['response'].'&membased='.$_POST['membased'].'">'.( $X - 1 ).'</a> ]';
						}
					}

					echo $OVERALL_OUT;
					$END = rpl('^pages^', $PAGES, $END);
					echo $END;
				}
			}
						
		}
		break;

	case 'faq':

		//
		//  IF FORM NOT SUBMITTED
		//		
		if( SUBM == NULL )
		{
			_parse( $tpl_dir . 'search.tpl' );
			$READ = getBlock( $class->read, 'FAQ' );
			$READ = template( $READ, 'search', '/#search]' );
			
			echo $READ;
			
		}
		else
		{
			//
			// DO VALIDATION OF KEYWORD
			//
			if( empty( $_POST['keyword'] ))
			{
				echo $error['keyword'];
			}
			else
			{
				// 
				// PREPARE THE SQL QUERY
				//
				$keyword = '%'. rpl ( '*', '%', $_POST['keyword'] ) . '%';
				$query = $db->query( "SELECT * FROM phpdesk_kb WHERE `title` LIKE '$keyword' OR 
										`message` LIKE '$keyword' " );
										
				//
				// PREPARE THE TEMPLATE
				//
				_parse( $tpl_dir . 'search.tpl' );
				$READ = getBlock( $class->read, 'FAQ' );
				$READ = template( $READ, 'results', '/#results]' );
				$LIST = template( $READ, $T_ST, $T_ED );
				$TOP  = template( $READ, NULl, $T_ST );
				$DOWN = template( $READ, $T_ED, NULL );
				
				// PRINT TOP!
				echo $TOP;
				
				// CONFIRM QUERY RESULTS					
				if( !$db->num( $query ))
				{
					echo $error['no_such_faq'];
				}
				else
				{
					$X = 0;
					
					// Get The User Type
					$tType = @where( $_COOKIE['help__user'] );
					$uType = str_replace( 'members', 'member', @preg_replace( '/phpdesk_(.*)/i', "\\1", $tType ));
					
					//
					// LIST ALL THE RECORDS
					//
					while( $f = $db->fetch( $query ))
					{	
						$X++;
						
						// GET BACKGROUND!!!
						$bg = ( is_float( $X / 2 )) ? 'ticketbg' : 'ticketbg2';
						
						$title = "<a href='$uType.php?action=kb&type=view&id=$f[id]&s=$SID&highlight=$keyword'>$f[title]</a>";
						$OUT = rpl( '^title^', $title, rpl( '^group^', $f['group'], $LIST ));
						$OUT = rpl( '^tdbg^', $bg, $OUT );
						echo $OUT;
					}
				}
				
				// PRINT Down!
				echo $DOWN;
			}
		}
		break;

	// 
	// SEARCH A MEMBER!
	// A nifty feature that can help a lot, it helps in searching
	// a member and thus easy editing can be possible..
	//
	case 'member':
	
		// if form not submitted..
		if( SUBM == "" AND !isset ( $_GET['page'] ) )
		{
			_parse( $tpl_dir . 'search.tpl' );
			$READ = getBlock( $class->read, 'MEMBER' );
			$READ = template( $READ, $T_ST . 'search', '/#search]' );
				
			echo $READ;
		}
		else
		{
		
			$IN = array_merge( $_GET, $_POST );
			
			// Parse ticket tpl file
			_parse( $tpl_dir.'search.tpl' );
			$READ = getBlock( $class->read, 'MEMBER' );
			$READ = template( $READ, $T_ST . 'results', '/#results]' );
						
			// TEMPLATE VARIABLES
			$LIST = template ( $READ, $T_ST, $T_ED );
			$END  = template ( $READ, $T_ED, NULL );
			$READ = template ( $READ, NULL, $T_ST );
			
			// print out the top part...
			echo $READ;
			
			$username = rpl ( '*', '%', $IN['username'] );
			$realname = rpl ( '*', '%', $IN['username'] );
			
			if( $username )
			{
				$EXT = "AND username LIKE '$username%' ";
			}
			
			if( $realname )
			{
				$EXT .= "AND name LIKE '%$realname%' ";
			}
			
			if( $IN['created'] )
			{
				$INTERVAL = intval( $IN['created'] );
				$INTERVAL = time() - ( 86400 * $INTERVAL );
				$EXT .= "AND registered >= '$INTERVAL' ";
			}

			// prepare the long query to search the members
			$sql = "SELECT m.username, m.name, m.id, m.email, m.disabled, COUNT(t.admin_user) as tickets FROM phpdesk_members m
						LEFT JOIN phpdesk_tickets t ON( m.username = t.admin_user ) 
						WHERE m.id <> '' $EXT
					GROUP by m.username";
			$db->query( $sql );
			
			// count no of records
			$T_COUNT = $db->num();
			
			if( !isset( $IN['page'] ) )
			{
				$START = 0;
				$FINISH = $a_tppage;
			}
			else
			{
				$START = $IN['last'];
				$FINISH = $a_tppage * $IN['page'];
			}
			
			$X = 0;
			
			// get the results.. :)
			
			while( $f = $db->fetch() )
			{
				//
				// I tried some ways to include it in the main query but there is no way because selected it
				// using count() ..
				//
				if( $f['tickets'] < intval($IN['tickets']) )
				{
					continue;
				}
				
				$X++;
				
				$bg = is_float( $x / 2 ) ? 'tdbg1' : 'tdbg2';
				
				if ( $X < $START OR $X > $FINISH )
				{
					continue;
				}
				
				$sub =  $f['disabled'] == '1' ? 'un' : null;
				
				$OUT = rpl( array( 'ID'  => $f['id'], 
								   'sub' => $sub,
								   'USERNAME'  =>  $f['username'],
								   'REAL_NAME' =>  $f['name'],
								   'tdbg'	 =>  $bg,
								   'EMAIL'   =>  $f['email'],
								   'TICKETS' =>  $f['tickets']
								  ), '^', $LIST
						  );
				echo $OUT;
			}
			
			// PREPARE PAGE NUMBERS
			if($T_COUNT > $a_tppage)
			{	
				$PAGES = NULL;
				$DO_IT = ceil( ( $T_COUNT / $a_tppage ) );
						
				$X = 1;
								
				while($X <= $DO_IT)
				{
					$X++;
					$IN['last'] = ( $a_tppage * ( $X - 2 ) ) + 1;
					$page   =  $X - 1;
					$PAGES .= " [ <a href='{$_SERVER[PHP_SELF]}type=member&username=$IN[username]&realname=$IN[realname]&created=$IN[created]&tickets=$IN[tickets]
								&last=$IN[last]&page=$page'>$page</a> ]";
				}
			}
		
			echo rpl( '^pages^', $PAGES, $END );
						
		}
		break;
	
} // END SWITCH

?>