# Phase 2: White-Box Testing Report

## Scope
This Phase 2 white-box work follows the same application selected in Phase 1 and focuses on the backend JWT implementation in `backend/app/Jwt` plus the API behavior covered by the existing feature tests in `backend/tests/Feature/Api`.

## Unit Test Coverage

| File | Coverage | Risk reduced |
|---|---|---|
| `backend/tests/Unit/Jwt/JwtParserTest.php` | Invalid token structure, bad base64, bad JSON, valid round-trip parse | Prevents crashes and malformed token acceptance |
| `backend/tests/Unit/Jwt/TokenTest.php` | Default headers, header/claim mutation, subject, expiration, signature storage | Verifies the token object preserves JWT state correctly |
| `backend/tests/Unit/Jwt/BuilderTest.php` | Builder chaining, default headers, custom claims, JWT subject interface support | Confirms token construction writes the expected payload/header data |
| `backend/tests/Unit/Jwt/GeneratorTest.php` | HMAC signature generation, missing APP_KEY failure, token generation round trip | Ensures signatures are deterministic and token output is valid |

## What the tests validate
- JWT headers always include `alg=HS256` and `typ=JWT`.
- Payload claims can be written and read without leaking internal state.
- Builder chaining writes `iat`, `exp`, `sub`, custom claims, and custom headers correctly.
- Signatures are generated with the configured `APP_KEY`.
- Generated tokens can be parsed back into a usable JWT token object.

## API Validation Basis
The API validation set follows the same RealWorld stack chosen in Phase 1 and is covered by the existing Laravel feature tests in `backend/tests/Feature/Api`, including:
- authentication: register, login, current user, JWT guard
- user profile: show, follow, unfollow, update
- articles: list, create, show, update, delete, feed
- comments: list, create, delete
- favorites: add, remove
- tags: list

## Evidence to include in submission
- Source code for the unit tests
- Postman collection exported in the `postman` folder with CRUD coverage and request-level assertions
- Screenshots or console output from running the tests, if your lecturer asks for proof of execution
