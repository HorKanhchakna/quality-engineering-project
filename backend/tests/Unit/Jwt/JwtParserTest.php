<?php

namespace Tests\Unit\Jwt;

use App\Jwt\Builder;
use App\Jwt\Generator;
use App\Exceptions\JwtParseException;
use App\Jwt\Parser;
use JsonException;
use Tests\TestCase;

class JwtParserTest extends TestCase
{
    public function testParseParts(): void
    {
        $this->expectException(JwtParseException::class);

        Parser::parse('string');
    }

    public function testParseNotBase64(): void
    {
        $this->expectException(JwtParseException::class);

        Parser::parse('string@.string#.string*');
    }

    public function testParseNotJson(): void
    {
        $this->expectException(JsonException::class);

        Parser::parse('b25l.dHdv.dGhyZWU=');
    }

    public function testParseValidTokenRoundTrip(): void
    {
        config(['app.key' => 'unit-test-secret']);

        $token = Builder::build()
            ->issuedAt(1000)
            ->expiresAt(2000)
            ->subject(12)
            ->withClaim('role', 'reader')
            ->getToken();
        $token->setUserSignature(Generator::signature($token));

        $encoded = implode('.', [
            base64_encode($token->headers()->toJson()),
            base64_encode($token->claims()->toJson()),
            base64_encode($token->getUserSignature()),
        ]);

        $parsed = Parser::parse($encoded);

        $this->assertSame(12, $parsed->getSubject());
        $this->assertSame('reader', $parsed->claims()->get('role'));
        $this->assertSame($token->getUserSignature(), $parsed->getUserSignature());
    }
}
