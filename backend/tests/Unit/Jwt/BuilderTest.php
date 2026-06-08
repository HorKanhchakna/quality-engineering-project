<?php

namespace Tests\Unit\Jwt;

use App\Jwt\Builder;
use App\Jwt\Token;
use App\Contracts\JwtSubjectInterface;
use Tests\TestCase;

/**
 * Unit tests for App\Jwt\Builder
 *
 * Tests verify: fluent chaining, default headers, claim writing,
 * subject (object and scalar), timestamps, and custom claims.
 */
class BuilderTest extends TestCase
{
    // ── Happy Path Tests ─────────────────────────────────────

    /** @test */
    public function build_returns_a_builder_instance(): void
    {
        $builder = Builder::build();

        $this->assertInstanceOf(Builder::class, $builder);
    }

    /** @test */
    public function get_token_returns_a_token_instance(): void
    {
        $token = Builder::build()->getToken();

        $this->assertInstanceOf(Token::class, $token);
    }

    /** @test */
    public function new_token_has_default_jwt_headers(): void
    {
        $token = Builder::build()->getToken();

        // Default headers from config('jwt.headers') must be present
        $this->assertNotEmpty($token->headers()->toArray());
    }

    /** @test */
    public function issued_at_stores_iat_claim(): void
    {
        $now = time();
        $token = Builder::build()->issuedAt($now)->getToken();

        $this->assertEquals($now, $token->claims()->get('iat'));
    }

    /** @test */
    public function expires_at_stores_exp_claim(): void
    {
        $expiry = time() + 3600;
        $token = Builder::build()->expiresAt($expiry)->getToken();

        $this->assertEquals($expiry, $token->getExpiration());
    }

    /** @test */
    public function subject_stores_scalar_identifier(): void
    {
        $token = Builder::build()->subject(77)->getToken();

        $this->assertEquals(77, $token->getSubject());
    }

    /** @test */
    public function subject_accepts_jwt_subject_interface_object(): void
    {
        // Create a fake user that implements JwtSubjectInterface
        $user = new class implements JwtSubjectInterface {
            public function getJwtIdentifier(): mixed { return 123; }
        };

        $token = Builder::build()->subject($user)->getToken();

        $this->assertEquals(123, $token->getSubject());
    }

    /** @test */
    public function with_claim_stores_custom_payload_value(): void
    {
        $token = Builder::build()->withClaim('role', 'admin')->getToken();

        $this->assertEquals('admin', $token->claims()->get('role'));
    }

    /** @test */
    public function with_header_stores_custom_header_value(): void
    {
        $token = Builder::build()->withHeader('kid', 'key-id-1')->getToken();

        $this->assertEquals('key-id-1', $token->headers()->get('kid'));
    }

    /** @test */
    public function fluent_chaining_works_correctly(): void
    {
        $now    = time();
        $expiry = $now + 7200;

        $token = Builder::build()
            ->issuedAt($now)
            ->expiresAt($expiry)
            ->subject(55)
            ->withClaim('custom', 'value')
            ->getToken();

        $this->assertEquals($now,     $token->claims()->get('iat'));
        $this->assertEquals($expiry,  $token->getExpiration());
        $this->assertEquals(55,       $token->getSubject());
        $this->assertEquals('value',  $token->claims()->get('custom'));
    }

    // ── Sad Path Tests ────────────────────────────────────────

    /** @test */
    public function with_claim_accepts_null_value(): void
    {
        $token = Builder::build()->withClaim('nullable_field', null)->getToken();

        // Key should exist in claims with null value
        $this->assertTrue($token->claims()->has('nullable_field'));
        $this->assertNull($token->claims()->get('nullable_field'));
    }

    /** @test */
    public function multiple_calls_to_subject_overwrites_previous(): void
    {
        $token = Builder::build()
            ->subject(10)
            ->subject(99) // second call should overwrite
            ->getToken();

        $this->assertEquals(99, $token->getSubject());
    }
}