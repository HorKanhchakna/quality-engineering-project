# Phase 2 API Validation

## Base URL
`http://localhost:8000/api`

## Project Alignment
This API validation follows the same application and project path selected in Phase 1, so the requests here are built against the same RealWorld backend used for the overall assignment.

## Included Requests
- `POST /users` register a new user
- `POST /users/login` authenticate an existing user
- `GET /user` retrieve the current user
- `PUT /user` update the current user
- `GET /tags` fetch tag list
- `GET /articles` list articles
- `POST /articles` create an article
- `GET /articles/{slug}` inspect an article
- `PUT /articles/{slug}` update an article
- `DELETE /articles/{slug}` delete an article
- `GET /profiles/{username}` inspect a profile
- `POST /profiles/{username}/follow` follow a profile
- `POST /articles/{slug}/comments` create a comment
- `POST /articles/{slug}/favorite` favorite an article

## Validation Goals
- Verify request and response shapes match the RealWorld API contract.
- Confirm protected routes require the `Authorization: Token <jwt>` header.
- Confirm successful authentication returns a token that can be reused in later requests.
- Confirm list endpoints return paginated or collection-style payloads without schema drift.
- Confirm each Postman request includes at least two assertions: HTTP status and JSON response validation.
- Confirm the collection covers a complete CRUD flow with at least 10 backend requests.

## Suggested Run Order
1. Register or log in to obtain a token.
2. Call `GET /user` and `PUT /user`.
3. Call list and detail endpoints such as `/tags`, `/articles`, and `/articles/{slug}`.
4. Create, update, and delete an article.
5. Exercise authenticated actions like follow, comment, and favorite.

## Notes
- The Postman collection uses variables so you can swap test accounts without rewriting the requests.
- Set `{{token}}` after a login response before running the protected requests.
