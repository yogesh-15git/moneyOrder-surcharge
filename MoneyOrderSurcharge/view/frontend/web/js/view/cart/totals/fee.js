
/*global define*/
define(
    [
        'Distinctive_MoneyOrderSurcharge/js/view/cart/summary/fee'
    ],
    function (Component) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Distinctive_MoneyOrderSurcharge/cart/totals/fee'
            },
            /**
             * @override
             *
             * @returns {boolean}
             */
            isDisplayed: function () {
                return this.getPureValue() != 0;
            }
        });
    }
);
