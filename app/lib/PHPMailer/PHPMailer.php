<?php
/**
 * Implementación liviana compatible con PHPMailer (SMTP con SSL/TLS y soporte HTML UTF-8)
 * ubicada en app/lib/PHPMailer para funcionar en Hostinger y XAMPP sin requerir Composer.
 */

declare(strict_types=1);

namespace PHPMailer\PHPMailer;

class Exception extends \Exception {}

class PHPMailer
{
    public string $CharSet = 'UTF-8';
    public string $Host = 'smtp.hostinger.com';
    public int $Port = 465;
    public bool $SMTPAuth = true;
    public string $Username = '';
    public string $Password = '';
    public string $SMTPSecure = 'ssl';
    public string $From = '';
    public string $FromName = '';
    public string $Subject = '';
    public string $Body = '';
    public string $AltBody = '';
    private bool $isHtml = true;
    private bool $useSmtp = false;
    private array $to = [];
    private array $replyTo = [];

    public function __construct(bool $exceptions = true) {}

    public function isSMTP(): void
    {
        $this->useSmtp = true;
    }

    public function isHTML(bool $isHtml = true): void
    {
        $this->isHtml = $isHtml;
    }

    public function setFrom(string $address, string $name = ''): void
    {
        $this->From = $address;
        $this->FromName = $name;
    }

    public function addAddress(string $address, string $name = ''): void
    {
        $this->to[] = ['address' => $address, 'name' => $name];
    }

    public function addReplyTo(string $address, string $name = ''): void
    {
        $this->replyTo[] = ['address' => $address, 'name' => $name];
    }

    public function send(): bool
    {
        if (empty($this->to)) {
            throw new Exception('No se especificó ningún destinatario.');
        }

        if (!$this->useSmtp || empty($this->Username) || empty($this->Password)) {
            return $this->sendViaMailFunction();
        }

        return $this->sendViaSmtpSocket();
    }

    private function sendViaMailFunction(): bool
    {
        $toHeader = implode(', ', array_map(fn($r) => $r['address'], $this->to));
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: " . ($this->isHtml ? "text/html" : "text/plain") . "; charset={$this->CharSet}\r\n";
        $fromFormatted = $this->FromName !== ''
            ? '=?UTF-8?B?' . base64_encode($this->FromName) . "?= <{$this->From}>"
            : $this->From;
        $headers .= "From: {$fromFormatted}\r\n";
        if (!empty($this->replyTo)) {
            $headers .= "Reply-To: {$this->replyTo[0]['address']}\r\n";
        }
        $subj = '=?UTF-8?B?' . base64_encode($this->Subject) . '?=';
        return @mail($toHeader, $subj, $this->Body, $headers);
    }

    private function sendViaSmtpSocket(): bool
    {
        $prefix = strtolower($this->SMTPSecure) === 'ssl' ? 'ssl://' : '';
        $fp = @fsockopen($prefix . $this->Host, $this->Port, $errno, $errstr, 12);
        if (!$fp) {
            throw new Exception("Error conectando al servidor SMTP: {$errstr} ({$errno})");
        }

        $this->expect($fp, 220);
        $this->cmd($fp, 'EHLO ' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), 250);

        if (strtolower($this->SMTPSecure) === 'tls') {
            $this->cmd($fp, 'STARTTLS', 220);
            stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->cmd($fp, 'EHLO ' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), 250);
        }

        if ($this->SMTPAuth) {
            $this->cmd($fp, 'AUTH LOGIN', 334);
            $this->cmd($fp, base64_encode($this->Username), 334);
            $this->cmd($fp, base64_encode($this->Password), 235);
        }

        $this->cmd($fp, "MAIL FROM:<{$this->From}>", 250);
        foreach ($this->to as $rcpt) {
            $this->cmd($fp, "RCPT TO:<{$rcpt['address']}>", [250, 251]);
        }

        $this->cmd($fp, 'DATA', 354);

        $fromFormatted = $this->FromName !== ''
            ? '=?UTF-8?B?' . base64_encode($this->FromName) . "?= <{$this->From}>"
            : "<{$this->From}>";

        $headers  = "Date: " . date('r') . "\r\n";
        $headers .= "From: {$fromFormatted}\r\n";
        $headers .= "To: <{$this->to[0]['address']}>\r\n";
        if (!empty($this->replyTo)) {
            $headers .= "Reply-To: <{$this->replyTo[0]['address']}>\r\n";
        }
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($this->Subject) . "?=\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: " . ($this->isHtml ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n\r\n";

        $message = $headers . chunk_split(base64_encode($this->Body)) . "\r\n.";
        $this->cmd($fp, $message, 250);
        $this->cmd($fp, 'QUIT', 221);
        fclose($fp);
        return true;
    }

    private function cmd($fp, string $command, int|array $expectedCode): void
    {
        fwrite($fp, $command . "\r\n");
        $this->expect($fp, $expectedCode);
    }

    private function expect($fp, int|array $expected): void
    {
        $response = '';
        while ($line = fgets($fp, 515)) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $code = (int)substr($response, 0, 3);
        $allowed = is_array($expected) ? $expected : [$expected];
        if (!in_array($code, $allowed, true)) {
            throw new Exception("Respuesta SMTP inesperada ({$code}): {$response}");
        }
    }
}

