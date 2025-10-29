var UploadImage = function(options) {
    var settings = {}; 

    settings = $.extend(true, {}, settings, options);

    var init = function() {
        bindEvent();
        showInputFile();
    };

    var bindEvent = function() {
        $("body").on('click', '.delete-img-btn', deleteImage);
        $("body").on('change', '.img-file', verifyFile);

        if ($('#type-sel').length > 0) {
            $("body").on('change', '#type-sel', changeType);
            $('#type-sel').trigger('change');
        }
    }

    var deleteImage = function(evt) {
        var $element = $(evt.target);
        var inputHidden = $("<input/>").attr('type', 'hidden').attr('name', 'delete_image[]').val($element.data('id'));
        $("form").append(inputHidden);
        $element.parents('.col').remove();
        showInputFile();
    }

    var showInputFile = function() {
        var numUploadedImage = $('.img-upload-container').length;
        if (numUploadedImage == 5) {
            $('#img-upload-label').hide();
        } else {
            $('#img-upload-label').show();
        }

        if (numUploadedImage > 0) {
            $('#img-label').show();
        } else {
            $('#img-label').hide();
        }

        for (var i = 1; i <= 5; i++) {
            if (i <= numUploadedImage) {
                $('#image-' + i).hide();
            } else {
                $('#image-' + i).show();
            }
        }
    }

    var verifyFile = function(evt) {
        var image = evt.currentTarget
        if (typeof (image.files) != "undefined") {
            if (!image.files[0].name.match(/[\.png|\.jpg|\.jpeg]$/i) || $.inArray(image.files[0].type, ['image/png', 'image/jpeg']) === -1) {
                bootbox.alert('กรุณาอัพโหลดไฟล์รูปภาพ jpg, jpeg หรือ png');
                image.value = null;
                return false;
            }

            var size = parseFloat(image.files[0].size / (1024 * 1024)).toFixed(2); 
            if(size > 1) {
                bootbox.alert('กรุณาอัพโหลดไฟล์รูปภาพขนาดไม่เกิน 1 MB');
                image.value = null;
                return false;
            }
        } else {
            alert("This browser does not support HTML5.");
        }
    }

    var changeType = function(evt) {
        if (evt.target.value == 1) {
            $('.disease-form-group').show();
            $('.insect-form-group').hide();
        } else {
            $('.disease-form-group').hide();
            $('.insect-form-group').show();
        }
    }

    return {
        init: init
    };
}

$(function() {
    var options = {};

    if (window.global != undefined) {
        options = window.global
    }

    if ($("#upload-image-app").length) {
        var uploadImage = new UploadImage(options);
        uploadImage.init();
    }
});
