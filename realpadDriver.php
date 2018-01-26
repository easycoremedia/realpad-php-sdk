<?php

/**
 * Created by PhpStorm.
 * User: slava
 * Date: 24.01.2018
 * Time: 15:34
 */

namespace Core;
require_once 'Logger.php';

class realpadDriver
{
    /**
     * @var string
     * @description REALPAD endpoint
     */
    private $endpoint;

    /**
     * @var Logger
     */
    public $logger;


    /**
     * @var
     */
    private $action;

    /**
     * @var int
     * @description Timeout between queries if cURL fails.
     */
    private $timeOut = 1;

    /**
     * @var string
     * @description We have 2 types of environment - test and production. We will provide the * production API URL alongside with the credentials.
     */
    private $env = 'test';

    /**
     * @var array
     */
    private $errorResponse = ['Parameter missing.', 'Incorrect parameter format.', 'Not authorized.', 'Invalid screen ID.', 'Unexpected parameters found.'];

    /**
     * RealPadAPI constructor.
     * @param $endpoint
     */
    public function __construct($endpoint)
    {
        $this->endpoint = 'https://' . $this->env . '.realpad.eu' . '/ws/v10/' . $endpoint;
        $this->logger = new Logger();
        $this->action = $endpoint;
    }

    /**
     * @param $body
     * @return string XML
     * @description You can use cURL or any other PHP package. We suggest to use https://github.com/ivoba/Buzzle/blob/master/src/Browser.php
     * @Method POST is required
     */
    public function post($body)
    {

        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $ch = curl_init();

        // Set REALPAD API endpoint
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        // Request body must be as array
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        if (curl_error($ch)) {
            // If error - will log this
            $this->logger::log($ch);

            // Wait a bit and try again
            sleep($this->timeOut);
            return $this->post($body);
        }
        if(in_array($response, $this->errorResponse)){
            return $this->logger::log($response);
        }

        if($this->action == 'create-lead') {
            return $response;
        }
        $xml = new \SimpleXMLElement($response);

        curl_close($ch);
        return $xml;
    }
}