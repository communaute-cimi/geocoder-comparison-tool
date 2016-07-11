<?php

require_once('../config.inc.php');

//$url_photon = 'http://france.photon.fluv.io/api/';
$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamUseHttpProxy = isset($_REQUEST['useHttpProxy']) ? $_REQUEST['useHttpProxy'] : FALSE;
$sParamUrlGeocoder = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : FALSE;
$sParamMockResponse = isset($_REQUEST['mockResponse']) ? $_REQUEST['mockResponse'] : FALSE;

try {
        
    if(function_exists('getMockResponse') &&  $sParamMockResponse == 'true') {
        echo getMockResponse();
        return 0;
    }
    
    if($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");        

    $aGeocodeParams = array(
        'q' => $sParamAddr . " " . $sParamCpVille
    );
    
    $uri = $sParamUrlGeocoder  . '?' . http_build_query($aGeocodeParams);
    
    if($sParamUseHttpProxy == "true") {
        $jsonResponse = RequestProxy::getCurl($uri, $oJsonConf['proxy']['host'], $oJsonConf['proxy']['port'], $oJsonConf['proxy']['user'], $oJsonConf['proxy']['pwd']);
    }else {
        $jsonResponse = @file_get_contents($uri);    
    }
    
    echo $jsonResponse;

}catch(Exception $ex) {
    echo json_encode(
        array(
            'error' => 1, 
            'message' => $ex->getMessage()
        )
    );
}

function getMockResponse() {
    $response = <<<EOF
    {
        "limit": 5,
        "attribution": "BAN",
        "version": "draft",
        "licence": "ODbL 1.0",
        "query": "4, rue du chemin de fer 91580 Etrechy",
        "type": "FeatureCollection",
        "features": [
            {
                "geometry": {
                    "type": "Point",
                    "coordinates": [2.193402, 48.494057]
                },
                "properties": {
                    "citycode": "91226",
                    "postcode": "91580",
                    "name": "4 Rue du Chemin de Fer",
                    "housenumber": "4",
                    "city": "\u00c9tr\u00e9chy",
                    "context": "91, Essonne, \u00cele-de-France",
                    "score": 0.9189272727272727,
                    "label": "4 Rue du Chemin de Fer 91580 \u00c9tr\u00e9chy",
                    "id": "ADRNIVX_0000000271564291",
                    "type": "housenumber",
                    "street": "Rue du Chemin de Fer"
                },
                "type": "Feature"
            }
        ]
    }
EOF;

    return $response;
}
