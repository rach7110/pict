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
 
    /** Send request to API 
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

    /** Format the output of the API response.
     * 
     * @param Object $response
     * @return array
     */
    public function transform($response)
    {
        $outputs = $response->get();
        $results = [];

         foreach ($outputs as $output) {
            /** @var ClarifaiURLImage $image */
            $image_id = $output->input()->id();
            $data =[];
            /** @var Concept $concept */
            foreach ($output->data() as $concept) {
                $data[] = ['name' => $concept->name(), 'value' => $concept->value()];
            }
            $results[] = ['id'=> $image_id, 'data' => $data];

        }

        return $results;
    }
}
