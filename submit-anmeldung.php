<?php
/**
 * BIG RED TOUR 2026 – Anmeldung Formular-Handler
 *
 * Dieses Skript muss auf einem PHP-fähigen Webserver liegen (poeppel-wkz.de).
 * Der HTML-Code kann auf GitHub Pages bleiben – das Formular submitted via fetch() hierher.
 *
 * Anforderungen: PHP >= 7.4, mail() muss auf dem Server konfiguriert sein.
 */

// Fehlerbehandlung (auf Produktivserver: 0)
error_reporting(0);
ini_set('display_errors', 0);

// CORS-Header (erlaubt Requests von GitHub Pages und lokalem Server)
$allowedOrigins = [
    'https://svenn8n-a11y.github.io',
    'https://www.poeppel-wkz.de',
    'http://localhost',
    'http://localhost:8080',
    'http://127.0.0.1:8080'
];
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

// Preflight-Request abfangen
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Nur POST erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Nur POST-Anfragen erlaubt']);
    exit;
}

// Daten aus FormData lesen
$data = $_POST;

if (empty($data)) {
    // Fallback: JSON-Body
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true) ?: [];
}

// Pflichtfelder prüfen
$requiredFields = ['vorname', 'nachname', 'firma', 'email', 'branche'];
foreach ($requiredFields as $field) {
    if (empty(trim($data[$field] ?? ''))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Pflichtfelder fehlen: ' . $field]);
        exit;
    }
}

// E-Mail validieren
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ungültige E-Mail-Adresse']);
    exit;
}

// Daten bereinigen
$vorname   = htmlspecialchars(strip_tags(trim($data['vorname'])));
$nachname  = htmlspecialchars(strip_tags(trim($data['nachname'])));
$name      = "$vorname $nachname";
$firma     = htmlspecialchars(strip_tags(trim($data['firma'])));
$email     = htmlspecialchars(strip_tags(trim($data['email'])));
$branche   = htmlspecialchars(strip_tags(trim($data['branche'])));
$personen        = htmlspecialchars(strip_tags(trim($data['personen'] ?? '1')));
$kein_newsletter = !empty($data['kein_newsletter']) ? true : false;

// Branchenbezeichnung leserlich machen
$branchenMap = [
    'holz-metall'  => 'Holz &amp; Metall / Zerspanung',
    'kfz'          => 'KFZ-Werkstatt / Autohaus',
    'shk'          => 'SHK (Sanitär, Heizung, Klima)',
    'bau'          => 'Bau (Hoch-/Tiefbau)',
    'feuerwehr'    => 'Feuerwehr / Rettungsdienst',
    'sonstige'     => 'Sonstige',
];
$brancheLabel = $branchenMap[$branche] ?? htmlspecialchars($branche);

// Timestamp
$zeitpunkt = date('d.m.Y') . ' um ' . date('H:i') . ' Uhr';

// ─── E-Mail-Empfänger (intern) ──────────────────────────────────────────────
$to      = 'support@poeppel-wkz.de, s.muellers@poeppel-wkz.de';
$eol     = "\r\n";
$subject = '=?UTF-8?B?' . base64_encode('BIG RED Anmeldung – ' . $name . ' | ' . $firma) . '?=';

// ─── Interne HTML-E-Mail ─────────────────────────────────────────────────────
$internMail = '
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; color: #333; font-size: 16px; }
    .container { max-width: 680px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .header { background: linear-gradient(135deg, #C8102E, #1a1a1a); color: #fff; padding: 30px 35px; }
    .header h1 { margin: 0 0 6px 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px; }
    .header p { margin: 0; opacity: 0.85; font-size: 14px; }
    .badge { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 20px; padding: 4px 14px; font-size: 13px; font-weight: 600; margin-top: 12px; }
    .section { padding: 28px 35px; border-bottom: 2px solid #f0f0f0; }
    .section:last-of-type { border-bottom: none; }
    .section-title { color: #C8102E; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 16px 0; }
    .info-row { display: flex; margin-bottom: 12px; }
    .info-label { font-weight: 600; min-width: 150px; color: #666; font-size: 15px; }
    .info-value { color: #1a1a1a; font-size: 15px; }
    .info-value strong { color: #C8102E; }
    .info-value a { color: #C8102E; text-decoration: none; }
    .newsletter-ja { background: #e8f5e9; border: 2px solid #2e7d32; border-radius: 8px; padding: 14px 20px; margin: 16px 0; color: #1b5e20; font-weight: 700; font-size: 15px; }
    .newsletter-nein { background: #fff3e0; border: 2px solid #e65100; border-radius: 8px; padding: 14px 20px; margin: 16px 0; color: #bf360c; font-weight: 700; font-size: 15px; }
    .footer-bar { background: #1a1a1a; color: #888; padding: 20px 35px; text-align: center; font-size: 13px; }
  </style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>Neue Anmeldung</h1>
    <p>Milwaukee BIG RED TOUR 2026 &middot; Memmingen</p>
    <div class="badge">10. April 2026</div>
  </div>
  <div class="section">
    <div class="section-title">Teilnehmer</div>
    <div class="info-row">
      <div class="info-label">Name:</div>
      <div class="info-value"><strong>' . $name . '</strong></div>
    </div>
    <div class="info-row">
      <div class="info-label">Firma:</div>
      <div class="info-value">' . $firma . '</div>
    </div>
    <div class="info-row">
      <div class="info-label">E-Mail:</div>
      <div class="info-value"><a href="mailto:' . $email . '">' . $email . '</a></div>
    </div>
    <div class="info-row">
      <div class="info-label">Branche:</div>
      <div class="info-value">' . $brancheLabel . '</div>
    </div>
    <div class="info-row">
      <div class="info-label">Personen:</div>
      <div class="info-value">' . $personen . '</div>
    </div>
  </div>
  <div class="section">
    <div class="section-title">Newsletter</div>
    ' . ($kein_newsletter
        ? '<div class="newsletter-nein">&#10060; KEIN Newsletter gewünscht – diese Person NICHT in den Verteiler aufnehmen!</div>'
        : '<div class="newsletter-ja">&#10003; Newsletter erwünscht – Person darf in den Verteiler aufgenommen werden.</div>'
    ) . '
  </div>
  <div class="section">
    <div class="section-title">Details</div>
    <div class="info-row">
      <div class="info-label">Angemeldet am:</div>
      <div class="info-value">' . $zeitpunkt . '</div>
    </div>
  </div>
  <div class="footer-bar">
    BIG RED TOUR 2026 &middot; R. P&ouml;ppel GmbH &amp; Co. KG &middot; Memmingen
  </div>
</div>
</body>
</html>';

// ─── Interne E-Mail senden ───────────────────────────────────────────────────
$headers  = "From: noreply@poeppel-wkz.de" . $eol;
$headers .= "Reply-To: $email" . $eol;
$headers .= "Return-Path: noreply@poeppel-wkz.de" . $eol;
$headers .= "MIME-Version: 1.0" . $eol;
$headers .= "Content-Type: text/html; charset=UTF-8" . $eol;
$headers .= "Content-Transfer-Encoding: quoted-printable" . $eol;
$headers .= "X-Mailer: PHP/" . phpversion() . $eol;
$headers .= "X-Priority: 1" . $eol;

$internMailEncoded = quoted_printable_encode($internMail);
$mailSent = mail($to, $subject, $internMailEncoded, $headers, '-f noreply@poeppel-wkz.de');

// ─── Bestätigungs-E-Mail an Teilnehmer ──────────────────────────────────────
if ($mailSent) {
    $confirmSubject = '=?UTF-8?B?' . base64_encode('Deine Anmeldung zur BIG RED TOUR 2026 ist bestätigt!') . '?=';

    $confirmMail = '
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f4f4f4; margin: 0; padding: 0; color: #333; font-size: 16px; line-height: 1.7; }
    .container { max-width: 640px; margin: 0 auto; background: #fff; }
    .logo-bar { background: #fff; padding: 16px 30px; border-bottom: 1px solid #f0f0f0; }
    .logo-bar table { width: 100%; border-collapse: collapse; }
    .logo-bar img { height: 28px; width: auto; display: block; }
    .header { background: linear-gradient(135deg, rgba(0,0,0,0.72) 0%, rgba(200,16,46,0.68) 100%),
              url(https://svenn8n-a11y.github.io/big-red-tour-2026/assets/images/truck_hd.png) center/cover no-repeat;
              color: #fff; padding: 48px 35px; text-align: center; }
    .header h1 { margin: 0 0 8px 0; font-size: 28px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
    .header p { margin: 0; font-size: 16px; opacity: 0.9; color: #fff; }
    .checkmark { width: 60px; height: 60px; background: rgba(255,255,255,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; }
    .body-section { padding: 32px 35px; border-bottom: 1px solid #f0f0f0; }
    .body-section:last-of-type { border-bottom: none; }
    .greeting { font-size: 17px; margin-bottom: 14px; }
    .event-box { background: #fff8f8; border: 2px solid #C8102E; border-radius: 8px; padding: 20px 24px; margin: 20px 0; }
    .event-box h3 { color: #C8102E; margin: 0 0 12px 0; font-size: 15px; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; }
    .event-row { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 8px; font-size: 15px; }
    .event-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .cap-box { background: #1a1a1a; color: #fff; border-radius: 8px; padding: 18px 24px; margin: 20px 0; }
    .cap-box strong { color: #C8102E; }
    .vip-box { background: linear-gradient(135deg, #C8102E 0%, #8B0000 100%); color: #fff; border-radius: 8px; padding: 16px 24px; margin: 16px 0; }
    .steps { margin: 16px 0; }
    .step { display: flex; gap: 14px; margin-bottom: 16px; align-items: flex-start; }
    .step-num { background: #C8102E; color: #fff; width: 32px; height: 32px; border-radius: 50%; font-weight: 700; font-size: 15px; text-align: center; line-height: 32px; flex-shrink: 0; }
    .step-text h4 { margin: 0 0 3px 0; font-size: 15px; color: #1a1a1a; }
    .step-text p { margin: 0; color: #666; font-size: 14px; }
    .contact-box { background: #f9f9f9; border-radius: 8px; padding: 20px 24px; margin: 16px 0; }
    .contact-name { font-weight: 700; color: #C8102E; font-size: 16px; margin-bottom: 8px; }
    .contact-item { font-size: 15px; margin-bottom: 6px; }
    .contact-item a { color: #C8102E; text-decoration: none; font-weight: 500; }
    .cta-btn { display: inline-block; background: #C8102E; color: #fff !important; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 700; font-size: 15px; margin: 16px 0; }
    .cal-box { text-align: center; padding: 4px 0 8px 0; }
    .cal-box p { font-weight: 600; color: #1a1a1a; margin-bottom: 14px; }
    .cal-btn { display: inline-block; border: 2px solid #C8102E; color: #C8102E !important; text-decoration: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 13px; margin: 4px 4px; font-family: -apple-system, sans-serif; }
    .footer-bar { background: #1a1a1a; color: #888; padding: 24px 35px; text-align: center; font-size: 13px; line-height: 1.8; }
    .footer-bar a { color: #999; text-decoration: underline; }
  </style>
</head>
<body>
<div class="container">

  <div class="logo-bar">
    <table><tr>
      <td><img src="https://svenn8n-a11y.github.io/big-red-tour-2026/assets/images/logo_poeppel.png" alt="Pöppel"></td>
      <td style="text-align:right;"><img src="https://svenn8n-a11y.github.io/big-red-tour-2026/assets/images/Milwaukee-Symbol-500x281.png" alt="Milwaukee Tool" style="height:32px;width:auto;display:block;"></td>
    </tr></table>
  </div>

  <div class="header">
    <div class="checkmark">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h1>Du bist dabei!</h1>
    <p>Deine Anmeldung zur BIG RED TOUR 2026 ist bestätigt.</p>
  </div>

  <div class="body-section">
    <p class="greeting">Hallo <strong>' . $vorname . '</strong>,</p>
    <p>vielen Dank für deine Anmeldung! Wir freuen uns, dich am <strong>10. April 2026</strong> bei uns begrüßen zu dürfen. Hier sind alle wichtigen Infos auf einen Blick:</p>

    <div class="event-box">
      <h3>Veranstaltungsdetails</h3>
      <div class="event-row">
        <span class="event-icon">&#128197;</span>
        <span><strong>Datum:</strong> Freitag, 10. April 2026</span>
      </div>
      <div class="event-row">
        <span class="event-icon">&#128336;</span>
        <span><strong>Uhrzeit:</strong> 10:00 – 17:00 Uhr</span>
      </div>
      <div class="event-row">
        <span class="event-icon">&#128205;</span>
        <span><strong>Ort:</strong> R. Pöppel GmbH &amp; Co. KG, Alpenstraße 45, 87700 Memmingen</span>
      </div>
      <div class="event-row">
        <span class="event-icon">&#127881;</span>
        <span><strong>Eintritt:</strong> Kostenlos</span>
      </div>
    </div>


  </div>

  <div class="body-section">
    <p style="font-weight:600;color:#1a1a1a;margin-bottom:12px;">&#127358; Die ersten 100 Anmeldungen erhalten eine exklusive Milwaukee-Cap – kostenlos!</p>
    <div class="cap-box">
      <strong>Dein Tipp:</strong> Komm früh &ndash; die Caps werden am Eingang bei Ankunft ausgegeben, solange der Vorrat reicht.
    </div>
  </div>

  <div class="body-section">
    <p style="font-weight:600;color:#1a1a1a;margin-bottom:16px;">Was erwartet dich?</p>
    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <div class="step-text">
          <h4>Live-Stationen &amp; Gewerk-Challenges</h4>
          <p>Interaktive Stationen für jedes Gewerk – teste selbst und miss dich mit anderen. Anwendungstechniker &amp; Spezialisten vor Ort beantworten deine Fragen.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-text">
          <h4>Vorstellung der Neuheiten</h4>
          <p>Die neuesten Milwaukee M18/M12 Innovationen – exklusiv präsentiert, bevor sie in den regulären Handel kommen.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-text">
          <h4>Blaulicht-Spektakel mit Feuerwehr &amp; THW</h4>
          <p>Dramatische Vorführung: Feuerwehr und THW schneiden ein Schrottauto live auf – mit Milwaukee M18 FUEL™ Rettungsgeräten. Das sieht man nicht alle Tage.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <div class="step-text">
          <h4>Gregor Prinz live</h4>
          <p>Forstwirt, Sicherheitsexperte &amp; YouTuber (55K Abonnenten) – live auf der Bühne mit M18 FUEL Kettensäge.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">5</div>
        <div class="step-text">
          <h4>Bucket Deals bis 1.000 €</h4>
          <p>Exklusive Tages-Deals: Werkzeugpakete ab 250 €, 500 € und 1.000 € – nur an diesem Tag.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">6</div>
        <div class="step-text">
          <h4>&#127881; Überraschungen</h4>
          <p>Wir haben noch ein paar Extras vorbereitet, die wir noch nicht verraten wollen. Komm vorbei und lass dich überraschen!</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">7</div>
        <div class="step-text">
          <h4>Food &amp; Getränke</h4>
          <p><strong>Der Schachen</strong> versorgt dich den ganzen Tag kostenlos mit Allgäuer Spezialitäten – inklusive <strong>4 Freigetränken pro Gast</strong>. Darüber hinaus warten kulinarische Schmankerl von weiteren Ausstellern auf dich.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="body-section">
    <div class="cal-box">
      <p>&#128197; Termin in deinen Kalender eintragen:</p>
      <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Milwaukee+BIG+RED+TOUR+2026+%E2%80%93+P%C3%B6ppel+Memmingen&dates=20260410T060000Z%2F20260410T150000Z&details=Live-Demos%2C+Vorstellung+der+Neuheiten%2C+Feuerwehr-Spektakel%2C+Gregor+Prinz+live%2C+Bucket+Deals+bis+1.000+%E2%82%AC&location=R.+P%C3%B6ppel+GmbH+%26+Co.+KG%2C+Alpenstra%C3%9Fe+45%2C+87700+Memmingen" target="_blank" class="cal-btn">Google Kalender</a>
      <a href="https://outlook.live.com/calendar/0/deeplink/compose?subject=Milwaukee+BIG+RED+TOUR+2026+%E2%80%93+P%C3%B6ppel+Memmingen&startdt=2026-04-10T08%3A00%3A00%2B02%3A00&enddt=2026-04-10T17%3A00%3A00%2B02%3A00&location=R.+P%C3%B6ppel+GmbH+%26+Co.+KG%2C+Alpenstra%C3%9Fe+45%2C+87700+Memmingen&body=Live-Demos%2C+Feuerwehr-Spektakel+und+mehr." target="_blank" class="cal-btn">Outlook</a>
      <a href="https://svenn8n-a11y.github.io/big-red-tour-2026/assets/calendar/big-red-tour-2026.ics" class="cal-btn">ICS-Datei (Apple / Alle)</a>
    </div>
  </div>

  <div class="body-section">
    <p style="font-weight:600;color:#1a1a1a;margin-bottom:12px;">Fragen? Wir sind für dich da:</p>
    <div class="contact-box">
      <div class="contact-name">Sven Müllers &ndash; R. Pöppel GmbH &amp; Co. KG</div>
      <div class="contact-item">&#128231; <a href="mailto:s.muellers@poeppel-wkz.de">s.muellers@poeppel-wkz.de</a></div>
      <div class="contact-item">&#128222; <a href="tel:+4983319559-0">+49 8331 9559-0</a></div>
    </div>
    <p style="text-align:center; margin-top:8px;">
      <a href="https://svenn8n-a11y.github.io/big-red-tour-2026/" class="cta-btn">Zur Eventseite</a>
    </p>
  </div>

  <div class="footer-bar">
    <p>R. Pöppel GmbH &amp; Co. KG &middot; Alpenstraße 45 &middot; 87700 Memmingen</p>
    <p><a href="https://svenn8n-a11y.github.io/big-red-tour-2026/datenschutz.html">Datenschutz</a> &middot; <a href="https://svenn8n-a11y.github.io/big-red-tour-2026/impressum.html">Impressum</a></p>
  </div>

</div>
</body>
</html>';

    $confirmHeaders  = "From: BIG RED TOUR 2026 <noreply@poeppel-wkz.de>" . $eol;
    $confirmHeaders .= "Reply-To: s.muellers@poeppel-wkz.de" . $eol;
    $confirmHeaders .= "Return-Path: noreply@poeppel-wkz.de" . $eol;
    $confirmHeaders .= "MIME-Version: 1.0" . $eol;
    $confirmHeaders .= "Content-Type: text/html; charset=UTF-8" . $eol;
    $confirmHeaders .= "Content-Transfer-Encoding: quoted-printable" . $eol;
    $confirmHeaders .= "X-Mailer: PHP/" . phpversion() . $eol;

    $confirmMailEncoded = quoted_printable_encode($confirmMail);
    mail($email, $confirmSubject, $confirmMailEncoded, $confirmHeaders, '-f noreply@poeppel-wkz.de');
}

// ─── Antwort ─────────────────────────────────────────────────────────────────
if ($mailSent) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Anmeldung erfolgreich']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'E-Mail konnte nicht gesendet werden. Bitte direkt kontaktieren: s.muellers@poeppel-wkz.de']);
}
