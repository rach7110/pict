<?php
namespace App\Services\ImageRecognition;
 use Clarifai\API\ClarifaiClient;
use Clarifai\DTOs\Inputs\ClarifaiURLImage;
use Clarifai\DTOs\Inputs\ClarifaiFileImage;
use Clarifai\DTOs\Outputs\ClarifaiOutput;
use Clarifai\DTOs\Predictions\Concept;
use Clarifai\DTOs\Searches\SearchBy;
use Clarifai\DTOs\Searches\SearchInputsResult;
use Clarifai\DTOs\Models\ModelType;
use App\ImageRecognitionInterface;

 class ClarifaiImageRecognition implements ImageRecognitionInterface
{
    private $client;

    public function __construct()
    {
        $this->client = new ClarifaiClient(config('clarifai.secret'));
    }
 
    /** 
     * Send request to API 
     * 
     * @param array $inputs
     * @return ClarifaiOutput[] 
     */
    public function send_request($inputs)
    {
        $files =[];

        foreach($inputs as $input) {
            $files[] = new ClarifaiFileImage(file_get_contents($input));
        }
 
        $model = $this->client->publicModels()->generalModel();
        $response = $model->batchPredict($files)->executeSync();

        return $response;
    }

    public function outputs($response)
    {
        /** @var ClarifaiOutput[] $outputs */
        $outputs = $response->get();

        return $outputs;
    }

    /** 
     * Get the contents of multiple files.
     * 
     * @param ClarifaiOutput $file_outputs
     * @return array
     */
    public function get_contents_of_files($file_outputs)
    {
        $contents_of_files = [];

        foreach ($file_outputs as $file_output) {
            $content = $this->get_contents($file_output);
            $contents_of_files[] = $content;
        }

        return $contents_of_files;
    }

    /** 
     * Get the contents of a single file.
     * 
     * @param ClarifaiOutput $file_output
     * @return array
     */
    public function get_contents($file_output)
    {
        $contents = [];

        foreach ($file_output->data() as $concept) {
            $contents[] = $concept->name(); 
        }

        return $contents;
    }

    /** 
     * Check if content exists in files.
     * 
     * @param string|array $word|$files
     * 
     * return array 
     */
    public function files_containing_content($word, $files)
    {
        $files_with_content = [];
        $response = $this->send_request($files);
        $file_outputs = $this->outputs($response);        

        foreach ($file_outputs as $file_output) {
            $image = $file_output->input();

            $image_id = $file_output->input()->id();
            $contents = $this->get_contents($file_output);

            if(in_array($word, $contents)) {
                $files_with_content[] = $image_id;
            }
        }

        return $files_with_content;
    }
}
