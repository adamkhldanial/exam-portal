<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\OptionController
 */
final class OptionControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function store_creates_option(): void
    {
        $question = Question::factory()->create();
        $response = $this->postJson(route('options.store'), [
            'question_id' => $question->id,
            'option_text' => 'Option A',
            'is_correct' => true,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('options', [
            'question_id' => $question->id,
            'option_text' => 'Option A',
            'is_correct' => true,
        ]);
    }

    #[Test]
    public function update_updates_option(): void
    {
        $option = Option::factory()->create();

        $response = $this->putJson(route('options.update', $option), [
            'option_text' => 'Updated Option',
            'is_correct' => false,
        ]);

        $option->refresh();

        $response->assertOk();
        $this->assertSame('Updated Option', $option->option_text);
        $this->assertFalse($option->is_correct);
    }

    #[Test]
    public function destroy_deletes_option(): void
    {
        $option = Option::factory()->create();

        $response = $this->deleteJson(route('options.destroy', $option));

        $response->assertNoContent();
        $this->assertDatabaseMissing('options', ['id' => $option->id]);
    }
}
