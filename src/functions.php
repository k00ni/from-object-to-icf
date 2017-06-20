<?php

/**
 * @param string $propertyOrFunction
 * @param array $mapping
 */
function getAccordingICFElements($propertyOrFunction, array $mapping, array $icfInfo)
{
    $elements = array();

    // go through all ICF elements related to given property/function requirement
    foreach ($mapping['requirement-to-icf-elements'][$propertyOrFunction] as $icf) {
        $elements[$icf] = $icfInfo[$icf]['label_de'];
    }

    return $elements;
}

/**
 * Loads a CSV file into a PHP array.
 * @param string $filepath
 * @param string $separator
 * @param string $quote
 * @return array
 */
function loadCSVFileIntoArray($filepath, $separator = ',', $quote = '"')
{
    $file = fopen($filepath, 'r');
    $lines = array();
    while (($line = fgetcsv($file, 0, $separator, $quote)) !== FALSE) {
        $lines[] = $line;
    }
    fclose($file);
    return $lines;
}
