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

        //Remote file example:
        // $response = $model->batchPredict([
        //     new ClarifaiURLImage('https://samples.clarifai.com/metro-north.jpg'),
        //     new ClarifaiURLImage('https://samples.clarifai.com/wedding.jpg'),
        // ])->executeSync(); 
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
     * @param array $file_results
     * 
     * return array 
     * 
     */
    public function files_containing_word($word, $files)
    {
        $file_with_contents = [];

        $file_outputs = $this->send_request($files)->get();        

        foreach ($file_outputs as $file_output) {
            $image = $file_output->input();
            dd($image->url());

            $image_id = $file_output->input()->id();
            $contents = $this->get_contents($file_output);

            if(in_array($word, $contents)) {
                $file_with_contents[] = $image_id;
            }
        }

        return $file_with_contents;
    }

    public function get_results($response)
    {


        // foreach ($outputs as $output) {
        //     /** @var ClarifaiURLImage $image */
        //     $image = $output->input();
        //     echo "Predicted concepts for image at url " . $image->url() . "\n";
            
        //     /** @var Concept $concept */
        //     foreach ($output->data() as $concept) {
        //         echo $concept->name() . ': ' . $concept->value() . "\n";
        //     }
        //     echo "\n";
        // }
    }
}
