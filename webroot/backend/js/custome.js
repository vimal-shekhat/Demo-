$(function () {

    $('.summernote').each(function () {
        var _this = $(this);

        var progressEl = $("<progress></progress>").insertBefore(_this).hide();
        // update progress bar

        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                //var  progressEl  = _this.closest('progress');
                progressEl.show().attr({value: e.loaded, max: e.total});
                // reset progress on complete
                if (e.loaded == e.total) {
                    progressEl.hide().attr('value', '0.0');
                }
            }
        }

        _this.summernote({
            height: 240,
            toolbar: [
                ['style', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['table', ['table']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['picture', 'link', 'video']],
                ['view', ['fullscreen', 'codeview']],
            ],
            onChange: function (e) {
                var editor = $(this);
                _this.val(editor.code());
                _this.trigger('change');
            },
            onImageUpload: function (file, editor, welEditable) {

                data = new FormData();
                data.append("file", file[0]);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: FullPath + "admin/users/fileupload",
                    cache: false,
                    contentType: false,
                    processData: false,
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload)
                            myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                        return myXhr;
                    },
                    success: function (url) {
                        //editor.insertImage(welEditable, url);
                        $(_this).summernote('editor.insertImage', url);
                    }
                });
            }
        });
    });
});