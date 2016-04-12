/*
 * Ajax 三级省市联动
 * User: mackcyl
 * Date: 13-9-9
 * Time: 下午2:12
 *
settings 参数说明
-----
url:省市数据josn文件路径
prov:默认省份
city:默认城市
dist:默认地区（县）
nodata:无数据状态
required:必选项
------------------------------ */

(function($){
	$.fn.citySelect=function(settings){
		if(this.length<1){return;};

		// 默认值
		settings=$.extend({
			url:jsDir+"/city.min.js",
			prov:null,
			city:null,
			dist:null,
			nodata:null,
			required:true
		},settings);

		var box_obj=this;
		var prov_obj=box_obj.find(".prov");
		var city_obj=box_obj.find(".city");
		var dist_obj=box_obj.find(".dist");
		var prov_val=settings.prov;
		var city_val=settings.city;
		var dist_val=settings.dist;
		var select_prehtml=(settings.required) ? "" : "<option value=''>请选择</option>";
		var span_prehtml = (settings.required) ? "" : "请选择";
        var city_json;

		// 赋值市级函数
		var cityStart=function(){

            console.log(prov_obj);

            var prov_id = 0;
            if(prov_obj.length > 1){
                prov_id=prov_obj.get(1).selectedIndex;
            }else{
                prov_id=prov_obj.get(0).selectedIndex;
            }
            console.log(prov_id);
			if(!settings.required){
				prov_id--;
			};
			city_obj.empty().attr("disabled",true);
			dist_obj.empty().attr("disabled",true);

            if(city_obj.length > 1){
                $(city_obj.get(0)).empty();
                $(city_obj.get(1) ).selectmenu( "refresh", true );
            }
            if(dist_obj.length > 1){
                $(dist_obj.get(0)).empty();
                $(dist_obj.get(1) ).selectmenu( "refresh", true );
            }
			if(prov_id<0||typeof(city_json.citylist[prov_id].c)=="undefined"){
				if(settings.nodata=="none"){
					city_obj.css("display","none");
					dist_obj.css("display","none");
				}else if(settings.nodata=="hidden"){
					city_obj.css("visibility","hidden");
					dist_obj.css("visibility","hidden");
				};
				return;
			};
			
			// 遍历赋值市级下拉列表
			temp_html=select_prehtml;
            var spanTest = "";
			$.each(city_json.citylist[prov_id].c,function(i,city){
                if(spanTest == ""){
                    spanTest = city.n;
                }
				temp_html+="<option value='"+city.n+"'>"+city.n+"</option>";
			});


			city_obj.html(temp_html).attr("disabled",false).css({"display":"","visibility":""});

            if(city_obj.length > 1){
                $(city_obj.get(0)).html('');
                $(city_obj.get(0)).html(spanTest);
                $(city_obj.get(1)).selectmenu("refresh");
            }

            distStart();
		};

		// 赋值地区（县）函数
		var distStart=function(){
//			var prov_id=prov_obj.get(0).selectedIndex;
//			var city_id=city_obj.get(0).selectedIndex;

            var prov_id = 0;
            if(prov_obj.length > 1){
                prov_id=prov_obj.get(1).selectedIndex;
            }else{
                prov_id=prov_obj.get(0).selectedIndex;
            }
            var city_id = 0;
            if(city_obj.length > 1){
                city_id=city_obj.get(1).selectedIndex;
            }else{
                city_id=city_obj.get(0).selectedIndex;
            }
			if(!settings.required){
				prov_id--;
				city_id--;
			};
			dist_obj.empty().attr("disabled",true);

            if(dist_obj.length > 1){
                $(dist_obj.get(0)).empty();
                $(dist_obj.get(1) ).selectmenu( "refresh", true );
            }

			if(prov_id<0||city_id<0||typeof(city_json.citylist[prov_id].c[city_id].a)=="undefined"){
				if(settings.nodata=="none"){
					dist_obj.css("display","none");
				}else if(settings.nodata=="hidden"){
					dist_obj.css("visibility","hidden");
				};
				return;
			};
			
			// 遍历赋值市级下拉列表
			temp_html=select_prehtml;
            var spanTest = "";
			$.each(city_json.citylist[prov_id].c[city_id].a,function(i,dist){

                if(spanTest == ""){
                    spanTest = dist.s;
                }
				temp_html+="<option value='"+dist.s+"'>"+dist.s+"</option>";
			});
			dist_obj.html(temp_html).attr("disabled",false).css({"display":"","visibility":""});

            if(dist_obj.length > 1){
                $(dist_obj.get(0)).empty();
                $(dist_obj.get(0)).html(spanTest);
                $(dist_obj.get(1) ).selectmenu( "refresh", true );
            }
        };

		var init=function(){
			// 遍历赋值省份下拉列表
			temp_html=select_prehtml;

            var spanTest = "";
			$.each(city_json.citylist,function(i,prov){
                if(spanTest == ""){
                    spanTest = prov.p;
                }
				temp_html+="<option value='"+prov.p+"'>"+prov.p+"</option>";
			});
			prov_obj.html(temp_html);
            if(prov_obj.length > 1){
                $(prov_obj.get(0)).text('');
                $(prov_obj.get(0)).html(spanTest);
            }

			// 若有传入省份与市级的值，则选中。（setTimeout为兼容IE6而设置）
			setTimeout(function(){
				if(settings.prov!=null){
					prov_obj.val(settings.prov);
                    if(prov_obj.length > 1){
                        $(prov_obj.get(0)).empty();
                        $(prov_obj.get(0)).html(settings.prov);
                    }
					cityStart();
					setTimeout(function(){
						if(settings.city!=null){
							city_obj.val(settings.city);
                            if(city_obj.length > 1){
                                $(city_obj.get(0)).text('');
                                $(city_obj.get(0)).html(settings.city);
                                $(city_obj.get(1)).selectmenu( "refresh" );
                            }
							distStart();
							setTimeout(function(){
								if(settings.dist!=null){

									dist_obj.val(settings.dist);
                                    if(dist_obj.length > 1){
                                        $(dist_obj.get(0)).empty();
                                        $(dist_obj.get(0)).html(settings.dist);
                                        $(dist_obj.get(1)).selectmenu( "refresh" );
                                    }
								};
							},1);
						};
					},1);
				};
			},1);

			// 选择省份时发生事件
			prov_obj.bind("change",function(){
				cityStart();
			});

			// 选择市级时发生事件
			city_obj.bind("change",function(){
				distStart();
			});
		};

		// 设置省市json数据
		if(typeof(settings.url)=="string"){
			$.getJSON(settings.url,function(json){
				city_json=json;
				init();
			});
		}else{
			city_json=settings.url;
			init();
		};
	};
})(jQuery);