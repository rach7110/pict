<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImageRecognitionInterface;

class ImageRecognitionController extends Controller
{
    protected $imageRecognition;
    
    public function __construct(ImageRecognitionInterface $imageRecognitionService)
    {
        $this->imageRecognition = $imageRecognitionService;
    }
    
    public function create()
    {
        return view('images.create');
    }
    
    /**
     * Store the image and send request to ImageReconition service.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //TODO: Validate the file contents.

        if (request()->file('image')->store('images')) {
            $request->session()->flash('message', 'File saved successfully');
            $request->session()->flash('alert-class', 'alert-success');
        } else {
            $request->session()->flash('message', 'Problem uploading your file. Please try again in a few minutes.');
            $request->session()->flash('alert-class', 'alert-danger');
        }

        return redirect('image');
            

        // var_dump(config('filesystems.disks.local.root'));


        return back();
        
        // Image analysis.
        $response = $this->imageRecognition->send_request($file);
         if($response->isSuccessful()) {
            $this->imageRecognition->display_output($response);
        } else {
            $request->session()->flash('message', 'Response not successful. Error Code: ' . $response->status()->statusCode());
            $request->session()->flash('class', 'alert-danger');
            return back();
        }
    }
} 
