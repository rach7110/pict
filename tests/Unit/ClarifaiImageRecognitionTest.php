<?php
namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\ImageRecognition\ClarifaiImageRecognition;

class ClarifaiImageRecognitionTest extends TestCase
{
    protected $file;
    protected $files;
    protected $response1;  // single file input.
    protected $response2;  // multiple file inputs.
    protected $svc;

    public function setup()
    {
        parent::setup();

        // Sample files submitted by a user.
        $this->file = ["https://samples.clarifai.com/metro-north.jpg"];
        $this->files = [
           "https://samples.clarifai.com/metro-north.jpg", 
           "https://samples.clarifai.com/wedding.jpg"
        ];

        // Send request to the external API.
        $this->svc = new ClarifaiImageRecognition;
        $this->response1 = $this->svc->send_request($this->file);  
        $this->response2 = $this->svc->send_request($this->files); 
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
     * Test it can detemine the content types of a file.
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

    /**
     * Test it checks if the user-supplied word exists in file contents. 
     *
     * @group clarifai
     */
    public function test_which_files_contain_content()
    {
        $files = ['https://placekitten.com/200/139'];

        $word1 = 'cat';
        $word2 = 'beach';

        $this->assertCount(1, $this->svc->files_containing_content($word1, $files));
        $this->assertCount(0, $this->svc->files_containing_content($word2, $files));
    }
}