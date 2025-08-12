<?php 
/* 
 *  Blogger and metaWeblog implementation for Pivot
 *
 *  Based on phpMyWeblog's api.php
 *
 *  Modified by Connor Carney to work with Pivot
 *  From the phpMyWeblog project's api.php
 *  Originally Created by Dougal Campbell <dougal@gunters.org>, <emc3@users.sf.net>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */




include_once('pv_core.php');
if(file_exists('xmlrpc/xmlrpc.inc')) {
	include_once('includes/xmlrpc/xmlrpc.inc');
	include_once('includes/xmlrpc/xmlrpcs.inc');;
} else {
	// fallback for old dirname.
	include_once('includes/xmlrpc-1.0.99.2/xmlrpc.inc');
	include_once('includes/xmlrpc-1.0.99.2/xmlrpcs.inc');
}

$db=new db();
$conversion_method=0;


// PIVOT FUNCTIONS
//These functions update the pivot database using the data
//from XMLRPC:

// Check username and password.
function pivot_get_userid($user,$pass) {
	global $Users;
	
	if ($Users[$user]['pass'] == md5($pass)) {
		return array('uid'=>$user);
	}else{
		return array(
					 'uid'=>-1,
					 'err'=>"your password is incorrect."
					 );
	}
	return array('uid'=>$user);
}

//Return the list of categories
function pivot_get_user_blogs($uid) {
	global $Users, $Cfg;
	$ThisUser = $Users[$uid];
	
	$testcats = explode("|", $Cfg['cats']);
	$cats = array();
	foreach ($testcats as $cat) {
		if (!($cat=="")){
			$cats[] = array(
							"blogid"=>$cat,
							"blogName"=>$cat,
							"url"=>''   );
		}
	}
	
	return $cats;
}


// Determine if a user can post to a given category.
function pivot_user_blog_check($uid,$blogid) {
	global $Users, $Cfg;
	$ThisUser = $Users[$uid];
	$allowed = explode("|", $Cfg['cat-'. $blogid]);
	if (in_array($uid, $allowed)){
		return 1;
	} else {
		return 0;
	}
}


// Determine if a user has access to a given post.
function pivot_user_post_check($uid,$blogid,$postid) {
	global $db;
	$entry=$db->read_entry($postid);
	if ($entry['user']==$uid){
		return 1;
	}
	return 0;
}

//Return recent posts in the given cat.
function pivot_recent($blogid,$num) {
	global $Paths, $Cfg, $db, $pivot_url;
	$postlist=$db->getlist(-$num,0,"", array($blogid), FALSE, "");
	
	$retposts=array();
	foreach($postlist as $post){
		$thispost= $db->read_entry($post['id']);
		$retposts[]=array(
						  'datetime'=>utf8_encode($thispost['date']),
						  'userid'=>$thispost['user'],
						  'postid'=>$thispost['code'],
						  'content'=>utf8_encode($thispost['introduction']),
						  'title'=>utf8_encode($thispost['title']),
						  'description'=>utf8_encode($thispost['introduction']),
						  'link'=>utf8_encode($Paths['pivot_url'].'entry.php?id='.$thispost['code']),
						  'permalink'=>utf8_encode($Paths['pivot_url'].'entry.php?id='.$thispost['code']),
						  'catagories'=>$thispost['category']
						  );
	}
	return $retposts;
}


//Get a specific post.
function pivot_get_post($blogid,$postid) {
	global $db, $Paths, $Cfg;
	
	$post=$db->read_entry($postid);
	$result = array(
					'datetime'=>$post['date'],
					'userid'=>$post['user'],
					'postid'=>$post['code'],
					'content'=>$post['introduction'],
					'title'=>$post['title'],
					'description'=>$post['introduction'],
					'link'=>$Paths['pivot_url'].'entry.php?id='.$post['code'],
					'permalink'=>$Paths['pivot_url'].'entry.php?id='.$post['code'],
					'catagories'=>$post['category']
					);
		
	return $result;
}

// Get blog name from blogid (should be same, since we recognize by name)
function pivot_blog_name($blogid) {
	return $blogid;
}

//Create a new post
function pivot_new_post($uid,$blogid,$title,$body,$cat_id) {
	global $Cfg, $db, $VerboseGenerate;
	
	$entry['code'] = ">";
		
	$entry['date'] = date("Y-m-d-H-i", get_current_date());
	
	$entry['introduction'] = strip_trailing_space(stripslashes($body));
	$entry['body'] = "";
	
	$entry['introduction'] = tidy_html($entry['introduction'], TRUE);
	$entry['body'] = tidy_html($entry['body'], TRUE);
	
	$entry['category'] = array($blogid);
	$entry['publish_date'] = fix_date("Y-m-d-H-i", get_current_date());
	$entry['edit_date'] = date("Y-m-d-H-i");
	$entry['title'] = strip_trailing_space(stripslashes($title));
	$entry['subtitle'] = "";
	$entry['user'] = $uid;
	$entry['convert_lb'] = $conversion_method;
	$entry['status'] =  "publish";
	$entry['allow_comments'] = 1;
	$entry['keywords'] = "";
	$entry['vialink'] =  "";
	$entry['viatitle'] = "";
	
	$db->set_entry($entry);
	$db->save_entry(TRUE);
	
	generate_pages( $db->entry['code'],TRUE,TRUE,TRUE,FALSE);
	
	//Return code
	return $db->entry['code'];
}


//Updates post, returns nothing
function pivot_update_post($uid,$blogid,$postid,$title,$content,$cat_id=1) {
	global $Cfg, $db;
	
	$oldentry = $db->read_entry($postid);
	
	$entry['code'] = $postid;
	
	$entry['date'] = date("Y-m-d-H-i", get_current_date());
	$entry['introduction'] = strip_trailing_space(stripslashes($content));
	$entry['body'] = $oldentry['body'];
	$entry['introduction'] = tidy_html($entry['introduction'], TRUE);
	$entry['body'] = tidy_html($entry['body'], TRUE);
	$entry['category'] = $oldentry['category'];
	$entry['publish_date'] = fix_date("Y-m-d-H-i", get_current_date());
	$entry['edit_date'] = fix_date("Y-m-d-H-i", get_current_date());
	$entry['title'] = strip_trailing_space(stripslashes($title));
	$entry['subtitle'] = $oldentry['subtitle'];
	$entry['user'] = $uid;
	$entry['convert_lb'] = $conversion_method;
	$entry['status'] =  "publish";
	$entry['allow_comments'] = 1;
	$entry['keywords'] = $oldentry['keywords'];
	$entry['vialink'] =  $oldentry['vialink'];
	$entry['viatitle'] = $oldentry['viatitle'];
	
	$db->set_entry($entry);
	$db->save_entry(TRUE);
	
	generate_pages( $db->entry['code'],TRUE,TRUE,TRUE,FALSE);
}

//Deletes post, returns nothing
function pivot_delete_post($blogid,$postid) {
	global $db;
	if ($postid=="")
		$postid=$blogid;
	$entry = $db->read_entry($postid);
	$db->delete_entry();
	
	$overview_arr = $db->getlist(-4,0,"","", FALSE);
	
	$VerboseGenerate = FALSE;
	
	foreach ($overview_arr as $entry) {
		generate_pages($entry['code'], TRUE, TRUE, TRUE, FALSE);
	}
}


//Returns info about user
function pivot_user_info($uid) {

	$info = array(	
	'userid' => $uid,
	'firstname' => '',
	'lastname' => '',
	'nickname' => $uid,
	'email' => '',
	'url' => ''
	);

	return $info;
}

//Returns name of category
function pivot_category_name($blogid, $cat_id=1) {
	return $blogid;
}

//Returns ID of a category
function pivot_category_id($blogid, $cat_name='General') {
	return $blogid;
}

//***********************************************************************************************

// Blogger API
// MetaWeblog is based on Blogger, so we have to include these.
// Posting via Blogger clients might work, but I don't recommend it.

//The parameters are application key (ignored), blog id, username, password, content, and publish status (ignored).

function newPost ($params) {
	global $xmlrpcerruser;

	$conv = $params->getParam(0); $appkey = $conv->scalarval();
	$conv = $params->getParam(1); $blogid = $conv->scalarval();
	$conv = $params->getParam(2); $user = $conv->scalarval();
	$conv = $params->getParam(3); $pass = $conv->scalarval();
	$conv = $params->getParam(4); $content = $conv->scalarval();
	$conv = $params->getParam(5); $publish = $conv->scalarval();

	// Make Sure User Name and Password Match
	$login = pivot_get_userid($user,$pass);

	//Get the User ID
	$uid = $login['uid'];
	
	if ($uid != -1) {
		//Check access rights
		if (!pivot_user_blog_check($uid,$blogid)) {
			$err = "User " . $user . " does not have access to blogid " . $blogid;
		}

	} else {
		//Throw an error if the password was wrong.
		$err = $login['err'];
	}

	if ($err) {
		// Return an Error
		return new xmlrpcresp(0, $xmlrpcerruser+1,$err);
	} else {

		// Let's try using <title> tags....
		// Blogger has no built-in title feature.  You should use MetaWeblog instead.
		$result = preg_match('/<title>(.+)<\/title>(.*)/is',$content,$arr);
		if ($result) {
			$title = $arr[1];
			$body = $arr[2];
		} else {
			$title = '';
			$body = $content;
		}

		$postid = pivot_new_post($uid,$blogid,$title,$body);
		
		$myResp = new xmlrpcval($postid,"string");

		//Return
		return new xmlrpcresp($myResp);
	}
}

// Parameters are application key (ignored), post id, username, password, content, and publish status (ignored)
function editPost ($params) {
	global $xmlrpcerruser;


	$conv = $params->getParam(0); $appkey = $conv->scalarval();
	$conv = $params->getParam(1); $postid = $conv->scalarval();
	$conv = $params->getParam(2); $user = $conv->scalarval();
	$conv = $params->getParam(3); $pass = $conv->scalarval();
	$conv = $params->getParam(4); $content = $conv->scalarval();
	$conv = $params->getParam(5); $publish = $conv->scalarval();

	$blogid=$postid;

	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid != -1) {
		if (!pivot_user_post_check($uid,$blogid,$postid)) {
			$err = "$user didn't write post $postid";
		}
	} else {
		$err = $login['err'];
	}
	
	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1,$err);
	} else {
		// Let's try using <title> tags....
		$result = preg_match('/^<title>(.+)<\/title>(.*)/is',$content,$arr);
		if ($result) {
			$title = $arr[1];
			$body = $arr[2];
		} else {
			$title = '';
			$body = $content;
		}

		pivot_update_post($uid,$blogid,$postid,$title,$body);
		
		$myResp = new xmlrpcval(1,"boolean");

		// return
		return new xmlrpcresp($myResp);
	}
}

//Parameters are application key (ignored), post id, username, and password
function getPost ($params) {

	$conv = $params->getParam(0); $appkey = $conv->scalarval();
	$conv = $params->getParam(1); $bpostid = $conv->scalarval();
	$conv = $params->getParam(2); $user = $conv->scalarval();
	$conv = $params->getParam(3); $pass = $conv->scalarval();

	$blogid=$postid;

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid != -1) {
		$xmlrpcpost = pivot_get_post($blogid,$postid);
	} else {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1,$err);
	} else {
		//Return a response
		$myResp = new xmlrpcval($xmlrpcpost,"struct");
		return new xmlrpcresp($myResp);
	}

}


//Parameters are application key (ignored), post id, username, password, and publish status (ignored)
function deletePost ($params) {
	global $xmlrpcerruser;

	$conv = $params->getParam(0); $appkey = $conv->scalarval();
	$conv = $params->getParam(1); $postid = $conv->scalarval();
	$conv = $params->getParam(2); $user = $conv->scalarval();
	$conv = $params->getParam(3); $pass = $conv->scalarval();
	$conv = $params->getParam(4); $publish = $conv->scalarval();
	
	$blogid=$postid;
	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid != -1) {
		if (!pivot_user_post_check($uid,$blogid,$postid)) {
			$err = "$user didn't write $postid";
		}
	} else {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1,$err);
	} else {
		pivot_delete_post($blogid,$postid);		
		$myResp = new xmlrpcval(1,"boolean");
		return new xmlrpcresp($myResp);
	}

}


//Parameters are application key (ignored), blog ID, username, password, and number of posts
function getRecentPosts ($params) {	// ($appkey, $blogid, $user, $pass, $num) 
	global $xmlrpcerruser;

	$conv = $params->getParam(0); $appkey = $conv->scalarval();
	$conv = $params->getParam(1); $blogid = $conv->scalarval();
	$conv = $params->getParam(2); $user = $conv->scalarval();
	$conv = $params->getParam(3); $pass = $conv->scalarval();
	$conv = $params->getParam(4); $num = $conv->scalarval();

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid != -1) {
		//Check Permissions
		if (pivot_user_blog_check($uid,$blogid)) {
			$postlist = pivot_recent($blogid,$num);
		} else {
			$err = "$user does not have access to $blogid";
		}

	} else {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1,$err);
	} else {
		// Encode each entry of the array.
		foreach($postlist as $entry) {
			// convert the date
			$unixtime = strtotime($entry['datetime']);
			$isoString=iso8601_encode($unixtime);
			$date = new xmlrpcval($isoString,"dateTime.iso8601");
			
			$userid = new xmlrpcval($entry['userid']);
			$postid = new xmlrpcval($entry['postid']);
			$content = new xmlrpcval($entry['content']);

			$encode_arr = array(
				'datecreated' => $date,
				'userid' => $userid,
				'postid' => $postid,
				'content' => $content
			);
			
			$xmlrpcpostarr[] = new xmlrpcval($encode_arr,"struct");
		}	

		$myResp = new xmlrpcval($xmlrpcpostarr,"array");

		return new xmlrpcresp($myResp);
	}

}


//Parameters are application key (ignored), username and password.
function getUserInf ($params) {

	$conv = $params->getParam(0); $appkey = $conv->scalarval();
	$conv = $params->getParam(1); $user = $conv->scalarval();
	$conv = $params->getParam(2); $pass = $conv->scalarval();

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid == -1) {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1,$err);
	} else {
		$xmlrpcuser = pivot_user_info($uid);
		$myResp = piv_xmlrpc_encode($xmlrpcuser);
		return new xmlrpcresp($myResp);
	}

}


//Parameters are application key, username, and password
function getUsersBlogs ($params) {
	global $xmlrpcerruser;

	$conv = $params->getParam(0);
	$conv = $params->getParam(1);
	$conv = $params->getParam(2);
	
	$appkey = $conv->scalarval();
	$user = $conv->scalarval();
	$pass = $conv->scalarval();

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $g['uid'];
	
	if ($uid != -1) {
		$bloglist = pivot_get_user_blogs($uid);
				
		if (!is_array($bloglist)) {
			$err = "$user isn't allowed to post here.";
		}

	} else {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1, $err);
	} else {
		//Make an array of blogs
		foreach($bloglist as $entry) {
			$xmlrpcblogarr[] = piv_xmlrpc_encode($entry);
		}	

		// Convert the array to XMLRPC
		$myResp = new xmlrpcval($xmlrpcblogarr,"array");

		// return
		return new xmlrpcresp($myResp);
	}
}


//Parameters are application key, blog id, username, password
//We don't support template editing, so we just return an error.
function getTemplate ($params) {	 
	global $xmlrpcerruser;

	$err = "this endpoint doesn't support template editing.";

	return new xmlrpcresp(0, $xmlrpcerruser+1, $err);
}

//Parameters are application key, blog id, username, password, template, and type
//We don't support template editing, so we just return an error.
function setTemplate ($params) {	// ($appkey, $blogid, $user, $pass, $template, $type) 
	global $xmlrpcerruser;

	$err = "this endpoing doesn't support template editing.";
	

	return new xmlrpcresp(0, $xmlrpcerruser+1, $err);
}


//**********************************************************************************************

//MetaWeblog Functions



//Parameters are blog id, username, password, content, and publish status.
function metaweblog_newPost($params) {
	global $xmlrpcerruser;

	$conv = $params->getParam(0); $blogid = $conv->scalarval();
	$conv = $params->getParam(1); $user = $conv->scalarval();
	$conv = $params->getParam(2); $pass = $conv->scalarval();
	$conv = $params->getParam(3); $contentstruct = piv_xmlrpc_decode($conv);
	$conv = $params->getParam(4); $conv = $conv->scalarval();

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid != -1) {
		if (!pivot_user_blog_check($uid,$blogid)) {
			$err = "User " . $user . " does not have access to blogid " . $blogid;
		}
	} else {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1, $err);
	} else {
		//Create post and respond

		$title = $contentstruct['title'];
		$content = $contentstruct['description'];
		$categories = $contentstruct['categories'];
		$cat_name = $categories[0];
		$cat_id = pivot_category_id($blogid,$cat_name);

		$postid = pivot_new_post($uid,$blogid,$title,$content,$cat_id);
		
		$myResp = new xmlrpcval($postid,"string");
		return new xmlrpcresp($myResp);
	}

}


//Parameters are post id, user, password, content and publish
function metaweblog_editPost ($params) {
	global $xmlrpcerruser;

	$conv = $params->getParam(0); $postid = $conv->scalarval();
	$conv = $params->getParam(1); $user = $conv->scalarval();
	$conv = $params->getParam(2); $pass = $conv->scalarval();
	$conv = $params->getParam(3); $contentstruct = piv_xmlrpc_decode($conv);
	$conv = $params->getParam(4); $publish = $conv->scalarval();
	
	$blogid=$postid;

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid != -1) {
		if (!pivot_user_post_check($uid,$blogid,$postid)) {
			$err = "$user didn't write post $postid";
		}
	} else {
		$err = $login['err'];
	}
	
	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1, $err);
	} else {
		//Update the post and return
		
		$title = $contentstruct['title'];
		$content = $contentstruct['description'];
		$categories = $contentstruct['categories'];
		$cat_name = $categories[0];
		$cat_id = pivot_category_id($blogid,$cat_name);

		pivot_update_post($uid,$blogid,$postid,$title,$content,$cat_id);
		
		$myResp = new xmlrpcval(1,"boolean");

		return new xmlrpcresp($myResp);
	}
}


//Parameters are post id, username, password
function metaweblog_getPost ($params) {
	$conv = $params->getParam(0); $bpostid = $conv->scalarval();
	$conv = $params->getParam(1); $user = $conv->scalarval();
	$conv = $params->getParam(2); $pass = $conv->scalarval();

	$blogid=$postid;

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];

	if ($uid != -1) {
		$xmlrpcpost = pivot_get_post($blogid,$postid);
		if ($xmlrpcpost['err']) {
			$err = $xmlrpcpost['err'];
		}

	} else {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1, $err);
	} else {
		//Encode as an XMLRPC array
		$myResp = piv_xmlrpc_encode($xmlrpcpost);
		
		return new xmlrpcresp($myResp);
	}

}


//Parameters are blog id, username, password, and number of posts
function metaweblog_getRecentPosts ($params) {
	global $xmlrpcerruser;

	$conv = $params->getParam(0); $blogid = $conv->scalarval();
	$conv = $params->getParam(1); $user = $conv->scalarval();
	$conv = $params->getParam(2); $pass = $conv->scalarval();
	$conv = $params->getParam(3); $num = $conv->scalarval();

	// Check password
	$login = pivot_get_userid($user,$pass);

	$uid = $login['uid'];
	
	if ($uid != -1) {
		// Check blog permissions.
		if (pivot_user_blog_check($uid,$blogid)) {
			$postlist = pivot_recent($blogid,$num);
		} else {
			$err = "$user can't access $blogid";
		}

	} else {
		$err = $login['err'];
	}

	if ($err) {
		return new xmlrpcresp(0, $xmlrpcerruser+1, $err);
	} else {
		// Encode each entry of the array.
		foreach($postlist as $entry) {
			// convert the date
			$unixtime = strtotime($entry['datetime']);
			$isoString=iso8601_encode($unixtime);
			$date = new xmlrpcval($isoString,"dateTime.iso8601");
			$userid = new xmlrpcval($entry['userid']);
			$content = new xmlrpcval($entry['content']);

			$postid = new xmlrpcval($entry['postid']);
			$title = new xmlrpcval($entry['title']);
			$description = new xmlrpcval($entry['description']);
			$link = new xmlrpcval($entry['link']);
			$permalink = new xmlrpcval($entry['permalink']);
			$category = new xmlrpcval($entry['category']);
			$cat_arr = new xmlrpcval($category,'array');

			$encode_arr = array(
				'datecreated' => $date,
				'userid' => $userid,
				'postid' => $postid,
				'title' => $title,
				'description' => $description,
				'link' => $link,
				'permalink' => $permalink,
				'categories' => $cat_arr,
			);
			
			$xmlrpcpostarr[] = new xmlrpcval($encode_arr,"struct");
		}	

		$myResp = new xmlrpcval($xmlrpcpostarr,"array");
		return new xmlrpcresp($myResp);
	}

}


//Parameters are $blog id, username, password
function metaweblog_getCategories ($params) {	
	global $xmlrpcerruser;
	
	$conv = $params->getParam(0); $blogid = $conv->scalarval();
	$conv = $params->getParam(1); $user = $conv->scalarval();
	$conv = $params->getParam(2); $pass = $conv->scalarval();
	
	$desc=new xmlrpcval($blogid);
	$html=new xmlrpcval("http://");
	$rss=new xmlrpcval("http://");

	$encode_array=array('description'=>$desc,
						'htmlUrl'=>$html,
						'rssURL'=>$rss);
	$xmlcatarray[]=new xmlrpcval($encode_array,"struct");
	
	
	//$myResp = new xmlrpcval($encode_array,"array");
	$myResp=new xmlrpcval($xmlcatarray,"array");
	return new xmlrpcresp($myResp);
}


//***********************************************************************************************

//XMLRPC Code


// Set up the server
$s=new xmlrpc_server( 
	array(	"blogger.newPost" =>
			array("function" => "newPost",
				"signature" => array(array($xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcBoolean)),
				"doc" => "Create a new post using the Blogger API"
			),
			"blogger.editPost" =>
			array("function" => "editPost",
				"signature" => array(array($xmlrpcBoolean,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcBoolean)),
				"doc" => "Edit an existing post using the Blogger API"
			),
			"blogger.deletePost" =>
			array("function" => "deletePost",
				"signature" => array(array($xmlrpcBoolean,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcBoolean)),
				"doc" => "Delete an existing post"
			),
			"blogger.getUsersBlogs" =>
			array("function" => "getUsersBlogs",
				"signature" => array(array($xmlrpcArray,$xmlrpcString,$xmlrpcString,$xmlrpcString)),
				"doc" => "Get a list of Pivot categories the user is authorized to access."
			),
			"blogger.getUserInfo" =>
			array("function" => "getUserInf",
				"signature" => array(array($xmlrpcStruct,$xmlrpcString,$xmlrpcString,$xmlrpcString)),
				"doc" => "Get information for a given username"
			),
			"blogger.getTemplate" =>
			array("function" => "getTemplate",
				"signature" => array(array($xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString)),
				"doc" => "We don't support template editing."
			),
			"blogger.setTemplate" =>
			array("function" => "setTemplate",
				"signature" => array(array($xmlrpcBoolean,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString)),
				"doc" => "We don't support template editing."
			),
			"blogger.getPost" =>
			array("function" => "getPost",
				"signature" => array(array($xmlrpcStruct,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString)),
				"doc" => "Get an existing post using the Blogger API"
			),
			"blogger.getRecentPosts" =>
			array("function" => "getRecentPosts",
				"signature" => array(array($xmlrpcArray,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcInt)),
				"doc" => "Get recent posts using the Blogger API"
			),
			"metaWeblog.newPost" =>
			array("function" => "metaweblog_newPost",
				"signature" => array(array($xmlrpcBoolean,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcStruct,$xmlrpcBoolean)),
				"doc" => "Create a new post using metaWeblog"
			),
			"metaWeblog.editPost" =>
			array("function" => "metaweblog_editPost",
				"signature" => array(array($xmlrpcBoolean,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcStruct,$xmlrpcBoolean)),
				"doc" => "Edit an existing post using metaWeblog"
			),
			"metaWeblog.getPost" =>
			array("function" => "metaweblog_getPost",
				"signature" => array(array($xmlrpcStruct,$xmlrpcString,$xmlrpcString,$xmlrpcString)),
				"doc" => "Get an existing post using metaWeblog"
			),
			"metaWeblog.getRecentPosts" =>
			array("function" => "metaweblog_getRecentPosts",
				"signature" => array(array($xmlrpcArray,$xmlrpcString,$xmlrpcString,$xmlrpcString,$xmlrpcInt)),
				"doc" => "Get recent posts usin metaWeblog"
			),
			"metaWeblog.getCategories" =>
			array("function" => "metaweblog_getCategories",
				"signature" => array(array($xmlrpcArray,$xmlrpcString,$xmlrpcString,$xmlrpcString)),
				"doc" => "Gets categories"
			),
	)
);

?>