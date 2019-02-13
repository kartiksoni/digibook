// author : Kartik Champaneriya
// date   : 28-07-2018
$(document).ready(function(){
    var cur_statecode = $('#cur_statecode').val();
    
    // Add Product button js // 

    $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        if(totalproduct > 0){
          $(".add_show").hide();
        }
        var html = $('#html-copy').html();
        
        html = html.replace('##SRNO##',totalproduct);
        //html = html.replace('##SRPRODUCT##',totalproduct);
        //html = html.replace('##PRODUCTCOUNT##',totalproduct);
        html = html.replace('<table>','');
        html = html.replace('</table>','');
        html = html.replace('<tbody>','');
        html = html.replace('</tbody>','');
        $('#product-tbody').append(html);
        if(totalproduct <= '2'){
          $('.remove_last').show();
        }
    });

    

    // End  Add Product button js //

    // Remove product button js //

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        var totalproduct = $('.product-tr').length;//for product length
          if(totalproduct <= 1){
            $(".add_show").show();
             $(':input[type="submit"]').prop('disabled', true);
          }else{
            $(".add_show").hide();
          }
        //$('.f_amount').trigger("change");
        $('.f_rate').trigger("change");
    });

    // End Remove product button js // 

    // Auto Compalete For getproduct //

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
    
    $('body').on('keyup click', '.tags ', function () {
        var $this = $(this);
        var purchase_mrp = $(this).closest('tr').find('.mrp').val();
        console.log('purchase_mrp => '+purchase_mrp);
        $(this).mcautocomplete({
        // These next two options are what this plugin adds to the autocomplete widget.
        showHeader: true,
        columns: [{
            name: 'Name',
            width: '200px;',
            valueField: 'name'
        }, {
            name: 'Qty',
            width: '100px',
            valueField: 'total_qty'
        }, {
            name: 'Batch',
            width: '200px',
            valueField: 'batch'
        }/*, {
            name: 'Generic Name',
            width: '250px;',
            valueField: 'generic_name'
        }*/, {
            name: 'MRP',
            width: '100px',
            valueField: 'mrp'
        }, {
            name: 'Expiry Date',
            width: '150px',
            valueField: 'expiry'
        }, {
            name: 'GST',
            width: '50px',
            valueField: 'igst'
        }],

        // Event handler for when a list item is selected.
        select: function (event, ui) {
            var vendor = $('#vendor').val();
            if(vendor !== ''){
                $('#errormsg').empty();
                this.value = (ui.item ? ui.item.name : '');
                $(this).closest('tr').find('.product-id').val(ui.item.id);
                $(this).closest('tr').find('.qty-value').val(ui.item.ratio);
                $(this).closest('tr').find('.f_igst').val(ui.item.igst);
                $(this).closest('tr').find('.f_cgst').val(ui.item.cgst);
                $(this).closest('tr').find('.f_sgst').val(ui.item.sgst);
                $(this).closest('tr').find('.mrp').val(ui.item.mrp);
                $(this).closest('tr').find('.batch').val(ui.item.batch);
                $(this).closest('tr').find('.expiry').val(ui.item.expiry);
                $(this).closest('tr').find('.mfg_co').val(ui.item.mfg_company);
                $(this).closest('tr').find('.expired').text((typeof ui.item.expired !== 'undefined' && ui.item.expired == 1) ? 'Expired!' : '');
                $(this).closest('tr').find('.rate').val((typeof ui.item.inward_rate !== 'undefined') ? ui.item.inward_rate : '');
            }else{
                $('#errormsg').html('<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>Please Select Vendor!</div></div></div>');
                $($this).val(null);
                $("html, body").animate({ scrollTop: 0 }, "slow");
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
                    mrp: purchase_mrp,
                    action: "searchProductWithExpired"
                },
                // The success event handler will display "No match found" if no items are returned.
                success: function (data) {
                  if(data.status == true){
                     $($this).closest('tr').find('.empty-message').html(null);
                    response(data.result);
                  }else{
                    $($this).closest('tr').find('.empty-message').html('No results found');
                  }
                }
            });
        }
    });
    });
    // End Auto Compalete For getproduct //

    // Rate,Discount,rate js //
    // created by kartik champaneriya//

    $('body').on('propertychange change keyup focusout past', '.rate', function() {
      var totalamount = 0;
      var rate = $(this).val();
      var discount = $(this).closest('tr').find('.discount').val();
      rate = (typeof rate !== "undifined" && rate !== '' && rate !== NaN) ? rate : 0;
      discount = (typeof discount !== "undifined" && discount !== '' && discount !== NaN) ? discount : 0;
      discount = (rate*discount/100);
      var total = (parseFloat(rate)-parseFloat(discount));
      $(this).closest('tr').find('.f_rate').val(total);
      $(this).closest('tr').find('.f_rate').trigger("change");
      //$('.ammout').trigger("change");
    });

    $('body').on('propertychange change keyup focusout past', '.discount', function() {
      var totalamount = 0;
      var discount = $(this).val();
      var rate = $(this).closest('tr').find('.rate').val();
      if(rate !== ''&& rate !== NaN && rate !== "undifined"){
        rate = (typeof rate !== "undifined" && rate !== '' && rate !== NaN) ? rate : 0;
        discount = (typeof discount !== "undifined" && discount !== '' && discount !== NaN) ? discount : 0;
        discount = (rate*discount/100);
        var total = (parseFloat(rate)-parseFloat(discount));
      }else{
        var total = "0";
      }
      $(this).closest('tr').find('.f_rate').val(total);
      $(this).closest('tr').find('.f_rate').trigger("change");
      //$('.ammout').trigger("change");
    });

    $('body').on('propertychange change keyup focusout past', '.qty', function() {
      $(this).closest('tr').find('.rate').trigger("change");
      var f_rate = $(this).closest('tr').find('.f_rate').val();
      f_rate = (typeof f_rate !== 'undefined' && !isNaN(f_rate) && f_rate != '') ? parseFloat(f_rate) : 0;
      var qty = $(this).val();
      qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty != '') ? parseFloat(qty) : 0;
      var total = qty*f_rate;
      $(this).closest('tr').find('.ammout').val(total);
      $(this).closest('tr').find('.f_rate').trigger("change");
      //$('.ammout').trigger("change");
    });

    $('body').on('propertychange change keyup focusout past', '.f_rate', function() {
      qty = $(this).closest('tr').find('.qty').val();
      f_rate = $(this).val();
       if(qty !== ''&& qty !== NaN && qty !== "undifined"){
          f_rate = (typeof f_rate !== "undifined" && f_rate !== '' && f_rate !== NaN) ? f_rate : 0;
          qty = (typeof qty !== "undifined" && qty !== '' && qty !== NaN) ? qty : 0;
          var total = (parseFloat(qty)*parseFloat(f_rate));
       }else{
        var total ="0";
       }
      $(this).closest('tr').find('.ammout').val(parseFloat(total).toFixed(2));
      $('.ammout').trigger("change");
    });
    
    $('body').on('propertychange change keyup focusout past', '.free_qty', function() {
        $(this).closest('tr').find('.qty').trigger("change");
    });

    $('body').on('propertychange change keyup focusout past update', '.ammout', function() {
        var totalamount = 0;
        var cgst = 0;
        var sgst = 0;
        var igst = 0;
        var totalcgst = 0;
        var totaligst = 0;
        var totalsgst = 0;
        var total_gst = 0;
        var statecode = $("#statecode").val();
        var cgst;
        $('.ammout').each(function() {
          var val = $.trim( $(this).val() );
          if(val){
              val = parseFloat( val.replace( /^\$/, "" ) );
              totalamount += !isNaN( val ) ? val : 0;
          }
        });
        if(statecode == cur_statecode){
        /// Code For same State Code  Code ///  
          /// CGST Count Code  ///
          var f_cgst_count = $(".f_cgst").length - 1;
          $('.f_cgst').each(function() {
            var f_cgst = $.trim( $(this).val() );
            
            var tmp_qty = $(this).closest('tr').find('.qty').val();
            tmp_qty = (typeof tmp_qty !== 'undefined' && !isNaN(tmp_qty) && tmp_qty !== '') ? parseFloat(tmp_qty) : 0;
            
            var tmp_free_qty = $(this).closest('tr').find('.free_qty').val();
            tmp_free_qty = (typeof tmp_free_qty !== 'undefined' && !isNaN(tmp_free_qty) && tmp_free_qty !== '') ? parseFloat(tmp_free_qty) : 0;
            
            var tmp_f_rate = $(this).closest('tr').find('.f_rate').val();
            tmp_f_rate = (typeof tmp_f_rate !== 'undefined' && !isNaN(tmp_f_rate) && tmp_f_rate !== '') ? parseFloat(tmp_f_rate) : 0;
            
            if(f_cgst){
                var tmp_f_amount = (tmp_qty+tmp_free_qty)*tmp_f_rate;
                
                f_cgst = parseFloat( f_cgst.replace( /^\$/, "" ) );
                // var tr_amount = $(this).closest('tr').find(".ammout").val();
                tr_amount = (!isNaN( tmp_f_amount ) && tmp_f_amount != '') ? tmp_f_amount :0;
                f_cgst = !isNaN( f_cgst ) ? f_cgst : 0;
                totalcgst += (tr_amount * f_cgst)/100;
            }
          });
         /* totalcgst = parseFloat(totalcgst);
          f_cgst_count = parseFloat(f_cgst_count);
          var cgst_total_avg = totalcgst/f_cgst_count;
          totalamount = parseFloat(totalamount);
          cgst_total_avg = parseFloat(cgst_total_avg);
          var cgst_total = ((totalamount * cgst_total_avg) / 100);3
          cgst_total = (cgst_total != '' && !isNaN(cgst_total)) ? cgst_total : 0;*/
          $("#total_cgst").val(parseFloat(totalcgst).toFixed(2));
          $("#hidden_total_cgst").val(parseFloat(totalcgst).toFixed(2));

          /// End CGST Count Code ///

          /// SGST Count Code ///
          var f_sgst_count = $(".f_sgst").length - 1;
          $('.f_sgst').each(function() {
            var f_sgst = $.trim( $(this).val() );
            
            var stmp_qty = $(this).closest('tr').find('.qty').val();
            stmp_qty = (typeof stmp_qty !== 'undefined' && !isNaN(stmp_qty) && stmp_qty !== '') ? parseFloat(stmp_qty) : 0;
            
            var stmp_free_qty = $(this).closest('tr').find('.free_qty').val();
            stmp_free_qty = (typeof stmp_free_qty !== 'undefined' && !isNaN(stmp_free_qty) && stmp_free_qty !== '') ? parseFloat(stmp_free_qty) : 0;
            
            var stmp_f_rate = $(this).closest('tr').find('.f_rate').val();
            stmp_f_rate = (typeof stmp_f_rate !== 'undefined' && !isNaN(stmp_f_rate) && stmp_f_rate !== '') ? parseFloat(stmp_f_rate) : 0;
            
            if(f_sgst){
                var stmp_f_amount = (stmp_qty+stmp_free_qty)*stmp_f_rate;
                f_sgst = parseFloat( f_sgst.replace( /^\$/, "" ) );
                // var tr_amount = $(this).closest('tr').find(".ammout").val();
                tr_amount = (!isNaN( stmp_f_amount ) && stmp_f_amount != '') ? stmp_f_amount :0;
                f_sgst = !isNaN( f_sgst ) ? f_sgst : 0;
                totalsgst += (tr_amount * f_sgst)/100;
            }
          });

          /*totalsgst = parseFloat(totalsgst);
          f_sgst_count = parseFloat(f_sgst_count);
          var sgst_total_avg = totalsgst/f_sgst_count;
          totalamount = parseFloat(totalamount);
          sgst_total_avg = parseFloat(sgst_total_avg);
          var sgst_total = ((totalamount * sgst_total_avg) / 100);
          sgst_total = (sgst_total != '' && !isNaN(sgst_total)) ? sgst_total : 0;*/
          $("#total_sgst").val(parseFloat(totalsgst).toFixed(2));
          $("#hidden_total_sgst").val(parseFloat(totalsgst).toFixed(2));

          /// End SGST Count Code ///


          /// IGST Count Code ///
          $("#total_igst").val(parseFloat(0).toFixed(2));
          $("#hidden_total_igst").val(parseFloat(0).toFixed(2));
          /// End IGST Count Code ///


          /// Total GST Count Code ///
          var final_sgst = parseFloat($("#total_sgst").val());
          var final_cgst = parseFloat($("#total_cgst").val());
          var final_igst = parseFloat($("#total_igst").val());
          var total_gst = final_sgst + final_cgst + final_igst;
          $("#total_tax").val(parseFloat(total_gst).toFixed(2));
          $("#hidden-total_tax").val(parseFloat(total_gst).toFixed(2));
          /// End Total Gst Count Code ///
        /// End Same State Code ///

        }else{
          /// Out State Code  ///

          /// IGST Count Code ///
          var f_igst_count = $(".f_igst").length - 1;
          $('.f_igst').each(function() {
            var f_igst = $.trim( $(this).val() );
            
            var itmp_qty = $(this).closest('tr').find('.qty').val();
            itmp_qty = (typeof itmp_qty !== 'undefined' && !isNaN(itmp_qty) && itmp_qty !== '') ? parseFloat(itmp_qty) : 0;
            
            var itmp_free_qty = $(this).closest('tr').find('.free_qty').val();
            itmp_free_qty = (typeof itmp_free_qty !== 'undefined' && !isNaN(itmp_free_qty) && itmp_free_qty !== '') ? parseFloat(itmp_free_qty) : 0;
            
            var itmp_f_rate = $(this).closest('tr').find('.f_rate').val();
            itmp_f_rate = (typeof itmp_f_rate !== 'undefined' && !isNaN(itmp_f_rate) && itmp_f_rate !== '') ? parseFloat(itmp_f_rate) : 0;
            
            if(f_igst){
                var itmp_f_amount = (itmp_qty+itmp_free_qty)*itmp_f_rate;
                f_igst = parseFloat( f_igst.replace( /^\$/, "" ) );
                // var tr_amount = $(this).closest('tr').find(".ammout").val();
                tr_amount = (!isNaN( itmp_f_amount ) && itmp_f_amount != '') ? itmp_f_amount :0;
                f_igst = !isNaN( f_igst ) ? f_igst : 0;
                totaligst += (tr_amount * f_igst)/100;
            }
          });

          /*totaligst = parseFloat(totaligst);
          f_igst_count = parseFloat(f_igst_count);
          var igst_total_avg = totaligst/f_igst_count;
          totalamount = parseFloat(totalamount);
          igst_total_avg = parseFloat(igst_total_avg);
          var igst_total = ((totalamount * igst_total_avg) / 100);
          igst_total = (igst_total != '' && !isNaN(igst_total)) ? igst_total : 0;*/
          $("#total_igst").val(parseFloat(totaligst).toFixed(2));
          $("#hidden_total_igst").val(parseFloat(totaligst).toFixed(2));
          /// End IGST Count Code ///

          /// SGST Count Code ///
          $("#total_sgst").val(parseFloat(0).toFixed(2));
          $("#hidden_total_sgst").val(parseFloat(0).toFixed(2));
          /// End SGST Count Code ///

          /// CGST Count Code ///
          $("#total_cgst").val(parseFloat(0).toFixed(2));
          $("#hidden_total_cgst").val(parseFloat(0).toFixed(2));
          /// End CGST Count Code ///

          /// Total GST Count Code ///
          var final_sgst = parseFloat($("#total_sgst").val());
          var final_cgst = parseFloat($("#total_cgst").val());
          var final_igst = parseFloat($("#total_igst").val());
          var total_gst = final_sgst + final_cgst + final_igst;
          total_gst = (total_gst != '' && !isNaN(total_gst)) ? parseFloat(total_gst) : 0;
          $("#total_tax").val(parseFloat(total_gst).toFixed(2));
          $("#hidden-total_tax").val(parseFloat(total_gst).toFixed(2));
          /// End Total Gst Count Code ///



          /// End Out State Code ///

        }
        $('#total_amount').val(parseFloat(totalamount).toFixed(2)); 
        $('.f_discount').trigger("change");
        $('#exampleFormControlSelect2').trigger("change");
        $('#total_courier').trigger("change");
        $('#purchase_amount').trigger("change");
    });

    /// Discount Count js /// 
    $('body').on('change keyup', '.f_discount', function() {
      var dis_per = $(this).val();
      dis_per = (typeof dis_per !== 'undefined' && !isNaN(dis_per) && dis_per != '') ? parseFloat(dis_per) : 0;
      
         var total_amount = $('#total_amount').val();
         total_amount = (typeof total_amount !== 'undefined' && !isNaN(total_amount) && total_amount != '') ? parseFloat(total_amount) : 0;
        
         var per_amount = (total_amount*dis_per/100);
         console.log(per_amount);
         per_amount = (typeof per_amount !== 'undefined' && !isNaN(per_amount) && per_amount != '') ? parseFloat(per_amount) : 0;
        
         $('.f_discount_rs').val(per_amount.toFixed(2));
        
         $('#overall_value').trigger("change");
    });
    
    
    
    $('body').on('change keyup', '.f_discount_rs', function() {
         var dis_per = $(this).val();
         
         var per_amount =$(this).val();
         per_amount = (typeof per_amount !== 'undefined' && !isNaN(per_amount) && per_amount != '') ? parseFloat(per_amount) : 0;
        
         $('.f_discount_rs').val(per_amount);
        
         $('#overall_value').trigger("change");
    });
    
    
    
    
    /// End Discount Count js ///


    /// Freight/Courier Charge  js ///
    /*$('body').on('change', '#exampleFormControlSelect2', function() {
        var val = $(this).val();
        if(val !== ''){
          var overall_value = $("#overall_value").val();
          if(overall_value !== '' && overall_value !== '0'){
            var o_total = (parseInt(overall_value)*parseInt(val)) / 100;
            var data_overall = (parseInt(overall_value)+parseInt(o_total));
          }else{
            var o_total = "0";
          }
          $('#total_courier').val(parseFloat(data_overall).toFixed(2));
        }
    });*/
    /// Freight/Courier Charge  js ///
    $("#total_courier").prop('disabled', true);
    $('body').on('change', '#courier_charge', function() {
          var courier_value = $(this).val();
          if(courier_value !== ''){
            $("#total_courier").prop('disabled', false);
          }
          $('#total_courier').trigger("change");
         
    });

    $('body').on('ropertychange change keyup focusout past update', '#total_courier', function() {

        var courier_charge = $("#courier_charge").val();
        courier_charge = (typeof courier_charge !== 'undefined' && courier_charge !== '') ? courier_charge : 0;
        var statecode = $("#statecode").val();
        if(statecode == cur_statecode){
          /// SGST Count Code ///
            var total_value = $(this).val();
            total_value = (typeof total_value !== 'undefined' && total_value !== '') ? total_value : 0;
            var courier_charge_s = parseFloat(courier_charge) / 2;

            var stored_sgst = $("#hidden_total_sgst").val();
            var stored_total = $("#hidden-total_tax").val();

            if(stored_sgst !== 0 && stored_sgst !== ''){

              var count_sgst = parseFloat(total_value)*parseFloat(courier_charge_s) / 100;
              var f_count_sgst = parseFloat(count_sgst) + parseFloat(stored_sgst);
              $("#total_sgst").val(parseFloat(f_count_sgst).toFixed(2));
            }else{
              $("#total_sgst").val(parseFloat(0).toFixed(2));
            }

          /// End SGST Count Code ///

          /// CGST Count Code ///

            var stored_cgst = $("#hidden_total_cgst").val();

            if(stored_cgst !== 0 && stored_cgst !== ''){

              var count_cgst = parseFloat(total_value)*parseFloat(courier_charge_s) / 100;
              var f_count_cgst = parseFloat(count_cgst) + parseFloat(stored_cgst);
              $("#total_cgst").val(parseFloat(f_count_cgst).toFixed(2));

            }else{
              $("#total_cgst").val(parseFloat(0).toFixed(2));
            }

          /// End CGST Count Code ///

          /// Total Count Code ///

            var total_count = parseFloat(f_count_sgst) + parseFloat(f_count_cgst);
            $("#total_tax").val(parseFloat(total_count).toFixed(2));

          /// End Total Count Code ///
        }else{

          /// IGST Count Code ///

          var total_value = $(this).val();
          total_value = (typeof total_value !== 'undefined' && total_value !== '') ? total_value : 0;
          //var courier_charge_s = parseFloat(courier_charge) / 2;

          var stored_igst = $("#hidden_total_igst").val();
          var stored_total = $("#hidden-total_tax").val();

          if(stored_igst !== 0 && stored_igst !== ''){

            var count_igst = parseFloat(total_value)*parseFloat(courier_charge) / 100;
            var f_count_igst = parseFloat(count_igst) + parseFloat(stored_igst);
            $("#total_igst").val(parseFloat(f_count_igst).toFixed(2));

          }else{
            $("#total_igst").val(parseFloat(0).toFixed(2));
          }

          /// End IGST Count Code  ///

          /// Total Count Code ///

            $("#total_tax").val(parseFloat(f_count_igst).toFixed(2));

          /// End Total Count Code ///

        }
        $('#overall_value').trigger("change");
    });

    /// Freight/Courier Charge  js ///

    /// End Freight/Courier Charge  js ///


    /// Discount Count js ///
    $('body').on('ropertychange change keyup focusout past update', '#overall_value', function() {
        var Total = $("#total_amount").val();
        Total = (typeof Total !== 'undefined' && Total !== '') ? Total : 0;
        
        var k_total = parseFloat(Total); 
        
        var dis_rs = $(".f_discount_rs").val();
        dis_rs = (typeof dis_rs !== 'undefined' && dis_rs != '' && !isNaN(dis_rs)) ? parseFloat(dis_rs) : 0;
        k_total = (parseFloat(k_total)-parseFloat(dis_rs));
        
        var total_courier = $("#total_courier").val();
        total_courier = (typeof total_courier !== 'undefined' && total_courier !== '') ? total_courier : 0;
        var k_total = parseFloat(k_total) + parseFloat(total_courier);

      var total_tax = $("#total_tax").val();
         total_tax = (typeof total_tax !== 'undefined' && total_tax !== '') ? total_tax : 0;
         var k_total = parseFloat(k_total) + parseFloat(total_tax);
        
        
        // var type = $('input[name=minimal-radio]:checked').val();
        // if(type == "per"){
        //   //var g1_total = 0;
        //   if(k_total !='' && k_total != 0){
        //     var f_discount = $('.f_discount').val();
        //     if(f_discount != '' && f_discount != 0){
        //       var total_bkp = (parseFloat(k_total)*parseFloat(f_discount)) / 100;
        //       k_total = (parseFloat(k_total)-parseFloat(total_bkp));
              
        //     }
        //   }
        //  //$('#overall_value').val(g1_total.toFixed(2));
        // }

        // if(type == "rs"){
        //   //var k_total = 0;
        //   if(k_total !=='' && k_total !== '0'){
        //     var f_discount = $('#rs_dis').val();
        //     if(f_discount !== '' && f_discount !== '0'){
        //       k_total = (parseFloat(k_total)-parseFloat(f_discount));
        //     }
        //   }
        //  //$('#overall_value').val(parseFloat(g_total).toFixed(2));
        // }
       $("#hidden_total").val(parseFloat(k_total).toFixed(2));


        //taxable vale
        //   var overall_value = (parseFloat(Total)-parseFloat(dis_rs));   
        //     $("#overall_value").val(parseFloat(overall_value).toFixed(2));

          var txval = (parseFloat(Total) - parseFloat(dis_rs) + parseFloat(total_courier));
         $("#overall_value").val(parseFloat(txval).toFixed(2));

        var note_details = $('#note_details').val();
        var note_value = $('#note_value').val();
        note_value = (typeof note_value !== 'undefined' && note_value !== '') ? note_value : 0;
        if(note_details == "credit_note"){
          k_total = (parseFloat(k_total)-parseFloat(note_value));
        }
        if(note_details == "debit_note"){
          k_total = (parseFloat(k_total)+parseFloat(note_value));
        }




        
        $("#purchase_amount").val(parseFloat(k_total).toFixed(2)); 

        var math_round = Math.round(k_total);
         var round_value = math_round - k_total;
         $("#round_off").val(parseFloat(round_value).toFixed(2));
         $("#total_total").val(parseFloat(math_round).toFixed(2));

        // $('#purchase_amount').val(k_total);


    });

    /// Creadit Note Js /// 

    $('body').on('ropertychange change keyup focusout past update', '.note_details', function() {
        $('#overall_value').trigger("change");
    });

    /// Creadit Note Js /// 

    /// End Discount Count js ///

    
$('#vendor').change(function(){
     var title = $(this).val();
      if(title == 'add_new_vendor'){
        $('#purchase-addvendormodel').modal('show');
        $('#vendor').val('').trigger('change');
      }
  });

$('#transporter_name').change(function(){
     var title = $(this).val();
      if(title == 'add_new_transporter'){
        $('#add-transport-model').modal('show');
        $('#transporter_name').val('').trigger('change');
      }
  });
  
});



// auther : Gautam Makwana
//date    : 30-7-2018
$( document ).ready(function() {
    $("#city").change(function(){
        var city_id = $(this).val();
        if(city_id !== ''){
            getVendorByCity(city_id);
        }else{
          $('#vendor').children('option:not(:first)').remove();
        }
        // $('#vendor').trigger("change");
    });
    
    function getVendorByCity(city_id){
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'city_id':city_id, 'action':'getCityByVendor'},
            dataType: "json",
            beforeSend: function() {
                $('.vendor-loader').show();
            },
            success: function (data) {
              $('.vendor-loader').hide();
              if(data.status == true){
                $('#vendor').children('option:not(:first)').remove();
                $.each(data.result, function (i, item) {
                  $('#vendor').append($('<option>', { 
                      value: item.id,
                      text : item.name 
                  }));
                });
              }else{
                $('#vendor').children('option:not(:first)').remove();
              }
            },
            error: function () {
              $('.vendor-loader').hide();
              $('#vendor').children('option:not(:first)').remove();
            }
        });
    }
    
    
    $('#add-vendor').on('change', '#country', function() {
        var country_id = $(this).val();
        if(country_id !== ''){
          $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'country_id':country_id, 'action':'getCountryByState'},
              dataType: "json",
              success: function (data) {
                if(data.status == true){
                  $('#state').children('option:not(:first)').remove();
                      $.each(data.result, function (i, item) {
                        $('#state').append($('<option>', { 
                            value: item.id,
                            text : item.name 
                        }));
                      });
                }else{
                  $('#state').children('option:not(:first)').remove();
                }
              },
              error: function () {
                $('#state').children('option:not(:first)').remove();
              }
            });
        }else{
          $('#state').children('option:not(:first)').remove();
        }
      $('#state').trigger("change");
    });

    $('#add-vendor').on('change', '#state', function() {
        var state_id = $(this).val();
        if(state_id !== ''){
          $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'state_id':state_id, 'action':'getStateByCity'},
              dataType: "json",
              success: function (data) {
                if(data.status == true){
                  $('#vendorcity').children('option:not(:first)').remove();
                  $.each(data.result, function (i, item) {
                      $('#vendorcity').append($('<option>', { 
                          value: item.id,
                          text : item.name 
                      }));
                  });
                }else{
                    $('#vendorcity').children('option:not(:first)').remove();
                  }
                },
              error: function () {
                $('#vendorcity').children('option:not(:first)').remove();
              }
          });
        }else{
          $('#vendorcity').children('option:not(:first)').remove();
        }
    });

    //--------------Add Area-----------------------
    $("#add-area-form").on("submit", function(event){
    event.preventDefault();
    var data = $(this).serialize();
    var dataarr = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
    $.ajax({
        type: "POST",
        url: 'ajax.php',
        data: {'action':'addarea', 'data': dataarr},
        dataType: "json",
        beforeSend: function() {
          $('#btn-addarea').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
          $('#btn-addarea').prop('disabled', true);
        },
        success: function (data) {
          if(data.status == true){
            $('#btn-addarea').html('Save');
            $('#btn-addarea').prop('disabled', false);
            $('#add-area-form')[0].reset();
            
            $('#Area').append($('<option>', { 
                value: data.result,
                text : dataarr.name 
            }));
            $('#Area').val(data.result).trigger('change');
            $('#addarea-model').modal('hide');
            showSuccessToast(data.message);
          }else{
            $('#btn-addarea').html('Save');
            $('#btn-addarea').prop('disabled', false);
            showDangerToast(data.message);
          }
        },
        error: function () {
          $('#btn-addarea').html('Save');
          $('#btn-addarea').prop('disabled', false);
          showDangerToast('Somthing Want Wrong! Try again.');
        }
    });

  });

    $("#add-vendor").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serializeArray();
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addvendor', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addvendor').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addvendor').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addvendormodel').modal('toggle');
                var city_id = $('#add-vendor').find('#vendorcity').val();
                $('#add-vendor')[0].reset();
                
                
                $.ajax({
                  type: "POST",
                  url: 'ajax.php',
                  data: {'action':'getAllVendorCity'},
                  dataType: "json",
                  success: function (data) {
                    if(data.status == true){
                        $('#city').children('option:not(:first)').remove();
                        $.each(data.result, function (i, item) {
                            $('#city').append($('<option>', { 
                                value: item.id,
                                text : item.name 
                            }));
                        });
                    }else{
                      $('#city').children('option:not(:first)').remove();
                      showSuccessToast(data.message);
                    }
                  },
                  error: function () {
                    $('#city').children('option:not(:first)').remove();
                    showSuccessToast(data.message);
                  }
                });
                
              }else{
                $("html, body").animate({ scrollTop: 0 }, "slow");
                showDangerToast('Somthing Want Wrong! Try again.');
              }
              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
              showSuccessToast(data.message);
            },
            error: function () {
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addvendor').html('Save');
              $('#btn-addvendor').prop('disabled', false);
              showDangerToast('Somthing Want Wrong! Try again.');
            }
        });

    });
    
    $(".customer_type").change(function(){
      var customer_type = $(this).val();
      $(".gstno").removeAttr('readonly');
      $(".gst_error").show();
      $(".gstno").prop('required',true);
      if(customer_type == "GST_unregistered"){
          $(".gst_error").hide();
          $(".gstno").attr('readonly', true);
          $(".gstno").removeAttr('required');
      }
      if(customer_type == "Consumer"){
          $(".gst_error").hide();
          $(".gstno").attr('readonly', true);
          $(".gstno").removeAttr('required');
      }
      if(customer_type == "Overseas"){
          $(".gst_error").hide();
          $(".gstno").attr('readonly', true);
          $(".gstno").removeAttr('required');
      }
    });
    
    $("#add-product").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
       //  var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addproduct', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addproduct').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addproduct').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#errormsg').html(htmlsuccess);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#purchase-addproductmodel').modal('toggle');
                $('#add-product')[0].reset();
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#addproduct-errormsg').html(htmlerror);
                $("html, body").animate({ scrollTop: 0 }, "slow");
              }
              $('#btn-addproduct').html('Save');
              $('#btn-addproduct').prop('disabled', false);
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#addvendor-errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");

              $('#btn-addproduct').html('Save');
              $('#btn-addproduct').prop('disabled', false);
            }
        });

    });
    
    // FOR PRODUCT POPUP OPENING STOCK RS OPENING QTY * INWART RATE = OPENING STOCK RS
    $('body').on('keyup', '#opening_qty, #inward_rate', function () {
        var openingqty = $('#add-product').find('#opening_qty').val();
        openingqty = (typeof openingqty !== 'undefined' && !isNaN(openingqty) && openingqty !== '') ? parseFloat(openingqty) : 0;
        
        var inwartrate = $('#add-product').find('#inward_rate').val();
        inwartrate = (typeof inwartrate !== 'undefined' && !isNaN(inwartrate) && inwartrate !== '') ? parseFloat(inwartrate) : 0;
        
        var opening_stock_rs = (openingqty*inwartrate);
        $('#add-product').find('#opening_stock_rs').val(opening_stock_rs.toFixed(2));
    });
    
    $("#vendor").change(function(){
        var vendor_id = $(this).val();
        if(vendor_id !== ''){
            $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'vendor_id':vendor_id, 'action':'getStatecodeByVendor'},
                dataType: "json",
                success: function (data) {
                  if(data.status == true){
                    $('#statecode').val(data.result.statecode);
                  }else{
                    $('#statecode').val('');
                  }
                },
                error: function () {
                  $('#statecode').val('');
                }
            });
        }else{
          $('#statecode').val('');
        }
    });

    $("#vendor").change(function(){
      var vendor_id = $(this).val();
      if(vendor_id !== ''){
          getAllOrder(vendor_id);
      }else{
        return false;
      }
    });
    
    // $('#poi-model').on('hidden.bs.modal', function () {
    //   addLastBill($('#vendor').val());
    // });
    
    function getAllOrder(vendor_id = null){
      if(vendor_id !== ''){
          $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'vendor_id':vendor_id, 'action':'getAllOrdersByVendorID'},
                dataType: "json",
                beforeSend: function() {
                    $('.vendor-loader').show();
                },
                success: function (data) {
                  $('.vendor-loader').hide();
                  if(data.status == true){
                    var html = $('#poi-tr-html').html();
                    html = html.replace("<table>", "");
                    html = html.replace("</table>", "");
                    html = html.replace("<tbody>", "");
                    html = html.replace("</tbody>", "");

                    var finalhtml = '';
                    $.each(data.result, function (i, item) {
                      var tmphtml = html;
                      tmphtml = tmphtml.replace("##SRNO##",i+1);
                      tmphtml = tmphtml.replace("##DATE##",item.date);
                      tmphtml = tmphtml.replace("##ORDER##",item.order_no);
                      tmphtml = tmphtml.replace("##PRODUCTNAME##",item.product_name);

                      tmphtml = tmphtml.replace("##BATCH##",item.batch_no);
                      tmphtml = tmphtml.replace("##EXPIRY##",item.expiry);

                      tmphtml = tmphtml.replace("##PRODUCTID##",item.product_id);
                      tmphtml = tmphtml.replace("##GENERIC##",item.generic_name);
                      tmphtml = tmphtml.replace("##MFG##",item.mfg_company);
                      tmphtml = tmphtml.replace("##PURCHASEPRICE##",item.purchase_price);
                      tmphtml = tmphtml.replace("##GST##",item.gst);
                      tmphtml = tmphtml.replace("##UNIT##",item.unit);
                      tmphtml = tmphtml.replace("##QTY##",item.qty);
                      tmphtml = tmphtml.replace("##POIID##",item.id);
                      finalhtml += tmphtml;
                    });
                    
                    $('#poi-body').empty();
                    $('#poi-body').html(finalhtml);
                    $('#poi-model').modal('show');
                  }else{
                    //addLastBill(vendor_id);
                    return false;
                  }
                },
                error: function () {
                    $('.vendor-loader').hide();
                    //addLastBill(vendor_id);
                    return false;
                }
          });
      }
    }

    function addLastBill(vendor_id = null){
      if(vendor_id !== ''){
          $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {'vendor_id':vendor_id, 'action':'getLastPurchaseBill'},
                dataType: "json",
                beforeSend: function() {
                    $('.vendor-loader').show();
                },
                success: function (data) {
                    $('.vendor-loader').hide();
                  if(data.status == true){


                    var html = $('#hidden-vendor-lastbill').html();
                    html = html.replace("<table>", "");
                    html = html.replace("</table>", "");
                    html = html.replace("<tbody>", "");
                    html = html.replace("</tbody>", "");

                    var finalhtml = '';
                    $.each(data.result, function (key, value) {
                      var tmphtml = html;
                      tmphtml = tmphtml.replace("##SRNO##",key+1);
                      tmphtml = tmphtml.replace("##PRODUCTNAME##",value.product_name);
                      tmphtml = tmphtml.replace("##PRODUCTID##",value.product_id);
                      tmphtml = tmphtml.replace("##MRP##",value.mrp);
                      tmphtml = tmphtml.replace("##MFG##",value.mfg_co);
                      tmphtml = tmphtml.replace("##BATCH##",value.batch);
                      tmphtml = tmphtml.replace("##EXPIRY##",value.expiry);
                      tmphtml = tmphtml.replace("##EXPIRED##",(value.expired == 1) ? 'Expired!' : '');
                      tmphtml = tmphtml.replace("##QTY##",value.qty);
                      tmphtml = tmphtml.replace("##RATIO##",value.qty_ratio);
                      tmphtml = tmphtml.replace("##FREEQTY##",value.free_qty);
                      tmphtml = tmphtml.replace("##RATE##",value.rate);
                      tmphtml = tmphtml.replace("##DISCOUNT##",value.discount);
                      tmphtml = tmphtml.replace("##FINALRATE##",value.f_rate);
                      tmphtml = tmphtml.replace("##AMOUNT##",value.ammout);

                      tmphtml = tmphtml.replace("##CGST##",value.f_cgst);
                      tmphtml = tmphtml.replace("##SGST##",value.f_sgst);
                      tmphtml = tmphtml.replace("##IGST##",value.f_igst);

                      finalhtml += tmphtml;
                    });
                    
                    $('#vendor-lastbill-product-tbody').empty();
                    $('#vendor-lastbill-product-tbody').html(finalhtml);
                    $('#vendor_lastbill_model').modal('show');
                  }else{
                    return false;
                  }
                },
                error: function () {
                    $('.vendor-loader').hide();
                    return false;
                }
            });
      }
    }
    
    // for check all last bill product
    $('body').on('click', '.lastbill-check-all', function () {
      if(this.checked){
        $("#vendor-lastbill-product-tbody .lastbill-check").each(function(){
          this.checked=true;
          $(this).closest('tr').css("background-color", "#A9A9A9");
        })              
      }else{
        $("#vendor-lastbill-product-tbody .lastbill-check").each(function(){
          this.checked=false;
          $(this).closest('tr').css("background-color", "#FFFFFF");
        })              
      }

      var length = $(".lastbill-check:checked").length;
      console.log(length);
      if(length > 0){
        $('#btn-add-vendor-lastbill').prop('disabled', false);
      }else{
        $('#btn-add-vendor-lastbill').prop('disabled', true);
      }
    });

    $('body').on('click', '.lastbill-check', function () {
      if($(this).prop('checked')==true){
        $(this).closest('tr').css("background-color", "#A9A9A9");
      }else{
        $(this).closest('tr').css("background-color", "#FFFFFF");
      }
      var length = $(".lastbill-check:checked").length;
      if(length > 0){
        $('#btn-add-vendor-lastbill').prop('disabled', false);
      }else{
        $('#btn-add-vendor-lastbill').prop('disabled', true);
      }
    });

    $("#btn-add-vendor-lastbill").click(function(){
      var data = [];
        $("input:checkbox[class=lastbill-check]:checked").each(function (){
            var tmparray = [];
            tmparray['product_name'] = $(this).closest('tr').find('.product_name').html();
            tmparray['product_id'] = $(this).closest('tr').find('.product_id').val();
            tmparray['mrp'] = $(this).closest('tr').find('.mrp').html();
            tmparray['mfg'] = $(this).closest('tr').find('.mfg').html();
            tmparray['batch_no'] = $(this).closest('tr').find('.batch').html();
            tmparray['expiry'] = $(this).closest('tr').find('.expiry').html();
            tmparray['expired'] = $(this).closest('tr').find('.expired').html();
            tmparray['qty'] = $(this).closest('tr').find('.qty').html();
            tmparray['ratio'] = $(this).closest('tr').find('.ratio').val();
            tmparray['freeqty'] = $(this).closest('tr').find('.freeqty').html();
            tmparray['rate'] = $(this).closest('tr').find('.rate').html();
            tmparray['discount'] = $(this).closest('tr').find('.discount').html();
            tmparray['finalrate'] = $(this).closest('tr').find('.finalrate').html();
            tmparray['amount'] = $(this).closest('tr').find('.amount').html();
            tmparray['cgst'] = $(this).closest('tr').find('.cgst').val();
            tmparray['sgst'] = $(this).closest('tr').find('.sgst').val();
            tmparray['igst'] = $(this).closest('tr').find('.igst').val();
            data.push(tmparray);
        });

        if(data.length > 0){
          var html = $('#html-copy').html();
          var firsttr = $('#product-tbody tr:first').find('.product-id').val();
          if(firsttr == ''){
            $('#product-tbody').empty();
          }
          var rowCount = ($('#product-tbody tr').length)+1;
          $.each(data, function (i, item) {
            tmphtml = html;
            tmphtml = tmphtml.replace('##SRNO##',rowCount);
            //tmphtml = tmphtml.replace('##SRPRODUCT##','');
            //tmphtml = tmphtml.replace('##PRODUCTCOUNT##','');
            tmphtml = tmphtml.replace('<table>','');
            tmphtml = tmphtml.replace('</table>','');
            tmphtml = tmphtml.replace('<tbody>','');
            tmphtml = tmphtml.replace('</tbody>','');
            $('#product-tbody').append(tmphtml);

            $('#product-tbody tr:last').find('.tags').val(item.product_name);
            $('#product-tbody tr:last').find('.product-id').val(item.product_id);
            $('#product-tbody tr:last').find('.mrp').val(item.mrp);
            $('#product-tbody tr:last').find('.mfg_co').val(item.mfg);
            $('#product-tbody tr:last').find('.batch').val(item.batch_no);
            $('#product-tbody tr:last').find('.expiry').val(item.expiry);
            $('#product-tbody tr:last').find('.expired').text(item.expired);
            $('#product-tbody tr:last').find('.qty').val(item.qty);
            $('#product-tbody tr:last').find('.qty-value').val(item.ratio);
            $('#product-tbody tr:last').find('.free_qty').val(item.freeqty);
            $('#product-tbody tr:last').find('.rate').val(item.rate);
            $('#product-tbody tr:last').find('.discount').val(item.discount);
            $('#product-tbody tr:last').find('.f_rate').val(item.finalrate);
            $('#product-tbody tr:last').find('.ammout').val(item.amount);
            $('#product-tbody tr:last').find('.f_igst').val(item.igst);
            $('#product-tbody tr:last').find('.f_cgst').val(item.cgst);
            $('#product-tbody tr:last').find('.f_sgst').val(item.sgst);
            $('#product-tbody tr:last').find('.qty').trigger('change');

            rowCount++;
          });
        }
      $('#vendor_lastbill_model').modal('hide');
    });

    $("#btn-addpoi").click(function(){
      var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      
      var i = 1;
      var data = [];
        $("input:checkbox[class=poi-checkbox]:checked").each(function (){
            var tmparray = [];
            tmparray['date'] = $(this).closest('tr').find('.poi-date').html();
            tmparray['product_name'] = $(this).closest('tr').find('.poi-pname').html();
            tmparray['batch_no'] = $(this).closest('tr').find('.poi-batch').html();
            tmparray['expiry'] = $(this).closest('tr').find('.poi-expiry').html();
            tmparray['product_id'] = $(this).closest('tr').find('.poi-pid').val();
            tmparray['generic_name'] = $(this).closest('tr').find('.poi-gname').html();
            tmparray['mfg_name'] = $(this).closest('tr').find('.poi-mfg').html();
            tmparray['purchase_price'] = $(this).closest('tr').find('.poi-pprice').html();
            tmparray['gst'] = $(this).closest('tr').find('.poi-gst').html();
            tmparray['unit'] = $(this).closest('tr').find('.poi-unit').html();
            tmparray['qty'] = $(this).closest('tr').find('.poi-qty').html();
            tmparray['id'] = $(this).closest('tr').find('.poi-id').val();
            tmparray['table'] = $(this).closest('tr').find('.poi-table').val();
            data.push(tmparray);
        });

      if(data.length > 0){
        
        html = $('#html-copy').html();
        html = html.replace("<table>", "");
        html = html.replace("</table>", "");
        html = html.replace("<tbody>", "");
        html = html.replace("</tbody>", "");

        $('#product-tbody').empty();
          var state_code = $('#statecode').val();
          $.each(data, function (i, item) {
              tmphtml = html;
              tmphtml = tmphtml.replace("##SRNO##",i+1);
              var append = $('#product-tbody').append(tmphtml);

              //$('#product-tbody tr:last').find('.btn-remove-product').hide(); 
              $('#product-tbody tr:last').find('.tags').val(item.product_name);
              $('#product-tbody tr:last').find('.product-id').val(item.product_id);
              $('#product-tbody tr:last').find('.mrp').val(item.purchase_price);
              $('#product-tbody tr:last').find('.mfg_co').val(item.mfg_name);
              $('#product-tbody tr:last').find('.qty').val(item.qty);
              $('#product-tbody tr:last').find('.batch').val(item.batch_no);
              $('#product-tbody tr:last').find('.expiry').val(item.expiry);

              if(state_code == cur_statecode){
                $('#product-tbody tr:last').find('.f_cgst').val(item.gst/2);
                $('#product-tbody tr:last').find('.f_sgst').val(item.gst/2);
                $('#product-tbody tr:last').find('.f_igst').val(0);
              }else{
                $('#product-tbody tr:last').find('.f_cgst').val(0);
                $('#product-tbody tr:last').find('.f_sgst').val(0);
                $('#product-tbody tr:last').find('.f_igst').val(item.gst);
              }
              $('#product-tbody tr:last').find('.f_poi_id').val(item.id);
          });

        $('#poi-model').modal('hide');

      }else{
        showDangerToast('Please select at least one item!');
        htmlerror =  htmlerror.replace("##MSG##", 'Please select at least one item!');
        $('#poi-error').html(htmlerror);
        return false;
      }

    });
    
    $(".purchase_type").change(function(){
      var purchase_type = $(this).val();

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'purchase_type':purchase_type, 'action':'getVoucherNoByType'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('#voucher_no').val(data.result);
              }else{
                $('#voucher_no').val('');
              }
            },
            error: function () {
              $('#voucher_no').val('');
            }
        });
    });
    
    // for check all order product
    $('body').on('click', '#poi-checkbox-all', function () {
      if(this.checked){
        $("#poi-body .poi-checkbox").each(function(){
          this.checked=true;
          $(this).closest('tr').css("background-color", "#A9A9A9");
        })              
      }else{
        $("#poi-body .poi-checkbox").each(function(){
          this.checked=false;
          $(this).closest('tr').css("background-color", "#FFFFFF");
        })              
      }
    });

    $('body').on('click', '.poi-checkbox', function () {
      if($(this).prop('checked')==true){
        $(this).closest('tr').css("background-color", "#A9A9A9");
      }else{
        $(this).closest('tr').css("background-color", "#FFFFFF");
      }
    });
    
    /*---------------ADD TRANSPORT START--------------------*/
    $("#add-transport-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
        // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'action':'addTransport', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-transport').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-transport').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                showSuccessToast(data.message);
                // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
                // $('#transport-errormsg').html(htmlsuccess);
                
                $('#transporter_name').append($('<option>', { 
                    value: data.result.id,
                    text : data.result.name 
                }));
                $('#transporter_name').val(data.result.id).trigger('change');
                $('#add-transport-form')[0].reset();
                $('#add-transport-model').modal('toggle');
              }else{
                showDangerToast(data.message);
                // htmlerror =  htmlerror.replace("##MSG##", data.message);
                // $('#transport-errormsg').html(htmlerror);
              }
              $('#btn-transport').html('Save');
              $('#btn-transport').prop('disabled', false);
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
              // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#transport-errormsg').html(htmlerror);

              $('#btn-transport').html('Save');
              $('#btn-transport').prop('disabled', false);
            }
        });

    });
    /*---------------ADD TRANSPORT START--------------------*/
    
});

$("#gst_no").keyup(function(){
    var gst_value = $(this).val();
    if(gst_value != ''){
        if (gst_value.match(/^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/)) {
            $("#gst_no").removeClass("parsley-error");
            $("#gst_no").addClass("parsley-success");
        }else{
            $("#gst_no").addClass("parsley-error");  
        }
    }else{
        $("#gst_no").addClass("parsley-error");
    }
    
});

 $('body').on('change keyup past', '#gst_no ', function () {
      var gstno = $(this).val();
   var pan = gstno.substring(2,12);
   $('#pan_num').val(pan);
   
  });
  