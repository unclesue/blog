
$.pjax.defaults.timeout = 5000;
$.pjax.defaults.maxCacheLength = 0;
$(document).pjax('a:not(a[target="_blank"])', {
    container: '#pjax-container'
});
