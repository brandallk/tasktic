
(function(exports) {

    function anyInArray(arrayToSearch, arrayOfValuesToMatchAgainst) {
        return arrayToSearch.reduce(function(acc, val){
            return !!(acc + arrayOfValuesToMatchAgainst.includes(val) ? 1 : 0);
        }, 0);
    }

    function noneInArray(arrayToSearch, arrayOfValuesToMatchAgainst) {
        return !anyInArray(arrayToSearch, arrayOfValuesToMatchAgainst);
    }

    exports.anyInArray = function(arrayToSearch, arrayOfValuesToMatchAgainst) {
        return anyInArray(arrayToSearch, arrayOfValuesToMatchAgainst);
    };

    exports.noneInArray = function(arrayToSearch, arrayOfValuesToMatchAgainst) {
        return noneInArray(arrayToSearch, arrayOfValuesToMatchAgainst);
    };

})(window.arrayHelpers = {});