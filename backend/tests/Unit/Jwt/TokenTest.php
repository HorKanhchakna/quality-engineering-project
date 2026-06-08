<?php

namespace Tests\Unit\Jwt;

use App\Jwt\Token;
use Tests\TestCase;

/**
 * Unit tests for App\Jwt\Token
 *
 * Tests verify: default headers, claim storage/retrieval,
 * expiration, subject, and signature handling.
 */
class TokenTest extends TestCase
{
    // ── Happy Path Tests ─────────────────────────────────────

    /** @test */
    public function it_creates_token_with_default_headers(): void
    {
        $token = new Token();
        $headers = $token->headers();

        // Default headers from config('jwt.headers') should be present
        $this->assertNotEmpty($headers->toArray(), 'Default headers should not be empty');
    }

    /** @test */
    public function it_stores_and_retrieves_payload_claim(): void
    {
        $token = new Token();
        $token->putToPayload('custom_key', 'custom_value');

        $this->assertEquals('custom_value', $token->claims()->get('custom_key'));
    }

    /** @test */
    public function it_stores_and_retrieves_expiration_timestamp(): void
    {
        $token = new Token();
        $expiry = time() + 3600; // 1 hour from now
        $token->putToPayload('exp', $expiry);

        $this->assertEquals($expiry, $token->getExpiration());
    }

    /** @test */
    public function it_stores_and_retrieves_subject(): void
    {
        $token = new Token();
        $token->putToPayload('sub', 42);

        $this->assertEquals(42, $token->getSubject());
    }

    /** @test */
    public function it_stores_and_retrieves_user_signature(): void
    {
        $token = new Token();
        $signature = 'abc123signature';
        $token->setUserSignature($signature);

        $this->assertEquals($signature, $token->getUserSignature());
    }

    /** @test */
    public function it_stores_custom_header_values(): void
    {
        $token = new Token();
        $token->putToHeader('alg', 'HS256');

        $this->assertEquals('HS256', $token->headers()->get('alg'));
    }

    /** @test */
    public function it_initializes_with_provided_headers_and_claims(): void
    {
        $token = new Token(
            ['typ' => 'JWT', 'alg' => 'HS256'],
            ['sub' => 99, 'exp' => 9999999999]
        );

        $this->assertEquals('JWT', $token->headers()->get('typ'));
        $this->assertEquals(99, $token->getSubject());
        $this->assertEquals(9999999999, $token->getExpiration());
    }

    // ── Sad Path Tests ────────────────────────────────────────

    /** @test */
    public function it_returns_null_signature_when_not_set(): void
    {
        $token = new Token();

        $this->assertNull($token->getUserSignature());
    }

    /** @test */
    public function it_returns_zero_expiration_when_not_set(): void
    {
        $token = new Token();

        // No exp set — getExpiration casts null to int = 0
        $this->assertEquals(0, $token->getExpiration());
    }

    /** @test */
    public function it_returns_null_subject_when_not_set(): void
    {
        $token = new Token();

        $this->assertNull($token->getSubject());
    }

    /** @test */
    public function headers_returns_a_clone_not_the_original(): void
    {
        $token = new Token(['typ' => 'JWT']);

        $headers = $token->headers();
        $headers->put('extra', 'injected'); // mutate the clone

        // Original should be unchanged
        $this->assertFalse($token->headers()->has('extra'));
    }
}