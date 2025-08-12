<?php

function error() 
{
    global $PH;
    require_once("render/render_list.inc");

    ### set up page ####
    {
        $page= new Page();

        $page->tabs['error']=  array('target'=>"index.php?go=error",     'title'=>"Error", 'bg'=>"error");
    	$page->cur_tab='error';
    	$page->crumbs=array(
    		#"Home",
    	);
    	$page->options=array(
            '',
            $PH->getWikiLink()
    	);

        $page->title="Unknown Page";
        $page->type="Error";
        $page->title_minor=get('go');
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    $block=new PageBlock(array('title'=>'Error'));
    $block->render_blockStart();
    echo "<div class=text>";
    echo "<p>Sorry but you found a function that has not yet been implemented.<br>";
    echo "If you feel this a bug, or a very important function is missing, please help us to fix this,
    Please hit the back-button of your browser and use the 'Wiki + Help' option to follow to the online
    documentation. Then edit 'issue' or 'request-part'.<p>";

    echo "</div>";

    $block->render_blockEnd();

    $block=new PageBlock(array('title'=>'Details'));
    $block->render_blockStart();
    echo "<div class=text>";
    echo "<pre>";
    echo renderBacktrace(debug_backtrace());

    echo "</pre>";
    echo "</div>";

    $block->render_blockEnd();


    echo (new PageContentClose);
	echo (new PageHtmlEnd);
}


# line ckomment

/**
 block comment
*/



?>