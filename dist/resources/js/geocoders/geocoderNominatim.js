var GeocodingResultGroup = function () {
    this.results = new Array();
    this.addResult = function(geocodingResult){
        return this.results.push(geocodingResult);    
    };
};

