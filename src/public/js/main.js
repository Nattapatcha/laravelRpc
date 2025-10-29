(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

require('./upload-image.js');
require('./paginate');
require('./map');

},{"./map":2,"./paginate":3,"./upload-image.js":4}],2:[function(require,module,exports){
"use strict";

var Map = function Map(options) {
    var settings = {};

    settings = $.extend(true, {}, settings, options);

    var init = function init() {
        renderMap();
    };

    var renderMap = function renderMap() {
        var mapContainer = document.getElementById('map');

        if (mapContainer == undefined) {
            return false;
        }

        var map = new google.maps.Map(mapContainer, {
            zoom: 15,
            center: settings.position
        });

        console.log(settings);

        new google.maps.Marker({
            position: settings.position,
            map: map,
            title: ""
        });
    };

    return {
        init: init
    };
};

$(function () {
    var options = {};

    if (window.global != undefined) {
        options = window.global;
    }

    if ($("#map").length) {
        var map = new Map(options);
        map.init();
    }
});

},{}],3:[function(require,module,exports){
'use strict';

var Paginate = function Paginate(options) {
    var settings = {};

    settings = $.extend(true, {}, settings, options);

    var init = function init() {
        console.log('test');
        initComponent();
    };

    var initComponent = function initComponent() {
        $('.delete-btn').on('click', function (e) {
            e.preventDefault();

            bootbox.confirm({
                message: "คุณต้องการลบข้อมูลนี้ใช้หรือไม่",
                buttons: {
                    confirm: {
                        label: 'ตกลง',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'ยกเลิก',
                        className: 'btn-danger'
                    }
                },
                callback: function callback(result) {
                    if (result) {
                        window.location = e.target.href;
                    }
                }
            });
        });
    };

    return {
        init: init
    };
};

$(function () {
    var options = {};

    if (window.global != undefined) {
        options = window.global;
    }

    if ($("#paginate").length) {
        var paginate = new Paginate(options);
        paginate.init();
    }
});

},{}],4:[function(require,module,exports){
'use strict';

var UploadImage = function UploadImage(options) {
    var settings = {};

    settings = $.extend(true, {}, settings, options);

    var init = function init() {
        bindEvent();
        showInputFile();
    };

    var bindEvent = function bindEvent() {
        $("body").on('click', '.delete-img-btn', deleteImage);
        $("body").on('change', '.img-file', verifyFile);

        if ($('#type-sel').length > 0) {
            $("body").on('change', '#type-sel', changeType);
            $('#type-sel').trigger('change');
        }
    };

    var deleteImage = function deleteImage(evt) {
        var $element = $(evt.target);
        var inputHidden = $("<input/>").attr('type', 'hidden').attr('name', 'delete_image[]').val($element.data('id'));
        $("form").append(inputHidden);
        $element.parents('.col').remove();
        showInputFile();
    };

    var showInputFile = function showInputFile() {
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
    };

    var verifyFile = function verifyFile(evt) {
        var image = evt.currentTarget;
        if (typeof image.files != "undefined") {
            if (!image.files[0].name.match(/[\.png|\.jpg|\.jpeg]$/i) || $.inArray(image.files[0].type, ['image/png', 'image/jpeg']) === -1) {
                bootbox.alert('กรุณาอัพโหลดไฟล์รูปภาพ jpg, jpeg หรือ png');
                image.value = null;
                return false;
            }

            var size = parseFloat(image.files[0].size / (1024 * 1024)).toFixed(2);
            if (size > 1) {
                bootbox.alert('กรุณาอัพโหลดไฟล์รูปภาพขนาดไม่เกิน 1 MB');
                image.value = null;
                return false;
            }
        } else {
            alert("This browser does not support HTML5.");
        }
    };

    var changeType = function changeType(evt) {
        if (evt.target.value == 1) {
            $('.disease-form-group').show();
            $('.insect-form-group').hide();
        } else {
            $('.disease-form-group').hide();
            $('.insect-form-group').show();
        }
    };

    return {
        init: init
    };
};

$(function () {
    var options = {};

    if (window.global != undefined) {
        options = window.global;
    }

    if ($("#upload-image-app").length) {
        var uploadImage = new UploadImage(options);
        uploadImage.init();
    }
});

},{}]},{},[1]);

//# sourceMappingURL=main.js.map
