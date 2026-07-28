# SLA Hero Card

A Zabbix 7.0 dashboard widget module that replaces the stock table-style SLA widgets with a single big-number "hero card" — built for NOC screens and executive dashboards where you want one glanceable metric instead of a table.
*Note that you already configured SLA Zabbix before you put this modules on the dashboards

![status](https://img.shields.io/badge/zabbix-7.0-red) ![license](https://img.shields.io/badge/license-MIT-blue)

<p align="center">
  <img src="https://github.com/user-attachments/assets/1e7df679-c479-4324-b397-6565b0107f4d" width="300" alt="Preview 1" />
  <img src="https://github.com/user-attachments/assets/5eb9e49a-07a0-4c06-b653-784fcc299b22" width="300" alt="Preview 2" />
</p>

## What it does

You pick an SLA definition and one of its Services, and the widget pulls the current SLI% straight from Zabbix's own SLA engine (`sla.getsli`) — not a raw item value, not a manual calculation. It compares that number against a target SLO you define and renders:

- The SLI% in large type, color-coded green (met) or red (breached)
- A status pill (`OK` / `SLA BREACH` / `NO DATA`)
- The target SLO and an optional sub-label underneath

Dark theme, glassmorphic glow on the value, subtle pulse animation on breach and on every data refresh. Meant to sit on a wallboard next to five other cards, each pointed at a different service.

## Requirements

- Zabbix 7.0 LTS (frontend module system, manifest v2)
- An SLA already configured under **Services → SLA**, with at least one Service linked to it via service tags

If you haven't set up SLA/Service monitoring yet, do that first — this widget is a presentation layer on top of it, not a replacement for it.

## Installation

1. Copy the `my_sla_hero_card` folder into your frontend's modules directory:

   ```bash
   cp -r my_sla_hero_card /usr/share/zabbix/modules/
   ```

   (adjust the path if you're running the official Zabbix container images — it's usually `/usr/share/zabbix/modules/` inside the web container)

2. Make sure the files are world-readable by whatever user your PHP process runs as. This bit people constantly:

   ```bash
   find /usr/share/zabbix/modules/my_sla_hero_card -type d -exec chmod 755 {} \;
   find /usr/share/zabbix/modules/my_sla_hero_card -type f -exec chmod 644 {} \;
   ```

3. In Zabbix: **Administration → General → Modules → Scan directory**, then enable "SLA Hero Card".

4. Add it to a dashboard like any other widget.

## Configuration

| Field | What it does |
|---|---|
| Header Title | Label at the top of the card, e.g. "Payment Gateway Uptime" |
| SLA | Which SLA definition to read the schedule/rules from |
| Service | Which service under that SLA to report on |
| Target SLO (%) | The threshold the card compares the live SLI against — intentionally separate from the SLA's own configured SLO, in case you want a tighter internal warning threshold |
| Sub-label / Description | Small text under the target line, e.g. "National Stores \| Monthly" |

Note: the SLA and Service pickers are independent — the widget doesn't filter the Service list down to only services attached to the SLA you picked. If you pick a service that isn't actually linked to that SLA, you'll just get a "NO DATA" card instead of an error, since `sla.getsli` returns nothing for that pairing.

## How it works

Rough data flow per widget refresh:

1. `actions/WidgetView.php` resolves the configured Service's name via `service.get`
2. Calls `sla.getsli` with `periods: 1` (no explicit date range) — Zabbix figures out the current reporting window itself, whether the SLA is daily, weekly, or monthly
3. Compares the returned SLI against your configured target and passes a plain data object to the view
4. `views/widget.view.php` renders it, no business logic in the template itself

No item history, no custom cron, no calculated items required. If Zabbix's SLA report page shows a number for that service, this widget will show the same number.

## File structure

```
my_sla_hero_card/
├── manifest.json           module + widget registration
├── Widget.php               widget class (display name)
├── includes/
│   └── WidgetForm.php        config fields: header, SLA, service, target, sub-label
├── actions/
│   └── WidgetView.php        fetches SLI data, builds the view payload
├── views/
│   ├── widget.edit.php        renders the config form
│   └── widget.view.php        renders the card itself
└── assets/
    ├── css/widget.css          hero card styling, color states, animations
    └── js/class.widget.js      refresh-flash effect, extends core CWidget
```

## Known limitations

- Single service per card — for a fleet view across many services, use the built-in SLA report widget instead and treat this one as a spotlight for the services that matter most
- No historical trend, just the current period's SLI — it's a status card, not a graph
- Target SLO is a flat percentage; there's no support for tiered/multi-threshold coloring

## License

MIT
