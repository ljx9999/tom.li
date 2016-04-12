/**
 * Created with JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-16
 * Time: 上午11:57
 * To change this template use File | Settings | File Templates.
 */
(function($){
    /*
     * $.import_js() helper (for javascript importing within javascript).
     */
    var import_js_imported = [];
    $.extend(true,
        {
            import_js : function(script)
            {
                var found = false;
                for (var i = 0; i < import_js_imported.length; i++)
                    if (import_js_imported[i] == script) {
                        found = true;
                        break;
                    }
                if (found == false) {
                    $("head").append('<script type="text/javascript" src="' + script + '"></script>');
                    import_js_imported.push(script);
                }
            }
        });

})(jQuery);