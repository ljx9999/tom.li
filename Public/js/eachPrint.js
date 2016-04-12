// 以下是
//var test = {
//    "express_id": "16",
//    "orders": {
//        "1": {
//            "sender_name": "寄件人姓名1",
//            "sender_company": "寄件人单位名称1",
//            "sender_address": "寄件人的详细地址1",
//            "addressee_name": "收件人姓名1",
//            "addressee_company": "收件人单位名称1",
//            "addressee_address": "收件人详细地址1",
//            "item_name": "内件品名1",
//            "item_count": "内件数量1",
//            "sender_phone": "寄件人电话1",
//            "addressee_phone": "收件人电话1"
//        },
//        "2": {
//            "sender_name": "寄件人姓名2",
//            "sender_company": "寄件人单位名称2",
//            "sender_address": "寄件人的详细地址2",
//            "addressee_name": "收件人姓名2",
//            "addressee_company": "收件人单位名称2",
//            "addressee_address": "收件人详细地址2",
//            "item_name": "内件品名2",
//            "item_count": "内件数量2",
//            "sender_phone": "寄件人电话2",
//            "addressee_phone": "收件人电话2"
//        },
//        "3": {
//            "sender_name": "寄件人姓名3",
//            "sender_company": "寄件人单位名称3",
//            "sender_address": "寄件人的详细地址3",
//            "addressee_name": "收件人姓名3",
//            "addressee_company": "收件人单位名称3",
//            "addressee_address": "收件人详细地址3",
//            "item_name": "内件品名3",
//            "item_count": "内件数量3",
//            "sender_phone": "寄件人电话3",
//            "addressee_phone": "收件人电话3"
//        }
//    }
//};





var LODOP;
function eachPrint(test, jsonResult) {


    LODOP = getLodop(document.getElementById('LODOP_OB'), document.getElementById('LODOP_EM'));
    LODOP.PRINT_INITA(4, 10, 1500, 1500, "快递");


    var sender_name = "寄件人姓名";
    var sender_company = "寄件人单位名称";
    var sender_address = "寄件人的详细地址";
    var addressee_name = "收件人姓名";
    var addressee_company = "收件人单位名称";
    var addressee_address = "收件人详细地址";
    var item_name = "内件品名";
    var item_count = "内件数量";
    var sender_phone = "寄件人电话";
    var addressee_phone = "收件人电话";

    for (x in test.orders) {

        LODOP.NewPage();
        sender_name = test.orders[x]['sender_name'];
        sender_company = test.orders[x]['sender_company'];
        sender_address = test.orders[x]['sender_address'];
        addressee_name = test.orders[x]['addressee_name'];
        addressee_company = test.orders[x]['addressee_company'];
        addressee_address = test.orders[x]['addressee_address'];
        item_name = test.orders[x]['item_name'];
        item_count = test.orders[x]['item_count'];
        sender_phone = test.orders[x]['sender_phone'];
        addressee_phone = test.orders[x]['addressee_phone'];

        var x = decode64(jsonResult);
        var index = x.indexOf("LODOP", 6);
        x = x.substring(index);
        //console.log(x);
        eval(x);


    }

    LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW", 1);// 打印预览时是否包含背景图。
    LODOP.SET_PREVIEW_WINDOW(0, 0, 0, 0, 0, "");
    LODOP.PREVIEW();
}
