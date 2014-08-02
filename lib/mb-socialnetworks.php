<?php
/*
Plugin Name: (MB) Social Networks
Plugin URI: http://www.mechabyte.com
Description: A widget that allows you to grab stats from popular social networks
Version: 1.00
Author: Mechabyte - Matthew Smith
Author URI: http://www.mechabyte.com
*/

/**
 * mechabyteYouTube Class
 */

class mechabyteSocialStats {
	public function facebook( $pageId ) {
		$cache = 60*60;
		$transient_id = 'mechabyteSocialFacebook';
		$cached_item = get_transient ( $transient_id );
		if ( !$cached_item || ($cached_item->pageId != $pageId ) ) {

			$api_url = "http://graph.facebook.com/%s";
			$response = wp_remote_get( sprintf($api_url, $pageId) );

			if ( is_wp_error( $response ) or ( wp_remote_retrieve_response_code( $response ) != 200 ) ) {
				return false;
			}

			$item_data = json_decode( wp_remote_retrieve_body( $response ), true );
			
			// If our info isn't in an array then these next steps won't work-- return false
			if ( !is_array( $item_data ) ) {
				return false;
			}

			$item_data = $item_data['likes'];

			// Create an object with our data
			$data_to_cache = new stdClass();
			$data_to_cache->pageId = $pageId;
			$data_to_cache->item_info = $item_data;
			// Store our data object as an item in the 'mechabyte_YouTube' transient
			set_transient( $transient_id, $data_to_cache, $cache );
			
			// Return the data we found
			return $item_data;
		}
		return $cached_item->item_info;
	}

	public function youtube( $username ) {

		$cache = 60*60;
		$transient_id = 'mechabyteSocialYoutube';
		$cached_item = get_transient ( $transient_id );
		if ( !$cached_item || ($cached_item->username != $username ) ) {

			$api_url = "https://gdata.youtube.com/feeds/api/users/%s?alt=json";
			$response = wp_remote_get( sprintf($api_url, $username) );

			if ( is_wp_error( $response ) or ( wp_remote_retrieve_response_code( $response ) != 200 ) ) {
				return false;
			}

			$item_data = json_decode( wp_remote_retrieve_body( $response ), true );
			
			// If our info isn't in an array then these next steps won't work-- return false
			if ( !is_array( $item_data ) ) {
				return false;
			}

			$item_data = $item_data['entry']['yt$statistics']['subscriberCount'];

			// Create an object with our data
			$data_to_cache = new stdClass();
			$data_to_cache->pageId = $pageId;
			$data_to_cache->item_info = $item_data;
			// Store our data object as an item in the 'mechabyte_YouTube' transient
			set_transient( $transient_id, $data_to_cache, $cache );
			
			// Return the data we found
			return $item_data;
		}
		return $cached_item->item_info;

	}
}

global $mechabyteSocialStats;
$mechabyteSocialStats = new mechabyteSocialStats();

?>