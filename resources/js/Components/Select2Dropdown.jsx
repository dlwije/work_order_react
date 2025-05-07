import React, { useEffect } from 'react';
// import $ from 'jquery';
// Now import Select2
import 'select2/dist/js/select2.min.js';
import 'select2/dist/css/select2.min.css';
// import 'bootstrap/dist/js/bootstrap.bundle.min'; // for tooltip

export default function Select2Dropdown({
                                            elementId = 'select-element',
                                            URL,
                                            URLGET = null,
                                            ID = null,
                                            vendorID = null,
                                            onChange
                                        }) {
    useEffect(() => {
        const selector = `#${elementId}`;

        console.log(selector);
        $(selector).select2({
            placeholder: 'Select...',
            ajax: {
                url: URL,
                type: 'GET',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term || '',
                        page: params.page || 1,
                        resCount: 10,
                        vendor_id: vendorID
                    };
                }
            }
        });

        // Tooltip handling
        $(`${selector}`).next('.select2-container').tooltip({
            title: function () {
                return $(this).prev().attr('title');
            },
            placement: 'auto',
        });

        // Handle select change
        $(selector).on('select2:select', function (e) {
            const selectedData = e.params.data;
            if (onChange) onChange(selectedData);
        });

        // Pre-select value if ID provided
        if (ID) {
            $.ajax({
                type: 'GET',
                url: URLGET,
                data: { id: ID }
            }).then(function (data) {
                const jsonVal = typeof data === 'string' ? JSON.parse(data) : data;
                const selected = new Option(jsonVal[0].text, jsonVal[0].id, true, true);
                $(selector).append(selected).trigger('change');

                // Trigger manual select event
                $(selector).trigger({
                    type: 'select2:select',
                    params: { data: jsonVal[0] }
                });
            });
        }

        return () => {
            $(selector).select2('destroy').off('select2:select');
        };
    }, [elementId, URL, URLGET, ID, vendorID]);

    return (
        <select id={elementId} className="form-control" title="Select something" style={{ width: '100%' }}></select>
    );
}
