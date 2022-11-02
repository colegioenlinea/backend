<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(4)->create();
        $response = $this->getJson(route('books.index'));
        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();
        $this->getJson(route('books.show', $book))->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    public function can_create_books()
    {
        $this->postJson(route('books.store'), [])->assertJsonValidationErrorFor('title');
        $this->postJson(route('books.store', [
            'title' => 'Libro creado por test'
        ]))->assertJsonFragment([
            'title' => 'Libro creado por test'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Libro creado por test'
        ]);
    }

    /** @test */
    public function can_update_book()
    {
        $book = Book::factory()->create();
        $this->patchJson(route('books.update', $book), [])->assertJsonValidationErrorFor('title');
        $this->patchJson(route('books.update', $book), [
            'title' => 'Libro actualizado'
        ])->assertJsonFragment([
            'title' => 'Libro actualizado'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Libro actualizado'
        ]);
    }

    /** @test */
    public function can_delete_book()
    {
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy', $book))->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
