# biankakriege/contao-company-data

[English](README.md) | **Deutsch**

`biankakriege/contao-company-data` ist eine Erweiterung für das [Contao CMS][Contao].

Unternehmensdaten und Ansprechpartner an einer Stelle pflegen und in Inhaltselementen, Modulen, Formular-Benachrichtigungen und als strukturierte Daten (schema.org JSON-LD) nutzen. Einmal ändern, und jede Seite zeigt den neuen Stand.

## Installation

Im Contao Manager nach **biankakriege/contao-company-data** suchen oder per Composer installieren:

```bash
composer require biankakriege/contao-company-data
```

## Verwendung

### Unternehmen und Personen

Unternehmen unter **Inhalte › Unternehmensdaten** anlegen: Adresse, Kontaktdaten, Impressum und Logo. Zu jedem Unternehmen lassen sich Personen mit Position, Kontaktdaten, Sprechzeiten und Bild pflegen.

![Unternehmen im Backend](docs/backend_company.png)

![Personen im ackend](docs/backend_person.png)

### Inhaltselemente und Module

- **Kontaktdaten:** Kontaktdaten eines Unternehmens, z. B. für Impressum oder Footer
- **Personen Liste** / **Einzelne Person:** Ansprechpartner eines Unternehmens
- **Logo** und **Firmen Kontaktdaten** als Frontend-Module, z. B. im Seitenlayout

Über **Auswahl Felder** wird pro Element festgelegt, welche Daten erscheinen.

![Inhaltselement mit Feldauswahl](docs/backend_content_element.png)

![Ausgabe im Frontend](docs/frontend_output_company.png)

![Ausgabe im Frontend](docs/frontend_output.png)

### Formulare

In den Formulareinstellungen (**Formulardaten mit Firma anreichern**) lässt sich eine E-Mail-Signatur anhängen oder Platzhalter wie `##form_company_name##` für das Notification Center erzeugen.

### Strukturierte Daten (JSON-LD)

Im Startpunkt (**Unternehmen (JSON-LD)**) ein Unternehmen auswählen, dann wird es auf jeder Seite als `Organization` ausgegeben. Die Inhaltselemente ergänzen das Unternehmen und die angezeigten Personen. Schema-Typ, Beschreibung und Profile (`sameAs`) werden pro Unternehmen gepflegt.

![Unternehmensauswahl im Startpunkt](docs/backend_root_page.png)

Das Ergebnis lässt sich mit dem [Schema Markup Validator](https://validator.schema.org/) prüfen.

## Templates

Alle Templates lassen sich wie üblich überschreiben, z. B. `bk_company_contact.html.twig`, `content-person-list.html.twig` oder `mail_signature.html.twig`.

## Lizenz

LGPL-3.0-or-later

[Contao]: https://contao.org