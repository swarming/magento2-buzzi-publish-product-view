/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */

define([
    'uiComponent',
    'Buzzi_Publish/js/action/send-event'
], function (Component, sendEvent) {
    "use strict";

    return Component.extend({
        defaults: {
            event_type: null,
            product_sku: null
        },

        initialize: function () {
            this._super();

            if (this.product_sku) {
                this.triggerEvent();
            }
        },

        triggerEvent: function () {
            sendEvent(
                this.event_type,
                {
                    product_sku: this.product_sku
                },
                this.product_sku
            );
        }
    });
});
