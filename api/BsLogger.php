<?php
/**
 * BsLogger - Better Stack (Logtail) singleton for PHP 7.2
 * No external packages needed. Buffers entries, sends batch on shutdown.
 */
class BsLogger
{
    const SOURCE_TOKEN   = 'FTS2xXAtPNcXiaAyy2VLq7Tj';
    const INGESTING_HOST = 's2649879.eu-central-1a.betterstackdata.com';

    private static $buffer = [];
    private static $registered = false;

    // ------------------------------------------------------------------ API

    /** Generic business event */
    public static function event(string $level, string $module, string $eventType, array $ctx = []): void
    {
        self::push($level, $module, $eventType, $ctx);
    }

    /** HTTP request (endpoint, status, timing) */
    public static function request(string $endpoint, int $httpStatus, float $durationMs, ?string $uid = null): void
    {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        self::push('info', 'request', 'http_request', [
            'endpoint'    => $endpoint,
            'http_status' => $httpStatus,
            'duration_ms' => round($durationMs, 2),
            'platform'    => self::platform($ua),
            'uid'         => $uid,
        ]);
    }

    /** Cron job event */
    public static function cron(string $job, string $status, float $durationSec, array $extra = []): void
    {
        $ctx = array_merge(['cron_job' => $job, 'cron_status' => $status, 'cron_duration_sec' => round($durationSec, 3)], $extra);
        $level = ($status === 'killed' || $status === 'skipped_lock') ? 'warning' : 'info';
        self::push($level, 'cron', $status, $ctx);
    }

    /** Critical error */
    public static function criticalError(string $errorType, string $message, array $ctx = []): void
    {
        $ctx['error_type'] = $errorType;
        self::push('error', 'error', $message, $ctx);
    }

    /** Warning shorthand */
    public static function warn(string $module, string $eventType, array $ctx = []): void
    {
        self::push('warning', $module, $eventType, $ctx);
    }

    /** MySQL error — logs errno + error string automatically */
    public static function mysqlError(string $context, $link, array $extra = []): void
    {
        $errno = ($link !== false && $link !== null) ? mysqli_errno($link) : 0;
        $error = ($link !== false && $link !== null) ? mysqli_error($link) : 'no_link';
        self::push('error', 'mysql', $context, array_merge(
            ['mysql_errno' => $errno, 'mysql_error' => $error],
            $extra
        ));
    }

    // ---------------------------------------------------------------- helpers

    public static function platform(string $ua): string
    {
        if (stripos($ua, 'iphone') !== false || stripos($ua, 'ipad') !== false || stripos($ua, 'ios') !== false) return 'ios';
        if (stripos($ua, 'android') !== false) return 'android';
        return 'unknown';
    }

    // ---------------------------------------------------------------- internals

    private static function push(string $level, string $logger, string $message, array $ctx): void
    {
        if (!self::$registered) {
            register_shutdown_function([self::class, 'flush']);
            self::$registered = true;
        }
        $ms  = sprintf('%03d', (int)(microtime(true) * 1000) % 1000);
        $ip  = isset($_SERVER['REMOTE_ADDR'])      ? (string)$_SERVER['REMOTE_ADDR']      : null;
        $ua  = isset($_SERVER['HTTP_USER_AGENT'])  ? (string)$_SERVER['HTTP_USER_AGENT']  : null;
        $entry = array_merge([
            'dt'        => gmdate('Y-m-d\TH:i:s.') . $ms . 'Z',
            'level'     => $level,
            'logger'    => $logger,
            'message'   => $message,
            'client_ip' => $ip,
            'platform'  => $ua !== null ? self::platform($ua) : null,
        ], $ctx);
        // strip null values
        $entry = array_filter($entry, function ($v) { return $v !== null; });
        self::$buffer[] = $entry;
    }

    public static function flush(): void
    {
        if (empty(self::$buffer)) return;
        $payload = json_encode(array_values(self::$buffer));
        self::$buffer = [];
        $url = 'https://' . self::INGESTING_HOST . '/';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_NOSIGNAL       => 1,
            CURLOPT_TIMEOUT_MS     => 500,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::SOURCE_TOKEN,
            ],
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
