$(function() {

    activateCK();
    activateDragField();
    fixHeights();

    //AUTOCOMPLETE
    $('.autocomplete_item input').each(function(index, ele) {
        $(ele).autocomplete({
            minLength: 2,
            source: function(request, response) {
                $.getJSON($('.form_pages').data('url'), {
                    term: split(request.term).pop()
                }, function(data) {
                    response((data) ? data : []);
                });
            },
            focus: function() {
                return false;
            },
            change: function (event, ui) {
                if(!ui.item){
                    $(event.target).val("");
                }
            }
        });
    });

    //INSCRIPTION
    if ($('.form_pages').length > 0) {

        $(document).on('submit', '.form_place_edit', function(evt) {
            if (evt.delegateTarget.activeElement.type!=="submit") {
                evt.preventDefault();
            }
        });

        // Pages
        var pageActive = 0;
        var changePage = function() {
            $('.form_page').hide();
            $('.form_page:eq(' + pageActive + ')').show();
            $('.form_submit_wrapper').hide();
            (pageActive == 0) ? $('.form_button_previous').hide() : $('.form_button_previous').show();
            (pageActive >= $('.form_page').length-1) ? $('.form_button_next').hide() : $('.form_button_next').show();
            (pageActive >= $('.form_page').length-1) ? $('.form_buttons').addClass('form_buttons_bottom') : $('.form_buttons').removeClass('form_buttons_bottom');
            if (pageActive == $('.form_page').length-1) {
                $('.form_submit_wrapper').show();
            }
        }
        var checkErrors = function() {
            var errors = [];
            $('.form_field_title').removeClass('error_field');
            $('.form_field_address').removeClass('error_field');
            $('.form_field_city').removeClass('error_field');
            $('.form_field_city_other').removeClass('error_field');
            $('.form_field_short_description').removeClass('error_field');
            $('.form_field_name_editor').removeClass('error_field');
            $('.form_field_email_editor').removeClass('error_field');
            $('.form_field_tags').removeClass('error_field');
            if (pageActive == 1) {
                if ($('.form_field_title input').val().trim()==='') {
                    errors.push('title');
                    $('.form_field_title').addClass('error_field');
                }
            }
            if (pageActive == 2) {
                if ($('.form_field_email input').val().trim()!='' && !validateEmail($('.form_field_email input').val())) {
                    errors.push('email');
                    $('.form_field_email').addClass('error_field');
                }
            }
            if (pageActive == 4) {
                if ($('.form_field_address textarea').val().trim()==='') {
                    errors.push('address');
                    $('.form_field_address').addClass('error_field');
                }
                if ($('.form_field_city select').is(':visible') && $('.form_field_city select').val().trim()==='') {
                    errors.push('city');
                    $('.form_field_city').addClass('error_field');
                }
                if ($('.form_field_cityother input').is(':visible') && $('.form_field_cityother input').val().trim()==='') {
                    errors.push('cityother');
                    $('.form_field_cityother').addClass('error_field');
                }
            }
            if (pageActive == 5) {
                if ($('.form_field_short_description textarea').val().trim()==='') {
                    errors.push('shortdescription');
                    $('.form_field_short_description').addClass('error_field');
                }
                if ($('.form_field_tags input').val().trim()==='') {
                    errors.push('idtag');
                    $('.form_field_tags').addClass('error_field');
                }
            }
            if (pageActive == 8) {
                if ($('.form_field_name_editor input').val().trim()==='') {
                    errors.push('nameeditor');
                    $('.form_field_name_editor').addClass('error_field');
                }
                if ($('.form_field_email_editor input').val().trim()==='' || !validateEmail($('.form_field_email_editor input').val())) {
                    errors.push('emaileditor');
                    $('.form_field_email_editor').addClass('error_field');
                }
            }
            return errors;
        }
        $(document).on('click touch', '.form_button_next', function(evt) {
            if (checkErrors().length == 0) {
                pageActive = (pageActive < $('.form_page').length-1) ? pageActive + 1 : pageActive;
                if (pageActive==10 && $('input[name=choice_promotion]').val()=='not-promoted') {
                    pageActive = pageActive + 1;
                }
                changePage();
            }
        });
        $(document).on('click touch', '.form_button_previous', function(evt) {
            if (pageActive==11 && $('input[name=choice_promotion]').val()=='not-promoted') {
                pageActive = pageActive - 1;
            }
            pageActive = (pageActive > 0) ? pageActive - 1 : 0;
            changePage();
        });
        if ($('.form_page').length > 0) {
            changePage();
        }

        // City
        $(document).on('click touch', '.trigger_city', function(evt) {
            $('.trigger_city_select').hide();
            $('.trigger_city_info').show();
        });

        // Promotion
        $(document).on('click touch', '.form_promotion_item', function(evt) {
            $('.form_promotion_item i').removeClass('icon-checked');
            $(this).find('i').addClass('icon-checked');
            var value = $(this).data('value');
            $('input[name=promoted]').val(value);
        });

        // Promotion
        $(document).on('click touch', '.form_payment_item', function(evt) {
            $('.form_payment_item i').removeClass('icon-checked');
            $(this).find('i').addClass('icon-checked');
            var value = $(this).data('value');
            $('input[name=choice_payment]').val(value);
        });

    }

    //RATING
    var activateStars = function() {
        $('.rating_all_wrapper').each(function(index, ele){
            var valueRating = $(ele).find('input').val() * 1;
            $(ele).find('.rating i').removeClass('icon-star');
            $(ele).find('.rating i').addClass('icon-star-empty');
            for (var i=0; i<valueRating; i++) {
                $(ele).find('.rating i').eq(i).addClass('icon-star');
            }
        });
    }
    activateStars();
    $(document).on('click touch', '.rating', function(evt){
        var inputText = $(this).parents('.rating_all_wrapper').first().find('input');
        inputText.val($(this).data('rating'));
        activateStars();
    });

    //MAPS
    //activateMaps();

    //SMOOTH SCROLL
    smoothScroll();

});

$(window).on('load', function() {
    fixHeights();
});

$(window).on('resize', function() {
    fixHeights();
});

$(window).on('scroll', function() {
});

function activateMenu() {
    $('.menu_mobile').click(function(){
        $('.menu_wrapper').toggle();
    });
}

function activateMaps() {
    if ($('.point_map').length > 0) {
        $('.point_map').each(function(index, ele){
            var initLat = $(ele).data('initlat') * 1;
            var initLng = $(ele).data('initlng') * 1;
            var initZoom = $(ele).data('initzoom') * 1;
            var mapLat = $(ele).find('.map').data('lat') * 1;
            var mapLng = $(ele).find('.map').data('lng') * 1;
            var mapZoom = $(ele).find('.map').data('zoom') * 1;
            var mapIns = $(ele).find('.mapIns');
            var inputLat = $(ele).find('.input_lat');
            var inputLng = $(ele).find('.input_lng');
            var checkboxShowHide = $(ele).find('.show_hide input[type=checkbox]');
            var activateSingleMap = function() {
                mapLat = (mapLat!=0) ? mapLat : initLat;
                mapLng = (mapLng!=0) ? mapLng : initLng;
                mapZoom = (mapZoom!=0) ? mapZoom : initZoom;
                inputLat.val(mapLat);
                inputLng.val(mapLng);
                var mapOptions = {
                    zoom: mapZoom,
                    center: new google.maps.LatLng(mapLat, mapLng)
                };
                var mapEle = new google.maps.Map(document.getElementById(mapIns.attr('id')), mapOptions);
                markerPort = new google.maps.Marker({
                    position: new google.maps.LatLng(mapLat, mapLng),
                    map: mapEle
                });
                google.maps.event.addListener(mapEle, 'click', function(newPosition) {
                    markerPort.setPosition(newPosition.latLng);
                    inputLat.val(newPosition.latLng.lat());
                    inputLng.val(newPosition.latLng.lng());
                });
            }
            if (checkboxShowHide.length > 0) {
                if (mapLat=='' || mapLng=='') {
                    checkboxShowHide.attr('checked', false);
                    $('.map').hide();
                } else {
                    checkboxShowHide.attr('checked', true);
                    activateSingleMap();
                }
                checkboxShowHide.click(function(){
                    if ($(this).is(':checked')) {
                        $('.map').show();
                        activateSingleMap();
                    } else {
                        $('.map').hide();
                        inputLat.val('');
                        inputLng.val('');
                    }
                });
            } else {
                activateSingleMap();
            }
        });
    }
}

function smoothScroll() {
    $('a[href*="#"]')
        .not('[href="#"]')
        .not('[href="#0"]')
        .click(function(event) {
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                &&
                location.hostname == this.hostname
            ) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000, function() {
                        var $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) {
                            return false;
                        } else {
                            $target.attr('tabindex','-1');
                            $target.focus();
                        };
                    });
                }
            }
        });
}

function fixHeights() {
    $('.content_wrapper-form .content_wrapper_ins').css('min-height', $(window).height() - 70);
    $('.form_pages_wrapper .form_pages .form_page').css('min-height', $(window).height() - 140);
}

function activateDragField() {
    $(document).on('change', '.drag_field_file input[type=file]', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var container = $(this).parents('.form_field').first();
        var file = $(this)[0]['files'][0];
        loadFileField(file, container);
    });
    $(document).on('click', '.drag_field_drag', function(event) {
        event.stopImmediatePropagation();
        $(this).parents('.form_field').first().find('input[type=file]').trigger('click');
    });
    $(document).on('dragleave', '.drag_field_drag', function(event) {
        event.preventDefault();
        $(this).removeClass('drag_field_drag_selected');
    });
    $(document).on('dragover', '.drag_field_drag', function(event) {
        event.preventDefault();
        $(this).addClass('drag_field_drag_selected');
    });
    $(document).on('drop', '.drag_field_drag', function(event) {
        event.preventDefault();
        $(this).removeClass('drag_field_drag_selected');
        var file = event.originalEvent.dataTransfer.files[0];
        var container = $(this).parents('.form_field').first();
        loadFileField(file, container);
    });
}
function resizeBase64Img(base64, maxWidth, maxHeight) {
    return new Promise((resolve, reject) => {
        let img = document.createElement("img");
        img.src = base64;
        img.onload = function() {
            var mode = (img.width > img.height) ? 'horizontal' : 'vertical';
            var ratio = (img.width > img.height) ? img.height / img.width : img.width / img.height;
            if (img.width > img.height) {
                var newWidth = Math.ceil((img.width > maxWidth) ? maxWidth : img.width);
                var newHeight = Math.ceil(newWidth * img.height / img.width);
            } else {
                var newHeight = Math.ceil((img.height > maxHeight) ? maxHeight : img.height);
                var newWidth = Math.ceil(newHeight * img.width / img.height);
            }
            var canvas = document.createElement("canvas");
            canvas.width = newWidth;
            canvas.height = newHeight;
            let context = canvas.getContext("2d");
            context.drawImage(img, 0, 0, newWidth, newHeight);
            resolve(canvas.toDataURL());
        }
    });
}
 function loadFileField(file, container) {
    var reader = new FileReader();
    reader.onloadend = function() {
        if (reader.result != '') {
            var containerData = container.find('.drag_field_wrapper');
            if (containerData.data('maxdimensions')) {
                resizeBase64Img(reader.result, containerData.data('maxwidth'), containerData.data('maxheight')).then((result)=>{
                    processFileField(result, file, container);
                });
            } else {
                processFileField(reader.result, file, container);
            }
        }
    };
    reader.readAsDataURL(file);
}
function processFileField(baseString, file, container) {
    var fileInput = container.find('input.filevalue').first();
    var fileInputName = container.find('input.filename').first();
    var fileInputUploaded = container.find('input.filename_uploaded').first();
    var fileInputFile = container.find('input.filename_input').first();
    var imageContainer = container.find('img').first();
    var fileContainer = container.find('.drag_field_file_name').first();
    var loader = container.find('.drag_field_loader').first();
    var loaderBar = container.find('.drag_field_loader_bar').first();
    var loaderMessage = container.find('.drag_field_loader_message').first();
    fileInput.val(baseString);
    fileInputName.val(file.name);
    fileInputFile.val(null);
    if (imageContainer) {
        imageContainer.attr('src', baseString);
        imageContainer.parents('.drag_field_image').show();
    }
    if (fileContainer) {
        fileContainer.find('em').html(file.name);
        fileContainer.show();
    }
    // Start uploading the image
    loaderBar.removeClass('drag_field_loader_bar_loaded');
    loaderMessage.html(loaderMessage.data('messageloading'));
    $.post({
        url: container.data('urluploadtemp'),
        data: {
            "file": baseString,
            "filename": fileInputName.val()
        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentage = Math.ceil((evt.loaded / evt.total) * 100);
                    loaderBar.css('width', percentage + '%');
                    loaderMessage.html(loaderMessage.data('messageloading') + ' (' + percentage + ' %)');
                    if (percentage == 100) {
                        loaderMessage.html(loaderMessage.data('messagesaving'));
                        loaderBar.addClass('drag_field_loader_bar_loaded');
                    }
                }
            }, false);
            return xhr;
        }
    }).done(function(response) {
        loaderBar.removeClass('drag_field_loader_bar_loaded');
        if (response.status == 'OK') {
            fileInput.val('');
            fileInputUploaded.val(response.file);
            loaderMessage.html(loaderMessage.data('messagesavedas') + ' : ' + response.filename);
        } else {
            loaderMessage.html(response.message_error || 'Error');
        }
    }).fail(function(event) {
        loaderMessage.html('');
        loaderBar.removeClass('drag_field_loader_bar_loaded');
    });
}

function activateCK() {
    $('.ckeditorArea textarea').each(function(index, ele) {
        if ($(ele).attr('rel') != 'ckeditor') {
            $(ele).attr('rel', 'ckeditor');
            if ($(ele).attr('id') == '' || $(ele).attr('id') == undefined) {
                $(ele).attr('id', randomString());
            }
            CKEDITOR.replace($(ele).attr('id'), {
                height: '450px',
                toolbar: [{
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup'],
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
                }, {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
                }, '/', {
                    name: 'document',
                    groups: ['mode', 'document', 'doctools'],
                    items: ['Source']
                }, {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo'],
                    items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                }, {
                    name: 'links',
                    items: ['Link', 'Unlink', 'Anchor']
                }, {
                    name: 'insert',
                    items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
                }, '/', {
                    name: 'styles',
                    items: ['Format', 'Font', 'FontSize']
                }, {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                }, {
                    name: 'tools',
                    items: ['Maximize', 'ShowBlocks', 'CodeSnippet', '-', 'Templates']
                }, ]
            });
        }
    });
    $('.ckeditorAreaSimple textarea').each(function(index, ele) {
        if ($(ele).attr('rel') != 'ckeditor') {
            $(ele).attr('rel', 'ckeditor');
            if ($(ele).attr('id') == '' || $(ele).attr('id') == undefined) {
                $(ele).attr('id', randomString());
            }
            CKEDITOR.replace($(ele).attr('id'), {
                height: '250px',
                toolbar: [{
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup', 'list', 'indent', 'blocks', 'align', 'bidi'],
                    items: ['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Link', 'Unlink', 'Image']
                }, '/', {
                    name: 'styles',
                    items: ['Format', 'Font', 'FontSize', '-', 'TextColor', 'BGColor']
                }]
            });
        }
    });
}

function randomString() {
    return Math.random().toString(36).substring(7);
}

function split( val ) {
    return val.split( /,\s*/ );
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function extractLast( term ) {
    return split( term ).pop();
}
