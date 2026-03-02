# Milwaukee BIG RED TOUR 2026 – Pöppel Memmingen

> Offizielle Event-Landingpage für die Milwaukee **BIG RED TOUR 2026** beim Fachhändler **Pöppel** in **Memmingen** (Allgäu).

---

## Das Event

| | |
|---|---|
| **Datum** | 10. April 2026 |
| **Uhrzeit** | 08:00 – 17:00 Uhr |
| **VIP Stammkunden** | 08:00 – 09:00 Uhr (exklusiv) |
| **Ort** | R. Pöppel GmbH & Co. KG · Alpenstraße 45 · 87700 Memmingen |
| **Eintritt** | Kostenlos |

Der Milwaukee **BIG RED TRUCK** kommt direkt nach Memmingen. Live-Demos, exklusive Bucket-Deals, Feuerwehr-Spektakel, Gregor Prinz Live-Demo und kulinarisches Rahmenprogramm mit „Der Schachen".

---

## Live-URL

**https://svenn8n-a11y.github.io/big-red-tour-2026/**

---

## Tech-Stack

| Technologie | Einsatz |
|---|---|
| HTML5 / CSS3 / Vanilla JS | Gesamte Landingpage |
| [GSAP 3.12](https://gsap.com/) + ScrollTrigger | Animationen, Parallax, Scroll-Trigger |
| PHP `mail()` (Pöppel-Server) | Anmeldeformular → `submit-anmeldung.php` |
| [bunny.net Fonts](https://fonts.bunny.net/) | Bebas Neue · Barlow Condensed · Inter (DSGVO-konform, EU-Server) |
| GitHub Pages | Hosting & Deployment |

Kein Build-System, keine Dependencies, keine Node.js – reines HTML/CSS/JS.

---

## Formular-Setup

Das Anmeldeformular (`index.html`) sendet via **POST** an `submit-anmeldung.php`, das auf dem Pöppel-Server liegt. Die PHP-Datei muss dort hochgeladen werden (nicht Teil von GitHub Pages).

**Empfänger:** `support@poeppel-wkz.de` · `s.muellers@poeppel-wkz.de`

```
submit-anmeldung.php → auf Pöppel-Server hochladen
index.html (form action) → zeigt bereits auf submit-anmeldung.php
```

---

## Seitenstruktur

Die Landingpage besteht aus mehreren Sektionen sowie eigenen rechtlichen Unterseiten:

| # | Sektion | ID | Beschreibung |
|---|---|---|---|
| 1 | **Hero** | `#hero` | Vollbild-Intro mit Parallax, Glitch-Logos, Live-Countdown |
| 2 | **Ticker** | _(Banner)_ | Roter Auto-Scroll-Incentive-Banner |
| 3 | **Event Strip** | `#event-strip` | Kompakte Event-Daten (Datum, Zeit, Ort, Eintritt) |
| 4 | **Erlebnis** | `#erlebnis` | 3 Karten mit 3D-Hover (Live-Demos / Feuerwehr / Deals) |
| 5 | **Zielgruppen** | `#zielgruppen` | Tab-Navigation: Holz / KFZ / SHK / Bau / Feuerwehr |
| 6 | **Gallery** | `#gallery` | Milwaukee Produktwelten – Bild-Slider |
| 7 | **Bau-Gewerk** | `#gewerk-bau` | Hoch- & Tiefbau – Bild links, Text rechts |
| 8 | **KFZ-Gewerk** | `#gewerk-kfz` | KFZ-Werkstätten & Autohäuser |
| 9 | **Zimmermann-Gewerk** | `#gewerk-zimmermann` | Zimmerer, Holzbau & Schreinerei |
| 10 | **Highlights-Divider** | `#highlights` | Roter Trenner „Das Live-Programm" mit Clip-Path |
| 11 | **Feuerwehr-Highlight** | `#highlight` | Feuerwehr schneidet Live ein Auto auf |
| 12 | **KFZ Pit-Stop** | `#pitstop` | KFZ-Profis batteln sich beim Pit-Stop |
| 13 | **Gregor Prinz** | `#georg` | Forstwirtschaft Live-Demo im Allgäu |
| 14 | **SHK Aktionen** | `#shk` | Sanitär, Heizung, Klima |
| 15 | **Deals** | `#deals` | Bucket-Deal-Stufen (250 / 500 / 1000 €) + Top-Secret-Stempel |
| 16 | **Video** | `#video-sec` | YouTube-Embed (youtube-nocookie.com, DSGVO-konform) |
| 17 | **Food – Der Schachen** | `#food` | Kostenlose Verpflegung durch Der Schachen |
| 18 | **Food – Heigl** | `#heigl` | Fleisch-Spezialitäten (Selbstzahlerbasis) |
| 19 | **Wetterfest** | `#wetterfest` | Das Event findet bei jedem Wetter statt |
| 20 | **Anmeldung** | `#anmeldung` | Kostenlose Anmeldung – PHP-Mailer |
| 21 | **FAQ** | `#faq` | Häufige Fragen – Accordion |

---

## Rechtliche Unterseiten

| Datei | Inhalt |
|---|---|
| `datenschutz.html` | DSGVO-konforme Datenschutzerklärung |
| `impressum.html` | Impressum gem. § 5 TMG |
| `agb.html` | Event-Teilnahmebedingungen |

---

## E-Mail-Templates

| Datei | Beschreibung |
|---|---|
| `email-templates/bestaetigung-kunde.html` | Vorschau: Bestätigungs-E-Mail an Teilnehmer |
| `email-templates/intern-benachrichtigung.html` | Vorschau: Interne Benachrichtigung (Pöppel-Team) |

Die tatsächlich versendeten E-Mails werden von `submit-anmeldung.php` generiert (gleicher Inhalt).

---

## Dateistruktur

```
big-red-tour-2026/
├── index.html                        Haupt-Landingpage
├── datenschutz.html                  Datenschutzerklärung (DSGVO)
├── impressum.html                    Impressum (§ 5 TMG)
├── agb.html                          Teilnahmebedingungen
├── submit-anmeldung.php              PHP-Mailer (auf Pöppel-Server hochladen!)
├── css/
│   └── style.css                     Alle Styles
├── js/
│   └── main.js                       Animationen, Interaktionen, Cookie-Banner
├── email-templates/
│   ├── bestaetigung-kunde.html       E-Mail-Vorschau Kunde
│   └── intern-benachrichtigung.html  E-Mail-Vorschau intern
├── assets/
│   ├── images/
│   │   ├── logo_poeppel.svg / .png
│   │   ├── Milwaukee-Symbol-500x281.png
│   │   ├── favicon_poeppel.svg
│   │   ├── truck_hd.png
│   │   ├── gewerke/                  Bilder je Gewerk
│   │   ├── food/                     Catering-Bilder
│   │   └── gallery/                  Produkt-Galerie-Bilder
│   └── music/
│       └── rockmusik-BIG-RED.mp3
├── docs/
│   └── DOKUMENTATION.md             Technische Referenz-Dokumentation
├── Konzepte/                         Briefings, Marketing-Ideen
├── Recherchen/                       Research & Best Practices
└── README.md                         Diese Datei
```

---

## Setup & lokale Vorschau

Kein Build-Prozess nötig. Einfach `index.html` im Browser öffnen:

```bash
# Mit Python HTTP-Server (empfohlen für korrekte MIME-Types)
python3 -m http.server 8080
# → http://localhost:8080

# Oder direkt in VS Code mit der Live Server Extension
```

---

## Deployment

Die Seite wird automatisch via **GitHub Actions** auf **GitHub Pages** deployed.

- Branch: `main`
- Action: `pages-build-deployment` (automatisch bei jedem Push)
- URL: `https://svenn8n-a11y.github.io/big-red-tour-2026/`

> **Hinweis:** `submit-anmeldung.php` funktioniert **nicht** auf GitHub Pages (statisch).
> Die PHP-Datei muss auf dem Webserver von Pöppel (`poeppel-wkz.de`) hochgeladen werden.

---

## Partner & Catering

| Partner | Info |
|---|---|
| **Der Schachen** | Kostenlose Verpflegung – 4 Freigetränke pro Gast inklusive, Barbecue-Spezialitäten & kulinarische Schmankerl |
| **Metzgerei Heigl** | Fleisch-Spezialitäten aus Benningen im Allgäu (Selbstzahlerbasis) |

---

## Offene TODOs

- [ ] **submit-anmeldung.php auf Pöppel-Server hochladen** – damit das Formular live funktioniert
- [ ] **Bucket Deal Goodies** – Prämien für 250 / 500 / 1000 € konkretisieren
- [ ] **Feuerwehr-Zeitslot** – genaue Uhrzeit im Programm
- [ ] **Milwaukee Collab** – Listung auf Milwaukee-Website beantragen
- [ ] **Custom Domain** – optional

---

## Design-System

| Element | Wert |
|---|---|
| **Milwaukee Rot** | `#C8102E` |
| **Hintergrund** | `#0D0D0D` |
| **Headline-Font** | Bebas Neue (via bunny.net) |
| **Sub-Font** | Barlow Condensed (via bunny.net) |
| **Body-Font** | Inter (via bunny.net) |
| **Max-Container** | 1280px |

---

## Technische Dokumentation

Detaillierte CSS-Architektur, JS-Funktionen-Referenz und Sektion-für-Sektion-Übersicht:

**[docs/DOKUMENTATION.md](docs/DOKUMENTATION.md)**

---

*Erstellt für Pöppel Memmingen · Milwaukee BIG RED TOUR 2026 · Allgäu*
