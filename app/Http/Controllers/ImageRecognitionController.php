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
    public function store(Request $request)
    {
        $content_file = request()->file('content');
        $user_file = request()->file('image');
        // dd($file->path());
        // dd($file->extension());

        // Validate the file contents.
        $rules = [
            'image' => 'image|max:2000|mimes:,png,jpeg,jpg,gif,svg',
            'content' => 'image|max:2000|mimes:,png,jpeg,jpg,gif,svg'
        ];
        
        $request->validate($rules);

        // $file = $user_file->store('images');

        if ($user_file) {
            $this->imageRecognitionSvc->analyzeImage($user_file);
        // return redirect('image');

        } else {
            $request->session()->flash('message', 'Problem uploading your file. Please try again in a few minutes.');
            $request->session()->flash('alert-class', 'alert-danger');
        }

        // var_dump(config('filesystems.disks.local.root'));
    }
} 
