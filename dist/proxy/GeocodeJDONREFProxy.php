<?php

$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamUrlGeocoder = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : "";

$oSoapClient = new SoapClient($sParamUrlGeocoder,array("trace" =>1));

try {

    if($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");

    $aAdresse = array(
        "",
        "",
        "",
        $sParamAddr,
        "",
        $sParamCpVille
    );
    
    $aResultGeocode = array();
    
    $requestValide = array(
        "application"   => 1,   
        "operation"     => 1,
        "donnees"       => $aAdresse
    );
    
	$oSoapValideResponse = $oSoapClient->valide($requestValide);

    if($oSoapValideResponse->return->codeRetour === 0) {
		throw new Exception($oSoapValideResponse->return->erreurs->message . ', code=' . $oSoapValideResponse->return->erreurs->code, 1);
        exit;
    }
    
    $aResultGeocode['total'] = count($oSoapValideResponse->return->propositions);
    $aResultValidGeocode = array();

    if(count($aResultGeocode['total'])>0) {
        $i = 0;
        foreach ($oSoapValideResponse->return->propositions as $proposition) {
                               
            // $aResultGeocode[$i]["valide"] = $proposition;
            $aValide = $proposition;

            $oSoapGeocodeResponse = geocodeAfterValid($oSoapClient, $proposition->donnees);

            if($oSoapGeocodeResponse->return->codeRetour === 0) {
                // throw new Exception($oSoapGeocodeResponse->return->erreurs->message . ', code=' . $oSoapGeocodeResponse->return->erreurs->code . ', service=' . $oSoapGeocodeResponse->return->erreurs->service, 1);
                // exit;
                //Créer JSON erreur
                $aGeocode = array("geocode" => array("erreur" => 1));
            }
            
            $aGeocode = $oSoapGeocodeResponse->return;

            $aResultValidGeocode[] = array('valide' => $aValide, 'geocode' => $aGeocode);

            $i++;
        }
    } else {
        throw new Exception("Erreur de validation", 1);
    }
    header('Content-Type: application/json');
    echo json_encode($aResultValidGeocode);
    
}catch(Exception $ex) {
    echo json_encode(
        array(
            'error' => 1, 
            'message' => $ex->getMessage()
        )
    );
}



function geocodeAfterValid($oSoapClient, $aAdresse) {
    
    try {

        // Virer la ligne PAYS le cas échéant
        if(count($aAdresse) == 7) array_pop($aAdresse);
        
        $requestGeocode = array(
            "application"   => 1,  
            "operation"     => 2,
            "services"      => 3, 
            "donnees"       => $aAdresse
        ); 

        $oSoapGeocodeResponse = $oSoapClient->geocode($requestGeocode);
        
        return $oSoapGeocodeResponse;
        
    }catch(Exception $Ex) {
        throw new Exception("Erreur de géocodage après validation", 1);
    }   
}
