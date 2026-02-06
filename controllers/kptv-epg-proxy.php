<?php

/**
 * EPG Proxy for XtreamCodes Providers
 * 
 * Passthrough proxy for EPG data from XC providers
 * No syncing, no storing - just proxying
 * 
 * @since 8.4
 * @package KP Library
 * @author Kevin Pirnie <me@kpirnie.com>
 */

defined('KPTV_PATH') || die('Direct Access is not allowed!');

/**
 * KPTV_EPG_Proxy
 * 
 * Handles EPG passthrough for XC providers only
 */
class KPTV_EPG_Proxy extends \KPT\Database
{

    private const CACHE_TTL = 3600; // 1 hour cache

    public function __construct()
    {
        parent::__construct(KPTV::get_setting('database'));
    }

    /**
     * Handle EPG proxy request
     * 
     * @param string $user Encrypted user ID
     * @param int $providerId Provider ID
     * @return void Outputs XML directly
     */
    public function handleEpgRequest(string $user, int $providerId): void
    {

        try {
            // Handle both URL params and query string params
            if ($user === null && isset($_GET['username'])) {
                // xmltv.php?username={encryptedUser}&password={providerId}
                $user = $_GET['username'];
                $providerId = (int)$_GET['password'];
            }

            // Decrypt user ID
            $userId = KPTV::decrypt($user);

            if (!$userId || !is_numeric($userId)) {
                $this->sendError('Invalid user credentials', 401);
                return;
            }

            // Get provider details
            $provider = $this->getProvider((int)$userId, $providerId);

            if (!$provider) {
                $this->sendError('Provider not found', 404);
                return;
            }

            // Only allow XC providers (type 0)
            if ($provider['sp_type'] !== 0) {
                $this->sendError('EPG only available for XtreamCodes providers', 400);
                return;
            }

            // Check cache first
            $cacheKey = "epg_proxy_{$userId}_{$providerId}";
            $cached = \KPT\Cache::get($cacheKey);

            if ($cached !== false) {
                $this->sendXmlResponse($cached);
                return;
            }

            // Fetch EPG from provider
            $epgData = $this->fetchProviderEpg($provider);

            if (!$epgData) {
                $this->sendError('Failed to fetch EPG data', 502);
                return;
            }

            // Cache the response
            \KPT\Cache::set($cacheKey, $epgData, self::CACHE_TTL);

            // Send response
            $this->sendXmlResponse($epgData);
        } catch (\Throwable $e) {
            \KPT\Logger::error("EPG proxy error", [
                'user' => $user,
                'provider' => $providerId,
                'error' => $e->getMessage()
            ]);

            $this->sendError('Failed to fetch EPG data', 500);
        }
    }

    /**
     * Get provider details
     * 
     * @param int $userId User ID
     * @param int $providerId Provider ID
     * @return array|null Provider data
     */
    private function getProvider(int $userId, int $providerId): ?array
    {

        $result = $this->query('SELECT id, sp_name, sp_type, sp_domain, sp_username, sp_password 
                               FROM kptv_stream_providers 
                               WHERE id = ? AND u_id = ?')
            ->bind([$providerId, $userId])
            ->single()
            ->fetch();

        return $result ? (array)$result : null;
    }

    /**
     * Fetch EPG data from XC provider
     * 
     * @param array $provider Provider details
     * @return string|null EPG XML data
     */
    private function fetchProviderEpg(array $provider): ?string
    {

        $domain = rtrim($provider['sp_domain'], '/');
        $username = $provider['sp_username'];
        $password = $provider['sp_password'];

        // Build XC EPG URL
        $epgUrl = "{$domain}/xmltv.php?username={$username}&password={$password}";

        try {
            // Initialize cURL with longer timeout for EPG
            $ch = curl_init($epgUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 120, // 2 minutes for large EPG files
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept: application/xml, text/xml, */*'
                ]
            ]);

            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($content === false || $httpCode !== 200) {
                \KPT\Logger::error("EPG fetch failed", [
                    'provider' => $provider['sp_name'],
                    'http_code' => $httpCode,
                    'error' => $error
                ]);
                return null;
            }

            return $content;
        } catch (\Exception $e) {
            \KPT\Logger::error("EPG fetch exception", [
                'provider' => $provider['sp_name'],
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Send XML response
     * 
     * @param string $xmlData XML content
     * @return void
     */
    private function sendXmlResponse(string $xmlData): void
    {

        // Set appropriate headers
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=' . self::CACHE_TTL);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + self::CACHE_TTL) . ' GMT');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, HEAD, OPTIONS');

        // Output XML
        echo $xmlData;
        exit;
    }

    /**
     * Send error response
     * 
     * @param string $message Error message
     * @param int $code HTTP status code
     * @return void
     */
    private function sendError(string $message, int $code = 400): void
    {

        http_response_code($code);
        header('Content-Type: application/xml; charset=utf-8');

        // Return minimal XML error
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<error>' . "\n";
        $xml .= '  <message>' . htmlspecialchars($message) . '</message>' . "\n";
        $xml .= '  <code>' . $code . '</code>' . "\n";
        $xml .= '</error>';

        echo $xml;
        exit;
    }
}
