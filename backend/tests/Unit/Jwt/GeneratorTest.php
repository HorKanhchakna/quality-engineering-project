<?php

namespace Tests\Unit\Jwt;

use App\Jwt\Generator;
use App\Jwt\Parser;
use App\Jwt\Token;
use App\Jwt\Builder;
use App\Contracts\JwtSubjectInterface;
use InvalidArgumentException;
use Tests\TestCase;

/**
 * Unit tests for App\Jwt\Generator
 *
 * Tests verify: HMAC signature generation, missing APP_KEY error,
 * JWT format compliance, token generation and re-parsing.
 */
class GeneratorTest extends TestCase
{
    // ── Helpers ───────────────────────────────────────────────

    /** Return a fake user implementing JwtSubjectInterface */
    private function fakeUser(int $id = 1): JwtSubjectInterface
    {
        return new class($id) implements JwtSubjectInterface {
            private int $id;

            public function __construct(int $id)
            {
                $this->id = $id;
            }

            public function getJwtIdentifier(): mixed
            {
                return $this->id;
            }
        };
    }

    /** Build a minimal Token for signature testing */
    private function buildToken(): Token
    {
        return Builder::build()
            ->subject(1)
            ->issuedAt(time())
            ->expiresAt(time() + 3600)
            ->getToken();
    }

    // ── Happy Path Tests ─────────────────────────────────────

    /** @test */
    public function it_generates_a_non_empty_signature(): void
    {
        config(['app.key' => 'test-secret-key-for-unit-tests']);

        $token = $this->buildToken();
        $sig   = Generator::signature($token);

        $this->assertNotEmpty($sig);
    }

    /** @test */
    public function it_generates_deterministic_signature_for_same_token(): void
    {
        config(['app.key' => 'test-secret-key-for-unit-tests']);

        $token = $this->buildToken();
        $sig1  = Generator::signature($token);
        $sig2  = Generator::signature($token);

        $this->assertEquals($sig1, $sig2, 'Same token must produce same signature');
    }

    /** @test */
    public function it_generates_a_token_string_with_two_dots(): void
    {
        config(['app.key' => 'test-secret-key-for-unit-tests']);
        config(['jwt.expiration' => 3600]);

        $tokenString = Generator::token($this->fakeUser());

        // A valid JWT has exactly 2 dots (header.payload.signature)
        $this->assertEquals(2, substr_count($tokenString, '.'));
    }

    /** @test */
    public function generated_token_can_be_parsed_back(): void
    {
        config(['app.key' => 'test-secret-key-for-unit-tests']);
        config(['jwt.expiration' => 3600]);
        config(['jwt.headers' => ['typ' => 'JWT', 'alg' => 'HS256']]);

        $tokenString = Generator::token($this->fakeUser(5));

        // Token should be parseable via Parser
        $parsed = Parser::parse($tokenString);

        $this->assertEquals(5, $parsed->getSubject());
    }

    /** @test */
    public function generated_token_contains_subject_id(): void
    {
        config(['app.key' => 'test-secret-key-for-unit-tests']);
        config(['jwt.expiration' => 3600]);
        config(['jwt.headers' => ['typ' => 'JWT', 'alg' => 'HS256']]);

        $tokenString = Generator::token($this->fakeUser(99));
        $parsed = Parser::parse($tokenString);

        $this->assertEquals(99, $parsed->getSubject());
    }

    /** @test */
    public function generated_token_contains_expiration_claim(): void
    {
        config(['app.key' => 'test-secret-key-for-unit-tests']);
        config(['jwt.expiration' => 3600]);
        config(['jwt.headers' => ['typ' => 'JWT', 'alg' => 'HS256']]);

        $tokenString = Generator::token($this->fakeUser());
        $parsed = Parser::parse($tokenString);

        $this->assertTrue($parsed->claims()->has('exp'), 'exp claim should be present');
        $this->assertGreaterThan(time(), $parsed->getExpiration(), 'exp should be in the future');
    }

    // ── Sad Path Tests ────────────────────────────────────────

    /** @test */
    public function it_throws_when_app_key_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No APP_KEY specified.');

        config(['app.key' => null]);

        $token = $this->buildToken();
        Generator::signature($token);
    }

    /** @test */
    public function different_secrets_produce_different_signatures(): void
    {
        $token = $this->buildToken();

        config(['app.key' => 'secret-one']);
        $sig1 = Generator::signature($token);

        config(['app.key' => 'secret-two']);
        $sig2 = Generator::signature($token);

        $this->assertNotEquals($sig1, $sig2, 'Different keys must produce different signatures');
    }
}