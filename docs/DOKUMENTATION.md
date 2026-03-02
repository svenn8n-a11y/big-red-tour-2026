# Technische Dokumentation – BIG RED TOUR 2026

> Referenz-Dokumentation für Entwickler. Ergänzt die [README.md](../README.md).

---

## Inhaltsverzeichnis

1. [CSS-Architektur](#css-architektur)
2. [JS-Funktionen-Referenz](#js-funktionen-referenz)
3. [Sektion-Übersicht](#sektion-übersicht)
4. [Bekannte Muster & Eigenheiten](#bekannte-muster--eigenheiten)
5. [Partner-Notizen](#partner-notizen)
6. [Deployment](#deployment)

---

## CSS-Architektur

### Custom Properties (`:root`)

Alle Design-Tokens sind als CSS Custom Properties in `:root` definiert (`css/style.css`):

```css
:root {
  /* Farben */
  --red:        #C8102E;           /* Milwaukee Brand-Rot */
  --red-light:  #E8182F;
  --red-dark:   #9B0C22;
  --red-glow:   rgba(200, 16, 46, 0.35);
  --black:      #080808;
  --bg:         #0D0D0D;           /* Haupt-Hintergrund */
  --bg-alt:     #111111;
  --card:       #161616;
  --card-hover: #1C1C1C;
  --border:     #252525;
  --border-red: rgba(200, 16, 46, 0.3);
  --white:      #F5F5F5;
  --muted:      #888888;
  --muted-light:#AAAAAA;
  --green:      #22C55E;

  /* Typografie */
  --font-head:  'Bebas Neue', 'Barlow Condensed', Impact, sans-serif;
  --font-sub:   'Barlow Condensed', sans-serif;
  --font-body:  'Inter', -apple-system, sans-serif;

  /* Layout */
  --gap:        clamp(3rem, 8vw, 7rem);  /* Sektions-Padding */
  --container:  1280px;                   /* Max-Breite */

  /* Easing */
  --ease:       cubic-bezier(0.16, 1, 0.3, 1);
  --ease-in:    cubic-bezier(0.4, 0, 1, 1);
}
```

### Naming-Konventionen (BEM-ähnlich)

Das Projekt verwendet eine BEM-ähnliche Struktur:

```
.block                  → Komponente
.block__element         → Kindelement
.block--modifier        → Variation/Zustand
```

Beispiele:
- `.gewerk` → Gewerk-Sektion
- `.gewerk__inner` → Grid-Container
- `.gewerk__figure` → Bild-Container
- `.gewerk--reversed` → Bild links (reversed layout)
- `.gewerk--bau` → Bau-spezifische Variante

### Breakpoints

| Breakpoint | Verwendung |
|---|---|
| `860px` | Primärer Tablet/Mobile-Breakpoint (Grid → Single-Column) |
| `768px` | Navigation, kleinere Layouts |
| `480px` | Mobile-only Anpassungen |

### Responsive Spacing

Sämtliche Abstände und Größen nutzen `clamp()` für fluid responsiveness:

```css
font-size: clamp(0.9rem, 1.6vw, 1.05rem);
padding: clamp(4rem, 8vw, 7rem) 0;
width: clamp(140px, 18vw, 240px);
```

---

## JS-Funktionen-Referenz

Alle Funktionen in `js/main.js` (~650 Zeilen). Initialisierung via `DOMContentLoaded`.

### Init-Funktionen (automatisch beim Laden aufgerufen)

| Funktion | Beschreibung |
|---|---|
| `initCountdown()` | Zählt rückwärts bis 10. April 2026, 08:00 CEST. Tage / Stunden / Minuten / Sekunden. |
| `initHeroAnimation()` | GSAP-Timeline für Hero-Einfahrts-Animation (Texte, Logo, CTA). |
| `initScrollReveal()` | Alle `.reveal-up`-Elemente mit GSAP ScrollTrigger animieren (translateY + opacity). |
| `initHeader()` | Sticky Header – wird bei Scroll kleiner, bekommt Backdrop-Blur. |
| `initScrollProgress()` | Rote Fortschritts-Leiste oben auf der Seite (wie Reading-Progress-Bar). |
| `initCustomCursor()` | Custom roter Cursor mit Lag-Effekt (Desktop only). |
| `initMagneticButtons()` | Magnetischer Hover-Effekt auf `.mag-btn` (GSAP). |
| `initTabs()` | Tab-Navigation in `#zielgruppen` (Holz/KFZ/SHK/Bau/Feuerwehr). |
| `initFAQ()` | Accordion in `#faq` – öffnet/schließt auf Click. |
| `initMobileNav()` | Hamburger-Menü für Mobile – öffnet Overlay-Navigation. |
| `initHeroParallax()` | Parallax auf Hero-Truck-Bild via GSAP ScrollTrigger. |
| `initCard3D()` | 3D-Tilt-Effekt auf `.card-3d` (Maus-Position → rotateX/Y). |
| `initForm()` | Anmeldeformular: Validierung + Formspree POST + Erfolgsmeldung. |
| `initMusic()` | Hintergrundmusik-Button (Play/Pause) mit Fade-In/Out. |
| `initErlebnisScroll()` | Scroll-Animation für Erlebnis-Karten. |
| `initGallery()` | Produkt-Galerie-Slider (Pfeil-Navigation + Auto-Scroll). |
| `initDealStamp()` | „Top Secret!"-Stempel-Animation auf Scroll mit `@keyframes stamp-in`. |
| `initHeiglCar()` | Heigl-Auto fährt von links ein (ScrollTrigger + CSS transition). |

### Hilfsfunktionen

| Funktion | Beschreibung |
|---|---|
| `alignLogoToH1()` | Richtet Hero-Logo-Container dynamisch an `#heroLine1` (oben) und `#heroLine2` (unten) aus. Verwendet `getBoundingClientRect()`. Wird bei `resize` neu aufgerufen. |
| `fakeSend(form)` | Simuliert Formular-Submit (Fallback wenn Formspree nicht konfiguriert). |
| `shakeEl(el)` | Kurze CSS-Klassen-basierte Shake-Animation auf einem Element (z.B. bei Validierungsfehler). |

---

## Sektion-Übersicht

### `#hero` – Hero

- **Klassen:** `.hero`, `.hero__content`, `.hero__logo-right`
- **Besonderheit:** Zwei Logos übereinander via `display: flex; flex-direction: column; justify-content: space-between`. Pöppel-Logo oben (Glitch-Effekt), Milwaukee-Logo unten. Höhe dynamisch via `alignLogoToH1()`.
- **Glitch:** `.hero__logo-glitch--a/b` – zwei identische Bilder mit `clip-path` und CSS-Animation.

### `#zielgruppen` – Zielgruppen-Tabs

- **Klassen:** `.tabs`, `.tab-btn`, `.tab-panel`
- **Tabs:** `[data-tab="holz"]`, `[data-tab="kfz"]`, `[data-tab="shk"]`, `[data-tab="bau"]`, `[data-tab="fw"]`
- **Breite:** `flex: 1` → alle Tabs gleichbreit, füllen Container

### `#gewerk-bau` / `#gewerk-kfz` / `#gewerk-zimmermann` – Gewerk-Sektionen

- **Basis-Klasse:** `.gewerk`, `.gewerk__inner` (CSS Grid 2-spaltig)
- **Reversed (Bild links):** `.gewerk--reversed` → `.gewerk__figure { order: -1 }`
- **Bau-spezifisch:** `.gewerk__inner--bau { grid-template-columns: 1.35fr 1fr }` – größere Bildspalte
- **Hintergrund-Deko:** `.gewerk__bg-deco` – absolut positioniertes SVG-Watermark

### `#highlights` – Highlights-Divider

- **Klasse:** `.highlights-divider`
- **Clip-Path:** `polygon(0 8%, 100% 0%, 100% 92%, 0% 100%)` – diagonale Kanten
- **Hintergrundtext:** `.highlights-divider__bg-text` – riesiges „LIVE" mit 6% Opacity

### `#deals` – Bucket Deals

- **Stempel:** `#dealsStamp` mit `.deals__stamp.is-stamped` → `@keyframes stamp-in`
- **Animation-Ablauf:** `scale(2.4) → scale(0.9) → scale(1.05) → scale(1)` + Rotation -22°

### `#heigl` – Heigl Meatmaster

- **Auto-Animation:** `#heiglCarWrap` – absolut positioniert, `bottom: 0; left: 0`
- **Einfahrt:** `transform: translateX(-105%)` → `.is-visible { translateX(0) }` via ScrollTrigger
- **Kein sichtbarer Rahmen:** Section hat `overflow: hidden`, Auto liegt außerhalb des Grids
- **Platz reservieren:** `.heigl__inner` hat `padding-left: clamp(240px, 36vw, 520px)`

---

## Bekannte Muster & Eigenheiten

### RGB-PNG ohne Alpha-Kanal (Logos auf dunklem Hintergrund)

**Problem:** `filter: brightness(0) invert(1)` funktioniert nur bei PNGs mit Alpha-Kanal (RGBA).
Bei RGB-PNGs (kein Alpha) wird jeder Pixel zu #000 (brightness 0), dann weiß (invert) → weißes Rechteck.

**Lösung:** `filter: invert(1)` allein – invertiert Farben ohne Alpha-Kanal zu benötigen.

Betroffene Datei: `assets/images/food/der_schachen_logo.png` (RGB, 800×500px)

```css
/* FALSCH für RGB-PNGs: */
filter: brightness(0) invert(1);  /* → weißes Rechteck */

/* RICHTIG für RGB-PNGs: */
filter: invert(1);  /* → korrekte Invertierung */
```

### Auto-Animation ohne sichtbaren Rahmen

**Problem:** Auto in einem Grid-Container mit `overflow: hidden` zeigt die Spaltengrenze als sichtbaren Rahmen.

**Lösung:** Auto aus dem Grid herausnehmen, absolut auf der Section positionieren:

```css
.heigl { position: relative; overflow: hidden; }
.heigl__car-wrap {
  position: absolute;
  bottom: 0; left: 0;
  transform: translateX(-105%);
  transition: transform 1.9s cubic-bezier(0.22, 1, 0.36, 1);
}
.heigl__car-wrap.is-visible { transform: translateX(0); }
/* Grid-Container hat padding-left um Platz zu lassen */
.heigl__inner { padding-left: clamp(240px, 36vw, 520px); }
```

### Hero-Logo dynamisch an Headline ausrichten

Zwei Logos sollen exakt von der Oberkante von „BIG" bis zur Unterkante von „TOUR" reichen:

```javascript
function alignLogoToH1() {
  const line1 = document.getElementById('heroLine1');  // "BIG RED"
  const line2 = document.getElementById('heroLine2');  // "TOUR 2026"
  const logoWrap = document.getElementById('heroPoeppelLogo');
  const parent = logoWrap.offsetParent;
  const parentRect = parent.getBoundingClientRect();
  logoWrap.style.top    = (line1.getBoundingClientRect().top - parentRect.top) + 'px';
  logoWrap.style.height = (line2.getBoundingClientRect().bottom - line1.getBoundingClientRect().top) + 'px';
}
window.addEventListener('resize', alignLogoToH1, { passive: true });
```

### Stamp-Animation (`@keyframes stamp-in`)

Rubber-Stamp-Effekt für das „Top Secret!"-Badge im Deals-Bereich:

```css
.deals__stamp { transform: translateY(-50%) rotate(-22deg) scale(0); opacity: 0; }
.deals__stamp.is-stamped { animation: stamp-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) forwards; }

@keyframes stamp-in {
  0%   { transform: translateY(-50%) rotate(-22deg) scale(2.4); opacity: 0; }
  60%  { transform: translateY(-50%) rotate(-22deg) scale(0.9); opacity: 0.9; }
  80%  { transform: translateY(-50%) rotate(-22deg) scale(1.05); opacity: 1; }
  100% { transform: translateY(-50%) rotate(-22deg) scale(1); opacity: 1; }
}
```

### `drop-shadow` vs `box-shadow` für freigestellte Bilder

Für Bilder mit Alpha-Kanal (RGBA PNG) gibt `filter: drop-shadow()` einen Schatten der der
tatsächlichen Form folgt. `box-shadow` würde immer das Rechteck des Bild-Elements umranden.

```css
/* Schatten folgt der Figur (nicht dem Rechteck): */
filter: drop-shadow(0 12px 50px rgba(0,0,0,0.85))
        drop-shadow(-10px 0 50px rgba(200,16,46,0.22));
```

---

## Partner-Notizen

### Der Schachen
- **Rolle:** Kostenlose Verpflegung für alle Event-Besucher
- **Angebot:** Getränke, Snacks, Verpflegung
- **Logo:** `assets/images/food/der_schachen_logo.png` (RGB, kein Alpha → `filter: invert(1)`)
- **Sektion:** `#food`

### Metzgerei Heigl (Benningen im Allgäu)
- **Slogan:** „Dein Meatmaster aus Benningen im Allgäu"
- **Angebot:** Allgäuer Chorizo, Dry Aged Ribeye, Spare Ribs, selbst gemachte Fleischspezialitäten
- **Basis:** Selbstzahler (kostenpflichtig)
- **Logo:** `assets/images/food/Heigl_logo.png`
- **Auto:** `assets/images/food/heigl_Auto.png` (RGBA – Alpha vorhanden, drop-shadow funktioniert)
- **Stand:** `assets/images/food/Heigl_Stand.png`
- **Sektion:** `#heigl`

---

## Deployment

### GitHub Pages

Die Seite wird automatisch deployed wenn ein Push auf `main` erfolgt.

- **Workflow:** `.github/workflows/` (automatisch von GitHub generiert)
- **Action:** `pages-build-deployment`
- **Dauer:** ~1–2 Minuten nach dem Push

### Deployment manuell triggern

Falls die Action nicht automatisch startet: leeren Commit pushen:

```bash
git commit --allow-empty -m "ci: retrigger GitHub Pages deployment"
git push
```

### Wichtige Git-Befehle für dieses Projekt

```bash
# Status prüfen
git status
git log --oneline -10

# Bilder hinzufügen (Ordner)
git add assets/images/food/
git add assets/images/gewerke/

# Deployment-Tag setzen
git tag -a v1.0-backup-YYYY-MM-DD -m "Backup-Beschreibung"
git push origin v1.0-backup-YYYY-MM-DD
```

---

## Offene technische TODOs

- [ ] **Formspree ID** – `YOUR_FORM_ID` in `index.html` ~Zeile 334 ersetzen
- [ ] **Video-Thumbnail** – YouTube Embed kann durch eigenes Thumbnail ersetzt werden
- [ ] **Performance** – Große Bilder (truck_hd.png, gewerke/) könnten als WebP optimiert werden
- [ ] **Meta OG-Image** – `assets/images/truck_hd.png` als OG-Image (ideal: 1200×630px)
- [ ] **Holz-Sektion** – `assets/images/gewerke/holz.png` vorhanden, aber noch keine eigene Sektion

---

*Zuletzt aktualisiert: März 2026*
