/**
 * Created with JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-3
 * Time: 下午1:39
 * To change this template use File | Settings | File Templates.
 */



$(function () {

    $("#btn_new").on("click",function(){
        location.href=rootURL+'/Admin/Category/add';
    });

    //小X
    $('.del').click(function () {

        var ids = new Array();

        var idObj = $(this).attr('id').split('_');

        var id = idObj[2];
        var isParent =idObj[3]
        ids.push(id);


        
        if(isParent > 0){
            jConfirm("当前分类下仍有分类，是否删除其下所有分类？","分类删除确认消息",function(r){
                if(r){
                    $.post(rootURL+'/Admin/Category/remove', {'ids': ids}, function (data) {
                        var status = data.status;
                        var info = data.info;

                        if (status) {
                            jConfirm(info,'分类删除',function(r){
                                location.reload();
                            });
                        } else {
                            jAlert(info);
                        }
                    });
                }else{
                }
            });
        }else{
            jConfirm("请确认要删除的分类，删除后无法恢复","分类删除确认消息",function(r){
                if(r){
                    $.post(rootURL+'/Admin/Category/remove', {'ids': ids}, function (data) {
                        var status = data.status;
                        var info = data.info;

                        if (status) {
                            
                                location.reload();
                          

                        } else {
                            jAlert(info);
                        }
                    });
                }else{
                }
            });
        }

    });



    //删除按钮

    $('#btn_delete').click(function () {

        //删除操作
        var ids = new Array();
        $("input[name='cat_checkbox']:checked").each(function () {
            var id = $(this).attr('id').split('_')[1];
            ids.push(id);
        });
        
        if(ids.length < 1){
            jAlert("请选择要删除的信息",'删除提示消息');

            return flase;
        }

        jConfirm("如果所选分类下有子分类,则会全部删除 ,是否确认删除分类,删除后不可恢复?","分类删除确认消息",function(r){
            if(r){
                $.post(rootURL+'/Admin/Category/remove', {'ids': ids}, function (data) {
                    var status = data.status;
                    var info = data.info;

                    if (status) {
                            location.reload();
                        
                    } else {
                        jAlert(info);
                    }
                });
            }else{
            }
        });
    });

    $("#btn-search").on("click",function(){

            var keyword = $("#keyword").val();
            if(keyword == "" || keyword == null){
               jAlert('请输入搜索关键字！','搜索提示',function(r){
                           
                });
                return flase;
            }else{
               $.post(rootURL+'/Admin/Category/ajaxSearchCategory', {'keyword': keyword }, function (data) {
                    $("#list_body").html(data.info);
                }); 
            }

            
        }
    );


});