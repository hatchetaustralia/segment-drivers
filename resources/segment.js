window.segment = new function () {
    this.identify = (guard = null) => {
        this.post('identify', {
            guard,
        });
    };

    this.page = (category, name = null, properties = {}) => {
        properties.url = window.location.pathname;

        this.post('page', {
            name,
            category: category || name,
            properties,
        });
    };

    this.track = (event = null, properties = {}) => {
        this.post('track', {
            event,
            properties,
        });
    };

    this.post = (url, payload) => {
        const getCsrf = () => {
            if (window?.vue?.$page?.props?.csrfToken) {
                return window.vue.$page.props.csrfToken;
            }

            const el = document.querySelector('meta[name="csrf-token"]');

            if (el) {
                return el.getAttribute('content');
            }

            return null;
        };

        fetch('/segment/' + url, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': getCsrf(),
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then(json => console.log('response', json));
    };
};

document.addEventListener('DOMContentLoaded', function () {
    // window.segment.page();
});
