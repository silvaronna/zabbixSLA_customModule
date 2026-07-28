<?php declare(strict_types = 0);

namespace Modules\SlaHeroCard;

use Zabbix\Core\CWidget;

/**
 * SLA Hero Card widget.
 *
 * Registers the widget's display name shown in the "Add widget" type
 * dropdown. Kept intentionally thin — all rendering/config logic lives
 * in WidgetForm.php (includes/) and WidgetView.php (actions/), per the
 * standard Zabbix 7.0 module split.
 */
class Widget extends CWidget {

	public function getDefaultName(): string {
		return _('SLA Hero Card');
	}
}
