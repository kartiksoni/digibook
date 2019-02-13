
   $("#trans_com").hide();
   $("#trans_all").hide();
   $("#company_list").removeAttr('required');
   $("#companyall").removeAttr('required');

 function myFunctionCompany(){  
   $("#trans_com").show();
    $("#company_list").prop('required',true);  
   $("#trans_all").hide();
    $("#companyall").removeAttr('required');  
   }
function mySelectedCompany(){
   $("#trans_com").hide();
    $("#companyall").prop('required',true);  
   $("#trans_all").show();
    $("#company_list").removeAttr('required');   
}
function AllCompany(){
   $("#trans_com").hide();
   $("#trans_all").hide();
   $("#company_list").removeAttr('required');
   $("#companyall").removeAttr('required');

}
function AllProduct(){
   $("#trans_com").hide();
   $("#trans_all").hide();
   $("#company_list").removeAttr('required');
   $("#companyall").removeAttr('required');
}

$( document ).ready(function() {

$.widget('custom.mcautocomplete', $.ui.autocomplete, {
          _create: function () {
              this._super();
              this.widget().menu("option", "items", "> :not(.ui-widget-header)");
          },
          _renderMenu: function (ul, items) {
              var self = this,
                  thead;
              if (this.options.showHeader) {
                  table = $('<div class="ui-widget-header" style="width:100%"></div>');
                  $.each(this.options.columns, function (index, item) {
                      table.append('<span style="padding:0 4px;float:left;width:' + item.width + ';">' + item.name + '</span>');
                  });
                  table.append('<div style="clear: both;"></div>');
                  ul.append(table);
              }
              $.each(items, function (index, item) {
                  self._renderItem(ul, item);
              });
          },
          _renderItem: function (ul, item) {
              var t = '',
                  result = '';
              $.each(this.options.columns, function (index, column) {
                  t += '<span style="padding:0 4px;float:left;width:' + column.width + ';">' + item[column.valueField ? column.valueField : index] + '</span>'
              });
              result = $('<li></li>')
                  .data('ui-autocomplete-item', item)
                  .append('<a class="mcacAnchor">' + t + '<div style="clear: both;"></div></a>')
                  .appendTo(ul);
              return result;
          }
    });
    $('body').on('keyup click', '#company_list', function () {
   
        var $this = $(this);
         console.log($this);
          $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
            showHeader: true,
            columns: [{
                name: 'Company',
                width: '200px',
                valueField: 'mfg_company'
            }],
            // Event handler for when a list item is selected.
            select: function (event, ui) {
                this.value = (ui.item ? ui.item.mfg_company : '');
                $('#company_list').val(ui.item.mfg_company);
                if(ui.item.id !== ''){
                  $('#company_id').val(ui.item.id);
                }
                return false;
            },
            // The rest of the options are for configuring the ajax webservice call.
            minLength: 1,
            source: function (request, response) {
                $.ajax({
                    url: 'ajax.php',
                    dataType: 'json',
                    type: "POST",
                    data: {
                        query: request.term,
                        action: "searchCompany"
                    },
                    // The success event handler will display "No match found" if no items are returned.
                    success: function (data) {
                        if(data.status == true){
                            $('.customererror').empty();
                            $($this).closest('tr').find('.customererror').empty();
                            response(data.result)
                        }else{
                            $('.customererror').text("No results found");
                        }
                    },error: function () {
                      // $('.customererror').text("No results found");
                    }
                });
            }
        });
    });

  $('#search').on("submit", function(event){
      event.preventDefault();
      var data = $(this).serialize();
       
       $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'SearchByTranstition', 'data': data},
            dataType: "json",          
            success: function (data) 

            { 
                if(data.status == true){                   
                     table = $('.datatable').DataTable(); 
                      table.clear().draw();
                      if(data.result.length > 0){
                     $.each(data.result, function (i, item) {

                             console.log(item);
                               var sgst = item.sgst;
                               var cgst = item.cgst;
                               var GST = parseInt(sgst) + parseInt(cgst);
                               var per = data.stockper; 
                               var sale = item.productsale;
                               var currentstock = item.currentstock;
                               var  OrderQty = ((sale * per) / 100) - parseInt(currentstock);
                              
                table.row.add([ item.product_name, item.mfg_company, item.batch_no, GST, item.unit, item.ratio, OrderQty, item.currentstock]).draw();                
                  
                     });
                    }else{   
                table.row.add(['','','No record found','','','','','']).draw(); 
                  }
                    
                }
            }

        });
  });

});
