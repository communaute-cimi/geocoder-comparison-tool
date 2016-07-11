<?php 
interface IGeocoder_Response {
    public function getResponse();
}

class Geocoder {
    public function geocode($message) {
        return $message.PHP_EOL;
    }
}

abstract class Geocoder_Response implements IGeocoder_Response {
    protected $geocoderResponse;
    
    public function __construct($geocoderResponse) {
        if($geocoderResponse instanceof IGeocoder_Response) {
            $this->geocoderResponse = $geocoderResponse;
        }
        else {
            trigger_error('Invalid GeocoderResponse !', E_USER_ERROR);
        }
    }
    
}

class Geocoder_Response_Ban extends Geocoder_Response {
    
    public function __construct($geocoderResponse) {
        parent::__construct($geocoderResponse);    
    }
    
    public function getResponse() {
        echo 'Ban';   
    }
}
