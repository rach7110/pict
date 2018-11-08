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
     * @param array $input
     * @return Object
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

        //Remote file example:
        // $response = $model->batchPredict([
        //     new ClarifaiURLImage('https://samples.clarifai.com/metro-north.jpg'),
        //     new ClarifaiURLImage('https://samples.clarifai.com/wedding.jpg'),
        // ])->executeSync(); 
    }

    /** 
     * Format the output of the API response.
     * 
     * @param ClarifaiObject $response
     * @return array
     */
    public function transform($response)
    {
        $file_outputs = $response->get();  // Returns an array of ClarifaiOtput objects.
        $results = [];

         foreach ($file_outputs as $file_output) {
            $image_id = $file_output->input()->id();
            $data =[];
            
            foreach ($file_output->data() as $concept) {
                $data[] = json_decode(json_encode(['name' => $concept->name(), 'value' => $concept->value()]));
            }
            $results[] = ['id'=> $image_id, 'data' => $data];
        }

        return $results;
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
}
