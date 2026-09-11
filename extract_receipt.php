<?php
/**
 * Extract structured data from receipts using the Photon Commerce API.
 *
 * Submits a receipt (PDF, image, Word, HTML, or email) and returns 100+
 * structured fields including merchant name, line items, totals, tax, tip,
 * payment type, card number, and more.
 * 25+ languages supported; handwriting, stamps, and tables handled.
 *
 * Processing times (Managed Agents):
 *   Trial accounts:  up to 24 hours
 *   Production:      5 minutes to 24 hours
 *
 * AI extraction (seconds, no Managed Agents):
 *   Contact support@photoncommerce.com to activate.
 *   Once active, submit to /api/v4 instead of /api/pro.
 *
 * Requires: guzzlehttp/guzzle (composer require guzzlehttp/guzzle)
 *
 * Docs:    https://apidocs.photoncommerce.com
 * Sandbox: https://sandbox-api.photoncommerce.com/api/v4/register (20 free calls)
 */

require 'vendor/autoload.php';

use GuzzleHttp\Client;

define('CLIENT_ID',  'YOUR_CLIENT_ID');
define('USERNAME',   'YOUR_USERNAME');
define('API_KEY',    'YOUR_API_KEY');
define('PASSWORD',   'YOUR_PASSWORD');
define('SECRET_KEY', 'YOUR_SECRET_KEY');

// Sandbox: https://sandbox-api.photoncommerce.com  (20 free calls, no card needed)
// Production: https://api.photoncommerce.com
define('BASE_URL', 'https://sandbox-api.photoncommerce.com');

$client = new Client([
    'base_uri' => BASE_URL,
    'headers'  => [
        'CLIENT-ID'     => CLIENT_ID,
        'AUTHORIZATION' => 'apikey ' . USERNAME . ':' . API_KEY,
        'PASSWORD'      => PASSWORD,
        'SECRET-KEY'    => SECRET_KEY,
    ],
]);

function submitReceipt(
    Client $client,
    string $filePath = null,
    string $url = null,
    string $webhookUrl = null,
    string $authToken = null,
    string $id = null,
    string $subaccount = null,
    int    $pageStart = null,
    int    $pageEnd = null
): string {
    if (!$filePath && !$url) throw new InvalidArgumentException('Provide either filePath or url.');

    $query = ['doctype' => 'receipt'];
    if ($url)        $query['url']         = $url;
    if ($webhookUrl) $query['webhook_url'] = $webhookUrl;
    if ($authToken)  $query['auth_token']  = $authToken;
    if ($id)         $query['ID']          = $id;
    if ($subaccount) $query['subaccount']  = $subaccount;
    if ($pageStart !== null) $query['page_start'] = $pageStart;
    if ($pageEnd   !== null) $query['page_end']   = $pageEnd;

    $options = ['query' => $query];
    if ($filePath) {
        $options['multipart'] = [
            ['name' => 'pdf', 'contents' => fopen($filePath, 'r'), 'filename' => basename($filePath)],
        ];
    }

    // For AI extraction (seconds), replace /api/pro with /api/v4 — contact support@photoncommerce.com to activate.
    $response = $client->post('/api/pro', $options);
    $data = json_decode($response->getBody(), true);
    return $data['photon_key'];
}

function fetchResult(Client $client, string $photonKey): array
{
    $response = $client->get('/api/v4/json', ['query' => ['photon_key' => $photonKey]]);
    $data = json_decode($response->getBody(), true);
    return $data['data'] ?? [];
}

function waitForResult(Client $client, string $photonKey, int $pollInterval = 20, int $timeout = 3600): array
{
    $deadline = time() + $timeout;
    while (time() < $deadline) {
        $result = fetchResult($client, $photonKey);
        $status = $result['Status'] ?? null;
        if ($status && $status !== 'pending' && $status !== 'processing') return $result;
        echo "  Status: " . ($status ?? 'pending') . " — retrying in {$pollInterval}s...\n";
        sleep($pollInterval);
    }
    throw new RuntimeException("Extraction not complete after {$timeout}s");
}

// --- Option A: submit from a local file ---
$photonKey = submitReceipt($client, filePath: 'receipt.pdf');

// --- Option B: submit via a publicly accessible URL ---
// $photonKey = submitReceipt($client, url: 'https://example.com/receipt.pdf');

echo "Submitted. photon_key: $photonKey\n";
echo "Waiting for extraction to complete...\n";

$result = waitForResult($client, $photonKey);

echo "\n--- Receipt Data ---\n";
echo "Merchant:      " . ($result['Vendor_Name']    ?? '') . "\n";
echo "Date:          " . ($result['Date']           ?? '') . "\n";
echo "Category:      " . ($result['Category']       ?? '') . "\n";
echo "Payment:       " . ($result['Payment_Type']   ?? '') . " " . ($result['Card_Number'] ?? '') . "\n";
echo "Subtotal:      " . ($result['Subtotal']        ?? '') . "\n";
echo "Tax:           " . ($result['Tax']             ?? '') . "\n";
echo "Tip:           " . ($result['Tip']             ?? '') . "\n";
echo "Total:         " . ($result['Total']           ?? '') . " " . ($result['Currency_Code'] ?? '') . "\n";

echo "\n--- Line Items ---\n";
foreach ($result['Line_Items'] ?? [] as $item) {
    echo "  {$item['Description']} — Qty {$item['QTY']} x {$item['Price']} = {$item['Amount']}\n";
}
