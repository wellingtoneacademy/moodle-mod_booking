<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Display all options.
 *
 * @package mod_booking
 * @copyright 2016 Andraž Prinčič <atletek@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class GoogleUrlApi {

    // https://developers.google.com/url-shortener/v1/getting_started#APIKey Get a new key
    // Constructor
    function GoogleURLAPI($key, $apiURL = 'https://www.googleapis.com/urlshortener/v1/url') {
        // Keep the API Url
        $this->apiURL = $apiURL . '?key=' . $key;
    }

    // Shorten a URL
    function shorten($url) {
        // Send information along
        $response = $this->send($url);
        // Return the result
        return isset($response['id']) ? $response['id'] : false;
    }

    // Expand a URL
    function expand($url) {
        // Send information along
        $response = $this->send($url, false);
        // Return the result
        return isset($response['longUrl']) ? $response['longUrl'] : false;
    }

    // Send information to Google
    function send($url, $shorten = true) {
        // Create cURL
        $ch = curl_init();
        // If we're shortening a URL...
        if ($shorten) {
            curl_setopt($ch, CURLOPT_URL, $this->apiURL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("longUrl" => $url)));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->apiURL . '&shortUrl=' . $url);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Execute the post
        $result = curl_exec($ch);
        // Close the connection
        curl_close($ch);
        // Return the result
        return json_decode($result, true);
    }
}