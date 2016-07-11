<?php
require_once('../config.inc.php');

$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamUseHttpProxy = isset($_REQUEST['useHttpProxy']) ? $_REQUEST['useHttpProxy'] : false;
$sParamUrl = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : FALSE;
$sParamMockResponse = isset($_REQUEST['mockResponse']) ? $_REQUEST['mockResponse'] : FALSE;

try {
    if ($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");
    if ($sParamUrl === FALSE) throw new Exception("Paramètre [url] absent");
    if(function_exists('getMockResponse') &&  $sParamMockResponse == 'true') {
        echo getMockResponse();
        return 0;
    }


    $aGeocodeParams = array('sensor' => 'false', 'address' => $sParamAddr . ' ' . $sParamCpVille);

    $uri = $sParamUrl . '?' . http_build_query($aGeocodeParams);

    if($sParamUseHttpProxy == "true") {
        $jsonResponse = RequestProxy::getCurl($uri, $oJsonConf['proxy']['host'], $oJsonConf['proxy']['port'], $oJsonConf['proxy']['user'], $oJsonConf['proxy']['pwd']);
    }else {
        $jsonResponse = @file_get_contents($uri);
    }
    
    $oResponse = json_decode($jsonResponse);

    if (count($oResponse -> results) == 0) {
        throw new Exception("Echec de géocodage", 1);
    }

    echo $jsonResponse;

} catch(Exception $ex) {
    echo json_encode(array('error' => 1, 'message' => $ex -> getMessage()));
}

function getMockResponse() {
    $response = <<<EOF
{
   "results" : [
      {
         "address_components" : [
            {
               "long_name" : "Étréchy",
               "short_name" : "Étréchy",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Essonne",
               "short_name" : "Essonne",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Île-de-France",
               "short_name" : "Île-de-France",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "France",
               "short_name" : "FR",
               "types" : [ "country", "political" ]
            },
            {
               "long_name" : "91580",
               "short_name" : "91580",
               "types" : [ "postal_code" ]
            }
         ],
         "formatted_address" : "91580 Étréchy, France",
         "geometry" : {
            "bounds" : {
               "northeast" : {
                  "lat" : 48.51846889999999,
                  "lng" : 2.2169131
               },
               "southwest" : {
                  "lat" : 48.4727849,
                  "lng" : 2.1371011
               }
            },
            "location" : {
               "lat" : 48.493478,
               "lng" : 2.190566
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 48.51846889999999,
                  "lng" : 2.2169131
               },
               "southwest" : {
                  "lat" : 48.4727849,
                  "lng" : 2.1371011
               }
            }
         },
         "place_id" : "ChIJKfysMorO5UcRwEaLaMOCCwQ",
         "types" : [ "locality", "political" ]
      }
   ],
   "status" : "OK"
}
    
EOF;

    return $response;
}
