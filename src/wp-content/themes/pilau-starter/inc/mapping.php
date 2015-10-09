<?php

/**
 * Mapping stuff
 *
 * Built to work with postcodes.io, but intended to keep a good level of
 * abstraction in case another service or method is needed
 *
 * @package	Pilau_Starter
 * @since	0.1
 */
global $postcode_uk_regex;
$postcode_uk_regex = '/([A-Za-z]{1,2}[0-9]{1,2})[\s]*([0-9][A-Za-z]{2})/';


/**
 * Get information related to a postcode
 *
 * @param	string		$postcode		Full UK postcode
 * @param	string		$info_type		'eastings' | 'northings' | 'country' | 'longitude' |
 * 										'latitude' | 'codes'
 * @return	mixed						Null if input didn't allow a valid request; False if
 * 										there was an error retrieving the info; otherwise a
 * 										string or possibly an array object
 */
function pilau_get_postcode_info( $postcode, $info_type ) {
	global $postcode_uk_regex;
	$info = 'nowt';
	$postcode = preg_replace( '/[^a-z0-9]/', '', strtolower( $postcode ) );

	// Check postcode is full and valid
	if ( preg_match( $postcode_uk_regex, $postcode ) === 1 ) {

		// Do request to postcodes.io
		$response = wp_remote_get(
			'https://api.postcodes.io/postcodes/' . $postcode,
			array()
		);

		// Good response?
		if ( wp_remote_retrieve_response_code( $response ) == 200 ) {

			// Parse JSON in body
			$infos = json_decode( wp_remote_retrieve_body( $response ) );
			$infos = $infos->result;

			// Check for info
			if ( array_key_exists( $info_type, $infos ) ) {
				$info = $infos->{$info_type};
			} else {
				$info = false;
			}

		} else {

			// Signal a bad response
			$info = false;

		}

	}

	return $info;
}
