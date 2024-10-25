<?php

namespace Tests\Feature;

use App\Mail\BlogPosted;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BlogsTest extends TestCase
{

    use RefreshDatabase;

    private $user;


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        Mail::fake();
    }

    public function test_home_page_is_loading_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_blogs_are_empty(): void
    {

        $response = $this->actingAs($this->user)->get('/blogs');
        $response->assertStatus(200);
        $response->assertViewHas('noBlogs');
    }

    public function test_index_displays_filtered_results_when_search_is_present(): void
    {

        for ($i = 1; $i <= 3; $i++) {
            Blog::factory()->create([
                'title' => "Title {$i}st"
            ]);
        }

        $response = $this->actingAs($this->user)->get('/blogs?search=1st');
        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('blogs'));
        $this->assertEquals('Title 1st', $response->viewData('blogs')->first()->title);
    }

    public function test_blogs_are_not_empty(): void
    {

        Blog::factory()->create();
        $response = $this->actingAs($this->user)->get('/blogs');
        $response->assertStatus(200);
        $response->assertViewHas('blogs');
    }

    public function test_create_blog_successfully(): void
    {
        $image = UploadedFile::fake()->image('test-image.jpg');

        $blog = [
            'title' => 'Title 1',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => $image,
        ];

        $response = $this->actingAs($this->user)->post('/blogs', $blog);
        $response->assertStatus(302);


        $response->assertRedirect('blogs/' . Blog::latest()->first()->id);

        $this->assertDatabaseHas('blogs', [
            'title' => 'Title 1',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'user_id' => $this->user->id
        ]);

        $lastBlog = Blog::latest()->first();
        $this->assertEquals($blog['title'], $lastBlog['title']);
        $this->assertEquals($blog['content'], $lastBlog['content']);
        $this->assertEquals($this->user->id, $lastBlog['user_id']);

        Mail::assertQueued(BlogPosted::class);

        $this->assertNull(Cache::get('blogs'));
    }

    public function test_blog_author_can_see_edit_button(): void
    {
        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get("/blogs/{$blog->id}");
        $response->assertStatus(200);
        $response->assertSee('Edit');
    }

    public function test_blog_non_author_cannot_see_edit_button(): void
    {
        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $non_author = User::factory()->create();

        $response = $this->actingAs($non_author)->get("/blogs/{$blog->id}");
        $response->assertStatus(200);
        $response->assertDontSee('Edit');
    }

    public function test_blog_non_author_cannot_update_others_blog(): void
    {

        $non_author = User::factory()->create();

        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $newBlogData = [
            'title' => 'Updated Title',
            'content' => 'Updated content for the blog.',
            'image' => UploadedFile::fake()->image('new_image.jpg'),
        ];

        $response = $this->actingAs($non_author)->patch("/blogs/{$blog->id}", $newBlogData);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Title',
            'content' => 'Updated content for the blog.',
        ]);
    }

    public function test_blog_author_can_update_own_blog(): void
    {

        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $newBlogData = [
            'title' => 'Updated Title',
            'content' => 'Updated content for the blog.',
            'image' => UploadedFile::fake()->image('new_image.jpg'),
        ];

        $response = $this->actingAs($this->user)->patch("/blogs/{$blog->id}", $newBlogData);

        $response->assertStatus(302);
        $response->assertRedirect("/blogs/{$blog->id}");

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Title',
            'content' => 'Updated content for the blog.',
        ]);

        $this->assertNull(Cache::get('blogs'));
    }

    public function test_blog_author_can_see_delete_button(): void
    {
        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get("/blogs/{$blog->id}");
        $response->assertStatus(200);
        $response->assertSee('Delete');
    }

    public function test_blog_non_author_cannot_see_delete_button(): void
    {
        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $non_author = User::factory()->create();

        $response = $this->actingAs($non_author)->get("/blogs/{$blog->id}");
        $response->assertStatus(200);
        $response->assertDontSee('Delete');
    }

    public function test_blog_author_can_delete_own_blog(): void
    {

        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->delete("/blogs/{$blog->id}");

        $response->assertStatus(302);
        $response->assertRedirect('/user/blogs');
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);

        $this->assertNull(Cache::get('blogs'));
    }

    public function test_blog_non_author_cannot_delete_others_blog(): void
    {

        $blog = Blog::factory()->create([
            'user_id' => $this->user->id
        ]);

        $non_author = User::factory()->create();

        $response = $this->actingAs($non_author)->delete("/blogs/{$blog->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('blogs', ['id' => $blog->id]);
    }


    protected function createUser()
    {
        return User::factory()->create();
    }

    protected function createBlog()
    {
        return Blog::factory()->create();
    }
}
