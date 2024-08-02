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

        switch (name) {
            case 'htmx:trigger':
                [eval][0](event.target.getAttribute("data-loading-callback"));
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
                        [eval][0](event.target.getAttribute("data-loaded-callback"));
                    })
                    .catch(error => {
                        console.error('Error loading images:', error);
                    });
                break;
        }
    }
});