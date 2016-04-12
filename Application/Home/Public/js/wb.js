function Pager(a) {
	this.$monitor = a.monitor, this.nextUrl = a.nextUrl || "", this.$container = a.container, this.triggerType = "click" == a.triggerType ? "click" : "lazyLoad", this.pauseInterval = parseInt(a.pauseInterval || 0, 10), this.loadCallback = a.onLoad || function() {}, this.errorCallback = a.onError || function() {}, this._stop = !1, this._pause = !1, this._lazyCount = 0, this.init();
	var b = this.triggerType,
		c = "init" + b.substring(0, 1).toUpperCase() + b.substring(1);
	$.isFunction(this[c]) && this[c]()
}

function Comment(a) {
	var b = window.$CONFIG || {},
		c = {
			container: null,
			code: null
		};
	this.page = 1, this.opts = $.extend(!0, c, a), this.$container = $(this.opts.container), b.openid && (this.user = {
		openid: b.openid,
		nickname: b.nickname,
		avatar: b.avatar
	}), this.createDOM(), this.bindEvent(), this.initPager()
}

function checkUpload() {
	var a = navigator.userAgent;
	if (-1 == a.indexOf("MicroMessenger")) return !0;
	var b = a.match(/MicroMessenger\/([\d\.]+)/);
	return b = parseFloat(b[1]), !isNaN(b) && b >= 5.2
}

function appAssistSelect() {
	window.onapp && $('[data-action="phonebookAssistSelect"]').trigger("click")
}

function appShare() {
	return window.onapp ? (callApp("share", JSON.stringify(Weixin.shareConfig)), !1) : void 0
}

function callApp(fun, param) {
	return window.onapp ? (window.callAppTimeout || (window.callAppTimeout = 0), setTimeout(function() {
		try {
			param || (param = "1");
			var browserName = navigator.userAgent.toLowerCase();
			if (-1 != browserName.indexOf("android")) eval("window.pbwc." + fun + "('" + param + "');");
			else if (-1 != browserName.indexOf("iphone") || -1 != browserName.indexOf("ipad")) {
				var url = "pbwc:app*" + fun + "*" + param;
				document.location = url
			}
		} catch (e) {}
	}, window.callAppTimeout), window.callAppTimeout += 50, !1) : void 0
}

function getAppParam(a) {
	var b = window.APP_PARAMS || {};
	return null == b[a] && (b[a] = ""), b[a] instanceof Object ? JSON.stringify(b[a]) : b[a]
}

function weixinReady(a) {
	Weixin.ready(a)
}

function imageReady(a) {
	var b = [];
	return a.each(function() {
		var a = $.Deferred();
		b.push(a), $(this).on("load error", function() {
			a.resolve()
		}), $(this).attr("src", $(this).data("src"))
	}), $.when.apply($, b)
}

function phonebookShare(a, b) {
	$.get("/index/addshare?code=" + b)
}

function activityShare(a, b) {
		$.get("/activity/addshare?code=" + b)
	}! function(a) {
		function b(a) {
			return Object.prototype.toString.call(a)
		}

		function c(a) {
			return "[object Object]" === b(a)
		}

		function d(a) {
			return "[object Function]" === b(a)
		}

		function e(a, b) {
			for (var c = 0, d = a.length; d > c && b.call(a, a[c], c) !== !1; c++);
		}

		function f(a) {
			if (!p.test(a)) return null;
			var b, c, d, e, f;
			if (-1 !== a.indexOf("trident/") && (b = /\btrident\/([0-9.]+)/.exec(a), b && b.length >= 2)) {
				d = b[1];
				var g = b[1].split(".");
				g[0] = parseInt(g[0], 10) + 4, f = g.join(".")
			}
			b = p.exec(a), e = b[1];
			var h = b[1].split(".");
			return "undefined" == typeof f && (f = e), h[0] = parseInt(h[0], 10) - 4, c = h.join("."), "undefined" == typeof d && (d = c), {
				browserVersion: f,
				browserMode: e,
				engineVersion: d,
				engineMode: c,
				compatible: d !== c
			}
		}

		function g(a) {
			if (o) try {
				var b = o.twGetRunPath.toLowerCase(),
					c = o.twGetSecurityID(window),
					d = o.twGetVersion(c);
				if (b && -1 === b.indexOf(a)) return !1;
				if (d) return {
					version: d
				}
			} catch (e) {}
		}

		function h(a, e, f) {
			var g = d(e) ? e.call(null, f) : e;
			if (!g) return null;
			var h = {
					name: a,
					version: k,
					codename: ""
				},
				i = b(g);
			if (g === !0) return h;
			if ("[object String]" === i) {
				if (-1 !== f.indexOf(g)) return h
			} else {
				if (c(g)) return g.hasOwnProperty("version") && (h.version = g.version), h;
				if (g.exec) {
					var j = g.exec(f);
					if (j) return h.version = j.length >= 2 && j[1] ? j[1].replace(/_/g, ".") : k, h
				}
			}
		}

		function i(a, b, c, d) {
			var f = u;
			e(b, function(b) {
				var c = h(b[0], b[1], a);
				return c ? (f = c, !1) : void 0
			}), c.call(d, f.name, f.version)
		}
		var j = {},
			k = "-1",
			l = navigator.userAgent || "",
			m = navigator.appVersion || "",
			n = navigator.vendor || "",
			o = window.external,
			p = /\b(?:msie |ie |trident\/[0-9].*rv[ :])([0-9.]+)/,
			q = [
				["nokia",
					function(a) {
						return -1 !== a.indexOf("nokia ") ? /\bnokia ([0-9]+)?/ : -1 !== a.indexOf("noain") ? /\bnoain ([a-z0-9]+)/ : /\bnokia([a-z0-9]+)?/
					}
				],
				["samsung",
					function(a) {
						return -1 !== a.indexOf("samsung") ? /\bsamsung(?:\-gt)?[ \-]([a-z0-9\-]+)/ : /\b(?:gt|sch)[ \-]([a-z0-9\-]+)/
					}
				],
				["wp",
					function(a) {
						return -1 !== a.indexOf("windows phone ") || -1 !== a.indexOf("xblwp") || -1 !== a.indexOf("zunewp") || -1 !== a.indexOf("windows ce")
					}
				],
				["pc", "windows"],
				["ipad", "ipad"],
				["ipod", "ipod"],
				["iphone", /\biphone\b|\biph(\d)/],
				["mac", "macintosh"],
				["mi", /\bmi[ \-]?([a-z0-9 ]+(?= build))/],
				["aliyun", /\baliyunos\b(?:[\-](\d+))?/],
				["meizu", /\b(?:meizu\/|m)([0-9]+)\b/],
				["nexus", /\bnexus ([0-9s.]+)/],
				["huawei",
					function(a) {
						return -1 !== a.indexOf("huawei-huawei") ? /\bhuawei\-huawei\-([a-z0-9\-]+)/ : /\bhuawei[ _\-]?([a-z0-9]+)/
					}
				],
				["lenovo",
					function(a) {
						return -1 !== a.indexOf("lenovo-lenovo") ? /\blenovo\-lenovo[ \-]([a-z0-9]+)/ : /\blenovo[ \-]?([a-z0-9]+)/
					}
				],
				["zte",
					function(a) {
						return /\bzte\-[tu]/.test(a) ? /\bzte-[tu][ _\-]?([a-su-z0-9\+]+)/ : /\bzte[ _\-]?([a-su-z0-9\+]+)/
					}
				],
				["vivo", /\bvivo ([a-z0-9]+)/],
				["htc",
					function(a) {
						return /\bhtc[a-z0-9 _\-]+(?= build\b)/.test(a) ? /\bhtc[ _\-]?([a-z0-9 ]+(?= build))/ : /\bhtc[ _\-]?([a-z0-9 ]+)/
					}
				],
				["oppo", /\boppo[_]([a-z0-9]+)/],
				["konka", /\bkonka[_\-]([a-z0-9]+)/],
				["sonyericsson", /\bmt([a-z0-9]+)/],
				["coolpad", /\bcoolpad[_ ]?([a-z0-9]+)/],
				["lg", /\blg[\-]([a-z0-9]+)/],
				["android", "android"],
				["blackberry", "blackberry"]
			],
			r = [
				["wp",
					function(a) {
						return -1 !== a.indexOf("windows phone ") ? /\bwindows phone (?:os )?([0-9.]+)/ : -1 !== a.indexOf("xblwp") ? /\bxblwp([0-9.]+)/ : -1 !== a.indexOf("zunewp") ? /\bzunewp([0-9.]+)/ : "windows phone"
					}
				],
				["windows", /\bwindows nt ([0-9.]+)/],
				["macosx", /\bmac os x ([0-9._]+)/],
				["ios",
					function(a) {
						return /\bcpu(?: iphone)? os /.test(a) ? /\bcpu(?: iphone)? os ([0-9._]+)/ : -1 !== a.indexOf("iph os ") ? /\biph os ([0-9_]+)/ : /\bios\b/
					}
				],
				["yunos", /\baliyunos ([0-9.]+)/],
				["android", /\bandroid[\/\- ]?([0-9.x]+)?/],
				["chromeos", /\bcros i686 ([0-9.]+)/],
				["linux", "linux"],
				["windowsce", /\bwindows ce(?: ([0-9.]+))?/],
				["symbian", /\bsymbian(?:os)?\/([0-9.]+)/],
				["meego", /\bmeego\b/],
				["blackberry", "blackberry"]
			],
			s = [
				["trident", p],
				["webkit", /\bapplewebkit[\/]?([0-9.+]+)/],
				["gecko", /\bgecko\/(\d+)/],
				["presto", /\bpresto\/([0-9.]+)/],
				["androidwebkit", /\bandroidwebkit\/([0-9.]+)/],
				["coolpadwebkit", /\bcoolpadwebkit\/([0-9.]+)/]
			],
			t = [
				["sg", / se ([0-9.x]+)/],
				["tw",
					function() {
						var a = g("theworld");
						return "undefined" != typeof a ? a : "theworld"
					}
				],
				["360",
					function(a) {
						var b = g("360se");
						return "undefined" != typeof b ? b : -1 !== a.indexOf("360 aphone browser") ? /\b360 aphone browser \(([^\)]+)\)/ : /\b360(?:se|ee|chrome|browser)\b/
					}
				],
				["mx",
					function() {
						try {
							if (o && (o.mxVersion || o.max_version)) return {
								version: o.mxVersion || o.max_version
							}
						} catch (a) {}
						return /\bmaxthon(?:[ \/]([0-9.]+))?/
					}
				],
				["qq", /\bm?qqbrowser\/([0-9.]+)/],
				["green", "greenbrowser"],
				["tt", /\btencenttraveler ([0-9.]+)/],
				["lb",
					function(a) {
						if (-1 === a.indexOf("lbbrowser")) return !1;
						var b;
						try {
							o && o.LiebaoGetVersion && (b = o.LiebaoGetVersion())
						} catch (c) {}
						return {
							version: b || k
						}
					}
				],
				["tao", /\btaobrowser\/([0-9.]+)/],
				["fs", /\bcoolnovo\/([0-9.]+)/],
				["sy", "saayaa"],
				["baidu", /\bbidubrowser[ \/]([0-9.x]+)/],
				["ie", p],
				["mi", /\bmiuibrowser\/([0-9.]+)/],
				["opera",
					function(a) {
						var b = /\bopera.+version\/([0-9.ab]+)/,
							c = /\bopr\/([0-9.]+)/;
						return b.test(a) ? b : c
					}
				],
				["chrome", / (?:chrome|crios|crmo)\/([0-9.]+)/],
				["uc",
					function(a) {
						return a.indexOf("ucbrowser/") >= 0 ? /\bucbrowser\/([0-9.]+)/ : /\buc\/[0-9]/.test(a) ? /\buc\/([0-9.]+)/ : a.indexOf("ucweb") >= 0 ? /\bucweb[\/]?([0-9.]+)?/ : /\b(?:ucbrowser|uc)\b/
					}
				],
				["android",
					function(a) {
						return -1 !== a.indexOf("android") ? /\bversion\/([0-9.]+(?: beta)?)/ : void 0
					}
				],
				["safari", /\bversion\/([0-9.]+(?: beta)?)(?: mobile(?:\/[a-z0-9]+)?)? safari\//],
				["webview", /\bcpu(?: iphone)? os (?:[0-9._]+).+\bapplewebkit\b/],
				["firefox", /\bfirefox\/([0-9.ab]+)/],
				["nokia", /\bnokiabrowser\/([0-9.]+)/]
			],
			u = {
				name: "na",
				version: k
			},
			v = function(a) {
				a = (a || "").toLowerCase();
				var b = {};
				i(a, q, function(a, c) {
					var d = parseFloat(c);
					b.device = {
						name: a,
						version: d,
						fullVersion: c
					}, b.device[a] = d
				}, b), i(a, r, function(a, c) {
					var d = parseFloat(c);
					b.os = {
						name: a,
						version: d,
						fullVersion: c
					}, b.os[a] = d
				}, b);
				var c = f(a);
				return i(a, s, function(a, d) {
					var e = d;
					c && (d = c.engineVersion || c.engineMode, e = c.engineMode);
					var f = parseFloat(d);
					b.engine = {
						name: a,
						version: f,
						fullVersion: d,
						mode: parseFloat(e),
						fullMode: e,
						compatible: c ? c.compatible : !1
					}, b.engine[a] = f
				}, b), i(a, t, function(a, d) {
					var e = d;
					c && ("ie" === a && (d = c.browserVersion), e = c.browserMode);
					var f = parseFloat(d);
					b.browser = {
						name: a,
						version: f,
						fullVersion: d,
						mode: parseFloat(e),
						fullMode: e,
						compatible: c ? c.compatible : !1
					}, b.browser[a] = f
				}, b), b
			};
		j = v(l + " " + m + " " + n), j.parse = v, a.detector = j
	}(window),
	function(a) {
		function b(a, b, d, e, f) {
			return !f && arguments.callee.caller && (f = c(arguments.callee.caller)), {
				msg: a || "",
				file: b || "",
				line: d || 0,
				num: e || "",
				stack: f || "",
				detector: {
					browser: detector.browser,
					device: detector.device,
					engine: detector.engine,
					os: detector.os
				}
			}
		}

		function c(a) {
			for (var b = []; a.arguments && a.arguments.callee && a.arguments.callee.caller && (a = a.arguments.callee.caller, b.push("at " + d(a)), a.caller !== a););
			return b.join("\n")
		}

		function d(a) {
			var b = String(a).match(/^function\b[^\)]+\)/);
			return b ? b[0] : ""
		}

		function e(a) {
			return a instanceof Error || "[object Error]" != Object.prototype.toString.call(a)
		}

		function f() {
			var a = +new Date;
			return a.toString().substring(0, 10)
		}
		var g = a.monitor = {},
			h = [],
			i = [];
		a.onerror = function() {
			var c = arguments,
				d = f();
			c = b.apply(null, c), c.date = d, c.url = location.href, c.top = $(a).scrollTop(), i.push(c), h.forEach(function(a) {
				a.call(null, c)
			})
		}, g.error = function(c) {
			if (e(c)) {
				var d = c.stack || c.stacktrace,
					g = b(c.message || c.description, c.fileName, c.lineNumber || c.line, c.number, d);
				g.date = f(), g.url = location.href, g.top = $(a).scrollTop(), i.push(g)
			}
		}, g.addListener = function(a) {
			h.push(a)
		}, g.report = function(a, b) {
			100 >= b && (b = 100), setInterval(function() {
				if (i.length > 0) {
					try {
						a(i)
					} catch (b) {}
					i = []
				}
			}, b)
		}, g.report(function(a) {
			-1 == a[0].msg.search(/WeixinJSBridge/) && $.post("/api/feedback/send", {
				message: JSON.stringify(err)
			})
		}, 1e3)
	}(window), Pager.prototype = {
		init: function() {
			this.$monitor.on("click", function(a) {
				a.preventDefault()
			});
			var a = this.$monitor.data("status");
			("" == a || void 0 == a) && this.status("wait")
		},
		initClick: function() {
			var a = this;
			this.$monitor.on("click", function() {
				a.allowAccess() && (a.nextRequest(), a._pause = !1, a._lazyCount = 0)
			})
		},
		initLazyLoad: function() {
			this.initClick();
			var a = this;
			this.$monitor.lazy({
				type: "url",
				res: function() {
					return {
						url: a.nextUrl,
						dataType: "json"
					}
				},
				beforeLoad: function() {
					a._lazyCount++, a.status("loading")
				},
				onLoad: function(b) {
					a.requestAfter(b), a._stop && a.$monitor.lazy("destroy"), a.pauseInterval > 0 && a._lazyCount >= a.pauseInterval && (a._pause = !0)
				},
				onError: function() {
					a.status("error"), a._pause = !0, a.errorCallback.apply(a, arguments)
				},
				condition: function() {
					return !a._pause && a.allowAccess()
				}
			})
		},
		requestAfter: function(a) {
			a && void 0 != a.html ? (this.$container.append($.trim(a.html)), a.next ? (this.nextUrl = a.next, this.status("wait")) : this.end(), this.imgLazyLoad(), this.loadCallback.apply(this, arguments)) : (this.status("error"), this.errorCallback.apply(this, arguments))
		},
		nextRequest: function() {
			this.status("loading");
			var a = this,
				b = $.getJSON(this.nextUrl);
			b.done($.proxy(this.requestAfter, this)), b.fail(function() {
				a.status("error"), a.errorCallback.apply(a, arguments)
			})
		},
		imgLazyLoad: function() {
			this.$container.find("img.lazy").removeClass("lazy").lazy()
		},
		end: function() {
			this._stop = !0, this.status("disabled")
		},
		allowAccess: function() {
			var a = this.$monitor.data("status");
			return !this._stop && "wait" == a || "error" == a
		},
		status: function(a) {
			this.$monitor.data("status", a).attr("data-status", a), "disabled" == a && (a = "over"), this.$monitor.removeClass("wait error loading over").addClass(a)
		}
	}, Pager.prototype.constructor = Pager, Comment.TPL = {
		REPLYBOX: '<div data-modal="alert" class="modal" >\n	<div class="modal-container">\n		<form method="post">\n			<div class="form-group">\n				<textarea name="content" required id="content" rows="3" class="form-control" placeholder="璇勮鍐呭"></textarea>\n				<div class="help-block empty">璇峰～鍐欏洖澶嶅唴瀹�</div>\n			</div>\n			<div class="form-group">\n				<button type="submit" class="btn btn-block">鎻愪氦</button>\n			</div>\n		</form>\n		<div class="actions">\n			<a href="#" class="btn btn-block btn-default" data-dismiss="modal">鍏抽棴绐楀彛</a>\n		</div>\n	</div>\n</div>',
		CONTAINER: '<div class="wrapper"><a class="btn btn-block mainReply" href="#">鐐瑰嚮鍙戣〃璇勮</a></div>\n<div class="list-comment"></div>\n<a data-status="wait" class="more listview-tips-more2">\n	<span class="tips-wait"><b class="icon icon-arrows-down"></b>鐐瑰嚮鏄剧ず鏇村</span>\n	<span class="tips-loading">鍔犺浇涓�</span>\n	<span class="tips-error">缃戠粶绻佸繖锛岃鐐瑰嚮閲嶆柊鍔犺浇</span>\n</a>'
	}, Comment.prototype = {
		pushComment: function(a, b) {
			var c = $.Deferred(),
				d = $.post("/comment/add", {
					code: this.opts.code,
					parent_id: a,
					content: b
				}, null, "json");
			return d.done(function(a) {
				a.status ? c.resolve(a.info) : c.reject({
					error: a.info
				})
			}), d.fail(function() {
				c.reject({
					error: "璇勮淇濆瓨澶辫触锛岃閲嶈瘯"
				})
			}), c.promise()
		},
		addComment: function(a) {
			var b = this.createHtml(a);
			this.$element.find(".list-comment").prepend(b)
		},
		removeComment: function(a) {
			var b = $.Deferred(),
				c = $.post("/comment/remove", {
					code: this.opts.code,
					id: a
				}, null, "json");
			return c.done(function(a) {
				a.status ? b.resolve(a.info) : b.reject({
					error: a.info
				})
			}), c.fail(function() {
				b.reject({
					error: "璇勮鍒犻櫎澶辫触锛岃閲嶈瘯"
				})
			}), b.promise()
		},
		initPager: function() {
			new Pager({
				monitor: this.$element.find(".more"),
				nextUrl: "/comment/view/code/" + this.opts.code,
				container: this.$element.find(".list-comment"),
				pauseInterval: 3
			})
		},
		createDOM: function() {
			this.$element = $("<div />", {
				"class": "box-comments"
			}), this.$element.html(Comment.TPL.CONTAINER), this.$element.appendTo(this.$container)
		},
		openReplyBox: function(a) {
			var b = this,
				c = $(Comment.TPL.REPLYBOX);
			c.find("form").validator({
				isErrorOnParent: !0,
				after: function() {
					c.find(".actions .btn").addClass("disabled"), c.find("[type=submit]").prop("disabled", !0);
					var d = b.pushComment(a || 0, $(this).find("textarea").val());
					return d.always(function() {
						c.find(".actions .btn").removeClass("disabled"), c.find("[type=submit]").prop("disabled", !1)
					}), d.done(function(a) {
						c.modal("destroy"), b.addComment(a)
					}), d.fail(function(a) {
						alert(a.error)
					}), !1
				}
			}), c.appendTo("body"), c.modal("show")
		},
		checkLogin: function() {
			return this.user ? !0 : (confirm("浣犳病鏈夌櫥褰曪紝鏄惁椹笂鍓嶅線鐧诲綍锛�") && (location.href = "/oauth/login?return=" + encodeURIComponent(location.href)), !1)
		},
		bindEvent: function() {
			var a = this;
			this.$element.on("click", ".mainReply", function(b) {
				b.preventDefault(), a.checkLogin() && a.openReplyBox()
			}), this.$element.on("click", ".delete", function(b) {
				if (b.preventDefault(), a.checkLogin() && !$(this).hasClass("disabled") && confirm("纭畾鍒犻櫎姝よ瘎璁猴紵")) {
					$(this).addClass("disabled");
					var c = $(this).parents(".item");
					a.removeComment(c.data("id")), c.remove()
				}
			}), this.$element.on("click", ".reply", function(b) {
				b.preventDefault(), a.checkLogin() && a.openReplyBox($(this).parents(".item").data("id"))
			})
		},
		createHtml: function(a) {
			return a
		}
	}, Comment.prototype.constructor = Comment, $(function() {
		Weixin.init(window.wxconfig, window.shareData), Weixin.error(function(a) {
			monitor.error(a)
		})
	}), $(function() {
		var a = document.createElement("style");
		a.appendChild(document.createTextNode("")), document.head.appendChild(a), window.isWechat && a.sheet.insertRule(".on-wechat {display: block;}", 0)
	}),
	function() {
		var a = navigator.userAgent.toLowerCase(); - 1 != a.indexOf("micromessenger") && (window.isWechat = !0, window.onapp = !1)
	}(), $(function(a) {
		function b() {
			var b = a('input[name="phonebook_id"]:checked').val(); - 1 == b ? (a("#phonebook-form").show(), a("#phonebook-btn").hide()) : (a("#phonebook-form").hide(), a("#phonebook-btn").show())
		}

		function c() {
			var b = a('[auto-address="true"]');
			if ("" == b.val()) {
				if (b.offset().top <= 0) return void b.click(function() {
					c()
				});
				var e = navigator.geolocation;
				e && e.getCurrentPosition(function(c) {
					if (c) {
						var e = c.coords.latitude,
							f = c.coords.longitude;
						a("#latitude").val(e), a("#longitude").val(f), a.get("/index/geocoder", {
							latitude: e,
							longitude: f
						}, function(c) {
							c && c.formatted_address && b.each(function() {
								"" == a(this).val() && c.formatted_address && a(this).val(c.formatted_address)
							})
						}, "json")
					} else d()
				}, function() {
					d()
				}, {
					maximumAge: 1e3
				})
			}
		}

		function d() {
			a.get("/index/cityInfo", {}, function(b) {
				m.each(function() {
					"" == a(this).val() && b.address && a(this).val(b.address)
				})
			}, "json")
		}

		function f(b) {
			var c, d, e = b.find("input"),
				f = a("#list-search"),
				h = b.parent().find(".view-list, .view-more"),
				i = function(a) {
					a && (h.addClass("util-hide"), f.removeClass("util-hide"), f.find("img").lazy("destroy"), f.empty().html('<div class="alert alert-warning wrapper text-center">' + a + "</div>"))
				},
				l = function(a) {
					h.addClass("util-hide"), f.find("img").lazy("destroy"), f.removeClass("util-hide").empty().html(a), f.find("img.lazy").removeClass("lazy").lazy()
				};
			e.on("input", function() {
				c && c.reject(), d && clearTimeout(d);
				var e = a(this).attr("name"),
					j = a.trim(a(this).val());
				"" != j ? d = setTimeout(function() {
					c = a.Deferred(), g(b.attr("action"), [{
						name: e,
						value: j
					}], c), c.done(l).fail(i)
				}, 100) : (f.empty().addClass("util-hide"), h.removeClass("util-hide"))
			}), e.on("focus", function() {
				j();
				var b = a(this).offset().top;
				setTimeout(function() {
					a(window).scrollTop(b - 50)
				}, 800)
			}), e.on("blur", k)
		}

		function g(b, c, d) {
			var e = a.getJSON(b, c);
			return e.done(function(b) {
				b && "" != a.trim(b.html) ? d.resolve(b.html) : d.reject("鎵句笉鍒板唴瀹�")
			}), e.fail(function() {
				d.reject("缃戠粶绻佸繖")
			}), d
		}

		function h() {
			n.each(function() {
				var b = {
						date: {
							preset: "date",
							dateOrder: "yymmdd",
							dateFormat: "yy-mm-dd"
						},
						datetime: {
							preset: "datetime",
							dateOrder: "yymmdd",
							dateFormat: "yy-mm-dd",
							timeFormat: "HH:ii"
						},
						time: {
							preset: "time",
							timeFormat: "hh:ii"
						}
					},
					c = a(this).attr("type");
				a(this).attr("type", "text").scroller(a.extend(b[c], {
					lang: "zh"
				}))
			})
		}

		function i() {}

		function j() {
			a("#site-nav").addClass("util-invisible")
		}

		function k() {
			a("#site-nav").removeClass("util-invisible")
		}

		function l() {
			a("img.lazy").removeClass("lazy").lazy({
				onError: function() {
					a(this).attr("src", "/Public/image/avatar.jpg")
				}
			})
		}
		if (a("#flashMessage")[0] && setTimeout(function() {
			"#" != a("#flashMessage").val() && alert(a("#flashMessage").val()), a("#flashMessage").val("#")
		}, 100), a(".listview-hide-show").on("click", function() {
			return a(".listview-hide-show").hide(), a(".listview-hide").show(), setTimeout(function() {
				a.lazy.refresh()
			}, 1e3), !1
		}), a("img.avatarimg").one("error", function() {
			a(this).attr("src", "/Public/image/avatar.jpg")
		}), a(document).on("click", '[data-rel="popup"]', function() {
			var b = a(this).attr("href");
			if (b) return a(b).modal("show"), !1
		}), a(document).on("click", '[data-action="help-agent"]', function() {
			return alert("寮曡崘寰界珷锛氭湁鎴愬姛寮曡崘鐢ㄦ埛鍔犲叆!"), !1
		}), a(document).on("click", '[data-action="help-share"]', function() {
			return alert("鍒嗕韩寰界珷锛氬府鍔╃兢涓诲垎浜€氳褰曪紒"), !1
		}), a('[data-action="message"]').on("click touchstart", function() {
			var b = a(this).data("id");
			b > 0 && a.getJSON("/message/read?id=" + b);
			var c = a(this).attr("href");
			return setTimeout(function() {
				window.location.href = c
			}, 500), !1
		}), a(document).on("click", 'div[data-show="showCard"]', function() {
			var b = a(this).parent().find(".show"),
				c = a(this),
				d = a(this).attr("href");
			if (!d) return !1;
			if (b[0]) return i(b), b.toggle(), !1;
			b = a('<div class="show">鍔犺浇涓�...</div>'), c.after(b);
			var d = a(this).attr("href");
			return a.get(d, function(c) {
				c = a("<div>" + c + "</div>");
				var d = c.find(".card-info").html(),
					e = c.find(".control-bar").html();
				b.html(d + e), i(b)
			}), !1
		}), a("#phonebook-select").find(".radio").size() > 0 && (b(), a("#phonebook-select").change(function() {
			b()
		})), a(document).on("click", '[data-action="showChat"]', function() {
			var b = a(this).attr("data-chathash");
			return b ? callApp("showChat", b) : void 0
		}), a(document).one("click", '[data-action="show-phonebook-content"]', function(b) {
			b.preventDefault(), a(this).hide(), a(".page-header .content-main").addClass("all")
		}), a(document).on("click", '[data-action="certify"]', function() {
			return e.preventDefault(), window.isWechat && (location.href = "http://mp.weixin.qq.com/s?__biz=MzA4NzA4NzcxMw==&mid=200870244&idx=1&sn=4440cfbbe0d625f5685d97a45428f270&scene=1#rd"), callApp("showJiaV", getAppParam("showJiaV"))
		}), a(document).on("click", '[data-action="phonebookAssistSelect"]', function() {
			var b = a(this).attr("data-code");
			return b ? callApp("phonebookAssistSelect", b) : void 0
		}), a(document).on("click", '[data-action="setPass"]', function() {
			var b = a(this).attr("openid"),
				c = a(this).attr("code"),
				d = a(this).attr("state"),
				e = a(this),
				f = "鏄惁纭鍙栨秷姝ょ敤鎴锋潈闄愶紵";
			return 1 == d && (f = "鏄惁纭鎵瑰噯姝ょ敤鎴凤紵"), b ? (confirm(f) && a.ajax({
				type: "GET",
				url: "/index/pass",
				dataType: "json",
				data: {
					openid: b,
					code: c,
					state: d
				},
				success: function(a) {
					a.status ? (e.after(a.info), e.remove(), confirm("鎿嶄綔鎴愬姛锛屾槸鍚﹀埛鏂版煡鐪嬬粨鏋�") && window.location.reload()) : alert(a.info)
				}
			}), !1) : void 0
		}), a(document).on("click", ".ico-approve", function() {
			return alert("姝ゅ悕鐗囧凡璁よ瘉锛屽悕鐗囪璇佹柟娉曪細\n1.鐐瑰嚮銆愭垜鐨勫悕鐗囥€慭n2.鎵撳紑闇€瑕佽璇佺殑鍚嶇墖\n3.椤甸潰搴曢儴鐐瑰嚮銆愬悕鐗囪璇併€�"), !1
		}), a(document).on("click", '[data-action="setRole"]', function() {
			var b = a(this).attr("openid"),
				c = a(this).attr("code"),
				d = a(this).attr("role"),
				e = a(this);
			return b ? (confirm("鏄惁纭璁剧疆姝ょ敤鎴凤紵") && a.ajax({
				type: "POST",
				url: "/index/setrole",
				dataType: "json",
				data: {
					openid: b,
					code: c,
					role: d
				},
				success: function(a) {
					a.status ? (e.after(a.info), e.remove(), confirm("鎿嶄綔鎴愬姛锛屾槸鍚﹀埛鏂版煡鐪嬬粨鏋�") && window.location.reload()) : alert(a.info)
				}
			}), !1) : void 0
		}), a('[auto-address="true"]')[0]) {
			var m = a('[auto-address="true"]');
			if (!m) return;
			setTimeout(c, 3e3)
		}
		a(document).on("click", '[data-action="save-to-phone"]', function() {
			if (window.onapp) {
				var b = a(this).attr("data-card-id"),
					c = window.cards[b];
				c && callApp("savePhoneBook", c)
			}
			return !1
		}), a("#help-form").validator({
			isErrorOnParent: !0,
			after: function() {
				var b = a(this),
					c = a.post(b.attr("action"), b.serializeArray(), null, "json");
				return b.find("input").prop("disabled", !0).addClass("disabled"), c.always(function() {
					b.find("input").prop("disabled", !1).removeClass("disabled")
				}), c.done(function(a) {
					a.status ? window.location.href = a.url : alert(a.info)
				}), c.fail(function() {
					alert("缃戠粶绻佸繖锛岃鍒锋柊閲嶈瘯")
				}), !1
			}
		}), a(document).scroll(function() {
			var b = document.activeElement.tagName.toLowerCase();
			"textarea" == b || "input" == b ? a("body").addClass("editing") : a("body").removeClass("editing")
		}), a("form .auto-size").size() > 0 && (a("form .auto-size").on("propertychange", function() {
			a(this).height(a(this)[0].scrollHeight)
		}).on("input", function() {
			a(this).height(a(this)[0].scrollHeight)
		}), a("form .auto-size").each(function() {
			"" != a(this).val() && a(this)[0].scrollHeight > 10 && a(this).height(a(this)[0].scrollHeight)
		})), a(document).on("click", '[data-action="chat"]', function() {
			return window.onapp ? (callApp("chatThat", JSON.stringify(window.chatData)), !1) : void 0
		}), a(document).on("click", '[data-action="share"]', function(b) {
			b.preventDefault();
			var c = a(this).data("share");
			try {
				c && a.extend(Weixin.shareConfig, c)
			} catch (b) {}
			return window.onapp ? (callApp("share", JSON.stringify(Weixin.shareConfig)), !1) : void Weixin.share()
		}), a(document).on("click", '[data-action="favorite"]', function(a) {
			return a.preventDefault(), window.onapp ? (callApp("share", JSON.stringify(Weixin.shareConfig)), !1) : void Weixin.favorite()
		}), a(document).on("click", '[data-action="share-other"]', function() {
			if (window.onapp) return callApp("share", JSON.stringify(Weixin.shareConfig)), !1;
			if (!a(".share-other-popup")[0]) {
				var b = a('<div class="share-other-popup modal" data-modal="alert">\n\n	<div class="modal-container">\n		<div class="text-center">\n			<p><b>闀挎寜閫夋嫨</b>涓嬮潰鏂囧瓧<b>澶嶅埗</b>杩涜鍒嗕韩</p>\n		</div>\n		<textarea class="select" style="ime-mode:disabled">			\n		</textarea>		\n		<div class="actions">\n			<a href="#" class="btn btn-block btn-default" data-dismiss="modal">鍏抽棴绐楀彛</a>\n		</div>\n	</div>\n\n</div>');
				a("body").append(b)
			}
			return a(".share-other-popup").find(".select").text("鍒嗕韩閾炬帴\n" + Weixin.link()), a(".share-other-popup").modal("show"), a(".share-other-popup .select").click(function() {
				a(".share-other-popup").focus().select()
			}), a(".share-other-popup").find(".select").focus().select(), !1
		}), setTimeout(function() {
			a('form[data-ajax="true"]').each(function() {
				a(this).on("submit", function() {
					var b = a(this);
					return a.ajax({
						type: b.attr("method"),
						url: b.attr("action"),
						dataType: "json",
						data: b.serialize(),
						success: function(a) {
							var c = b.find('[name="phone"]').val();
							if (a.status) window.location.href = a.url;
							else if (2001 == a.code || 2002 == a.code) {
								if (!confirm("涓轰繚闅滀俊鎭湡瀹烇紝绯荤粺灏嗗悜銆�" + c + "銆戝彂閫佺煭淇￠獙璇佺爜锛屾槸鍚︾‘璁ゅ彂閫侊紵")) return b.find("[type=submit]").prop("disabled", !1), !1;
								var d = window.userKeycodeLogin;
								d.init(), d.sendCode(c), d.show(), d._callback = function() {
									b.submit(), d.hide()
								}
							} else alert(a.info), b.find("[type=submit]").prop("disabled", !1)
						},
						error: function() {
							b.find("[type=submit]").prop("disabled", !1)
						}
					}), !1
				})
			})
		}, 100), a("#complaintForm").on("show", function() {
			a(this).find("textarea").val("").focus(), a(this).find("[type=submit]").prop("disabled", !1)
		}), a("#complaintForm form").validator({
			isErrorOnParent: !0,
			after: function() {
				a(this).find("[type=submit]").prop("disabled", !0);
				var b = a(this).serializeArray();
				return b.push({
					name: "url",
					value: location.href
				}), a.post("/complaint/index", b), alert("鎰熻阿鎮ㄧ殑涓炬姤锛屾垜浠細灏藉揩澶勭悊锛�"), a(this).find("[data-dismiss=modal]").click(), !1
			}
		}), a(document).on("click", '[data-action="brower-popup"]', function() {
			return window.onapp ? !1 : (Weixin.openbybrowser(), !1)
		}), a(".view-search").each(function() {
			a(this).on("submit", !1), f(a(this))
		});
		var n = a("[type=date], [type=datetime], [type=time]");
		return yepnope({
			test: n.size() > 0,
			yep: ["/Public/lib/mobiscroll.js", "/Public/lib/mobiscroll.css"],
			complete: h
		}), i(a("body")), l(), a(document).on("click", '[data-action="addFriend"]', function() {
			var b = a(this).attr("openid"),
				c = a(this);
			return confirm("纭涓嶵A浜ゆ崲鍚嶇墖鍚楋紵") ? (c.html('<p style="text-align: center">浜ゆ崲涓�...</p>'), b && a.ajax({
				type: "POST",
				url: "/card/add",
				dataType: "json",
				data: {
					id: b
				},
				success: function(a) {
					2115 == a.code ? c.after('<p class="alert text-center alert-warning" >' + a.info + '<a href="/user/level" target="_blank">濡備綍鎻愬崌绛夌骇锛�</a></p>').remove() : c.after('<p class="alert text-center alert-warning" >' + a.info + "</p>").remove(), 0 == a.status && a.url && (window.location.href = a.url)
				}
			}), !1) : !1
		}), a(document).on("click", '[data-action="addFriendAgent"]', function() {
			var b = a(this).attr("openid"),
				c = a(this).attr("code"),
				d = a(this);
			return confirm("鍙互閫氳繃缇や富浠嬬粛锛岃鎮ㄥ拰浠栨垚涓烘湅鍙嬶紝鏄惁鎻愬嚭鐢宠锛�") ? (d.html('<p style="text-align: center">鐢宠涓�...</p>'), b && a.ajax({
				type: "POST",
				url: "/index/agentadd",
				dataType: "json",
				data: {
					code: c,
					openid: b
				},
				success: function(a) {
					d.after('<p class="alert text-center alert-warning" >' + a.info + "</p>").remove(), 0 == a.status && (alert(a.info), a.url && (window.location.href = a.url))
				}
			}), !1) : !1
		}), a(document).on("click", ".grid-select li", function() {
			a(this).find("input").prop("checked", "checked"), a(this).addClass("active"), a(this).siblings().each(function() {
				a(this).find("input:checked")[0] ? a(this).addClass("active") : a(this).removeClass("active")
			})
		}), window.userKeycodeLogin = {
			$elm: null,
			_callback: {},
			sendCode: function(b) {
				this.$elm.find('[name="phone"]').val(b), a.post("/index/getPhoneKey", {
					phone: b
				}, null, "json")
			},
			init: function() {
				this.useElm(), this.addEvent()
			},
			show: function() {
				this.$elm.modal("show")
			},
			hide: function() {
				this.$elm.modal("hide")
			},
			useElm: function() {
				this.$elm || (this.$elm = a('\n<article id="virtualMember" class="modal">\n	<div class="modal-container">\n		<div class="modal-title">楠岃瘉鎵嬫満楠岃瘉鐮�</div>\n		<form id="" action="#" method="post" data-validate="auto">\n			<div class="alert text-center">\n				涓轰繚闅滀俊鎭湡瀹烇紝<br>绯荤粺姝ｅ彂<b>鐭俊楠岃瘉鐮�</b>缁欐偍銆�<br>\n				璇风◢绛�<b class="sec">60</b>绉掋€俓n			</div>\n			<div class="form-group">				\n				<label class="control-label" for="code">璇疯緭鍏ョ煭淇￠獙璇佺爜</label>\n				<input name="code" value="" id="code" type="text" class="form-control text-center"\n				       pattern="^\\d{1,6}$" placeholder="璇疯緭鍏ョ煭淇￠獙璇佺爜" required/>\n\n				<div class="help-block empty">璇峰～鍐欑煭淇￠獙璇佺爜</div>\n				<div class="help-block unvalid">鍙兘濉啓鏁板瓧锛屾渶澶�6浣嶆暟</div>\n			</div>\n			<div class="form-group">\n				<input type="hidden" name="phone" />\n				<button class="btn btn-block">鎻愪氦</button>\n			</div>\n		</form>\n		<div class="actions">\n			<div class="btn btn-block btn-default" data-dismiss="modal">鍏抽棴绐楀彛</div>\n		</div>\n	</div>\n</article>\n'), this.$elm.appendTo("body"))
			},
			callback: function() {
				this._callback && this._callback()
			},
			addEvent: function() {
				this.$elm.find("form").validator({
					isErrorOnParent: !0,
					after: function() {
						var b = a(this),
							c = a.post("/index/loginByPhoneKey", a(this).serializeArray(), null, "json");
						return b.find("input").prop("disabled", !0).addClass("disabled"), c.always(function() {
							b.find("input").prop("disabled", !1).removeClass("disabled")
						}), c.done(function(a) {
							return a ? a.status ? void window.userKeycodeLogin.callback() : (alert(a.info), !1) : (alert("鎻愪氦澶辫触"), !1)
						}), c.fail(function() {
							alert("缃戠粶绻佸繖锛岃鍒锋柊閲嶈瘯")
						}), !1
					}
				})
			}
		}, !window.openid && window.onapp ? (callApp("webNotSign", ""), !1) : void 0
	}), modjs.define("selectPriceType", function() {
		2 == $("#pay-type").val() ? ($("#pay-group").show(), $("#other-group").hide()) : ($("#pay-group").hide(), 3 == $("#pay-type").val() ? $("#other-group").show() : $("#other-group").hide()), $(document).on("change", "#pay-type", function() {
			2 == $(this).val() ? ($("#pay-group").show(), $("#other-group").hide()) : ($("#pay-group").hide(), 3 == $(this).val() ? $("#other-group").show() : $("#other-group").hide())
		})
	}), modjs.define("activity/create", function(a) {
		a("selectPriceType"); {
			var b = $("#form-create .form-file .upload-area");
			a("upload")({
				trigger: b,
				name: "logo",
				form: "#form-create",
				token: window.qiniuToken,
				bucket: window.qiniuBucket,
				progress: function(a) {
					b.find(".text").hide(), b.find(".proportion").remove(), $('<span class="proportion" />').text("鍥剧墖涓婁紶涓�(" + a + "%)").appendTo(b)
				},
				complete: function() {
					b.find(".proportion").remove(), b.find(".text").show(), b.siblings(".cancel").addClass("util-hide")
				},
				success: function(a) {
					var c = b.siblings("img");
					c.size() < 1 && (c = $("<img />").insertBefore(b)), c.attr("src", a + "!w640"), $("#logo").val(a)
				},
				error: function() {
					alert("涓婁紶鍥剧墖澶辫触")
				}
			})
		}
		$(".share-icon a[data-url]").on("click", function(a) {
			a.preventDefault(), $(this).addClass("active").siblings("a").removeClass("active"), $("[name=share_icon]").val($(this).data("url"))
		});
		var c = $(".share-icon .upload-btn");
		if (window.activity_share_icon) {
			var d = $(".share-icon a[data-url='" + window.activity_share_icon + "']");
			d.size() > 0 ? d.click() : (c.find("img").attr("src", window.activity_share_icon), c.parent().addClass("active").siblings("a").removeClass("active"))
		}
		a("upload")({
			trigger: c,
			name: "share_icon",
			form: "#form-create",
			token: window.qiniuToken,
			bucket: window.qiniuBucket,
			preview: function(a) {
				this.$trigger.find("img").attr("src", a)
			},
			progress: function(a) {
				this.$trigger.find(".proportion").remove(), $('<div class="proportion" />').text("涓婁紶涓�(" + a + "%)").appendTo(this.$trigger)
			},
			process: function(a) {
				return a + "!200"
			},
			success: function() {
				this.$trigger.find(".proportion").remove(), this.$trigger.parent().addClass("active").siblings("a").removeClass("active")
			},
			error: function() {
				alert("涓婁紶鍥剧墖澶辫触")
			}
		}), a("upload")({
			name: "cover",
			trigger: ".upload-cover",
			form: "#form-create",
			token: window.qiniuToken,
			bucket: window.qiniuBucket,
			enable: function() {
				this.$trigger.removeClass("btn-disabled")
			},
			disable: function() {
				this.$trigger.addClass("btn-disabled")
			},
			success: function(a) {
				var b = this.$trigger.siblings("img");
				b.size() < 1 && (b = $('<img style="margin-right: 0.3em;" />').insertBefore(this.$trigger)), b.attr("src", a + "!200x230")
			},
			error: function() {
				alert("涓婁紶鍥剧墖澶辫触")
			}
		})
	}), define("activity/view", function() {
		$(document).on("click", "#showMore", function() {
			$(this).text("鏌ョ湅璇︽儏" == $(this).text() ? "鏀惰捣" : "鏌ョ湅璇︽儏"), $("#span-more").toggleClass("open"), $("#span-less").toggle()
		})
	}), define("activity/setqunqrcode", function(a) {
		a("upload")({
			name: "image",
			trigger: $(".upload-from .placeholder, .site-setqunqrcode .change"),
			form: ".upload-from",
			token: window.uploadToken,
			bucket: window.uploadBucket,
			autoSubmit: !0,
			preview: function(a) {
				this.$form.find(".avatar .thumb").attr("src", a), this.$form.find(".placeholder").remove(), this.$form.find(".avatar").removeClass("util-hide")
			},
			progress: function(a) {
				this.$form.find(".progress").css("line-height", this.$form.find(".avatar .thumb").height() + "px"), this.$form.find(".progress").text("姝ｅ湪涓婁紶(" + a + "%)")
			},
			error: function() {
				alert("涓婁紶鍥剧墖澶辫触")
			}
		})
	}), define("activity-upload", function(a) {
		return function(b, c, d, e) {
			e || (e = "w640"), b = $(b), b.submit(function(a) {
				var c = b.find('[name="image"]').val();
				c || a.preventDefault()
			});
			var f = a("upload")({
				name: "image",
				trigger: b.find(".placeholder, .avatar .change"),
				form: b,
				token: c,
				bucket: d,
				preview: function(a) {
					b.find(".avatar .thumb").attr("src", a)
				},
				progress: function(a) {
					b.find(".progress").css("line-height", b.find(".avatar .thumb").height() + "px"), b.find(".progress").text("姝ｅ湪涓婁紶(" + a + "%)")
				},
				success: function(a) {
					b.find(".thumb").attr("src", a + "!" + e), b.find('[name="image"]').val(a)
				},
				error: function() {
					alert("涓婁紶鍥剧墖澶辫触")
				},
				complete: function() {
					$(".upload-progress").addClass("util-hide")
				}
			});
			f.on("disable", function() {
				b.find(".placeholder").remove(), b.find(".avatar").removeClass("util-hide on off").addClass("on"), b.find(".progress").css("line-height", b.find(".avatar .thumb").height() + "px"), b.find(".progress").text("姝ｅ湪涓婁紶鍥剧墖")
			}), f.on("enable", function() {
				b.find(".avatar").removeClass("on off").addClass("off"), b.find(".avatar .progress").empty()
			})
		}
	}), define("changeImage", function(a) {
		return function(b, c) {
			a("upload")({
				name: "image",
				trigger: "#uploadForm a, .upload-area",
				form: "#uploadForm",
				token: b,
				bucket: c,
				autoSubmit: !0,
				preview: function(a) {
					$("#uploadThumb").attr("src", a)
				},
				progress: function(a) {
					$(".upload-progress").removeClass("util-hide").find("span").text(a + "%")
				},
				complete: function() {
					$(".upload-progress").addClass("util-hide")
				}
			})
		}
	}), define("card/view", function() {
		return $(document).on("click", '[data-action="show"]', function() {
				var a = "/card/setprivacy",
					b = $(this),
					c = $(this).attr("data-code");
				b.text("鎿嶄綔涓�..."), $.get(a, {
					code: c,
					privacy: 0,
					ajax: 1
				}, function(a) {
					a.msg ? (b.text("璁剧疆闅愯棌鍙风爜"), b.attr("data-action", "hide"), b.removeClass("badge-success").addClass("badge-red"), window.location.reload()) : alert(a.error)
				}, "json")
			}), $(document).on("click", '[data-action="hide"]', function() {
				var a = "/card/setprivacy",
					b = $(this),
					c = $(this).attr("data-code");
				b.text("鎿嶄綔涓�..."), $.get(a, {
					code: c,
					privacy: 1,
					ajax: 1
				}, function(a) {
					a.msg ? (b.text("璁剧疆鍏紑鍙风爜"), b.attr("data-action", "show"), b.removeClass("badge-red").addClass("badge-success"), window.location.reload()) : alert(a.error)
				}, "json")
			}), $('[data-action="cardHelp"]').click(function(a) {
				a.preventDefault(), $(".tabview").hide(), $(".help-form").show(), $('[name="realname"]').focus().select()
			}),
			function(a, b) {
				$.ajax({
					url: a,
					dataType: "jsonp",
					jsonp: "callback",
					success: function(a) {
						if (a.length > 0) {
							var c = "";
							for (var d in a) {
								var e = a[d].cover,
									f = a[d].title.substr(0, 9),
									g = a[d].top_price / 100;
								c += '<a href="http://app.qun.hk/fw/goods/view/good_id/' + a[d].good_id + '" class="service-item"><img src="' + e + '_200.jpg" alt=""/><h2>' + f + '</h2><span>锟�</span><span class="price">' + g + "</span></a> "
							}
							$("#service").html(c), $(".site-card-view").find(".nav-tabs").show()
						} else 1 == b ? ($(".site-card-view").find(".nav-tabs").show(), $("#service").html('<img src="/Public/image/card_service.jpg" style="max-width: 100%;height: auto;display: block" />')) : $(".site-card-view").find(".nav-tabs").hide()
					}
				})
			}
	}), $(document).on("click", ".site-share", function(a) {
		a.preventDefault(), $(this).hide()
	}), define("forum/post", function(a) {
		var b = $(".nav-tabs .nav-item");
		b.click(function(a) {
			a.preventDefault(), location.hash = $(this).find("a").attr("href")
		}), $(window).on("hashchange", function() {
			var a = ["help", "topic", "activity", "file"],
				b = location.hash.slice(1); - 1 === a.indexOf(b) && (b = "topic");
			var c = a.indexOf(b); - 1 !== c && ($(".nav-tabs .nav-item").eq(c).addClass("active").siblings().removeClass("active"), $(".tab-content").eq(c).addClass("active").siblings().removeClass("active"))
		}), $(window).trigger("hashchange"), $(".form-help, .form-topic").each(function() {
			var b = [],
				c = $(this).find(".imglist"),
				d = a("upload")({
					name: "image",
					multiple: !0,
					trigger: $(this).find(".addimage"),
					token: window.qiniuToken,
					bucket: window.qiniuBucket,
					enable: function() {
						this.$trigger.removeClass("disabled")
					},
					disable: function() {
						this.$trigger.addClass("disabled")
					},
					progress: function() {
						this.$trigger.text("姝ｅ湪涓婁紶")
					},
					success: function(a) {
						var d = $('<div class="img"><img src="' + a + '!w640"/><input type="hidden" name="images[]" value="' + a + '" /></div>');
						c.append(d), b.push(a), b.length >= 9 && this.disable()
					},
					error: function() {
						alert("涓婁紶鍥剧墖澶辫触")
					},
					complete: function() {
						this.$trigger.text("娣诲姞鍥剧墖")
					}
				});
			$(this).find(".imglist").on("click", "img", function() {
				if (confirm("纭畾鍒犻櫎姝ゅ紶鍥剧墖鍚楋紵")) {
					var a = $(this).index();
					b.splice(a, 1), d.enable(), $(this).remove()
				}
			})
		});
		var c, d, e = $(".form-file"),
			f = function(a) {
				var b = "";
				for (var c in a) b += 0 == c ? '<a href="#" class="list-group-item listview-item radio checked" radio="name"><div class="list-group-item-hd"><input type="radio" checked name="doc_id" value="' + a[c].doc_id + '"/><div class="input-radio"></div></div><div class="list-group-item-bd"><div class="content">' + a[c].title + "</div></div></a> " : '<a href="#" class="list-group-item listview-item radio" radio="name"><div class="list-group-item-hd"><input type="radio" name="doc_id" value="' + a[c].doc_id + '"/><div class="input-radio"></div></div><div class="list-group-item-bd"><div class="content">' + a[c].title + "</div></div></a> ";
				e.find(".files").html(b)
			};
		c && c.abort(), c = $.get("/docs/search", {}, function(a) {
			null != a ? f(a) : e.find(".files").html('<span style="color: #ff0000">鏆傛椂娌℃湁鏂囦欢</span> ')
		}, "json"), e.find(".keywords").on("input", function() {
			d && clearTimeout(d), d = setTimeout(function() {
				c && c.abort(), c = $.get("/docs/search", {
					keywords: $(this).val(),
					limit: 4
				}, function(a) {
					null != a ? f(a) : e.find(".files").html('<span style="color: #ff0000">鏆傛椂娌℃湁鏂囦欢</span> ')
				}, "json")
			}.bind(this), 500)
		});
		var g, h, i = $(".form-activity"),
			j = function(a) {
				var b = "";
				for (var c in a) b += 0 == c ? '<a href="#" class="list-group-item listview-item radio checked" radio="activity_id"><div class="list-group-item-hd"><input type="radio" checked name="activity_id" value="' + a[c].activity_id + '"/><div class="input-radio"></div></div><div class="list-group-item-bd"><div class="content">' + a[c].title + "</div></div></a> " : '<a href="#" class="list-group-item listview-item radio" radio="activity_id"><div class="list-group-item-hd"><input type="radio" name="activity_id" value="' + a[c].activity_id + '"/><div class="input-radio"></div></div><div class="list-group-item-bd"><div class="content">' + a[c].title + "</div></div></a> ";
				i.find(".activitys").html(b)
			};
		g && g.abort(), g = $.get("/activity/search", {}, function(a) {
			null != a ? j(a) : i.find(".activitys").html('<span style="color: #ff0000">鏆傛椂娌℃湁娲诲姩</span> ')
		}, "json"), i.find(".keywords").on("input", function() {
			h && clearTimeout(d), h = setTimeout(function() {
				g && g.abort(), g = $.get("/activity/search", {
					keywords: $(this).val(),
					limit: 4
				}, function(a) {
					null != a ? j(a) : i.find(".activitys").html('<span style="color: #ff0000">鏆傛椂娌℃湁娲诲姩</span> ')
				}, "json")
			}.bind(this), 500)
		})
	}), define("docs/post", function(a) {
		if (a("upload")({
			name: "url",
			trigger: ".form-file a",
			form: ".site-docs-post form",
			token: window.qiniuToken,
			bucket: window.qiniuBucket,
			accept: "*",
			autoSubmit: !0,
			progress: function(a) {
				this.$trigger.text("姝ｅ湪涓婁紶(" + a + "%)")
			},
			error: function() {
				alert("涓婁紶鏂囦欢澶辫触")
			},
			complete: function() {
				this.$trigger.text("鐐瑰嚮涓婁紶")
			}
		}), void 0 == window.isPc) {
			var b, c = $("#hash").val();
			setInterval(function() {
				b && b.abort(), b = $.get("/docs/result", {
					hash: c
				}, function(a) {
					var b = parseInt(a.status);
					b >= 0 && ($("#mobile").hide(), $("#pc_upload").show().find("b").text(b), $("#my").show())
				}, "json")
			}, 5e3)
		}
	}), define("docs/index", function() {
		$(document).on("blur", ".search-input", function() {
			$(this).parent()[0].submit()
		})
	}), define("docs/view", function() {
		$(document).on("click", '[data-action="down"]', function() {
			var a = $(this).data("hash"),
				b = $(this).data("email");
			if (0 == $("#down-modal").length) {
				var c = $('<div class="modal" id="down-modal" data-modal="alert">\n	<form action="/docs/send" method="post">\n		<input type="hidden" name="hash" id="hash" />\n		<div class="modal-container">\n			<label for="email">閭鍦板潃锛�</label>\n			<input type="text" name="email" id="email" class="form-control" />\n			<div class="actions">\n				<button type="submit" class="btn btn-primary btn-block">纭畾鍙戦€�</button>\n				<a href="#" class="btn btn-block btn-default" data-dismiss="modal">鍏抽棴绐楀彛</a>\n			</div>\n		</div>\n	</form>\n\n</div>');
				$("body").append(c)
			}
			$("#hash").val(a), $("#email").val(b), $("#down-modal").modal("show")
		}), $(document).on("focus", "#content", function() {
			$(".docs-ft").hide()
		})
	}), define("card/cerfity", function(a) {
		$("#form-create").submit(function() {
			return "" == $("[name=material_idcard]").val() || "" == $("[name=material_card]").val() ? (alert("璇烽€夋嫨鐓х墖绛夊緟涓婁紶瀹屾瘯鍚庯紝鎵嶇偣鍑绘彁浜よ璇�"), !1) : void 0
		}), a("upload")({
			name: "material_card",
			trigger: ".upload-card",
			form: "#form-create",
			token: window.qiniuToken,
			bucket: window.qiniuBucket,
			enable: function() {
				this.$trigger.removeClass("disabled")
			},
			disable: function() {
				this.$trigger.addClass("disabled")
			},
			success: function() {
				this.$trigger.removeClass("btn-default").addClass("btn-success").text("鍚嶇墖鍙婂伐鐗�(宸蹭笂浼 )")
			},
			progress: function(a) {
				this.$trigger.text("涓婁紶涓�(" + a + "%)")
			},
			error: function() {
				this.$trigger.text("涓婁紶"), alert("涓婁紶澶辫触")
			}
		}), a("upload")({
			name: "material_idcard",
			trigger: ".upload-idcard",
			form: "#form-create",
			token: window.qiniuToken,
			bucket: window.qiniuBucket,
			enable: function() {
				this.$trigger.removeClass("disabled")
			},
			disable: function() {
				this.$trigger.addClass("disabled")
			},
			success: function() {
				this.$trigger.removeClass("btn-default").addClass("btn-success").text("韬唤璇佹闈�(宸蹭笂浼 )")
			},
			progress: function(a) {
				this.$trigger.text("涓婁紶涓�(" + a + "%)")
			},
			error: function() {
				this.$trigger.text("涓婁紶"), alert("涓婁紶澶辫触")
			}
		})
	}), $('[data-action="shareComponent"]').removeClass("btn-block"), $(document).on("click", '[data-action="shareComponent"]', function(a) {
		if (a.preventDefault(), window.onapp) {
			var b = $.extend(!0, {}, Weixin.shareConfig);
			return b.MsgImg = b.TLImg = b.imgUrl, callApp("share", JSON.stringify(b)), !1
		}
		$(".site-share img").each(function() {
			$(this).attr("src", $(this).data("src"))
		}), $(".site-share").show()
	}), $(document).on("click", ".download", function(a) {
		return a.stopPropagation(), !0
	}), $(document).on("click", '[data-action="batch"]', function() {
		$(".batch-modal").show()
	}), $(document).on("click", ".batch-modal", function() {
		$(this).hide()
	}), define("qun/create", function() {
		var a = $("#json_required").val(),
			b = void 0 == a ? [] : JSON.parse(a);
		$(document).on("click", '[data-action="custom"]', function() {
			var a = $(this).data("name"),
				c = b.indexOf(a); - 1 == c ? b.push(a) : b.splice(c, 1), $(this).toggleClass("active");
			var d = "";
			for (var e in b) d += '<input type="hidden" name="required[]" value="' + b[e] + '"/>';
			$("#required-field").html(d)
		})
	}), $(document).on("click", ".accordion-hd", function() {
		var a = $(this).parents(".accordion-group");
		$(this).find(".icon").removeClass("icon-arrow-down icon-arrow-right"), $(this).find(".list-group-item-fd .icon").addClass(a.hasClass("open") ? "icon-arrow-right" : "icon-arrow-down")
	}), define("industry/city", function() {
		var a, b;
		$(document).on("input", "#keywords", function() {
			b && clearTimeout(b), b = setTimeout(function() {
				a && a.abort();
				var b = $("#keywords").val().trim();
				if (b.trim().length > 0) {
					var c = $(this);
					c.text("鎼滅储涓�..."), a = $.post("/industry/search_city", {
						keywords: b
					}, null, "json"), a.done(function(a) {
						if (null == a) $('<div id="info" class="alert alert-warning">娌℃湁鎼滅储鍒扮浉鍏冲煄甯�</div>').insertBefore($("#keywords").parent()), setTimeout(function() {
							$("#info").remove()
						}, 2e3);
						else {
							var b = "";
							$("input[type='checkbox']").prop("checked", !1), $(".checked").removeClass("checked");
							for (var c in a) void 0 != a[c] && (b += '<a href="#" class="list-group-item listview-item radio" radio="city">\n	<div class="list-group-item-hd">\n		<input type="radio" name="city_id" value="' + a[c].citycode + '">\n\n		<div class="input-radio"></div>\n	</div>\n	<div class="list-group-item-bd">\n		<div class="content">\n			<span class="title">' + a[c].cityname + "</span>\n		</div>\n	</div>\n</a>");
							$("#result").html(b)
						}
					}), a.fail(function() {})
				}
			}, 500)
		}), $(document).on("click", "[data-redirect]", function() {
			var a = $(this).attr("href");
			a.length > 0 && (window.location.href = a)
		})
	}), define("industry/selectcity", function() {
		var a, b;
		$(document).on("input", "#keywords", function() {
			b && clearTimeout(b), b = setTimeout(function() {
				a && a.abort();
				var b = $("#keywords").val().trim(),
					c = $(this);
				c.text("鎼滅储涓�..."), a = $.post("/industry/search_city", {
					keywords: b
				}, null, "json"), a.done(function(a) {
					if (null == a) $('<div id="info" class="alert alert-warning">娌℃湁鎼滅储鍒扮浉鍏冲煄甯�</div>').insertBefore($("#keywords").parent()), setTimeout(function() {
						$("#info").remove()
					}, 2e3);
					else {
						var b = "";
						for (var c in a) void 0 != a[c] && (b += '<a data-redirect="" href="/industry/index/tag_id/' + window.tag_id + "/city_id/" + a[c].citycode + '" class="list-group-item listview-item radio" radio="city"><div class="list-group-item-bd">\n		<div class="content">\n			<span class="title">' + a[c].cityname + "</span>\n		</div>\n	</div>\n</a>");
						$("#result").html(b)
					}
				})
			}, 500)
		}), $(document).on("click", "[data-redirect]", function() {
			var a = $(this).attr("href");
			a.length > 0 && (window.location.href = a)
		})
	}), define("industry/index", function() {
		var a;
		$(document).on("input", "#keywords", function() {
			a && clearTimeout(a), a = setTimeout(function() {
				$("#keywords").parent().parent()[0].submit()
			}, 500)
		})
	}), define("forum/view", function() {
		$("[data-action='post']").click(function() {
			return $(".post-modal").show(), !1
		}), $(".post-modal").click(function(a) {
			a.stopPropagation(), $(this).hide()
		})
	}), define("common/loading", function() {
		"use strict";
		return {
			init: function() {
				var a = $(".xl-loading-modal");
				if (0 == a.length) {
					var b = $('<div class="xl-loading-modal"><div class="loading-container"><div class="ball"></div><div class="ball1"></div><div class="text">loading...</div></div></div>');
					$("body").append(b)
				}
			},
			open: function() {
				return this.init(), $(".xl-loading-modal").show(), this
			},
			close: function() {
				return this.init(), $(".xl-loading-modal").hide(), this
			},
			msg: function(a) {
				return this.init(), $(".xl-loading-modal>.loading-container>.text").text(a), this
			}
		}
	}), define("map/location", function(a) {
		"use strict";
		var b, c, d, e = a("common/loading"),
			f = JSON.parse($("#locationData").val()) || {},
			g = function() {
				if (navigator.geolocation) {
					e.msg("GPS瀹氫綅涓�...3绉�").open();
					var a = 3,
						d = setInterval(function() {
							a--, 0 >= a && (clearInterval(d), h()), e.msg("GPS瀹氫綅涓�..." + a + "绉�")
						}, 1e3);
					navigator.geolocation.getCurrentPosition(function(a) {
						d && clearInterval(d);
						var f = a.coords.longitude,
							g = a.coords.latitude;
						b && b.abort(), b = $.post("/map/locationbygps", {
							lng: f,
							lat: g
						}, function(a) {
							e.close(), a.error ? alert(a.error) : c = a, h()
						}, "json")
					})
				} else h()
			},
			h = function() {
				e.msg("IP瀹氫綅涓�...").open(), b && b.abort(), b = $.get("/map/locationbyip", {}, function(a) {
					e.close(), a.error ? alert(a.error) : (d = a, void 0 != c && d.city_code == c.city_code ? (f = c, f.type = 1) : (f = d, f.type = 2), i())
				}, "json")
			},
			i = function() {
				$("#location").val(f.address)
			},
			j = function() {
				$("#btn").on("click", function() {
					var a = $("#location").val();
					return 0 == a.trim().length ? void alert("璇疯緭鍏ュ湴鍧€") : void(a == f.address ? (b && b.abort(), e.msg("涓婁紶鍦板潃...").open(), b = $.post("/map/setlocation", f, function(a) {
						e.close(), a.error ? alert(a.error) : location.href = window._return.length > 0 ? window._return : "/map/gotomap"
					}, "json")) : (b && b.abort(), e.msg("瑙ｆ瀽鍦板潃...").open(), b = $.post("/map/address2point", {
						address: a
					}, function(a) {
						e.close(), a.error ? alert(a.error) : (a.type = 3, b && b.abort(), e.msg("涓婁紶鍦板潃...").open(), b = $.post("/map/setlocation", a, function(a) {
							e.close(), a.error ? alert(a.error) : location.href = "/map/gotomap"
						}, "json"))
					}, "json")))
				})
			},
			k = function() {
				$("body").css({
					background: "#f4f5f6"
				})
			};
		k(), j(), window.needlocation && g()
	}), define("form/check", function() {
		"use strict";
		return {
			checkIsPhone: function(a) {
				return /^(\+86)?1[3-8]\d{9}$/.test(a)
			}
		}
	}), define("map/phonebook", function(a) {
		"use strict";
		var b = a("form/check");
		$("body").css({
			background: "#f4f5f6"
		}), $(".form").on("submit", function() {
			return 0 == $("#realname").val().trim().length ? (alert("璇峰～鍐欏鍚�"), !1) : 0 == $("#phone").val().trim().length ? (alert("璇峰～鍐欐墜鏈哄彿"), !1) : b.checkIsPhone($("#phone").val().trim()) ? 0 == $("#department").val().trim().length ? (alert("璇峰～鍐欏叕鍙歌亴浣�"), !1) : !0 : (alert("鎵嬫満鍙风爜鏍煎紡閿欒!"), !1)
		})
	}), define("map/view", function(a) {
		"use strict";
		var b, c, d, e, f, g, h, i, j = a("common/loading"),
			k = [],
			l = [],
			m = [],
			n = function() {
				m = [];
				for (var a in g)
					if (void 0 != g[a].count && g[a].count > 0) {
						var b, c = "/Public/image/map/icon";
						switch (g[a].count) {
							case 1:
								b = c + "/icon_1.png";
								break;
							case 2:
								b = c + "/icon_2.png";
								break;
							case 3:
								b = c + "/icon_3.png";
								break;
							case 4:
								b = c + "/icon_4.png";
								break;
							case 5:
								b = c + "/icon_5.png";
								break;
							case 6:
								b = c + "/icon_6.png";
								break;
							case 7:
								b = c + "/icon_7.png";
								break;
							case 8:
								b = c + "/icon_8.png";
								break;
							case 9:
								b = c + "/icon_9.png";
								break;
							default:
								b = c + "/icon_9_plus.png"
						}
						var d = new BMap.Icon(b, new BMap.Size(18, 22.5), {
							anchor: new BMap.Size(18, 22.5)
						});
						m[a] = new BMap.Marker(new BMap.Point(g[a]._lng, g[a]._lat), {
							icon: d
						}), e.addOverlay(m[a])
					}
			},
			o = function() {
				for (var a in m) e.removeOverlay(m[a]);
				for (var a in l) e.removeOverlay(l[a])
			},
			p = function(a) {
				d && d.abort(), d = window.qun ? $.get("/map/nearmembersqun", {
					lng: a.lng,
					lat: a.lat,
					qun_id: window.qun.qun_id
				}, function(a) {
					void 0 != a.marker ? (o(), n()) : (o(), k = a, r(k))
				}, "json") : $.get("/map/nearmembersfriend", {
					lng: a.lng,
					lat: a.lat,
					star: window.star
				}, function(a) {
					void 0 != a.marker ? (o(), n()) : (o(), k = a, r(k))
				}, "json")
			},
			q = function(a, b) {
				var c = 180 / 3.14169,
					d = a.lat / c,
					e = a.lng / c,
					f = b.lat / c,
					g = b.lng / c,
					h = Math.cos(d) * Math.cos(e) * Math.cos(f) * Math.cos(g),
					i = Math.cos(d) * Math.sin(e) * Math.cos(f) * Math.sin(g),
					j = Math.sin(d) * Math.sin(f),
					k = Math.acos(h + i + j > 1 ? 1 : h + i + j);
				return 6366e3 * k
			},
			r = function(a) {
				for (var f in a) {
					var g = (a[f].openid, a[f].location),
						h = a[f].user,
						i = new BMap.Icon(h.avatar, new BMap.Size(48, 48), {
							anchor: new BMap.Size(24, 24)
						}),
						j = new BMap.Point(g.lng, g.lat);
					l[f] = new BMap.Marker(j, {
						icon: i
					}), e.addOverlay(l[f]), l[f].addEventListener("click", function(b) {
						return function() {
							if (!window.added) return alert("璇峰厛鍔犲叆鎴戜滑"), $(".btn-map-operation").trigger("click"), !1;
							var e = a[b];
							if ($(".user").find("#avatar").attr("src", e.user.avatar), $(".user").find("#realname").text(e.user.nickname), e.openid == c.openid ? ($(".user").find(".edit,#address").show(), $(".user").find("#position,#location,.opt").hide(), window.query || $(".user").find(".btn-success").hide()) : ($(".user").find(".btn-success").hide(), $(".user").find("#address,.edit").hide(), $(".user").find("#position,#location").show(), e.user.phone ? ($(".user").find("#location").find("span").text((q(c, e.location) / 1e3).toFixed(1) + "km"), $(".user").find("#position").show().find("span").text(e.user.department.substr(0, 17) + e.user.position.substr(0, 8)), $(".user").find(".call").attr("href", "tel:" + e.user.phone), $(".user").find(".sms").attr("href", "sms:" + e.user.phone), $(".user").find(".opt").show()) : ($(".user").find("#location").find("span").text((q(c, e.location) / 1e3).toFixed(1) + "km"), $(".user").find("#position,.opt").hide())), e.openid == c.openid) $("#exchange").hide(), $("#invite").hide(), $("#msg").hide(), $(".sms,.call").hide();
							else if ($(".sms,.call,.star").show(), $(".sms,.call,.star").off("click"), 1 == e.user.isfriend)
								if (d && d.abort(), d = $.get("/map/isstar", {
									openid: e.openid
								}, function(a) {
									if (a.error) alert(a.error);
									else {
										var b = $(".user").find(".star>img");
										1 == a.star ? b.attr("src", b.data("active")) : b.attr("src", b.data("normal")), $(".star").on("click", function() {
											d && d.abort(), d = $.get("/map/togglestar", {
												openid: e.openid
											}, function(a) {
												a.error ? alert(a.error) : 1 == a.star ? b.attr("src", b.data("active")) : b.attr("src", b.data("normal"))
											}, "json")
										})
									}
								}, "json"), q(c, e.location) < 1e4) $("#exchange").hide(), $("#invite").hide(), $("#msg").show();
								else {
									var f = q(c, e.location);
									f > 1e3 ? f = (f / 1e3).toFixed(1) + "km" : f += "m", $("#exchange").hide(), $("#invite_msg").val("鎴戣窡鎮ㄧ浉璺�" + f + "锛岀綉鑱婁笉濡傜浉瑙侊紝鎴戞兂鍜屾偍鑱氫竴鑱氥€�").show(), $("#msg").hide()
								} else $(".sms,.call,.star").on("click", function(a) {
								a.preventDefault(), $("[data-action='addFriend']").trigger("click")
							}), $("#exchange").hide(), $("#invite").hide(), $("#msg").hide();
							$(".user").find("#invite").off("click"), $(".user").find("#invite").on("click", function() {
								$(".invite").find("button").attr("data-openid", e.openid), $(".invite").show()
							}), $(".user").find("#exchange").attr("openid", e.openid), $(".user").find("#msg").attr("href", "/chat?openid=" + e.openid), $(".user").show()
						}
					}(f))
				}!b && setTimeout(function() {
					b = !0, $("#map").find("img").each(function() {
						var a = $(this).attr("src"); - 1 == a.indexOf("map") && $(this).addClass("xl-map-avatar")
					})
				}, 1), b && $("#map").find("img").each(function() {
					var a = $(this).attr("src"); - 1 == a.indexOf("map") && $(this).addClass("xl-map-avatar")
				})
			},
			s = function() {
				var a = $(".nav").height(),
					b = $(window).height(),
					h = b - a;
				$("body").css({
					height: b + "px"
				}), $("#map").css({
					height: h + "px",
					position: "relative"
				}), c = JSON.parse($("#adminLocation").val());
				var i = JSON.parse($("#userInfo").val());
				$(".user").find("#avatar").attr("src", i.avatar), $(".user").find("#realname").text(i.nickname), $(".user").find("#position,#location,.opt").hide(), $(".user").find("#address").text(c.address), f = JSON.parse($("#target").val()), g = window.boxes;
				var k = new BMap.Point(f.lng, f.lat);
				e = new BMap.Map("map"), e.addControl(new BMap.ZoomControl), u(), window.all ? (k = new BMap.Point(105.323347, 34.568941), e.centerAndZoom(k, 4), n()) : e.centerAndZoom(k, 15);
				var l;
				e.addEventListener("zoomend", function(a) {
					l = a.target.zoomLevel, 6 >= l ? (o(), n()) : p(e.getCenter())
				}, !1), e.addEventListener("moveend", function() {
					$(".user").hide(), e.getZoom() > 6 ? p(e.getCenter()) : (o(), n())
				}, !1), $('[data-action="close"]').on("click", function() {
					$($(this).data("target")).hide()
				}), $("#save").on("click", function() {
					$(".save-modal").show()
				}), $(".save-modal").on("click", function() {
					$(this).hide()
				}), $(".arrow-down").on("click", function() {
					$(".user").hide()
				}), $(".invite").find("button").on("click", function() {
					var a = $(this).data("openid"),
						b = $("#invite_msg").val();
					"" == b ? alert("璇峰～鍐欒仛浼氶個璇疯緸") : (j.msg("閭€璇蜂腑...").open(), d && d.abort(), d = $.post("/map/invite", {
						openid: a,
						msg: b
					}, function(a) {
						j.close(), alert(a.error ? a.error : a.msg), $(".invite").hide()
					}, "json"))
				}), $(".icon-close").on("click", function() {
					$(".invite").hide()
				}), $(".btn-earth").on("click", function() {
					var a = new BMap.Point(105.323347, 34.568941);
					e.centerAndZoom(a, 4)
				})
			},
			t = JSON.parse($("#adminLocation").val()),
			u = function(a) {
				var b = function() {
						if (navigator.geolocation) {
							j.msg("GPS瀹氫綅涓�...3绉�").open();
							var a = 3,
								b = setInterval(function() {
									a--, 0 >= a && (clearInterval(b), f()), j.msg("GPS瀹氫綅涓�..." + a + "绉�")
								}, 1e3);
							navigator.geolocation.getCurrentPosition(function(a) {
								b && clearInterval(b);
								var c = a.coords.longitude,
									e = a.coords.latitude;
								d && d.abort(), d = $.post("/map/locationbygps", {
									lng: c,
									lat: e
								}, function(a) {
									j.close(), a.error ? alert(a.error) : h = a, f()
								}, "json")
							})
						} else f()
					},
					f = function() {
						j.msg("IP瀹氫綅涓�...").open(), d && d.abort(), d = $.get("/map/locationbyip", {}, function(a) {
							j.close(), a.error ? alert(a.error) : (i = a, void 0 != h && i.city_code == h.city_code ? (t = h, t.type = 1) : (t = i, t.type = 2), g())
						}, "json")
					},
					g = function() {
						var a = new BMap.Point(t.lng, t.lat);
						e.centerAndZoom(a, 15), p(e.getCenter()), window.query ? ($(".guide").show(), $(".user").find(".btn-success").show()) : ($(".user").find(".btn-success").hide(), $(".guide").hide());
						for (var b in k) k[b].openid == c.openid && (l[b].setPosition(a), k[b].location.address = t.address, k[b].location.lng = t.lng, k[b].location.lat = t.lat, c.address = t.address, c.lng = t.lng, c.lat = t.lat)
					};
				(window.needLocation || a) && b(), !window.needLocation && g()
			};
		$("[data-action='location']").on("click", function() {
			u(!0)
		}), s()
	}), define("map/district", function() {
		var a = function() {
			$("body").css({
				"min-height": "100%",
				background: "#b0e3fd"
			})
		};
		a()
	}), define("async/script", function() {
		return function(a, b) {
			var c = document.getElementsByTagName("head")[0],
				d = document.createElement("script");
			d.setAttribute("type", "text/javascript"), d.setAttribute("src", a), d.setAttribute("async", !0), d.setAttribute("defer", !0), c.appendChild(d), document.all ? d.onreadystatechange = function() {
				var a = this.readyState;
				("loaded" === a || "complete" === a) && b && b()
			} : d.onload = function() {
				b && b()
			}
		}
	}), define("utils", function(a, b) {
		b.inherits = function(a, b) {
			a._super = b, a.prototype = Object.create(b.prototype, {
				constructor: {
					value: a,
					enumerable: !1,
					writable: !0,
					configurable: !0
				}
			}), a.prototype.superclass = b.prototype
		}
	}), define("upload", function(a) {
		var b = a("upload-wechat"),
			c = a("upload-common");
		return function(a) {
			a || (a = {});
			var d = a.wechat || {};
			a.wechat = null, delete a.wechat;
			var e;
			return !window.isWechat || a.accept && "image/*" != a.accept ? e = new c(a) : ($.extend(a, d), e = new b(a)), e.init(), e
		}
	}), define("upload-wechat", function(a) {
		function b() {
			c.apply(this, arguments)
		}
		var c = a("upload-base");
		a("utils").inherits(b, c);
		var d = b.prototype;
		return d.change = function() {
			var a = this,
				b = $.Deferred();
			Weixin.api("chooseImage", function(c) {
				c.localIds && c.localIds[0] ? (a._preview(c.localIds[0]), setTimeout(function() {
					b.resolve(c.localIds[0])
				}, 1)) : setTimeout(function() {
					b.reject()
				}, 1)
			}), this.upload(b.promise())
		}, d.upload = function(a) {
			var b = this,
				c = a.then(function(a) {
					var b = $.Deferred();
					return Weixin.api("uploadImage", {
						localId: a,
						isShowProgressTips: 1,
						success: function(a) {
							a && a.serverId ? setTimeout(function() {
								b.resolve(a.serverId)
							}, 0) : setTimeout(function() {
								b.reject()
							}, 0)
						}
					}, function() {
						b.reject()
					}), b.promise()
				});
			c = c.then(function(a) {
				return $.post("/weixin/download", {
					id: a,
					bucket: b.opts.bucket,
					token: b.opts.token
				}, null, "json")
			}), c.done(function(a) {
				a && a.status ? b._success(a.info) : b._error(new Error("鍥剧墖淇濆瓨澶辫触"))
			}), c.fail(function() {
				b._error(new Error("鍥剧墖淇濆瓨澶辫触"))
			})
		}, d.isSupprt = function() {
			return !!window.wx
		}, b
	}), define("upload-common", function(a) {
		function b() {
			c.apply(this, arguments), this.createUploader()
		}
		var c = a("upload-base");
		a("utils").inherits(b, c);
		var d = b.prototype;
		return d.change = function() {
			this.uploader.getTrigger().click()
		}, d.upload = function() {
			this.superclass.upload.apply(this, arguments), this.uploader.submit()
		}, d.isSupprt = function() {
			return Uploader.isSupport()
		}, d.createUploader = function() {
			if (this.uploader) return this;
			var a = this,
				b = a.opts.accept || "image/*";
			this.uploader = new Uploader({
				name: "file",
				trigger: $('<input accept="' + b + '" type="file" />'),
				action: "http://up.qiniu.com/",
				data: {
					token: this.opts.token
				},
				change: function(b) {
					a.emit("change", b[0]), a.upload()
				},
				preprocess: function(b, c) {
					Uploader.makeThumb(b[0], {
						width: 640,
						height: 640,
						success: function(b, d) {
							a._preview(d), a._notify(0), c([b])
						},
						error: function() {
							c(b)
						}
					})
				},
				success: function(b) {
					b = "http://" + a.opts.bucket + ".qiniudn.com/" + b.key, a._success(b)
				},
				error: function() {
					a._error(new Error("涓婁紶鏂囦欢澶辫触"))
				},
				progress: function(b, c) {
					a._notify(c)
				}
			})
		}, b
	}), define("upload-base", function(a) {
		function b(a) {
			c.call(this), this.debug = !!a.debug, this.$form = a.form ? $(a.form) : null, this.$trigger = $(a.trigger), this.opts = a, this.state = 1
		}
		var c = a("upload-event");
		a("utils").inherits(b, c);
		var d = b.prototype;
		return d.init = function() {
			var a = this.opts.name;
			if (this.$form ? (this.$input = this.$form.find("[name=" + a + "]"), 0 == this.$input.size() && (this.$input = $('<input type="hidden" name="' + a + '" />'), this.$form.append(this.$input))) : this.$input = $('<input type="hidden" name="' + a + '" />'), this.$trigger.on("click", function(a) {
				a.preventDefault()
			}), this.$trigger.on("click", $.proxy(function() {
				1 == this.state && this.change()
			}, this)), ["success", "error", "progress", "change", "enable", "disable", "abort", "preview"].forEach(function(a) {
				this.opts[a] && this.on(a, this.opts[a])
			}, this), this.opts.complete) {
				var b = this;
				this.on("success error", function() {
					b.opts.complete.call(b)
				})
			}
		}, d.abort = function() {
			this.emit("abort"), this.enable()
		}, d.enable = function() {
			this.state = 1, this.emit("enable")
		}, d.disable = function() {
			this.state = 0, this.emit("disable")
		}, d.change = function() {
			this.emit("change")
		}, d._success = function(a) {
			$.isFunction(this.opts.process) && (a = this.opts.process(a) || a), this.$input.val(a), this.opts.autoSubmit ? (this.emit("success", a), this.$form && this.$form.submit()) : (this.enable(), this.emit("success", a))
		}, d._error = function(a) {
			this.enable(), this.emit("error", a)
		}, d._preview = function(a) {
			this.emit("preview", a)
		}, d._notify = function(a) {
			this.emit("progress", a)
		}, d.upload = function() {
			this.disable()
		}, d.isSupprt = function() {
			return !0
		}, d.destroy = function() {
			this.off()
		}, d.log = function(a) {
			this.debug && ("object" == typeof a && null != a && window.JSON && (a = JSON.stringify(a)), window.console && console.debug("[Upload]:" + a))
		}, b
	}), define("upload-event", function() {
		function a() {}

		function b(a, b, c) {
			for (var d = a.length; d--;)
				if (a[d].listener === b && (null == c || a[d].context === c)) return d;
			return -1
		}

		function c(a) {
			var b = a.toString().split(" ");
			return b = b.filter(function(a) {
				return "" != a && null != a
			})
		}
		var d = a.prototype;
		return d.on = function(a, b, d) {
			if ("function" == typeof b) {
				var e = c(a);
				e.forEach(function(a) {
					this._bindEvent(a, b, d)
				}, this)
			}
			return this
		}, d.once = function(a, b, c) {
			return this.on(a, {
				listener: b,
				once: !0,
				context: c || this
			})
		}, d.off = function(a, b, d) {
			if ("string" == typeof a) {
				var e = c(a);
				e.forEach(function(a) {
					this._removeEvent(a, b, d)
				}, this)
			} else this._removeEvent(a, b, d);
			return this
		}, d._bindEvent = function(a, c, d) {
			var e, f = this.getListenersAsObject(a.toString()),
				g = "object" == typeof c;
			for (e in f) f.hasOwnProperty(e) && -1 === b(f[e], c, d) && f[e].push(g ? c : {
				listener: c,
				once: !1,
				context: d || this
			});
			return this
		}, d._removeEvent = function(a, b, c) {
			var d, e, f = this._getEvents();
			if (0 == arguments.length && delete this._events, "string" == typeof a)
				if (b || null != typeof c) {
					e = this.getListeners(a);
					for (var g = e.length; g--;) {
						var h = !(b && e[g].listener !== b || null != c && e[g].context !== c);
						h && e.splice(g, 1)
					}
				} else delete f[a];
			else if (a instanceof RegExp)
				for (d in f) f.hasOwnProperty(d) && a.test(d) && this.off(d, b, c);
			else
				for (d in f) f.hasOwnProperty(d) && this.off(d, b, c);
			return this
		}, d.emit = function(a) {
			var b, c, d, e = Array.prototype.slice.call(arguments, 1),
				f = this.getListenersAsObject(a);
			for (d in f)
				if (f.hasOwnProperty(d))
					for (c = f[d].length; c--;) b = f[d][c], b.once === !0 && f[d].splice(c, 1), b.listener.apply(b.context, e || []);
			return this
		}, d.getListenersAsObject = function(a) {
			var b, c = this.getListeners(a);
			return c instanceof Array && (b = {}, b[a] = c), b || c
		}, d.getListeners = function(a) {
			var b, c, d = this._getEvents();
			if (a instanceof RegExp) {
				b = {};
				for (c in d) d.hasOwnProperty(c) && a.test(c) && (b[c] = d[c])
			} else b = d[a] || (d[a] = []);
			return b
		}, d._getEvents = function() {
			return this._events || (this._events = {})
		}, a
	});