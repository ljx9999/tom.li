<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ljx
 * 针对ajax翻页请求扩展类, 继承Page类
 */
// 导入分页类
namespace Common\Library\Tieson;

use  \Org\Util\Page;

class PageAjax extends Page {

	public $jSFunction;
	public $jSGoFunction;
	public $hrefGoFunction;


	protected $config = array(
		'header' => '条记录', 'prev' => '上一页', 'next' => '下一页', 'first' => '第一页', 'last' => '最后一页', 'go' => "GO",
		'theme'  => ' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %gopage% %end% '
	);


	public function __construct($totalRows, $listRows = '', $parameter = '', $url = '') {
		parent::__construct($totalRows, $listRows, $parameter, $url);
		$this->nowPage = !empty($_REQUEST[$this->varPage]) ? intval($_REQUEST[$this->varPage]) : 1;
		if ($this->nowPage < 1) {
			$this->nowPage = 1;
		} elseif (!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
			$this->nowPage = $this->totalPages;
		}
		$this->firstRow = $this->listRows * ($this->nowPage - 1);
	}

	public function getTotalPages() {
		return $this->totalPages;
	}

	public function getNowPage() {
		return $this->nowPage;
	}

	public function setJsFunction($jsFunction = "") {
		$this->jSFunction = (empty($jsFunction)) ? "ajaxPage" : $jsFunction;
	}

	public function getJsFunction() {
		if (empty($this->jSFunction)) {
			$this->jSFunction = "ajaxPage";
		}
		return $this->jSFunction;
	}

	public function getJsGoFunction() {
		if (empty($this->jSGoFunction)) {
			$this->jSGoFunction = "ajaxPageByGo";
		}
		return $this->jSGoFunction;
	}

	public function getHrefGoFunction() {
		if (empty($this->hrefGoFunction)) {
			$this->hrefGoFunction = "hrefPageByGo";
		}
		return $this->hrefGoFunction;
	}


	/**
	 * AJAX分页显示输出
	 *  public
	 */
	public function ajaxShow() {
		if (0 == $this->totalRows) return '';
		$p           = $this->varPage;
		$nowCoolPage = ceil($this->nowPage / $this->rollPage);

		// 分析分页参数
		if ($this->url) {
			$depr = C('URL_PATHINFO_DEPR');
			$url  = rtrim(U('/' . $this->url, '', false), $depr) . $depr . '__PAGE__';
		} else {
			if ($this->parameter && is_string($this->parameter)) {
				parse_str($this->parameter, $parameter);
			} elseif (is_array($this->parameter)) {
				$parameter = $this->parameter;
			} elseif (empty($this->parameter)) {
				unset($_GET[C('VAR_URL_PARAMS')]);
				$var = !empty($_POST) ? $_POST : $_GET;
				if (empty($var)) {
					$parameter = array();
				} else {
					$parameter = $var;
				}
			}
			$parameter[$p] = '__PAGE__';
			$url           = U('', $parameter);
		}
		//上下翻页字符串
		$upRow   = $this->nowPage - 1;
		$downRow = $this->nowPage + 1;
		if ($upRow > 0) {
			$href = $this->constructAjaxPageUrl($upRow, $url);
			//            $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
			$upPage = "<a href='" . $href . "'>" . $this->config['prev'] . "</a>";
		} else {
			$upPage = '';
		}

		if ($downRow <= $this->totalPages) {
			$href     = $this->constructAjaxPageUrl($downRow, $url);
			$downPage = "<a href='" . $href . "'>" . $this->config['next'] . "</a>";
			//            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
		} else {
			$downPage = '';
		}
		// << < > >>
		if ($nowCoolPage == 1) {
			$theFirst = '';
			$prePage  = '';
		} else {
			$preRow   = $this->nowPage - $this->rollPage;
			$prePage  = "<a href='" . $this->constructAjaxPageUrl($preRow, $url) . "' >上" . $this->rollPage . "页</a>";
			$theFirst = "<a href='" . $this->constructAjaxPageUrl(1, $url) . "' >" . $this->config['first'] . "</a>";
			//            $prePage    =   "<a href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a>";
			//            $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a>";
		}
		if ($nowCoolPage == $this->coolPages) {
			$nextPage = '';
			$theEnd   = '';
		} else {
			$nextRow   = $this->nowPage + $this->rollPage;
			$theEndRow = $this->totalPages;
			$nextPage  = "<a href='" . $this->constructAjaxPageUrl($nextRow, $url) . "' >下" . $this->rollPage . "页</a>";
			$theEnd    = "<a href='" . $this->constructAjaxPageUrl($theEndRow, $url) . "' >" . $this->config['last'] . "</a>";
			//            $nextPage   =   "<a href='".str_replace('__PAGE__',$nextRow,$url)."' >下".$this->rollPage."页</a>";
			//            $theEnd     =   "<a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a>";

		}
		// 1 2 3 4 5
		$linkPage = "";
		for ($i = 1; $i <= $this->rollPage; $i++) {
			$page = ($nowCoolPage - 1) * $this->rollPage + $i;
			if ($page != $this->nowPage) {
				if ($page <= $this->totalPages) {
					$linkPage .= "<a href='" . $this->constructAjaxPageUrl($page, $url) . "'>" . $page . "</a>";
					//                    $linkPage .= "<a href='".str_replace('__PAGE__',$page,$url)."'>".$page."</a>";
				} else {
					break;
				}
			} else {
				if ($this->totalPages != 1) {
					$linkPage .= "<span class='current'>" . $page . "</span>";
				}
			}
		}

		$goPage = "<input type='text' name='page' id='pageid' style='width:20px;'><a href='" . $this->constructAjaxPageUrlByGo("pageid", $url) . "' >Go</a>";

		$pageStr = str_replace(array(
			'%header%', '%nowPage%', '%totalRow%', '%totalPage%', '%upPage%', '%downPage%', '%first%', '%prePage%',
			'%linkPage%', '%nextPage%', '%gopage%', '%end%'
		), array(
			$this->config['header'], $this->nowPage, $this->totalRows, $this->totalPages, $upPage, $downPage, $theFirst,
			$prePage, $linkPage, $nextPage, $goPage, $theEnd
		), $this->config['theme']);
		return $pageStr;
	}


	/**
	 * 分页显示输出
	 * @access public
	 */
	public function show() {
		if (0 == $this->totalRows) return '';
		$p           = $this->varPage;
		$nowCoolPage = ceil($this->nowPage / $this->rollPage);

		// 分析分页参数
		if ($this->url) {
			$depr = C('URL_PATHINFO_DEPR');
			$url  = rtrim(U('/' . $this->url, '', false), $depr) . $depr . '__PAGE__';
		} else {
			if ($this->parameter && is_string($this->parameter)) {
				parse_str($this->parameter, $parameter);
			} elseif (is_array($this->parameter)) {
				$parameter = $this->parameter;
			} elseif (empty($this->parameter)) {
				unset($_GET[C('VAR_URL_PARAMS')]);
				$var = !empty($_POST) ? $_POST : $_GET;
				if (empty($var)) {
					$parameter = array();
				} else {
					$parameter = $var;
				}
			}
			$parameter[$p] = '__PAGE__';
			$url           = U('', $parameter);
		}
		//上下翻页字符串
		$upRow   = $this->nowPage - 1;
		$downRow = $this->nowPage + 1;
		if ($upRow > 0) {
			$upPage = "<a href='" . str_replace('__PAGE__', $upRow, $url) . "'>" . $this->config['prev'] . "</a>";
		} else {
			$upPage = '';
		}

		if ($downRow <= $this->totalPages) {
			$downPage = "<a href='" . str_replace('__PAGE__', $downRow, $url) . "'>" . $this->config['next'] . "</a>";
		} else {
			$downPage = '';
		}
		// << < > >>
		if ($nowCoolPage == 1) {
			$theFirst = '';
			$prePage  = '';
		} else {
			$preRow   = $this->nowPage - $this->rollPage;
			$prePage  = "<a href='" . str_replace('__PAGE__', $preRow, $url) . "' >上" . $this->rollPage . "页</a>";
			$theFirst = "<a href='" . str_replace('__PAGE__', 1, $url) . "' >" . $this->config['first'] . "</a>";
		}
		if ($nowCoolPage == $this->coolPages) {
			$nextPage = '';
			$theEnd   = '';
		} else {
			$nextRow   = $this->nowPage + $this->rollPage;
			$theEndRow = $this->totalPages;
			$nextPage  = "<a href='" . str_replace('__PAGE__', $nextRow, $url) . "' >下" . $this->rollPage . "页</a>";
			$theEnd    = "<a href='" . str_replace('__PAGE__', $theEndRow, $url) . "' >" . $this->config['last'] . "</a>";
		}
		// 1 2 3 4 5
		$linkPage = "";
		for ($i = 1; $i <= $this->rollPage; $i++) {
			$page = ($nowCoolPage - 1) * $this->rollPage + $i;
			if ($page != $this->nowPage) {
				if ($page <= $this->totalPages) {
					$linkPage .= "<a href='" . str_replace('__PAGE__', $page, $url) . "'>" . $page . "</a>";
				} else {
					break;
				}
			} else {
				if ($this->totalPages != 1) {
					$linkPage .= "<span class='current'>" . $page . "</span>";
				}
			}
		}


		$goPage = "<input type='text' name='page' id='pageid' style='width:20px;'><a href='" . $this->constructPageUrlByGo("pageid", $url) . "' >Go</a>";

		$pageStr = str_replace(array(
			'%header%', '%nowPage%', '%totalRow%', '%totalPage%', '%upPage%', '%downPage%', '%first%', '%prePage%',
			'%linkPage%', '%nextPage%', '%gopage%', '%end%'
		), array(
			$this->config['header'], $this->nowPage, $this->totalRows, $this->totalPages, $upPage, $downPage, $theFirst,
			$prePage, $linkPage, $nextPage, $goPage, $theEnd
		), $this->config['theme']);
		return $pageStr;
	}

	private function constructAjaxPageUrl($Row, $url) {
		$hrefStr = '';
		$hrefStr .= "javascript:" . $this->getJsFunction() . "(\"" . str_replace('__PAGE__', $Row, $url) . "\")";
		return $hrefStr;
	}

	private function constructAjaxPageUrlByGo($Row, $url) {
		$hrefStr = '';
		$hrefStr .= "javascript:" . $this->getJsGoFunction() . "(\"" . str_replace('__PAGE__', '%_p%', $url) . "\",\"" . $Row . "\")";
		return $hrefStr;
	}

	private function constructPageUrlByGo($Row, $url) {
		$hrefStr = '';
		$hrefStr .= "javascript:" . $this->getHrefGoFunction() . "(\"" . str_replace('__PAGE__', '%_p%', $url) . "\",\"" . $Row . "\")";
		return $hrefStr;
	}
}