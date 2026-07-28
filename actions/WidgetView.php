<?php declare(strict_types = 0);

namespace Modules\SlaHeroCard\Actions;

use API;
use CControllerDashboardWidgetView;
use CControllerResponseData;

class WidgetView extends CControllerDashboardWidgetView {

	protected function doAction(): void {
		$slaid = $this->fields_values['slaid'];
		$serviceid = $this->fields_values['serviceid'];
		$target_slo = (float) $this->fields_values['target_slo'];
		if (is_array($slaid)) {
	    	$slaid = reset($slaid) ?: null;
		}

		if (is_array($serviceid)) {
		    $serviceid = reset($serviceid) ?: null;
		}
		$sli_value = null;
		$service_name = '';

		if ($slaid && $serviceid) {
			$services = API::Service()->get([
				'output' => ['serviceid', 'name'],
				'serviceids' => [$serviceid],
				'preservekeys' => true
			]);

			if ($services) {
				$service_name = $services[$serviceid]['name'];

				// periods=1 with no period_from/period_to returns just the
				// single most recent reporting period, sized to whatever
				// schedule (daily/weekly/monthly) is configured on the SLA
				// itself — we don't need to compute timestamps ourselves.
				$sli_result = API::Sla()->getsli([
					'slaid' => $slaid,
					'serviceids' => [$serviceid],
					'periods' => 1
				]);

				// Only one service was requested, so it's always index 0 in
				// both dimensions regardless of the API's (unspecified)
				// internal ordering.
				if ($sli_result && isset($sli_result['sli'][0][0]['sli'])) {
					$sli_value = (float) $sli_result['sli'][0][0]['sli'];
				}
			}
		}

		$is_breach = ($sli_value !== null && $sli_value < $target_slo);

		$this->setResponse(new CControllerResponseData([
			'name' => $this->getInput('name', $this->widget->getName()),
			'header_title' => $this->fields_values['header_title'],
			'sub_label' => $this->fields_values['sub_label'],
			'service_name' => $service_name,
			'sli_value' => $sli_value,
			'target_slo' => $target_slo,
			'is_breach' => $is_breach,
			'user' => [
				'debug_mode' => $this->getDebugMode()
			]
		]));
	}
}
