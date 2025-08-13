<?php

define('LANG_CHARSET', 'iso-8859-1' );

// 1) splitting the rows on '|' (pipe)
// 2) splitting the blocks of characters on ';' (semi-colon)
// 3) splitting the indivudua characters on ',' (coma)

// EG: 'A;B;C;D;E;F;G;H;I;J;K;L'
//     Gives 'A B C D E F G H I J K L' on one line

// EG: 'A,B,C;D,E;F|G;H,I;J,K,L'
//     Gives 'ABC DE F' on line 1, and 'G HI JKL' on line 2

define('ALPHA_ARRAY', 'A;B;C;D;E;F;G;H;I;J;K;L|M;N;O;P;Q;R;S;T;U;V;W;X|Y;Z;1;2;3;4;5;6;7;8;9;0');

?>