<?php
function Smiley($texttoreplace)
{
    $smilies=array( 
    
    
    ':)' => "<img src='images/smile.gif'>",
    ':(' => "<img src='images/sad.gif'>",
    ':p' => "<img src='images/tongue.gif'>",
    ';)' => "<img src='images/wink.gif'>",
    ';smirk' => "<img src='images/smirk.gif'>",
    ':blush' =>"<img src='images/blush.gif'>",
    ':angry' =>"<img src='images/angry.gif'>",
    ':o'=>     "<img src='images/shocked.gif'>",
    ':shocked'=>     "<img src='images/shocked.gif' />",
    ':ninja'=>"<img src='images/ninja.gif' />",
    ':cool'=>"<img src='images/cool.gif' />",
    '(!)'=>"<img src='images/exclamation.gif' />",
    '(?)'=>"<img src='images/question.gif' />",
    '(heart)'=>"<img src='images/heart.gif' />",
    ':{blink}'=>"<img src='images/winking.gif'>",
    '{clover}'=>"<img src='images/clover.gif'>",
    ':[glasses]'=>"<img src='images/glasses.gif'>",
    ':[barf]'=>"<img src='images/barf.gif'>",
    ':[reallymad]'=>"<img src='images/mad.gif'>",
    'fuck'=>"$#$%",
    'Fuck'=>"&$#@"
  
 
    );

    $texttoreplace=str_replace(array_keys($smilies), array_values($smilies), $texttoreplace);
    return $texttoreplace;
}
?>