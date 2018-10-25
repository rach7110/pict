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
    
    public function create()
    {
        return view('recognition.create');
    }
    
    /**
     * Store the image and send request to ImageReconition service.
     *
     * @param  Request  $request
     * @return Response
     */
    public function analyze(Request $request)
    {
        $content_file = request()->file('content');
        $user_files = request()->file('image');

        // dd($file->path());
        // dd($file->extension());

        // Validate the file contents.
        $rules = [
            'image.0' => 'required|image|max:2000|mimes:,png,jpeg,jpg,gif,svg',
            'image.*' => 'image|max:2000|mimes:,png,jpeg,jpg,gif,svg',
            'content' => 'image|max:2000|mimes:,png,jpeg,jpg,gif,svg'
        ];
        
        $request->validate($rules);

        $results = $this->imageRecognitionSvc->analyzeImage($user_files);
        
        if($results) {
            $request->session()->flash('message', 'Success!');
            $request->session()->flash('alert-class', 'alert-info');
            
            return view('recognition.create')->with(['results' => $results]);
        } else {
            $request->session()->flash('message', 'Problem analyzing your file. Please try again in a few minutes. ' . $this->imageRecognitionSvc->getErrors());
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect('recognition');
        }

    }
} 
