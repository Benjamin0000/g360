/*
Template Name: Monster Admin
Author: Themedesigner
Email: niravjoshi87@gmail.com
File: js
*/
$(function () {
    "use strict";
    var ini_currency = true;
    
    $(function () {
        $(".preloader").fadeOut();
    });
    jQuery(document).on('click', '.mega-dropdown', function (e) {
        e.stopPropagation()
    });
    // ==============================================================
    // This is for the top header part and sidebar part
    // ==============================================================
    var set = function () {
            var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
            var topOffset = 70;
            if (width < 1170) {
                $("body").addClass("mini-sidebar");
                $('.navbar-brand span').hide();
                $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
                $(".sidebartoggler i").addClass("ti-menu");
            }
            else {
                $("body").removeClass("mini-sidebar");
                $('.navbar-brand span').show();
                $(".sidebartoggler i").removeClass("ti-menu");
            }

            var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
            height = height - topOffset;
            if (height < 1) height = 1;
            if (height > topOffset) {
                $(".page-wrapper").css("min-height", (height) + "px");
            }

    };
    $(window).ready(set);
    $(window).on("resize", set);
    // ==============================================================
    // Theme options
    // ==============================================================
    $(".sidebartoggler").on('click', function () {
        if ($("body").hasClass("mini-sidebar")) {
            $("body").trigger("resize");
            $(".scroll-sidebar, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow", "visible");
            $("body").removeClass("mini-sidebar");
            $('.navbar-brand span').show();
            $(".sidebartoggler i").addClass("ti-menu");
        }
        else {
            $("body").trigger("resize");
            $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $("body").addClass("mini-sidebar");
            $('.navbar-brand span').hide();
            $(".sidebartoggler i").removeClass("ti-menu");
        }
    });
    // topbar stickey on scroll

    $(".fix-header .topbar").stick_in_parent({

    });


    // this is for close icon when navigation open in mobile view
    $(".nav-toggler").click(function () {
        $("body").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
        $(".nav-toggler i").addClass("ti-close");
    });
    $(".sidebartoggler").on('click', function () {
        $(".sidebartoggler i").toggleClass("ti-menu");
    });
    // ==============================================================
    // Right sidebar options
    // ==============================================================
    $(".right-side-toggle").click(function () {
        $(".right-sidebar").slideDown(50);
        $(".right-sidebar").toggleClass("shw-rside");

    });

    // ==============================================================
    // Auto select left navbar
    // ==============================================================
    $(function () {
        var url = window.location;
        var element = $('ul#sidebarnav a').filter(function () {
            return this.href == url;
        }).addClass('active').parent().addClass('active');
        while (true) {
            if (element.is('li')) {
                element = element.parent().addClass('in').parent().addClass('active');
            }
            else {
                break;
            }
        }

    });
    // ==============================================================
    //tooltip
    // ==============================================================
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    // ==============================================================
    //Popover
    // ==============================================================
    $(function () {
        $('[data-toggle="popover"]').popover()
    });
    // ==============================================================
    // Sidebarmenu
    // ==============================================================
    $(function () {
        $('#sidebarnav').metisMenu();
    });
    // ==============================================================
    // Slimscrollbars
    // ==============================================================
    $('.scroll-sidebar').slimScroll({
        position: 'right'
        , size: "10px"
        , height: '100%'
        , color: 'black'
     });
    $('.message-center').slimScroll({
        position: 'right'
        , size: "5px"

        , color: '#dcdcdc'
     });


    $('.aboutscroll').slimScroll({
        position: 'right'
        , size: "5px"
        , height: '80'
        , color: '#dcdcdc'
     });
    $('.message-scroll').slimScroll({
        position: 'right'
        , size: "5px"
        , height: '570'
        , color: '#dcdcdc'
     });
    $('.chat-box').slimScroll({
        position: 'right'
        , size: "5px"
        , height: '470'
        , color: '#dcdcdc'
     });

    $('.slimscrollright').slimScroll({
        height: '100%'
        , position: 'right'
        , size: "5px"
        , color: '#dcdcdc'
     });

    // ==============================================================
    // Resize all elements
    // ==============================================================
    $("body").trigger("resize");
    // ==============================================================
    // To do list
    // ==============================================================
    $(".list-task li label").click(function () {
        $(this).toggleClass("task-done");
    });

    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

     // ==============================================================
    // Collapsable cards
    // ==============================================================
    $(document).on("click", ".card-actions a", function(e) {
    if (e.preventDefault(), $(this).hasClass("btn-close")) $(this).parent().parent().parent().fadeOut();
    });

    // For Custom File Input
    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });

    (function ($, window, document) {
        var panelSelector = '[data-perform="card-collapse"]';
        $(panelSelector).each(function () {
            var $this = $(this)
                , parent = $this.closest('.card')
                , wrapper = parent.find('.card-block')
                , collapseOpts = {
                    toggle: false
                };
            if (!wrapper.length) {
                wrapper = parent.children('.card-heading').nextAll().wrapAll('<div/>').parent().addClass('card-block');
                collapseOpts = {};
            }
            wrapper.collapse(collapseOpts).on('hide.bs.collapse', function () {
                $this.children('i').removeClass('ti-minus').addClass('ti-plus');
            }).on('show.bs.collapse', function () {
                $this.children('i').removeClass('ti-plus').addClass('ti-minus');
            });
        });
        $(document).on('click', panelSelector, function (e) {
            e.preventDefault();
            var parent = $(this).closest('.card');
            var wrapper = parent.find('.card-block');
            wrapper.collapse('toggle');
        });
    }(jQuery, window, document));

    //datatable
    if (typeof $.fn.DataTable != 'undefined') {

        $("table.datatable").DataTable();
    }

    //date picker
    if (typeof $.fn.datepicker != 'undefined') {
        jQuery('#datepicker, .datepicker').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    }

    if(ini_currency == true){

        $("input[type='text'].currency").each(function(a,s){
            var $l = $(s);
            if($l.val() == 0)
                $l.data("formated",false);
            else{
                $l.data("value",$l.val());
                $l.val(format($l.val()));
                $l.data("formated",true);
            }
        });
        $("input[type='text'].currency").on("blur",function(){
            var $this = $(this);
            var value = "";
            var tmp = $this.val().split(',');
            for(var i in tmp){
                value +=''+tmp[i];
            }
            value = parseFloat(value);
            if(isNaN(value) || value =="" || value ==" "){
                $this.data("formated",false);
                $this.data("value",0);
                $this.val(0);
                return;
            }
            $this.data("value",parseFloat(value));
            $this.val(format(value));
            $this.data("formated",true);
        });
        $("input[type='text'].currency").on("focus",function(e){
            var $this = $(this);
            if($this.data("formated") == true){
                $this.val($this.data("value"));
            }
            if(e.which == 9) {
                e.preventDefault();
            }
        });
    
        $("form").on("submit",function(e){
            $("input[type='text'].currency").each(function(a,s){
                var $e  = $(s);
                if($e.data("formated") == true)
                    $e.val($e.data("value"));
            });
        });
    }
    

});

function format(e){
    if(isNaN(parseFloat(e)))
        return 0;
    var temp = new Intl.NumberFormat("en-us",{ 
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 10
                                });
    return temp.format(parseFloat(e));
}

var xhr_error = "we're currently unable to process your request, try again later<br/>";

function get_loader(m)
{
    m = m == null | m == undefined ? 'loading...' : m;
    var loader = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> '+m;
    return loader;
}

var _curr_sec;
var rsend = false;
function _down(sec){
    _curr_sec = 60;
    $r = $("#rsend");
    $r.data('text',$r.text());
    $r.removeClass('text-info');
    $r.addClass('text-warning');

    var handle = setInterval(function(e){
        if(_curr_sec < 1)
        {
            rsend = true;
            $r.text($r.data('text'));
            clearInterval(handle);
            $r.addClass('text-info');
            $r.removeClass('text-warning');
            $("#ss_zone2").text('');
        }
        else
        {
            $r.text($r.data('text') +' in '+ _curr_sec);
            _curr_sec--;
        }
    },1000);
}

