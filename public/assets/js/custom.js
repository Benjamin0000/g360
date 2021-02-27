/*
Template Name: Monster Admin
Author: Themedesigner
Email: niravjoshi87@gmail.com
File: js
*/
$(function () {
    "use strict";
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
        position: 'left'
        , size: "5px"
        , height: '100%'
        , color: '#dcdcdc'
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

    var loader = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
    
    $("#signupform").on('submit',function(e){
        
        /*if (e.target.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
        }*/
        e.preventDefault();
        e.stopPropagation();
        
        var $form = $(this);
        var $ele = $form.find('#loginbtn');
        
        $ele.data('text',$ele.text());
        $ele.html(get_loader());
        $ele.prop('disabled',true);
        
        var datastring = $form.serialize();
        
        $.ajax({
            type: "POST",
            url: '/register',
            data: datastring,
            dataType: "json",
            success: function(data) {
                console.log(data);
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);

                Array.prototype.filter.call(data.rv, function(obj) {
                    var $e =  $("#"+obj.ele);
                    $e.removeClass('is-valid');
                    $e.removeClass('is-invalid');
                    obj.status == true ? $e.addClass('is-valid') : $e.addClass('is-invalid');

                    var $parent = $e.parent();
                    $parent.find('.invalid-feedback,.valid-feedback').remove();
                    
                    var clas = obj.status ? 'valid-feedback' : 'invalid-feedback';
                    var div = document.createElement('div');
                    div.classList = clas;
                    div.innerHTML = obj.msg;

                    $parent.append(div);
                });
                    
                if(data.status)
                {
                    $form.data('nounce',data.nounce);
                    $form.addClass('was-validated');
                    $("#err_zone").html('');
                    $form.slideUp();
                    $("#vform").fadeIn();
                    $("#reference").val(data.ref);
                    _down();
                }
                else
                {
                    $ele.text($ele.data('text'));
                    $ele.prop('disabled',false);
                    $("#err_zone").html(data.msg);
                }
            },
            error: function(xhr, status, error) {
                console.log('error',xhr.responseText);
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                $("#err_zone").html(xhr_error);
            }
        });
        
    });

    $("#vform").on('submit',function(e){

        var $form = $(this);
        if($form.data('forward'))
        {
            return;
        }
        e.preventDefault();
        e.stopPropagation();
        
        var $ele = $form.find('#vbtn');
        
        $ele.data('text',$ele.text());
        $ele.html(loader);
        $ele.prop('disabled',true);
        
        var datastring = $form.serialize();

        $.ajax({
            type: "POST",
            url: baseurl+'intl/check_verification_code',
            data: datastring,
            dataType: "json",
            success: function(data) {
                console.log(data);
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                                
                if(data.status)
                {
                    $form.data('forward',true);
                    $form.submit();
                }
                else
                {
                    var $in = $("#vcode");
                    $in.removeClass('is-valid');
                    $in.addClass('is-invalid');
                    $("#vcode_error").text(data.msg);
                }
            },
            error: function(xhr, status, error) {
                console.log('error',xhr.responseText);
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                $("#err_zone2").html(xhr_error);
            }
        });
    });
    
    $("#rsend").click(function(e){

        var $ele = $(this);
        if(rsend == false)
        {
            return;
        }
        
        $ele.data('text',$ele.text());
        $ele.html(get_loader(''));
        $("#err_zone2").text('');

        $.ajax({
            type: "POST",
            url: baseurl+'intl/resend_verification_sms',
            data: {ref:$("#reference").val()},
            dataType: "json",
            success: function(data) {
                console.log(data);
                
                $ele.text($ele.data('text'));
                if(data.status)
                {
                    rsend = false;
                    $("#ss_zone2").text(data.msg);
                    _down();
                }
                else { $("#err_zone2").text(data.msg); }
            },
            error: function(xhr, status, error) {
                console.log('error',xhr.responseText);
                $ele.text($ele.data('text'));
                $("#err_zone2").html(xhr_error);
            }
        });
    });

    $("#loginform").on('submit',function(e){

        var $form = $(this);
        
        e.preventDefault();
        e.stopPropagation();
        
        var $ele = $form.find('button');
        
        $ele.data('text',$ele.text());
        $ele.html(loader);
        $ele.prop('disabled',true);
        
        var datastring = $form.serialize();

        $.ajax({
            type: "POST",
            url: '/login',
            data: datastring,
            dataType: "json",
            success: function(data) {
                console.log(data);
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                if(data.status)
                {
                    window.location.href = data.d.r;
                }
                else
                {
                    $("#err_zone2").text(data.msg);
                }
            },
            error: function(xhr, status, error) {
                console.log('error',xhr.responseText);
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                $("#err_zone2").html(xhr_error);
            }
        });
    });

    //datatable
    if (typeof $.fn.DataTable != 'undefined') {

        $("table.datatable").DataTable();
    }

    //date picker
    if(typeof $.fn.datepicker != 'undefined')
    {
        jQuery('#datepicker, .datepicker').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    }
});

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

