<?php

namespace App\Services;

use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAuthService
{
    /**
     * Verify a Firebase ID token and extract user data.
     *
     * @param string $idToken The Firebase ID token from the mobile client
     * @return array{uid: string, email: string|null, name: string|null, provider: string|null}
     * @throws \Kreait\Firebase\Exception\Auth\FailedToVerifyToken
     */
    public function verifyIdToken(string $idToken): array
    {
        $verifiedIdToken = Firebase::auth()->verifyIdToken($idToken);

        $claims = $verifiedIdToken->claims();

        $firebaseClaims = $claims->get('firebase', []);
        $signInProvider = $firebaseClaims['sign_in_provider'] ?? null;

        $providerMap = [
            'google.com'    => 'google',
            'apple.com'     => 'apple',
            'microsoft.com' => 'microsoft',
        ];

        return [
            'uid'      => $claims->get('sub'),
            'email'    => $claims->get('email'),
            'name'     => $claims->get('name'),
            'provider' => $providerMap[$signInProvider] ?? $signInProvider,
        ];
    }
}
