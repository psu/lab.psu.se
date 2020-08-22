<?php
// https://stackoverflow.com/questions/15157876/html-list-to-csv

$filename = "sitemap-sa-eng"; // without ".html"
$delim    = ";";
$empty    = "(tom sida)";
$dom      = new DOMDocument;

$dom->preserveWhiteSpace = false;
$dom->loadHTMLFile("./$filename.html");

header("content-type:application/csv;charset=UTF-16LE");
header("Content-Disposition:attachment;filename=\"$filename.csv\"");

foreach ($dom->getElementsByTagName('li') as $li) {
  $depth    = get_depth($li);
  $name     = trim( mb_convert_encoding($li->childNodes->item(0)->nodeValue, 'UTF-16LE', 'UTF-8') );
  $name     = $name==''? $empty: $name;
  $href     = trim( $li->childNodes->item(0)->getAttribute('href') );  
  printf(
    "%s%s$delim%s$delim%s%s$delim%s".PHP_EOL,
    $depth,
    str_repeat($delim, $depth-1),
    '"=HYPERLINK(""'.$href.'""'.$delim.'""'.$name.'"")"',
    str_repeat($delim, 10-$depth),
    $name,
    $href
  );
}

function get_depth(DOMElement $element) {
    $depth = 0;
    while (
        $element->parentNode->tagName === 'li' ||
        $element->parentNode->tagName === 'ul'
    ) {
        if ($element->parentNode->tagName === 'ul') {
            $depth++;
        }
        $element = $element->parentNode;
    }
    return $depth;
}

?>
