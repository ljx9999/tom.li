jQuery.extend(jQuery.validator.messages, {
 required: '<span class="v_error" /> <p style="display:inline-block;position:relative;top:5px;color:red;font-size:18px;line-height:22px;">＊</p>',
 remote: "请批改该字段",
 email: '<span class="v_error" /> 请输入正确格局的电子邮件',
 url: '<span class="v_error" />  请输入正确的链接',
 date: '<span class="v_error" /> 请输入合法的日期',
 dateISO: "请输入合法的日期 (ISO).",
 number: '<span class="v_error" /> 请输入合法的数字',
 digits: "只能输入整数",
 creditcard: "请输入合法的信用卡号",
 equalTo: "请再次输入雷同的值",
 accept: "请输入拥有合法后缀名的字符串",
 maxlength: jQuery.validator.format("请输入一个 长度最多是 {0} 的字符串"),
 minlength: jQuery.validator.format("请输入一个 长度起码是 {0} 的字符串"),
 rangelength: jQuery.validator.format("请输入 一个长度介于 {0} 和 {1} 之间的字符串"),
 range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
 max: jQuery.validator.format("请输入一个最大为{0} 的值"),
 min: jQuery.validator.format("请输入一个最小为{0} 的值")
});