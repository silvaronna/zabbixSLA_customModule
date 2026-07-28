<?php declare(strict_types = 0);

/**
 * SLA Hero Card widget form view.
 *
 * @var CView $this
 * @var array $data
 */

(new CWidgetFormView($data))
	->addField(new CWidgetFieldTextBoxView($data['fields']['header_title']))
	->addField(new CWidgetFieldMultiSelectSlaView($data['fields']['slaid']))
	->addField(new CWidgetFieldMultiSelectServiceView($data['fields']['serviceid']))
	->addField(new CWidgetFieldNumericBoxView($data['fields']['target_slo']))
	->addField(new CWidgetFieldTextBoxView($data['fields']['sub_label']))
	->show();
