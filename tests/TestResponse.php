<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestResponse as BaseTestResponse;

class TestResponse extends BaseTestResponse
{
    public function assertJsonHasFragmentError(string $field, string $message) : self
    {
        $this->assertJsonValidationErrors([$field])
            ->assertJsonFragment([$message]);

        return $this;
    }
}
