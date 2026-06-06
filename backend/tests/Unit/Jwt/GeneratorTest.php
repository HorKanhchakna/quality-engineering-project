<?php

namespace Tests\Unit\Jwt;

use App\Contracts\JwtSubjectInterface;
use App\Jwt\Builder;
use App\Jwt\Generator;
use App\Jwt\Parser;
use App\Jwt\Token;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Tests\TestCase;

class GeneratorTest extends TestCase
{
    public function testSignatureUsesAppKeyAndTokenData(): void
    {
        config(['app.key' => 'unit-test-secret']);

        $token = Builder::build()
            ->issuedAt(1000)
            ->expiresAt(2000)
            ->subject(42)
            ->withClaim('scope', 'read')
            ->getToken();

        $expected = hash_hmac('sha256', base64_encode($token->headers()->toJson()) . '.' . base64_encode($token->claims()->toJson()), 'unit-test-secret');

        $this->assertSame($expected, Generator::signature($token));
    }

    public function testSignatureThrowsWithoutAppKey(): void
    {
        config(['app.key' => null]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No APP_KEY specified.');

        Generator::signature(new Token());
    }

    public function testTokenCanBeParsedAndValidatedForRoundTripData(): void
    {
        config([
            'app.key' => 'unit-test-secret',
            'jwt.expiration' => 3600,
        ]);

        Carbon::setTestNow(Carbon::create(2026, 6, 6, 12, 0, 0, 'UTC'));

        try {
            $subject = new class implements JwtSubjectInterface {
                public function getJwtIdentifier(): mixed
                {
                    return 77;
                }
            };

            $encoded = Generator::token($subject);
            $token = Parser::parse($encoded);

            $this->assertSame(77, $token->getSubject());
            $this->assertSame(3600, $token->getExpiration() - $token->claims()->get('iat'));
            $this->assertSame(Generator::signature($token), $token->getUserSignature());
        } finally {
            Carbon::setTestNow();
        }
    }
}
