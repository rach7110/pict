<?php
 namespace App;
 
 interface ImageRecognitionInterface
{
    public function send_request($input);
     public function display_output($response);
} 