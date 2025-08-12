<?php
function censor($content){
    //If you want to use the censor, make a file called censorwords.txt , and put each word you want censored on a new line. Be sure there are no blank lines at the bottom of the file, as that may turn into disaster!  
    //Written by Garrett P. www.garrettsites.net
    $words_list=@file('censorwords.txt');
    $search=array('a','b','i','l','o','p','s');
    $replace=array('(?:a|\@|\*)','(?:b|8|3|\*)','(?:i|1|l|\!|\*)','(?:l|1|i|\!|\*)','(?:o|0|\*)','(?:p|\?|\*)','(?:s|\$|\*)');
    foreach($words_list as $badword){
        $badword=rtrim($badword);
        $len=strlen($badword);
        $rep='';
        for($i=0; $i < $len; $i++){
            $rep.='-';
        }
        $badwordpreg=preg_split('//', $badword, -1, PREG_SPLIT_NO_EMPTY);
        $badwordpreg=str_replace($search, $replace, $badwordpreg);
        $badword='';
        for($i=0; $i < count($badwordpreg); $i++){
            $badword.=$badwordpreg[$i];
            if($i != (count($badwordpreg)-1)) $badword.='(.{0,5})';
        }
        $badword="/$badword/i";
        $content=preg_replace($badword, $rep, $content);
    }
    return $content;
}
?>