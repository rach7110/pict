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

 class ClarifaiApiService implements ImageRecognitionInterface
{
    private $client;
    protected $error;

    public function __construct()
    {
        $this->client = new ClarifaiClient(config('clarifai.secret'));
    }

    public function analyzeImage($input)
    {
        $response = $this->send_request($input);

         if($response->isSuccessful()) {
            return $this->output($response);
        } else {
            $this->error = "Status code: " . $response->status()->statusCode();

            return false;
        }
    }

    /** Send request to API that will analyze media content
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
         //Remote file
        // $response = $model->batchPredict([
        //     new ClarifaiURLImage('https://samples.clarifai.com/metro-north.jpg'),
        //     new ClarifaiURLImage('https://samples.clarifai.com/wedding.jpg'),
        // ])->executeSync();  
        $model = $this->client->publicModels()->generalModel();
        $response = $model->batchPredict($files)->executeSync();
        
        return $response;
    }

     public function output($response)
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

    public function getErrors()
    {
        return $this->error;
    }
}
