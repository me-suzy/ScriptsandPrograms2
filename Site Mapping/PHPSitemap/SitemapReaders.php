<?php

function SitemapHTMLReader($path)
{
	$contents = GetFileContents($path);
	if (preg_match('/<title>(.*)<\/title>/', $contents, $matches)) $title = $matches[1];
	return $title;
}

function SitemapPDFReader($path)
{
	$title = `c:/apache/htdocs/sitemap/pdfinfo $path`;
	$title = trim(substr($title, 8, strpos($title, 'Subject:') - 8));
	return $title;
}

?>