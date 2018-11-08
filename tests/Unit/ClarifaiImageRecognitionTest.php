<?php
namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\ImageRecognition\ClarifaiImageRecognition;

class ClarifaiImageRecognitionTest extends TestCase
{
    protected $response1;  // single file input.
    protected $response2;  // multiple file inputs.
    protected $svc;

    public function setup()
    {
        parent::setup();

        // Sample files submitted by a user.
        $input = ["https://samples.clarifai.com/metro-north.jpg"];
        $inputs = [
           "https://samples.clarifai.com/metro-north.jpg", 
           "https://samples.clarifai.com/wedding.jpg"
        ];

        // Send request to the external API.
        $this->svc = new ClarifaiImageRecognition;
        $this->response1 = $this->svc->send_request($input);  
        $this->response2 = $this->svc->send_request($inputs); 
    }

    /**
     * Test the API responds succesfully.
     *
     * @group clarifai
     */
    public function test_API_responds_successfully()
    {
        $this->assertEquals(true, $this->response1->isSuccessful());
        $this->assertEquals(true, $this->response2->isSuccessful());
    }

    /**
     * Test it can transform the API request to a response containing: 
     *   image id,
     *   data containing an array of: concept names and percentages.
     *
     * @group clarifai
     */
    public function test_clarifai_transforms_response()
    {
        $response = $this->response2;
        $formatted_response = $this->svc->transforms($response);
        $content = json_decode($formatted_response[0]);
        // Results have an image 'id' key.
        $this->assertEquals(true, array_key_exists('id', $content));
        // Results have a 'data' key.
        $this->assertEquals(true, array_key_exists('data', $content));
    }

    /** 
     * Test it can determine the content types of a file.
     * 
     * @group clarifai
    */
    public function test_clarifai_determines_content_of_files()
    {
        $contents = $this->svc->get_contents_of_files($this->response2->get());

        $this->assertContains("wedding", $contents[1]);
        $this->assertContains("man", $contents[1]);
        $this->assertContains("woman", $contents[1]);
    }
}