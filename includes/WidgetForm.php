<?php declare(strict_types = 0);

namespace Modules\SlaHeroCard\Includes;

use Zabbix\Widgets\CWidgetForm;
use Zabbix\Widgets\CWidgetField;

use Zabbix\Widgets\Fields\{
	CWidgetFieldTextBox,
	CWidgetFieldNumericBox,
	CWidgetFieldMultiSelectSla,
	CWidgetFieldMultiSelectService
};

/**
 * SLA Hero Card widget form.
 *
 * Field-to-DB serialization/validation is handled entirely by the
 * CWidgetField subclasses below. Data source follows the native
 * Zabbix flow: SLA definition -> Service (child of a host group under
 * that SLA) -> sla.getsli() for the computed SLI, NOT a raw Item value.
 */
class WidgetForm extends CWidgetForm {

	public function addFields(): self {
		return $this
			->addField(
				(new CWidgetFieldTextBox('header_title', _('Header Title')))
					->setDefault(_('Service Availability'))
					->setMaxLength(255)
			)
			->addField(
				(new CWidgetFieldMultiSelectSla('slaid', _('SLA')))
					// The SLA definition that owns the schedule (daily/
					// weekly/monthly) and the SLO the service is measured
					// against.
					->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
					->setMultiple(false)
			)
			->addField(
				(new CWidgetFieldMultiSelectService('serviceid', _('Service')))
					// The specific Service node (e.g. a host-group child
					// under the SLA) to report the SLI for.
					->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
					->setMultiple(false)
			)
			->addField(
				(new CWidgetFieldNumericBox('target_slo', _('Target SLO (%)')))
					->setDefault('99.50')
					->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
			)
			->addField(
				(new CWidgetFieldTextBox('sub_label', _('Sub-label / Description')))
					->setDefault('')
					->setMaxLength(255)
			);
	}
}
