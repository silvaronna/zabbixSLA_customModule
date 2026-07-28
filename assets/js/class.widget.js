/**
 * SLA Hero Card widget client-side class.
 *
 * Data fetching and status-color decisions are done entirely server-side
 * (actions/WidgetView.php) — the base CWidget lifecycle already re-requests
 * widget.my_sla_hero_card.view on every refresh interval and swaps the
 * container's innerHTML for us, so this class only needs to hook the parts
 * of the lifecycle where DOM-level polish belongs: a refresh "flash" so an
 * NOC screen operator visually notices a value change, and a defensive
 * resize no-op (typography is handled by CSS clamp(), not JS).
 */
class CWidgetSlaHeroCard extends CWidget {

	/**
	 * Called by the dashboard framework every time new widget content has
	 * been fetched and is about to replace the current DOM. We let the
	 * base class do the actual swap, then apply a short highlight pulse
	 * so a value/status change is noticeable on an always-on NOC display.
	 */
	setContents(response) {
		super.setContents(response);
		this._flashOnRefresh();
	}

	_flashOnRefresh() {
		const card = this._target.querySelector('.sla-hero-card');

		if (card === null) {
			return;
		}

		card.classList.remove('sla-hero-flash');

		// Force reflow so the animation restarts even if the class was
		// still present from a previous refresh.
		void card.offsetWidth;

		card.classList.add('sla-hero-flash');
	}

	/**
	 * No custom logic needed on resize — the card layout is fluid (flex +
	 * CSS clamp() typography), so it re-centers and re-scales on its own.
	 * Kept as an explicit override for clarity/future extension rather than
	 * silently relying on the base class default.
	 */
	onResize() {
		super.onResize();
	}
}
