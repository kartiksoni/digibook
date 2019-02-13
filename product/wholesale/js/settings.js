(function($) {
  'use strict';
  $(function() {
    $(".nav-settings").click(function() {
      $("#right-sidebar").toggleClass("open");
    });
    $(".settings-close").click(function() {
      $("#right-sidebar,#theme-settings").removeClass("open");
    });

    $("#settings-trigger").on("click", function() {
      $("#theme-settings").toggleClass("open");
    });


    //background constants
    var navbar_classes = "navbar-danger navbar-success navbar-warning navbar-dark navbar-light navbar-primary navbar-info navbar-pink";
    var sidebar_classes = "sidebar-light sidebar-dark";
    var $body = $("body");

    //sidebar backgrounds
    $("#sidebar-light-theme").on("click", function() {
      $body.removeClass(sidebar_classes);
      $body.addClass("sidebar-light");
      $(".sidebar-bg-options").removeClass("selected");
      $(this).addClass("selected");
    });
    $("#sidebar-dark-theme").on("click", function() {
      $body.removeClass(sidebar_classes);
      $body.addClass("sidebar-dark");
      $(".sidebar-bg-options").removeClass("selected");
      $(this).addClass("selected");
    });


    //Navbar Backgrounds
    $(".tiles.primary").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".navbar").addClass("navbar-primary");
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    $(".tiles.success").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".navbar").addClass("navbar-success");
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    $(".tiles.warning").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".navbar").addClass("navbar-warning");
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    $(".tiles.danger").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".navbar").addClass("navbar-danger");
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    $(".tiles.pink").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".navbar").addClass("navbar-pink");
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    $(".tiles.info").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".navbar").addClass("navbar-info");
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    $(".tiles.dark").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".navbar").addClass("navbar-dark");
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    $(".tiles.default").on("click", function() {
      $(".navbar").removeClass(navbar_classes);
      $(".tiles").removeClass("selected");
      $(this).addClass("selected");
    });
    
    // For left side bar search added by gautam makwana 10-01-2019
    /*------------------------------------------START------------------------------*/
      var navArray = [];
      var navHtml = $('#sidebar-menu').html();

      $('#sidebar-menu .nav-item a').each(function() {
        var text = $.trim($(this).text());
        var url = $(this).attr('href');
        var nav = []
        nav['text'] = text;
        nav['url'] = url;
        navArray.push(nav);
      });

      $("#searchsidebar").on("keyup", function () {
          var search = $(this).val().toLowerCase();
          var searchArray = [];
          if(search != ''){

            $.each(navArray, function(key, value){
              var val = (value['text']).toLowerCase();
              if(val.indexOf(search) >= 0){
                var tmp = [];
                tmp['text'] = value['text'];
                tmp['url'] = value['url'];
                if ($.inArray(value['text'], searchArray.text) == -1 && value['url'].charAt(0) != '#'){
                  searchArray.push(tmp);
                }
              }
            });
            
            if(searchArray.length > 0){
              $('#sidebar-menu').empty();
              $.each(searchArray, function(key, value){
                var append = '<li class="nav-item"><a class="nav-link" href="'+value['url']+'"><i class="fa fa-search"></i> &nbsp;&nbsp;&nbsp;<span class="menu-title">'+value['text']+'</span></a></li>';
                $('#sidebar-menu').append(append);
              });
            }else{
              $('#sidebar-menu').empty();
              var append = '<li class="nav-item"><a class="nav-link" href="javascript:void(0);"><span class="menu-title text-danger">No Result Found!</span></a></li>';
              $('#sidebar-menu').append(append);
            }
          }else{
            $('#sidebar-menu').html(navHtml);
          }
      });
    /*------------------------------------------END------------------------------*/
    
    /*--------------------USED FOR SESSION EVENT - GAUTAM MAKWANA - 05-02-19 - START------------------*/
    	function keepAlive() {
            var httpRequest = new XMLHttpRequest();
            httpRequest.open('GET', "index.php");
            httpRequest.send(null);
            console.log('keepAlive');
        }

        setInterval(keepAlive, 600000); //My session expires at 10 minutes
    /*--------------------USED FOR SESSION EVENT - GAUTAM MAKWANA - 05-02-19 - END------------------*/
    
    
  });
})(jQuery);