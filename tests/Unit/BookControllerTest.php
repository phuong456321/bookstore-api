<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;


class BookControllerTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;

    protected function setUp(): void{
        parent::setUp();
        $this->markTestSkipped('Tempory Stop This Test Case...');
    }
    public function test_index_returns_all_books(): void
    {
        // $this->markTestSkipped('Tempory Stop This Test Case...');
        Book::factory()->count(3)->create();

        $response = $this->get('api/books');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_show_returns_single_book(): void
    {
        $book = Book::factory()->create();
        $response = $this->get('api/books/' . $book->id);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'price' => $book->price,
        ]);
    }

    public function test_store_creates_new_book(): void
    {
        $bookData = [
            'title' => 'Test Title',
            'author' => 'Test Author',
            'price' => 10.99,
            'description' => 'Test Description'
        ];

        $response = $this->post('api/books', $bookData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', $bookData);
    }

    public function test_update_edits_existing_book(): void
    {
        $book = Book::factory()->create();
        $updateData = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'price' => 19.99,
            'description' => 'Updated Description'
        ];

        $response = $this->put('api/books/' . $book->id, $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', $updateData);
    }

    public function test_destroy_delete_book()
    {
        $book = Book::factory()->create();

        $response = $this->delete('api/books/' . $book->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_search_finds_books_by_title()
    {
        $book1 = Book::factory()->create([
            'title'=>'Laravel Book',
        ]);
        $book2 = Book::factory()->create([
            'title'=>'C Program Book',
        ]);

        $response = $this->get('api/books/search/Laravel');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            ['title' => 'Laravel Book'],
        ]);
    }

    public function test_filter_finds_books_by_author()
    {
        $book1 = Book::factory()->create([
            'author'=>'Jonathan',
        ]);
        $book2 = Book::factory()->create([
            'author'=>'Potter',
        ]);

        $response = $this->get('api/books/filter/Potter');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            ['author' => 'Potter'],
        ]);
        
    }


}
