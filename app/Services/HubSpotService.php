<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HubSpotService
{
    protected string $baseUrl = 'https://api.hubapi.com/crm/v3/objects/contacts';
    protected ?string $accessToken;

    public function __construct()
    {
        $this->accessToken = config('services.hubspot.token');
    }

    /**
     * Create or update a contact in HubSpot CRM.
     *
     * Returns an associative array with:
     *   - 'hubspot_contact_id' => string
     *   - 'hubspot_response'   => array (full API response body)
     *
     * @throws \Exception on configuration or API errors.
     */
    public function createOrUpdateContact(array $data): array
    {
        $this->validateToken();

        $properties = $this->mapProperties($data);

        // 1. Attempt to create a new contact
        $response = $this->sendRequest('POST', $this->baseUrl, $properties);

        // 2. If the contact already exists (409), extract the existing ID and update instead
        if ($response->status() === 409) {
            $contactId = $this->extractExistingContactId($response);

            if ($contactId) {
                // Remove email — it's the unique identifier and cannot be patched
                unset($properties['email']);
                $response = $this->sendRequest('PATCH', "{$this->baseUrl}/{$contactId}", $properties);
            }
        }

        // 3. Handle final response
        if (!$response->successful()) {
            $errorMsg = $response->json('message') ?? $response->body();
            Log::error('HubSpot API failure', [
                'email'    => $data['email'] ?? null,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
            throw new \Exception('HubSpot API Error: ' . $errorMsg);
        }

        $responseData = $response->json();
        $hubspotContactId = $responseData['id'] ?? null;

        Log::info('HubSpot contact synced', [
            'email'              => $data['email'] ?? null,
            'hubspot_contact_id' => $hubspotContactId,
        ]);

        return [
            'hubspot_contact_id' => $hubspotContactId,
            'hubspot_response'   => $responseData,
        ];
    }

    /**
     * Map local form fields to HubSpot contact properties.
     */
    protected function mapProperties(array $data): array
    {
        $name = trim($data['name'] ?? '');
        $nameParts = explode(' ', $name, 2);

        $enquiryType = $data['enquiry_type'] ?? 'general-enquiry';
        $message = $data['message'] ?? '';

        return [
            'email'     => $data['email'] ?? '',
            'firstname' => $nameParts[0] ?? '',
            'lastname'  => $nameParts[1] ?? '',
            'company'   => $data['company'] ?? '',
            'jobtitle'  => $data['role'] ?? '',
            'message'   => "Enquiry Type: {$enquiryType}\n\nMessage:\n{$message}",
        ];
    }

    /**
     * Extract the existing contact ID from a 409 Conflict response.
     */
    protected function extractExistingContactId($response): ?string
    {
        $errorMsg = $response->json('message') ?? '';

        if (preg_match('/Existing ID:\s*(\d+)/i', $errorMsg, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Ensure the HubSpot access token is configured.
     *
     * @throws \Exception
     */
    protected function validateToken(): void
    {
        if (empty($this->accessToken)) {
            throw new \Exception('HubSpot access token is not configured. Set HUBSPOT_ACCESS_TOKEN in .env');
        }
    }

    /**
     * Send an HTTP request to HubSpot.
     * Automatically retries once if HubSpot reports non-existent properties (400).
     */
    protected function sendRequest(string $method, string $url, array $properties)
    {
        $response = Http::withToken($this->accessToken)
            ->send($method, $url, [
                'json' => ['properties' => $properties],
            ]);

        // Auto-fix: strip non-existent properties and retry once
        if ($response->status() === 400) {
            $invalidProps = $this->extractInvalidProperties($response);

            if (!empty($invalidProps)) {
                foreach ($invalidProps as $prop) {
                    unset($properties[$prop]);
                }

                $response = Http::withToken($this->accessToken)
                    ->send($method, $url, [
                        'json' => ['properties' => $properties],
                    ]);
            }
        }

        return $response;
    }

    /**
     * Parse HubSpot 400 error response to find property names that don't exist.
     */
    protected function extractInvalidProperties($response): array
    {
        $json = $response->json();
        $invalid = [];

        // From structured error objects
        foreach (($json['errors'] ?? []) as $error) {
            if (($error['code'] ?? '') === 'PROPERTY_DOESNT_EXIST') {
                $propName = $error['context']['propertyName'][0] ?? null;
                if ($propName) {
                    $invalid[] = $propName;
                }
            }
        }

        // Fallback: regex on top-level message
        $message = $json['message'] ?? '';
        if (preg_match_all('/Property "([^"]+)" does not exist/i', $message, $matches)) {
            $invalid = array_merge($invalid, $matches[1]);
        }

        return array_unique($invalid);
    }
}