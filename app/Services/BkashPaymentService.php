<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BkashPaymentService
{
    protected $baseUrl;
    protected $appKey;
    protected $appSecret;
    protected $username;
    protected $password;
    protected $sandbox;

    public function __construct()
    {
        // Always use test mode for now
        $this->sandbox = true;
        $this->baseUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta';
        
        // Use hardcoded test credentials
        $this->useHardcodedTestCredentials();
        
        Log::info('BkashPaymentService initialized in TEST mode', [
            'sandbox' => $this->sandbox,
            'baseUrl' => $this->baseUrl,
        ]);
    }

    /**
     * Use hardcoded test credentials
     */
    private function useHardcodedTestCredentials()
    {
        $this->appKey = 'test_app_key_' . time();
        $this->appSecret = 'test_app_secret_' . time();
        $this->username = 'test_user_' . time();
        $this->password = 'test_password_' . time();
        
        Log::info('Using hardcoded test credentials');
    }

    /**
     * Get bKash token - TEST MODE
     */
    public function getToken()
    {
        try {
            Log::info('Getting TEST bKash token');
            
            // Always return mock token for testing
            $token = 'mock_token_' . time() . '_' . rand(10000, 99999);
            
            Log::info('Generated TEST token', ['token' => $token]);
            return $token;

        } catch (\Exception $e) {
            Log::error('TEST bKash token error: ' . $e->getMessage());
            
            // Return mock token even on error
            return 'error_mock_token_' . time();
        }
    }

    /**
     * Create payment - TEST MODE
     */
    public function createPayment($invoiceId, $amount, $invoiceNumber)
    {
        try {
            Log::info('Creating TEST bKash payment', [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'invoice_number' => $invoiceNumber,
                'mode' => 'TEST',
            ]);

            // Always return mock response for testing
            $paymentID = 'TEST_' . time() . '_' . $invoiceId;
            
            $response = [
                'paymentID' => $paymentID,
                'bkashURL' => url('/bkash/test-payment/' . $paymentID),
                'transactionStatus' => 'Initiated',
                'statusMessage' => 'Success - Test Mode',
                'amount' => $amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $invoiceNumber,
                'paymentCreateTime' => now()->toDateTimeString(),
                'mode' => 'TEST',
            ];

            Log::info('TEST payment response', $response);
            return $response;

        } catch (\Exception $e) {
            Log::error('TEST bKash create payment error: ' . $e->getMessage());
            
            // Return mock response even on error
            return [
                'paymentID' => 'ERROR_' . time(),
                'statusMessage' => 'Error in test mode: ' . $e->getMessage(),
                'mode' => 'TEST_ERROR',
            ];
        }
    }

    /**
     * Execute payment - TEST MODE
     */
    public function executePayment($paymentID)
    {
        try {
            Log::info('Executing TEST bKash payment', [
                'paymentID' => $paymentID,
                'mode' => 'TEST',
            ]);

            // Always return mock success response
            $response = [
                'transactionStatus' => 'Completed',
                'trxID' => 'TRX_TEST_' . time() . '_' . rand(10000, 99999),
                'paymentID' => $paymentID,
                'amount' => '100.00',
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => 'TEST_INV_' . time(),
                'statusMessage' => 'Successful - Test Mode',
                'paymentExecuteTime' => now()->toDateTimeString(),
                'mode' => 'TEST',
            ];

            Log::info('TEST execute response', $response);
            return $response;

        } catch (\Exception $e) {
            Log::error('TEST bKash execute payment error: ' . $e->getMessage());
            
            return [
                'transactionStatus' => 'Failed',
                'statusMessage' => 'Test execution error',
                'mode' => 'TEST_ERROR',
            ];
        }
    }

    /**
     * Query payment - TEST MODE
     */
    public function queryPayment($paymentID)
    {
        try {
            Log::info('Querying TEST bKash payment', ['paymentID' => $paymentID]);

            return [
                'transactionStatus' => 'Completed',
                'statusMessage' => 'Payment query successful - Test Mode',
                'paymentID' => $paymentID,
                'mode' => 'TEST',
            ];

        } catch (\Exception $e) {
            Log::error('TEST bKash query payment error: ' . $e->getMessage());
            
            return [
                'transactionStatus' => 'Initiated',
                'statusMessage' => 'Payment query failed in test mode',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if service is configured - Always true for TEST
     */
    public function isConfigured()
    {
        return true;
    }

    /**
     * Get configuration status
     */
    public function getConfigStatus()
    {
        return [
            'sandbox_mode' => $this->sandbox,
            'base_url' => $this->baseUrl,
            'app_key' => substr($this->appKey, 0, 10) . '...',
            'service_ready' => true,
            'mode' => 'HARDCODED_TEST',
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Simple test method
     */
    public function testConnection()
    {
        return [
            'success' => true,
            'message' => 'BkashPaymentService is working in TEST mode',
            'timestamp' => now()->toDateTimeString(),
            'config' => $this->getConfigStatus(),
        ];
    }
}