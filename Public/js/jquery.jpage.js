/**
 * Created by mackcyl on 14-5-23.
 */
// JavaScript Document
jQuery.extend({
        page:function (divId,pagesize,pageId){
            var div=divId;
            var x="#"+div+" table tr:has(td)"
            var y="#"+pageId;
            var table=$(x);

            var row=table.length;
            var pageSize=pagesize;
            var allPage=parseInt(""+((row+pageSize-1)/pageSize),10);
            var currentPageNum=1;
            $(table).hide();
            showRow("1");
            $(y).css("width",$("#page table").width()+"px");
            $(y).css("float","right");
            var pageStr = '';
            pageStr += "<span class='pageDocument' id='pre'>上一页</span>";
            for(var i=1;i<=allPage;i++){
                pageStr += "<span class='pageNum pageDocument' id='"+i+"'>"+i+"</span>"
            }
            pageStr += "<span class='pageDocument' id='next'>下一页</span>";
            $(y).html(pageStr);
            $(".pageNum").bind("click",{}, function (){ showRow($(this).attr("id"));});
            $("span").bind("mouseover",{}, function (){ $(this).css("cursor", "hand");$(this).addClass('pageHover')});
            $("span").bind("mouseout",{}, function (){$(this).removeClass('pageHover')});
            $("#pre").bind("click",{}, function (){ showPre();});
            $("#next").bind("click",{}, function (){ showNext();});


            function showRow(page){

                currentPageNum=parseInt(page);
                $(table).hide();
                var first=(currentPageNum-1)*pageSize;
                var last=pageSize*currentPageNum;
                if(last>row) last=row;
                for(var i=first;i<last;i++){
                    table.eq(i).show();
                }
                var id="#"+currentPageNum;

                var cd="span:not("+id+")";
                $(cd).css("color","#000000");
                $(id).css("color","red");
            }

            function showPre(){


                var p;
                if(currentPageNum-0==1){
                    p=1;
                }
                else {
                    p=currentPageNum-1;
                }
                showRow(p);

            }

            function showNext(){
                var p;
                if(currentPageNum==allPage){
                    p=currentPageNum;
                }
                else {
                    p=currentPageNum+1;
                }

                showRow(p);
            }
            $("#1").css("color","red");
        }
    }
);