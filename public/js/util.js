let util = {
    ajax: function (obj, middleware) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var deferred = $.Deferred();
        $.ajax({
            url: obj.url || '/interface',
            data: obj.data || {},
            dataType: obj.dataType || 'json',
            type: obj.type || 'get',
        }).success(function (data) {
            if (data.code != 200) {
                deferred.reject(data.err_msg);
            } else {
                deferred.resolve(data.data)
            }
        }).error(function (err) {
            deferred.reject(err);
        })

        // 添加中间件
        if (!middleware) {
            middleware = function () {
            };
        }

        return deferred.done(middleware);
    }

}
