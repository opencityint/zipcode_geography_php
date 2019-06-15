<?php

class ZipCodeLookup {
	function zipsInit() {
		$radius = 50;// 50 km radius
		$zip = 10004;// lower tip Manhattan island
		$theresult = $this->zipsWithinRadius($zip, $radius);
		echo json_encode($theresult);
	}
	function zipsWithinRadius($zip, $rad) {	
		$db = new PDO('mysql:host=localhost;dbname=yourdb;charset=utf8', 'user', 'password');
		$sql = $db->prepare("SELECT * FROM zipcodes WHERE zip= :zip LIMIT 1");
		$sql->execute(array(':zip' => $zip));
		$res = $sql->fetch(PDO::FETCH_ASSOC);

		$lat = $res['latitude'];
		$lon = $res['longitude'];		
		$R = 6371;  // earth's mean radius in km
		// miles 3959
		$maxLat = $lat + rad2deg($rad/$R);
		$minLat = $lat - rad2deg($rad/$R);
		// compensate for degrees longitude getting smaller with increasing latitude
		$maxLon = $lon + rad2deg($rad/$R/cos(deg2rad($lat)));
		$minLon = $lon - rad2deg($rad/$R/cos(deg2rad($lat)));
		
		$zsql = "SELECT zip, city, state, (
		      6371 * acos (
		      cos ( radians(:lat) )
		      * cos( radians( latitude ) )
		      * cos( radians( longitude ) - radians(:lon) )
		      + sin ( radians(:lat) )
		      * sin( radians( latitude ) )
		    )
		) AS distance FROM zipcodes HAVING distance < :rad ORDER BY distance";

		$prams = array(
			':lat'	=> $lat,
			':lon'	=> $lon,
			':rad'    => $rad
		);
		$points = $db->prepare($zsql);
		$points->execute($prams);
		$zips = $points->fetchAll(PDO::FETCH_ASSOC);
		
		return $zips;
	}
}
?>