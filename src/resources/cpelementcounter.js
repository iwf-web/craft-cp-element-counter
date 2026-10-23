(() => {
    // Counts keyed by the CP URL of the nav item, shared as Inertia prop.
    let counts = {};
    let scheduled = false;

    function readInitialPage() {
        const script = document.querySelector('script[data-page="app"]');

        try {
            return script ? JSON.parse(script.textContent) : null;
        } catch {
            return null;
        }
    }

    function setCounts(page) {
        if (page && page.props && page.props.cpElementCounter) {
            counts = page.props.cpElementCounter;
            scheduleRender();
        }
    }

    function render() {
        scheduled = false;

        // Only subnav items list sources; a top-level item shares its URL with its “All …” source.
        document.querySelectorAll('craft-nav-list[slot="subnav"] > craft-nav-item').forEach((item) => {
            const count = item.href ? counts[item.href] : undefined;
            let pill = item.querySelector(':scope > .cpelementcount-pill');

            if (count === undefined) {
                pill?.remove();
                return;
            }

            if (!pill) {
                pill = document.createElement('span');
                pill.slot = 'suffix';
                pill.className = 'cpelementcount-pill';
                item.append(pill);
            }

            const text = Number(count).toLocaleString(document.documentElement.lang || undefined);
            if (pill.textContent !== text) {
                pill.textContent = text;
            }
        });
    }

    function scheduleRender() {
        if (!scheduled) {
            scheduled = true;
            requestAnimationFrame(render);
        }
    }

    document.addEventListener('inertia:navigate', (event) => setCounts(event.detail.page));

    document.addEventListener('DOMContentLoaded', () => {
        setCounts(readInitialPage());

        // The nav is re-rendered by Vue, which may drop the pills again.
        new MutationObserver(scheduleRender).observe(document.body, {childList: true, subtree: true});
    });
})();
