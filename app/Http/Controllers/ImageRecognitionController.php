<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImageRecognitionInterface;
use Illuminate\Support\Facades\Storage;

class ImageRecognitionController extends Controller
{
    protected $imageRecognitionSvc;
    
    public function __construct(ImageRecognitionInterface $imageRecognitionService)
    {
        $this->imageRecognitionSvc = $imageRecognitionService;
    }
    
    public function createFullAnalysis()
    {
        return view('recognition.full.create');
    }

    public function createConceptAnalysis()
    {
        return view('recognition.concept.create');
    }
    
    /**
     * Send request to ImageReconition service.
     *
     * @param  Request  $request
     * @return Response
     */
    public function analyze(Request $request)
    {
        // $content_file = request()->file('content');
        $user_files = request()->file('image');

        // Validate the file contents.
        $rules = [
            'image.0' => 'required|image|max:2000|mimes:,png,jpeg,jpg,gif,svg',
            'image.*' => 'image|max:2000|mimes:,png,jpeg,jpg,gif,svg',
            'content' => 'image|max:2000|mimes:,png,jpeg,jpg,gif,svg'
        ];
        
        $request->validate($rules);

        $response = $this->imageRecognitionSvc->send_request($user_files);

        if($response->isSuccessful()) {
            $results = $this->imageRecognitionSvc->outputs($response);
            
            return view('recognition.full.show')->with(['results' => $results]);
        } else {
            $request->session()->flash('message', 'Problem analyzing your file. 
                                        Please try again in a few minutes.  
                                        Status code: ' . $response->status()->statusCode()
                                        );
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->route('recognition.full');
        }
    }
} 
