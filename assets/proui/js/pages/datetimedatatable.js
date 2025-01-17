/*!
 DateTime picker for DataTables.net v1.1.1

 © SpryMedia Ltd, all rights reserved.
 License: MIT datatables.net/license/mit
*/
var $jscomp = $jscomp || {};
$jscomp.scope = {};
$jscomp.findInternal = function (d, f, l) {
  d instanceof String && (d = String(d));
  for (var m = d.length, g = 0; g < m; g++) {
    var q = d[g];
    if (f.call(l, q, g, d)) return { i: g, v: q };
  }
  return { i: -1, v: void 0 };
};
$jscomp.ASSUME_ES5 = !1;
$jscomp.ASSUME_NO_NATIVE_MAP = !1;
$jscomp.ASSUME_NO_NATIVE_SET = !1;
$jscomp.SIMPLE_FROUND_POLYFILL = !1;
$jscomp.ISOLATE_POLYFILLS = !1;
$jscomp.defineProperty =
  $jscomp.ASSUME_ES5 || "function" == typeof Object.defineProperties
    ? Object.defineProperty
    : function (d, f, l) {
        if (d == Array.prototype || d == Object.prototype) return d;
        d[f] = l.value;
        return d;
      };
$jscomp.getGlobal = function (d) {
  d = [
    "object" == typeof globalThis && globalThis,
    d,
    "object" == typeof window && window,
    "object" == typeof self && self,
    "object" == typeof global && global,
  ];
  for (var f = 0; f < d.length; ++f) {
    var l = d[f];
    if (l && l.Math == Math) return l;
  }
  throw Error("Cannot find global object");
};
$jscomp.global = $jscomp.getGlobal(this);
$jscomp.IS_SYMBOL_NATIVE =
  "function" === typeof Symbol && "symbol" === typeof Symbol("x");
$jscomp.TRUST_ES6_POLYFILLS =
  !$jscomp.ISOLATE_POLYFILLS || $jscomp.IS_SYMBOL_NATIVE;
$jscomp.polyfills = {};
$jscomp.propertyToPolyfillSymbol = {};
$jscomp.POLYFILL_PREFIX = "$jscp$";
var $jscomp$lookupPolyfilledValue = function (d, f) {
  var l = $jscomp.propertyToPolyfillSymbol[f];
  if (null == l) return d[f];
  l = d[l];
  return void 0 !== l ? l : d[f];
};
$jscomp.polyfill = function (d, f, l, m) {
  f &&
    ($jscomp.ISOLATE_POLYFILLS
      ? $jscomp.polyfillIsolated(d, f, l, m)
      : $jscomp.polyfillUnisolated(d, f, l, m));
};
$jscomp.polyfillUnisolated = function (d, f, l, m) {
  l = $jscomp.global;
  d = d.split(".");
  for (m = 0; m < d.length - 1; m++) {
    var g = d[m];
    if (!(g in l)) return;
    l = l[g];
  }
  d = d[d.length - 1];
  m = l[d];
  f = f(m);
  f != m &&
    null != f &&
    $jscomp.defineProperty(l, d, { configurable: !0, writable: !0, value: f });
};
$jscomp.polyfillIsolated = function (d, f, l, m) {
  var g = d.split(".");
  d = 1 === g.length;
  m = g[0];
  m = !d && m in $jscomp.polyfills ? $jscomp.polyfills : $jscomp.global;
  for (var q = 0; q < g.length - 1; q++) {
    var a = g[q];
    if (!(a in m)) return;
    m = m[a];
  }
  g = g[g.length - 1];
  l = $jscomp.IS_SYMBOL_NATIVE && "es6" === l ? m[g] : null;
  f = f(l);
  null != f &&
    (d
      ? $jscomp.defineProperty($jscomp.polyfills, g, {
          configurable: !0,
          writable: !0,
          value: f,
        })
      : f !== l &&
        (($jscomp.propertyToPolyfillSymbol[g] = $jscomp.IS_SYMBOL_NATIVE
          ? $jscomp.global.Symbol(g)
          : $jscomp.POLYFILL_PREFIX + g),
        (g = $jscomp.propertyToPolyfillSymbol[g]),
        $jscomp.defineProperty(m, g, {
          configurable: !0,
          writable: !0,
          value: f,
        })));
};
$jscomp.polyfill(
  "Array.prototype.find",
  function (d) {
    return d
      ? d
      : function (f, l) {
          return $jscomp.findInternal(this, f, l).v;
        };
  },
  "es6",
  "es3"
);
(function (d) {
  "function" === typeof define && define.amd
    ? define(["jquery"], function (f) {
        return d(f, window, document);
      })
    : "object" === typeof exports
    ? (module.exports = function (f, l) {
        f || (f = window);
        return d(l, f, f.document);
      })
    : d(jQuery, window, document);
})(function (d, f, l, m) {
  var g,
    q = function (a, b) {
      "undefined" === typeof g &&
        (g = f.moment
          ? f.moment
          : f.dayjs
          ? f.dayjs
          : f.luxon
          ? f.luxon
          : null);
      this.c = d.extend(!0, {}, q.defaults, b);
      b = this.c.classPrefix;
      var c = this.c.i18n;
      if (!g && "YYYY-MM-DD" !== this.c.format)
        throw "DateTime: Without momentjs, dayjs or luxon only the format 'YYYY-MM-DD' can be used";
      "string" === typeof this.c.minDate &&
        (this.c.minDate = new Date(this.c.minDate));
      "string" === typeof this.c.maxDate &&
        (this.c.maxDate = new Date(this.c.maxDate));
      c = d(
        '<div class="' +
          b +
          '"><div class="' +
          b +
          '-date"><div class="' +
          b +
          '-title"><div class="' +
          b +
          '-iconLeft"><button title="' +
          c.previous +
          '">' +
          c.previous +
          '</button></div><div class="' +
          b +
          '-iconRight"><button title="' +
          c.next +
          '">' +
          c.next +
          '</button></div><div class="' +
          b +
          '-label"><span></span><select class="' +
          b +
          '-month"></select></div><div class="' +
          b +
          '-label"><span></span><select class="' +
          b +
          '-year"></select></div></div><div class="' +
          b +
          '-buttons"><a class="' +
          b +
          '-clear">' +
          c.clear +
          '</a><a class="' +
          b +
          '-today">' +
          c.today +
          '</a></div><div class="' +
          b +
          '-calendar"></div></div><div class="' +
          b +
          '-time"><div class="' +
          b +
          '-hours"></div><div class="' +
          b +
          '-minutes"></div><div class="' +
          b +
          '-seconds"></div></div><div class="' +
          b +
          '-error"></div></div>'
      );
      this.dom = {
        container: c,
        date: c.find("." + b + "-date"),
        title: c.find("." + b + "-title"),
        calendar: c.find("." + b + "-calendar"),
        time: c.find("." + b + "-time"),
        error: c.find("." + b + "-error"),
        buttons: c.find("." + b + "-buttons"),
        clear: c.find("." + b + "-clear"),
        today: c.find("." + b + "-today"),
        input: d(a),
      };
      this.s = {
        d: null,
        display: null,
        minutesRange: null,
        secondsRange: null,
        namespace: "dateime-" + q._instance++,
        parts: {
          date: null !== this.c.format.match(/[YMD]|L(?!T)|l/),
          time: null !== this.c.format.match(/[Hhm]|LT|LTS/),
          seconds: -1 !== this.c.format.indexOf("s"),
          hours12: null !== this.c.format.match(/[haA]/),
        },
      };
      this.dom.container
        .append(this.dom.date)
        .append(this.dom.time)
        .append(this.dom.error);
      this.dom.date
        .append(this.dom.title)
        .append(this.dom.buttons)
        .append(this.dom.calendar);
      this._constructor();
    };
  d.extend(q.prototype, {
    destroy: function () {
      this._hide(!0);
      this.dom.container.off().empty();
      this.dom.input.removeAttr("autocomplete").off(".datetime");
    },
    errorMsg: function (a) {
      var b = this.dom.error;
      a ? b.html(a) : b.empty();
      return this;
    },
    hide: function () {
      this._hide();
      return this;
    },
    max: function (a) {
      this.c.maxDate = "string" === typeof a ? new Date(a) : a;
      this._optionsTitle();
      this._setCalander();
      return this;
    },
    min: function (a) {
      this.c.minDate = "string" === typeof a ? new Date(a) : a;
      this._optionsTitle();
      this._setCalander();
      return this;
    },
    owns: function (a) {
      return 0 < d(a).parents().filter(this.dom.container).length;
    },
    val: function (a, b) {
      if (a === m) return this.s.d;
      if (a instanceof Date) this.s.d = this._dateToUtc(a);
      else if (null === a || "" === a) this.s.d = null;
      else if ("--now" === a) this.s.d = new Date();
      else if ("string" === typeof a)
        if (g && g == f.luxon) {
          var c = g.DateTime.fromFormat(a, this.c.format);
          this.s.d = c.isValid ? c.toJSDate() : null;
        } else
          g
            ? ((c = g.utc(a, this.c.format, this.c.locale, this.c.strict)),
              (this.s.d = c.isValid() ? c.toDate() : null))
            : ((c = a.match(/(\d{4})\-(\d{2})\-(\d{2})/)),
              (this.s.d = c ? new Date(Date.UTC(c[1], c[2] - 1, c[3])) : null));
      if (b || b === m) this.s.d ? this._writeOutput() : this.dom.input.val(a);
      this.s.display = this.s.d ? new Date(this.s.d.toString()) : new Date();
      this.s.display.setUTCDate(1);
      this._setTitle();
      this._setCalander();
      this._setTime();
      return this;
    },
    _constructor: function () {
      var a = this,
        b = this.c.classPrefix,
        c = this.dom.input.val(),
        k = function () {
          var e = a.dom.input.val();
          e !== c && (a.c.onChange.call(a, e, a.s.d, a.dom.input), (c = e));
        };
      this.s.parts.date || this.dom.date.css("display", "none");
      this.s.parts.time || this.dom.time.css("display", "none");
      this.s.parts.seconds ||
        (this.dom.time.children("div." + b + "-seconds").remove(),
        this.dom.time.children("span").eq(1).remove());
      this.c.buttons.clear || this.dom.clear.css("display", "none");
      this.c.buttons.today || this.dom.today.css("display", "none");
      this._optionsTitle();
      d(l).on("i18n.dt", function (e, h) {
        h.oLanguage.datetime &&
          (d.extend(!0, a.c.i18n, h.oLanguage.datetime), a._optionsTitle());
      });
      "hidden" === this.dom.input.attr("type") &&
        (this.dom.container.addClass("inline"),
        (this.c.attachTo = "input"),
        this.val(this.dom.input.val(), !1),
        this._show());
      c && this.val(c, !1);
      this.dom.input
        .attr("autocomplete", "off")
        .on("focus.datetime click.datetime", function () {
          a.dom.container.is(":visible") ||
            a.dom.input.is(":disabled") ||
            (a.val(a.dom.input.val(), !1), a._show());
        })
        .on("keyup.datetime", function () {
          a.dom.container.is(":visible") && a.val(a.dom.input.val(), !1);
        });
      this.dom.container
        .on("change", "select", function () {
          var e = d(this),
            h = e.val();
          e.hasClass(b + "-month")
            ? (a._correctMonth(a.s.display, h), a._setTitle(), a._setCalander())
            : e.hasClass(b + "-year")
            ? (a.s.display.setUTCFullYear(h), a._setTitle(), a._setCalander())
            : e.hasClass(b + "-hours") || e.hasClass(b + "-ampm")
            ? (a.s.parts.hours12
                ? ((e =
                    1 *
                    d(a.dom.container)
                      .find("." + b + "-hours")
                      .val()),
                  (h =
                    "pm" ===
                    d(a.dom.container)
                      .find("." + b + "-ampm")
                      .val()),
                  a.s.d.setUTCHours(
                    12 !== e || h ? (h && 12 !== e ? e + 12 : e) : 0
                  ))
                : a.s.d.setUTCHours(h),
              a._setTime(),
              a._writeOutput(!0),
              k())
            : e.hasClass(b + "-minutes")
            ? (a.s.d.setUTCMinutes(h), a._setTime(), a._writeOutput(!0), k())
            : e.hasClass(b + "-seconds") &&
              (a.s.d.setSeconds(h), a._setTime(), a._writeOutput(!0), k());
          a.dom.input.focus();
          a._position();
        })
        .on("click", function (e) {
          var h = a.s.d;
          h = e.target.nodeName.toLowerCase();
          var r = "span" === h ? e.target.parentNode : e.target;
          h = r.nodeName.toLowerCase();
          if ("select" !== h)
            if (
              (e.stopPropagation(),
              "a" === h &&
                (e.preventDefault(),
                d(r).hasClass(b + "-clear")
                  ? ((a.s.d = null),
                    a.dom.input.val(""),
                    a._writeOutput(),
                    a._setCalander(),
                    a._setTime(),
                    k())
                  : d(r).hasClass(b + "-today") &&
                    ((a.s.display = new Date()),
                    a._setTitle(),
                    a._setCalander())),
              "button" === h)
            ) {
              var p = d(r);
              e = p.parent();
              if (e.hasClass("disabled") && !e.hasClass("range")) p.blur();
              else if (e.hasClass(b + "-iconLeft"))
                a.s.display.setUTCMonth(a.s.display.getUTCMonth() - 1),
                  a._setTitle(),
                  a._setCalander(),
                  a.dom.input.focus();
              else if (e.hasClass(b + "-iconRight"))
                a._correctMonth(a.s.display, a.s.display.getUTCMonth() + 1),
                  a._setTitle(),
                  a._setCalander(),
                  a.dom.input.focus();
              else {
                if (p.parents("." + b + "-time").length) {
                  r = p.data("value");
                  p = p.data("unit");
                  h = a._needValue();
                  if ("minutes" === p) {
                    if (e.hasClass("disabled") && e.hasClass("range")) {
                      a.s.minutesRange = r;
                      a._setTime();
                      return;
                    }
                    a.s.minutesRange = null;
                  }
                  if ("seconds" === p) {
                    if (e.hasClass("disabled") && e.hasClass("range")) {
                      a.s.secondsRange = r;
                      a._setTime();
                      return;
                    }
                    a.s.secondsRange = null;
                  }
                  if ("am" === r)
                    if (12 <= h.getUTCHours()) r = h.getUTCHours() - 12;
                    else return;
                  else if ("pm" === r)
                    if (12 > h.getUTCHours()) r = h.getUTCHours() + 12;
                    else return;
                  h[
                    "hours" === p
                      ? "setUTCHours"
                      : "minutes" === p
                      ? "setUTCMinutes"
                      : "setSeconds"
                  ](r);
                  a._setTime();
                  a._writeOutput(!0);
                } else
                  (h = a._needValue()),
                    h.setUTCDate(1),
                    h.setUTCFullYear(p.data("year")),
                    h.setUTCMonth(p.data("month")),
                    h.setUTCDate(p.data("day")),
                    a._writeOutput(!0),
                    a.s.parts.time
                      ? a._setCalander()
                      : setTimeout(function () {
                          a._hide();
                        }, 10);
                k();
              }
            } else a.dom.input.focus();
        });
    },
    _compareDates: function (a, b) {
      return g && g == f.luxon
        ? g.DateTime.fromJSDate(a).toISODate() ===
            g.DateTime.fromJSDate(b).toISODate()
        : this._dateToUtcString(a) === this._dateToUtcString(b);
    },
    _correctMonth: function (a, b) {
      var c = this._daysInMonth(a.getUTCFullYear(), b),
        k = a.getUTCDate() > c;
      a.setUTCMonth(b);
      k && (a.setUTCDate(c), a.setUTCMonth(b));
    },
    _daysInMonth: function (a, b) {
      return [
        31,
        0 !== a % 4 || (0 === a % 100 && 0 !== a % 400) ? 28 : 29,
        31,
        30,
        31,
        30,
        31,
        31,
        30,
        31,
        30,
        31,
      ][b];
    },
    _dateToUtc: function (a) {
      return new Date(
        Date.UTC(
          a.getFullYear(),
          a.getMonth(),
          a.getDate(),
          a.getHours(),
          a.getMinutes(),
          a.getSeconds()
        )
      );
    },
    _dateToUtcString: function (a) {
      return g && g == f.luxon
        ? g.DateTime.fromJSDate(a).toISODate()
        : a.getUTCFullYear() +
            "-" +
            this._pad(a.getUTCMonth() + 1) +
            "-" +
            this._pad(a.getUTCDate());
    },
    _hide: function (a) {
      if (a || "hidden" !== this.dom.input.attr("type"))
        (a = this.s.namespace),
          this.dom.container.detach(),
          d(f).off("." + a),
          d(l).off("keydown." + a),
          d("div.dataTables_scrollBody").off("scroll." + a),
          d("div.DTE_Body_Content").off("scroll." + a),
          d("body").off("click." + a);
    },
    _hours24To12: function (a) {
      return 0 === a ? 12 : 12 < a ? a - 12 : a;
    },
    _htmlDay: function (a) {
      if (a.empty) return '<td class="empty"></td>';
      var b = ["selectable"],
        c = this.c.classPrefix;
      a.disabled && b.push("disabled");
      a.today && b.push("now");
      a.selected && b.push("selected");
      return (
        '<td data-day="' +
        a.day +
        '" class="' +
        b.join(" ") +
        '"><button class="' +
        c +
        "-button " +
        c +
        '-day" type="button" data-year="' +
        a.year +
        '" data-month="' +
        a.month +
        '" data-day="' +
        a.day +
        '"><span>' +
        a.day +
        "</span></button></td>"
      );
    },
    _htmlMonth: function (a, b) {
      var c = this._dateToUtc(new Date()),
        k = this._daysInMonth(a, b),
        e = new Date(Date.UTC(a, b, 1)).getUTCDay(),
        h = [],
        r = [];
      0 < this.c.firstDay && ((e -= this.c.firstDay), 0 > e && (e += 7));
      for (var p = k + e, u = p; 7 < u; ) u -= 7;
      p += 7 - u;
      var w = this.c.minDate;
      u = this.c.maxDate;
      w && (w.setUTCHours(0), w.setUTCMinutes(0), w.setSeconds(0));
      u && (u.setUTCHours(23), u.setUTCMinutes(59), u.setSeconds(59));
      for (var n = 0, t = 0; n < p; n++) {
        var x = new Date(Date.UTC(a, b, 1 + (n - e))),
          A = this.s.d ? this._compareDates(x, this.s.d) : !1,
          v = this._compareDates(x, c),
          B = n < e || n >= k + e,
          z = (w && x < w) || (u && x > u),
          y = this.c.disableDays;
        Array.isArray(y) && -1 !== d.inArray(x.getUTCDay(), y)
          ? (z = !0)
          : "function" === typeof y && !0 === y(x) && (z = !0);
        r.push(
          this._htmlDay({
            day: 1 + (n - e),
            month: b,
            year: a,
            selected: A,
            today: v,
            disabled: z,
            empty: B,
          })
        );
        7 === ++t &&
          (this.c.showWeekNumber &&
            r.unshift(this._htmlWeekOfYear(n - e, b, a)),
          h.push("<tr>" + r.join("") + "</tr>"),
          (r = []),
          (t = 0));
      }
      c = this.c.classPrefix;
      k = c + "-table";
      this.c.showWeekNumber && (k += " weekNumber");
      w &&
        ((w = w >= new Date(Date.UTC(a, b, 1, 0, 0, 0))),
        this.dom.title
          .find("div." + c + "-iconLeft")
          .css("display", w ? "none" : "block"));
      u &&
        ((a = u < new Date(Date.UTC(a, b + 1, 1, 0, 0, 0))),
        this.dom.title
          .find("div." + c + "-iconRight")
          .css("display", a ? "none" : "block"));
      return (
        '<table class="' +
        k +
        '"><thead>' +
        this._htmlMonthHead() +
        "</thead><tbody>" +
        h.join("") +
        "</tbody></table>"
      );
    },
    _htmlMonthHead: function () {
      var a = [],
        b = this.c.firstDay,
        c = this.c.i18n,
        k = function (h) {
          for (h += b; 7 <= h; ) h -= 7;
          return c.weekdays[h];
        };
      this.c.showWeekNumber && a.push("<th></th>");
      for (var e = 0; 7 > e; e++) a.push("<th>" + k(e) + "</th>");
      return a.join("");
    },
    _htmlWeekOfYear: function (a, b, c) {
      a = new Date(c, b, a, 0, 0, 0, 0);
      a.setDate(a.getDate() + 4 - (a.getDay() || 7));
      return (
        '<td class="' +
        this.c.classPrefix +
        '-week">' +
        Math.ceil(((a - new Date(c, 0, 1)) / 864e5 + 1) / 7) +
        "</td>"
      );
    },
    _needValue: function () {
      this.s.d || (this.s.d = this._dateToUtc(new Date()));
      return this.s.d;
    },
    _options: function (a, b, c) {
      c || (c = b);
      a = this.dom.container.find("select." + this.c.classPrefix + "-" + a);
      a.empty();
      for (var k = 0, e = b.length; k < e; k++)
        a.append('<option value="' + b[k] + '">' + c[k] + "</option>");
    },
    _optionSet: function (a, b) {
      var c = this.dom.container.find("select." + this.c.classPrefix + "-" + a);
      a = c.parent().children("span");
      c.val(b);
      b = c.find("option:selected");
      a.html(0 !== b.length ? b.text() : this.c.i18n.unknown);
    },
    _optionsTime: function (a, b, c, k, e) {
      var h = this.c.classPrefix,
        r = this.dom.container.find("div." + h + "-" + a),
        p =
          12 === b
            ? function (v) {
                return v;
              }
            : this._pad;
      h = this.c.classPrefix;
      var u = h + "-table",
        w = this.c.i18n;
      if (r.length) {
        var n = "";
        var t = 10;
        var x = function (v, B, z) {
          12 === b &&
            "number" === typeof v &&
            (12 <= c && (v += 12), 12 == v ? (v = 0) : 24 == v && (v = 12));
          var y =
            c === v || ("am" === v && 12 > c) || ("pm" === v && 12 <= c)
              ? "selected"
              : "";
          k && -1 === d.inArray(v, k) && (y += " disabled");
          z && (y += " " + z);
          return (
            '<td class="selectable ' +
            y +
            '"><button class="' +
            h +
            "-button " +
            h +
            '-day" type="button" data-unit="' +
            a +
            '" data-value="' +
            v +
            '"><span>' +
            B +
            "</span></button></td>"
          );
        };
        if (12 === b) {
          n += "<tr>";
          for (e = 1; 6 >= e; e++) n += x(e, p(e));
          n += x("am", w.amPm[0]);
          n += "</tr><tr>";
          for (e = 7; 12 >= e; e++) n += x(e, p(e));
          n += x("pm", w.amPm[1]);
          n += "</tr>";
          t = 7;
        } else {
          if (24 === b) {
            var A = 0;
            for (t = 0; 4 > t; t++) {
              n += "<tr>";
              for (e = 0; 6 > e; e++) (n += x(A, p(A))), A++;
              n += "</tr>";
            }
          } else {
            n += "<tr>";
            for (t = 0; 60 > t; t += 10) n += x(t, p(t), "range");
            e = null !== e ? e : 10 * Math.floor(c / 10);
            n =
              n +
              '</tr></tbody></thead><table class="' +
              (u + " " + u + '-nospace"><tbody><tr>');
            for (t = e + 1; t < e + 10; t++) n += x(t, p(t));
            n += "</tr>";
          }
          t = 6;
        }
        r.empty().append(
          '<table class="' +
            u +
            '"><thead><tr><th colspan="' +
            t +
            '">' +
            w[a] +
            "</th></tr></thead><tbody>" +
            n +
            "</tbody></table>"
        );
      }
    },
    _optionsTitle: function () {
      var a = this.c.i18n,
        b = this.c.minDate,
        c = this.c.maxDate;
      b = b ? b.getFullYear() : null;
      c = c ? c.getFullYear() : null;
      b = null !== b ? b : new Date().getFullYear() - this.c.yearRange;
      c = null !== c ? c : new Date().getFullYear() + this.c.yearRange;
      this._options("month", this._range(0, 11), a.months);
      this._options("year", this._range(b, c));
    },
    _pad: function (a) {
      return 10 > a ? "0" + a : a;
    },
    _position: function () {
      var a =
          "input" === this.c.attachTo
            ? this.dom.input.position()
            : this.dom.input.offset(),
        b = this.dom.container,
        c = this.dom.input.outerHeight();
      if (b.hasClass("inline")) b.insertAfter(this.dom.input);
      else {
        this.s.parts.date && this.s.parts.time && 550 < d(f).width()
          ? b.addClass("horizontal")
          : b.removeClass("horizontal");
        "input" === this.c.attachTo
          ? b.css({ top: a.top + c, left: a.left }).insertAfter(this.dom.input)
          : b.css({ top: a.top + c, left: a.left }).appendTo("body");
        var k = b.outerHeight(),
          e = b.outerWidth(),
          h = d(f).scrollTop();
        a.top + c + k - h > d(f).height() &&
          ((c = a.top - k), b.css("top", 0 > c ? 0 : c));
        e + a.left > d(f).width() &&
          ((a = d(f).width() - e),
          "input" === this.c.attachTo &&
            (a -= d(b).offsetParent().offset().left),
          b.css("left", 0 > a ? 0 : a));
      }
    },
    _range: function (a, b, c) {
      var k = [];
      for (c || (c = 1); a <= b; a += c) k.push(a);
      return k;
    },
    _setCalander: function () {
      this.s.display &&
        this.dom.calendar
          .empty()
          .append(
            this._htmlMonth(
              this.s.display.getUTCFullYear(),
              this.s.display.getUTCMonth()
            )
          );
    },
    _setTitle: function () {
      this._optionSet("month", this.s.display.getUTCMonth());
      this._optionSet("year", this.s.display.getUTCFullYear());
    },
    _setTime: function () {
      var a = this,
        b = this.s.d,
        c = null;
      g && g == f.luxon && (c = g.DateTime.fromJSDate(b));
      var k = null != c ? c.hour : b ? b.getUTCHours() : 0,
        e = function (h) {
          return a.c[h + "Available"]
            ? a.c[h + "Available"]
            : a._range(0, 59, a.c[h + "Increment"]);
        };
      this._optionsTime(
        "hours",
        this.s.parts.hours12 ? 12 : 24,
        k,
        this.c.hoursAvailable
      );
      this._optionsTime(
        "minutes",
        60,
        null != c ? c.minute : b ? b.getUTCMinutes() : 0,
        e("minutes"),
        this.s.minutesRange
      );
      this._optionsTime(
        "seconds",
        60,
        null != c ? c.second : b ? b.getSeconds() : 0,
        e("seconds"),
        this.s.secondsRange
      );
    },
    _show: function () {
      var a = this,
        b = this.s.namespace;
      this._position();
      d(f).on("scroll." + b + " resize." + b, function () {
        a._position();
      });
      d("div.DTE_Body_Content").on("scroll." + b, function () {
        a._position();
      });
      d("div.dataTables_scrollBody").on("scroll." + b, function () {
        a._position();
      });
      var c = this.dom.input[0].offsetParent;
      if (c !== l.body)
        d(c).on("scroll." + b, function () {
          a._position();
        });
      d(l).on("keydown." + b, function (k) {
        (9 !== k.keyCode && 27 !== k.keyCode && 13 !== k.keyCode) || a._hide();
      });
      setTimeout(function () {
        d("body").on("click." + b, function (k) {
          d(k.target).parents().filter(a.dom.container).length ||
            k.target === a.dom.input[0] ||
            a._hide();
        });
      }, 10);
    },
    _writeOutput: function (a) {
      var b = this.s.d,
        c = "";
      b &&
        (c =
          g && g == f.luxon
            ? g.DateTime.fromJSDate(this.s.d).toFormat(this.c.format)
            : g
            ? g.utc(b, m, this.c.locale, this.c.strict).format(this.c.format)
            : b.getUTCFullYear() +
              "-" +
              this._pad(b.getUTCMonth() + 1) +
              "-" +
              this._pad(b.getUTCDate()));
      this.dom.input.val(c).trigger("change", { write: b });
      "hidden" === this.dom.input.attr("type") && this.val(c, !1);
      a && this.dom.input.focus();
    },
  });
  q.use = function (a) {
    g = a;
  };
  q._instance = 0;
  q.defaults = {
    attachTo: "body",
    buttons: { clear: !1, today: !1 },
    classPrefix: "dt-datetime",
    disableDays: null,
    firstDay: 1,
    format: "YYYY-MM-DD",
    hoursAvailable: null,
    i18n: {
      clear: "Clear",
      previous: "Previous",
      next: "Next",
      months:
        "January February March April May June July August September October November December".split(
          " "
        ),
      weekdays: "Sun Mon Tue Wed Thu Fri Sat".split(" "),
      amPm: ["am", "pm"],
      hours: "Hour",
      minutes: "Minute",
      seconds: "Second",
      unknown: "-",
      today: "Today",
    },
    maxDate: null,
    minDate: null,
    minutesAvailable: null,
    minutesIncrement: 1,
    strict: !0,
    locale: "en",
    onChange: function () {},
    secondsAvailable: null,
    secondsIncrement: 1,
    showWeekNumber: !1,
    yearRange: 25,
  };
  q.version = "1.1.1";
  f.DateTime || (f.DateTime = q);
  d.fn.dtDateTime = function (a) {
    return this.each(function () {
      new q(this, a);
    });
  };
  d.fn.dataTable &&
    ((d.fn.dataTable.DateTime = q),
    (d.fn.DataTable.DateTime = q),
    d.fn.dataTable.Editor && (d.fn.dataTable.Editor.DateTime = q));
  return q;
});
