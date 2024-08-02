import htmx from 'htmx.org';

console.log("module loaded");

htmx.defineExtension('img-preload', {
    onEvent: (name, event) => {
        if (
            (
                event?.target?.getAttribute("hx-ext") ??
                event?.target?.getAttribute("data-hx-ext" ?? "")
            ) !== "img-preload"
        ) {
            return;
        }

        const getSpinners = () => [...document.querySelectorAll(
            event.target.getAttribute("data-preload-spinner")
            ?? ".img-preload"
        )];

        switch (name) {
            case 'htmx:trigger':
                getSpinners().forEach(s => s.classList.add("img-preload"));
                break;
            case 'htmx:beforeOnLoad':
                event.detail.shouldSwap = false;

                const parser = new DOMParser();
                const doc = parser.parseFromString(event.detail.xhr.response, 'text/html');
                const imagePromises = [...doc.getElementsByTagName('img')]
                    .map(img => img.src)
                    .map(src => new Promise((resolve, reject) => {
                        const image = new Image();
                        image.onload = resolve;
                        image.onerror = reject;
                        image.src = src;
                    }));

                Promise.all(imagePromises)
                    .then(() => {
                        htmx.swap(event.detail.target, event.detail.xhr.response, {
                            swapStyle: "outerHTML",
                            transition: true,
                        });
                        getSpinners().forEach(s => s.classList.remove("img-preload"))
                    })
                    .catch(error => {
                        console.error('Error loading images:', error);
                    });
                break;
        }
    }
});