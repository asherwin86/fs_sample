<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->mock_values =  [
            'title' => 'Title',
            'author' => 'Author',
            'blurb' => 'Blurb',
            'status' => 'finished',
        ];
    }

    /** @test */
    public function user_must_be_logged_in_to_list_books()
    {
        $response = $this->get(route('books.index'));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function logged_in_user_can_list_books()
    {
        $response = $this->actingAs($this->user)->get(route('books.index'));

        $response->assertViewIs('books.index');
    }

    /** @test */
    public function logged_in_user_can_create_book()
    {

        $response = $this->actingAs($this->user)->get(route('books.create'));

        $response->assertViewIs('books.create');

        $response = $this->actingAs($this->user)->post(route('books.store'), $this->mock_values);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', $this->mock_values);
    }

    /** @test */
    public function logged_in_user_can_edit()
    {
        $edit_values = [
            'title' => 'Title new',
            'author' => 'Author new',
            'blurb' => 'Blurb new',
            'status' => 'retired',
        ];

        //Create our book.
        $response = $this->actingAs($this->user)->post(route('books.store'), $this->mock_values);

        $response->assertStatus(302);
        $response->assertRedirect(route('books.index'));

        $response = $this->actingAs($this->user)->patch(route('books.update', 1), $edit_values);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', $edit_values);
    }

    /** @test */
    public function logged_in_user_can_destroy()
    {
        //Create our book.
        $response = $this->actingAs($this->user)->post(route('books.store'), $this->mock_values);

        $response->assertStatus(302)
            ->assertRedirect(route('books.index'));

        $response = $this->actingAs($this->user)->delete(route('books.destroy', 1));
        
        $response->assertStatus(302)
            ->assertRedirect(route('books.index'));

        $this->assertDeleted('books', $this->mock_values);
    }

    /** @test */
    public function logged_in_user_can_view_through_api()
    {
        //Create our book.
        $response = $this->actingAs($this->user)->post(route('books.store'), $this->mock_values);

        $response->assertStatus(302);
        $response->assertRedirect(route('books.index'));

        $response = $this->actingAs($this->user, 'api')->getJson(route('books.view', 1));

        $response->assertStatus(200);
        $response->assertSee($this->mock_values['title']);
    }

    /** @test */
    public function logged_in_user_can_filter_through_api()
    {
        //Create our book.
        $response = $this->actingAs($this->user)->post(route('books.store'), $this->mock_values);

        $response->assertStatus(302);
        $response->assertRedirect(route('books.index'));

        $response = $this->actingAs($this->user, 'api')->getJson(route('books.filter', 'finished'));

        $response->assertStatus(200);
        $response->assertSee($this->mock_values['title']);

        $response = $this->actingAs($this->user, 'api')->getJson(route('books.filter', 'invalid'));

        $response->assertStatus(200);
        $response->assertSee('[]');
    }
}
