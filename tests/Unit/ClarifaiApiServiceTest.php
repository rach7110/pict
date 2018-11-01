<?php
namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\ImageRecognition\ClarifaiImageRecognition;

class ExampleTest extends TestCase
{
    /**
     * Test the request is sent succesfully.
     *
     * @group clarifai
     */
    public function testSendRequestToClarifaiSingleInput()
    {
        $svc = new ClarifaiImageRecognition;
        $input = ["https://samples.clarifai.com/metro-north.jpg"];
        $response = $svc->send_request($input);
         $this->assertEquals(true, $response->isSuccessful());
    }
     /**
     * Test the request is sent succesfully with multiple inputs.
     *
     * @group clarifai
     */
    public function testSendRequestToClarifaiMultipleInputs()
    {
        $svc = new ClarifaiImageRecognition;
        $input = [
            "https://samples.clarifai.com/metro-north.jpg", 
            "https://samples.clarifai.com/wedding.jpg"
        ];
        
        $response = $svc->send_request($input);
         $this->assertEquals(true, $response->isSuccessful());
    }
}