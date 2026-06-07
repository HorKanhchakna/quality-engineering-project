# Charter 4 — Security: Authentication Token Edge Cases

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-04 |
| **Tester** | Poeun Sreytey |
| **Date** | 2026-06-06 |
| **Time Spent** | 40 minutes |

## Mission
Explore authentication edge cases related to JWT token handling — expired tokens, malformed tokens, missing tokens, and token behavior after logout — to identify security risks or unexpected access.

## Setup / Start Conditions
User account testuser@demo.com exists. Browser DevTools open to inspect network requests and localStorage. Backend JWT configuration reviewed (expiration time, signing algorithm).

## Actions Performed
1. Logged in as testuser@demo.com and captured the JWT token from localStorage.
2. Manually modified the JWT payload in localStorage (changed the `sub` field to a different user ID) and attempted to access protected routes.
3. Removed the JWT token entirely from localStorage and attempted to create an article via the UI.
4. Logged out and immediately reused the old JWT token via direct API calls (curl/Postman).
5. Waited for the token to approach its expiration time and tested API calls near the boundary.
6. Sent a completely malformed string ("not-a-real-token") as the Authorization header.

## Observations / Findings
- **Modified token payload**: API correctly rejected the tampered token with HTTP 401 — signature verification is working properly.
- **Missing token**: UI correctly redirected to /login. API returned 401 Unauthorized as expected.
- **Reused token after logout**: The old JWT token was **still accepted** by the API after logout. The backend does not maintain a token blacklist or revocation list. This means a stolen token remains valid until it naturally expires.
- **Malformed token string**: API returned 401 with no crash — error handling is robust.
- **Near-expiration boundary**: Token worked up until the exact expiration second, then correctly returned 401. No grace period issues observed.

## Bugs Found
- No new functional bugs found in this session. However, a **security observation** was documented: JWT tokens are not invalidated upon logout (stateless JWT design). This is a known trade-off of stateless JWT architectures but should be noted as an accepted risk.

## Risk Assessment
- **Token theft risk**: If a token is intercepted (e.g., via XSS), the attacker has access until the token expires. Recommendation: implement short-lived tokens with refresh token rotation, or maintain a server-side token blacklist.
