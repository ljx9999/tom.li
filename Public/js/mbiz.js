/**
 * Created with JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-3
 * Time: 下午2:50
 * MBIZ项目共用js文件
 */
//选择类型控件
//分类
var category = {
    action:"Category",
    status: false,
    init_data: {},
    dialog_html: {},
    menu_index: 0,
    category_name: '',
    category_id: 0,
    module_id: 0,
    tempText: null,
    init: function (parentId) {
        parentId = parentId ? parentId : 0;

        var dialogCategory = $("body").data("#dialog_category");
        if(dialogCategory) {
        }else{
            dialogCategory = "<div id='dialog_category' title='选择分类'>" +
                             "</div>";
            $("body").append(dialogCategory);
        };

        $( "#dialog_category" ).dialog({
            autoOpen: false,
//            height: 600,
            width: 600,
            modal: true,
            position: {
                my: "center",
                at: "center top",
                of:  $("body")
            },
            buttons: {
                "确定": function() {
                    category.fill();
//                    $("#category_name").val(category.category_name);
//                    $("#category_id").val(category.category_id);
                    $( this ).dialog( "close" );
                },
                "取消": function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
            }
        });

        //Ajax 加载Base 数据
        //初始化列表数据
        category.getCategory(parentId);
        $('#category_name').click(function () {
            $('#dialog_category').html("");
            category.root(category.init_data);
            $( "#dialog_category" ).dialog("open");
            var myDialogX = ($("body").width() - $('#dialog_category').outerWidth())/2;
            var myDialogY = (($("body").height() - $('#dialog_category').outerHeight())/2) - $(window).scrollTop();
            $('#dialog_category').dialog( 'option', 'position', [myDialogX, myDialogY/2] );

        })
    },

    root: function (categoryList) {
        if(categoryList =='' || categoryList == undefined || categoryList ==null){
            return false;
        }else{
        var selectHtml = "<ul>"
        var optionHtml = "";
        for (var i=0;i<categoryList.length;i++){
            optionHtml += "<li id='"+categoryList[i]['id']+"' class='select_li'>"+categoryList[i]['cat_name']+"</option>";
        }
        selectHtml += optionHtml += "</ul>";

        $('#dialog_category').append(selectHtml);

        $('.select_li').unbind("click");

        $('.select_li').on('click', function () {

            parnetObj = $(this).parent('ul:first');


            $(parnetObj).children('.select_li').removeClass("active");
            $(this).addClass("active");
            category.category_id = $(this).attr('id');
            category.category_name =  $(this).html();

            category.load(this);
        });
        }
    },
    load: function (node) {

        parnetObjt = $(node).parent('ul:first');
        $(parnetObjt).nextAll('ul').each(function(){
            $(this).remove();
        });

        var parentId = $(node).attr('id');
        category.category_id = parentId;
        category.category_name =  $(node).html();

        $.post(window.SITE_URL + "/Admin/"+category.action+"/ajaxGetCategory", {'parentId': parentId}, function (data) {
            
            respCategoryList = data.info;
            if(respCategoryList == null){
                return;
            }else{
                category.root(respCategoryList);
            }
        });
    },

    getCategory:function(parentId){
        $.post(window.SITE_URL + "/Admin/"+category.action+"/ajaxGetCategory", {'parentId': parentId}, function (data) {
            category.init_data = data.info;
        });
    },
    fill: function () {
        $('#product_cats').val(category.category_id);
        $('#category_name').val(category.category_name);

        //loadCatAttribute();

    }
}


var beditor = {
    new: function (id, type) {
        var editor = new UE.ui.Editor();
        type = type ? type : 'user';

        switch (type) {
            case 'user' :
                return UE.getEditor(id, {
                    toolbars: [
                        ['fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                            'directionalityltr', 'directionalityrtl', 'indent', '|',
                            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                            'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'highlightcode', 'pagebreak', 'template', 'background', '|',
                            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|',
                            'print', 'preview', 'searchreplace']
                    ],
                    wordCount: false,
                    elementPathEnabled: false
                })
                break;
            case 'admin':
                return UE.getEditor(id);
                break;
        }

    }
}


/**
 * 初始化颜色选择js扩展
 * @constructor
 */
function CoolorPrickerConstruct(){

    var palettes = { };
    palettes.default = [
        ["#000000", "#434343", "#666666", "#999999", "#b7b7b7", "#cccccc", "#d9d9d9", "#efefef", "#f3f3f3", "#ffffff"],
        ["#980000", "#ff0000", "#ff9900", "#ffff00", "#00ff00", "#00ffff", "#4a86e8", "#0000ff", "#9900ff", "#ff00ff"],
        ["#e6b8af", "#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d9ead3", "#c9daf8", "#cfe2f3", "#d9d2e9", "#ead1dc"],
        ["#dd7e6b", "#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#a4c2f4", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
        ["#cc4125", "#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6d9eeb", "#6fa8dc", "#8e7cc3", "#c27ba0"],
        ["#a61c00", "#cc0000", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3c78d8", "#3d85c6", "#674ea7", "#a64d79"],
        ["#85200c", "#990000", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#1155cc", "#0b5394", "#351c75", "#741b47"],
        ["#5b0f00", "#660000", "#783f04", "#7f6000", "#274e13", "#0c343d", "#1c4587", "#073763", "#20124d", "#4c1130"]
    ];

    $('.goods_lise_color').spectrum({
        showPaletteOnly: true,
        showPalette:true,
        color: 'blanchedalmond',
        palette: palettes.default
    });

    /*$('.goods_lise_color').ColorPicker({

        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).css("background-color","#"+hex);
            $(el).ColorPickerHide();

        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    })
        .bind('keyup', function(){
            $(this).ColorPickerSetColor(this.value);
        });*/
}