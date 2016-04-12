/**
 * Created with JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-18
 * Time: 上午10:59
 * 会员管理js
 */


function User(){

}

User.prototype.getList = function(){
    var urlStr = window.SITE_URL + '/Admin/Order/ajaxGetUserList';
    var paraStr =  $("#user_search_form").serialize();
    $.ajax({
        type: "POST",
        url: urlStr,
        data: paraStr,
        dataType : "JSON",
        success: function(jsonResult){
            var userList = jsonResult.info.userList;
            var page = jsonResult.info.pageBody;

            var i;
            var tbodyHtml;
            for(i=0; i<userList.length; i++){
                var user = userList[i];

                tbodyHtml += "<tr>";
                tbodyHtml += "<td>" +
                    "<input type='radio' value='"+user.id+"' id='user_"+user.id+"' name='user'/>" +
                    "<input type='hidden' value='"+user.account+"("+user.truename+")"+"' id='"+user.id+"'>" +
                    "</td>";
                tbodyHtml += "<td><label for='user_"+user.id+"'> "+user.account+"("+user.truename+")"+"</label></td>";
                tbodyHtml += "</tr>";

            }

            $("#user_tbody").html(tbodyHtml);
            $("#user_page").html(page);
        }
    });
}

User.prototype.add = function(){

    if($("input[name='user']:radio:checked").length == 0){
        jAlert('请选择订单用户','提示消息');
        return false;
    }
    var user_id = $("input[name='user']:radio:checked").val();
    $("#user_id").val(user_id);
    $("#user_name").remove('disabled');
    $("#user_name").val($("#"+user_id).val());

    return true;

}