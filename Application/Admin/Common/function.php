<?php
/**
 * Created by PhpStorm.
 * User: mackcyl
 * Date: 14/12/25
 * Time: 下午5:54
 */

function getEmptyTr($colspan = 1 ,$message='暂无数据'){
    $emptyHtml  = "<tr>";
    $emptyHtml .= '<td colspan="'.$colspan.'" data-angle="center">'.$message.'</td>';
    $emptyHtml .= "</tr>";
    return $emptyHtml;
}