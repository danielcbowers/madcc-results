console.log('MCC resolve-bib.js loaded');

document.addEventListener('DOMContentLoaded', function () {

    const bibInput = document.getElementById('bib_number');
    const riderName = document.getElementById('rider_name');

    if (!bibInput || !riderName) {
        return;
    }

    let timeout;

    bibInput.addEventListener('input', function () {

        clearTimeout(timeout);

        if (bibInput.value.trim() === '') {
            riderName.innerHTML = '<em>Enter a bib number</em>';
            return;
        }

        timeout = setTimeout(function () {

            fetch(mccResults.ajaxUrl, {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },

                body:
                    'action=mcc_lookup_rider' +
                    '&nonce=' + encodeURIComponent(mccResults.nonce) +
                    '&bib=' + encodeURIComponent(bibInput.value)

            })

            .then(response => response.json())

            .then(data => {

                if (data.success) {

                    riderName.innerHTML =
                        '<strong>' + data.data.name + '</strong>';

                } else {

                    riderName.innerHTML =
                        '<span style="color:#d63638;">No rider found</span>';

                }

            })

            .catch(function () {

                riderName.innerHTML =
                    '<span style="color:#d63638;">Lookup failed</span>';

            });

        }, 300);

    });

});