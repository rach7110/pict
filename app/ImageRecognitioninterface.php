<?php
 namespace App;
 
 interface ImageRecognitionInterface
{
    public function send_request($input);
    
    public function outputs($response);
} 