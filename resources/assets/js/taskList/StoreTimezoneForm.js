export default class StoreTimezoneForm {

    constructor() {
        this.domElement     = document.querySelector('form#storeUserTimeZone');
        this.tzInput        = this.domElement.querySelector('input#tzOffset');
        this.storedTZOffset = Number(this.domElement.getAttribute('data-storedTZOffset'));
    }

    activate() {
        const tzOffsetMinutes = this.getUserTimezoneOffset();
        
        this.tzInput.value = tzOffsetMinutes;
        
        // Only submit the form if the user's timezone (offset) has changed
        if (tzOffsetMinutes != this.storedTZOffset) {
            this.domElement.submit();
        }
    }

    getUserTimezoneOffset() {
        let offset = new Date().getTimezoneOffset();
        offset = (offset == 0) ? 0 : -offset;

        return offset;
    }
}