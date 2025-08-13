<?php
// +-----------------------------------------------------------------------+
// | Copyright (c) 2002-2003, Richard Heyes                                |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | o Redistributions of source code must retain the above copyright      |
// |   notice, this list of conditions and the following disclaimer.       |
// | o Redistributions in binary form must reproduce the above copyright   |
// |   notice, this list of conditions and the following disclaimer in the |
// |   documentation and/or other materials provided with the distribution.|
// | o The names of the authors may not be used to endorse or promote      |
// |   products derived from this software without specific prior written  |
// |   permission.                                                         |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
// |                                                                       |
// +-----------------------------------------------------------------------+
// | Author: Richard Heyes <richard@phpguru.org>                           |
// +-----------------------------------------------------------------------+

	require('./class/Tree.php');

/**
* Example of Tree class.
*/
	echo '<html><body><pre>';
	$tree = &new Tree();
	
	$node1 = &$tree->nodes->addNode(new Tree_Node('1'));
	$node2 = &$tree->nodes->addNode(new Tree_Node('2'));
	$node3 = &$tree->nodes->addNode(new Tree_Node('3'));
	
	$node2->nodes->addNode(new Tree_Node('2_1'));
	$node2_2 = &$node2->nodes->addNode(new Tree_Node('2_2'));
	$node2->nodes->addNode(new Tree_Node('2_3'));
	
	echo "<h2>Dumping entire tree:</h2>\r\n\r\n";
	print_r($tree);
	
	/**
    * This next call will not only dump the 2_3 node,
	* but also, thanks to the "tree" and "parent" properties,
	* the entire tree.
    */
	echo "\r\n<h2>Dumping results of search() for 2_3 node:</h2>\r\n\r\n";
	print_r($tree->nodes->search('2_3'));

	echo "\r\n<h2>Dumping results of indexOf() method:</h2>\r\n\r\n";
	print_r($tree->nodes->indexOf($node2));
	
	echo "\r\n<h2>Removing second node from tree and re-dumping:</h2>\r\n\r\n";
	$node2->remove();
	print_r($tree);
?>
</pre></body></html>