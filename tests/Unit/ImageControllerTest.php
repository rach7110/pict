<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageControllerTest extends TestCase
{
    /**
     * tet the validator works on a image upload.
     *
     * @return void
     */
    public function testImageValidator()
    {
        // Fail validator
        $image = UploadedFile::fake()->image('test.ext'); //Erroneous extension will fail validator.
        $response = $this->json('POST', '/image', ['image' => $image]);
        $response->assertSee('errors');

        //Pass validator
        $image = UploadedFile::fake()->image('test.jpg');
        $response = $this->json('POST', '/image', ['image' => $image]);
        $response->assertDontSee('errors');
    }
}
