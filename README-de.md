# === Einfaches Scroll to Top ===

# Struktur und Allgemeines

Dieses Plugin fügt der Website einen anpassbaren "Scroll to Top"-Button hinzu, der den Benutzern ermöglicht, schnell wieder zum Seitenanfang zu gelangen. Es erfordert das [CMB2](https://wordpress.org/plugins/cmb2/) Plugin für Konfigurationsoptionen.

```
wp-content
└── plugins
    └── simple-scroll-to-top
        ├── simple-scroll-to-top.php
        ├── css
        │   └── sstt-style.css
        ├── js
        │   └── sstt-script.js
        └── readme.md
```

## Installation

1. **Plugin herunterladen**: Klone das Repository von [GitHub](https://github.com/bugsplat404/wp-plugin-sstt) herunter.

2. **In WordPress hochladen**: Lade den Ordner `simple-scroll-to-top` in das Verzeichnis `/wp-content/plugins/`.

3. **Erforderliches Plugin installieren**: Installiere und aktiviere das [CMB2](https://wordpress.org/plugins/cmb2/) Plugin, das für die Konfigurationsoptionen benötigt wird.

4. **Plugin aktivieren**: Gehe im WordPress-Admin-Bereich zu **Plugins** > **Installierte Plugins** und aktiviere **Simple Scroll To Top**.

## Konfiguration

Die Konfiguration des Plugins kann wie folgt durchgeführt werden:

1. Navigiere zu **Einstellungen** > **Simple Scroll To Top** im WordPress-Admin-Bereich.

2. Passe die folgenden Einstellungen nach deinen Wünschen an:

   - **📍 Position**
   - **🎨 Button-Farbe**
   - **✍️ Hover-Farbe**
   - **📏 Größe (px)**
   - **⭕ Form**
   - **🖼️ Icon**
   - **🛫 Scroll-Geschwindigkeit (ms)**
   - **👁️ Anzeige-Schwelle (px)**
   - **↕️ Abstand zum unteren Rand (px)**
   - **↔️ Seitenabstand (px)**
   - **📝 Tooltip-Text**
   - **💥 Animationseffekt**
   - **📱 Auf mobilen Geräten ausblenden**
   - **🗂️ Z-Index**

3. **Änderungen speichern**: Klicke auf **Änderungen speichern**, um die Einstellungen anzuwenden.

# Code Erklärung

Das WP Plugin ist in folgende 3 Komponenten aufgebaut:
- 1. sstt-style.css
Die Style-Datei definiert den Standart und allgemeinen Style des Scroll Buttons.
In diesem Fall war die Vorgabe, dass der Button Fixiert sein soll, was mit dem Attribut "position: fixed;" realisiert wurde.
```css
	...
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9999;
```
Zudem ist der Z-Index wichtig, damit der Button über den anderen Seitenelementen angezeigt wird.

- 2. sstt-script.js
Die JavaScript-Datei ist dafür zuständig, mit jQuery einen ScrollListener hinzuzufügen, um zur richtigen Zeit den Button ein- bzw. auszublenden.
Dies geschieht sobald der scrollTop Wert (Distanz die das Fenster gescrolled wurde in px) dem in den Einstellungen festgesetzten Schwellwert überschreitet.
```js
$(window).scroll(function() {
	if ($(window).scrollTop() > parseInt(sstt_vars.display_threshold)) {
	...
```
Durch das Klicken des "Scroll Top" Buttons wird dann der scrollTop Wert auf 0 gesetzt. Durch die jQuery Animation mit der Duration von 800ms wird ein "Smoother" Effekt erzeugt.

- 3. simple-scroll-to-top.php
Die Kernkomponente ist die eigentliche Konfigurationsdatei. Sie ist in folgende Teile untergliedert:

### 1. Check ob das benötigte CMB2 Plugin installiert ist
```php
  // Check if CMB2 Plugin is installed
  if ( file_exists( WP_PLUGIN_DIR . '/cmb2/init.php' ) ) {
      require_once WP_PLUGIN_DIR . '/cmb2/init.php';
  } else {
      // Show error if CMB2 is not installed
      add_action( 'admin_notices', 'sstt_cmb2_notice' );
      function sstt_cmb2_notice() {
          echo '<div class="error"><p>SSTT requires the CMB2 Plugin to work. Please install it.</p></div>';
      }
      return;
  }
```

### 2. Enqueue_Scripts Funktion
Die Funktion sstt_enqueue_scripts lädt die erforderlichen CSS- und JavaScript-Dateien für eine "Scroll to Top"-Schaltfläche in WordPress und konfiguriert deren Darstellung und Verhalten basierend auf den Benutzeroptionen.
Hierfür werden zunächst die vordefinierten Styles und Scripts von Wordpress zur Queue hinzugefügt, damit sie im Frontend geladen werden können.
Anschließend werden die eigenen Styles wie Position, Farbe, Icon etc. definiert. 
In diesem Fall habe ich mich für Inline-Styles entschieden, da hierdurch einfach und dynamisch die Eigenschaften des Buttons jederzeit ohne viel Aufwand verändert werden kann.
"wp_localize_script" Überträgt die PHP Daten an das JS Skript, um die serverseitige Optionen für die Nutzung im JavaScript verfügbar zu machen
```php
function sstt_enqueue_scripts() {
	wp_enqueue_style( ... );
	wp_enqueue_script( ... );
	...
    wp_add_inline_style( 'sstt-style', $custom_css );
	...
	wp_localize_script( ... );
	
```

### 3. Hinzufügen des Scroll Buttons
Der Scroll Button wird mittels echo in die HTML-Datei ausgegeben.
Die besondere Herausforderung war hierbei ein eigenes Icon mittels "wp_get_attachment_image_src" zu laden.
Dies muss ggf. genauer angepasst werden, da bei custom Icons die padding Abstände zum Rand nicht immer stimmen und nur .png/.jpg Dateien verwendet werden können.
```php
function sstt_add_button() {
	...
    echo '<div id="scrollToTop" title="' . esc_attr( $tooltip_text ) . '">' . $icon_html . '</div>';
```

### 4. Optionsmenu wird im CMB2 Admin Menu registriert
Zum Schluss werden alle Einstellungen zum Admin Menu hinzugefügt, dies passiert mit der Funktion sstt_register_settings.
Das CMB2-Plugin ermöglicht es einfache Auswahlfelder, Color Picker, etc. hinzuzufügen indem man die Methode "add_field" aus der CMB2-Bibliothek aufruft.
```php
function sstt_register_settings() {
    $cmb->add_field( array(
	...
```

# Weiteres

## Herausforderungen
Da ich bisher hauptsächlich WP Plugins verwendet, aber noch nie eines entwickelt habe, war die erste große Herausforderung zu verstehen, wie diese aufgebaut sind.
Nachdem man die Struktur verstanden hat, war dies jedoch kein allzu großes Problem.
Die wirkliche Herausforderung kam mit den variablen Feldern und CMB2.
Da ich nicht direkt eine Lösung gefunden habe, wie die Variablen an CSS übertragen werden können (bei JS geht das ja mit wp_localize_script),
versuchte ich zunächst verschiedene Style Klassen und CSS Variablen zu verwenden.
Allerdings bin ich dann auf Inline-Styles gestoßen, mit denen das direkte Bearbeiten der Styles in PHP einfacher wurde.
Bei größeren Projekten bzw. mehr Design Optionen könnte dies jedoch zu unübersichtlich werden, weshalb der Ansatz mit den CSS Variablen möglicherweise besser passen könnte.

## Anforderungen

- **WordPress-Version**: Getestet auf Version 6.6.2.
- **PHP-Version**: Getestet auf PHP 8.2.
- **Erforderliches Plugin**: [CMB2](https://wordpress.org/plugins/cmb2/).

![Config Image](https://github.com/bugsplat404/wp-plugin-sstt/blob/main/doc-img.png "Config Image")

## Zukünftige Verbesserungen

- **Zusätzliche Icon-Bibliotheken**: Integration beliebter Icon-Bibliotheken für mehr Icon-Auswahl.
- **Mehrsprachige Unterstützung**: Lokalisierung für mehrere Sprachen hinzufügen.
- **Erweiterte Animationen**: Mehr Animationseffekte mit CSS3 oder JavaScript-Bibliotheken anbieten.
- **Benutzerdefiniertes CSS**: Nutzern erlauben, eigenes CSS direkt auf der Einstellungsseite zu ergänzen.


Github Url: [GitHub-Repository](https://github.com/bugsplat404/wp-plugin-sstt).
