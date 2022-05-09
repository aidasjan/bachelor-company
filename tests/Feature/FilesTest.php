<?php

namespace Tests\Feature;

use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FilesTest extends TestCase
{
    use RefreshDatabase;

    public function test_downloads_document()
    {
        UploadedFile::fake()->create('file.pdf')->storeAs('uploads', 'file.pdf', 'public');
        $file = File::factory()->create(['id' => 1, 'file_name' => 'file', 'file_extension' => 'pdf']);

        $response = $this->get('/files/documents/1');

        $response->assertStatus(200);
        $response->assertDownload($file->name . '.pdf');
    }

    public function test_shows_image()
    {
        UploadedFile::fake()->image('image.png')->storeAs('uploads', 'image.png', 'public');
        File::factory()->create(['id' => 1, 'file_name' => 'image', 'file_extension' => 'png', 'file_mime_type' => 'image/png']);

        $response = $this->get('/files/images/1');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }
}
