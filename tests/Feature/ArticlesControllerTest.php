<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreateArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->followingRedirects();

        $response = $this->post(route('articles.store'), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => 'Example title',
            'content' => 'Example content'
        ]);
    }

    public function testDeleteArticle()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $this->followingRedirects();

        $response = $this->delete(route('articles.destroy', $article));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);
    }

    public function testUpdateArticle()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->followingRedirects();

        $response = $this->put(route('articles.update', $article), [
            'title' => 'Edited title'
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'title' => 'Edited title'
        ]);
    }

    public function testEditArticle()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('articles.edit', $article));
        $response->assertSee("Currently editing {$article->title}");
    }

    public function testShowArticle()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('articles.show', $article));
        $response->assertSee($article->content);
    }

    public function testArticlesIndex()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('articles.index'));
        $response->assertSee('Create new article');
    }

    public function testCreateArticlePage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('articles.create'));
        $response->assertSee('Your article title');
        $response->assertSee('Create');
    }
}
