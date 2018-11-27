function isFunction(fn) {
    return Object.prototype.toString.call(fn) === "[object Function]";
}

function setCookie(key, value, expires) {
    //expires 使用秒
    var objDate = new Date();
    objDate.setTime(objDate.getTime() + Number(expires) * 1000);
    document.cookie = key + "=" + value + ";expires=" + objDate.toGMTString();
}

function getCookie(key) {
    var arrData = document.cookie.match(new RegExp("(^| )" + key + "=([^;]*)(;|$)"));
    if(arrData != null) {
        return(arrData[2]);
    } else {
        return "";
    }
}

function delCookie(key) {
    var objData = new Date();
    objData.setTime(objData.getTime() - 1);
    var value = getCookie(key);
    if(value != null) {
        document.cookie = key + "=" + value + ";expires=" + objData.toGMTString();
    }
}

function setLocalStorage(key, value) {
    localStorage.setItem(key, value);
}

function getLocalStorage(key) {
    return localStorage.getItem(key);
}

function delLocalStorage(key) {
    localStorage.removeItem(key);
}

function clearLocalStorage() {
    localStorage.clear();
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    var strValue = '';
    if (null != r){
        var strValue = decodeURI(r[2]);
    }
    return strValue;
}

//sessionStorage

(function() {
    var initializing = false;
    var superPattern = /xyz/.test(function() {
        xyz;
    }) ? /\b_super\b/ : /.*/;

    Object.subClass = function(properties) {
        var _super = this.prototype;

        initializing = true;
        var proto = new this();
        initializing = false;

        for(var name in properties) {
            proto[name] = typeof properties[name] == "function" &&
                typeof _super[name] == "function" &&
                superPattern.test(properties[name]) ?
                (function(name, fn) {
                    return function() {
                        var tmp = this._super;
                        this._super = _super[name];
                        var ret = fn.apply(this, arguments);
                        this._super = tmp;
                        return ret;
                    };
                })(name, properties[name]) : properties[name];
        }

        function Class() {
            if(!initializing && this.init) {
                this.init.apply(this, arguments);
            }
        }
        Class.prototype = proto;
        Class.constructor = Class;
        Class.subClass = arguments.callee();
        return Class;
    };

    Date.prototype.format = function(format) {
        var o = {
            "M+": this.getMonth() + 1, //month
            "d+": this.getDate(), //day
            "h+": this.getHours(), //hour
            "m+": this.getMinutes(), //minute
            "s+": this.getSeconds(), //second
            "q+": Math.floor((this.getMonth() + 3) / 3), //quarter
            "S": this.getMilliseconds() //millisecond
        }
        if(/(y+)/.test(format)) {
            format = format.replace(RegExp.$1,
                (this.getFullYear() + "").substr(4 - RegExp.$1.length))
        };
        if(/(A+)/.test(format)) {
            var ampm = (this.getHours() >= 12) ? "PM" : "AM";
            format = format.replace(RegExp.$1,
                ampm)
        };
        for(var k in o) {
            if(new RegExp("(" + k + ")").test(format))
                format = format.replace(RegExp.$1,
                    RegExp.$1.length == 1 ? o[k] :
                    ("00" + o[k]).substr(("" + o[k]).length));
        }
        return format;
    }
})();