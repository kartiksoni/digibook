$( document ).ready(function() {
    $('body').on('keypress', '.onlyalphabet', function (event) {
       var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });
});