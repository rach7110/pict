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
        return view('images.concepts.create');
    }
     public function store()
    {
        $file = request()->file('image')->store('images');
        // var_dump(config('filesystems.disks.local.root') . "/" . $file);
        
        //TODO: Validate the file contents.
    
        $response = $this->imageRecognition->send_request($input);
         if($response->isSuccessful()) {
            $this->imageRecognition->display_output($response);
        } else {
            Session::flash('message', 'Response not successful. Error Code: ' . $response->status()->statusCode());
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
    }
} 
