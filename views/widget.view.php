<?php declare(strict_types = 0);

/**
 * SLA Hero Card widget view.
 *
 * @var CView $this
 * @var array $data
 */

$has_data = ($data['sli_value'] !== null);

if (!$has_data) {
	$status_class = 'sla-hero-nodata';
	$badge_icon = '⚪';
	$badge_text = _('NO DATA');
	$sli_display = _('N/A');
}
elseif ($data['is_breach']) {
	$status_class = 'sla-hero-breach';
	$badge_icon = '🔴';
	$badge_text = _('SLA BREACH');
	$sli_display = number_format($data['sli_value'], 2).'%';
}
else {
	$status_class = 'sla-hero-ok';
	$badge_icon = '🟢';
	$badge_text = _('OK');
	$sli_display = number_format($data['sli_value'], 2).'%';
}

$footer = (new CDiv())->addClass('sla-hero-footer');

$footer->addItem(
	(new CSpan(_s('Target: %1$s%%', number_format($data['target_slo'], 2))))->addClass('sla-hero-target')
);

if ($data['sub_label'] !== '') {
	$footer->addItem(
		(new CSpan($data['sub_label']))->addClass('sla-hero-sublabel')
	);
}

$card = (new CDiv())
	->addClass('sla-hero-card')
	->addClass($status_class)
	->addItem(
		(new CDiv($data['header_title']))->addClass('sla-hero-header')
	)
	->addItem(
		(new CDiv($sli_display))->addClass('sla-hero-value')
	)
	->addItem(
		(new CDiv([$badge_icon, ' ', $badge_text]))->addClass('sla-hero-badge')
	)
	->addItem($footer);

(new CWidgetView($data))
	->addItem($card)
	->show();
