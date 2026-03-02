<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class StoreImageTest extends TestCase
{
    use RefreshDatabase;

    const string IMAGE_CREATE_URL = '/dashboard/images/create';

    const string IMAGE_STORE_URL = '/dashboard/images';

    const string PATH_DASHBOARD = '/dashboard';

    const string PATH_LOGIN = '/login';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[TestDox('Logged user can view image creation page')]
    public function test_user_can_view_create_image()
    {
        $response = $this->actingAs($this->user)->get(self::IMAGE_CREATE_URL);
        $response->assertStatus(200);
        $response->assertSee('Create Image');
        $response->assertViewIs('images.create');
    }

    #[TestDox('Guest cannot view image creation page')]
    public function test_guest_cannot_view_create_image()
    {
        $response = $this->get(self::IMAGE_CREATE_URL);
        $response->assertStatus(302);
        $response->assertRedirect(self::PATH_LOGIN);
    }

    #[TestDox('Sucessful image creation')]
    public function test_store_image_success()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->image('my_image.png')->size(500);

        $payload = [
            'title' => 'My Image',
            'image' => $file,
        ];
        $response = $this->actingAs($this->user)->post(self::IMAGE_STORE_URL, $payload);

        $response->assertRedirect(self::PATH_DASHBOARD);

        $storedPath = 'uploads/'.$file->hashName();
        Storage::disk('s3')->assertExists($storedPath);

        $this->assertDatabaseHas('images', [
            'title' => 'My Image',
            'path' => $storedPath,
            'size' => 500 * 1024,
            'user_id' => $this->user->id,
        ]);
    }

    #[TestDox('Image creation failure with missing title')]
    public function test_store_image_fail_missing_title()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->image('my_image.png')->size(500);

        $payload = [
            'title' => '    ',
            'image' => $file,
        ];
        $response = $this->actingAs($this->user)->post(self::IMAGE_STORE_URL, $payload);
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.',
        ]);
    }

    #[TestDox('Image creation failure with duplicated title')]
    public function test_store_image_fail_invalid_title()
    {
        Image::factory()->create([
            'title' => 'Title already used',
            'user_id' => $this->user->id,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('my_image.png')->size(500);

        $payload = [
            'title' => 'Title already used',
            'image' => $file,
        ];
        $response = $this->actingAs($this->user)->post(self::IMAGE_STORE_URL, $payload);
        $response->assertSessionHasErrors([
            'title' => 'You already have an image with this title.',
        ]);
    }

    #[TestDox('Image creation failure with incorrect file type')]
    public function test_store_image_fail_invalid_file_type()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->image('my_image.jpg')->size(500);

        $payload = [
            'title' => 'My Image',
            'image' => $file,
        ];
        $response = $this->actingAs($this->user)->post(self::IMAGE_STORE_URL, $payload);
        $response->assertSessionHasErrors([
            'image' => 'The uploaded image is not a PNG.',
        ]);
    }

    #[TestDox('Image creation failure with invalid files size')]
    public function test_store_image_fail_invalid_file_size()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->image('my_image.png')->size(9001);

        $payload = [
            'title' => 'My Image',
            'image' => $file,
        ];
        $response = $this->actingAs($this->user)->post(self::IMAGE_STORE_URL, $payload);
        $response->assertSessionHasErrors([
            'image' => 'File size limit: 2MB.',
        ]);
    }
}
