<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Envoie un mail avec toute la conversation du ticket.
 * @param string $to Email du destinataire
 * @param string $subject Sujet du mail
 * @param int $ticketId ID du ticket concerné
 * @param int $userId ID de l'utilisateur qui envoie (pour l'adresse from)
 */
function sendTicketNotification($to, $subject, $ticketId, $userId) {
    try {
        $pdo = new PDO(
            'mysql:host=' . ($_ENV['DB_HOST'] ?? 'localhost') . ';dbname=' . ($_ENV['DB_NAME']),
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );

        $stmt = $pdo->prepare('SELECT email FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $fromEmail = $user ? $user['email'] : ($_ENV['MAIL_FROM'] ?? 'noreply@example.com');

        $stmt = $pdo->prepare('SELECT t.ticket_name, t.message, t.created_at, t.status, u.name AS creator_name FROM ticket t JOIN users u ON t.creator = u.id WHERE t.id = ?');
        $stmt->execute([$ticketId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare('SELECT m.message, m.created_at, u.name FROM ticket_message m JOIN users u ON m.creator = u.id WHERE m.ticket_id = ? ORDER BY m.created_at ASC');
        $stmt->execute([$ticketId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $isClosed = isset($ticket['status']) && strtolower($ticket['status']) === 'cloture';
        $mainTitle = $isClosed
            ? "Votre ticket MyTickets #{$ticketId} a été clôturé"
            : "Une réponse à votre ticket MyTickets #{$ticketId}";

        $body = "<div style='font-family:Segoe UI,Arial,sans-serif;background:#f4f8fb;padding:0;margin:0;'>";
        $body .= "<div style='max-width:600px;margin:32px auto;background:#fff;border-radius:10px;box-shadow:0 2px 12px #e3e8ee;padding:32px 28px;'>";
        $body .= "<div style='text-align:center;margin-bottom:24px;'>";
        $body .= "<img src='https://cdn-icons-png.flaticon.com/512/9068/9068679.png' alt='MyTickets' style='width:48px;height:48px;margin-bottom:8px;'>";
        $body .= "<h1 style='color:#2563eb;font-size:1.6em;margin:0 0 8px 0;'>{$mainTitle}</h1>";
        $body .= "</div>";
        $body .= "<h2 style='color:#222;font-size:1.2em;margin-bottom:8px;'>Ticket : " . htmlspecialchars($ticket['ticket_name']) . "</h2>";
        $body .= "<p style='margin:0 0 18px 0;color:#555;'><b>Créé par :</b> " . htmlspecialchars($ticket['creator_name']) . " <b>le</b> " . date('d/m/Y H:i', strtotime($ticket['created_at'])) . "</p>";
        $body .= "<div style='margin-bottom:2em;padding:14px 18px;background:#e8f0fe;border-radius:7px;'><b style='color:#2563eb;'>Message initial :</b><br>" . nl2br(htmlspecialchars($ticket['message'])) . "</div>";
        if ($messages && count($messages) > 0) {
            $body .= "<div style='margin-bottom:2em;'><b style='color:#2563eb;'>Conversation :</b><br>";
            foreach ($messages as $msg) {
                $body .= "<div style='margin:14px 0 0 0;padding:12px 16px;background:#f6f8fa;border-radius:6px;'>";
                $body .= "<b style='color:#2563eb;'>" . htmlspecialchars($msg['name']) . "</b> ";
                $body .= "<span style='color:#888;font-size:12px;'>[" . date('d/m/Y H:i', strtotime($msg['created_at'])) . "]</span><br>";
                $body .= "<span style='color:#222;'>" . nl2br(htmlspecialchars($msg['message'])) . "</span>";
                $body .= "</div>";
            }
            $body .= "</div>";
        }
        $body .= "<div style='text-align:center;margin-top:32px;'>";
        $body .= "<a href='https://workshopgroupe.nicolasdeprets.online/view_tickets.php' style='display:inline-block;padding:12px 28px;background:#2563eb;color:#fff;border-radius:6px;text-decoration:none;font-weight:600;font-size:1em;'>Accéder au tickets</a>";
        $body .= "</div>";
        $body .= "</div></div>";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8'; 
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        $mail->setFrom($fromEmail, 'Support');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

    } catch (Exception $e) {
    } catch (Throwable $e) {
    }
}