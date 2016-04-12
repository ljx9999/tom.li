/**
 * Created by mackcyl on 13-12-20.
 */
//处理在进行ajax请求时, 显示加载进度响应
$("document").ready(function(){
    var dialogFrag1 = $("body").data("#background");
    var dialogFrag2 = $("body").data("#progressBar");
    if(dialogFrag1) {
        if(!dialogFrag1.is(":hidden")) {
            dialogFrag1.hide();
        }
    }else{
        $dialogFrag1Html = "<div id='background' class='ui-widget-overlay ui-front'></div>";
        $("body").append($dialogFrag1Html);
    };
    if(dialogFrag2) {
        if(!dialogFrag2.is(":hidden")) {
            dialogFrag2.hide();
        }
    }else{
        $dialogFrag2Html = "<div id='progressBar' class='progressBar box' style='display:none;z-index:9999'><span class='progressBar_img'><img src='"+jsImagesDir+"/grogress_small.gif' alt='' /></span><span class='progressBar_word'>数据加载中，请稍等...</span></div>";
        $("body").append($dialogFrag2Html);
    };

    var ajaxbg = $("#background,#progressBar");
    $bg = $("#background");
    $pb = $("#progressBar");
    var $left = ($bg.width()-$pb.width())/2+"px";
    var $top = (($bg.height() - $pb.height())/2)+50+"px";
    ajaxbg.hide();
    $(document).ajaxStart(function(){
        $("#progressBar").css({ top: $top, left: $left});
        ajaxbg.show();
    }).ajaxStop(function(){
        ajaxbg.hide();
    });

});