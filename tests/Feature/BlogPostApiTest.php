<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BlogPost;
use App\Models\User;

use Database\Factories\UserFactory;
use Database\Factories\BlogPostFactory;



class BlogPostApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    use RefreshDatabase;

    private $apiBase = '/api/blog-posts';

    public function testGuestCannotCreateBlogPost()
    {
        $response = $this->json('POST', $this->apiBase, [
            'title' => 'New Post',
            'content' => 'Lorem ipsum',
            'user_id' => 1, 
        ]);

        $response->assertUnauthorized();
    }
    public function testAuthenticatedUserCanCreateBlogPost()
    {
       
        $user = UserFactory::new()->create();
        $this->actingAs($user, 'api'); 
        $token = $user->createToken('token')->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('POST', $this->apiBase, [
            'title' => 'New Post',
            'content' => 'Lorem ipsum',
            'user_id'=>$user->id,
        ]);

        $response->assertCreated();
    }
    public function testAuthenticatedUserCanUpdateBlogPost()
    {
        $user = UserFactory::new()->create();
        $blogPost = BlogPostFactory::new()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->json('PUT', "{$this->apiBase}/{$blogPost->id}", [
            'title' => 'Updated Post',
            'content' => 'Updated Lorem ipsum',
            'user_id'=>$user->id,
        ]);

        $response->assertOk();
    }
    
}
