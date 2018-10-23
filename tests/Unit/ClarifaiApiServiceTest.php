<?php
 use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ImageRecognition\ClarifaiApiService;
 class ExampleTest extends TestCase
{
    /**
     * Test the request is sent succesfully.
     *
     * @group clarifai
     */
    public function testSendRequestToClarifaiSingleInput()
    {
        $svc = new ClarifaiApiService;
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
        $svc = new ClarifaiApiService;
        $input = [
            "https://samples.clarifai.com/metro-north.jpg", 
            "https://samples.clarifai.com/wedding.jpg"
        ];
        
        $response = $svc->send_request($input);
         $this->assertEquals(true, $response->isSuccessful());
    }
}