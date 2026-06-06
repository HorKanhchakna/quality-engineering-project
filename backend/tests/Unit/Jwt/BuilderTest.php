<?php

namespace Tests\Unit\Jwt;

use App\Contracts\JwtSubjectInterface;
use App\Jwt\Builder;
use Tests\TestCase;

class BuilderTest extends TestCase
{
    public function testBuildReturnsTokenWithDefaultHeaders(): void
    {
        $token = Builder::build()->getToken();

        $this->assertSame('HS256', $token->headers()->get('alg'));
        $this->assertSame('JWT', $token->headers()->get('typ'));
    }

    public function testBuilderChainWritesClaimsAndHeaders(): void
    {
        $token = Builder::build()
            ->issuedAt(111)
            ->expiresAt(222)
            ->subject(333)
            ->withClaim('scope', 'write')
            ->withHeader('kid', 'key-1')
            ->getToken();

        $this->assertSame(111, $token->claims()->get('iat'));
        $this->assertSame(222, $token->claims()->get('exp'));
        $this->assertSame(333, $token->getSubject());
        $this->assertSame('write', $token->claims()->get('scope'));
        $this->assertSame('key-1', $token->headers()->get('kid'));
    }

    public function testSubjectAcceptsJwtSubjectInterface(): void
    {
        $subject = new class implements JwtSubjectInterface {
            public function getJwtIdentifier(): mixed
            {
                return 55;
            }
        };

        $token = Builder::build()
            ->subject($subject)
            ->getToken();

        $this->assertSame(55, $token->getSubject());
    }
}
