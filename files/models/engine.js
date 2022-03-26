function TivinsAppEngine() {
    /**
     *
     * @param url URL object
     * @param parameters object or null
     * @param post object or null
     * @returns {Promise<null|any>}
     */
    this.callService = async function (url, parameters, post) {
        const options = {
            method: post ? 'POST' : 'GET',
            body: post ? JSON.stringify(post) : null,
            headers: {
                'Accept': 'application/json'
            },
        };

        if (parameters) {
            for (let z in parameters) {
                url.searchParams.append(z, parameters[z]);
            }
        }
        const response = await fetch(url.href, options);
        console.debug("Fetch", {url: url.href, ok: response.ok, status: response.status});

        try {
            return await response.json();
        } catch (exception) {
            console.error("exception!");
            return null;
        }
    }
}

// (new TivinsAppEngine).callService(new URL('http://example.com/api')).then(r => doStuff());