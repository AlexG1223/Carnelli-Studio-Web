<?php
/**
 * Mailer: Envío de correos transaccionales (aviso a Alex y confirmación al cliente)
 * usando PHPMailer con el SMTP de Hostinger y registro de respaldo en storage/logs/emails.log.
 */

declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APP_PATH . '/lib/PHPMailer/PHPMailer.php';

class Mailer
{
    public static function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): bool {
        $smtpEnabled = (bool)env('SMTP_ENABLED', false);

        // Registrar siempre en log local para auditoría y pruebas en XAMPP
        $logDir = STORAGE_PATH . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logEntry = sprintf(
            "[%s] TO: %s <%s> | SUBJECT: %s\n",
            date('Y-m-d H:i:s'),
            $toName,
            $toEmail,
            $subject
        );
        @file_put_contents($logDir . '/emails.log', $logEntry, FILE_APPEND | LOCK_EX);

        if (!$smtpEnabled) {
            return true;
        }

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = (string)env('SMTP_HOST', 'smtp.hostinger.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = (string)env('SMTP_USER', '');
            $mail->Password   = (string)env('SMTP_PASS', '');
            $mail->SMTPSecure = (string)env('SMTP_ENCRYPTION', 'ssl');
            $mail->Port       = (int)env('SMTP_PORT', 465);

            $fromEmail = (string)env('SMTP_FROM_EMAIL', 'carnellistudio2026@gmail.com');
            $fromName  = (string)env('SMTP_FROM_NAME', 'Alex Carnelli · Carnelli Studio');

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName);

            if ($replyToEmail) {
                $mail->addReplyTo($replyToEmail, $replyToName ?? '');
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            return $mail->send();
        } catch (Exception $e) {
            @file_put_contents(
                $logDir . '/php-errors.log',
                '[' . date('Y-m-d H:i:s') . '] Error SMTP: ' . $e->getMessage() . "\n",
                FILE_APPEND
            );
            return false;
        }
    }
}

