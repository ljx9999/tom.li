/**
 * Created with JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-16
 * Time: 上午11:57
 * To change this template use File | Settings | File Templates.
 */
(function($){
    $.extend(true,
        {
            sortOptionParam:{
                queryStrDefault:"ajaxSearch",
                imgDir : jsImagesDir,
                sortBy : '',
                sortOrder : ''
            },

            mbizTableSort : function(sort_by,callbackFun,type){
                var event = arguments[0]||window.event;
                var sortObj = event.currentTarget;

                this.sortOptionParam.sortBy = sort_by;
                var sortObjId = $.trim($(sortObj).attr("title"));

                var sort_order = (sortObjId.toUpperCase()  == "倒序") ? "ASC" : "DESC";

                this.sortOptionParam.sortOrder = sort_order;
                //正常情况传递两个值，由于导航栏需要传递三个值，所以加个判断
                if(type != ''){
                    var paraStr = "sort_by="+sort_by+"&sort_order="+sort_order+"&type="+parseInt(type);
                }else{
                    var paraStr = "sort_by="+sort_by+"&sort_order="+sort_order;
                }

                var urlStr = window.SITE_URL;
                var re =/\/admin\/(\w+)\/?/i;
                var arr = location.href.match(re);
                urlStr += arr[0] ;
                urlStr += this.sortOptionParam.queryStrDefault ;

                $.ajax({
                    type: "POST",
                    url: urlStr,
                    data: paraStr,
                    dataType : "JSON",
                    success: eval(callbackFun)
                });


            },
            getSortBy:function(){
                return this.sortOptionParam.sortBy;
            },
            getSortObj:function(){
                return this.sortOptionParam.sortObj;
            },
            getSortOrder:function(){
                return this.sortOptionParam.sortOrder;
            },
            getSortImage:function(){
                return this.imgDir+"/"+this.sortOptionParam.sortBy.toLowerCase()+".gif";
            },

            changeSortImage:function(){
                var titleLang = (this.sortOptionParam.sortOrder.toLowerCase() == 'desc')?'倒序':'升序';
                $("."+this.sortOptionParam.sortBy).parent('a:first').attr("title",titleLang);
                $("."+this.sortOptionParam.sortBy).attr("src",this.sortOptionParam.imgDir+"/"+this.sortOptionParam.sortOrder.toLowerCase()+".gif");
            }

        });

})(jQuery);