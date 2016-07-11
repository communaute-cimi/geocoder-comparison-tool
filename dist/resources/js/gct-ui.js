/**
 * Generate IHM for Nominatim geocoder
 * 
 */
function processNominatimResponse(geoResult, groupResultIndex) {
    var response = geoResult.response, context = geoResult.context, featureGroup=geoResult.featureGroup;
    
    for(var i=0; i<response.length;i++) {
        element = response[i];

        $("#geocodingTbl").append(generateGeocoderResultLine(i, element.display_name.substr(0, 80), element.importance, groupResultIndex));
        $("#geocodingTbl").append(renderDetails(element));
        // Ajouter les points de géoloc
        lon = element.lon;
        lat = element.lat;

        L.marker([lat, lon], {icon: L.AwesomeMarkers.icon({"markerColor":"cadetblue", "icon":"glyphicon-map-marker"})}).bindPopup(renderPopup(element)).addTo(featureGroup);        
        // L.circle([lat, lon], 40,  { color: 'red', fillColor: context.color, fillOpacity: 0.4 }).addTo(featureGroup);
    }
    
    map.addLayer(featureGroup);
    map.fitBounds(featureGroup.getBounds());
    
    function renderDetails(element) {
        var result = '<tr class="details" style="display:none;">';
            result+= '  <td colspan="4"><table class="jh-root">'+ $(JsonHuman.format(element)).html() + '</table></td>';
            result+= '</tr>';
        return result;
    }
    
    function renderPopup(element) {
        var result = '<table class="tbl_popup">';
            result+= '  <tr><td>place_id</td><td>{place_id}</td></tr>'.format({"place_id":element.place_id});
            result+= '  <tr><td>osm_type</td><td>{osm_type}</td>'.format({"osm_type":element.osm_type});
            result+= '  <tr><td>lat</td><td>{lat}</td></td>'.format({"lat":element.lat});
            result+= '  <tr><td>lon</td><td>{lon}</td></td>'.format({"lon":element.lon});
            result+= '</table>';
        return result;
    }    
}

/**
 * Generate IHM for BAN geocoder
 * https://adresse.data.gouv.fr/api/
 */
function processBanResponse(geoResult, groupResultIndex) {
    var response = geoResult.response, context = geoResult.context, featureGroup=geoResult.featureGroup; 
    
    for(var i=0; i<response.features.length;i++) {
        element = response.features[i];
        address = element.properties.label;
        $("#geocodingTbl").append(generateGeocoderResultLine(i, element.properties.label.substr(0, 80), element.properties.score, groupResultIndex));
        $("#geocodingTbl").append(renderDetails(element));

        // Ajouter les points de géoloc
        if(element.geometry.type=="Point") {
            lon = element.geometry.coordinates[0];
            lat = element.geometry.coordinates[1];
            L.marker([lat, lon], {icon: L.AwesomeMarkers.icon({"markerColor":context.iconColor, "icon":"glyphicon-map-marker"})}).bindPopup(renderPopup(element)).addTo(featureGroup);
            // L.circle([lat, lon], 40,  { color: "red", fillColor: context.color, fillOpacity: 0.4 }).addTo(featureGroup);

            map.addLayer(featureGroup);
            map.fitBounds(featureGroup.getBounds());
        }   
    }
    
    function renderDetails(element) {
        var result = '<tr class="details" style="display:none;">';
            result+= '  <td colspan="4"><table class="jh-root">'+ $(JsonHuman.format(element)).html() + '</table></td>';
            result+= '</tr>';
        return result;
    }
    
    function renderPopup(element) {
        try {
            var result = '<table class="tbl_popup">';
                result+= '  <tr><td>id</td><td>{id}</td></tr>'.format({"id":element.properties.id});
                result+= '  <tr><td>type</td><td>{type}</td>'.format({"type":element.properties.type});
                result+= '  <tr><td>lat</td><td>{lat}</td></td>'.format({"lat":element.geometry.coordinates[1]});
                result+= '  <tr><td>lon</td><td>{lon}</td></td>'.format({"lon":element.geometry.coordinates[0]});
                result+= '</table>';
            return result;            
        }catch(e) {
            return "Erreur de rendu --> " + e;
            console.log(e);
        }

    }      
    
}

/**
 * Generate IHM for Google geocoder
 */
function processGoogleResponse(geoResult, groupResultIndex) {
    var response = geoResult.response, context = geoResult.context, featureGroup=geoResult.featureGroup;
    
    for(var i=0; i<response.results.length;i++) {
        element = response.results[i];
        console.log(element);
        address = element.formatted_address;

        $("#geocodingTbl").append(generateGeocoderResultLine(i, address.substr(0, 80), '', groupResultIndex));
        $("#geocodingTbl").append(renderDetails(element)); 

        lon = element.geometry.location.lng;
        lat = element.geometry.location.lat;
        
        L.marker([lat, lon], {icon: L.AwesomeMarkers.icon({"markerColor":context.iconColor, "icon":"glyphicon-map-marker"})}).bindPopup('').addTo(featureGroup);
        // L.circle([lat, lon], 40,  { color: "red", fillColor: context.color, fillOpacity: 0.1 }).addTo(featureGroup);
        map.addLayer(featureGroup);
        map.fitBounds(featureGroup.getBounds());
 
    }
    
    function renderDetails(element) {
        var result = '<tr class="details" style="display:none;">';
            result+= '  <td colspan="4"><table class="jh-root">'+ $(JsonHuman.format(element)).html() + '</table></td>';
            result+= '</tr>';
        return result;
    }
    
    function renderPopup(element) {
        try {
            var result = '<table class="tbl_popup">';
                result+= '  <tr><td>id</td><td>{id}</td></tr>'.format({"id":element.properties.id});
                result+= '  <tr><td>type</td><td>{type}</td>'.format({"type":element.properties.type});
                result+= '  <tr><td>lat</td><td>{lat}</td></td>'.format({"lat":element.geometry.coordinates[1]});
                result+= '  <tr><td>lon</td><td>{lon}</td></td>'.format({"lon":element.geometry.coordinates[0]});
                result+= '</table>';
            return result;            
        }catch(e) {
            return "Erreur de rendu --> " + e;
            console.log(e);
        }
    }   
}

/**
 * Generate IHM for Jdonref geocoder
 * https://adullact.net/projects/jdonref-v2/
 */
function processJdonRefResponse(geoResult, groupResultIndex) {
    var response = geoResult.response, context = geoResult.context, featureGroup=geoResult.featureGroup;
    
    for(var i=0; i<response.length;i++) {
        element = response[i];
        address = element.valide.donnees[3] + " " + element.valide.donnees[5];
        
        $("#geocodingTbl").append(generateGeocoderResultLine(i, address.substr(0, 80), element.valide.note.toString().substr(0,6), groupResultIndex));
        $("#geocodingTbl").append(renderDetails(element)); 
        
        lon = element.geocode.propositions.x;
        lat = element.geocode.propositions.y;
            
        L.marker([lat, lon], {icon: L.AwesomeMarkers.icon({"markerColor":context.iconColor, "icon":"glyphicon-map-marker"})}).bindPopup(renderPopup(element)).addTo(featureGroup);
        // L.circle([lat, lon], 40,  { color: "red", fillColor: context.color, fillOpacity: 0.1 }).addTo(featureGroup);
        map.addLayer(featureGroup);
        map.fitBounds(featureGroup.getBounds());
    }
    
    function renderDetails(element) {
        var result = '<tr class="details" style="display:none;">';
            result+= '  <td colspan="4"><table class="jh-root">'+ $(JsonHuman.format(element)).html() + '</table></td>';
            result+= '</tr>';
        return result;
    }
    
    function renderPopup(element) {
        try {
            var result = '<table class="tbl_popup">';
                result+= '  <tr><td>[geocode] codeRetour</td><td>{codeRetour}</td></tr>'.format({"codeRetour":element.geocode.codeRetour});
                result+= '  <tr><td>[geocode] service</td><td>{service}</td></tr>'.format({"service":element.geocode.propositions.service});
                result+= '  <tr><td>[valide] code</td><td>{code}</td>'.format({"code":element.valide.code});
                result+= '  <tr><td>[valide] note</td><td>{note}</td>'.format({"note":element.valide.note});
                result+= '  <tr><td>[geocode] type</td><td>{type}</td>'.format({"type":element.geocode.propositions.type});
                result+= '  <tr><td>lon</td><td>{lon}</td></td>'.format({"lon":element.geocode.propositions.x});
                result+= '  <tr><td>lat</td><td>{lat}</td></td>'.format({"lat":element.geocode.propositions.y});
                result+= '</table>';
            return result;            
        }catch(e) {
            return "Erreur de rendu --> " + e;
            console.log(e);
        }
    }   
}

/*
function line4Jdonref(response, context, featureGroup) {

    for(var i=0; i<response.length;i++) {
        element = response[i];
        console.log(context.conf.jsFunction);
        $("#geocodingTbl").append(generateGeocoderResultLine(i, element.valide.donnees[3], element.valide.donnees[5], element.valide.ids[5], element.geocode.propositions.type, '', element.valide.note, ''));
    }
    
    // Ajouter les points de géoloc
    lon = element.geocode.propositions.x;
    lat = element.geocode.propositions.y;
    
    L.circle([lat, lon], 40,  { color: 'red', fillColor:  context.color, fillOpacity: 0.7 }).addTo(featureGroup);
    L.marker([lat, lon], {icon: L.AwesomeMarkers.icon()}).bindPopup('note : ' +  '').addTo(featureGroup);
    
    map.addLayer(featureGroup);
    map.fitBounds(featureGroup.getBounds());    
}
*/


/**
 * Récupérer les information de configuration pour le géocodeur
 * ID est l'identificateur unique (clé 'id' dans json.conf)
 * @param {Object} id
 */

function getGeocoderConf(id) {
    var json = {};
    
    for(var i=0;i<confJson.geocoders.length;i++) {
        var geocoder = confJson.geocoders[i];
        if(geocoder['id']===id) {
            json = geocoder; 
        }
    }
    
    return json;
}

/**
 * Adding to html page each button declared in config file
 */
function addGeocoderButtons(){

    for(var i=0;i<confJson.geocoders.length;i++) {
        geocoder = confJson.geocoders[i];
        
        // frm_geocoding
        var r=$('<button>' + geocoder.shortName + '</button>').attr({
            type: "submit",
            id: geocoder.id,
            class:"btn btn-default",
            style:"background:{color}".format({"color":geocoder.color})
        });
        
        $("#frm_geocoding").append(r);
        
        $("#frm_geocoding").append('&nbsp;');
        
        // Handling click event
        $("#"+geocoder.id).click(function(event) {
            geocodeEvt(this);
        });
    }
    
    // Finaly add erase button
    var btnID = 'btn_clear_results';
    var r=$('<button><span class="glyphicon glyphicon-minus" aria-hidden="true"></span>' + '&nbsp;Clear' + '</button>').attr({
        type: "submit",
        id: btnID,
        value: 'new',
        class : "btn btn-default glyphicon-erase"
    });
    
    $("#frm_geocoding").append(r);
    
    $("#"+btnID).click(function(event) {
        clearTableResutlGeocoding('geocodingTbl');
    });
}

function generateGroupResultLine(groupResultIndex, context) {
    var address = context.search.addr + ' ' + context.search.cpVille;
    var dt_end = new Date();
    
    $("#geocodingTbl").append(
        "<tr onclick='groupResultRowClick(this, {resultIndex});' id='groupResult_{resultIndex}' class='result_head_row' style='background-color:{color};'><td colspan='4'>[{geocoder}] - {addr} - ({time})</td></tr>".format(
                {   "resultIndex":groupResultIndex,"geocoder":context.conf.shortName, "color": context.conf.color, 
                    "addr": address, "time": (dt_end - context.dt_start) / 1000}
        )
    );
}
/**
 * Clear both result list and map
 * @param {Object} id
 */
function clearTableResutlGeocoding(id) {
    $("#"+id).empty();
    $("#"+id).append('<tr><th class="tr_index">#</th><th>Address</th><th class="tr_score">score</th><th class="tr_btn" >-</th></tr>');
    
    for(var i=0;i<groupResult.results.length;i++) {
        groupResult.results[i].featureGroup.clearLayers();
    }
    
    groupResult = new GeocodingResultGroup();
}

/**
 * For geocoding result generate HTML row 
 * 
 * @param {Object} i                    row index
 * @param {Object} address              geocode address
 * @param {Object} score                geocode scoring
 * @param {Object} groupResultIndex     index of geocoding --> one geocoding = many row
 */

function generateGeocoderResultLine(i, address, score, groupResultIndex) {
    var result = "<tr>";
        result+= '  <td>{i}</td>'.format({"i": i});
        result+= '  <td>{address}</td>'.format({"address": address});
        result+= '  <td>{score}</td>'.format({"score": score.toString().substr(0,6)});
        result+= '  <td>' + '<button data-placement="left" data-toggle="popover" onclick="resultDetailsClick(this, {groupResultIndex}, {i});" type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon glyphicon-list-alt" aria-hidden="true" ></span></button>'.format({"groupResultIndex":groupResultIndex,"i":i});
        result+= '      &nbsp;<button onclick="resultLocationClick(this, {groupResultIndex}, {i});" type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon glyphicon glyphicon-map-marker" aria-hidden="true"></span></button>'.format({"groupResultIndex":groupResultIndex,"i":i});    
        result+= '  </td>';
        result+= '</tr>';

    return result;
}
