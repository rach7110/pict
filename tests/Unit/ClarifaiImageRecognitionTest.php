<?php
namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\ImageRecognition\ClarifaiImageRecognition;

class ClarifaiImageRecognitionTest extends TestCase
{
    protected $response1;
    protected $response2;
    protected $svc;

    public function setup()
    {
        parent::setup();

        // File inputs from a user.
        $input = ["https://samples.clarifai.com/metro-north.jpg"];
        $inputs = [
           "https://samples.clarifai.com/metro-north.jpg", 
           "https://samples.clarifai.com/wedding.jpg"
        ];

        // Send request to the external API.
        $this->svc = new ClarifaiImageRecognition;
        $this->response1 = $this->svc->send_request($input);  // single file input.
        $this->response2 = $this->svc->send_request($inputs);  // multiple file inputs.
    }

    /**
     * Test the API responseds succesfully.
     *
     * @group clarifai
     */
    public function testAPIRespondsSuccessfully()
    {
        $this->assertEquals(true, $this->response1->isSuccessful());
        $this->assertEquals(true, $this->response2->isSuccessful());
    }

    /**
     * Test it transforms response to an array of json objects with: 
     *   image id,
     *   data containing an array of: concept names and percentages.
     *
     * @group clarifai
     */
    public function testTransformsResponse()
    {
        $response = $this->response2;
        $output = $this->svc->transform($response);

        $content1 = json_decode($output[0]);
        $this->assertEquals(true, array_key_exists('data', $content1));
    }
}