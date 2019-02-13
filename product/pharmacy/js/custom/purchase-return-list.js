$( document ).ready(function() {
    $(".btn-applycr").click(function() {

	  var return_id = $(this).attr('data-id');
	  $('#apply-creditnote-model').modal('show');
	  $('#purchase_return_id').val(return_id);
	  
	});

	function getCreditNoteNo(){
		$.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'getCreditNoteNo'},
            dataType: "json",
            success: function (data) {
              $('#cr_no').val(data.result);
            },
            error: function () {
              
            }
        });
	}

	$(".credittype").change(function() {
		var val = $('.credittype:checked').val();

		if(val == 'Billing'){
      $('#cr_no').val(null);
			$('.cr_no_lable').html('Invoice No');
			$('.cr_date_lable').html('Invoice Date');
			$('.cr_amount_lable').html('Invoice Amount');
		}else{
      getCreditNoteNo();
			$('.cr_no_lable').html('Credit No');
			$('.cr_date_lable').html('Date');
			$('.cr_amount_lable').html('Amount');
		}

	});

	// SAVE CREDIT NOTE IN TO DATABASE

	$("#add-creditnote-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addCreditNote', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addcreditnote').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addcreditnote').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                 showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#apply-creditnote-model').modal('toggle');
                $('#add-creditnote-form')[0].reset();
                $('#tr-'+dataarr.purchase_return_id).find('.status').html('<div class="badge badge-outline-danger">Close</div>');
                
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#addcreditnote-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addcreditnote').html('Save');
              $('#btn-addcreditnote').prop('disabled', false);
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#addcreditnote-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addcreditnote').html('Save');
              $('#btn-addcreditnote').prop('disabled', false);
            }
        });

    });
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

    $(".tags").mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
        showHeader: true,
        columns: [{
            name: 'Name',
            width: '100px;',
            valueField: 'name'
        }, {
            name: 'Qty',
            width: '100px',
            valueField: 'total_qty'
        }, {
            name: 'Batch',
            width: '100px',
            valueField: 'batch'
        }, {
            name: 'Generic Name',
            width: '250px',
            valueField: 'generic_name'
        }, {
            name: 'MRP',
            width: '100px',
            valueField: 'mrp'
        }, {
            name: 'Expiry Date',
            width: '150px',
            valueField: 'expiry'
        }],

        // Event handler for when a list item is selected.
        select: function (event, ui) {
            this.value = (ui.item ? ui.item.name : '');
            //$('#results').text(ui.item ? 'Selected: ' + ui.item.name + ', ' + ui.item.purchase_id + ', ' + ui.item.batch : 'Nothing selected, input was ' + this.value);
            
            $('.pr_id').val(ui.item.pr_id);
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
                    action: "getproduct_purchase_return_list"
                },
                // The success event handler will display "No match found" if no items are returned.
                success: function (data) {
                  if(data.length === 0){
                    $(".empty-message0").text("No results found");
                  }else{
                    $(".empty-message0").empty();
                    var result;
                    if (data.length < 0) {
                        result = [{
                            label: 'No match found.'
                        }];
                    } else {
                        result = data;
                    }
                    response(result);
                }
              }
            });
        }
    }); 

});