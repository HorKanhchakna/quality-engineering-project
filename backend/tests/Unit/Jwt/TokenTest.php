<?php

namespace Tests\Unit\Jwt;

use App\Jwt\Token;
use Tests\TestCase;

class TokenTest extends TestCase
{
    public function testDefaultHeadersAreLoadedFromConfig(): void
    {
        $token = new Token();

        $this->assertSame('HS256', $token->headers()->get('alg'));
        $this->assertSame('JWT', $token->headers()->get('typ'));
    }

    public function testHeadersReturnsACopy(): void
    {
        $token = new Token();
        $headers = $token->headers();

        $headers->put('kid', 'injected-value');

        $this->assertFalse($token->headers()->has('kid'));
    }

    public function testClaimsReturnsACopy(): void
    {
        $token = new Token();
        $token->putToPayload('sub', 123);

        $claims = $token->claims();
        $claims->put('sub', 456);

        $this->assertSame(123, $token->getSubject());
    }

    public function testPutToPayloadStoresClaim(): void
    {
        $token = new Token();

        $token->putToPayload('role', 'editor');

        $this->assertSame('editor', $token->claims()->get('role'));
    }

    public function testPutToHeaderStoresHeader(): void
    {
        $token = new Token();

        $token->putToHeader('kid', 'key-1');

        $this->assertSame('key-1', $token->headers()->get('kid'));
    }

    public function testGetSubjectReturnsPayloadSubject(): void
    {
        $token = new Token();

        $token->putToPayload('sub', 99);

        $this->assertSame(99, $token->getSubject());
    }

    public function testGetExpirationReturnsIntegerValue(): void
    {
        $token = new Token();

        $token->putToPayload('exp', '1700000000');

        $this->assertSame(1700000000, $token->getExpiration());
    }

    public function testSetUserSignatureStoresSignature(): void
    {
        $token = new Token();

        $token->setUserSignature('signature-value');

        $this->assertSame('signature-value', $token->getUserSignature());
    }
}
