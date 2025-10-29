var Paginate = function(options) {
    var settings = {}; 

    settings = $.extend(true, {}, settings, options);

    var init = function() {
        console.log('test');
        initComponent();
    };

    var initComponent = function() {
        $('.delete-btn').on('click', function(e) {
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
                callback: function (result) {
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
}

$(function() {
    var options = {};

    if (window.global != undefined) {
        options = window.global
    }

    if ($("#paginate").length) {
        var paginate = new Paginate(options);
        paginate.init();
    }
});
