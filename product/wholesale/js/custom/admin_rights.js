// author : Kartik Champaneriya
// date   : 20-08-2018
$(document).ready(function(){
    $(document).on('change', ".ModuleChange", function () {
            var module_id = $(this).val();
            if ($(this).is(':checked')) {
                $('.sub_module'+module_id).prop('checked', true);
            } else {
                $('.sub_module'+module_id).prop('checked', false);
            }
    });

    //submodule checked inside all checked
    $(document).on('change', ".SubModuleChange", function () {

        var sub_module_id = $(this).val();
        var moduleid = $(this).data('moduleid');
        if ($(this).is(':checked')) {
            $('#module' + moduleid).prop('checked', true);
        }
    });
    
    $("#checkall").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 
 /*Check All Js*/
 $(".check_all").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 /*Check All Js*/
});
    
    
    
    