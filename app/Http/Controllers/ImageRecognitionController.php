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
        $user_file = request()->file('image');
        // dd($file->path());
        // dd($file->extension());
        // Validate the file contents.
        $rules = [
            'image' => 'required|image|max:2000|mimes:,png,jpeg,jpg,gif,svg'
        ];
        
        $request->validate($rules);

        $file = $user_file->store('images');

        // Store the file contents.
        if ($file) {
            //TODO: store relative path to DB.
            $upload = new Upload;
            $upload->directory = $file;

            $request->session()->flash('message', 'File saved successfully');
            $request->session()->flash('alert-class', 'alert-success');
            dd(Storage::get($file)->path());

        } else {
            $request->session()->flash('message', 'Problem uploading your file. Please try again in a few minutes.');
            $request->session()->flash('alert-class', 'alert-danger');
        }



        // $this->analyzeImage($file);
        // return redirect('image');
        // var_dump(config('filesystems.disks.local.root'));
    }

    public function analyzeImage($image)
    {
        $response = $this->imageRecognitionSvc->send_request($image);

         if($response->isSuccessful()) {
            return $this->imageRecognitionSvc->display_output($response);
        } else {
            return 'Response not successful. Error Code: ' . $response->status()->statusCode();
        }
    }
} 
