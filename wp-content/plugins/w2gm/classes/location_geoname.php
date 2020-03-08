<?php

class w2gm_locationGeoname {
	
	private $last_ret;
	
	private function getURL($query) {
		$fullUrl = '';
			
		if (get_option('w2gm_address_autocomplete_code')) {
			$iso3166 = strtolower(get_option('w2gm_address_autocomplete_code'));
			if ($iso3166 == 'gb') {
				$iso3166 = 'uk';
			}
			$region = '&region='.$iso3166;
		} else {
			$region = '';
		}
			
		if (get_option('w2gm_google_api_key_server')) {
			$fullUrl = sprintf("https://maps.googleapis.com/maps/api/place/textsearch/json?query=%s&language=en&key=%s%s", urlencode($query), get_option('w2gm_google_api_key_server'), $region);
		}
			
		return $fullUrl;
	}
	
	private function processResult($ret, $return) {
		$use_districts = true;
		$use_provinces = true;
		
		if ($ret) {
			if ($ret["status"] == "OK") {
				if ($return == 'coordinates') {
					return array($ret["results"][0]["geometry"]["location"]["lng"], $ret["results"][0]["geometry"]["location"]["lat"]);
				} elseif ($return == 'geoname') {
					$geocoded_name = array();
					foreach ($ret["results"][0]["address_components"] AS $component) {
						if (@$component["types"][0] == "sublocality") {
							$town = $component["long_name"];
							$geocoded_name[] = $town;
						}
						if (@$component["types"][0] == "locality") {
							$city = $component["long_name"];
							$geocoded_name[] = $city;
						}
						if ($use_districts)
							if (@$component["types"][0] == "administrative_area_level_3") {
								$district = $component["long_name"];
								$geocoded_name[] = $district;
							}
						if ($use_provinces)
							if (@$component["types"][0] == "administrative_area_level_2") {
								$province = $component["long_name"];
								$geocoded_name[] = $province;
							}
						if (@$component["types"][0] == "administrative_area_level_1") {
							$state = $component["long_name"];
							$geocoded_name[] = $state;
						}
						if (@$component["types"][0] == "country") {
							$country = $component["long_name"];
							$geocoded_name[] = $country;
						}
					}
					return implode(', ', $geocoded_name);
				} elseif ($return == 'address') {
					return @$ret["results"][0]["formatted_address"];
				}
			}
		}
		return '';
	}

	public function geocodeRequest($query, $return = 'geoname') {
		$fullUrl = $this->getURL($query);
		
		$response = wp_remote_get($fullUrl);
		$body = wp_remote_retrieve_body($response);
		
		$ret = json_decode($body, true);
		
		$this->last_ret = $ret;
		
		if ($return == 'test') {
			if (is_wp_error($response)) {
				return $response->get_error_message();
			} else {
				return $ret;
			}
		}
		
		$address = $this->processResult($ret, $return);

		return $address;
	}
	
	public function getLastStatus() {
		return $this->last_ret["status"];
	}
	
	public function getLastError() {
		return ($this->last_ret["error_message"]) ? $this->last_ret["error_message"] : '';
	}
}
?>