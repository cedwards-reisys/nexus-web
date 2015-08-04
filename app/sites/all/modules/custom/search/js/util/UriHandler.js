
(function (global) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Util requires FS');
    }

    var UriHandler = FS.Class.extend({

        options:{
            mode:'php',
            key:["source", "scheme", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"],
            parser:{
                php:/^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
                strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
                loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
            }
        },

        init:function (str, mode) {
            if (typeof mode != "undefined") {
                this.options.mode = mode;
            }
            $.extend(this, this.parseURI(str));
        },

        addParam:function (key, value) {
            this.queryObject[key] = value;
            return this;
        },

        getParam:function (key) {
            if (typeof this.queryObject[key] !== "undefined") {
                return this.queryObject[key];
            } else {
                return null;
            }
        },

        removeParam:function (key) {
            delete this.queryObject[key];
            return this;
        },

        // rebuild uri based on this object
        // not complete but should work for now  FIXME
        getURI:function () {
            var uri = this.path + this.getQueryString(this.queryObject);
            if ( this.host ) {
                var host = this.port ? (this.host + ":" + this.port) : this.host;
                uri = this.scheme + '://' + host + uri;
            }
            return uri;
        },

        redirect:function () {
            if(window.event) {
                window.event.returnValue = false;
            }
            window.location.href = this.getURI();
        },

        parseURI:function (str) {

            if (typeof str == "undefined") {
                str = location.href;
            }

            var o = this.options,
                m = o.parser[o.mode].exec(str),
                uri = {},
                i = 14;

            while (i--) uri[o.key[i]] = m[i] || "";

            // query
            uri["queryObject"] = this.getQueryObject(uri["query"]);

            return uri;
        },

        mergeQueryString:function (query) {
            var merged_query = this.getQueryString().substring(1) + '&' + query.substring(1);
            this.queryObject = this.getQueryObject(merged_query);
            return this;
        },

        // Deserialize a params string into an object, optionally coercing numbers,
        // booleans, null and undefined values;
        //
        // Usage:
        //
        // > queryStringToObject( params [, coerce ] );
        //
        // Arguments:
        //
        //  params - (String) A params string to be parsed.
        //  coerce - (Boolean) If true, coerces any numbers or true, false, null, and
        //    undefined to their actual value. Defaults to false if omitted.
        //
        // Returns:
        //
        //  (Object) An object representing the deserialized params string.
        getQueryObject:function (params, coerce) {
            var obj = {},
                coerce_types = { 'true':!0, 'false':!1, 'null':null };
            // Iterate over all name=value pairs.
            $.each(params.replace(/\+/g, ' ').split('&'), function (j, v) {
                var param = v.split('='),
                    key = decodeURIComponent(param[0]),
                    val,
                    cur = obj,
                    i = 0,

                // If key is more complex than 'foo', like 'a[]' or 'a[b][c]', split it
                // into its component parts.
                    keys = key.split(']['),
                    keys_last = keys.length - 1;

                // If the first keys part contains [ and the last ends with ], then []
                // are correctly balanced.
                if (/\[/.test(keys[0]) && /\]$/.test(keys[ keys_last ])) {
                    // Remove the trailing ] from the last keys part.
                    keys[ keys_last ] = keys[ keys_last ].replace(/\]$/, '');

                    // Split first keys part into two parts on the [ and add them back onto
                    // the beginning of the keys array.
                    keys = keys.shift().split('[').concat(keys);

                    keys_last = keys.length - 1;
                } else {
                    // Basic 'foo' style key.
                    keys_last = 0;
                }

                // Are we dealing with a name=value pair, or just a name?
                if (param.length === 2) {
                    val = decodeURIComponent(param[1]);

                    // Coerce values.
                    if (coerce) {
                        val = val && !isNaN(val) ? +val              // number
                            : val === 'undefined' ? undefined         // undefined
                            : coerce_types[val] !== undefined ? coerce_types[val] // true, false, null
                            : val;                                                // string
                    }

                    if (keys_last) {
                        // Complex key, build deep object structure based on a few rules:
                        // * The 'cur' pointer starts at the object top-level.
                        // * [] = array push (n is set to array length), [n] = array if n is
                        //   numeric, otherwise object.
                        // * If at the last keys part, set the value.
                        // * For each keys part, if the current level is undefined create an
                        //   object or array based on the type of the next keys part.
                        // * Move the 'cur' pointer to the next level.
                        // * Rinse & repeat.
                        for ( ; i <= keys_last; i++ ) {
                            key = keys[i] === '' ? cur.length : keys[i];
                            cur = cur[key] = i < keys_last
                                ? cur[key] || (( keys[i+1] && isNaN( keys[i+1] )) ? {} : [] )
                                : val;
                        }

                    } else {
                        // Simple key, even simpler rules, since only scalars and shallow
                        // arrays are allowed.

                        if ($.isArray(obj[key])) {
                            // val is already an array, so push on the next value.
                            obj[key].push(val);

                        } else if (obj[key] !== undefined) {
                            // val isn't an array, but since a second value has been specified,
                            // convert val into an array.
                            obj[key] = [ obj[key], val ];

                        } else {
                            // val is a scalar.
                            obj[key] = val;
                        }
                    }

                } else if (key) {
                    // No value was defined, so set something meaningful.
                    obj[key] = coerce
                        ? undefined
                        : '';
                }
            });

            return obj;
        },

        // serializes key/values into a query string
        getQueryString:function (object) {
            if (typeof object == 'undefined') {
                object = this.queryObject;
            }

            if ($.isEmptyObject(object)) {
                return '';
            } else {
                return '?' + $.param(object).replace(/%5B/g, '[').replace(/%5D/g, ']');
            }
        }

    });

    global.FS.Util.UriHandler = UriHandler;

})(typeof window === 'undefined' ? this : window);
