/**
 * Created with JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-16
 * Time: 上午11:57
 * 颜色选择器构造
 */



(function($){
    /*
     * $.cPricker() helper .
     */

    $.extend(true,
        {
            cPricker : function(id ,type,defValue,themStr)
            {
                var configStr = 'default';

                var palettes = { };
                palettes.default = [
                    ["White", "Ivory", "Snow", "LightYellow", "MintCream", "Azure", "FloralWhite", "Honeydew", "GhostWhite", "Seashell", "Cornsilk", "AliceBlue", "LemonChiffon", "LightCyan"],
                    ["OldLace", "LightGoldenrodYellow", "LavenderBlush", "WhiteSmoke", "Beige", "Linen", "PapayaWhip", "BlanchedAlmond", "AntiqueWhite", "MistyRose", "Bisque", "Lavender", "Moccasin", "PaleGoldenrod"],
                    ["NavajoWhite", "Yellow", "PeachPuff", "Wheat", "Khaki", "Gainsboro", "PaleTurquoise", "Pink", "Aquamarine", "LightGray", "PowderBlue", "PaleGreen", "GreenYellow", "LightPink"],
                    ["LightBlue", "Gold", "Thistle", "LightGreen", "LightSteelBlue", "Silver", "LightSkyBlue", "BurlyWood", "SkyBlue", "Chartreuse", "Plum", "LawnGreen", "Tan", "LightSalmon"],
                    ["SandyBrown", "Cyan", "Aqua", "DarkKhaki", "Violet", "Turquoise", "Orange", "YellowGreen", "DarkSalmon", "MediumAquamarine", "DarkSeaGreen", "DarkGray", "MediumTurquoise", "Goldenrod"],
                    ["MediumSpringGreen", "SpringGreen", "Salmon", "LightCoral", "Coral", "DarkOrange", "HotPink", "RosyBrown", "Orchid", "Lime", "PaleVioletRed", "Peru", "DarkTurquoise", "CornflowerBlue"],
                    ["Tomato", "DeepSkyBlue", "LimeGreen", "CadetBlue", "MediumSeaGreen", "DarkGoldenrod", "MediumPurple", "LightSeaGreen", "LightSlateGray", "MediumOrchid", "Gray", "Chocolate", "IndianRed", "SlateGray"],
                    ["MediumSlateBlue", "DodgerBlue", "OliveDrab", "SteelBlue", "OrangeRed", "Olive", "SlateBlue", "RoyalBlue", "Magenta", "Fuchsia", "SeaGreen", "DimGray", "DeepPink", "Sienna"],
                    ["DarkOrchid", "DarkCyan", "ForestGreen", "DarkOliveGreen", "BlueViolet", "Teal", "MediumVioletRed", "Crimson", "SaddleBrown", "Brown", "FireBrick", "Red", "Green", "DarkSlateBlue"],
                    ["DarkSlateGray", "DarkViolet", "DarkGreen", "DarkMagenta", "Purple", "DarkRed", "Maroon", "Indigo", "MidnightBlue", "Blue", "MediumBlue", "DarkBlue", "Navy", "Black"]
                ];
                palettes.name = [
                    ["White", "Ivory", "Snow", "LightYellow", "MintCream", "Azure", "FloralWhite", "Honeydew", "GhostWhite", "Seashell", "Cornsilk", "AliceBlue", "LemonChiffon", "LightCyan"],
                    ["OldLace", "LightGoldenrodYellow", "LavenderBlush", "WhiteSmoke", "Beige", "Linen", "PapayaWhip", "BlanchedAlmond", "AntiqueWhite", "MistyRose", "Bisque", "Lavender", "Moccasin", "PaleGoldenrod"],
                    ["NavajoWhite", "Yellow", "PeachPuff", "Wheat", "Khaki", "Gainsboro", "PaleTurquoise", "Pink", "Aquamarine", "LightGray", "PowderBlue", "PaleGreen", "GreenYellow", "LightPink"],
                    ["LightBlue", "Gold", "Thistle", "LightGreen", "LightSteelBlue", "Silver", "LightSkyBlue", "BurlyWood", "SkyBlue", "Chartreuse", "Plum", "LawnGreen", "Tan", "LightSalmon"],
                    ["SandyBrown", "Cyan", "Aqua", "DarkKhaki", "Violet", "Turquoise", "Orange", "YellowGreen", "DarkSalmon", "MediumAquamarine", "DarkSeaGreen", "DarkGray", "MediumTurquoise", "Goldenrod"],
                    ["MediumSpringGreen", "SpringGreen", "Salmon", "LightCoral", "Coral", "DarkOrange", "HotPink", "RosyBrown", "Orchid", "Lime", "PaleVioletRed", "Peru", "DarkTurquoise", "CornflowerBlue"],
                    ["Tomato", "DeepSkyBlue", "LimeGreen", "CadetBlue", "MediumSeaGreen", "DarkGoldenrod", "MediumPurple", "LightSeaGreen", "LightSlateGray", "MediumOrchid", "Gray", "Chocolate", "IndianRed", "SlateGray"],
                    ["MediumSlateBlue", "DodgerBlue", "OliveDrab", "SteelBlue", "OrangeRed", "Olive", "SlateBlue", "RoyalBlue", "Magenta", "Fuchsia", "SeaGreen", "DimGray", "DeepPink", "Sienna"],
                    ["DarkOrchid", "DarkCyan", "ForestGreen", "DarkOliveGreen", "BlueViolet", "Teal", "MediumVioletRed", "Crimson", "SaddleBrown", "Brown", "FireBrick", "Red", "Green", "DarkSlateBlue"],
                    ["DarkSlateGray", "DarkViolet", "DarkGreen", "DarkMagenta", "Purple", "DarkRed", "Maroon", "Indigo", "MidnightBlue", "Blue", "MediumBlue", "DarkBlue", "Navy", "Black"]
                ];

                palettes.simple = [
                    ["White","Gold","Black","WhiteSmoke"],
                    ["Blue","Red","Green","Yellow"]
                ];

                if(themStr == "name" || themStr == "default"){
                    configStr = themStr;
                }




                var Jid ;
                if(type == undefined || type == ""){
                    Jid = '.'+id;
                }else if(type == 'id'){
                    Jid = '#'+id;
                }else{
                    Jid = '.'+id;
                }

                var defColor = $(Jid).val();
                if(defColor == undefined || defColor == ""){
                    if(defValue == undefined || defValue == ""){
                        defColor = 'blanchedalmond';
                    }else{
                        defColor = defValue;
                    }

                }


                $(Jid).spectrum({
                    showPaletteOnly: true,
                    showPalette:true,
                    preferredFormat: "name",
                    //preferredFormat: configStr,
                    className:'named',
                    color: defColor,
                    palette: palettes[configStr]
                });
                /*
                 flat: true,
                 showInput: true,
                 className: i,
                 preferredFormat: "rgb",
                 showPalette: true,
                 showPaletteOnly: true,
                 palette: palettes[i]
                 */
                /*
                $().ColorPicker({
                    onSubmit: function(hsb, hex, rgb, el) {
                        $(el).val(hex);
                        $(el).css("background-color","#"+hex);
                        $(el).ColorPickerHide();
                    },
                    onBeforeShow: function () {
                        $(this).ColorPickerSetColor(this.value);
                    }
                }).bind('keyup', function(){
                        $(this).ColorPickerSetColor(this.value);
                });
                */
            }
        });

})(jQuery);