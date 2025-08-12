<?php
// -------------------------------------------------------------
//
// $Id: pgsql.php,v 1.4 2005/05/05 07:25:10 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

class pgsql
{
	// ---------------------------
	var $host = SQL_HOST;
	var $port = SQL_PORT;
	var $user = SQL_USER;
	var $password = SQL_PASSWORD;
	var $database = SQL_DATABASE;
	// ---------------------------
	var $link_id;
	var $num_queries = 0;
	var $query_id;
	// ---------------------------

	function close()
	{
		return ($this->link_id) ? @pg_close($this->link_id) : false;
	}

	function connect()
	{
		$str = 'host=' . $this->host . ' port=' . $this->port . ' dbname=' . decode($this->database) . ' user=' . decode($this->user) . ' password=' . decode($this->password);
		$this->link_id = @pg_connect($str);
		return ($this->link_id) ? $this->link_id : $this->error('');
	}

	function error($sql)
	{
		if (!$this->link_id)
		{
			echo '<p>Connection to PostgreSQL server failed.</p>';
			exit();
		}
		if (!$this->query_id)
		{
			printf('<p>Error in query "<code>%s</code>".</p>', $sql);
			exit();
		}
	}

	function fetch()
	{
		return ($this->query_id) ? @pg_fetch_array($this->query_id) : false;
	}

	function insert_id()
	{
		if ($this->query_id && $this->last_query_id != '')
		{
			if (preg_match('#^INSERT[\t\n ]+INTO[\t\n ]+([a-z\_]+)#si', $this->last_query_id, $table))
			{
				$seq = array('genu_answers' => 'genu_answers_answer_id_seq',
						'genu_categories' => 'genu_categories_category_id_seq',
						'genu_comments' => 'genu_comments_comment_id_seq',
						'genu_news' => 'genu_news_news_id_seq',
						'genu_posts' => 'genu_posts_post_id_seq',
						'genu_questions' => 'genu_questions_question_id_seq',
						'genu_smilies' => 'genu_smilies_smiley_id_seq',
						'genu_users' => 'genu_users_user_id_seq',
						'genu_votes' => 'genu_votes_vote_id_seq');
				$last_id = @pg_query($this->link_id, 'SELECT currval(\'' . $seq[$table[1]] . '\') AS last_id');
				if (!$last_id)
				{
					return false;
				}
				$insert_id = @pg_fetch_array($last_id, NULL, PGSQL_ASSOC);
				return ($insert_id) ? $insert_id['last_id'] : false;
			}
		}
		return false;
	}

	function num_queries()
	{
		return $this->num_queries;
	}

	function num_rows()
	{
		return ($this->query_id) ? @pg_num_rows($this->query_id) : false;
	}

	function query($sql = '')
	{
		if (!$this->connect())
		{
			return false;
		}
		elseif ($sql != '')
		{
			$this->num_queries++;
			$sql = preg_replace('#LIMIT ([0-9]+),([ 0-9]+)#si', 'LIMIT \\2 OFFSET \\1', $sql);
			$this->last_query_id = $sql;
			$this->query_id = @pg_query($this->link_id, $sql);
			if ($this->query_id)
			{
				return $this->query_id;
			}
			else
			{
				return $this->error($sql);
			}
		}
		else
		{
			return false;
		}
	}
}

?>