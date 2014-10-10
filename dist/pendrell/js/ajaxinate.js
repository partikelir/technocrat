/*!
 * History API JavaScript Library v4.1.0
 *
 * Support: IE8+, FF3+, Opera 9+, Safari, Chrome and other
 *
 * Copyright 2011-2013, Dmitrii Pakhtinov ( spb.piksel@gmail.com )
 *
 * http://spb-piksel.ru/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Update: 2014-03-24 13:14
 */
(function(window) {
    // Prevent the code from running if there is no window.history object
    if (!window.history) return;
    // symlink to document
    var document = window.document;
    // HTML element
    var documentElement = document.documentElement;
    // symlink to constructor of Object
    var Object = window['Object'];
    // symlink to JSON Object
    var JSON = window['JSON'];
    // symlink to instance object of 'Location'
    var windowLocation = window.location;
    // symlink to instance object of 'History'
    var windowHistory = window.history;
    // new instance of 'History'. The default is a reference to the original object instance
    var historyObject = windowHistory;
    // symlink to method 'history.pushState'
    var historyPushState = windowHistory.pushState;
    // symlink to method 'history.replaceState'
    var historyReplaceState = windowHistory.replaceState;
    // if the browser supports HTML5-History-API
    var isSupportHistoryAPI = !!historyPushState;
    // verifies the presence of an object 'state' in interface 'History'
    var isSupportStateObjectInHistory = 'state' in windowHistory;
    // symlink to method 'Object.defineProperty'
    var defineProperty = Object.defineProperty;
    // new instance of 'Location', for IE8 will use the element HTMLAnchorElement, instead of pure object
    var locationObject = redefineProperty({}, 't') ? {} : document.createElement('a');
    // prefix for the names of events
    var eventNamePrefix = '';
    // String that will contain the name of the method
    var addEventListenerName = window.addEventListener ? 'addEventListener' : (eventNamePrefix = 'on') && 'attachEvent';
    // String that will contain the name of the method
    var removeEventListenerName = window.removeEventListener ? 'removeEventListener' : 'detachEvent';
    // String that will contain the name of the method
    var dispatchEventName = window.dispatchEvent ? 'dispatchEvent' : 'fireEvent';
    // reference native methods for the events
    var addEvent = window[addEventListenerName];
    var removeEvent = window[removeEventListenerName];
    var dispatch = window[dispatchEventName];
    // default settings
    var settings = {"basepath": '/', "redirect": 0, "type": '/'};
    // key for the sessionStorage
    var sessionStorageKey = '__historyAPI__';
    // Anchor Element for parseURL function
    var anchorElement = document.createElement('a');
    // last URL before change to new URL
    var lastURL = windowLocation.href;
    // Control URL, need to fix the bug in Opera
    var checkUrlForPopState = '';
    // trigger event 'onpopstate' on page load
    var isFireInitialState = false;
    // store a list of 'state' objects in the current session
    var stateStorage = {};
    // in this object will be stored custom handlers
    var eventsList = {};
    // stored last title
    var lastTitle = document.title;

    /**
     * Properties that will be replaced in the global
     * object 'window', to prevent conflicts
     *
     * @type {Object}
     */
    var eventsDescriptors = {
        "onhashchange": null,
        "onpopstate": null
    };

    /**
     * Fix for Chrome in iOS
     * See https://github.com/devote/HTML5-History-API/issues/29
     */
    var fastFixChrome = function(method, args) {
        var isNeedFix = window.history !== windowHistory;
        if (isNeedFix) {
            window.history = windowHistory;
        }
        method.apply(windowHistory, args);
        if (isNeedFix) {
            window.history = historyObject;
        }
    };

    /**
     * Properties that will be replaced/added to object
     * 'window.history', includes the object 'history.location',
     * for a complete the work with the URL address
     *
     * @type {Object}
     */
    var historyDescriptors = {
        /**
         * @namespace history
         * @param {String} [type]
         * @param {String} [basepath]
         */
        "redirect": function(type, basepath) {
            settings["basepath"] = basepath = basepath == null ? settings["basepath"] : basepath;
            settings["type"] = type = type == null ? settings["type"] : type;
            if (window.top == window.self) {
                var relative = parseURL(null, false, true)._relative;
                var path = windowLocation.pathname + windowLocation.search;
                if (isSupportHistoryAPI) {
                    path = path.replace(/([^\/])$/, '$1/');
                    if (relative != basepath && (new RegExp("^" + basepath + "$", "i")).test(path)) {
                        windowLocation.replace(relative);
                    }
                } else if (path != basepath) {
                    path = path.replace(/([^\/])\?/, '$1/?');
                    if ((new RegExp("^" + basepath, "i")).test(path)) {
                        windowLocation.replace(basepath + '#' + path.
                            replace(new RegExp("^" + basepath, "i"), type) + windowLocation.hash);
                    }
                }
            }
        },
        /**
         * The method adds a state object entry
         * to the history.
         *
         * @namespace history
         * @param {Object} state
         * @param {string} title
         * @param {string} [url]
         */
        pushState: function(state, title, url) {
            var t = document.title;
            if (lastTitle != null) {
                document.title = lastTitle;
            }
            historyPushState && fastFixChrome(historyPushState, arguments);
            changeState(state, url);
            document.title = t;
            lastTitle = title;
        },
        /**
         * The method updates the state object,
         * title, and optionally the URL of the
         * current entry in the history.
         *
         * @namespace history
         * @param {Object} state
         * @param {string} title
         * @param {string} [url]
         */
        replaceState: function(state, title, url) {
            var t = document.title;
            if (lastTitle != null) {
                document.title = lastTitle;
            }
            delete stateStorage[windowLocation.href];
            historyReplaceState && fastFixChrome(historyReplaceState, arguments);
            changeState(state, url, true);
            document.title = t;
            lastTitle = title;
        },
        /**
         * Object 'history.location' is similar to the
         * object 'window.location', except that in
         * HTML4 browsers it will behave a bit differently
         *
         * @namespace history
         */
        "location": {
            set: function(value) {
                window.location = value;
            },
            get: function() {
                return isSupportHistoryAPI ? windowLocation : locationObject;
            }
        },
        /**
         * A state object is an object representing
         * a user interface state.
         *
         * @namespace history
         */
        "state": {
            get: function() {
                return stateStorage[windowLocation.href] || null;
            }
        }
    };

    /**
     * Properties for object 'history.location'.
     * Object 'history.location' is similar to the
     * object 'window.location', except that in
     * HTML4 browsers it will behave a bit differently
     *
     * @type {Object}
     */
    var locationDescriptors = {
        /**
         * Navigates to the given page.
         *
         * @namespace history.location
         */
        assign: function(url) {
            if (('' + url).indexOf('#') === 0) {
                changeState(null, url);
            } else {
                windowLocation.assign(url);
            }
        },
        /**
         * Reloads the current page.
         *
         * @namespace history.location
         */
        reload: function() {
            windowLocation.reload();
        },
        /**
         * Removes the current page from
         * the session history and navigates
         * to the given page.
         *
         * @namespace history.location
         */
        replace: function(url) {
            if (('' + url).indexOf('#') === 0) {
                changeState(null, url, true);
            } else {
                windowLocation.replace(url);
            }
        },
        /**
         * Returns the current page's location.
         *
         * @namespace history.location
         */
        toString: function() {
            return this.href;
        },
        /**
         * Returns the current page's location.
         * Can be set, to navigate to another page.
         *
         * @namespace history.location
         */
        "href": {
            get: function() {
                return parseURL()._href;
            }
        },
        /**
         * Returns the current page's protocol.
         *
         * @namespace history.location
         */
        "protocol": null,
        /**
         * Returns the current page's host and port number.
         *
         * @namespace history.location
         */
        "host": null,
        /**
         * Returns the current page's host.
         *
         * @namespace history.location
         */
        "hostname": null,
        /**
         * Returns the current page's port number.
         *
         * @namespace history.location
         */
        "port": null,
        /**
         * Returns the current page's path only.
         *
         * @namespace history.location
         */
        "pathname": {
            get: function() {
                return parseURL()._pathname;
            }
        },
        /**
         * Returns the current page's search
         * string, beginning with the character
         * '?' and to the symbol '#'
         *
         * @namespace history.location
         */
        "search": {
            get: function() {
                return parseURL()._search;
            }
        },
        /**
         * Returns the current page's hash
         * string, beginning with the character
         * '#' and to the end line
         *
         * @namespace history.location
         */
        "hash": {
            set: function(value) {
                changeState(null, ('' + value).replace(/^(#|)/, '#'), false, lastURL);
            },
            get: function() {
                return parseURL()._hash;
            }
        }
    };

    /**
     * Just empty function
     *
     * @return void
     */
    function emptyFunction() {
        // dummy
    }

    /**
     * Prepares a parts of the current or specified reference for later use in the library
     *
     * @param {string} [href]
     * @param {boolean} [isWindowLocation]
     * @param {boolean} [isNotAPI]
     * @return {Object}
     */
    function parseURL(href, isWindowLocation, isNotAPI) {
        var re = /(?:([\w0-9]+:))?(?:\/\/(?:[^@]*@)?([^\/:\?#]+)(?::([0-9]+))?)?([^\?#]*)(?:(\?[^#]+)|\?)?(?:(#.*))?/;
        if (href != null && href !== '' && !isWindowLocation) {
            var current = parseURL(), _pathname = current._pathname, _protocol = current._protocol;
            // convert to type of string
            href = '' + href;
            // convert relative link to the absolute
            href = /^(?:[\w0-9]+\:)?\/\//.test(href) ? href.indexOf("/") === 0
                ? _protocol + href : href : _protocol + "//" + current._host + (
                href.indexOf("/") === 0 ? href : href.indexOf("?") === 0
                    ? _pathname + href : href.indexOf("#") === 0
                    ? _pathname + current._search + href : _pathname.replace(/[^\/]+$/g, '') + href
                );
        } else {
            href = isWindowLocation ? href : windowLocation.href;
            // if current browser not support History-API
            if (!isSupportHistoryAPI || isNotAPI) {
                // get hash fragment
                href = href.replace(/^[^#]*/, '') || "#";
                // form the absolute link from the hash
                // https://github.com/devote/HTML5-History-API/issues/50
                href = windowLocation.protocol.replace(/:.*$|$/, ':') + '//' + windowLocation.host + settings['basepath']
                    + href.replace(new RegExp("^#[\/]?(?:" + settings["type"] + ")?"), "");
            }
        }
        // that would get rid of the links of the form: /../../
        anchorElement.href = href;
        // decompose the link in parts
        var result = re.exec(anchorElement.href);
        // host name with the port number
        var host = result[2] + (result[3] ? ':' + result[3] : '');
        // folder
        var pathname = result[4] || '/';
        // the query string
        var search = result[5] || '';
        // hash
        var hash = result[6] === '#' ? '' : (result[6] || '');
        // relative link, no protocol, no host
        var relative = pathname + search + hash;
        // special links for set to hash-link, if browser not support History API
        var nohash = pathname.replace(new RegExp("^" + settings["basepath"], "i"), settings["type"]) + search;
        // result
        return {
            _href: result[1] + '//' + host + relative,
            _protocol: result[1],
            _host: host,
            _hostname: result[2],
            _port: result[3] || '',
            _pathname: pathname,
            _search: search,
            _hash: hash,
            _relative: relative,
            _nohash: nohash,
            _special: nohash + hash
        }
    }

    /**
     * Initializing storage for the custom state's object
     */
    function storageInitialize() {
        var sessionStorage;
        /**
         * sessionStorage throws error when cookies are disabled
         * Chrome content settings when running the site in a Facebook IFrame.
         * see: https://github.com/devote/HTML5-History-API/issues/34
         * and: http://stackoverflow.com/a/12976988/669360
         */
        try {
            sessionStorage = window['sessionStorage'];
            sessionStorage.setItem(sessionStorageKey + 't', '1');
            sessionStorage.removeItem(sessionStorageKey + 't');
        } catch(_e_) {
            sessionStorage = {
                getItem: function(key) {
                    var cookie = document.cookie.split(key + "=");
                    return cookie.length > 1 && cookie.pop().split(";").shift() || 'null';
                },
                setItem: function(key, value) {
                    var state = {};
                    // insert one current element to cookie
                    if (state[windowLocation.href] = historyObject.state) {
                        document.cookie = key + '=' + JSON.stringify(state);
                    }
                }
            }
        }

        try {
            // get cache from the storage in browser
            stateStorage = JSON.parse(sessionStorage.getItem(sessionStorageKey)) || {};
        } catch(_e_) {
            stateStorage = {};
        }

        // hang up the event handler to event unload page
        addEvent(eventNamePrefix + 'unload', function() {
            // save current state's object
            sessionStorage.setItem(sessionStorageKey, JSON.stringify(stateStorage));
        }, false);
    }

    /**
     * This method is implemented to override the built-in(native)
     * properties in the browser, unfortunately some browsers are
     * not allowed to override all the properties and even add.
     * For this reason, this was written by a method that tries to
     * do everything necessary to get the desired result.
     *
     * @param {Object} object The object in which will be overridden/added property
     * @param {String} prop The property name to be overridden/added
     * @param {Object} [descriptor] An object containing properties set/get
     * @param {Function} [onWrapped] The function to be called when the wrapper is created
     * @return {Object|Boolean} Returns an object on success, otherwise returns false
     */
    function redefineProperty(object, prop, descriptor, onWrapped) {
        // test only if descriptor is undefined
        descriptor = descriptor || {set: emptyFunction};
        // variable will have a value of true the success of attempts to set descriptors
        var isDefinedSetter = !descriptor.set;
        var isDefinedGetter = !descriptor.get;
        // for tests of attempts to set descriptors
        var test = {configurable: true, set: function() {
            isDefinedSetter = 1;
        }, get: function() {
            isDefinedGetter = 1;
        }};

        try {
            // testing for the possibility of overriding/adding properties
            defineProperty(object, prop, test);
            // running the test
            object[prop] = object[prop];
            // attempt to override property using the standard method
            defineProperty(object, prop, descriptor);
        } catch(_e_) {
        }

        // If the variable 'isDefined' has a false value, it means that need to try other methods
        if (!isDefinedSetter || !isDefinedGetter) {
            // try to override/add the property, using deprecated functions
            if (object.__defineGetter__) {
                // testing for the possibility of overriding/adding properties
                object.__defineGetter__(prop, test.get);
                object.__defineSetter__(prop, test.set);
                // running the test
                object[prop] = object[prop];
                // attempt to override property using the deprecated functions
                descriptor.get && object.__defineGetter__(prop, descriptor.get);
                descriptor.set && object.__defineSetter__(prop, descriptor.set);
            }

            // Browser refused to override the property, using the standard and deprecated methods
            if ((!isDefinedSetter || !isDefinedGetter) && object === window) {
                try {
                    // save original value from this property
                    var originalValue = object[prop];
                    // set null to built-in(native) property
                    object[prop] = null;
                } catch(_e_) {
                }
                // This rule for Internet Explorer 8
                if ('execScript' in window) {
                    /**
                     * to IE8 override the global properties using
                     * VBScript, declaring it in global scope with
                     * the same names.
                     */
                    window['execScript']('Public ' + prop, 'VBScript');
                } else {
                    try {
                        /**
                         * This hack allows to override a property
                         * with the set 'configurable: false', working
                         * in the hack 'Safari' to 'Mac'
                         */
                        defineProperty(object, prop, {value: emptyFunction});
                    } catch(_e_) {
                    }
                }
                // set old value to new variable
                object[prop] = originalValue;

            } else if (!isDefinedSetter || !isDefinedGetter) {
                // the last stage of trying to override the property
                try {
                    try {
                        // wrap the object in a new empty object
                        var temp = Object.create(object);
                        defineProperty(Object.getPrototypeOf(temp) === object ? temp : object, prop, descriptor);
                        for(var key in object) {
                            // need to bind a function to the original object
                            if (typeof object[key] === 'function') {
                                temp[key] = object[key].bind(object);
                            }
                        }
                        try {
                            // to run a function that will inform about what the object was to wrapped
                            onWrapped.call(temp, temp, object);
                        } catch(_e_) {
                        }
                        object = temp;
                    } catch(_e_) {
                        // sometimes works override simply by assigning the prototype property of the constructor
                        defineProperty(object.constructor.prototype, prop, descriptor);
                    }
                } catch(_e_) {
                    // all methods have failed
                    return false;
                }
            }
        }

        return object;
    }

    /**
     * Adds the missing property in descriptor
     *
     * @param {Object} object An object that stores values
     * @param {String} prop Name of the property in the object
     * @param {Object|null} descriptor Descriptor
     * @return {Object} Returns the generated descriptor
     */
    function prepareDescriptorsForObject(object, prop, descriptor) {
        descriptor = descriptor || {};
        // the default for the object 'location' is the standard object 'window.location'
        object = object === locationDescriptors ? windowLocation : object;
        // setter for object properties
        descriptor.set = (descriptor.set || function(value) {
            object[prop] = value;
        });
        // getter for object properties
        descriptor.get = (descriptor.get || function() {
            return object[prop];
        });
        return descriptor;
    }

    /**
     * Wrapper for the methods 'addEventListener/attachEvent' in the context of the 'window'
     *
     * @param {String} event The event type for which the user is registering
     * @param {Function} listener The method to be called when the event occurs.
     * @param {Boolean} capture If true, capture indicates that the user wishes to initiate capture.
     * @return void
     */
    function addEventListener(event, listener, capture) {
        if (event in eventsList) {
            // here stored the event listeners 'popstate/hashchange'
            eventsList[event].push(listener);
        } else {
            // FireFox support non-standart four argument aWantsUntrusted
            // https://github.com/devote/HTML5-History-API/issues/13
            if (arguments.length > 3) {
                addEvent(event, listener, capture, arguments[3]);
            } else {
                addEvent(event, listener, capture);
            }
        }
    }

    /**
     * Wrapper for the methods 'removeEventListener/detachEvent' in the context of the 'window'
     *
     * @param {String} event The event type for which the user is registered
     * @param {Function} listener The parameter indicates the Listener to be removed.
     * @param {Boolean} capture Was registered as a capturing listener or not.
     * @return void
     */
    function removeEventListener(event, listener, capture) {
        var list = eventsList[event];
        if (list) {
            for(var i = list.length; --i;) {
                if (list[i] === listener) {
                    list.splice(i, 1);
                    break;
                }
            }
        } else {
            removeEvent(event, listener, capture);
        }
    }

    /**
     * Wrapper for the methods 'dispatchEvent/fireEvent' in the context of the 'window'
     *
     * @param {Event|String} event Instance of Event or event type string if 'eventObject' used
     * @param {*} [eventObject] For Internet Explorer 8 required event object on this argument
     * @return {Boolean} If 'preventDefault' was called the value is false, else the value is true.
     */
    function dispatchEvent(event, eventObject) {
        var eventType = ('' + (typeof event === "string" ? event : event.type)).replace(/^on/, '');
        var list = eventsList[eventType];
        if (list) {
            // need to understand that there is one object of Event
            eventObject = typeof event === "string" ? eventObject : event;
            if (eventObject.target == null) {
                // need to override some of the properties of the Event object
                for(var props = ['target', 'currentTarget', 'srcElement', 'type']; event = props.pop();) {
                    // use 'redefineProperty' to override the properties
                    eventObject = redefineProperty(eventObject, event, {
                        get: event === 'type' ? function() {
                            return eventType;
                        } : function() {
                            return window;
                        }
                    });
                }
            }
            // run function defined in the attributes 'onpopstate/onhashchange' in the 'window' context
            ((eventType === 'popstate' ? window.onpopstate : window.onhashchange)
                || emptyFunction).call(window, eventObject);
            // run other functions that are in the list of handlers
            for(var i = 0, len = list.length; i < len; i++) {
                list[i].call(window, eventObject);
            }
            return true;
        } else {
            return dispatch(event, eventObject);
        }
    }

    /**
     * dispatch current state event
     */
    function firePopState() {
        var o = document.createEvent ? document.createEvent('Event') : document.createEventObject();
        if (o.initEvent) {
            o.initEvent('popstate', false, false);
        } else {
            o.type = 'popstate';
        }
        o.state = historyObject.state;
        // send a newly created events to be processed
        dispatchEvent(o);
    }

    /**
     * fire initial state for non-HTML5 browsers
     */
    function fireInitialState() {
        if (isFireInitialState) {
            isFireInitialState = false;
            firePopState();
        }
    }

    /**
     * Change the data of the current history for HTML4 browsers
     *
     * @param {Object} state
     * @param {string} [url]
     * @param {Boolean} [replace]
     * @param {string} [lastURLValue]
     * @return void
     */
    function changeState(state, url, replace, lastURLValue) {
        if (!isSupportHistoryAPI) {
            // normalization url
            var urlObject = parseURL(url);
            // if current url not equal new url
            if (urlObject._relative !== parseURL()._relative) {
                // if empty lastURLValue to skip hash change event
                lastURL = lastURLValue;
                if (replace) {
                    // only replace hash, not store to history
                    windowLocation.replace("#" + urlObject._special);
                } else {
                    // change hash and add new record to history
                    windowLocation.hash = urlObject._special;
                }
            }
        }
        if (!isSupportStateObjectInHistory && state) {
            stateStorage[windowLocation.href] = state;
        }
        isFireInitialState = false;
    }

    /**
     * Event handler function changes the hash in the address bar
     *
     * @param {Event} event
     * @return void
     */
    function onHashChange(event) {
        // https://github.com/devote/HTML5-History-API/issues/46
        var fireNow = lastURL;
        // new value to lastURL
        lastURL = windowLocation.href;
        // if not empty fireNow, otherwise skipped the current handler event
        if (fireNow) {
            // if checkUrlForPopState equal current url, this means that the event was raised popstate browser
            if (checkUrlForPopState !== windowLocation.href) {
                // otherwise,
                // the browser does not support popstate event or just does not run the event by changing the hash.
                firePopState();
            }
            // current event object
            event = event || window.event;

            var oldURLObject = parseURL(lastURL, true);
            var newURLObject = parseURL();
            // HTML4 browser not support properties oldURL/newURL
            if (!event.oldURL) {
                event.oldURL = oldURLObject._href;
                event.newURL = newURLObject._href;
            }
            if (oldURLObject._hash !== newURLObject._hash) {
                // if current hash not equal previous hash
                dispatchEvent(event);
            }
        }
    }

    /**
     * The event handler is fully loaded document
     *
     * @param {*} [noScroll]
     * @return void
     */
    function onLoad(noScroll) {
        // Get rid of the events popstate when the first loading a document in the webkit browsers
        setTimeout(function() {
            // hang up the event handler for the built-in popstate event in the browser
            addEvent('popstate', function(e) {
                // set the current url, that suppress the creation of the popstate event by changing the hash
                checkUrlForPopState = windowLocation.href;
                // for Safari browser in OS Windows not implemented 'state' object in 'History' interface
                // and not implemented in old HTML4 browsers
                if (!isSupportStateObjectInHistory) {
                    e = redefineProperty(e, 'state', {get: function() {
                        return historyObject.state;
                    }});
                }
                // send events to be processed
                dispatchEvent(e);
            }, false);
        }, 0);
        // for non-HTML5 browsers
        if (!isSupportHistoryAPI && noScroll !== true && historyObject.location) {
            // scroll window to anchor element
            scrollToAnchorId(historyObject.location.hash);
            // fire initial state for non-HTML5 browser after load page
            fireInitialState();
        }
    }

    /**
     * Finds the closest ancestor anchor element (including the target itself).
     *
     * @param {HTMLElement} target The element to start scanning from.
     * @return {HTMLElement} An element which is the closest ancestor anchor.
     */
    function anchorTarget(target) {
        while (target) {
            if (target.nodeName === 'A') return target;
            target = target.parentNode;
        }
    }

    /**
     * Handles anchor elements with a hash fragment for non-HTML5 browsers
     *
     * @param {Event} e
     */
    function onAnchorClick(e) {
        var event = e || window.event;
        var target = anchorTarget(event.target || event.srcElement);
        var defaultPrevented = "defaultPrevented" in event ? event['defaultPrevented'] : event.returnValue === false;
        if (target && target.nodeName === "A" && !defaultPrevented) {
            var current = parseURL();
            var expect = parseURL(target.getAttribute("href", 2));
            var isEqualBaseURL = current._href.split('#').shift() === expect._href.split('#').shift();
            if (isEqualBaseURL && expect._hash) {
                if (current._hash !== expect._hash) {
                    historyObject.location.hash = expect._hash;
                }
                scrollToAnchorId(expect._hash);
                if (event.preventDefault) {
                    event.preventDefault();
                } else {
                    event.returnValue = false;
                }
            }
        }
    }

    /**
     * Scroll page to current anchor in url-hash
     *
     * @param hash
     */
    function scrollToAnchorId(hash) {
        var target = document.getElementById(hash = (hash || '').replace(/^#/, ''));
        if (target && target.id === hash && target.nodeName === "A") {
            var rect = target.getBoundingClientRect();
            window.scrollTo((documentElement.scrollLeft || 0), rect.top + (documentElement.scrollTop || 0)
                - (documentElement.clientTop || 0));
        }
    }

    /**
     * Library initialization
     *
     * @return {Boolean} return true if all is well, otherwise return false value
     */
    function initialize() {
        /**
         * Get custom settings from the query string
         */
        var scripts = document.getElementsByTagName('script');
        var src = (scripts[scripts.length - 1] || {}).src || '';
        var arg = src.indexOf('?') !== -1 ? src.split('?').pop() : '';
        arg.replace(/(\w+)(?:=([^&]*))?/g, function(a, key, value) {
            settings[key] = (value || (key === 'basepath' ? '/' : '')).replace(/^(0|false)$/, '');
        });

        /**
         * hang up the event handler to listen to the events hashchange
         */
        addEvent(eventNamePrefix + 'hashchange', onHashChange, false);

        // a list of objects with pairs of descriptors/object
        var data = [locationDescriptors, locationObject, eventsDescriptors, window, historyDescriptors, historyObject];

        // if browser support object 'state' in interface 'History'
        if (isSupportStateObjectInHistory) {
            // remove state property from descriptor
            delete historyDescriptors['state'];
        }

        // initializing descriptors
        for(var i = 0; i < data.length; i += 2) {
            for(var prop in data[i]) {
                if (data[i].hasOwnProperty(prop)) {
                    if (typeof data[i][prop] === 'function') {
                        // If the descriptor is a simple function, simply just assign it an object
                        data[i + 1][prop] = data[i][prop];
                    } else {
                        // prepare the descriptor the required format
                        var descriptor = prepareDescriptorsForObject(data[i], prop, data[i][prop]);
                        // try to set the descriptor object
                        if (!redefineProperty(data[i + 1], prop, descriptor, function(n, o) {
                            // is satisfied if the failed override property
                            if (o === historyObject) {
                                // the problem occurs in Safari on the Mac
                                window.history = historyObject = data[i + 1] = n;
                            }
                        })) {
                            // if there is no possibility override.
                            // This browser does not support descriptors, such as IE7

                            // remove previously hung event handlers
                            removeEvent(eventNamePrefix + 'hashchange', onHashChange, false);

                            // fail to initialize :(
                            return false;
                        }

                        // create a repository for custom handlers onpopstate/onhashchange
                        if (data[i + 1] === window) {
                            eventsList[prop] = eventsList[prop.substr(2)] = [];
                        }
                    }
                }
            }
        }

        // redirect if necessary
        if (settings['redirect']) {
            historyObject['redirect']();
        }

        // If browser does not support object 'state' in interface 'History'
        if (!isSupportStateObjectInHistory && JSON) {
            storageInitialize();
        }

        // track clicks on anchors
        if (!isSupportHistoryAPI) {
            document[addEventListenerName](eventNamePrefix + "click", onAnchorClick, false);
        }

        if (document.readyState === 'complete') {
            onLoad(true);
        } else {
            if (!isSupportHistoryAPI && parseURL()._relative !== settings["basepath"]) {
                isFireInitialState = true;
            }
            /**
             * Need to avoid triggering events popstate the initial page load.
             * Hang handler popstate as will be fully loaded document that
             * would prevent triggering event onpopstate
             */
            addEvent(eventNamePrefix + 'load', onLoad, false);
        }

        // everything went well
        return true;
    }

    /**
     * Starting the library
     */
    if (!initialize()) {
        // if unable to initialize descriptors
        // therefore quite old browser and there
        // is no sense to continue to perform
        return;
    }

    /**
     * If the property history.emulate will be true,
     * this will be talking about what's going on
     * emulation capabilities HTML5-History-API.
     * Otherwise there is no emulation, ie the
     * built-in browser capabilities.
     *
     * @type {boolean}
     * @const
     */
    historyObject['emulate'] = !isSupportHistoryAPI;

    /**
     * Replace the original methods on the wrapper
     */
    window[addEventListenerName] = addEventListener;
    window[removeEventListenerName] = removeEventListener;
    window[dispatchEventName] = dispatchEvent;

})(window);
/**
 * Copyright (c) 2011-2014 Felix Gnass
 * Licensed under the MIT license
 */
(function(root, factory) {

  /* CommonJS */
  if (typeof exports == 'object')  module.exports = factory()

  /* AMD module */
  else if (typeof define == 'function' && define.amd) define(factory)

  /* Browser global */
  else root.Spinner = factory()
}
(this, function() {
  "use strict";

  var prefixes = ['webkit', 'Moz', 'ms', 'O'] /* Vendor prefixes */
    , animations = {} /* Animation rules keyed by their name */
    , useCssAnimations /* Whether to use CSS animations or setTimeout */

  /**
   * Utility function to create elements. If no tag name is given,
   * a DIV is created. Optionally properties can be passed.
   */
  function createEl(tag, prop) {
    var el = document.createElement(tag || 'div')
      , n

    for(n in prop) el[n] = prop[n]
    return el
  }

  /**
   * Appends children and returns the parent.
   */
  function ins(parent /* child1, child2, ...*/) {
    for (var i=1, n=arguments.length; i<n; i++)
      parent.appendChild(arguments[i])

    return parent
  }

  /**
   * Insert a new stylesheet to hold the @keyframe or VML rules.
   */
  var sheet = (function() {
    var el = createEl('style', {type : 'text/css'})
    ins(document.getElementsByTagName('head')[0], el)
    return el.sheet || el.styleSheet
  }())

  /**
   * Creates an opacity keyframe animation rule and returns its name.
   * Since most mobile Webkits have timing issues with animation-delay,
   * we create separate rules for each line/segment.
   */
  function addAnimation(alpha, trail, i, lines) {
    var name = ['opacity', trail, ~~(alpha*100), i, lines].join('-')
      , start = 0.01 + i/lines * 100
      , z = Math.max(1 - (1-alpha) / trail * (100-start), alpha)
      , prefix = useCssAnimations.substring(0, useCssAnimations.indexOf('Animation')).toLowerCase()
      , pre = prefix && '-' + prefix + '-' || ''

    if (!animations[name]) {
      sheet.insertRule(
        '@' + pre + 'keyframes ' + name + '{' +
        '0%{opacity:' + z + '}' +
        start + '%{opacity:' + alpha + '}' +
        (start+0.01) + '%{opacity:1}' +
        (start+trail) % 100 + '%{opacity:' + alpha + '}' +
        '100%{opacity:' + z + '}' +
        '}', sheet.cssRules.length)

      animations[name] = 1
    }

    return name
  }

  /**
   * Tries various vendor prefixes and returns the first supported property.
   */
  function vendor(el, prop) {
    var s = el.style
      , pp
      , i

    prop = prop.charAt(0).toUpperCase() + prop.slice(1)
    for(i=0; i<prefixes.length; i++) {
      pp = prefixes[i]+prop
      if(s[pp] !== undefined) return pp
    }
    if(s[prop] !== undefined) return prop
  }

  /**
   * Sets multiple style properties at once.
   */
  function css(el, prop) {
    for (var n in prop)
      el.style[vendor(el, n)||n] = prop[n]

    return el
  }

  /**
   * Fills in default values.
   */
  function merge(obj) {
    for (var i=1; i < arguments.length; i++) {
      var def = arguments[i]
      for (var n in def)
        if (obj[n] === undefined) obj[n] = def[n]
    }
    return obj
  }

  /**
   * Returns the absolute page-offset of the given element.
   */
  function pos(el) {
    var o = { x:el.offsetLeft, y:el.offsetTop }
    while((el = el.offsetParent))
      o.x+=el.offsetLeft, o.y+=el.offsetTop

    return o
  }

  /**
   * Returns the line color from the given string or array.
   */
  function getColor(color, idx) {
    return typeof color == 'string' ? color : color[idx % color.length]
  }

  // Built-in defaults

  var defaults = {
    lines: 12,            // The number of lines to draw
    length: 7,            // The length of each line
    width: 5,             // The line thickness
    radius: 10,           // The radius of the inner circle
    rotate: 0,            // Rotation offset
    corners: 1,           // Roundness (0..1)
    color: '#000',        // #rgb or #rrggbb
    direction: 1,         // 1: clockwise, -1: counterclockwise
    speed: 1,             // Rounds per second
    trail: 100,           // Afterglow percentage
    opacity: 1/4,         // Opacity of the lines
    fps: 20,              // Frames per second when using setTimeout()
    zIndex: 2e9,          // Use a high z-index by default
    className: 'spinner', // CSS class to assign to the element
    top: '50%',           // center vertically
    left: '50%',          // center horizontally
    position: 'absolute'  // element position
  }

  /** The constructor */
  function Spinner(o) {
    this.opts = merge(o || {}, Spinner.defaults, defaults)
  }

  // Global defaults that override the built-ins:
  Spinner.defaults = {}

  merge(Spinner.prototype, {

    /**
     * Adds the spinner to the given target element. If this instance is already
     * spinning, it is automatically removed from its previous target b calling
     * stop() internally.
     */
    spin: function(target) {
      this.stop()

      var self = this
        , o = self.opts
        , el = self.el = css(createEl(0, {className: o.className}), {position: o.position, width: 0, zIndex: o.zIndex})
        , mid = o.radius+o.length+o.width

      css(el, {
        left: o.left,
        top: o.top
      })
        
      if (target) {
        target.insertBefore(el, target.firstChild||null)
      }

      el.setAttribute('role', 'progressbar')
      self.lines(el, self.opts)

      if (!useCssAnimations) {
        // No CSS animation support, use setTimeout() instead
        var i = 0
          , start = (o.lines - 1) * (1 - o.direction) / 2
          , alpha
          , fps = o.fps
          , f = fps/o.speed
          , ostep = (1-o.opacity) / (f*o.trail / 100)
          , astep = f/o.lines

        ;(function anim() {
          i++;
          for (var j = 0; j < o.lines; j++) {
            alpha = Math.max(1 - (i + (o.lines - j) * astep) % f * ostep, o.opacity)

            self.opacity(el, j * o.direction + start, alpha, o)
          }
          self.timeout = self.el && setTimeout(anim, ~~(1000/fps))
        })()
      }
      return self
    },

    /**
     * Stops and removes the Spinner.
     */
    stop: function() {
      var el = this.el
      if (el) {
        clearTimeout(this.timeout)
        if (el.parentNode) el.parentNode.removeChild(el)
        this.el = undefined
      }
      return this
    },

    /**
     * Internal method that draws the individual lines. Will be overwritten
     * in VML fallback mode below.
     */
    lines: function(el, o) {
      var i = 0
        , start = (o.lines - 1) * (1 - o.direction) / 2
        , seg

      function fill(color, shadow) {
        return css(createEl(), {
          position: 'absolute',
          width: (o.length+o.width) + 'px',
          height: o.width + 'px',
          background: color,
          boxShadow: shadow,
          transformOrigin: 'left',
          transform: 'rotate(' + ~~(360/o.lines*i+o.rotate) + 'deg) translate(' + o.radius+'px' +',0)',
          borderRadius: (o.corners * o.width>>1) + 'px'
        })
      }

      for (; i < o.lines; i++) {
        seg = css(createEl(), {
          position: 'absolute',
          top: 1+~(o.width/2) + 'px',
          transform: o.hwaccel ? 'translate3d(0,0,0)' : '',
          opacity: o.opacity,
          animation: useCssAnimations && addAnimation(o.opacity, o.trail, start + i * o.direction, o.lines) + ' ' + 1/o.speed + 's linear infinite'
        })

        if (o.shadow) ins(seg, css(fill('#000', '0 0 4px ' + '#000'), {top: 2+'px'}))
        ins(el, ins(seg, fill(getColor(o.color, i), '0 0 1px rgba(0,0,0,.1)')))
      }
      return el
    },

    /**
     * Internal method that adjusts the opacity of a single line.
     * Will be overwritten in VML fallback mode below.
     */
    opacity: function(el, i, val) {
      if (i < el.childNodes.length) el.childNodes[i].style.opacity = val
    }

  })


  function initVML() {

    /* Utility function to create a VML tag */
    function vml(tag, attr) {
      return createEl('<' + tag + ' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">', attr)
    }

    // No CSS transforms but VML support, add a CSS rule for VML elements:
    sheet.addRule('.spin-vml', 'behavior:url(#default#VML)')

    Spinner.prototype.lines = function(el, o) {
      var r = o.length+o.width
        , s = 2*r

      function grp() {
        return css(
          vml('group', {
            coordsize: s + ' ' + s,
            coordorigin: -r + ' ' + -r
          }),
          { width: s, height: s }
        )
      }

      var margin = -(o.width+o.length)*2 + 'px'
        , g = css(grp(), {position: 'absolute', top: margin, left: margin})
        , i

      function seg(i, dx, filter) {
        ins(g,
          ins(css(grp(), {rotation: 360 / o.lines * i + 'deg', left: ~~dx}),
            ins(css(vml('roundrect', {arcsize: o.corners}), {
                width: r,
                height: o.width,
                left: o.radius,
                top: -o.width>>1,
                filter: filter
              }),
              vml('fill', {color: getColor(o.color, i), opacity: o.opacity}),
              vml('stroke', {opacity: 0}) // transparent stroke to fix color bleeding upon opacity change
            )
          )
        )
      }

      if (o.shadow)
        for (i = 1; i <= o.lines; i++)
          seg(i, -2, 'progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)')

      for (i = 1; i <= o.lines; i++) seg(i)
      return ins(el, g)
    }

    Spinner.prototype.opacity = function(el, i, val, o) {
      var c = el.firstChild
      o = o.shadow && o.lines || 0
      if (c && i+o < c.childNodes.length) {
        c = c.childNodes[i+o]; c = c && c.firstChild; c = c && c.firstChild
        if (c) c.opacity = val
      }
    }
  }

  var probe = css(createEl('group'), {behavior: 'url(#default#VML)'})

  if (!vendor(probe, 'transform') && probe.adj) initVML()
  else useCssAnimations = vendor(probe, 'animation')

  return Spinner

}));

/**
 * Copyright (c) 2011-2014 Felix Gnass
 * Licensed under the MIT license
 */

/*

Basic Usage:
============

$('#el').spin(); // Creates a default Spinner using the text color of #el.
$('#el').spin({ ... }); // Creates a Spinner using the provided options.

$('#el').spin(false); // Stops and removes the spinner.

Using Presets:
==============

$('#el').spin('small'); // Creates a 'small' Spinner using the text color of #el.
$('#el').spin('large', '#fff'); // Creates a 'large' white Spinner.

Adding a custom preset:
=======================

$.fn.spin.presets.flower = {
  lines: 9
  length: 10
  width: 20
  radius: 0
}

$('#el').spin('flower', 'red');

*/

(function(factory) {

  if (typeof exports == 'object') {
    // CommonJS
    factory(require('jquery'), require('spin'))
  }
  else if (typeof define == 'function' && define.amd) {
    // AMD, register as anonymous module
    define(['jquery', 'spin'], factory)
  }
  else {
    // Browser globals
    if (!window.Spinner) throw new Error('Spin.js not present')
    factory(window.jQuery, window.Spinner)
  }

}(function($, Spinner) {

  $.fn.spin = function(opts, color) {

    return this.each(function() {
      var $this = $(this),
        data = $this.data();

      if (data.spinner) {
        data.spinner.stop();
        delete data.spinner;
      }
      if (opts !== false) {
        opts = $.extend(
          { color: color || $this.css('color') },
          $.fn.spin.presets[opts] || opts
        )
        data.spinner = new Spinner(opts).spin(this)
      }
    })
  }

  $.fn.spin.presets = {
    tiny: { lines: 8, length: 2, width: 2, radius: 3 },
    small: { lines: 8, length: 4, width: 3, radius: 5 },
    large: { lines: 10, length: 8, width: 4, radius: 8 }
  }

}));

// ==== AJAXINATE ==== //

// Documentation: https://github.com/synapticism/ajaxinate

// Global namespace object; inspiration for the design of this via Ryan Florence: http://ryanflorence.com/authoring-jquery-plugins-with-object-oriented-javascript/
var XN8 = {};

;(function($, window, document, undefined){
  'use strict';

  // Initialize HTML5-History-API polyfill with this single line
  var location = window.history.location || window.location;

  // Check to see if the browser supports the HTML5 history API; if not, fail early and let the site load normally
  if ( !(window.history && history.pushState) ) {
    return false;
  }

  // Constructor function
  var Ajaxinate = this.Ajaxinate = function(element, opts){
    this.element = element;
    this.opts = $.extend({}, $.fn.ajaxinate.defaults, opts);
    this.init();
  };

  // Prototype logic
  Ajaxinate.prototype = {
    init: function(){

      // Fetch initial content and menu
      this.content = $(this.opts.contentSel).filter(':first');
      this.menu = $(this.opts.menuSel).filter(':first');

      // Invalid content selector; abort mission! (We can handle a bad menu selector, however...)
      if (!this.content.length) {
        return false;
      }

      // Initialize internal link selector
      this.internalSel();

      // Initialize event handlers for the element(s) specified
      this.prep(this.element);

      // Initialize popstate event handler
      this.popper();

      // Initialize spinner
      this.spinner();
    },



    // Internal link slector
    internalSel: function(){
      var self = this;

      // Register the custom selector to identify internal links
      $.expr[':'].internal = function(obj,index,meta,stack){
        var
          $this   = $(obj),
          url     = $this.attr('href') || '',
          rooturl = self.root();

        // The link is "internal" if: the domain matches the current domain or there is no protocol (i.e. it is a relative link)
        return url.substring(0,rooturl.length) === rooturl || url.indexOf(':') === -1;
      };
    }, // end internalSel()



    // Primary event binding function
    prep: function(element){
      var self = this;

      // Select all anchor tags within the target element; uses native JavaScript methods (fast)
      $(element).find('a')

      // Now filter the results to find certain internal links; uses complex jQuery selectors (slow)
      .filter(':internal' + self.opts.filters)

      // Manage click event
      .on('click', function(event){
        var
          $this = $(this),
          url   = $this.attr('href'),
          title = $this.attr('title') || null; // Not hugely important but we'll use a title attribute if one happens to be present

        // Return false (and prevent all actions) under these circumstances:
        // - URL is the same as the current location (no sense in reloading anything)
        // - URL is just a hash (ditto)
        if ( url === location.href || url === '#' ) {
          return false;
        }

        // Return true (and allow normal browser behavior) under these circumstances:
        // - Middle mouse button clicks (event.which === 2); reference: https://api.jquery.com/event.which/
        // - Command and control key clicks e.g. to open in a new tab; reference: https://github.com/browserstate/ajaxify/pull/15/files
        // - See also: event.altKey, event.shiftKey (not implemented)
        // - URL not set (shouldn't happen) or URL begins with a hash (in document link)
        // - URL matches the exceptions defined in the options (defaults: certain file extensions e.g. images, audio files, etc.)
        if (
          event.which === 2 ||
          event.metaKey     ||
          event.ctrlKey     ||
          !url              ||
          url[0] === '#'    ||
          url.match(self.opts.exceptions)
        ) {
          // @TODO: .trigger('internalException') custom event?
          return true;
        }

        // Don't make a page request if we've come this far
        event.preventDefault();

        // Push and load
        self.pusher(title,url);
      });
    }, // end prep()



    // Push state handler; isolated here for greater modularity; you can call this from plugins more easily
    pusher: function(title,url){

      // Initiate a state object for this push; @TODO: flesh this out a bit, it's not hugely useful in its present state
      var state = {
        title: title,
        url: url
      };

      // Push state
      history.pushState(state,title,url);

      // Load new content via AJAX
      this.load(url);
    }, // end pusher()



    // The popstate event fires when the user navigates backward or forward in the browser
    popper: function(){
      var self = this;

      $(window).on('popstate', function(event){

        // @TODO: check previous state object to see whether more than just the hash has changed
        if (location.hash.substring(1) === '') {
          self.load(location.href);
        }
      });
    }, // end popper()



    // Conditionally initialize spinner div; degrades gracefully if spin.js not found
    spinner: function(){
      if ( $.isFunction(window.Spinner) ) {
        this.content.before('<div id="spinner" style="position: fixed; height: 100%; width: 100%;"></div>');
        $('#spinner').spin(this.opts.spinOpts).hide();
      }
    }, // end spinner()



    // This function dynamically loads content from the requested page
    load: function(url){
      var
        self       = this,
        $body      = $(document.body),
        $spinner   = $('#spinner'),
        contentSel = this.opts.contentSel,
        menuSel    = this.opts.menuSel;

      // Dim existing content; use animate to preserve the element's height (avoids scrollbar flicker)
      this.content.animate({ opacity: this.opts.outOpacity }, this.opts.outDelay);

      // Spin spin sugar; no delay needed here
      $spinner.show();

      // AJAX page request; fetch the content we need
      $.ajax({
        url: url,
        success: function(data,textStatus,jqXHR){

          // Quickly fade content out entirely
          self.content.animate({ opacity: 0 }, 20);

          var
            $data         = $(self.docHelp(data)),
            $docBody      = $data.find('#doc-body:first'),
            $content      = $docBody.find(contentSel).filter(':first'),
            $menu         = $docBody.find(menuSel).filter(':first');

          // If no content is received for any reason just forward the user on to the target URL
          if (!$content.length) {
            document.location.href = url;
            return false;
          }

          // Stop animation immediately
          self.content.stop(true,true);

          // Update the content and prep event handlers
          self.prep( self.content.html( $content.html() ) );

          // Swap the menu(s) and prep event handlers if a menu is found
          if ($menu.length) {
            self.prep( self.menu.html( $menu.html() ) );
          }

          // Harmonize body classes; makes WordPress pages render smoothly but also generally useful
          $body.attr('class', $docBody.attr('class'));

          // Fade the content back in
          self.content.animate({ opacity: 1 }, self.opts.inDelay);

          // Update body scripts outside of the content area
          self.scripts( $docBody.find('script').not(contentSel+':first script') );

          // Update document title
          self.title( $data.find('#doc-title:first').text() );
        },

        // Error handling; fall back on regular page load if anything goes wrong
        error: function(jqXHR,textStatus,errorThrown){
          document.location.href = url;
          return false;
        }, // end error handling

        // Clean up after completing the AJAX call
        complete: function(jqXHR,textStatus){

          // Fade out spinner
          $spinner.fadeOut(self.opts.spinFade, function(){ $(this).hide(); });

          // Scroll to the appropriate location
          self.scroll();

          // Update analytics
          self.analytics(url);

        } // end complete

      }); // end $.ajax

    }, // end load()



    // Update scripts
    scripts: function($scripts){
      var
        $body       = $('body'),
        contentScr  = this.opts.contentSel + ':first script'; // Selects content scripts

      // Fetch body scripts not in the content (which will be replaced anyhow)
      if ( $scripts.length ) {
        $scripts.detach();
      } else {
        return false; // Early exit; there are no scripts to work with
      }

      // Update the body scripts outside of the content area; we're assuming that header scripts don't change
      $scripts.each(function(){
        var $this = $(this);

        // If the current script isn't empty we assume it must be inline
        if ( $this.html() !== '' ) {

          // Save the currect script contents for use in the loop to follow
          var
            inlineScript = $this.html(),
            isNew        = true;

          // Check existing scripts to see if they contain the same stuff
          $.each( $body.find('script:not(:empty)').not(contentScr), function(){
            if ( $this.html() === inlineScript ) {
              isNew = false;
            }
          });

          // Paste new stuff in if it isn't found in the current DOM; this is quite a kludge
          if ( isNew === true ) {
            $body.find('script:not(:empty)').not(contentScr).filter(':first').before('<script>' + $this.html() + '</script>');
          }

        // Fetch new non-inline scripts
        } else {

          // Test for the presence of each script; no sense in repeating ourselves here
          if ( !$body.find('script[src*="' + $this.attr('src') + '"]').length ) {
            // $.getScript does not cache scripts by default so we'll skip the shortcut and go straight to the source
            $.ajax({
              url: $this.attr('src'),
              dataType: "script",
              cache: true,
              complete: function(){ // In case caching isn't working... add the script to the DOM
                var newScript = document.createElement('script');
                newScript.src = $this.attr('src'); // No need for type="text/javascript" in HTML5
                $body[0].appendChild(newScript);
              }
            }); // end $.ajax()
          }
        }
      }); // end $scripts.each()
    }, // end scripts()



    // Update document title
    title: function($title){
      document.title = $title
        .replace('<','&lt;')
        .replace('>','&gt;')
        .replace('&','&amp;')
      ;
    }, // end title()



    // Update scroll position
    scroll: function(){
      var
        $body       = $('body'),
        hash        = location.hash.substring(1),
        scrollDelay = this.opts.scrollDelay,
        top         = $(this.opts.contentSel).filter(':first').offset().top;

      // Test for the presence of a hash in the location
      if (hash !== '') {

        // Define the selector
        var $hash = $('[id="'+hash+'"]');

        // Test whether the selector exists; scroll there if found and fall back on regular scroll routine if not
        if ( $hash.length ) {
          $body.animate({ scrollTop: $hash.offset().top }, scrollDelay, "swing");
          return; // Early exit from function; we've got something to scroll to and it's going to be below the fold
        }
      }

      // Scroll to the top of the content container when below the fold
      if ( $(window).scrollTop() > top ) {
        $body.animate({ scrollTop: top }, scrollDelay, "swing");
      }

    }, // end scroll()



    // Update Google Analytics on content load
    analytics: function(url){
      var relativeUrl = '/' + url.replace(this.root(), ''); // Set the relative URL required by Google Analytics

      // Inform Google Analytics of the change; compatible with the new Universal Analytics script
      if ( typeof window.ga !== 'undefined' ) {
        window.ga('send', 'pageview', relativeUrl);
      } else if ( typeof window._gaq !== 'undefined' ) {
        window._gaq.push(['_trackPageview', relativeUrl]); // Legacy analytics; ref: https://github.com/browserstate/ajaxify/pull/25
      }
    }, // end analytics()



    // Document helper; browsers often strip out html, head, body, title, base, and meta tags when using .innerHTML; see https://api.jquery.com/load/
    // This function allows access to these tags by transforming them to divs with ids that match the original e.g. #doc-title
    // See also: https://gist.github.com/cowboy/742952/
    docHelp: function(html){
      var
        result = String(html)
          .replace(/<\!DOCTYPE[^>]*>/i, '')
          .replace(/<(html|head|body|title|base|meta)([\s\>])/gi,'<div id="doc-$1"$2')
          .replace(/<\/(html|head|body|title|base|meta)\>/gi,'</div>')
      ;
      return $.trim(result);
    }, // end docHelp()



    // Utility function to get the root URL with trailing slash e.g. http(s)://yourdomain.com/
    root: function(){
      var
        port = document.location.port ? ':' + document.location.port : '',
        url = document.location.protocol + '//' + (document.location.hostname || document.location.host) + port + '/';
      return url;
    } // end root()

  }; // end Ajaxinate.prototype



  // A lightweight plugin wrapper around the constructor preventing multiple instantiations
  $.fn.ajaxinate = function (opts){
    return this.each(function(){
      if (!$.data(this, 'XN8_ajaxinate')) {
        $.data(this, 'XN8_ajaxinate', new Ajaxinate(this, opts));
      }
    });
  };



  // Extensible default settings
  $.fn.ajaxinate.defaults = {
    contentSel:    '#content' // The selector for all page contents
  , menuSel:       '#menu'    // The selector for the entire menu
  , outDelay:      600
  , outOpacity:    0.2        // Dimming rather than completely hiding content
  , inDelay:       120
  , scrollDelay:   300
  , spinFade:      300        // Spinner fade out duration
  , spinOpts: {               // spin.js options; reference: https://fgnass.github.io/spin.js/
      lines:  25
    , length: 0
    , width:  4
    , radius: 25
    , speed:  1.5
    , trail:  40
    , top:    '25%'
  }

  // Whitelisted extensions (regexp); these will negate the custom "internal" selector
  , exceptions: /\.(jpg|jpeg|png|gif|bmp|pdf|mp3|flac|wav|rar|zip)$/i

  // Additional selectors to filter when binding events (string); use ":not(selector)", for example, to skip certain selectors e.g. `:not([href*="test"])` etc.
  , filters: ''
  };

}).apply(XN8, [jQuery, window, document]);

// ==== AJAXINATE WORDPRESS EXTENSION ==== //

// Augments the Ajaxinate script to handle several scenarios unique to WordPress
// Must be called *after* the main Ajaxinate script; assumes XN8 is already defined

;(function($, window, document, undefined){
  'use strict';

  var PT = XN8.Ajaxinate.prototype;

  // Hook into prep to add WordPress-specific methods
  PT._prep = PT.prep;
  PT.prep = function(element){
    this._prep(element);
    this.wpArchives(element);
    this.wpSearch(element);
  }; // end prep()



  // URL encode function to emulate WP's search rewrite; via: http://phpjs.org/functions/urlencode/
  PT.encode = function(str){
    str = (str+'').toString();
    return encodeURIComponent(str)
      .replace(/!/g, '%21')
      .replace(/'/g, '%27')
      .replace(/\(/g, '%28')
      .replace(/\)/g, '%29')
      .replace(/\*/g, '%2A')
      .replace(/%20/g, '+');
  }; // end encode()



  // WordPress archive dropdown handler; for use with the archive widget; don't use with the category widget, the code is completely different
  PT.wpArchives = function(element){
    var self = this;

    $(element)
    .find('select[name$="dropdown"]')
    .removeAttr('onchange') // Ditch the default JavaScript event handler
    .on('change', function(event){
      var url   = $(this).val(),
          title = $(this).find(':selected').text().trim() || null;

      self.pusher(title,url);
    });
  }; // end wpDropdown()



  // WordPress search form; you may need to customize this to suit your own theme
  PT.wpSearch = function(element){
    var self = this;

    $(element)
    .find(self.opts.searchSel)
    .on('submit', function(event){
      var
        url = '',
        s = $('input[type="search"]', this).val() || null;

      event.preventDefault();

      // If the form was submitted without content focus on the search field
      if (s === null) {

        $('input[type="search"]', this).focus();

      // Setup our search URL
      } else {
        if ( self.opts.searchBase && self.opts.searchBase !== '' ) {
          url = self.root() + self.opts.searchBase + "/" + self.encode(s);
        } else {
          url = self.root() + "?s=" + s;
        }

        // Launch without title
        self.pusher(s,url);
      }
    });
  }; // end wpSearch()



  // Search form selector
  $.fn.ajaxinate.defaults.searchSel  = '.search-form';

  // Search rewrite base; for use with permalinks for search e.g. http://yoursite.com/search/testing+this;
  $.fn.ajaxinate.defaults.searchBase = 'search';

  // WordPress-specific exceptions; there are more efficient ways to do this sort of thing but this is easier to configure
  $.fn.ajaxinate.defaults.filters    = $.fn.ajaxinate.defaults.filters + ':not([href*="wp-login"], [href*="wp-admin"], [href*="replytocom"], [id*="cancel-comment-reply-link"], [href$="/feed/"])';

}).apply(XN8, [jQuery, window, document]);

// ==== AJAXINATE ==== //

// Selective content loading for WordPress: https://github.com/synapticism/ajaxinate

(function($){
  $(function(){ // Shortcut to $(document).ready(handler);
    $(document.body).ajaxinate({
      contentSel: '#content-wrapper',
      menuSel: '.menu-header'
    }); // To set an option include an object literal e.g. `ajaxinate({ searchBase: 'search' });`
  });
}(jQuery));
