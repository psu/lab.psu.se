<?php

$html = "<ul>
    <li>Test column 01
        <ul>
            <li>Test column 02
                <ul>
                    <li>Test column 03
                        <ul>
                            <li>Test column 04
                                <ul>
                                    <li>Test column 05</li>
                                    <li>Test column 05</li>
                                    <li>Test column 05</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>";

$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;
$dom->loadHTML($html);

foreach ($dom->getElementsByTagName('li') as $li) {   // #1
  printf(
      '%s%s%s',
      str_repeat(',', get_depth($li)),                // #2
      trim($li->childNodes->item(0)->nodeValue),      // #3
      PHP_EOL
  );
}

function get_depth(DOMElement $element)
{
    $depth = -1;
    while (                                           // #4
        $element->parentNode->tagName === 'li' ||
        $element->parentNode->tagName === 'ul'
    ) {
        if ($element->parentNode->tagName === 'ul') { // #5
            $depth++;
        }
        $element = $element->parentNode;
    }
    return $depth;
}

?>
