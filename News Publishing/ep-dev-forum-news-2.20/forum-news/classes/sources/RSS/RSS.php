<?php

/* ------------------------------------------------------------------ */
//	Source Module: Really Simple Syndication (RSS)
//		module version: 1.0
//		RSS versions: 0.91, 0.92, 2.0
//		6/28/2005
/* ------------------------------------------------------------------ */


class EP_Dev_Forum_News_RSS_Access
{
	var $SMILIES;
	var $POSTS;
	
	// array to store cached author urls
	var $AUTHOR_IMAGES;
	
	var $CONF;

	var $ERROR;
	var $LINKS;
	var $PARSER;


	function EP_Dev_Forum_News_RSS_Access(&$PARSER, &$forum_conf, &$error_handle)
	{
		/* 
			initialize forum configuration
			WARNING: this should be used only to pull url data,
			where only ->url is valid. Any other config data may not
			exist in future versions. Ideally we would make most of
			the data private, but PHP5 isn't widely supported yet.
		*/
		$this->CONF = $forum_conf;

		// initialize error handle
		$this->ERROR = $error_handle;

		// initialize RSS parser
		$this->PARSER = $PARSER;

		// initialize forum-specific links
		$this->LINKS['author'] = "";
		$this->LINKS['thread'] = "";
	}


	/* ------------------------------------------------------------------ */
	//	Get Author Link
	//  Returns full url to author profile 
	/* ------------------------------------------------------------------ */
	
	function get_Author_Link($author)
	{
		// return Author Link
		return $this->LINKS['author'] . $author;
	}


	/* ------------------------------------------------------------------ */
	//	Get Thread Link
	//  Returns full url to thread 
	/* ------------------------------------------------------------------ */
	
	function get_Thread_Link($thread)
	{
		// return author link
		return $this->LINKS['thread'] . $thread;
	}


	/* ------------------------------------------------------------------ */
	//	Fetch Posts
	//  Grabs posts of specified conditions and stores into $POSTS
	/* ------------------------------------------------------------------ */
	
	function fetch_Posts($number_to_fetch, $ids_to_fetch, $headlines_only = false)
	{
		// +------------------------------
		//	Fetch RSS Data
		// +------------------------------

		// load data from rss
		$this->PARSER->load();

		// process data
		$this->PARSER->parse();

		$ROOT = $this->PARSER->getRoot();

		// detect version
		if ($ROOT->getName() == "RSS")
		{
			switch($ROOT->getAttribute("version"))
			{
				default: $version = "RSS";
			}
		}
		else
		{
			$version = "RDF";
		}


		for($i=0; $i<$ROOT->getNumberChildren(); $i++)
		{
			if ($ROOT->getChild($i+1)->getName() != "ITEM")
			{
				for($j=0; $j<$ROOT->getChild($i+1)->getNumberChildren(); $j++)
				{
					if ($ROOT->getChild($i+1)->getChild($j+1)->getName() == "ITEM")
					{
						$ITEMS[] = $ROOT->getChild($i+1)->getChild($j+1);
					}
				}
			}
			else
			{
				$ITEMS[] = $ROOT->getChild($i+1);
			}
		}


		// cycle through results of $post
		foreach($ITEMS as $current_post)
		{
			switch($version)
			{
				case "RDF:RDF" :
				case "RDF" : 	
					$this->parsePost_RDF($current_post);
				break;

				default : $this->parsePost_RSS($current_post);
			}

		}

	}



	function getNewsData($child)
	{
		if (is_object($child))
		{
			$numData = $child->getNumberData();
			$numChildren = $child->getNumberChildren();

			for($i=0; $i<$numData || $i<$numChildren; $i++)
			{
				if ($i < $numData)
				{
					$data = $child->getData($i+1);
					if (!empty($data))
						$newsPost .= $data;
				}

				if ($i < $numChildren)
				{

					$data = $this->getNewsData($child->getChild($i+1));
					if ($data == "")
					{
						$newsPost .= "<" . $child->getChild($i+1)->getName() . $child->getChild($i+1)->getAttributesString() . " />";
					}
					else
					{
						$newsPost .= "<" . $child->getChild($i+1)->getName() . $child->getChild($i+1)->getAttributesString() . ">"
									. $data
									. "</" . $child->getChild($i+1)->getName() . ">";
					}
				}
			}
		}
		else
		{
			$newsPost = "";
		}

		return $newsPost;
	}


	function parsePost_RSS($post)
	{
		$date = $this->getNewsData($post->getChildByName("PUBDATE"));

		if (empty($date))
			$date = $this->getNewsData($post->getChildByName("DC:DATE"));

		// +------------------------------
		//	Store Post Data
		// +------------------------------
		
		// Store into post data
		$this->POSTS[] = array(
								"text" => $this->getNewsData($post->getChildByName("DESCRIPTION")),
								"title" => $this->getNewsData($post->getChildByName("TITLE")),
								"author_name" => $this->getNewsData($post->getChildByName("AUTHOR")),
								"author_id" => $this->getNewsData($post->getChildByName("AUTHOR")),
								"date" => (!empty($date) ? strtotime($date) : ""),
								"reply_num" => "",
								"view_num" => "",
								"post_id" => $this->getNewsData($post->getChildByName("GUID")),
								"cat_id" => $this->getNewsData($post->getChildByName("CATEGORY")),
								"author_url" => "",
								"post_url" => $this->getNewsData($post->getChildByName("COMMENTS"))
							);
	}


	function parsePost_RDF($post)
	{

		// +------------------------------
		//	Store Post Data
		// +------------------------------
		
		// Store into post data
		$this->POSTS[] = array(
								"text" => $this->getNewsData($post->getChildByName("DESCRIPTION")),
								"title" => $this->getNewsData($post->getChildByName("TITLE")),
								"author_name" => $this->getNewsData($post->getChildByName("DC:CREATOR")),
								"author_id" => $this->getNewsData($post->getChildByName("DC:CREATOR")),
								"date" => strtotime($this->getNewsData($post->getChildByName("DC:DATE"))),
								"reply_num" => "",
								"view_num" => "",
								"post_id" => $this->getNewsData($post->getAttribute("RDF:ABOUT")),
								"cat_id" => $this->getNewsData($post->getChildByName("DC:SUBJECT")),
								"author_url" => "",
								"post_url" => $this->getNewsData($post->getChildByName("LINK"))
							);
	}


	/* ------------------------------------------------------------------ */
	//	Parse BB Code
	//  Parses $text for BB code that is specific to this forum.
	/* ------------------------------------------------------------------ */
	
	function parse_BBcode(&$text)
	{
		// Nothing --- This is RSS.
	}


	/* ------------------------------------------------------------------ */
	//	Parse Smilies
	//  Parses $text for forum's smilies
	/* ------------------------------------------------------------------ */
	
	function parse_Smilies(&$text)
	{
		// Nothing --- This is RSS.
	}


	/* ------------------------------------------------------------------ */
	//	Fetch Author Image
	//  Grabs avatar information from DB and stores into $AUTHOR_IMAGES
	/* ------------------------------------------------------------------ */
	
	function fetch_Author_Image($author)
	{
		// Nothing --- This is RSS.

		// store into author images array
		$this->AUTHOR_IMAGES[$author] = null;
	}


	/* ------------------------------------------------------------------ */
	//	Fetch Smilies
	//  Grabs smilies from database and stores into $SMILIES
	/* ------------------------------------------------------------------ */
	
	function fetch_Smilies()
	{
		// Nothing --- This is RSS.
	}


	/* ------------------------------------------------------------------ */
	//	get Author Image
	//  Returns full url to author image
	/* ------------------------------------------------------------------ */
	
	function get_Author_Image($author)
	{
		// Nothing --- This is RSS.
		return null;
	}


	/* ------------------------------------------------------------------ */
	//	get Posts
	//  Returns data stored in $POSTS
	/* ------------------------------------------------------------------ */
	
	function get_Posts()
	{
		// this is a simple return as posts are already in expected format.
		return $this->POSTS;
	}
}