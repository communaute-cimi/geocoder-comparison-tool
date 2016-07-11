
/**
 * Fichier de configuration
 */
var confJson = {}; 
var featureGroup;

/**
 * Defining result group class (one response many locations)
 */
var GeocodingResultGroup = function () {
    this.results = new Array();
    this.addResult = function(geocodingResult){
        return this.results.push(geocodingResult);    
    };
};

/**
 * Defining geocoding result (many locations)
 */
var GeocodingResult = function (response, featureGroup, context) {
    this.response = response;
    this.featureGroup = featureGroup;
    this.context = context;
};

// Creating an instance of GeocodingResultGroup
var groupResult = new GeocodingResultGroup();

/**
 * Add string prototype format (like python.format())
 * "myString {str}".format({"str":"is beautiful"} --> display myString is beautiful
 */
if (!String.prototype.format) {
    String.prototype.format = (function () {
        var str = this.toString();
        // console.log(arguments);
        if (!arguments.length)
            return str;
        var args = typeof arguments[0],
            args = (("string" == args || "number" == args) ? arguments : arguments[0]);
        for (arg in args)
            str = str.replace(RegExp("\\{" + arg + "\\}", "gi"), args[arg]);
        return str;
    });
}


/**
 * Initialisation
 *  - get configuration file
 *  - add geocoding buttons
 *  - initialize map
 */
function init() {

    // parse configuration file
    
    $.getJSON('./conf/conf.json', function (data){
        confJson=data;   
    }).done(function(response) {
            // Add button for each geocoder
            addGeocoderButtons();
            
            // conf params for map view
            initView = response.map.initView;
        
            // Initialize Map
            map = L.map('map').setView([initView.lat, initView.lon], initView.zoom);
        
            L.tileLayer(confJson.map.osmUrl + '{z}/{x}/{y}.png', {
                maxZoom : 19,
                attribution : 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, '
                    + '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, '
            }).addTo(map);
        
            clearTableResutlGeocoding('geocodingTbl');
              
        }).fail(function(e) {
            alert('ERROR !! Message : ' + e.statusText);
        });
}


/**
 * Event related to the geocoder button 
 *  
 */

function geocodeEvt(btn) {
	
	geocoderConf = getGeocoderConf(btn.id);

    try {	

    	var nomVoie = $("#frm_geocoding input[name=nom_voie]").val();
    	
    	// mandatory
    	var cpVille = $("#frm_geocoding input[name=cp_ville]").val();
    	var urlProxy = geocoderConf.urlProxy;
    	var urlGeocoder = geocoderConf.urlGeocoder;
    	
    	// Default false (even if not present in config file)
    	var mockResponse = (geocoderConf.mockResponse == true) ? true : false;
    	var useHttpProxy = (geocoderConf.useHttpProxy == true) ? true : false;
    	
    	var context = {"conf": geocoderConf, "search":{"addr": nomVoie, "cpVille": cpVille}, "dt_start" : new Date()};
    	
    	var datas =  jQuery.param({
    	    "addr": nomVoie, "cpVille": cpVille, "urlGeocoder": urlGeocoder, 
    	    "useHttpProxy":useHttpProxy, "mockResponse":mockResponse
        });

        $.ajax({
            url : urlProxy,
            data: datas,
            dataType : 'json',
            context : context,
            complete : displayGeocodeResult
        }).done(function(e) {
            // console.log( 'Done', e );
        }).fail(function(e) {
            throw("echec de l'appel ajax");
        });
    }catch(e) {
        /*TODO bootstrap panel */
       
        alert(e);
    }
}

/**
 * Display geocode result for all geocoders
 * 
 */
function displayGeocodeResult(response) {

    var context = this;
    
    try {
        var response = response.responseJSON;
        if(response.error == 1) {
            throw ("Erreur : " + response.message);
        }
        
        featureGroup = new L.featureGroup();
        var geoResult = new GeocodingResult(response, featureGroup, context); 
        var groupResultIndex = groupResult.addResult(geoResult);
        
        generateGroupResultLine(groupResultIndex, context);
        
        // Eval jsFunction used to display geocoder results
        s = eval(context.conf.jsFunction+'(geoResult, groupResultIndex);');
                
    }catch(e) {
        alert('Erreur, résultat de géocodage --> ' +  e);
        return 0;
    }
}

/**
 * Event : click on result row (TR)
 * @param {Object} TR dom element
 */

function groupResultRowClick(oElement) {
    // console.log(oElement);
    try {
        var groupResultIndex = parseInt(oElement.id.replace('groupResult_', ""));
        result = groupResult.results[groupResultIndex-1];
        map.fitBounds(result.featureGroup.getBounds());
    }catch(e) {
        alert(e);        
    }
}

/**
 * Event : click on result row (TR)
 * @param {Object} TR dom element
 */
function resultLocationClick(oElem, groupResultIndex,i) {
    try {
        console.log(groupResultIndex,i);
        var points = groupResult.results[groupResultIndex-1].featureGroup.getLayers();
        var point = points[i];
        point.openPopup();
        // point.popup();
        map.panTo(point.getLatLng());
    }catch(e) {
        // Can't get the point
        alert(e);
    }
}


function resultDetailsClick(oElem, groupResultIndex, i) {
    try {    
        var details = $(oElem).closest('tr').next().get(0);
        $(details).toggle();
    }catch(e) {
        alert(e);        
    }    
}
