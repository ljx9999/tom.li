var Zepto = function() {
	function a(a) {
		return null == a ? String(a) : W[X.call(a)] || "object"
	}

	function b(b) {
		return "function" == a(b)
	}

	function c(a) {
		return null != a && a == a.window
	}

	function d(a) {
		return null != a && a.nodeType == a.DOCUMENT_NODE
	}

	function e(b) {
		return "object" == a(b)
	}

	function f(a) {
		return e(a) && !c(a) && Object.getPrototypeOf(a) == Object.prototype
	}

	function g(a) {
		return "number" == typeof a.length
	}

	function h(a) {
		return E.call(a, function(a) {
			return null != a
		})
	}

	function i(a) {
		return a.length > 0 ? y.fn.concat.apply([], a) : a
	}

	function j(a) {
		return a.replace(/::/g, "/").replace(/([A-Z]+)([A-Z][a-z])/g, "$1_$2").replace(/([a-z\d])([A-Z])/g, "$1_$2").replace(/_/g, "-").toLowerCase()
	}

	function k(a) {
		return a in I ? I[a] : I[a] = new RegExp("(^|\\s)" + a + "(\\s|$)")
	}

	function l(a, b) {
		return "number" != typeof b || J[j(a)] ? b : b + "px"
	}

	function m(a) {
		var b, c;
		return H[a] || (b = G.createElement(a), G.body.appendChild(b), c = getComputedStyle(b, "").getPropertyValue("display"), b.parentNode.removeChild(b), "none" == c && (c = "block"), H[a] = c), H[a]
	}

	function n(a) {
		return "children" in a ? F.call(a.children) : y.map(a.childNodes, function(a) {
			return 1 == a.nodeType ? a : void 0
		})
	}

	function o(a, b) {
		var c, d = a ? a.length : 0;
		for (c = 0; d > c; c++) this[c] = a[c];
		this.length = d, this.selector = b || ""
	}

	function p(a, b, c) {
		for (x in b) c && (f(b[x]) || _(b[x])) ? (f(b[x]) && !f(a[x]) && (a[x] = {}), _(b[x]) && !_(a[x]) && (a[x] = []), p(a[x], b[x], c)) : b[x] !== w && (a[x] = b[x])
	}

	function q(a, b) {
		return null == b ? y(a) : y(a).filter(b)
	}

	function r(a, c, d, e) {
		return b(c) ? c.call(a, d, e) : c
	}

	function s(a, b, c) {
		null == c ? a.removeAttribute(b) : a.setAttribute(b, c)
	}

	function t(a, b) {
		var c = a.className || "",
			d = c && c.baseVal !== w;
		return b === w ? d ? c.baseVal : c : void(d ? c.baseVal = b : a.className = b)
	}

	function u(a) {
		try {
			return a ? "true" == a || ("false" == a ? !1 : "null" == a ? null : +a + "" == a ? +a : /^[\[\{]/.test(a) ? y.parseJSON(a) : a) : a
		} catch (b) {
			return a
		}
	}

	function v(a, b) {
		b(a);
		for (var c = 0, d = a.childNodes.length; d > c; c++) v(a.childNodes[c], b)
	}
	var w, x, y, z, A, B, C = [],
		D = C.concat,
		E = C.filter,
		F = C.slice,
		G = window.document,
		H = {},
		I = {},
		J = {
			"column-count": 1,
			columns: 1,
			"font-weight": 1,
			"line-height": 1,
			opacity: 1,
			"z-index": 1,
			zoom: 1
		},
		K = /^\s*<(\w+|!)[^>]*>/,
		L = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
		M = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
		N = /^(?:body|html)$/i,
		O = /([A-Z])/g,
		P = ["val", "css", "html", "text", "data", "width", "height", "offset"],
		Q = ["after", "prepend", "before", "append"],
		R = G.createElement("table"),
		S = G.createElement("tr"),
		T = {
			tr: G.createElement("tbody"),
			tbody: R,
			thead: R,
			tfoot: R,
			td: S,
			th: S,
			"*": G.createElement("div")
		},
		U = /complete|loaded|interactive/,
		V = /^[\w-]*$/,
		W = {},
		X = W.toString,
		Y = {},
		Z = G.createElement("div"),
		$ = {
			tabindex: "tabIndex",
			readonly: "readOnly",
			"for": "htmlFor",
			"class": "className",
			maxlength: "maxLength",
			cellspacing: "cellSpacing",
			cellpadding: "cellPadding",
			rowspan: "rowSpan",
			colspan: "colSpan",
			usemap: "useMap",
			frameborder: "frameBorder",
			contenteditable: "contentEditable"
		},
		_ = Array.isArray || function(a) {
			return a instanceof Array
		};
	return Y.matches = function(a, b) {
		if (!b || !a || 1 !== a.nodeType) return !1;
		var c = a.webkitMatchesSelector || a.mozMatchesSelector || a.oMatchesSelector || a.matchesSelector;
		if (c) return c.call(a, b);
		var d, e = a.parentNode,
			f = !e;
		return f && (e = Z).appendChild(a), d = ~Y.qsa(e, b).indexOf(a), f && Z.removeChild(a), d
	}, A = function(a) {
		return a.replace(/-+(.)?/g, function(a, b) {
			return b ? b.toUpperCase() : ""
		})
	}, B = function(a) {
		return E.call(a, function(b, c) {
			return a.indexOf(b) == c
		})
	}, Y.fragment = function(a, b, c) {
		var d, e, g;
		return L.test(a) && (d = y(G.createElement(RegExp.$1))), d || (a.replace && (a = a.replace(M, "<$1></$2>")), b === w && (b = K.test(a) && RegExp.$1), b in T || (b = "*"), g = T[b], g.innerHTML = "" + a, d = y.each(F.call(g.childNodes), function() {
			g.removeChild(this)
		})), f(c) && (e = y(d), y.each(c, function(a, b) {
			P.indexOf(a) > -1 ? e[a](b) : e.attr(a, b)
		})), d
	}, Y.Z = function(a, b) {
		return new o(a, b)
	}, Y.isZ = function(a) {
		return a instanceof Y.Z
	}, Y.init = function(a, c) {
		var d;
		if (!a) return Y.Z();
		if ("string" == typeof a)
			if (a = a.trim(), "<" == a[0] && K.test(a)) d = Y.fragment(a, RegExp.$1, c), a = null;
			else {
				if (c !== w) return y(c).find(a);
				d = Y.qsa(G, a)
			} else {
			if (b(a)) return y(G).ready(a);
			if (Y.isZ(a)) return a;
			if (_(a)) d = h(a);
			else if (e(a)) d = [a], a = null;
			else if (K.test(a)) d = Y.fragment(a.trim(), RegExp.$1, c), a = null;
			else {
				if (c !== w) return y(c).find(a);
				d = Y.qsa(G, a)
			}
		}
		return Y.Z(d, a)
	}, y = function(a, b) {
		return Y.init(a, b)
	}, y.extend = function(a) {
		var b, c = F.call(arguments, 1);
		return "boolean" == typeof a && (b = a, a = c.shift()), c.forEach(function(c) {
			p(a, c, b)
		}), a
	}, Y.qsa = function(a, b) {
		var c, d = "#" == b[0],
			e = !d && "." == b[0],
			f = d || e ? b.slice(1) : b,
			g = V.test(f);
		return a.getElementById && g && d ? (c = a.getElementById(f)) ? [c] : [] : 1 !== a.nodeType && 9 !== a.nodeType && 11 !== a.nodeType ? [] : F.call(g && !d && a.getElementsByClassName ? e ? a.getElementsByClassName(f) : a.getElementsByTagName(b) : a.querySelectorAll(b))
	}, y.contains = G.documentElement.contains ? function(a, b) {
		return a !== b && a.contains(b)
	} : function(a, b) {
		for (; b && (b = b.parentNode);)
			if (b === a) return !0;
		return !1
	}, y.type = a, y.isFunction = b, y.isWindow = c, y.isArray = _, y.isPlainObject = f, y.isEmptyObject = function(a) {
		var b;
		for (b in a) return !1;
		return !0
	}, y.inArray = function(a, b, c) {
		return C.indexOf.call(b, a, c)
	}, y.camelCase = A, y.trim = function(a) {
		return null == a ? "" : String.prototype.trim.call(a)
	}, y.uuid = 0, y.support = {}, y.expr = {}, y.noop = function() {}, y.map = function(a, b) {
		var c, d, e, f = [];
		if (g(a))
			for (d = 0; d < a.length; d++) c = b(a[d], d), null != c && f.push(c);
		else
			for (e in a) c = b(a[e], e), null != c && f.push(c);
		return i(f)
	}, y.each = function(a, b) {
		var c, d;
		if (g(a)) {
			for (c = 0; c < a.length; c++)
				if (b.call(a[c], c, a[c]) === !1) return a
		} else
			for (d in a)
				if (b.call(a[d], d, a[d]) === !1) return a; return a
	}, y.grep = function(a, b) {
		return E.call(a, b)
	}, window.JSON && (y.parseJSON = JSON.parse), y.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(a, b) {
		W["[object " + b + "]"] = b.toLowerCase()
	}), y.fn = {
		constructor: Y.Z,
		length: 0,
		forEach: C.forEach,
		reduce: C.reduce,
		push: C.push,
		sort: C.sort,
		splice: C.splice,
		indexOf: C.indexOf,
		concat: function() {
			var a, b, c = [];
			for (a = 0; a < arguments.length; a++) b = arguments[a], c[a] = Y.isZ(b) ? b.toArray() : b;
			return D.apply(Y.isZ(this) ? this.toArray() : this, c)
		},
		map: function(a) {
			return y(y.map(this, function(b, c) {
				return a.call(b, c, b)
			}))
		},
		slice: function() {
			return y(F.apply(this, arguments))
		},
		ready: function(a) {
			return U.test(G.readyState) && G.body ? a(y) : G.addEventListener("DOMContentLoaded", function() {
				a(y)
			}, !1), this
		},
		get: function(a) {
			return a === w ? F.call(this) : this[a >= 0 ? a : a + this.length]
		},
		toArray: function() {
			return this.get()
		},
		size: function() {
			return this.length
		},
		remove: function() {
			return this.each(function() {
				null != this.parentNode && this.parentNode.removeChild(this)
			})
		},
		each: function(a) {
			return C.every.call(this, function(b, c) {
				return a.call(b, c, b) !== !1
			}), this
		},
		filter: function(a) {
			return b(a) ? this.not(this.not(a)) : y(E.call(this, function(b) {
				return Y.matches(b, a)
			}))
		},
		add: function(a, b) {
			return y(B(this.concat(y(a, b))))
		},
		is: function(a) {
			return this.length > 0 && Y.matches(this[0], a)
		},
		not: function(a) {
			var c = [];
			if (b(a) && a.call !== w) this.each(function(b) {
				a.call(this, b) || c.push(this)
			});
			else {
				var d = "string" == typeof a ? this.filter(a) : g(a) && b(a.item) ? F.call(a) : y(a);
				this.forEach(function(a) {
					d.indexOf(a) < 0 && c.push(a)
				})
			}
			return y(c)
		},
		has: function(a) {
			return this.filter(function() {
				return e(a) ? y.contains(this, a) : y(this).find(a).size()
			})
		},
		eq: function(a) {
			return -1 === a ? this.slice(a) : this.slice(a, +a + 1)
		},
		first: function() {
			var a = this[0];
			return a && !e(a) ? a : y(a)
		},
		last: function() {
			var a = this[this.length - 1];
			return a && !e(a) ? a : y(a)
		},
		find: function(a) {
			var b, c = this;
			return b = a ? "object" == typeof a ? y(a).filter(function() {
				var a = this;
				return C.some.call(c, function(b) {
					return y.contains(b, a)
				})
			}) : 1 == this.length ? y(Y.qsa(this[0], a)) : this.map(function() {
				return Y.qsa(this, a)
			}) : y()
		},
		closest: function(a, b) {
			var c = this[0],
				e = !1;
			for ("object" == typeof a && (e = y(a)); c && !(e ? e.indexOf(c) >= 0 : Y.matches(c, a));) c = c !== b && !d(c) && c.parentNode;
			return y(c)
		},
		parents: function(a) {
			for (var b = [], c = this; c.length > 0;) c = y.map(c, function(a) {
				return (a = a.parentNode) && !d(a) && b.indexOf(a) < 0 ? (b.push(a), a) : void 0
			});
			return q(b, a)
		},
		parent: function(a) {
			return q(B(this.pluck("parentNode")), a)
		},
		children: function(a) {
			return q(this.map(function() {
				return n(this)
			}), a)
		},
		contents: function() {
			return this.map(function() {
				return F.call(this.childNodes)
			})
		},
		siblings: function(a) {
			return q(this.map(function(a, b) {
				return E.call(n(b.parentNode), function(a) {
					return a !== b
				})
			}), a)
		},
		empty: function() {
			return this.each(function() {
				this.innerHTML = ""
			})
		},
		pluck: function(a) {
			return y.map(this, function(b) {
				return b[a]
			})
		},
		show: function() {
			return this.each(function() {
				"none" == this.style.display && (this.style.display = ""), "none" == getComputedStyle(this, "").getPropertyValue("display") && (this.style.display = m(this.nodeName))
			})
		},
		replaceWith: function(a) {
			return this.before(a).remove()
		},
		wrap: function(a) {
			var c = b(a);
			if (this[0] && !c) var d = y(a).get(0),
				e = d.parentNode || this.length > 1;
			return this.each(function(b) {
				y(this).wrapAll(c ? a.call(this, b) : e ? d.cloneNode(!0) : d)
			})
		},
		wrapAll: function(a) {
			if (this[0]) {
				y(this[0]).before(a = y(a));
				for (var b;
					(b = a.children()).length;) a = b.first();
				y(a).append(this)
			}
			return this
		},
		wrapInner: function(a) {
			var c = b(a);
			return this.each(function(b) {
				var d = y(this),
					e = d.contents(),
					f = c ? a.call(this, b) : a;
				e.length ? e.wrapAll(f) : d.append(f)
			})
		},
		unwrap: function() {
			return this.parent().each(function() {
				y(this).replaceWith(y(this).children())
			}), this
		},
		clone: function() {
			return this.map(function() {
				return this.cloneNode(!0)
			})
		},
		hide: function() {
			return this.css("display", "none")
		},
		toggle: function(a) {
			return this.each(function() {
				var b = y(this);
				(a === w ? "none" == b.css("display") : a) ? b.show(): b.hide()
			})
		},
		prev: function(a) {
			return y(this.pluck("previousElementSibling")).filter(a || "*")
		},
		next: function(a) {
			return y(this.pluck("nextElementSibling")).filter(a || "*")
		},
		html: function(a) {
			return 0 in arguments ? this.each(function(b) {
				var c = this.innerHTML;
				y(this).empty().append(r(this, a, b, c))
			}) : 0 in this ? this[0].innerHTML : null
		},
		text: function(a) {
			return 0 in arguments ? this.each(function(b) {
				var c = r(this, a, b, this.textContent);
				this.textContent = null == c ? "" : "" + c
			}) : 0 in this ? this[0].textContent : null
		},
		attr: function(a, b) {
			var c;
			return "string" != typeof a || 1 in arguments ? this.each(function(c) {
				if (1 === this.nodeType)
					if (e(a))
						for (x in a) s(this, x, a[x]);
					else s(this, a, r(this, b, c, this.getAttribute(a)))
			}) : this.length && 1 === this[0].nodeType ? !(c = this[0].getAttribute(a)) && a in this[0] ? this[0][a] : c : w
		},
		removeAttr: function(a) {
			return this.each(function() {
				1 === this.nodeType && a.split(" ").forEach(function(a) {
					s(this, a)
				}, this)
			})
		},
		prop: function(a, b) {
			return a = $[a] || a, 1 in arguments ? this.each(function(c) {
				this[a] = r(this, b, c, this[a])
			}) : this[0] && this[0][a]
		},
		data: function(a, b) {
			var c = "data-" + a.replace(O, "-$1").toLowerCase(),
				d = 1 in arguments ? this.attr(c, b) : this.attr(c);
			return null !== d ? u(d) : w
		},
		val: function(a) {
			return 0 in arguments ? this.each(function(b) {
				this.value = r(this, a, b, this.value)
			}) : this[0] && (this[0].multiple ? y(this[0]).find("option").filter(function() {
				return this.selected
			}).pluck("value") : this[0].value)
		},
		offset: function(a) {
			if (a) return this.each(function(b) {
				var c = y(this),
					d = r(this, a, b, c.offset()),
					e = c.offsetParent().offset(),
					f = {
						top: d.top - e.top,
						left: d.left - e.left
					};
				"static" == c.css("position") && (f.position = "relative"), c.css(f)
			});
			if (!this.length) return null;
			var b = this[0].getBoundingClientRect();
			return {
				left: b.left + window.pageXOffset,
				top: b.top + window.pageYOffset,
				width: Math.round(b.width),
				height: Math.round(b.height)
			}
		},
		css: function(b, c) {
			if (arguments.length < 2) {
				var d, e = this[0];
				if (!e) return;
				if (d = getComputedStyle(e, ""), "string" == typeof b) return e.style[A(b)] || d.getPropertyValue(b);
				if (_(b)) {
					var f = {};
					return y.each(b, function(a, b) {
						f[b] = e.style[A(b)] || d.getPropertyValue(b)
					}), f
				}
			}
			var g = "";
			if ("string" == a(b)) c || 0 === c ? g = j(b) + ":" + l(b, c) : this.each(function() {
				this.style.removeProperty(j(b))
			});
			else
				for (x in b) b[x] || 0 === b[x] ? g += j(x) + ":" + l(x, b[x]) + ";" : this.each(function() {
					this.style.removeProperty(j(x))
				});
			return this.each(function() {
				this.style.cssText += ";" + g
			})
		},
		index: function(a) {
			return a ? this.indexOf(y(a)[0]) : this.parent().children().indexOf(this[0])
		},
		hasClass: function(a) {
			return a ? C.some.call(this, function(a) {
				return this.test(t(a))
			}, k(a)) : !1
		},
		addClass: function(a) {
			return a ? this.each(function(b) {
				if ("className" in this) {
					z = [];
					var c = t(this),
						d = r(this, a, b, c);
					d.split(/\s+/g).forEach(function(a) {
						y(this).hasClass(a) || z.push(a)
					}, this), z.length && t(this, c + (c ? " " : "") + z.join(" "))
				}
			}) : this
		},
		removeClass: function(a) {
			return this.each(function(b) {
				if ("className" in this) {
					if (a === w) return t(this, "");
					z = t(this), r(this, a, b, z).split(/\s+/g).forEach(function(a) {
						z = z.replace(k(a), " ")
					}), t(this, z.trim())
				}
			})
		},
		toggleClass: function(a, b) {
			return a ? this.each(function(c) {
				var d = y(this),
					e = r(this, a, c, t(this));
				e.split(/\s+/g).forEach(function(a) {
					(b === w ? !d.hasClass(a) : b) ? d.addClass(a): d.removeClass(a)
				})
			}) : this
		},
		scrollTop: function(a) {
			if (this.length) {
				var b = "scrollTop" in this[0];
				return a === w ? b ? this[0].scrollTop : this[0].pageYOffset : this.each(b ? function() {
					this.scrollTop = a
				} : function() {
					this.scrollTo(this.scrollX, a)
				})
			}
		},
		scrollLeft: function(a) {
			if (this.length) {
				var b = "scrollLeft" in this[0];
				return a === w ? b ? this[0].scrollLeft : this[0].pageXOffset : this.each(b ? function() {
					this.scrollLeft = a
				} : function() {
					this.scrollTo(a, this.scrollY)
				})
			}
		},
		position: function() {
			if (this.length) {
				var a = this[0],
					b = this.offsetParent(),
					c = this.offset(),
					d = N.test(b[0].nodeName) ? {
						top: 0,
						left: 0
					} : b.offset();
				return c.top -= parseFloat(y(a).css("margin-top")) || 0, c.left -= parseFloat(y(a).css("margin-left")) || 0, d.top += parseFloat(y(b[0]).css("border-top-width")) || 0, d.left += parseFloat(y(b[0]).css("border-left-width")) || 0, {
					top: c.top - d.top,
					left: c.left - d.left
				}
			}
		},
		offsetParent: function() {
			return this.map(function() {
				for (var a = this.offsetParent || G.body; a && !N.test(a.nodeName) && "static" == y(a).css("position");) a = a.offsetParent;
				return a
			})
		}
	}, y.fn.detach = y.fn.remove, ["width", "height"].forEach(function(a) {
		var b = a.replace(/./, function(a) {
			return a[0].toUpperCase()
		});
		y.fn[a] = function(e) {
			var f, g = this[0];
			return e === w ? c(g) ? g["inner" + b] : d(g) ? g.documentElement["scroll" + b] : (f = this.offset()) && f[a] : this.each(function(b) {
				g = y(this), g.css(a, r(this, e, b, g[a]()))
			})
		}
	}), Q.forEach(function(b, c) {
		var d = c % 2;
		y.fn[b] = function() {
			var b, e, f = y.map(arguments, function(c) {
					return b = a(c), "object" == b || "array" == b || null == c ? c : Y.fragment(c)
				}),
				g = this.length > 1;
			return f.length < 1 ? this : this.each(function(a, b) {
				e = d ? b : b.parentNode, b = 0 == c ? b.nextSibling : 1 == c ? b.firstChild : 2 == c ? b : null;
				var h = y.contains(G.documentElement, e);
				f.forEach(function(a) {
					if (g) a = a.cloneNode(!0);
					else if (!e) return y(a).remove();
					e.insertBefore(a, b), h && v(a, function(a) {
						null == a.nodeName || "SCRIPT" !== a.nodeName.toUpperCase() || a.type && "text/javascript" !== a.type || a.src || window.eval.call(window, a.innerHTML)
					})
				})
			})
		}, y.fn[d ? b + "To" : "insert" + (c ? "Before" : "After")] = function(a) {
			return y(a)[b](this), this
		}
	}), Y.Z.prototype = o.prototype = y.fn, Y.uniq = B, Y.deserializeValue = u, y.zepto = Y, y
}();
window.Zepto = Zepto, void 0 === window.$ && (window.$ = Zepto),
	function(a) {
		function b(a) {
			return a._zid || (a._zid = m++)
		}

		function c(a, c, f, g) {
			if (c = d(c), c.ns) var h = e(c.ns);
			return (q[b(a)] || []).filter(function(a) {
				return !(!a || c.e && a.e != c.e || c.ns && !h.test(a.ns) || f && b(a.fn) !== b(f) || g && a.sel != g)
			})
		}

		function d(a) {
			var b = ("" + a).split(".");
			return {
				e: b[0],
				ns: b.slice(1).sort().join(" ")
			}
		}

		function e(a) {
			return new RegExp("(?:^| )" + a.replace(" ", " .* ?") + "(?: |$)")
		}

		function f(a, b) {
			return a.del && !s && a.e in t || !!b
		}

		function g(a) {
			return u[a] || s && t[a] || a
		}

		function h(c, e, h, i, k, m, n) {
			var o = b(c),
				p = q[o] || (q[o] = []);
			e.split(/\s/).forEach(function(b) {
				if ("ready" == b) return a(document).ready(h);
				var e = d(b);
				e.fn = h, e.sel = k, e.e in u && (h = function(b) {
					var c = b.relatedTarget;
					return !c || c !== this && !a.contains(this, c) ? e.fn.apply(this, arguments) : void 0
				}), e.del = m;
				var o = m || h;
				e.proxy = function(a) {
					if (a = j(a), !a.isImmediatePropagationStopped()) {
						a.data = i;
						var b = o.apply(c, a._args == l ? [a] : [a].concat(a._args));
						return b === !1 && (a.preventDefault(), a.stopPropagation()), b
					}
				}, e.i = p.length, p.push(e), "addEventListener" in c && c.addEventListener(g(e.e), e.proxy, f(e, n))
			})
		}

		function i(a, d, e, h, i) {
			var j = b(a);
			(d || "").split(/\s/).forEach(function(b) {
				c(a, b, e, h).forEach(function(b) {
					delete q[j][b.i], "removeEventListener" in a && a.removeEventListener(g(b.e), b.proxy, f(b, i))
				})
			})
		}

		function j(b, c) {
			return (c || !b.isDefaultPrevented) && (c || (c = b), a.each(y, function(a, d) {
				var e = c[a];
				b[a] = function() {
					return this[d] = v, e && e.apply(c, arguments)
				}, b[d] = w
			}), (c.defaultPrevented !== l ? c.defaultPrevented : "returnValue" in c ? c.returnValue === !1 : c.getPreventDefault && c.getPreventDefault()) && (b.isDefaultPrevented = v)), b
		}

		function k(a) {
			var b, c = {
				originalEvent: a
			};
			for (b in a) x.test(b) || a[b] === l || (c[b] = a[b]);
			return j(c, a)
		}
		var l, m = 1,
			n = Array.prototype.slice,
			o = a.isFunction,
			p = function(a) {
				return "string" == typeof a
			},
			q = {},
			r = {},
			s = "onfocusin" in window,
			t = {
				focus: "focusin",
				blur: "focusout"
			},
			u = {
				mouseenter: "mouseover",
				mouseleave: "mouseout"
			};
		r.click = r.mousedown = r.mouseup = r.mousemove = "MouseEvents", a.event = {
			add: h,
			remove: i
		}, a.proxy = function(c, d) {
			var e = 2 in arguments && n.call(arguments, 2);
			if (o(c)) {
				var f = function() {
					return c.apply(d, e ? e.concat(n.call(arguments)) : arguments)
				};
				return f._zid = b(c), f
			}
			if (p(d)) return e ? (e.unshift(c[d], c), a.proxy.apply(null, e)) : a.proxy(c[d], c);
			throw new TypeError("expected function")
		}, a.fn.bind = function(a, b, c) {
			return this.on(a, b, c)
		}, a.fn.unbind = function(a, b) {
			return this.off(a, b)
		}, a.fn.one = function(a, b, c, d) {
			return this.on(a, b, c, d, 1)
		};
		var v = function() {
				return !0
			},
			w = function() {
				return !1
			},
			x = /^([A-Z]|returnValue$|layer[XY]$)/,
			y = {
				preventDefault: "isDefaultPrevented",
				stopImmediatePropagation: "isImmediatePropagationStopped",
				stopPropagation: "isPropagationStopped"
			};
		a.fn.delegate = function(a, b, c) {
			return this.on(b, a, c)
		}, a.fn.undelegate = function(a, b, c) {
			return this.off(b, a, c)
		}, a.fn.live = function(b, c) {
			return a(document.body).delegate(this.selector, b, c), this
		}, a.fn.die = function(b, c) {
			return a(document.body).undelegate(this.selector, b, c), this
		}, a.fn.on = function(b, c, d, e, f) {
			var g, j, m = this;
			return b && !p(b) ? (a.each(b, function(a, b) {
				m.on(a, c, d, b, f)
			}), m) : (p(c) || o(e) || e === !1 || (e = d, d = c, c = l), (o(d) || d === !1) && (e = d, d = l), e === !1 && (e = w), m.each(function(l, m) {
				f && (g = function(a) {
					return i(m, a.type, e), e.apply(this, arguments)
				}), c && (j = function(b) {
					var d, f = a(b.target).closest(c, m).get(0);
					return f && f !== m ? (d = a.extend(k(b), {
						currentTarget: f,
						liveFired: m
					}), (g || e).apply(f, [d].concat(n.call(arguments, 1)))) : void 0
				}), h(m, b, e, d, c, j || g)
			}))
		}, a.fn.off = function(b, c, d) {
			var e = this;
			return b && !p(b) ? (a.each(b, function(a, b) {
				e.off(a, c, b)
			}), e) : (p(c) || o(d) || d === !1 || (d = c, c = l), d === !1 && (d = w), e.each(function() {
				i(this, b, d, c)
			}))
		}, a.fn.trigger = function(b, c) {
			return b = p(b) || a.isPlainObject(b) ? a.Event(b) : j(b), b._args = c, this.each(function() {
				b.type in t && "function" == typeof this[b.type] ? this[b.type]() : "dispatchEvent" in this ? this.dispatchEvent(b) : a(this).triggerHandler(b, c)
			})
		}, a.fn.triggerHandler = function(b, d) {
			var e, f;
			return this.each(function(g, h) {
				e = k(p(b) ? a.Event(b) : b), e._args = d, e.target = h, a.each(c(h, b.type || b), function(a, b) {
					return f = b.proxy(e), e.isImmediatePropagationStopped() ? !1 : void 0
				})
			}), f
		}, "focusin focusout focus blur load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select keydown keypress keyup error".split(" ").forEach(function(b) {
			a.fn[b] = function(a) {
				return 0 in arguments ? this.bind(b, a) : this.trigger(b)
			}
		}), a.Event = function(a, b) {
			p(a) || (b = a, a = b.type);
			var c = document.createEvent(r[a] || "Events"),
				d = !0;
			if (b)
				for (var e in b) "bubbles" == e ? d = !!b[e] : c[e] = b[e];
			return c.initEvent(a, d, !0), j(c)
		}
	}(Zepto),
	function(a) {
		function b(b, c, d) {
			var e = a.Event(c);
			return a(b).trigger(e, d), !e.isDefaultPrevented()
		}

		function c(a, c, d, e) {
			return a.global ? b(c || s, d, e) : void 0
		}

		function d(b) {
			b.global && 0 === a.active++ && c(b, null, "ajaxStart")
		}

		function e(b) {
			b.global && !--a.active && c(b, null, "ajaxStop")
		}

		function f(a, b) {
			var d = b.context;
			return b.beforeSend.call(d, a, b) === !1 || c(b, d, "ajaxBeforeSend", [a, b]) === !1 ? !1 : void c(b, d, "ajaxSend", [a, b])
		}

		function g(a, b, d, e) {
			var f = d.context,
				g = "success";
			d.success.call(f, a, g, b), e && e.resolveWith(f, [a, g, b]), c(d, f, "ajaxSuccess", [b, d, a]), i(g, b, d)
		}

		function h(a, b, d, e, f) {
			var g = e.context;
			e.error.call(g, d, b, a), f && f.rejectWith(g, [d, b, a]), c(e, g, "ajaxError", [d, e, a || b]), i(b, d, e)
		}

		function i(a, b, d) {
			var f = d.context;
			d.complete.call(f, b, a), c(d, f, "ajaxComplete", [b, d]), e(d)
		}

		function j() {}

		function k(a) {
			return a && (a = a.split(";", 2)[0]), a && (a == x ? "html" : a == w ? "json" : u.test(a) ? "script" : v.test(a) && "xml") || "text"
		}

		function l(a, b) {
			return "" == b ? a : (a + "&" + b).replace(/[&?]{1,2}/, "?")
		}

		function m(b) {
			b.processData && b.data && "string" != a.type(b.data) && (b.data = a.param(b.data, b.traditional)), !b.data || b.type && "GET" != b.type.toUpperCase() || (b.url = l(b.url, b.data), b.data = void 0)
		}

		function n(b, c, d, e) {
			return a.isFunction(c) && (e = d, d = c, c = void 0), a.isFunction(d) || (e = d, d = void 0), {
				url: b,
				data: c,
				success: d,
				dataType: e
			}
		}

		function o(b, c, d, e) {
			var f, g = a.isArray(c),
				h = a.isPlainObject(c);
			a.each(c, function(c, i) {
				f = a.type(i), e && (c = d ? e : e + "[" + (h || "object" == f || "array" == f ? c : "") + "]"), !e && g ? b.add(i.name, i.value) : "array" == f || !d && "object" == f ? o(b, i, d, c) : b.add(c, i)
			})
		}
		var p, q, r = 0,
			s = window.document,
			t = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
			u = /^(?:text|application)\/javascript/i,
			v = /^(?:text|application)\/xml/i,
			w = "application/json",
			x = "text/html",
			y = /^\s*$/,
			z = s.createElement("a");
		z.href = window.location.href, a.active = 0, a.ajaxJSONP = function(b, c) {
			if (!("type" in b)) return a.ajax(b);
			var d, e, i = b.jsonpCallback,
				j = (a.isFunction(i) ? i() : i) || "jsonp" + ++r,
				k = s.createElement("script"),
				l = window[j],
				m = function(b) {
					a(k).triggerHandler("error", b || "abort")
				},
				n = {
					abort: m
				};
			return c && c.promise(n), a(k).on("load error", function(f, i) {
				clearTimeout(e), a(k).off().remove(), "error" != f.type && d ? g(d[0], n, b, c) : h(null, i || "error", n, b, c), window[j] = l, d && a.isFunction(l) && l(d[0]), l = d = void 0
			}), f(n, b) === !1 ? (m("abort"), n) : (window[j] = function() {
				d = arguments
			}, k.src = b.url.replace(/\?(.+)=\?/, "?$1=" + j), s.head.appendChild(k), b.timeout > 0 && (e = setTimeout(function() {
				m("timeout")
			}, b.timeout)), n)
		}, a.ajaxSettings = {
			type: "GET",
			beforeSend: j,
			success: j,
			error: j,
			complete: j,
			context: null,
			global: !0,
			xhr: function() {
				return new window.XMLHttpRequest
			},
			accepts: {
				script: "text/javascript, application/javascript, application/x-javascript",
				json: w,
				xml: "application/xml, text/xml",
				html: x,
				text: "text/plain"
			},
			crossDomain: !1,
			timeout: 0,
			processData: !0,
			cache: !0
		}, a.ajax = function(b) {
			var c, e = a.extend({}, b || {}),
				i = a.Deferred && a.Deferred();
			for (p in a.ajaxSettings) void 0 === e[p] && (e[p] = a.ajaxSettings[p]);
			d(e), e.crossDomain || (c = s.createElement("a"), c.href = e.url, c.href = c.href, e.crossDomain = z.protocol + "//" + z.host != c.protocol + "//" + c.host), e.url || (e.url = window.location.toString()), m(e);
			var n = e.dataType,
				o = /\?.+=\?/.test(e.url);
			if (o && (n = "jsonp"), e.cache !== !1 && (b && b.cache === !0 || "script" != n && "jsonp" != n) || (e.url = l(e.url, "_=" + Date.now())), "jsonp" == n) return o || (e.url = l(e.url, e.jsonp ? e.jsonp + "=?" : e.jsonp === !1 ? "" : "callback=?")), a.ajaxJSONP(e, i);
			var r, t = e.accepts[n],
				u = {},
				v = function(a, b) {
					u[a.toLowerCase()] = [a, b]
				},
				w = /^([\w-]+:)\/\//.test(e.url) ? RegExp.$1 : window.location.protocol,
				x = e.xhr(),
				A = x.setRequestHeader;
			if (i && i.promise(x), e.crossDomain || v("X-Requested-With", "XMLHttpRequest"), v("Accept", t || "*/*"), (t = e.mimeType || t) && (t.indexOf(",") > -1 && (t = t.split(",", 2)[0]), x.overrideMimeType && x.overrideMimeType(t)), (e.contentType || e.contentType !== !1 && e.data && "GET" != e.type.toUpperCase()) && v("Content-Type", e.contentType || "application/x-www-form-urlencoded"), e.headers)
				for (q in e.headers) v(q, e.headers[q]);
			if (x.setRequestHeader = v, x.onreadystatechange = function() {
				if (4 == x.readyState) {
					x.onreadystatechange = j, clearTimeout(r);
					var b, c = !1;
					if (x.status >= 200 && x.status < 300 || 304 == x.status || 0 == x.status && "file:" == w) {
						n = n || k(e.mimeType || x.getResponseHeader("content-type")), b = x.responseText;
						try {
							"script" == n ? (1, eval)(b) : "xml" == n ? b = x.responseXML : "json" == n && (b = y.test(b) ? null : a.parseJSON(b))
						} catch (d) {
							c = d
						}
						c ? h(c, "parsererror", x, e, i) : g(b, x, e, i)
					} else h(x.statusText || null, x.status ? "error" : "abort", x, e, i)
				}
			}, f(x, e) === !1) return x.abort(), h(null, "abort", x, e, i), x;
			if (e.xhrFields)
				for (q in e.xhrFields) x[q] = e.xhrFields[q];
			var B = "async" in e ? e.async : !0;
			x.open(e.type, e.url, B, e.username, e.password);
			for (q in u) A.apply(x, u[q]);
			return e.timeout > 0 && (r = setTimeout(function() {
				x.onreadystatechange = j, x.abort(), h(null, "timeout", x, e, i)
			}, e.timeout)), x.send(e.data ? e.data : null), x
		}, a.get = function() {
			return a.ajax(n.apply(null, arguments))
		}, a.post = function() {
			var b = n.apply(null, arguments);
			return b.type = "POST", a.ajax(b)
		}, a.getJSON = function() {
			var b = n.apply(null, arguments);
			return b.dataType = "json", a.ajax(b)
		}, a.fn.load = function(b, c, d) {
			if (!this.length) return this;
			var e, f = this,
				g = b.split(/\s/),
				h = n(b, c, d),
				i = h.success;
			return g.length > 1 && (h.url = g[0], e = g[1]), h.success = function(b) {
				f.html(e ? a("<div>").html(b.replace(t, "")).find(e) : b), i && i.apply(f, arguments)
			}, a.ajax(h), this
		};
		var A = encodeURIComponent;
		a.param = function(b, c) {
			var d = [];
			return d.add = function(b, c) {
				a.isFunction(c) && (c = c()), null == c && (c = ""), this.push(A(b) + "=" + A(c))
			}, o(d, b, c), d.join("&").replace(/%20/g, "+")
		}
	}(Zepto),
	function(a) {
		a.fn.serializeArray = function() {
			var b, c, d = [],
				e = function(a) {
					return a.forEach ? a.forEach(e) : void d.push({
						name: b,
						value: a
					})
				};
			return this[0] && a.each(this[0].elements, function(d, f) {
				c = f.type, b = f.name, b && "fieldset" != f.nodeName.toLowerCase() && !f.disabled && "submit" != c && "reset" != c && "button" != c && "file" != c && ("radio" != c && "checkbox" != c || f.checked) && e(a(f).val())
			}), d
		}, a.fn.serialize = function() {
			var a = [];
			return this.serializeArray().forEach(function(b) {
				a.push(encodeURIComponent(b.name) + "=" + encodeURIComponent(b.value))
			}), a.join("&")
		}, a.fn.submit = function(b) {
			if (0 in arguments) this.bind("submit", b);
			else if (this.length) {
				var c = a.Event("submit");
				this.eq(0).trigger(c), c.isDefaultPrevented() || this.get(0).submit()
			}
			return this
		}
	}(Zepto),
	function() {
		try {
			getComputedStyle(void 0)
		} catch (a) {
			var b = getComputedStyle;
			window.getComputedStyle = function(a) {
				try {
					return b(a)
				} catch (c) {
					return null
				}
			}
		}
	}(),
	function(a) {
		function b(b, d) {
			var i = b[h],
				j = i && e[i];
			if (void 0 === d) return j || c(b);
			if (j) {
				if (d in j) return j[d];
				var k = g(d);
				if (k in j) return j[k]
			}
			return f.call(a(b), d)
		}

		function c(b, c, f) {
			var i = b[h] || (b[h] = ++a.uuid),
				j = e[i] || (e[i] = d(b));
			return void 0 !== c && (j[g(c)] = f), j
		}

		function d(b) {
			var c = {};
			return a.each(b.attributes || i, function(b, d) {
				0 == d.name.indexOf("data-") && (c[g(d.name.replace("data-", ""))] = a.zepto.deserializeValue(d.value))
			}), c
		}
		var e = {},
			f = a.fn.data,
			g = a.camelCase,
			h = a.expando = "Zepto" + +new Date,
			i = [];
		a.fn.data = function(d, e) {
			return void 0 === e ? a.isPlainObject(d) ? this.each(function(b, e) {
				a.each(d, function(a, b) {
					c(e, a, b)
				})
			}) : 0 in this ? b(this[0], d) : void 0 : this.each(function() {
				c(this, d, e)
			})
		}, a.fn.removeData = function(b) {
			return "string" == typeof b && (b = b.split(/\s+/)), this.each(function() {
				var c = this[h],
					d = c && e[c];
				d && a.each(b || d, function(a) {
					delete d[b ? g(this) : a]
				})
			})
		}, ["remove", "empty"].forEach(function(b) {
			var c = a.fn[b];
			a.fn[b] = function() {
				var a = this.find("*");
				return "remove" === b && (a = a.add(this)), a.removeData(), c.call(this)
			}
		})
	}(Zepto),
	function(a) {
		a.Callbacks = function(b) {
			b = a.extend({}, b);
			var c, d, e, f, g, h, i = [],
				j = !b.once && [],
				k = function(a) {
					for (c = b.memory && a, d = !0, h = f || 0, f = 0, g = i.length, e = !0; i && g > h; ++h)
						if (i[h].apply(a[0], a[1]) === !1 && b.stopOnFalse) {
							c = !1;
							break
						}
					e = !1, i && (j ? j.length && k(j.shift()) : c ? i.length = 0 : l.disable())
				},
				l = {
					add: function() {
						if (i) {
							var d = i.length,
								h = function(c) {
									a.each(c, function(a, c) {
										"function" == typeof c ? b.unique && l.has(c) || i.push(c) : c && c.length && "string" != typeof c && h(c)
									})
								};
							h(arguments), e ? g = i.length : c && (f = d, k(c))
						}
						return this
					},
					remove: function() {
						return i && a.each(arguments, function(b, c) {
							for (var d;
								(d = a.inArray(c, i, d)) > -1;) i.splice(d, 1), e && (g >= d && --g, h >= d && --h)
						}), this
					},
					has: function(b) {
						return !(!i || !(b ? a.inArray(b, i) > -1 : i.length))
					},
					empty: function() {
						return g = i.length = 0, this
					},
					disable: function() {
						return i = j = c = void 0, this
					},
					disabled: function() {
						return !i
					},
					lock: function() {
						return j = void 0, c || l.disable(), this
					},
					locked: function() {
						return !j
					},
					fireWith: function(a, b) {
						return !i || d && !j || (b = b || [], b = [a, b.slice ? b.slice() : b], e ? j.push(b) : k(b)), this
					},
					fire: function() {
						return l.fireWith(this, arguments)
					},
					fired: function() {
						return !!d
					}
				};
			return l
		}
	}(Zepto),
	function(a) {
		function b(c) {
			var d = [
					["resolve", "done", a.Callbacks({
						once: 1,
						memory: 1
					}), "resolved"],
					["reject", "fail", a.Callbacks({
						once: 1,
						memory: 1
					}), "rejected"],
					["notify", "progress", a.Callbacks({
						memory: 1
					})]
				],
				e = "pending",
				f = {
					state: function() {
						return e
					},
					always: function() {
						return g.done(arguments).fail(arguments), this
					},
					then: function() {
						var c = arguments;
						return b(function(b) {
							a.each(d, function(d, e) {
								var h = a.isFunction(c[d]) && c[d];
								g[e[1]](function() {
									var c = h && h.apply(this, arguments);
									if (c && a.isFunction(c.promise)) c.promise().done(b.resolve).fail(b.reject).progress(b.notify);
									else {
										var d = this === f ? b.promise() : this,
											g = h ? [c] : arguments;
										b[e[0] + "With"](d, g)
									}
								})
							}), c = null
						}).promise()
					},
					promise: function(b) {
						return null != b ? a.extend(b, f) : f
					}
				},
				g = {};
			return a.each(d, function(a, b) {
				var c = b[2],
					h = b[3];
				f[b[1]] = c.add, h && c.add(function() {
					e = h
				}, d[1 ^ a][2].disable, d[2][2].lock), g[b[0]] = function() {
					return g[b[0] + "With"](this === g ? f : this, arguments), this
				}, g[b[0] + "With"] = c.fireWith
			}), f.promise(g), c && c.call(g, g), g
		}
		var c = Array.prototype.slice;
		a.when = function(d) {
			var e, f, g, h = c.call(arguments),
				i = h.length,
				j = 0,
				k = 1 !== i || d && a.isFunction(d.promise) ? i : 0,
				l = 1 === k ? d : b(),
				m = function(a, b, d) {
					return function(f) {
						b[a] = this, d[a] = arguments.length > 1 ? c.call(arguments) : f, d === e ? l.notifyWith(b, d) : --k || l.resolveWith(b, d)
					}
				};
			if (i > 1)
				for (e = new Array(i), f = new Array(i), g = new Array(i); i > j; ++j) h[j] && a.isFunction(h[j].promise) ? h[j].promise().done(m(j, g, h)).fail(l.reject).progress(m(j, f, e)) : --k;
			return k || l.resolveWith(g, h), l.promise()
		}, a.Deferred = b
	}(Zepto),
	function(a) {
		function b(b) {
			return b = a(b), !(!b.width() && !b.height()) && "none" !== b.css("display")
		}

		function c(a, b) {
			a = a.replace(/=#\]/g, '="#"]');
			var c, d, e = h.exec(a);
			if (e && e[2] in g && (c = g[e[2]], d = e[3], a = e[1], d)) {
				var f = Number(d);
				d = isNaN(f) ? d.replace(/^["']|["']$/g, "") : f
			}
			return b(a, c, d)
		}
		var d = a.zepto,
			e = d.qsa,
			f = d.matches,
			g = a.expr[":"] = {
				visible: function() {
					return b(this) ? this : void 0
				},
				hidden: function() {
					return b(this) ? void 0 : this
				},
				selected: function() {
					return this.selected ? this : void 0
				},
				checked: function() {
					return this.checked ? this : void 0
				},
				parent: function() {
					return this.parentNode
				},
				first: function(a) {
					return 0 === a ? this : void 0
				},
				last: function(a, b) {
					return a === b.length - 1 ? this : void 0
				},
				eq: function(a, b, c) {
					return a === c ? this : void 0
				},
				contains: function(b, c, d) {
					return a(this).text().indexOf(d) > -1 ? this : void 0
				},
				has: function(a, b, c) {
					return d.qsa(this, c).length ? this : void 0
				}
			},
			h = new RegExp("(.*):(\\w+)(?:\\(([^)]+)\\))?$\\s*"),
			i = /^\s*>/,
			j = "Zepto" + +new Date;
		d.qsa = function(b, f) {
			return c(f, function(c, g, h) {
				try {
					var k;
					!c && g ? c = "*" : i.test(c) && (k = a(b).addClass(j), c = "." + j + " " + c);
					var l = e(b, c)
				} catch (m) {
					throw console.error("error performing selector: %o", f), m
				} finally {
					k && k.removeClass(j)
				}
				return g ? d.uniq(a.map(l, function(a, b) {
					return g.call(a, b, l, h)
				})) : l
			})
		}, d.matches = function(a, b) {
			return c(b, function(b, c, d) {
				return !(b && !f(a, b) || c && c.call(a, null, d) !== a)
			})
		}
	}(Zepto), ~ function(a) {
		var b, c, d, e, f, g, h, i, j, k, l, m, n, o = [];
		b = {
			email: function(a) {
				return /^(?:[a-z0-9]+[_\-+.]?)*[a-z0-9]+@(?:([a-z0-9]+-?)*[a-z0-9]+.)+([a-z]{2,})+$/i.test(a)
			},
			date: function(a) {
				var b, c, d = /^([1-2]\d{3})([-/.])?(1[0-2]|0?[1-9])([-/.])?([1-2]\d|3[01]|0?[1-9])$/;
				return d.test(a) ? (b = d.exec(a), year = +b[1], month = +b[3] - 1, day = +b[5], c = new Date(year, month, day), year === c.getFullYear() && month === c.getMonth() && day === c.getDate()) : !1
			},
			mobile: function(a) {
				return /^1[3-9]\d{9}$/.test(a)
			},
			tel: function(a) {
				return /^(?:(?:0\d{2,3}[- ]?[1-9]\d{6,7})|(?:[48]00[- ]?[1-9]\d{6}))$/.test(a)
			},
			number: function(a) {
				var b = +this.$item.attr("min"),
					c = +this.$item.attr("max"),
					d = /^\-?(?:[1-9]\d*|0)(?:[.]\d+)?$/.test(a),
					a = +a,
					e = +this.$item.attr("step");
				return isNaN(b) && (b = a - 1), isNaN(c) && (c = a + 1), d && (isNaN(e) || 0 >= e ? a >= b && c >= a : 0 === (a + b) % e && a >= b && c >= a)
			},
			range: function(a) {
				return this.number(a)
			},
			url: function(a) {
				var b, c = "((https?|s?ftp|irc[6s]?|git|afp|telnet|smb):\\/\\/)?",
					d = "([a-z0-9]\\w*(\\:[\\S]+)?\\@)?",
					e = "(?:[a-z0-9]+(?:-[w]+)*.)*[a-z]{2,}",
					f = "(:\\d{1,5})?",
					g = "\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}",
					h = "(\\/\\S*)?",
					i = [c, d, e, f, h],
					j = [c, d, g, f, h];
				return b = function(b) {
					return new RegExp("^" + b.join("") + "$", "i").test(a)
				}, b(i) || b(j)
			},
			password: function(a) {
				return this.text(a)
			},
			checkbox: function() {
				return b._checker("checkbox")
			},
			radio: function() {
				return b._checker("radio")
			},
			_checker: function(b) {
				var c = this.$item.parents("form").eq(0),
					d = "input:" + b + '[name="' + this.$item.attr("name") + '"]',
					e = !1,
					f = a(d, c);
				return f.each(function(a, b) {
					return b.checked && !e ? e = !0 : void 0
				}), e
			},
			text: function(a) {
				var b = parseInt(this.$item.attr("maxlength"), 10);
				return notEmpty = function(a) {
					return !!a.length && !/^\s+$/.test(a)
				}, isNaN(b) ? notEmpty(a) : notEmpty(a) && a.length <= b
			}
		}, k = function(b, c, d) {
			var e = b.data(),
				f = e.url,
				g = e.method || "get",
				h = e.key || "key",
				i = l(b),
				j = {};
			j[h] = i, a[g](f, j).success(function(a) {
				var e = a ? "IM VALIDED" : "unvalid";
				return n.call(this, b, c, d, e)
			}).error(function() {})
		}, m = function(b, c, d) {
			var e = "a" === b.data("aorb") ? "b" : "a",
				g = a("[data-aorb=" + e + "]", b.parents("form").eq(0)),
				h = [b, c, d],
				i = [g, c, d],
				j = 0;
			return j += n.apply(this, h) ? 0 : 1, j += n.apply(this, i) ? 0 : 1, j = j > 0 ? (f.apply(this, h), f.apply(this, i), !1) : n.apply(this, h.concat("unvalid"))
		}, n = function(c, d, g, h) {
			if (!c) return "DONT VALIDATE UNEXIST ELEMENT";
			var i, j, k, m, n;
			return i = c.attr("pattern"), i && i.replace("\\", "\\\\"), j = c.attr("type") || "text", j = b[j] ? j : "text", k = a.trim(l(c)), n = c.data("event"), h = h ? h : i ? new RegExp(i).test(k) || "unvalid" : b[j](k) || "unvalid", "unvalid" === h && f(c, d, g), /^(?:unvalid|empty)$/.test(h) ? (m = {
				$el: e.call(this, c, d, g, h),
				type: j,
				error: h
			}, c.trigger("after:" + n, c), m) : (f.call(this, c, d, g), c.trigger("after:" + n, c), !1)
		}, c = function(b, c) {
			return a(b, c)
		}, l = function(a) {
			return a.val() || (a.is("[contenteditable]") ? a.text() : "")
		}, validate = function(a, c, d) {
			var e, f, g, h, i, j;
			return b.$item = a, g = a.attr("type"), h = l(a), e = a.data("url"), f = a.data("aorb"), j = a.data("event"), i = [a, c, d], j && a.trigger("before:" + j, a), /^(?:radio|checkbox)$/.test(g) || f || b.text(h) ? f ? m.apply(this, i) : e ? k.apply(this, i) : n.call(this, a, c, d) : n.call(this, a, c, d, "empty")
		}, i = function(b, c, d, e) {
			var f, g = /^radio|checkbox/;
			a.each(b, function(b, h) {
				a(h).on(g.test(h.type) || "SELECT" === h.tagName ? "change blur" : c, function() {
					var b = a(this);
					g.test(this.type) && (b = a("input[type=" + this.type + "][name=" + this.name + "]", b.closest("form"))), b.each(function() {
						(f = validate.call(this, a(this), d, e)) && o.push(f)
					})
				})
			})
		}, h = function(b, c, d, e) {
			return c && !i.length ? !0 : (o = a.map(b, function(b) {
				var c = validate.call(null, a(b), d, e);
				return c ? c : void 0
			}), i.length ? o : !1)
		}, j = function(b) {
			var c, d;
			return (c = a.grep(o, function(a) {
				return a.$el = b
			})[0]) ? (d = a.inArray(c, o), o.splice(d, 1), o) : void 0
		}, d = function(a, b) {
			return a.data("parent") ? a.closest(a.data("parent")) : b ? a.parent() : a
		}, e = function(a, b, c, e) {
			return d(a, c).addClass(b + " " + e)
		}, f = function(a, b, c) {
			return j.call(this, a), d(a, c).removeClass(b + " empty unvalid")
		}, g = function(a) {
			return a.attr("novalidate") || a.attr("novalidate", "true")
		}, a.fn.validator = function(b) {
			var d = this,
				b = b || {},
				e = b.identifie || "[required]",
				j = b.error || "error",
				k = b.isErrorOnParent || !1,
				l = b.method || "blur",
				m = b.before || function() {
					return !0
				},
				n = b.after || function() {
					return !0
				},
				p = b.errorCallback || function() {},
				q = c(e, d);
			g(d), l && i.call(this, q, l, j, k), d.on("focusin", e, function() {
				f.call(this, a(this), "error unvalid empty", k)
			}), d.on("submit", function(a) {
				return m.call(this, q), h.call(this, q, l, j, k), o.length ? (a.preventDefault(), p.call(this, o)) : n.call(this, a, q)
			})
		}
	}($),
	function(a) {
		"use strict";
		var b = [],
			c = function() {
				for (var a = n(), c = 0; c < b.length; c++) try {
					var d = b[c],
						g = d[0],
						h = d[1],
						i = g.data("offset.lazy"),
						j = h.condition(g, i) && (0 != i.top || 0 != i.left);
					j && i.top + g.height() > a.top && i.top < a.bottom && (h.once && l(g), h.beforeLoad(g, h), e(d))
				} catch (k) {}
				f()
			},
			d = function(a) {
				for (var c = 0; c < b.length; c++) {
					var d = b[c];
					if (d && d[0].get(0) == a.get(0)) {
						d[1].once && l(d[0]), e(d);
						break
					}
				}
			},
			e = function(b) {
				var c = b[0],
					d = b[1];
				"image" == d.type ? ! function(a, b) {
					setTimeout(function() {
						g(a, b)
					}, 0)
				}(c, d) : "url" == d.type ? ! function(a, b) {
					setTimeout(function() {
						h(a, b)
					}, 0)
				}(c, d) : ! function(b, c) {
					setTimeout(function() {
						c.onLoad.call(b.get(0), c.res(b), c), a(window).trigger("scroll")
					}, 0)
				}(c, d)
			},
			f = function() {
				var a = function(a, c) {
					for (var d = [], e = 0; e < b.length; e++) c(b[e], e, b) && d.push(b[e]);
					return d
				};
				b = a(b, function(a) {
					return !!a
				})
			},
			g = function(b, c) {
				var d = c.res(b);
				b.one({
					"load.__lazy": function(d) {
						c.onLoad.call(b.get(0), d, c), a(this).off(".lazy"), a(window).trigger("scroll")
					},
					"error.__lazy": function(d) {
						c.onError.call(b.get(0), d, c), a(this).off(".__lazy"), a(window).trigger("scroll")
					}
				});
				var e = b.get(0);
				null != d && "" != d ? (b.attr("src", d), e.complete || 4 === e.readyState ? b.trigger("load") : "uninitialized" === e.readyState && 0 === e.src.indexOf("data:") && b.trigger("error")) : b.trigger("error")
			},
			h = function(b, c) {
				a.ajax(c.res(b)).done(function(a) {
					c.onLoad.call(b.get(0), a, c)
				}).fail(function(a) {
					c.onError.call(b.get(0), a, c)
				}).always(function() {
					a(window).trigger("scroll")
				})
			},
			i = function() {
				a.each(b, function(a, b) {
					b && setTimeout(function() {
						b[0].data("offset.lazy", b[1].offset(b[0]))
					}, 0)
				})
			},
			j = function() {
				i(), setTimeout(function() {
					a(window).trigger("scroll")
				}, 10)
			},
			k = function(c, d) {
				if (d || (d = {}), a.isFunction(d.offset) || (d.offset = function(a) {
					return a.offset()
				}), "img" == c.get(0).tagName.toLowerCase() && (d.type = "image"), d.condition || (d.condition = function() {
					return !0
				}), "image" == d.type && (d.once = !0, d.res || (d.res = c.data("src"))), !a.isFunction(d.res)) {
					var e = d.res;
					d.res = function() {
						return e
					}
				}
				c.data("offset.lazy", d.offset(c)), d.beforeLoad || (d.beforeLoad = function() {}), d.onLoad || (d.onLoad = function() {}), d.onError || (d.onError = function() {}), b.push([c, d])
			},
			l = function(a) {
				for (var c = 0; c < b.length; c++) {
					var d = b[c];
					if (d && d[0].get(0) == a.get(0)) {
						d[0].removeData("offset.lazy"), b[c] = null;
						break
					}
				}
			},
			m = function(a, b, c) {
				var d, e = null,
					f = 0;
				return function() {
					var g = new Date,
						h = b - (g - f);
					return c || (c = this), 0 >= h && (clearTimeout(e), e = null, f = g, d = a.apply(c, arguments)), d
				}
			},
			n = function() {
				var b = a(window),
					c = b.scrollTop();
				return {
					top: c - a.lazy.sensitivity,
					bottom: c + b.height() + a.lazy.sensitivity
				}
			};
		a(window).on("scroll.__lazy", m(i, 100)), a(window).on("scroll.__lazy", m(c, 1)), a.fn.lazy = function(b) {
			return "destroy" === b ? this.each(function() {
				l(a(this))
			}) : "load" == b ? this.each(function() {
				d(a(this))
			}) : (this.each(function() {
				var c = a(this);
				setTimeout(function() {
					k(c, a.extend(!0, {}, b))
				}, 0)
			}), setTimeout(function() {
				a(window).trigger("scroll")
			}, 100)), this
		}, a.lazy = {
			refresh: j,
			sensitivity: 50
		}
	}($),
	function() {
		function a(a) {
			a.show().css("z-index", ++e), b(a)
		}

		function b(a) {
			var b = a.find(".modal-container"),
				c = $(window),
				d = {
					top: (c.height() - b.height()) / 2
				};
			d.left < 0 && (d.left = 0), d.top < 0 && (d.top = 0), $("body").addClass("modal-open"), b.css(d)
		}

		function c(a) {
			a.hide(), 0 == $(".modal").filter(":visible").size() && $("body").removeClass("modal-open")
		}

		function d(a, b, c) {
			var d, e, f, g, h, i = function() {
				var j = Date.now() - g;
				b > j ? d = setTimeout(i, b - j) : (d = null, c || (h = a.apply(f, e), f = e = null))
			};
			return function() {
				f = this, e = arguments, g = Date.now();
				var j = c && !d;
				return d || (d = setTimeout(i, b)), j && (h = a.apply(f, e), f = e = null), h
			}
		}
		var e = 99999;
		$(window).resize(d(function() {
			$(".modal").filter(":visible").each(function() {
				b($(this))
			})
		}, 100)), $(document).on("click", '[data-dismiss="modal"]', function(a) {
			a.preventDefault(), $(this).parents(".modal").modal("hide")
		}), $.fn.modal = function(b) {
			$(this).each(function() {
				"show" == b ? (a($(this)), $(this).trigger("show")) : "hide" == b ? (c($(this)), $(this).trigger("hide")) : "destroy" == b && (c($(this)), $(this).trigger("destroy").empty().remove())
			})
		}
	}();
var Uploader = function() {
	function a(b) {
		if (!(this instanceof a)) return new a(b);
		if (!a.isSupport()) throw g("娴忚鍣ㄤ笉鏀寔FormData鎴朅jax涓婁紶"), new Error("娴忚鍣ㄤ笉鏀寔");
		c(b) && (b = {
			trigger: b
		}), b || (b = {});
		var d = {
			trigger: null,
			name: null,
			action: null,
			data: null,
			multiple: null,
			change: null,
			error: null,
			success: null,
			progress: null,
			preprocess: null,
			complete: null
		};
		b && $.extend(d, b);
		var e = $(d.trigger);
		d.action = d.action || e.data("action") || location.href, d.name = d.name || e.attr("name") || e.data("name") || "file", d.data = d.data || f(e.data("data")), null == d.multiple && (d.multiple = e.prop("multiple")), e.prop("multiple", !!d.multiple), this.settings = d, this.queue = [], this.setup()
	}

	function b(a, b) {
		this.file = a, this.active = !1, this.state = 0, this.settings = b
	}

	function c(a) {
		return "[object String]" === Object.prototype.toString.call(a)
	}

	function d(a, b) {
		a = e(a);
		var c = [];
		return $.each(a, function(d, e) {
			b(e, d, a) && c.push(e)
		}), c
	}

	function e(a) {
		return a ? Array.prototype.slice.call(a) : []
	}

	function f(a) {
		if (!a) return {};
		for (var b = {}, c = a.split("&"), d = function(a) {
			return decodeURIComponent(a.replace(/\+/g, " "))
		}, e = 0; e < c.length; e++) {
			var f = c[e].split("="),
				g = d(f[0]);
			b[g] = d(f[1])
		}
		return b
	}

	function g(b) {
		a._debug && ("object" == typeof b && null != b && window.JSON && (b = JSON.stringify(b)), window.console && console.debug("[HTML5Uploader]:" + b))
	}
	return g(navigator.userAgent), a._debug = !1, a.isSupport = function() {
		return !!window.FormData
	}, a.debug = g, a.prototype.setup = function() {
		var a = this,
			b = this.getTrigger();
		return b.on("change", function(c) {
			var e = this.files;
			e = d(e, function(a) {
				return a && "" != a.name
			}), e.length > 0 && (g("宸查€夋嫨瑕佷笂浼犵殑鏂囦欢"), a._files = e || [{
				name: c.target.value
			}], b.val(), a.settings.change ? a.settings.change.call(a, a._files) : a.submit())
		}), this
	}, a.prototype.submit = function() {
		if (this._files.length < 1) return this;
		var a = this.settings,
			c = this;
		return $.each(this._files, function(d, e) {
			var f = new b(e, {
				data: a.data,
				name: a.name,
				action: a.action
			});
			f.preprocess($.proxy(a.preprocess || function(a, b) {
				b(a)
			}, c)), f.success($.proxy(a.success || function() {}, c)), f.progress($.proxy(a.progress || function() {}, c)), f.error($.proxy(a.error || function() {}, c)), f.complete($.proxy(c._nextUpload, c)), c.queue.push(f)
		}), this._upload(), this
	}, a.prototype._upload = function() {
		this.queue.length > 0 && !this.queue[0].active && (g("寮€濮嬩笂浼犳枃浠�(0)"), this.queue[0].upload())
	}, a.prototype._nextUpload = function() {
		if (0 == this.queueCount()) g("鍏ㄩ儴鏂囦欢涓婁紶瀹屾瘯"), this.settings.complete && this.settings.complete.call(this), this.queue = [], this.getTrigger().val("");
		else {
			var a = this.queueTotal() - this.queueCount();
			g("寮€濮嬩笂浼犳枃浠�(" + a + ")"), this.queue[a].upload()
		}
	}, a.prototype.queueCount = function() {
		var a = d(this.queue, function(a) {
			return !a.active
		});
		return a.length
	}, a.prototype.queueTotal = function() {
		return this.queue.length
	}, a.prototype.getTrigger = function() {
		return $(this.settings.trigger)
	}, a.prototype.enable = function() {
		return this.getTrigger().prop("disabled", !1), this.getTrigger().triggerHandler("enable"), this
	}, a.prototype.disable = function() {
		return this.getTrigger().prop("disabled", !0), this.getTrigger().triggerHandler("disable"), this
	}, a.prototype.abort = function() {
		return this.queue.length > 0 && ($.each(this.queue, function(a) {
			a.abort()
		}), this.getTrigger().triggerHandler("abort")), this
	}, a.prototype.on = function(a, b) {
		return this.getTrigger().on(a + ".uploader", $.proxy(b, this)), this
	}, a.prototype.off = function(a) {
		return void 0 == a && (a = ""), this.getTrigger().off(a + ".uploader"), this
	}, a.prototype.once = function(a, b) {
		return this.getTrigger().one(a + ".uploader", $.proxy(b, this)), this
	}, b.prototype.upload = function() {
		if (this.active = !0, this.settings.preprocess) {
			g("璋冪敤涓婁紶棰勫鐞嗗櫒");
			var a = this,
				b = [this.file];
			this.settings.preprocess(b, function(b) {
				g("澶勭悊瀹屾垚锛屽紑濮嬩笂浼 "), b instanceof Array && (b = b[0]), a._upload(b)
			})
		} else this._upload(this.file)
	}, b.prototype._upload = function(a) {
		var b = this,
			c = new FormData,
			d = this.settings.data;
		d = $.isFunction(d) ? d.call(null, a) : d, $.each(d || {}, function(a, b) {
			c.append(a, b)
		}), c.append(this.settings.name, a);
		var e = this.settings.success || function() {},
			f = this.settings.error || function() {},
			h = this.settings.complete || function() {},
			i = this.settings.progress || function() {},
			j = function() {
				var b = new XMLHttpRequest,
					c = navigator.userAgent.toLowerCase(),
					d = "undefined" != typeof WeixinJSBridge || -1 != c.indexOf("micromessenger"),
					e = -1 != c.indexOf("ios") || -1 != c.indexOf("iphone") || -1 != c.indexOf("ipad"),
					f = b.upload && "onprogress" in b.upload && (!d || e),
					h = function(b) {
						var c = 0,
							d = b.loaded || b.position || 0,
							e = b.total;
						b.lengthComputable && (c = Math.ceil(d / e * 100)), g("涓婁紶杩涘害锛�" + c + "%"), i(b, c, d, e, a)
					};
				if (f) b.upload.addEventListener("progress", h, !1);
				else {
					g("娴忚鍣ㄤ笉鏀寔涓婁紶杩涘害锛屼娇鐢ㄦā鎷熻繘搴�");
					var j, k = 0;
					b.addEventListener("loadstart", function() {
						j = setInterval(function() {
							k >= 82 ? j && clearInterval(j) : (k += 3, g("涓婁紶杩涘害锛�" + k + "%"), i(event, k, 0, 0, a))
						}, 500), i(event, 0, 0, null, a)
					}, !1), b.onload = function() {
						j && clearInterval(j), i(event, 100, 0, null, a), k = 0
					}
				}
				return b
			};
		this.ajax = $.ajax({
			url: this.settings.action,
			type: "post",
			dataType: "json",
			processData: !1,
			contentType: !1,
			data: c,
			xhr: j,
			context: this,
			success: function() {
				g("鏂囦欢涓婁紶鎴愬姛锛�"), e.apply(null, arguments)
			},
			error: function(a, b) {
				g("鏂囦欢涓婁紶澶辫触锛�"), "abort" != b && f.apply(null, arguments)
			},
			complete: function() {
				b.state = 1, h.apply(null, arguments)
			}
		})
	}, b.prototype.abort = function() {
		this.ajax && this.ajax.abort()
	}, b.prototype.preprocess = function(a) {
		this.settings.preprocess = a
	}, b.prototype.success = function(a) {
		this.settings.success = a
	}, b.prototype.progress = function(a) {
		this.settings.progress = a
	}, b.prototype.error = function(a) {
		this.settings.error = a
	}, b.prototype.complete = function(a) {
		this.settings.complete = a
	}, a
}();
Uploader.makeThumb = function() {
		"use strict";

		function a(a, c) {
			Uploader.debug("寮€濮嬬敓鎴愬浘鐗囩缉鐣ュ浘");
			var g = {};
			$.extend(g, i, c);
			var h, j = "",
				k = new FileReader,
				l = $("<canvas></canvas>"),
				m = l[0],
				n = m.getContext("2d"),
				o = function(a, b) {
					j = m.toDataURL(g.type);
					var c = {
						width: h.width,
						height: h.height
					};
					if (j = $.trim(j), l.remove(), "" == j) return Uploader.debug("鐢熸垚缂╃暐鍥惧け璐�(璇诲彇canvas鐨刣ataURL澶辫触)"), void($.isFunction(g.error) && g.error("ready dataURL fail", a));
					var d = f(j);
					return d && 0 != d.size ? (Uploader.debug("鐢熸垚缂╃暐鍥炬垚鍔�"), void($.isFunction(g.success) && g.success(d, j, {
						size: c,
						exif: b || null,
						oriDataURL: a.target.result
					}))) : (Uploader.debug("鐢熸垚缂╃暐鍥惧け璐�(鏃犳硶鎶奃ataURL杞负Blob)"), void(g.error && g.error("dataURL to blob error", a)))
				},
				p = new e(a),
				q = function(a, b) {
					var c = b && b.Orientation;
					m.width = g.width, m.height = g.height, g.background && (n.fillStyle = g.background, n.fillRect(0, 0, g.width, g.height)), p.render(m, {
						maxWidth: g.width,
						maxHeight: g.height,
						orientation: c
					}), setTimeout(function() {
						o(a, b)
					}, 100)
				};
			k.onerror = function(a) {
				Uploader.debug("鑾峰彇澶辫触"), $.isFunction(g.error) && g.error("read dataURL fail", a)
			}, k.onload = function(a) {
				Uploader.debug("鑾峰彇鎴愬姛锛屽DataURL杩涜澶勭悊锛屽彇寰楅渶瑕佺殑鏁版嵁");
				var c = a.target,
					e = c.result;
				h = new Image;
				var f;
				h.onload = function() {
					Uploader.debug("鑾峰彇鏁版嵁鎴愬姛锛岀敓鎴愮缉鐣ュ浘涓�..."), q.apply(null, [a, f])
				}, h.onerror = function() {
					Uploader.debug("鑾峰彇鏁版嵁澶辫触"), $.isFunction(g.error) && g.error("read dataURL image error", a)
				};
				var i = e.replace(/^.*?,/, ""),
					j = new b(atob(i));
				f = d.readFromBinaryFile(j), f && (f.hasOwnProperty("MakerNote") && delete f.MakerNote, f.hasOwnProperty("UserComment") && delete f.UserComment), e = e.replace("data:base64", "data:image/jpeg;base64"), h.src = e
			}, Uploader.debug("鑾峰彇鍥剧墖婧愭枃浠禗ataURL"), k.readAsDataURL(a)
		}
		var b = function(a, b, c) {
				var d = a,
					e = b || 0,
					f = 0;
				this.getRawData = function() {
					return d
				}, "string" == typeof a ? (f = c || d.length, this.getByteAt = function(a) {
					return 255 & d.charCodeAt(a + e)
				}, this.getBytesAt = function(a, b) {
					for (var c = [], f = 0; b > f; f++) c[f] = 255 & d.charCodeAt(a + f + e);
					return c
				}) : "unknown" == typeof a && (f = c || IEBinary_getLength(d), this.getByteAt = function(a) {
					return IEBinary_getByteAt(d, a + e)
				}, this.getBytesAt = function(a, b) {
					return new VBArray(IEBinary_getBytesAt(d, a + e, b)).toArray()
				}), this.getLength = function() {
					return f
				}, this.getSByteAt = function(a) {
					var b = this.getByteAt(a);
					return b > 127 ? b - 256 : b
				}, this.getShortAt = function(a, b) {
					var c = b ? (this.getByteAt(a) << 8) + this.getByteAt(a + 1) : (this.getByteAt(a + 1) << 8) + this.getByteAt(a);
					return 0 > c && (c += 65536), c
				}, this.getSShortAt = function(a, b) {
					var c = this.getShortAt(a, b);
					return c > 32767 ? c - 65536 : c
				}, this.getLongAt = function(a, b) {
					var c = this.getByteAt(a),
						d = this.getByteAt(a + 1),
						e = this.getByteAt(a + 2),
						f = this.getByteAt(a + 3),
						g = b ? (((c << 8) + d << 8) + e << 8) + f : (((f << 8) + e << 8) + d << 8) + c;
					return 0 > g && (g += 4294967296), g
				}, this.getSLongAt = function(a, b) {
					var c = this.getLongAt(a, b);
					return c > 2147483647 ? c - 4294967296 : c
				}, this.getStringAt = function(a, b) {
					for (var c = [], d = this.getBytesAt(a, b), e = 0; b > e; e++) c[e] = String.fromCharCode(d[e]);
					return c.join("")
				}, this.getCharAt = function(a) {
					return String.fromCharCode(this.getByteAt(a))
				}, this.toBase64 = function() {
					return window.btoa(d)
				}, this.fromBase64 = function(a) {
					d = window.atob(a)
				}
			},
			c = function() {
				function a() {
					var a = null;
					return window.ActiveXObject ? a = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (a = new XMLHttpRequest), a
				}

				function c(b, c, d) {
					var e = a();
					e ? (c && ("undefined" != typeof e.onload ? e.onload = function() {
						"200" == e.status ? c(this) : d && d(), e = null
					} : e.onreadystatechange = function() {
						4 == e.readyState && ("200" == e.status ? c(this) : d && d(), e = null)
					}), e.open("HEAD", b, !0), e.send(null)) : d && d()
				}

				function d(c, d, e, f, g, h) {
					var i = a();
					if (i) {
						var j = 0;
						f && !g && (j = f[0]);
						var k = 0;
						f && (k = f[1] - f[0] + 1), d && ("undefined" != typeof i.onload ? i.onload = function() {
							"200" == i.status || "206" == i.status || "0" == i.status ? (i.binaryResponse = new b(i.responseText, j, k), i.fileSize = h || i.getResponseHeader("Content-Length"), d(i)) : e && e(), i = null
						} : i.onreadystatechange = function() {
							if (4 == i.readyState) {
								if ("200" == i.status || "206" == i.status || "0" == i.status) {
									var a = {
										status: i.status,
										binaryResponse: new b("unknown" == typeof i.responseBody ? i.responseBody : i.responseText, j, k),
										fileSize: h || i.getResponseHeader("Content-Length")
									};
									d(a)
								} else e && e();
								i = null
							}
						}), i.open("GET", c, !0), i.overrideMimeType && i.overrideMimeType("text/plain; charset=x-user-defined"), f && g && i.setRequestHeader("Range", "bytes=" + f[0] + "-" + f[1]), i.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 1970 00:00:00 GMT"), i.send(null)
					} else e && e()
				}
				return function(a, b, e, f) {
					f ? c(a, function(c) {
						var g, h, i = parseInt(c.getResponseHeader("Content-Length"), 10),
							j = c.getResponseHeader("Accept-Ranges");
						g = f[0], f[0] < 0 && (g += i), h = g + f[1] - 1, d(a, b, e, [g, h], "bytes" == j, i)
					}) : d(a, b, e)
				}
			}(),
			d = {};
		! function() {
			function a(a) {
				return !!a.exifdata
			}

			function b(a, b) {
				c(a.src, function(c) {
					var d = e(c.binaryResponse);
					a.exifdata = d || {}, b && b()
				})
			}

			function e(a) {
				if (255 != a.getByteAt(0) || 216 != a.getByteAt(1)) return !1;
				for (var b = 2, c = a.getLength(); c > b;) {
					if (255 != a.getByteAt(b)) return i && console.log("Not a valid marker at offset " + b + ", found: " + a.getByteAt(b)), !1;
					var d = a.getByteAt(b + 1);
					if (22400 == d) return i && console.log("Found 0xFFE1 marker"), h(a, b + 4, a.getShortAt(b + 2, !0) - 2);
					if (225 == d) return i && console.log("Found 0xFFE1 marker"), h(a, b + 4, a.getShortAt(b + 2, !0) - 2);
					b += 2 + a.getShortAt(b + 2, !0)
				}
			}

			function f(a, b, c, d, e) {
				for (var f = a.getShortAt(c, e), h = {}, j = 0; f > j; j++) {
					var k = c + 12 * j + 2,
						l = d[a.getShortAt(k, e)];
					!l && i && console.log("Unknown tag: " + a.getShortAt(k, e)), h[l] = g(a, k, b, c, e)
				}
				return h
			}

			function g(a, b, c, d, e) {
				var f = a.getShortAt(b + 2, e),
					g = a.getLongAt(b + 4, e),
					h = a.getLongAt(b + 8, e) + c;
				switch (f) {
					case 1:
					case 7:
						if (1 == g) return a.getByteAt(b + 8, e);
						for (var i = g > 4 ? h : b + 8, j = [], k = 0; g > k; k++) j[k] = a.getByteAt(i + k);
						return j;
					case 2:
						var l = g > 4 ? h : b + 8;
						return a.getStringAt(l, g - 1);
					case 3:
						if (1 == g) return a.getShortAt(b + 8, e);
						for (var i = g > 2 ? h : b + 8, j = [], k = 0; g > k; k++) j[k] = a.getShortAt(i + 2 * k, e);
						return j;
					case 4:
						if (1 == g) return a.getLongAt(b + 8, e);
						for (var j = [], k = 0; g > k; k++) j[k] = a.getLongAt(h + 4 * k, e);
						return j;
					case 5:
						if (1 == g) return a.getLongAt(h, e) / a.getLongAt(h + 4, e);
						for (var j = [], k = 0; g > k; k++) j[k] = a.getLongAt(h + 8 * k, e) / a.getLongAt(h + 4 + 8 * k, e);
						return j;
					case 9:
						if (1 == g) return a.getSLongAt(b + 8, e);
						for (var j = [], k = 0; g > k; k++) j[k] = a.getSLongAt(h + 4 * k, e);
						return j;
					case 10:
						if (1 == g) return a.getSLongAt(h, e) / a.getSLongAt(h + 4, e);
						for (var j = [], k = 0; g > k; k++) j[k] = a.getSLongAt(h + 8 * k, e) / a.getSLongAt(h + 4 + 8 * k, e);
						return j
				}
			}

			function h(a, b) {
				if ("Exif" != a.getStringAt(b, 4)) return i && console.log("Not valid EXIF data! " + a.getStringAt(b, 4)), !1;
				var c, e = b + 6;
				if (18761 == a.getShortAt(e)) c = !1;
				else {
					if (19789 != a.getShortAt(e)) return i && console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)"), !1;
					c = !0
				} if (42 != a.getShortAt(e + 2, c)) return i && console.log("Not valid TIFF data! (no 0x002A)"), !1;
				if (8 != a.getLongAt(e + 4, c)) return i && console.log("Not valid TIFF data! (First offset not 8)", a.getShortAt(e + 4, c)), !1;
				var g = f(a, e, e + 8, d.TiffTags, c);
				if (g.ExifIFDPointer) {
					var h = f(a, e, e + g.ExifIFDPointer, d.Tags, c);
					for (var j in h) {
						switch (j) {
							case "LightSource":
							case "Flash":
							case "MeteringMode":
							case "ExposureProgram":
							case "SensingMethod":
							case "SceneCaptureType":
							case "SceneType":
							case "CustomRendered":
							case "WhiteBalance":
							case "GainControl":
							case "Contrast":
							case "Saturation":
							case "Sharpness":
							case "SubjectDistanceRange":
							case "FileSource":
								h[j] = d.StringValues[j][h[j]];
								break;
							case "ExifVersion":
							case "FlashpixVersion":
								h[j] = String.fromCharCode(h[j][0], h[j][1], h[j][2], h[j][3]);
								break;
							case "ComponentsConfiguration":
								h[j] = d.StringValues.Components[h[j][0]] + d.StringValues.Components[h[j][1]] + d.StringValues.Components[h[j][2]] + d.StringValues.Components[h[j][3]]
						}
						g[j] = h[j]
					}
				}
				if (g.GPSInfoIFDPointer) {
					var k = f(a, e, e + g.GPSInfoIFDPointer, d.GPSTags, c);
					for (var j in k) {
						switch (j) {
							case "GPSVersionID":
								k[j] = k[j][0] + "." + k[j][1] + "." + k[j][2] + "." + k[j][3]
						}
						g[j] = k[j]
					}
				}
				return g
			}
			var i = !1;
			d.Tags = {
				36864: "ExifVersion",
				40960: "FlashpixVersion",
				40961: "ColorSpace",
				40962: "PixelXDimension",
				40963: "PixelYDimension",
				37121: "ComponentsConfiguration",
				37122: "CompressedBitsPerPixel",
				37500: "MakerNote",
				37510: "UserComment",
				40964: "RelatedSoundFile",
				36867: "DateTimeOriginal",
				36868: "DateTimeDigitized",
				37520: "SubsecTime",
				37521: "SubsecTimeOriginal",
				37522: "SubsecTimeDigitized",
				33434: "ExposureTime",
				33437: "FNumber",
				34850: "ExposureProgram",
				34852: "SpectralSensitivity",
				34855: "ISOSpeedRatings",
				34856: "OECF",
				37377: "ShutterSpeedValue",
				37378: "ApertureValue",
				37379: "BrightnessValue",
				37380: "ExposureBias",
				37381: "MaxApertureValue",
				37382: "SubjectDistance",
				37383: "MeteringMode",
				37384: "LightSource",
				37385: "Flash",
				37396: "SubjectArea",
				37386: "FocalLength",
				41483: "FlashEnergy",
				41484: "SpatialFrequencyResponse",
				41486: "FocalPlaneXResolution",
				41487: "FocalPlaneYResolution",
				41488: "FocalPlaneResolutionUnit",
				41492: "SubjectLocation",
				41493: "ExposureIndex",
				41495: "SensingMethod",
				41728: "FileSource",
				41729: "SceneType",
				41730: "CFAPattern",
				41985: "CustomRendered",
				41986: "ExposureMode",
				41987: "WhiteBalance",
				41988: "DigitalZoomRation",
				41989: "FocalLengthIn35mmFilm",
				41990: "SceneCaptureType",
				41991: "GainControl",
				41992: "Contrast",
				41993: "Saturation",
				41994: "Sharpness",
				41995: "DeviceSettingDescription",
				41996: "SubjectDistanceRange",
				40965: "InteroperabilityIFDPointer",
				42016: "ImageUniqueID"
			}, d.TiffTags = {
				256: "ImageWidth",
				257: "ImageHeight",
				34665: "ExifIFDPointer",
				34853: "GPSInfoIFDPointer",
				40965: "InteroperabilityIFDPointer",
				258: "BitsPerSample",
				259: "Compression",
				262: "PhotometricInterpretation",
				274: "Orientation",
				277: "SamplesPerPixel",
				284: "PlanarConfiguration",
				530: "YCbCrSubSampling",
				531: "YCbCrPositioning",
				282: "XResolution",
				283: "YResolution",
				296: "ResolutionUnit",
				273: "StripOffsets",
				278: "RowsPerStrip",
				279: "StripByteCounts",
				513: "JPEGInterchangeFormat",
				514: "JPEGInterchangeFormatLength",
				301: "TransferFunction",
				318: "WhitePoint",
				319: "PrimaryChromaticities",
				529: "YCbCrCoefficients",
				532: "ReferenceBlackWhite",
				306: "DateTime",
				270: "ImageDescription",
				271: "Make",
				272: "Model",
				305: "Software",
				315: "Artist",
				33432: "Copyright"
			}, d.GPSTags = {
				0: "GPSVersionID",
				1: "GPSLatitudeRef",
				2: "GPSLatitude",
				3: "GPSLongitudeRef",
				4: "GPSLongitude",
				5: "GPSAltitudeRef",
				6: "GPSAltitude",
				7: "GPSTimeStamp",
				8: "GPSSatellites",
				9: "GPSStatus",
				10: "GPSMeasureMode",
				11: "GPSDOP",
				12: "GPSSpeedRef",
				13: "GPSSpeed",
				14: "GPSTrackRef",
				15: "GPSTrack",
				16: "GPSImgDirectionRef",
				17: "GPSImgDirection",
				18: "GPSMapDatum",
				19: "GPSDestLatitudeRef",
				20: "GPSDestLatitude",
				21: "GPSDestLongitudeRef",
				22: "GPSDestLongitude",
				23: "GPSDestBearingRef",
				24: "GPSDestBearing",
				25: "GPSDestDistanceRef",
				26: "GPSDestDistance",
				27: "GPSProcessingMethod",
				28: "GPSAreaInformation",
				29: "GPSDateStamp",
				30: "GPSDifferential"
			}, d.StringValues = {
				ExposureProgram: {
					0: "Not defined",
					1: "Manual",
					2: "Normal program",
					3: "Aperture priority",
					4: "Shutter priority",
					5: "Creative program",
					6: "Action program",
					7: "Portrait mode",
					8: "Landscape mode"
				},
				MeteringMode: {
					0: "Unknown",
					1: "Average",
					2: "CenterWeightedAverage",
					3: "Spot",
					4: "MultiSpot",
					5: "Pattern",
					6: "Partial",
					255: "Other"
				},
				LightSource: {
					0: "Unknown",
					1: "Daylight",
					2: "Fluorescent",
					3: "Tungsten (incandescent light)",
					4: "Flash",
					9: "Fine weather",
					10: "Cloudy weather",
					11: "Shade",
					12: "Daylight fluorescent (D 5700 - 7100K)",
					13: "Day white fluorescent (N 4600 - 5400K)",
					14: "Cool white fluorescent (W 3900 - 4500K)",
					15: "White fluorescent (WW 3200 - 3700K)",
					17: "Standard light A",
					18: "Standard light B",
					19: "Standard light C",
					20: "D55",
					21: "D65",
					22: "D75",
					23: "D50",
					24: "ISO studio tungsten",
					255: "Other"
				},
				Flash: {
					0: "Flash did not fire",
					1: "Flash fired",
					5: "Strobe return light not detected",
					7: "Strobe return light detected",
					9: "Flash fired, compulsory flash mode",
					13: "Flash fired, compulsory flash mode, return light not detected",
					15: "Flash fired, compulsory flash mode, return light detected",
					16: "Flash did not fire, compulsory flash mode",
					24: "Flash did not fire, auto mode",
					25: "Flash fired, auto mode",
					29: "Flash fired, auto mode, return light not detected",
					31: "Flash fired, auto mode, return light detected",
					32: "No flash function",
					65: "Flash fired, red-eye reduction mode",
					69: "Flash fired, red-eye reduction mode, return light not detected",
					71: "Flash fired, red-eye reduction mode, return light detected",
					73: "Flash fired, compulsory flash mode, red-eye reduction mode",
					77: "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
					79: "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
					89: "Flash fired, auto mode, red-eye reduction mode",
					93: "Flash fired, auto mode, return light not detected, red-eye reduction mode",
					95: "Flash fired, auto mode, return light detected, red-eye reduction mode"
				},
				SensingMethod: {
					1: "Not defined",
					2: "One-chip color area sensor",
					3: "Two-chip color area sensor",
					4: "Three-chip color area sensor",
					5: "Color sequential area sensor",
					7: "Trilinear sensor",
					8: "Color sequential linear sensor"
				},
				SceneCaptureType: {
					0: "Standard",
					1: "Landscape",
					2: "Portrait",
					3: "Night scene"
				},
				SceneType: {
					1: "Directly photographed"
				},
				CustomRendered: {
					0: "Normal process",
					1: "Custom process"
				},
				WhiteBalance: {
					0: "Auto white balance",
					1: "Manual white balance"
				},
				GainControl: {
					0: "None",
					1: "Low gain up",
					2: "High gain up",
					3: "Low gain down",
					4: "High gain down"
				},
				Contrast: {
					0: "Normal",
					1: "Soft",
					2: "Hard"
				},
				Saturation: {
					0: "Normal",
					1: "Low saturation",
					2: "High saturation"
				},
				Sharpness: {
					0: "Normal",
					1: "Soft",
					2: "Hard"
				},
				SubjectDistanceRange: {
					0: "Unknown",
					1: "Macro",
					2: "Close view",
					3: "Distant view"
				},
				FileSource: {
					3: "DSC"
				},
				Components: {
					0: "",
					1: "Y",
					2: "Cb",
					3: "Cr",
					4: "R",
					5: "G",
					6: "B"
				}
			}, d.getData = function(c, d) {
				return c.complete ? (a(c) ? d && d() : b(c, d), !0) : !1
			}, d.getTag = function(b, c) {
				return a(b) ? b.exifdata[c] : void 0
			}, d.getAllTags = function(b) {
				if (!a(b)) return {};
				var c = b.exifdata,
					d = {};
				for (var e in c) c.hasOwnProperty(e) && (d[e] = c[e]);
				return d
			}, d.pretty = function(b) {
				if (!a(b)) return "";
				var c = b.exifdata,
					d = "";
				for (var e in c) c.hasOwnProperty(e) && (d += "object" == typeof c[e] ? e + " : [" + c[e].length + " values]\r\n" : e + " : " + c[e] + "\r\n");
				return d
			}, d.readFromBinaryFile = function(a) {
				return e(a)
			}
		}();
		var e = function() {
				function a(a) {
					var b = a.naturalWidth,
						c = a.naturalHeight;
					if (b * c > 1048576) {
						var d = document.createElement("canvas");
						d.width = d.height = 1;
						var e = d.getContext("2d");
						return e.drawImage(a, -b + 1, 0), 0 === e.getImageData(0, 0, 1, 1).data[3]
					}
					return !1
				}

				function b(a, b, c) {
					var d = document.createElement("canvas");
					d.width = 1, d.height = c;
					var e = d.getContext("2d");
					e.drawImage(a, 0, 0);
					for (var f = e.getImageData(0, 0, 1, c).data, g = 0, h = c, i = c; i > g;) {
						var j = f[4 * (i - 1) + 3];
						0 === j ? h = i : g = i, i = h + g >> 1
					}
					var k = i / c;
					return 0 === k ? 1 : k
				}

				function c(a, b, c) {
					var e = document.createElement("canvas");
					return d(a, e, b, c), e.toDataURL("image/jpeg", b.quality || .8)
				}

				function d(c, d, f, g) {
					var h = c.naturalWidth,
						i = c.naturalHeight,
						j = f.width,
						k = f.height,
						l = d.getContext("2d");
					l.save(), e(d, j, k, f.orientation);
					var m = a(c);
					m && (h /= 2, i /= 2);
					var n = 1024,
						o = document.createElement("canvas");
					o.width = o.height = n;
					for (var p = o.getContext("2d"), q = g ? b(c, h, i) : 1, r = Math.ceil(n * j / h), s = Math.ceil(n * k / i / q), t = 0, u = 0; i > t;) {
						for (var v = 0, w = 0; h > v;) p.clearRect(0, 0, n, n), p.drawImage(c, -v, -t), l.drawImage(o, 0, 0, n, n, w, u, r, s), v += n, w += r;
						t += n, u += s
					}
					l.restore(), o = p = null
				}

				function e(a, b, c, d) {
					switch (d) {
						case 5:
						case 6:
						case 7:
						case 8:
							a.width = c, a.height = b;
							break;
						default:
							a.width = b, a.height = c
					}
					var e = a.getContext("2d");
					switch (d) {
						case 2:
							e.translate(b, 0), e.scale(-1, 1);
							break;
						case 3:
							e.translate(b, c), e.rotate(Math.PI);
							break;
						case 4:
							e.translate(0, c), e.scale(1, -1);
							break;
						case 5:
							e.rotate(.5 * Math.PI), e.scale(1, -1);
							break;
						case 6:
							e.rotate(.5 * Math.PI), e.translate(0, -c);
							break;
						case 7:
							e.rotate(.5 * Math.PI), e.translate(b, -c), e.scale(-1, 1);
							break;
						case 8:
							e.rotate(-.5 * Math.PI), e.translate(-b, 0)
					}
				}

				function f(a) {
					if (a instanceof Blob) {
						var b = new Image,
							c = window.URL && window.URL.createObjectURL ? window.URL : window.webkitURL && window.webkitURL.createObjectURL ? window.webkitURL : null;
						if (!c) throw Error("No createObjectURL function found to create blob url");
						b.src = c.createObjectURL(a), this.blob = a, a = b
					}
					if (!a.naturalWidth && !a.naturalHeight) {
						var d = this;
						a.onload = function() {
							var a = d.imageLoadListeners;
							if (a) {
								d.imageLoadListeners = null;
								for (var b = 0, c = a.length; c > b; b++) a[b]()
							}
						}, this.imageLoadListeners = []
					}
					this.srcImage = a
				}
				return f.prototype.render = function(a, b) {
					if (this.imageLoadListeners) {
						var e = this;
						return void this.imageLoadListeners.push(function() {
							e.render(a, b)
						})
					}
					b = b || {};
					var f = this.srcImage.naturalWidth,
						g = this.srcImage.naturalHeight,
						h = b.width,
						i = b.height,
						j = b.maxWidth,
						k = b.maxHeight,
						l = !this.blob || "image/jpeg" === this.blob.type;
					h && !i ? i = g * h / f << 0 : i && !h ? h = f * i / g << 0 : (h = f, i = g), j && h > j && (h = j, i = g * h / f << 0), k && i > k && (i = k, h = f * i / g << 0);
					var m = {
						width: h,
						height: i
					};
					for (var n in b) m[n] = b[n];
					var o = a.tagName.toLowerCase();
					"img" === o ? a.src = c(this.srcImage, m, l) : "canvas" === o && d(this.srcImage, a, m, l), "function" == typeof this.onrender && this.onrender(a)
				}, f
			}(),
			f = function(a) {
				try {
					for (var b = atob(a.split(",")[1]), c = a.split(",")[0].split(":")[1].split(";")[0], d = new ArrayBuffer(b.length), e = new Uint8Array(d), f = 0; f < b.length; f++) e[f] = b.charCodeAt(f);
					if (window.Blob) return new Blob([d], {
						type: c
					});
					var g = window.BlobBuilder || window.WebKitBlobBuilder,
						h = new g;
					return h.append(d), h.getBlob(c)
				} catch (i) {
					return null
				}
			},
			g = function() {
				var a = window.URL && window.URL.createObjectURL ? window.URL : window.webkitURL && window.webkitURL.createObjectURL ? window.webkitURL : null;
				Uploader.debug("寰俊鐜涓嬶紝灏忕背绯荤粺鐨勬墜鏈虹敓鎴愮殑缂╃暐鍥句笂浼犲瓨鍦ㄩ棶棰�");
				var b = navigator.userAgent.toLowerCase(),
					c = -1 != b.search(/hm\d+/) && "undefined" != typeof WeixinJSBridge;
				return !!window.FileReader && !!a && !!window.Blob && window.Uint8Array && window.ArrayBuffer && !c
			},
			h = function(a) {
				var b = window.URL && window.URL.createObjectURL ? window.URL : window.webkitURL && window.webkitURL.createObjectURL ? window.webkitURL : null;
				return b.createObjectURL(a)
			},
			i = {
				width: 0,
				height: 0,
				fill: !1,
				background: "#fff",
				type: "image/jpeg",
				size: "contain",
				mark: {},
				stretch: !1,
				success: null,
				error: null
			},
			j = /image.*/;
		return function(b, c) {
			if (g())
				if ("" == b.type && b instanceof Blob) {
					var d = new Image;
					d.onload = function() {
						a(b, c)
					}, d.onerror = function() {
						Uploader.debug("鍥剧墖鍔犺浇澶辫触锛孶RL閿欒鎴栦笉鏄浘鐗囩被鍨�"), $.isFunction(c.error) && c.error("the file is not a image")
					}, d.src = h(b)
				} else j.test(b.type) ? a(b, c) : (Uploader.debug("鏂囦欢涓嶆槸鍥剧墖绫诲瀷"), $.isFunction(c.error) && c.error("the file is not a image"));
			else Uploader.debug("娴忚鍣ㄤ笉鏀寔鐢熸垚缂╃暐鍥�"), $.isFunction(c.error) && c.error("your brower isn't support makeThumb")
		}
	}(), $(function() {
		"use strict";

		function a(a, b, c) {
			var d, e, f, g = null,
				h = 0;
			c || (c = {});
			var i = function() {
				h = c.leading === !1 ? 0 : new Date, g = null, f = a.apply(d, e)
			};
			return function() {
				var j = new Date;
				h || c.leading !== !1 || (h = j);
				var k = b - (j - h);
				return d = this, e = arguments, 0 >= k ? (clearTimeout(g), g = null, h = j, f = a.apply(d, e)) : g || c.trailing === !1 || (g = setTimeout(i, k)), f
			}
		}
		$(document).on("click", "[data-toggle]", function(a) {
			a.preventDefault()
		}), $(document).on("click", '[data-toggle="modal"]', function(a) {
			a.preventDefault(), $("#" + $(this).data("target")).modal("show")
		}), $('[data-toggle="dropdown"]').on("click", function(a) {
			a.stopPropagation(), a.preventDefault(), $(this).parents(".dropdown").toggleClass("open")
		}), $(".titlebar .act-secondary").on("click", function(a) {
			a.stopPropagation(), $(this).find('[data-toggle="dropdown"]').trigger("click")
		}), $(".dropdown-menu").click(function(a) {
			a.stopPropagation()
		}), $(document).on("click", function() {
			$(".dropdown").removeClass("open")
		}), $('.titlebar[data-toggle="menu"] .act-secondary').click(function(a) {
			a.preventDefault();
			var b = $(this).parents(".titlebar");
			$(b.data("target")).toggleClass("open"), b.toggleClass("affix"), $(this).parent().find(".toggleTitle").toggle(b.hasClass("affix"))
		}), $(".titlebar .act-primary").click(function(a) {
			a.stopPropagation()
		}), $(".navmenu").click(function() {
			$('.titlebar[data-toggle="menu"] .act-secondary').click()
		}), $(".navmenu").on("click", "li", function(a) {
			a.stopPropagation(), $('.titlebar[data-toggle="menu"] .act-secondary').click()
		}), $(document).on("focus", ".input-group input", function() {
			$(this).parents(".input-group").addClass("active")
		}), $(document).on("blur", ".input-group input", function() {
			$(this).parents(".input-group").removeClass("active")
		}), $(document).on("click", '[data-toggle="open"]', function() {
			$(this).toggleClass("active"), $("#" + $(this).data("target")).toggleClass("open")
		}), $(window).on("scroll", a(function() {
			$(window).scrollTop() >= $(window).height() / 2 ? $(".gotop").show() : $(".gotop").hide(), document.body.scrollHeight - $(window).scrollTop() <= $(window).height() ? $(".page-action-hide").removeClass("page-action") : $(".page-action-hide").addClass("page-action")
		}, 10)), $(".gotop").on("click", function() {
			$(window).scrollTop(0)
		}), $(document).on("click", ".accordion .accordion-hd", function(a) {
			a.preventDefault(), $(this).parent().toggleClass("open"), $.lazy.refresh()
		}), $(".select").each(function() {
			var a = $(this).find(".select-box"),
				b = $(this).find("option").filter(":selected");
			b.size() > 0 && a.text(b.text())
		}), $(document).on("change", ".select select", function() {
			var a = $(this).parents(".select"),
				b = a.find(".select-box"),
				c = $(this).find("option").filter(":selected");
			c.size() > 0 && b.text(c.text()), a.triggerHandler("change", $(this).val())
		}), $("input[type=radio]").each(function() {
			var a = $(this).parents(".radio");
			a.attr("radio", $(this).attr("name")), $(this).prop("checked") && a.addClass("checked")
		}), $(document).on("click", ".radio", function(a) {
			if (a.preventDefault(), !$(this).hasClass("checked")) {
				var b = $(this).attr("radio");
				$("[radio=" + b + "]").removeClass("checked"), $('input[type=radio][name="' + b + '"]').prop("checked", !1), $(this).addClass("checked"), $(this).find("input[type=radio]").prop("checked", !0).trigger("change")
			}
		});
		var b = function() {
			$("input[type=checkbox]").each(function() {
				$(this).prop("checked") ? $(this).parents(".checkbox").addClass("checked") : $(this).parents(".checkbox").removeClass("checked")
			})
		};
		b(), $(document).on("click", ".checkbox", function(a) {
			a.preventDefault(), $(this).toggleClass("checked");
			var b = $(this).hasClass("checked");
			$(this).find("input[type=checkbox]").prop("checked", b).trigger("change")
		}), $(document).on("check", "input[type=checkbox]", function() {
			b()
		}), $(document).on("click", '[data-toggle="tab"]', function(a) {
			a.preventDefault(), $(this).parent().addClass("active").siblings().removeClass("active");
			var b = $($(this).attr("href"));
			b.size() > 0 && b.addClass("active").siblings().removeClass("active")
		}), $('form[data-validate="auto"]').each(function() {
			$(this).validator({
				isErrorOnParent: !0,
				errorCallback: function(a) {
					a.length > 0 && $(window).scrollTop(a[0].$el.offset().top - 50)
				},
				after: function() {
					$(this).find("[type=submit]").prop("disabled", !0)
				}
			})
		}), $(document).on("keydown", "input", function(a) {
			return 13 != a.which
		})
	}),
	function() {
		function a(a, b) {
			a.attr("aria-init") || (a.attr("aria-init", 1), a.append('<div class="switch-cnt"><div class="switch-animate"><div class="icon icon-plus"></div></div></div>'), a.find("input").change(function() {
				$(this).prop("checked") ? a.addClass("on") : a.removeClass("on")
			})), b === !0 || "on" == b ? a.find("input").prop("checked", !0).change() : b === !1 || "off" == b ? a.find("input").prop("checked", !1).change() : a.find("input").trigger("change")
		}
		$.fn.switcher = function(b) {
			$(this).each(function() {
				a($(this), b)
			})
		}, $(function() {
			$(".switch").switcher()
		})
	}();