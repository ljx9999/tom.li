! function(a, b, c) {
	function d(a) {
		return "[object Function]" == q.call(a)
	}

	function e(a) {
		return "string" == typeof a
	}

	function f() {}

	function g(a) {
		return !a || "loaded" == a || "complete" == a || "uninitialized" == a
	}

	function h() {
		var a = r.shift();
		s = 1, a ? a.t ? o(function() {
			("c" == a.t ? m.injectCss : m.injectJs)(a.s, 0, a.a, a.x, a.e, 1)
		}, 0) : (a(), h()) : s = 0
	}

	function i(a, c, d, e, f, i, j) {
		function k(b) {
			if (!n && g(l.readyState) && (t.r = n = 1, !s && h(), l.onload = l.onreadystatechange = null, b)) {
				"img" != a && o(function() {
					v.removeChild(l)
				}, 50);
				for (var d in A[c]) A[c].hasOwnProperty(d) && A[c][d].onload()
			}
		}
		var j = j || m.errorTimeout,
			l = b.createElement(a),
			n = 0,
			q = 0,
			t = {
				t: d,
				s: c,
				e: f,
				a: i,
				x: j
			};
		1 === A[c] && (q = 1, A[c] = []), "object" == a ? l.data = c : (l.src = c, l.type = a), l.width = l.height = "0", l.onerror = l.onload = l.onreadystatechange = function() {
			k.call(this, q)
		}, r.splice(e, 0, t), "img" != a && (q || 2 === A[c] ? (v.insertBefore(l, u ? null : p), o(k, j)) : A[c].push(l))
	}

	function j(a, b, c, d, f) {
		return s = 0, b = b || "j", e(a) ? i("c" == b ? x : w, a, b, this.i++, c, d, f) : (r.splice(this.i++, 0, a), 1 == r.length && h()), this
	}

	function k() {
		var a = m;
		return a.loader = {
			load: j,
			i: 0
		}, a
	}
	var l, m, n = b.documentElement,
		o = a.setTimeout,
		p = b.getElementsByTagName("script")[0],
		q = {}.toString,
		r = [],
		s = 0,
		t = "MozAppearance" in n.style,
		u = t && !!b.createRange().compareNode,
		v = u ? n : p.parentNode,
		n = a.opera && "[object Opera]" == q.call(a.opera),
		n = !!b.attachEvent && !n,
		w = t ? "object" : n ? "script" : "img",
		x = n ? "script" : w,
		y = Array.isArray || function(a) {
			return "[object Array]" == q.call(a)
		},
		z = [],
		A = {},
		B = {
			timeout: function(a, b) {
				return b.length && (a.timeout = b[0]), a
			}
		};
	m = function(a) {
		function b(a) {
			var b, c, d, a = a.split("!"),
				e = z.length,
				f = a.pop(),
				g = a.length,
				f = {
					url: f,
					origUrl: f,
					prefixes: a
				};
			for (c = 0; g > c; c++) d = a[c].split("="), (b = B[d.shift()]) && (f = b(f, d));
			for (c = 0; e > c; c++) f = z[c](f);
			return f
		}

		function g(a, e, f, g, h) {
			var i = b(a),
				j = i.autoCallback;
			i.url.split(".").pop().split("?").shift(), i.bypass || (e && (e = d(e) ? e : e[a] || e[g] || e[a.split("/").pop().split("?")[0]]), i.instead ? i.instead(a, e, f, g, h) : (A[i.url] ? i.noexec = !0 : A[i.url] = 1, f.load(i.url, i.forceCSS || !i.forceJS && "css" == i.url.split(".").pop().split("?").shift() ? "c" : c, i.noexec, i.attrs, i.timeout), (d(e) || d(j)) && f.load(function() {
				k(), e && e(i.origUrl, h, g), j && j(i.origUrl, h, g), A[i.url] = 2
			})))
		}

		function h(a, b) {
			function c(a, c) {
				if (a) {
					if (e(a)) c || (l = function() {
						var a = [].slice.call(arguments);
						m.apply(this, a), n()
					}), g(a, l, b, 0, j);
					else if (Object(a) === a)
						for (i in h = function() {
							var b, c = 0;
							for (b in a) a.hasOwnProperty(b) && c++;
							return c
						}(), a) a.hasOwnProperty(i) && (!c && !--h && (d(l) ? l = function() {
							var a = [].slice.call(arguments);
							m.apply(this, a), n()
						} : l[i] = function(a) {
							return function() {
								var b = [].slice.call(arguments);
								a && a.apply(this, b), n()
							}
						}(m[i])), g(a[i], l, b, i, j))
				} else !c && n()
			}
			var h, i, j = !!a.test,
				k = a.load || a.both,
				l = a.callback || f,
				m = l,
				n = a.complete || f;
			c(j ? a.yep : a.nope, !!k), k && c(k)
		}
		var i, j, l = this.yepnope.loader;
		if (e(a)) g(a, 0, l, 0);
		else if (y(a))
			for (i = 0; i < a.length; i++) j = a[i], e(j) ? g(j, 0, l, 0) : y(j) ? m(j) : Object(j) === j && h(j, l);
		else Object(a) === a && h(a, l)
	}, m.addPrefix = function(a, b) {
		B[a] = b
	}, m.addFilter = function(a) {
		z.push(a)
	}, m.errorTimeout = 1e4, null == b.readyState && b.addEventListener && (b.readyState = "loading", b.addEventListener("DOMContentLoaded", l = function() {
		b.removeEventListener("DOMContentLoaded", l, 0), b.readyState = "complete"
	}, 0)), a.yepnope = k(), a.yepnope.executeStack = h, a.yepnope.injectJs = function(a, c, d, e, i, j) {
		var k, l, n = b.createElement("script"),
			e = e || m.errorTimeout;
		n.src = a;
		for (l in d) n.setAttribute(l, d[l]);
		c = j ? h : c || f, n.onreadystatechange = n.onload = function() {
			!k && g(n.readyState) && (k = 1, c(), n.onload = n.onreadystatechange = null)
		}, o(function() {
			k || (k = 1, c(1))
		}, e), i ? n.onload() : p.parentNode.insertBefore(n, p)
	}, a.yepnope.injectCss = function(a, c, d, e, g, i) {
		var j, e = b.createElement("link"),
			c = i ? h : c || f;
		e.href = a, e.rel = "stylesheet", e.type = "text/css";
		for (j in d) e.setAttribute(j, d[j]);
		g || (p.parentNode.insertBefore(e, p), o(c, 0))
	}
}(this, document);
var modjs = function(a) {
	"use strict";
	var b = {},
		c = function(a, c) {
			b[a] = "function" == typeof c ? c : {
				exports: c
			}
		},
		d = function(a, c) {
			setTimeout(function() {
				if (b.hasOwnProperty(a)) {
					var d = e(a);
					c && c(d)
				}
			}, 0)
		},
		e = function(a) {
			if (b.hasOwnProperty(a)) {
				if ("function" == typeof b[a]) {
					var c = {};
					c.exports = {};
					var d = b[a](e, c.exports, c);
					void 0 !== d && (c.exports = d), b[a] = c
				}
				return b[a].exports
			}
			throw new Error("module[" + a + "] not defined")
		};
	return a.define = c, {
		define: c,
		use: d,
		require: e
	}
}(window);
! function(a) {
	"use strict";
	var b = {
		version: 4.3
	};
	a.WeixinApi = b, "function" == typeof define && (define.amd || define.cmd) && (define.amd ? define(function() {
		return b
	}) : define.cmd && define(function(a, c, d) {
		d.exports = b
	}));
	var c = function() {
			for (var a, b, c = {}, d = 0, e = arguments.length; e > d; d++)
				if (a = arguments[d], "object" == typeof a)
					for (b in a) a[b] && (c[b] = a[b]);
			return c
		},
		d = function(a, d, e) {
			e = e || {};
			var f = function(a) {
					switch (!0) {
						case /\:cancel$/i.test(a.err_msg):
							e.cancel && e.cancel(a);
							break;
						case /\:(confirm|ok)$/i.test(a.err_msg):
							e.confirm && e.confirm(a);
							break;
						case /\:fail$/i.test(a.err_msg):
						default:
							e.fail && e.fail(a)
					}
					e.all && e.all(a)
				},
				g = function(b, c) {
					if ("menu:share:timeline" == a.menu || "general:share" == a.menu && "timeline" == c.shareTo) {
						var d = b.title;
						b.title = b.desc || d, b.desc = d || b.desc
					}!c || "favorite" != c.shareTo && "favorite" != c.scene ? "general:share" === a.menu ? "timeline" === c.shareTo ? WeixinJSBridge.invoke("shareTimeline", b, f) : "friend" === c.shareTo ? WeixinJSBridge.invoke("sendAppMessage", b, f) : "QQ" === c.shareTo ? WeixinJSBridge.invoke("shareQQ", b, f) : "weibo" === c.shareTo && WeixinJSBridge.invoke("shareWeibo", b, f) : WeixinJSBridge.invoke(a.action, b, f) : e.favorite === !1 ? WeixinJSBridge.invoke("sendAppMessage", b, new Function) : WeixinJSBridge.invoke(a.action, b, f)
				};
			WeixinJSBridge.on(a.menu, function(a) {
				if (e.dataLoaded = e.dataLoaded || new Function, e.async && e.ready) b._wx_loadedCb_ = e.dataLoaded, b._wx_loadedCb_.toString().indexOf("_wx_loadedCb_") > 0 && (b._wx_loadedCb_ = new Function), e.dataLoaded = function(f) {
					e.__cbkCalled = !0;
					var h = c(d, f);
					h.img_url = h.imgUrl || h.img_url, delete h.imgUrl, b._wx_loadedCb_(h), g(h, a)
				}, (!a || "favorite" != a.shareTo && "favorite" != a.scene || e.favorite !== !1) && (e.ready && e.ready(a, d), e.__cbkCalled || (e.dataLoaded({}), e.__cbkCalled = !1));
				else {
					var f = c(d);
					(!a || "favorite" != a.shareTo && "favorite" != a.scene || e.favorite !== !1) && e.ready && e.ready(a, f), g(f, a)
				}
			})
		};
	b.shareToTimeline = function(a, b) {
		d({
			menu: "menu:share:timeline",
			action: "shareTimeline"
		}, {
			appid: a.appId ? a.appId : "",
			img_url: a.imgUrl,
			link: a.link,
			desc: a.desc,
			title: a.title,
			img_width: "640",
			img_height: "640"
		}, b)
	}, b.shareToFriend = function(a, b) {
		d({
			menu: "menu:share:appmessage",
			action: "sendAppMessage"
		}, {
			appid: a.appId ? a.appId : "",
			img_url: a.imgUrl,
			link: a.link,
			desc: a.desc,
			title: a.title,
			img_width: "640",
			img_height: "640"
		}, b)
	}, b.shareToWeibo = function(a, b) {
		d({
			menu: "menu:share:weibo",
			action: "shareWeibo"
		}, {
			content: a.desc,
			url: a.link
		}, b)
	}, b.generalShare = function(a, b) {
		d({
			menu: "general:share"
		}, {
			appid: a.appId ? a.appId : "",
			img_url: a.imgUrl,
			link: a.link,
			desc: a.desc,
			title: a.title,
			img_width: "640",
			img_height: "640"
		}, b)
	}, b.disabledShare = function(a) {
		a = a || function() {
			alert("褰撳墠椤甸潰绂佹鍒嗕韩锛�")
		}, ["menu:share:timeline", "menu:share:appmessage", "menu:share:qq", "menu:share:weibo", "general:share"].forEach(function(b) {
			WeixinJSBridge.on(b, function() {
				return a(), !1
			})
		})
	}, b.imagePreview = function(a, b) {
		a && b && 0 != b.length && WeixinJSBridge.invoke("imagePreview", {
			current: a,
			urls: b
		})
	}, b.showOptionMenu = function() {
		WeixinJSBridge.call("showOptionMenu")
	}, b.hideOptionMenu = function() {
		WeixinJSBridge.call("hideOptionMenu")
	}, b.showToolbar = function() {
		WeixinJSBridge.call("showToolbar")
	}, b.hideToolbar = function() {
		WeixinJSBridge.call("hideToolbar")
	}, b.getNetworkType = function(a) {
		a && "function" == typeof a && WeixinJSBridge.invoke("getNetworkType", {}, function(b) {
			a(b.err_msg)
		})
	}, b.closeWindow = function(a) {
		a = a || {}, WeixinJSBridge.invoke("closeWindow", {}, function(b) {
			switch (b.err_msg) {
				case "close_window:ok":
					a.success && a.success(b);
					break;
				default:
					a.fail && a.fail(b)
			}
		})
	}, b.ready = function(b) {
		var c = function() {
			var a = {};
			Object.keys(WeixinJSBridge).forEach(function(b) {
				a[b] = WeixinJSBridge[b]
			}), Object.keys(WeixinJSBridge).forEach(function(b) {
				"function" == typeof WeixinJSBridge[b] && (WeixinJSBridge[b] = function() {
					try {
						var c = arguments.length > 0 ? arguments[0] : {},
							d = c.__params ? c.__params.__runOn3rd_apis || [] : [];
						["menu:share:timeline", "menu:share:appmessage", "menu:share:weibo", "menu:share:qq", "general:share"].forEach(function(a) {
							-1 === d.indexOf(a) && d.push(a)
						})
					} catch (e) {}
					return a[b].apply(WeixinJSBridge, arguments)
				})
			})
		};
		if (b && "function" == typeof b) {
			var d = this,
				e = function() {
					c(), b(d)
				};
			"undefined" == typeof a.WeixinJSBridge ? document.addEventListener ? document.addEventListener("WeixinJSBridgeReady", e, !1) : document.attachEvent && (document.attachEvent("WeixinJSBridgeReady", e), document.attachEvent("onWeixinJSBridgeReady", e)) : e()
		}
	}, b.openInWeixin = function() {
		return /MicroMessenger/i.test(navigator.userAgent)
	}, b.sendEmail = function(a, b) {
		b = b || {}, WeixinJSBridge.invoke("sendEmail", {
			title: a.subject,
			content: a.body
		}, function(a) {
			"send_email:sent" === a.err_msg ? b.success && b.success(a) : b.fail && b.fail(a), b.all && b.all(a)
		})
	}, b.enableDebugMode = function(b) {
		a.onerror = function(a, c, d, e) {
			if ("function" == typeof b) b({
				message: a,
				script: c,
				line: d,
				column: e
			});
			else {
				var f = [];
				f.push("棰濓紝浠ｇ爜鏈夐敊銆傘€傘€�"), f.push("\n閿欒淇℃伅锛�", a), f.push("\n鍑洪敊鏂囦欢锛�", c), f.push("\n鍑洪敊浣嶇疆锛�", d + "琛岋紝" + e + "鍒�"), alert(f.join(""))
			}
		}
	}, b.share = function(a, c) {
		b.ready(function(b) {
			b.shareToFriend(a, c), b.shareToTimeline(a, c), b.shareToWeibo(a, c), b.generalShare(a, c)
		})
	}
}(window),
function(a) {
	"use strict";

	function b() {
		g(p, o), p.desc = "", p.title = f(o.title) || f(o.desc) ? f(o.title) ? f(o.title) ? "" : o.desc : o.title : o.title + " - " + o.desc
	}

	function c(a) {
		return "function" == typeof a
	}

	function d(a) {
		c(a.success) && (m.normal = a.success), c(a.cancel) && (n.normal = a.cancel), c(a.callback) && (m.complete = a.callback, n.complete = a.callback), c(a.complete) && (m.complete = a.complete, n.complete = a.complete)
	}

	function e(a) {
		return function() {
			Object.keys(a).forEach(function(b) {
				a[b] && a[b]()
			})
		}
	}

	function f(a) {
		return "" == a || null == a
	}

	function g(a, b) {
		b || (b = {}), b.hasOwnProperty("imgUrl") || (b.hasOwnProperty("MsgImg") ? b.imgUrl = b.MsgImg : b.hasOwnProperty("TLImg") && (b.imgUrl = b.TLImg)), l._fieldList.forEach(function(c) {
			b.hasOwnProperty(c) && (a[c] = "link" == c || "imgUrl" == c || "dataUrl" == c ? i(b[c]) : b[c])
		})
	}

	function h(a) {
		var b = {};
		return Object.keys(a || {}).forEach(function(c) {
			b[c] = a[c]
		}), b
	}

	function i(a) {
		try {
			return null == a ? location.href : (a = a.toString(), /http[s]?:\/\//.test(a) ? a : "/" == a[0] ? location.protocol + "//" + location.host + a : location.protocol + "//" + location.host + "/" + a)
		} catch (b) {
			return ""
		}
	}
	var j = a.wx,
		k = a.WeixinApi,
		l = {},
		m = {},
		n = {},
		o = {},
		p = {},
		q = {
			shareImg: "http://appmcdn.m0.hk/share.png",
			browerImg: "http://appmcdn.m0.hk/share-brower.png",
			favoriteImg: "http://appmcdn.m0.hk/share-favorite.png"
		};
	l.settings = q, l.shareConfig = o, l._fieldList = ["title", "desc", "link", "imgUrl", "type", "dataUrl"], l.init = function(a, b) {
		this._inited || (this._inited = !0, this.config(b), o.success = p.success = e(m), o.cancel = p.cancel = e(n), j ? (j.config(a || {}), j.ready(function() {
			j.onMenuShareAppMessage(o), j.onMenuShareTimeline(p), j.onMenuShareQQ(o), j.onMenuShareWeibo(o)
		})) : k && k.ready(function() {
			var a = {
				favorite: !1,
				async: !0,
				ready: function() {
					this.dataLoaded(o)
				},
				cancel: function() {
					o.cancel()
				},
				confirm: function() {
					o.success()
				}
			};
			k.share(o, a)
		}))
	}, l.ready = function(a) {
		a = a && a.bind(this), j ? j.ready(a) : k && k.ready(a)
	}, l.error = function(a) {
		j && j.error(a.bind(this))
	}, l.api = function(a, b, c) {
		"function" == typeof b && (b = {
			success: b
		}), j ? j.checkJsApi({
			jsApiList: [a],
			success: function(d) {
				d = d.checkResult, d[a] ? j.ready(function() {
					j[a].call(j, b)
				}) : c && c(new Error("褰撳墠鐜涓嶆敮鎸佽鎺ュ彛"))
			}
		}) : c && c(new Error("褰撳墠鐜涓嶆敮鎸佽鎺ュ彛"))
	}, l.config = function(c, e) {
		if (1 == arguments.length && "string" == typeof c) return o[c];
		var f = {};
		"object" != typeof c ? f[c] = e : f = c, g(o, f), o.MsgImg = o.TlImg = o.imgUrl, b(), d(f || {});
		var i = a.shareData = h(o);
		i.success = i.cancel = null, delete i.success, delete i.cancel
	}, l.success = function(a) {
		m.normal = a
	}, l.cancel = function(a) {
		n.normal = a
	}, l.complete = function(a) {
		m.complete = a, n.complete = a
	}, l._fieldList.forEach(function(a) {
		l[a] = function(b) {
			return 0 == arguments.length ? this.config(a) : void this.config(a, b)
		}
	}), l.isPopup = function(a) {
		return a || (a = "popup"), a = "wx-" + a, !!document.getElementById(a)
	}, l.closePopup = function(a) {
		a || (a = "popup"), a = "wx-" + a;
		var b = document.getElementById(a);
		b && b.click()
	}, l.popup = function(a, b, c) {
		if (a || (a = "popup"), !this.isPopup(a)) {
			a = "wx-" + a;
			var d = document.createElement("div");
			d.id = a;
			var e = {
				position: "fixed",
				zIndex: 999999,
				left: 0,
				top: 0,
				width: "100%",
				height: "100%",
				textAlign: "center",
				backgroundColor: "rgba(0, 0, 0, 0.7)"
			};
			for (var f in e) e.hasOwnProperty(f) && (d.style[f] = e[f]);
			var g = document.createElement("img");
			g.src = b, e = {
				maxWidth: "608px",
				width: "95%",
				paddingTop: "5px",
				verticalAlign: "bottom"
			};
			for (f in e) e.hasOwnProperty(f) && (g.style[f] = e[f]);
			d.appendChild(g), document.body.appendChild(d);
			var h, i = function() {
				h && clearTimeout(h), document.body.removeChild(d), d = null, c && c()
			};
			return d.addEventListener("click", function() {
				h && clearTimeout(h), h = setTimeout(i, 500)
			}, !1), d
		}
	}, l.openbybrowser = function(a) {
		a === !1 ? this.closePopup("openByBrowser") : this.popup("openByBrowser", q.browerImg)
	}, l.share = function(a) {
		if (!this.isPopup("share")) {
			var b = l.popup("share", q.shareImg, function() {
				m.close = null, delete m.close, a && a()
			});
			m.close = function() {
				b.click()
			}
		}
	}, l.favorite = function() {
		l.popup("favorite", q.favoriteImg)
	}, a.Weixin = l, "function" == typeof define ? define("weixin", l) : "undefined" != typeof module && module.exports && (module.exports = l)
}(window);