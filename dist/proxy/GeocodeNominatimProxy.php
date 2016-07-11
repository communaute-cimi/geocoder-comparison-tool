<?php

$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamUrlGeocoder = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : "";
$sParamProxy = isset($_REQUEST['proxy']) ? $_REQUEST['proxy'] : FALSE;
$sParamMockResponse = isset($_REQUEST['mockResponse']) ? $_REQUEST['mockResponse'] : FALSE;

try {

    if(function_exists('getMockResponse') &&  $sParamMockResponse == 'true') {
        echo getMockResponse();
        return 0;
    }

    $aGeocodeParams = array(
        'q' => $sParamAddr . " " . $sParamCpVille,
        'format' => 'json',
        // 'limit' => 10,
        'polygon_geojson' => 1
    );
    
    $uri = $sParamUrlGeocoder  . '?' . http_build_query($aGeocodeParams);

    if($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");
    
    $jsonResponse = @file_get_contents($uri);
    
    if($jsonResponse !=false) {
        header('Content-Type: application/json');
        echo ($jsonResponse);    
    }else {
        throw new Exception("Pas de réseau", 1);
    }
    
}catch(Exception $ex) {
    header('Content-Type: application/json');
    echo json_encode(
        array(
            'error' => 1, 
            'message' => $ex->getMessage()
        )
    );
}

function getMockResponse() {
    $response = <<<EOF
[
    {
        "place_id": "144343917",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http:\/\/www.openstreetmap.org\/copyright",
        "osm_type": "relation",
        "osm_id": "333146",
        "boundingbox": ["48.4727556", "48.5184617", "2.1371235", "2.2171554"],
        "lat": "48.4930895",
        "lon": "2.1909175",
        "display_name": "Étréchy, Étampes, Essonne, Île-de-France, France métropolitaine, 91580, France",
        "class": "boundary",
        "type": "administrative",
        "importance": 0.30567999588715,
        "icon": "http:\/\/nominatim.openstreetmap.org\/images\/mapicons\/poi_boundary_administrative.p.20.png"
    }, {
        "place_id": "46302230",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http:\/\/www.openstreetmap.org\/copyright",
        "osm_type": "node",
        "osm_id": "3274558635",
        "boundingbox": ["48.4876598", "48.4877598", "2.1968482", "2.1969482"],
        "lat": "48.4877098",
        "lon": "2.1968982",
        "display_name": "Étréchy, Étampes, Essonne, Île-de-France, France métropolitaine, 91580, France",
        "class": "place",
        "type": "postcode",
        "importance": 0.225
    }, {
        "place_id": "145244253",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http:\/\/www.openstreetmap.org\/copyright",
        "boundingbox": ["48.493109474635", "48.493209474635", "2.186140759925", "2.186240759925"],
        "lat": "48.4931594746352",
        "lon": "2.18619075992503",
        "display_name": "Étréchy, Étampes, Essonne, Île-de-France, France métropolitaine, 91580, France",
        "class": "place",
        "type": "postcode",
        "importance": 0.225
    }, {
        "place_id": "46667207",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http:\/\/www.openstreetmap.org\/copyright",
        "osm_type": "node",
        "osm_id": "3484287069",
        "boundingbox": ["48.4886692", "48.4986692", "2.1895652", "2.1995652"],
        "lat": "48.4936692",
        "lon": "2.1945652",
        "display_name": "Étréchy, Rue de Vintué, Étréchy, Étampes, Essonne, Île-de-France, France métropolitaine, 91580, France",
        "class": "railway",
        "type": "station",
        "importance": 0.20436424356534,
        "icon": "http:\/\/nominatim.openstreetmap.org\/images\/mapicons\/transport_train_station2.p.20.png"
    }, {
        "place_id": "92949948",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http:\/\/www.openstreetmap.org\/copyright",
        "osm_type": "way",
        "osm_id": "145489240",
        "boundingbox": ["48.4916097", "48.4918074", "2.1901393", "2.1905353"],
        "lat": "48.49171715",
        "lon": "2.19036934164226",
        "display_name": "Étréchy, Grande Rue, Étréchy, Étampes, Essonne, Île-de-France, France métropolitaine, 91580, France",
        "class": "amenity",
        "type": "post_office",
        "importance": 0.001,
        "icon": "http:\/\/nominatim.openstreetmap.org\/images\/mapicons\/amenity_post_office.p.20.png"
    }, {
        "place_id": "18216721",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http:\/\/www.openstreetmap.org\/copyright",
        "osm_type": "node",
        "osm_id": "1919188073",
        "boundingbox": ["48.4935932", "48.4936932", "2.1946032", "2.1947032"],
        "lat": "48.4936432",
        "lon": "2.1946532",
        "display_name": "Étréchy, Rue de Vintué, Étréchy, Étampes, Essonne, Île-de-France, France métropolitaine, 91580, France",
        "class": "railway",
        "type": "stop",
        "importance": 0.001
    }, {
        "place_id": "95432494",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http:\/\/www.openstreetmap.org\/copyright",
        "osm_type": "way",
        "osm_id": "145493758",
        "boundingbox": ["48.4936265", "48.4938284", "2.1942511", "2.1944754"],
        "lat": "48.4937267",
        "lon": "2.19436247742377",
        "display_name": "Étréchy, Rue Pasteur, Étréchy, Étampes, Essonne, Île-de-France, France métropolitaine, 91580, France",
        "class": "building",
        "type": "yes",
        "importance": 0.001
    }
]
EOF;

    return $response;
}



// 