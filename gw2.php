<?php

//API JSON acquisition
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
curl_setopt($ch, CURLOPT_URL, "https://api.guildwars2.com/v1/events.json?world_id=2014");
$api = curl_exec($ch);
$api = json_decode($api, true);
curl_close($ch);

//Event Name
$filename = "GW2/event_names.json";
$handle = fopen($filename, "r");
$eventNames = fread($handle, filesize($filename));
fclose($handle);
$eventNames = json_decode($eventNames, true);

//Marker creating
$marker = '<script>var events = {};
	L.Map.include(L.LayerIndexMixin);';
	
for($i=0; $i < count($api['events']); $i++){
	if($api['events'][$i]['state'] == "Active"){
		$name = getEventName($api['events'][$i]['event_id'], $eventNames);
		$marker .=
		'events["'.$api['events'][$i]['event_id'].'"] = L.marker([50, '.$i.']).bindPopup("'.$name.'");
		map.addLayer(events["'.$api['events'][$i]['event_id'].'"])
		.indexLayer(events["'.$api['events'][$i]['event_id'].'"]);
		';
	}
}
$marker .= "map.on('moveend', function () {
        var shown = map.search(map.getBounds());
        console.log(shown.length + ' objects shown.');
		//console.log(shown);
    });</script>";

function getEventName($id, $eventNames){
	foreach($eventNames as $event){
		if($id == $event['id']){
			$name = $event['name'];
			break;
		}
	}
	return $name;
}

//Creating HTML template
$template =
'<html>
	<head>
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
		<script src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
		<script src="rtree.js"></script>
		<script src="GW2/leaflet.layerindex.js"></script>
	</head>
	<body>
		<div id="map" style="width: 100%; height: 100%"></div>
		<script src="GW2/gw2map.js"></script>
		'.$marker.'
	</body>
</html>'
;

echo $template;
?>