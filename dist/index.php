<?php
require_once ('config.inc.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Outil de test pour le géocodage</title>
		<meta charset="utf-8" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<script src="resources/libs/jQuery-2.1.1.js"></script>

		<link rel="stylesheet" href="resources/libs/bootstrap-3.2.0-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="resources/libs/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css">

		<script src="resources/libs/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>

		<script src="resources/libs/leaflet-0.7.3/leaflet.js"></script>
		<link rel="stylesheet" href="resources/libs/leaflet-0.7.3/leaflet.css" />

		<!-- awesome-markers pour la coloration des markers -->
		<script src="resources/libs/Leaflet.awesome-markers-2.0-develop/leaflet.awesome-markers.min.js"></script>
		<link rel="stylesheet" href="resources/libs/Leaflet.awesome-markers-2.0-develop/leaflet.awesome-markers.css" />
		<link rel="stylesheet" href="resources/libs/font-awesome-4.6.3/css/font-awesome.css" />

		<script src="resources/js/gct.js"></script>
		<script src="resources/js/gct-ui.js"></script>

		<script src="resources/libs/json.human/json.human.js"></script>
		<link rel="stylesheet" href="resources/libs/json.human/json.human.css" />

		<style>
            .jh-root {
                width: 100%;
                font-weight: normal;
                font-size: .8em;
                border: 1px solid blue;
            }
            .tbl_popup {
                padding: 5px;
                margin: 5px;
            }

            table tr .tr_btn {
                width: 70px;
                text-align: center;
            }

            table tr .tr_score {
                width: 120px;
                text-align: center;
            }
            table tr .tr_index {
                width: 40px;
                text-align: center;
            }

		</style>
	</head>

	<body onload="init('<?php echo $oJsonConf['map']['osmUrl']; ?>');">

		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href=".">Geocoder</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<form class="navbar-form navbar-left" role="search" id="frm_geocoding">
						<div class="form-group">
							<input type="text" name="nom_voie" class="form-control" placeholder="Voie">
							<input type="text" name="cp_ville" class="form-control" placeholder="CP / Ville">
						</div>

					</form>
					<ul class="nav navbar-nav navbar-right" data-toggle="modal" data-target=".bs-example-modal-sm">
						<li>
							<a href="#">A propos</a>
						</li>
					</ul>
					<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
						<div class="modal-dialog modal-md">
							<div class="modal-content">
								<div style="padding:10px;">
									<h2>Geocoding Viewer</h2>
									<div>
										(v 0.1)
									</div>
									<br/>
									<p>
										<h3>Viewer for differents geocoders</h3>
									</p>
									<br />
									<p>
										Source code on <a href="https://github.com/communaute-cimi/geocoding-viewer">GitHub</a>.
									</p>
									<p>
										Développement <a href="https://twitter.com/communaute_cimi">CIMI</a>
									</p>
								</div>
							</div>
						</div>
					</div>

				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>

		<div class="panel-body">
			<div class="col-md-7">
				<table class="table table-hover table-condensed table-bordered" id="geocodingTbl">

				</table>
			</div>
			<div class="col-md-5" style="margin 0px">
				<div id="map" style="height: 600px; "></div>
			</div>

		</div><!-- /.container -->
	</body>

	<script>
        $("#frm_geocoding").submit(function(event) {
            event.preventDefault();
        });

	</script>
</html>
