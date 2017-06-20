<?php

// set paths
$rootPath               = __DIR__ .'/../';
$dataFolderPath         = $rootPath .'data/';
$dataMappingFolderPath  = $rootPath .'data/mapping/';
$srcFolderPath          = $rootPath .'src/';

// require important PHP files
require_once $srcFolderPath .'functions.php';

/*
 * load object => ... => ICF mappings
 */
$mapObjReqIcfFile = $dataMappingFolderPath . 'mapping-object-requirement-icf.json';
$mapObjReqIcf = json_decode(file_get_contents($mapObjReqIcfFile), true); // JSON to array of array

/*
 * load requirement metadata
 */
$reqFile = $dataMappingFolderPath . 'requirements.json';
$reqInfo = json_decode(file_get_contents($reqFile), true); // JSON to array of array

/*
 * load ICF metadata
 */
$icfFile = $dataMappingFolderPath . 'icf.json';
$icfInfo = json_decode(file_get_contents($icfFile), true); // JSON to array of array

/*
 * load BVL data source
 */
$bvlDataArray = loadCSVFileIntoArray($dataFolderPath . 'place-data-from-bvl.csv');

$buildingsWithRequirements = array();

foreach ($bvlDataArray as $lineIndex => $lineArray) {
    if (0 == $lineIndex) continue;

    // build ID to identify building later on
    $placeId = $lineArray[1] . ' - ' . $lineArray[6] . ', '. $lineArray[7] . ', ' . $lineArray[8];
    $buildingsWithRequirements[$placeId] = array(
        'steps' => array(),
        'swing-door' => array()
    );

    // Anzahl-der-Stufen-bis-Aufzug-in-der-Einrichtung > 0 means that there are steps
    // before the location which you have to overcome until you reach the building
    // we define steps as steps BEFORE the building
    if (0 < $lineArray[20]) {
        // properties
        $buildingsWithRequirements[$placeId]['steps']['affects-properties']
            = $mapObjReqIcf['object-to-requirement']['steps']['affects-properties'];

        // functions
        $buildingsWithRequirements[$placeId]['steps']['affects-functions']
            = $mapObjReqIcf['object-to-requirement']['steps']['affects-functions'];
    }

    // Tuerart-am-Eingang-Pendeltuer == "ja" means that entrance has a swing door
    // which has to be used manually
    if ('ja' == $lineArray[44]) {
        // properties
        $buildingsWithRequirements[$placeId]['swing-door']['affects-properties']
            = $mapObjReqIcf['object-to-requirement']['swing-door']['affects-properties'];

        // functions
        $buildingsWithRequirements[$placeId]['swing-door']['affects-functions']
            = $mapObjReqIcf['object-to-requirement']['swing-door']['affects-functions'];
    }

    // remove entries, which have no data
    if (0 == count($buildingsWithRequirements[$placeId]['steps'])
        && 0 == count($buildingsWithRequirements[$placeId]['swing-door'])) {
        unset($buildingsWithRequirements[$placeId]);
    } else {
        if (0 == count($buildingsWithRequirements[$placeId]['steps'])) {
            unset($buildingsWithRequirements[$placeId]['steps']);
        }
        if (0 == count($buildingsWithRequirements[$placeId]['swing-door'])) {
            unset($buildingsWithRequirements[$placeId]['swing-door']);
        }
    }
}

// now we know all buildings and their requirements to a user

// go through all found buildings and collect related ICF information
foreach ($buildingsWithRequirements as $buildingLabel => $structures) {
    $icfElements = array();

    echo 'Location: '. $buildingLabel .': ';

    // output for each structure
    foreach ($structures as $structure => $requirements) {
        echo PHP_EOL . '|' . PHP_EOL . '`-'. $structure;
        echo PHP_EOL . '  `-- has requirements:';

        /*
         * properties
         */
        if (isset($requirements['affects-properties'])) {
            foreach ($requirements['affects-properties'] as $prop) {
                echo PHP_EOL . '  |   `-- '. $prop .': '. $reqInfo['properties'][$prop]['label_de'];
                echo ' related to ICF elements: ';

                foreach (getAccordingICFElements($prop, $mapObjReqIcf, $icfInfo) as $icfElement) {
                    echo PHP_EOL . '          `-- ' . $icfElement;
                }

                $icfElements = array_merge($icfElements, getAccordingICFElements($prop, $mapObjReqIcf, $icfInfo));
            }
        }

        /*
         * functions
         */
        if (isset($requirements['affects-functions'])) {
            foreach ($requirements['affects-functions'] as $prop) {
                echo PHP_EOL . '  |   `-- '. $prop .': '. $reqInfo['functions'][$prop]['label_de'];
                echo ' related to ICF elements:';

                foreach (getAccordingICFElements($prop, $mapObjReqIcf, $icfInfo) as $icfElement) {
                    echo PHP_EOL . '          `-- ' . $icfElement;
                }

                $icfElements = array_merge($icfElements, getAccordingICFElements($prop, $mapObjReqIcf, $icfInfo));
            }
        }

        echo PHP_EOL . '  |' . PHP_EOL . '  `-- requires availability/functionality of the following ICF elements:';
        foreach ($icfElements as $icfElement) {
            echo PHP_EOL . '      `-- '. $icfElement;
        }
    }

    echo PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
}

echo PHP_EOL;
echo PHP_EOL;
