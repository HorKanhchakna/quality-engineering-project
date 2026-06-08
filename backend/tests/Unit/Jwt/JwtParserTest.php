<?php

namespace Tests\Unit\Jwt;

use App\Jwt\Parser;
use App\Jwt\Token;
use App\Exceptions\JwtParseException;
use JsonException;
use Tests\TestCase;

/**
 * Unit tests for App\Jwt\Parser
 *
 * Tests verify: valid token parsing, malformed structure detection,
 * invalid base64 handling, invalid JSON handling, and claim preservation.
 */
class JwtParserTest extends TestCase
{
    // ── Helpers ───────────────────────────────────────────────

    /**
     * Build a well-formed 3-part JWT string manually
     * (without real signature validation — just structure testing)
     */
    private function makeValidTokenString(array $headers = [], array $payload = []): string
    {
        $defaultHeaders = ['typ' => 'JWT', 'alg' => 'HS256'];
        $defaultPayload = ['sub' => 1, 'exp' => time() + 3600, 'iat' => time()];

        $h = base64_encode(json_encode(array_merge($defaultHeaders, $headers)));
        $p = base64_encode(json_encode(array_merge($defaultPayload, $payload)));
        $s = base64_encode('fake-signature');

        return "$h.$p.$s";
    }

    // ── Happy Path Tests ─────────────────────────────────────

    /** @test */
    public function it_parses_a_valid_token_string(): void
    {
        $tokenString = $this->makeValidTokenString();
        $token = Parser::parse($tokenString);

        $this->assertInstanceOf(Token::class, $token);
    }

    /** @test */
    public function it_preserves_payload_claims_after_parsing(): void
    {
        $tokenString = $this->makeValidTokenString([], ['sub' => 42]);
        $token = Parser::parse($tokenString);

        $this->assertEquals(42, $token->getSubject());
    }

    /** @test */
    public function it_preserves_header_values_after_parsing(): void
    {
        $tokenString = $this->makeValidTokenString(['alg' => 'HS256']);
        $token = Parser::parse($tokenString);

        $this->assertEquals('HS256', $token->headers()->get('alg'));
    }

    /** @test */
    public function it_preserves_expiration_timestamp_after_parsing(): void
    {
        $expiry = time() + 7200;
        $tokenString = $this->makeValidTokenString([], ['exp' => $expiry]);
        $token = Parser::parse($tokenString);

        $this->assertEquals($expiry, $token->getExpiration());
    }

    /** @test */
    public function it_stores_the_signature_part_on_the_token(): void
    {
        $tokenString = $this->makeValidTokenString();
        $token = Parser::parse($tokenString);

        // The third part (signature) should be stored
        $this->assertNotNull($token->getUserSignature());
    }

    // ── Sad Path Tests ────────────────────────────────────────

    /** @test */
    public function it_throws_on_token_with_only_two_parts(): void
    {
        $this->expectException(JwtParseException::class);

        Parser::parse('only.twoparts');
    }

    /** @test */
    public function it_throws_on_token_with_four_parts(): void
    {
        $this->expectException(JwtParseException::class);

        Parser::parse('one.two.three.four');
    }

    /** @test */
    public function it_throws_on_empty_string(): void
    {
        $this->expectException(JwtParseException::class);

        Parser::parse('');
    }

    /** @test */
    public function it_throws_on_invalid_base64_in_header(): void
    {
        $this->expectException(JwtParseException::class);

        // Invalid base64 in first segment
        Parser::parse('!!!invalid-base64!!!.' . base64_encode('{}') . '.' . base64_encode('sig'));
    }

    /** @test */
    public function it_throws_on_invalid_json_in_payload(): void
    {
        $this->expectException(JsonException::class);

        $validHeader  = base64_encode(json_encode(['typ' => 'JWT']));
        $invalidJson  = base64_encode('{not: valid json}');
        $signature    = base64_encode('sig');

        Parser::parse("$validHeader.$invalidJson.$signature");
    }

    /** @test */
    public function it_throws_on_single_segment_token(): void
    {
        $this->expectException(JwtParseException::class);

        Parser::parse('justonepart');
    }
}