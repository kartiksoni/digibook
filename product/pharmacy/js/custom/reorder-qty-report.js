$(document).ready(function(){
    var reordertable;
    
    //datepicker initialize
    $('.datepicker, .datepicker1').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
    
    reordertable = $('.reordertable').DataTable( {
       "columnDefs": [
			{ "orderable": false, "targets": 0 }
		],
		"order": [],
        "fixedHeader": {
            "header": true,
            "footer": true
        }
    });
   
   //Show Hide Option Based On Radio Button Selected
   $("[name='type']").on('click',function(){
         var type = $(this).val();
         
        if(type == "1"){//Company Wise
            
            $("#trans_com").show();
            $("#company_list").prop('required',true);  
            $("#trans_all").hide();
            $("#companyall").removeAttr('required'); 
                 
        } else if(type == "2"){//all company
            
            $("#trans_com").hide();
            $("#trans_all").hide();
            $("#company_list").removeAttr('required');
            $("#companyall").removeAttr('required');
            
             
        } else if(type == "3"){//selected company
            
            $("#trans_com").hide();
            $("#companyall").prop('required',true);  
            $("#trans_all").show();
            $("#company_list").removeAttr('required'); 
        }
   });
   
   //Custom Autocomplete 
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
    
    //company list
    $('body').on('keyup click', '#company_list', function () {
   
        var $this = $(this);
        //  console.log($this);
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
                        
                        if(data.status === true){
                            $('.companyerror').empty();
                            $($this).closest('tr').find('.companyerror').empty();
                            response(data.result)
                        }else{
                            $('.companyerror').text("No results found");
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
        				console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
        			}
                });
            }
        });
    });
    
    // for check all order product
	$('body').on('click', '#pr-checkbox-all', function () {
		if (this.checked) {
		    
			reordertable.$(".pr-checkbox").each(function () {
				this.checked = true;
				$(this).closest('tr').css("background-color", "#A9A9A9");
			});

		} else {

			reordertable.$(".pr-checkbox").each(function () {
				this.checked = false;
				$(this).closest('tr').css("background-color", "#FFFFFF");

			});

		}
	});
	
	// highlight checked row
	$('body').on('click', '.pr-checkbox', function () {
		
		if ($(this).prop('checked') === true) {
			$(this).closest('tr').css("background-color", "#A9A9A9");
		} else {
			$(this).closest('tr').css("background-color", "#FFFFFF");
		}

	});
	
	//Adding Purchase Order
	$(document).on("submit","#reorder_form",function(e){

		//stop page immidiate refresh
		e.preventDefault();
		
		var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        
        
        var rowdata = [];
		reordertable.$("input:checkbox[class=pr-checkbox]:checked").each(function (key) {
			var tmparray = {};
			tmparray.product_id = $(this).closest('tr').find('.product_id').val();
			rowdata.push(JSON.stringify(tmparray));
		});
		
        if (rowdata.length > 0) {
        
        
        } else {
		    showDangerToast('Please Select at Least One Product!');
			return false;
		}
		
	});
	
    
});