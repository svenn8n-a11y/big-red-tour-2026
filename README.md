# Milwaukee BIG RED TOUR 2026 – Pöppel Memmingen

> Offizielle Event-Landingpage für die Milwaukee **BIG RED TOUR 2026** beim Fachhändler **Pöppel** in **Memmingen** (Allgäu).

---

## Das Event

| | |
|---|---|
| **Datum** | 10. April 2026 |
| **Uhrzeit** | 08:00 – 17:00 Uhr |
| **VIP Stammkunden** | 08:00 – 09:00 Uhr (exklusiv) |
| **Ort** | Pöppel, Memmingen (Allgäu, Bayern) |
| **Eintritt** | Kostenlos |

Der Milwaukee **BIG RED TRUCK** kommt direkt nach Memmingen. Live-Demos, exklusive Bucket-Deals, Feuerwehr-Spektakel, Gregor Prinz Live-Demo und kulinarisches Rahmenprogramm.

---

## Live-URL

**https://svenn8n-a11y.github.io/big-red-tour-2026/**

---

## Tech-Stack

| Technologie | Einsatz |
|---|---|
| HTML5 / CSS3 / Vanilla JS | Gesamte Landingpage |
| [GSAP 3.12](https://gsap.com/) + ScrollTrigger | Animationen, Parallax, Scroll-Trigger |
| [Formspree](https://formspree.io/) | Anmeldeformular (Platzhalter: `YOUR_FORM_ID`) |
| Google Fonts | Bebas Neue · Barlow Condensed · Inter |
| GitHub Pages | Hosting & Deployment |

Kein Build-System, keine Dependencies, keine Node.js – reines HTML/CSS/JS.

---

## Seitenstruktur

Die Landingpage besteht aus 21 Sektionen:

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
| 16 | **Video** | `#video-sec` | YouTube-Embed: Was dich im Truck erwartet |
| 17 | **Food – Der Schachen** | `#food` | Kostenlose Verpflegung durch Der Schachen |
| 18 | **Food – Heigl** | `#heigl` | Fleisch-Spezialitäten (Selbstzahlerbasis) |
| 19 | **Wetterfest** | `#wetterfest` | Das Event findet bei jedem Wetter statt |
| 20 | **Anmeldung** | `#anmeldung` | Kostenlose Anmeldung via Formspree |
| 21 | **FAQ** | `#faq` | Häufige Fragen – Accordion |

---

## Dateistruktur

```
big-red-tour-2026/
├── index.html                  Haupt-Landingpage (~1430 Zeilen)
├── css/
│   └── style.css               Alle Styles (~3000 Zeilen)
├── js/
│   └── main.js                 Animationen & Interaktionen (~650 Zeilen)
├── assets/
│   ├── images/
│   │   ├── logo_poeppel_NEU.svg
│   │   ├── MILWAUKEE_TOOL_LOGO-50.svg
│   │   ├── truck_hd.png
│   │   ├── gewerke/            Bilder je Gewerk (bau.png, zimmermann.png, holz.png)
│   │   ├── food/               Catering-Bilder (Heigl, Der Schachen)
│   │   └── gallery/            Produkt-Galerie-Bilder & Videos
│   └── music/
│       └── rockmusik-BIG-RED.mp3
├── docs/
│   └── DOKUMENTATION.md       Technische Referenz-Dokumentation
├── Konzepte/                   Briefings, SEO-Mapping, Marketing-Ideen
├── Recherchen/                 Research & Best Practices
└── README.md                   Diese Datei
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

---

## Partner & Catering

| Partner | Info |
|---|---|
| **Der Schachen** | Kostenlose Verpflegung (Getränke, Snacks) |
| **Metzgerei Heigl** | Fleisch-Spezialitäten aus Benningen im Allgäu (Selbstzahlerbasis) – Allgäuer Chorizo, Dry Aged Ribeye, Spare Ribs |

---

## Offene TODOs

- [ ] **Formspree Form-ID einsetzen** – `YOUR_FORM_ID` in `index.html` (Zeile ~334) durch echte ID ersetzen
- [ ] **Bucket Deal Goodies** – Prämien für 250 / 500 / 1000 € konkretisieren
- [ ] **Google Maps Link** – Händler-Adresse + Karte im Footer
- [ ] **Datenschutz & Impressum** – separate Seiten anlegen
- [ ] **Feuerwehr-Zeitslot** – genaue Uhrzeit im Programm
- [ ] **Milwaukee Collab** – Listung auf Milwaukee-Website beantragen

---

## Design-System

| Element | Wert |
|---|---|
| **Milwaukee Rot** | `#C8102E` |
| **Hintergrund** | `#0D0D0D` |
| **Headline-Font** | Bebas Neue |
| **Sub-Font** | Barlow Condensed |
| **Body-Font** | Inter |
| **Max-Container** | 1280px |

---

## Technische Dokumentation

Detaillierte CSS-Architektur, JS-Funktionen-Referenz und Sektion-für-Sektion-Übersicht:

**[docs/DOKUMENTATION.md](docs/DOKUMENTATION.md)**

---

*Erstellt für Pöppel Memmingen · Milwaukee BIG RED TOUR 2026 · Allgäu*
