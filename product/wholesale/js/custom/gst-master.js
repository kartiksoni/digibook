$( document ).ready(function() {
    $('body').on('change keyup focusout', '.igst', function() {
        var igst = $(".igst").val();
        if(igst !==''){
            sgst = igst / 2;
            cgst = igst / 2;
            $(".sgst").val(sgst);
            $(".cgst").val(cgst);
        }else{
            $(".sgst").val("");
            $(".cgst").val("");
        }
    });
});