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

// ==== PAGE LOADER ==== //

// Based on Ajaxinate: https://github.com/synapticism/ajaxinate
// With some help from: http://www.problogdesign.com/wordpress/load-next-wordpress-posts-with-ajax/

// Global namespace object; inspiration for the design of this via Ryan Florence: http://ryanflorence.com/authoring-jquery-plugins-with-object-oriented-javascript/
var PG8 = {};

;(function($, document, window, undefined){
  'use strict';

  // Exit early if WordPress didn't pass us anything
  if (typeof pendrellVars === 'undefined' || pendrellVars.PG8 === '') {
    return;
  }

  // Initialize HTML5-History-API polyfill with this single line
  var location = window.history.location || window.location;

  // Constructor function
  var PageLoader = this.PageLoader = function(opts){
    this.thisPage = parseInt(pendrellVars.PG8.startPage);
    this.thisLink = location.href;
    this.nextPage = this.thisPage + 1;
    this.nextLink = pendrellVars.PG8.nextLink;
    this.maxPages = parseInt(pendrellVars.PG8.maxPages);
    this.maxedOut = 0; // A flag to determine whether all pages have been loaded
    this.opts     = $.extend({}, $.fn.pageloader.defaults, opts);
    this.content  = $(this.opts.contentSel);

    // Initialize page loader only if there are pages to load
    if (this.nextPage <= this.maxPages) {
      this.init();
    }
  };



  // Prototype functionality
  PageLoader.prototype = {
    init: function(){

      // Wrap all the children of the main element in a way that is consistent with how content is loaded
      this.content.children().wrapAll('<div id="content-page-'+this.thisPage+'" class="clear" data-href="'+this.thisLink+'"></div>');

      // Create the first (p)lace)holder div that content will be loaded into
      this.holder();

      // Bind event handlers
      this.handler();

      // Initialize spinner
      this.spinner();
    },



    // Create a placeholder; abstracted into a function for DRYness
    holder: function(){
      this.content.append('<div id="content-page-' + this.nextPage + '" class="clear" data-href="'+this.nextLink+'"></div>');
    }, // end holder()



    // Event handlers
    handler: function(){
      var
        self    = this,
        $window = $(window);

      // Bind to click events on the body element to ensure compatibility with other forms of DOM manipulation we may be doing
      $('body').on('click', self.opts.nextSel, function(event){

        // Are there more posts to load? This has to be checked again as nextPage increments
        if (self.nextPage <= self.maxPages) {

          // Cancel page request
          event.preventDefault();

          // Invoke spinner
          $(this).parents('nav:first').before($('#spinner').show());

          // Load content
          self.loader(self.nextPage, self.nextLink);
        }
      }); // end .on('click')



      // Watch scroll position and change URL accordingly
      $window.on('scroll', this.content, function(event){

        // Clear previously set timer
        clearTimeout($.data(this, 'pushTimer'));
        clearTimeout($.data(this, 'infinTimer'));

        // Manage push state based on scroll position; keeps the URL updated wherever the window position is
        $.data(this, 'pushTimer', setTimeout(function() {

          // Setup some useful variables including info about the top-most page
          var
            firstPage = self.content.children(':first'),
            firstTop  = firstPage.offset().top,
            firstLink = firstPage.data('href'),
            winTop    = $window.scrollTop();

          // Push state if the top of the window is above the first page
          if ( winTop <= firstTop ) {
            self.pusher(firstLink);
          } else {

            // Monitor the children of the main content selector; should be a bunch of divs representing each page of content
            self.content.children().each(function(){
              var
                $this   = $(this),
                top     = $this.offset().top - self.opts.scrollOffset,
                bottom  = $this.outerHeight()+top;

              // Push state if the top of the window falls into the range of a given page
              if ( top <= winTop && winTop < bottom ) {
                self.pusher($this.data('href'));
              }
            }); // end each()
          } // end if
        }, self.opts.pushDelay)); // end $.data()

        // Infinite scroll, a lazy (yet smart) implementation
        if ( self.maxedOut === 0 ) { // Only bother with this if there are more pages to load
          $.data(this, 'infinTimer', setTimeout(function() {
            var
              $document       = $(document),
              scrollHeight    = $document.height(),
              scrollPosition  = $window.height() + $window.scrollTop(), // Position of the bottom of the window
              scrollLastPage  = self.content.children(':last').offset().top, // Bottom of the content area
              scrollDiff      = scrollHeight - scrollPosition; // How close to the absolute bottom of the document

            // Trigger a click when the bottom of the window is just below the contents of the last page
            // But not the absolute bottom; we'd like to be able to reach the footer if we can!
            if ( scrollPosition > scrollLastPage + self.opts.scrollOffset && scrollPosition <= scrollLastPage + self.opts.scrollOffset + self.opts.infinOffset && scrollDiff >= 1 ) {
              $(self.opts.nextSel).trigger('click');
            }
          }, self.opts.infinDelay)); // end $.data()
        } // end infinite scroll
      }); // end $window.on('scroll')
    }, // end handler()



    // Conditionally initialize spinner div; degrades gracefully if spin.js not found
    spinner: function(){
      if ( $.isFunction(window.Spinner) ) {
        this.content.after('<div id="spinner" style="position: relative;"></div>');
        $('#spinner').spin(this.opts.spinOpts).hide();
      }
    }, // end spinner()



    pusher: function(url){
      if (typeof url !== 'undefined' && url !== location.href) {
        history.pushState(null,null,url);
      }
    }, // end pusher()



    // Page loader
    loader: function(page, link){
      var self = this;

      // Load content into the appropriate page loader
      $('#content-page-'+page).load(link+' '+self.opts.contentSel+' > *', function() {

        // Cache the next selector
        var $navLink = $(self.opts.nextSel);

        // Update page number and nextLink
        self.thisPage = page;
        self.thisLink = link;
        self.nextPage = page + 1;
        self.nextLink = link.replace(/\/page\/[0-9]?/, '/page/'+self.nextPage);

        // @TODO: load scripts necessary to display content on new pages e.g. MediaElement.js
        // Presently this script assumes all scripts are already loaded
        // Follow the link for an example: https://github.com/Automattic/jetpack/blob/master/modules/infinite-scroll/infinity.js

        // Change the URL
        self.pusher(self.thisLink);

        // Create another placeholder
        self.holder();

        // Navigation link handling
        if (self.nextPage > self.maxPages) {
          $navLink.remove(); // End of the line
          self.maxedOut = 1; // Set a flag to avoid further processing
        } else {
          $('a', $navLink).attr('href', self.nextLink); // Update the navigation links (for right-click etc.)
        }

        // Hide spinner
        $('#spinner').hide();

        self.loaded();

        // Scroll to the appropriate location
        self.scroll();

        // Update analytics with relative URL on load (not on scroll)
        self.analytics('/'+location.href.replace(self.root(), ''));
      });
    }, // end loader()



    // Emit the DOMContentLoaded event (for compatibility with Prism.js and other scripts)
    loaded: function(){
      var loaded = document.createEvent("Event");
      loaded.initEvent("DOMContentLoaded", true, true);
      window.document.dispatchEvent(loaded);
    },



    // Scroll to the top of the new content container
    scroll: function(){
      var top = $('#content-page-'+this.thisPage).children(':first').offset().top - this.opts.scrollOffset;
      $('body, html').animate({ scrollTop: top }, this.opts.scrollDelay, "swing");
    }, // end scroll()



    // Update Google Analytics on content load, not on scroll
    analytics: function(url){
      // Inform Google Analytics of the change; compatible with the new Universal Analytics script
      if ( typeof window.ga !== 'undefined' ) {
        window.ga('send', 'pageview', url);
      } else if ( typeof window._gaq !== 'undefined' ) {
        window._gaq.push(['_trackPageview', url]); // Legacy analytics; ref: https://github.com/browserstate/ajaxify/pull/25
      }
    }, // end analytics()



    // Utility function to get the root URL with trailing slash e.g. http(s)://yourdomain.com/
    root: function(){
      var
        port = document.location.port ? ':' + document.location.port : '',
        url = document.location.protocol + '//' + (document.location.hostname || document.location.host) + port + '/';
      return url;
    } // end root()
  };



  $.fn.pageloader = function (opts){
    return this.each(function(){
      if (!$.data(this, 'PG8_pageloader')) {
        $.data(this, 'PG8_pageloader', new PageLoader(opts));
      }
    });
  };



  // Extensible default settings
  $.fn.pageloader.defaults = {
    contentSel:    'main'       // The content selector
  , nextSel:       '.nav-next'  // Selector for the "next" navigation link
  , scrollDelay:   300          // Smooth scrolling delay
  , scrollOffset:  30           // To account for margins and such
  , pushDelay:     250          // How long to wait on scroll before trying to update history
  , infinDelay:    600          // How long to wait before pulling new content automatically
  , infinOffset:   300          // Height of the area below the last page in which infinite scrolling will be triggered
  , spinOpts: {                 // spin.js options; reference: https://fgnass.github.io/spin.js/
      lines:  25
    , length: 0
    , width:  4
    , radius: 25
    , speed:  1.5
    , trail:  40
    , top:    '15px'
    }
  };



  // Instantiate the plugin
  $(document.body).pageloader();

}).apply(PG8, [jQuery, document, window]);

/*! Picturefill - v2.2.0 - 2014-10-30
* http://scottjehl.github.io/picturefill
* Copyright (c) 2014 https://github.com/scottjehl/picturefill/blob/master/Authors.txt; Licensed MIT */
/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas, David Knight. Dual MIT/BSD license */

window.matchMedia || (window.matchMedia = function() {
	"use strict";

	// For browsers that support matchMedium api such as IE 9 and webkit
	var styleMedia = (window.styleMedia || window.media);

	// For those that don't support matchMedium
	if (!styleMedia) {
		var style       = document.createElement('style'),
			script      = document.getElementsByTagName('script')[0],
			info        = null;

		style.type  = 'text/css';
		style.id    = 'matchmediajs-test';

		script.parentNode.insertBefore(style, script);

		// 'style.currentStyle' is used by IE <= 8 and 'window.getComputedStyle' for all other browsers
		info = ('getComputedStyle' in window) && window.getComputedStyle(style, null) || style.currentStyle;

		styleMedia = {
			matchMedium: function(media) {
				var text = '@media ' + media + '{ #matchmediajs-test { width: 1px; } }';

				// 'style.styleSheet' is used by IE <= 8 and 'style.textContent' for all other browsers
				if (style.styleSheet) {
					style.styleSheet.cssText = text;
				} else {
					style.textContent = text;
				}

				// Test if media query is true or false
				return info.width === '1px';
			}
		};
	}

	return function(media) {
		return {
			matches: styleMedia.matchMedium(media || 'all'),
			media: media || 'all'
		};
	};
}());
/*! Picturefill - Responsive Images that work today.
*  Author: Scott Jehl, Filament Group, 2012 ( new proposal implemented by Shawn Jansepar )
*  License: MIT/GPLv2
*  Spec: http://picture.responsiveimages.org/
*/
(function( w, doc, image ) {
	// Enable strict mode
	"use strict";

	// If picture is supported, well, that's awesome. Let's get outta here...
	if ( w.HTMLPictureElement ) {
		w.picturefill = function() { };
		return;
	}

	// HTML shim|v it for old IE (IE9 will still need the HTML video tag workaround)
	doc.createElement( "picture" );

	// local object for method references and testing exposure
	var pf = {};

	// namespace
	pf.ns = "picturefill";

	// srcset support test
	(function() {
		pf.srcsetSupported = "srcset" in image;
		pf.sizesSupported = "sizes" in image;
	})();

	// just a string trim workaround
	pf.trim = function( str ) {
		return str.trim ? str.trim() : str.replace( /^\s+|\s+$/g, "" );
	};

	// just a string endsWith workaround
	pf.endsWith = function( str, suffix ) {
		return str.endsWith ? str.endsWith( suffix ) : str.indexOf( suffix, str.length - suffix.length ) !== -1;
	};

	/**
	 * Shortcut method for https://w3c.github.io/webappsec/specs/mixedcontent/#restricts-mixed-content ( for easy overriding in tests )
	 */
	pf.restrictsMixedContent = function() {
		return w.location.protocol === "https:";
	};
	/**
	 * Shortcut method for matchMedia ( for easy overriding in tests )
	 */

	pf.matchesMedia = function( media ) {
		return w.matchMedia && w.matchMedia( media ).matches;
	};

	// Shortcut method for `devicePixelRatio` ( for easy overriding in tests )
	pf.getDpr = function() {
		return ( w.devicePixelRatio || 1 );
	};

	/**
	 * Get width in css pixel value from a "length" value
	 * http://dev.w3.org/csswg/css-values-3/#length-value
	 */
	pf.getWidthFromLength = function( length ) {
		// If a length is specified and doesn’t contain a percentage, and it is greater than 0 or using `calc`, use it. Else, use the `100vw` default.
		length = length && length.indexOf( "%" ) > -1 === false && ( parseFloat( length ) > 0 || length.indexOf( "calc(" ) > -1 ) ? length : "100vw";

		/**
		 * If length is specified in  `vw` units, use `%` instead since the div we’re measuring
		 * is injected at the top of the document.
		 *
		 * TODO: maybe we should put this behind a feature test for `vw`?
		 */
		length = length.replace( "vw", "%" );

		// Create a cached element for getting length value widths
		if ( !pf.lengthEl ) {
			pf.lengthEl = doc.createElement( "div" );

			// Positioning styles help prevent padding/margin/width on `html` or `body` from throwing calculations off.
			pf.lengthEl.style.cssText = "border:0;display:block;font-size:1em;left:0;margin:0;padding:0;position:absolute;visibility:hidden";
		}

		pf.lengthEl.style.width = length;

		doc.body.appendChild(pf.lengthEl);

		// Add a class, so that everyone knows where this element comes from
		pf.lengthEl.className = "helper-from-picturefill-js";

		if ( pf.lengthEl.offsetWidth <= 0 ) {
			// Something has gone wrong. `calc()` is in use and unsupported, most likely. Default to `100vw` (`100%`, for broader support.):
			pf.lengthEl.style.width = doc.documentElement.offsetWidth + "px";
		}

		var offsetWidth = pf.lengthEl.offsetWidth;

		doc.body.removeChild( pf.lengthEl );

		return offsetWidth;
	};

	// container of supported mime types that one might need to qualify before using
	pf.types =  {};

	// Add support for standard mime types
	pf.types[ "image/jpeg" ] = true;
	pf.types[ "image/gif" ] = true;
	pf.types[ "image/png" ] = true;

	// test svg support
	pf.types[ "image/svg+xml" ] = doc.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image", "1.1");

	// test webp support, only when the markup calls for it
	pf.types[ "image/webp" ] = function() {
		// based on Modernizr's lossless img-webp test
		// note: asynchronous
		var type = "image/webp";

		image.onerror = function() {
			pf.types[ type ] = false;
			picturefill();
		};
		image.onload = function() {
			pf.types[ type ] = image.width === 1;
			picturefill();
		};
		image.src = "data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA=";
	};

	/**
	 * Takes a source element and checks if its type attribute is present and if so, supported
	 * Note: for type tests that require a async logic,
	 * you can define them as a function that'll run only if that type needs to be tested. Just make the test function call picturefill again when it is complete.
	 * see the async webp test above for example
	 */
	pf.verifyTypeSupport = function( source ) {
		var type = source.getAttribute( "type" );
		// if type attribute exists, return test result, otherwise return true
		if ( type === null || type === "" ) {
			return true;
		} else {
			// if the type test is a function, run it and return "pending" status. The function will rerun picturefill on pending elements once finished.
			if ( typeof( pf.types[ type ] ) === "function" ) {
				pf.types[ type ]();
				return "pending";
			} else {
				return pf.types[ type ];
			}
		}
	};

	// Parses an individual `size` and returns the length, and optional media query
	pf.parseSize = function( sourceSizeStr ) {
		var match = /(\([^)]+\))?\s*(.+)/g.exec( sourceSizeStr );
		return {
			media: match && match[1],
			length: match && match[2]
		};
	};

	// Takes a string of sizes and returns the width in pixels as a number
	pf.findWidthFromSourceSize = function( sourceSizeListStr ) {
		// Split up source size list, ie ( max-width: 30em ) 100%, ( max-width: 50em ) 50%, 33%
		//                            or (min-width:30em) calc(30% - 15px)
		var sourceSizeList = pf.trim( sourceSizeListStr ).split( /\s*,\s*/ ),
			winningLength;

		for ( var i = 0, len = sourceSizeList.length; i < len; i++ ) {
			// Match <media-condition>? length, ie ( min-width: 50em ) 100%
			var sourceSize = sourceSizeList[ i ],
				// Split "( min-width: 50em ) 100%" into separate strings
				parsedSize = pf.parseSize( sourceSize ),
				length = parsedSize.length,
				media = parsedSize.media;

			if ( !length ) {
				continue;
			}
			if ( !media || pf.matchesMedia( media ) ) {
				// if there is no media query or it matches, choose this as our winning length
				// and end algorithm
				winningLength = length;
				break;
			}
		}

		// pass the length to a method that can properly determine length
		// in pixels based on these formats: http://dev.w3.org/csswg/css-values-3/#length-value
		return pf.getWidthFromLength( winningLength );
	};

	pf.parseSrcset = function( srcset ) {
		/**
		 * A lot of this was pulled from Boris Smus’ parser for the now-defunct WHATWG `srcset`
		 * https://github.com/borismus/srcset-polyfill/blob/master/js/srcset-info.js
		 *
		 * 1. Let input (`srcset`) be the value passed to this algorithm.
		 * 2. Let position be a pointer into input, initially pointing at the start of the string.
		 * 3. Let raw candidates be an initially empty ordered list of URLs with associated 
		 *    unparsed descriptors. The order of entries in the list is the order in which entries 
		 *    are added to the list.
		 */
		var candidates = [];

		while ( srcset !== "" ) {
			srcset = srcset.replace( /^\s+/g, "" );

			// 5. Collect a sequence of characters that are not space characters, and let that be url.
			var pos = srcset.search(/\s/g),
				url, descriptor = null;

			if ( pos !== -1 ) {
				url = srcset.slice( 0, pos );

				var last = url.slice(-1);

				// 6. If url ends with a U+002C COMMA character (,), remove that character from url
				// and let descriptors be the empty string. Otherwise, follow these substeps
				// 6.1. If url is empty, then jump to the step labeled descriptor parser.

				if ( last === "," || url === "" ) {
					url = url.replace( /,+$/, "" );
					descriptor = "";
				}
				srcset = srcset.slice( pos + 1 );

				// 6.2. Collect a sequence of characters that are not U+002C COMMA characters (,), and 
				// let that be descriptors.
				if ( descriptor === null ) {
					var descpos = srcset.indexOf( "," );
					if ( descpos !== -1 ) {
						descriptor = srcset.slice( 0, descpos );
						srcset = srcset.slice( descpos + 1 );
					} else {
						descriptor = srcset;
						srcset = "";
					}
				}
			} else {
				url = srcset;
				srcset = "";
			}

			// 7. Add url to raw candidates, associated with descriptors.
			if ( url || descriptor ) {
				candidates.push({
					url: url,
					descriptor: descriptor
				});
			}
		}
		return candidates;
	};

	pf.parseDescriptor = function( descriptor, sizesattr ) {
		// 11. Descriptor parser: Let candidates be an initially empty source set. The order of entries in the list 
		// is the order in which entries are added to the list.
		var sizes = sizesattr || "100vw",
			sizeDescriptor = descriptor && descriptor.replace( /(^\s+|\s+$)/g, "" ),
			widthInCssPixels = pf.findWidthFromSourceSize( sizes ),
			resCandidate;

			if ( sizeDescriptor ) {
				var splitDescriptor = sizeDescriptor.split(" ");

				for (var i = splitDescriptor.length - 1; i >= 0; i--) {
					var curr = splitDescriptor[ i ],
						lastchar = curr && curr.slice( curr.length - 1 );

					if ( ( lastchar === "h" || lastchar === "w" ) && !pf.sizesSupported ) {
						resCandidate = parseFloat( ( parseInt( curr, 10 ) / widthInCssPixels ) );
					} else if ( lastchar === "x" ) {
						var res = curr && parseFloat( curr, 10 );
						resCandidate = res && !isNaN( res ) ? res : 1;
					}
				}
			}
		return resCandidate || 1;
	};

	/**
	 * Takes a srcset in the form of url/
	 * ex. "images/pic-medium.png 1x, images/pic-medium-2x.png 2x" or
	 *     "images/pic-medium.png 400w, images/pic-medium-2x.png 800w" or
	 *     "images/pic-small.png"
	 * Get an array of image candidates in the form of
	 *      {url: "/foo/bar.png", resolution: 1}
	 * where resolution is http://dev.w3.org/csswg/css-values-3/#resolution-value
	 * If sizes is specified, resolution is calculated
	 */
	pf.getCandidatesFromSourceSet = function( srcset, sizes ) {
		var candidates = pf.parseSrcset( srcset ),
			formattedCandidates = [];

		for ( var i = 0, len = candidates.length; i < len; i++ ) {
			var candidate = candidates[ i ];

			formattedCandidates.push({
				url: candidate.url,
				resolution: pf.parseDescriptor( candidate.descriptor, sizes )
			});
		}
		return formattedCandidates;
	};

	/**
	 * if it's an img element and it has a srcset property,
	 * we need to remove the attribute so we can manipulate src
	 * (the property's existence infers native srcset support, and a srcset-supporting browser will prioritize srcset's value over our winning picture candidate)
	 * this moves srcset's value to memory for later use and removes the attr
	 */
	pf.dodgeSrcset = function( img ) {
		if ( img.srcset ) {
			img[ pf.ns ].srcset = img.srcset;
			img.removeAttribute( "srcset" );
		}
	};

	// Accept a source or img element and process its srcset and sizes attrs
	pf.processSourceSet = function( el ) {
		var srcset = el.getAttribute( "srcset" ),
			sizes = el.getAttribute( "sizes" ),
			candidates = [];

		// if it's an img element, use the cached srcset property (defined or not)
		if ( el.nodeName.toUpperCase() === "IMG" && el[ pf.ns ] && el[ pf.ns ].srcset ) {
			srcset = el[ pf.ns ].srcset;
		}

		if ( srcset ) {
			candidates = pf.getCandidatesFromSourceSet( srcset, sizes );
		}
		return candidates;
	};

	pf.applyBestCandidate = function( candidates, picImg ) {
		var candidate,
			length,
			bestCandidate;

		candidates.sort( pf.ascendingSort );

		length = candidates.length;
		bestCandidate = candidates[ length - 1 ];

		for ( var i = 0; i < length; i++ ) {
			candidate = candidates[ i ];
			if ( candidate.resolution >= pf.getDpr() ) {
				bestCandidate = candidate;
				break;
			}
		}

		if ( bestCandidate && !pf.endsWith( picImg.src, bestCandidate.url ) ) {
			if ( pf.restrictsMixedContent() && bestCandidate.url.substr(0, "http:".length).toLowerCase() === "http:" ) {
				if ( typeof console !== undefined ) {
					console.warn( "Blocked mixed content image " + bestCandidate.url );
				}
			} else {
				picImg.src = bestCandidate.url;
				// currentSrc attribute and property to match
				// http://picture.responsiveimages.org/#the-img-element
				picImg.currentSrc = picImg.src;

				var style = picImg.style || {},
					WebkitBackfaceVisibility = "webkitBackfaceVisibility" in style,
					currentZoom = style.zoom;

				if (WebkitBackfaceVisibility) { // See: https://github.com/scottjehl/picturefill/issues/332
					style.zoom = ".999";

					WebkitBackfaceVisibility = picImg.offsetWidth;

					style.zoom = currentZoom;
				}
			}
		}
	};

	pf.ascendingSort = function( a, b ) {
		return a.resolution - b.resolution;
	};

	/**
	 * In IE9, <source> elements get removed if they aren't children of
	 * video elements. Thus, we conditionally wrap source elements
	 * using <!--[if IE 9]><video style="display: none;"><![endif]-->
	 * and must account for that here by moving those source elements
	 * back into the picture element.
	 */
	pf.removeVideoShim = function( picture ) {
		var videos = picture.getElementsByTagName( "video" );
		if ( videos.length ) {
			var video = videos[ 0 ],
				vsources = video.getElementsByTagName( "source" );
			while ( vsources.length ) {
				picture.insertBefore( vsources[ 0 ], video );
			}
			// Remove the video element once we're finished removing its children
			video.parentNode.removeChild( video );
		}
	};

	/**
	 * Find all `img` elements, and add them to the candidate list if they have
	 * a `picture` parent, a `sizes` attribute in basic `srcset` supporting browsers,
	 * a `srcset` attribute at all, and they haven’t been evaluated already.
	 */
	pf.getAllElements = function() {
		var elems = [],
			imgs = doc.getElementsByTagName( "img" );

		for ( var h = 0, len = imgs.length; h < len; h++ ) {
			var currImg = imgs[ h ];

			if ( currImg.parentNode.nodeName.toUpperCase() === "PICTURE" ||
			( currImg.getAttribute( "srcset" ) !== null ) || currImg[ pf.ns ] && currImg[ pf.ns ].srcset !== null ) {
				elems.push( currImg );
			}
		}
		return elems;
	};

	pf.getMatch = function( img, picture ) {
		var sources = picture.childNodes,
			match;

		// Go through each child, and if they have media queries, evaluate them
		for ( var j = 0, slen = sources.length; j < slen; j++ ) {
			var source = sources[ j ];

			// ignore non-element nodes
			if ( source.nodeType !== 1 ) {
				continue;
			}

			// Hitting the `img` element that started everything stops the search for `sources`.
			// If no previous `source` matches, the `img` itself is evaluated later.
			if ( source === img ) {
				return match;
			}

			// ignore non-`source` nodes
			if ( source.nodeName.toUpperCase() !== "SOURCE" ) {
				continue;
			}
			// if it's a source element that has the `src` property set, throw a warning in the console
			if ( source.getAttribute( "src" ) !== null && typeof console !== undefined ) {
				console.warn("The `src` attribute is invalid on `picture` `source` element; instead, use `srcset`.");
			}

			var media = source.getAttribute( "media" );

			// if source does not have a srcset attribute, skip
			if ( !source.getAttribute( "srcset" ) ) {
				continue;
			}

			// if there's no media specified, OR w.matchMedia is supported
			if ( ( !media || pf.matchesMedia( media ) ) ) {
				var typeSupported = pf.verifyTypeSupport( source );

				if ( typeSupported === true ) {
					match = source;
					break;
				} else if ( typeSupported === "pending" ) {
					return false;
				}
			}
		}

		return match;
	};

	function picturefill( opt ) {
		var elements,
			element,
			parent,
			firstMatch,
			candidates,
			options = opt || {};

		elements = options.elements || pf.getAllElements();

		// Loop through all elements
		for ( var i = 0, plen = elements.length; i < plen; i++ ) {
			element = elements[ i ];
			parent = element.parentNode;
			firstMatch = undefined;
			candidates = undefined;

			// immediately skip non-`img` nodes
			if ( element.nodeName.toUpperCase() !== "IMG" ) {
				continue;
			}

			// expando for caching data on the img
			if ( !element[ pf.ns ] ) {
				element[ pf.ns ] = {};
			}

			// if the element has already been evaluated, skip it unless
			// `options.reevaluate` is set to true ( this, for example,
			// is set to true when running `picturefill` on `resize` ).
			if ( !options.reevaluate && element[ pf.ns ].evaluated ) {
				continue;
			}

			// if `img` is in a `picture` element
			if ( parent.nodeName.toUpperCase() === "PICTURE" ) {

				// IE9 video workaround
				pf.removeVideoShim( parent );

				// return the first match which might undefined
				// returns false if there is a pending source
				// TODO the return type here is brutal, cleanup
				firstMatch = pf.getMatch( element, parent );

				// if any sources are pending in this picture due to async type test(s)
				// remove the evaluated attr and skip for now ( the pending test will
				// rerun picturefill on this element when complete)
				if ( firstMatch === false ) {
					continue;
				}
			} else {
				firstMatch = undefined;
			}

			// Cache and remove `srcset` if present and we’re going to be doing `picture`/`srcset`/`sizes` polyfilling to it.
			if ( parent.nodeName.toUpperCase() === "PICTURE" ||
			( element.srcset && !pf.srcsetSupported ) ||
			( !pf.sizesSupported && ( element.srcset && element.srcset.indexOf("w") > -1 ) ) ) {
				pf.dodgeSrcset( element );
			}

			if ( firstMatch ) {
				candidates = pf.processSourceSet( firstMatch );
				pf.applyBestCandidate( candidates, element );
			} else {
				// No sources matched, so we’re down to processing the inner `img` as a source.
				candidates = pf.processSourceSet( element );

				if ( element.srcset === undefined || element[ pf.ns ].srcset ) {
					// Either `srcset` is completely unsupported, or we need to polyfill `sizes` functionality.
					pf.applyBestCandidate( candidates, element );
				} // Else, resolution-only `srcset` is supported natively.
			}

			// set evaluated to true to avoid unnecessary reparsing
			element[ pf.ns ].evaluated = true;
		}
	}

	/**
	 * Sets up picture polyfill by polling the document and running
	 * the polyfill every 250ms until the document is ready.
	 * Also attaches picturefill on resize
	 */
	function runPicturefill() {
		picturefill();
		var intervalId = setInterval( function() {
			// When the document has finished loading, stop checking for new images
			// https://github.com/ded/domready/blob/master/ready.js#L15
			picturefill();
			if ( /^loaded|^i|^c/.test( doc.readyState ) ) {
				clearInterval( intervalId );
				return;
			}
		}, 250 );

		function checkResize() {
			var resizeThrottle;

			if ( !w._picturefillWorking ) {
				w._picturefillWorking = true;
				w.clearTimeout( resizeThrottle );
				resizeThrottle = w.setTimeout( function() {
					picturefill({ reevaluate: true });
					w._picturefillWorking = false;
				}, 60 );
			}
		}

		if ( w.addEventListener ) {
			w.addEventListener( "resize", checkResize, false );
		} else if ( w.attachEvent ) {
			w.attachEvent( "onresize", checkResize );
		}
	}

	runPicturefill();

	/* expose methods for testing */
	picturefill._ = pf;

	/* expose picturefill */
	if ( typeof module === "object" && typeof module.exports === "object" ) {
		// CommonJS, just export
		module.exports = picturefill;
	} else if ( typeof define === "function" && define.amd ) {
		// AMD support
		define( function() { return picturefill; } );
	} else if ( typeof w === "object" ) {
		// If no AMD and we are in the browser, attach to window
		w.picturefill = picturefill;
	}

} )( this, this.document, new this.Image() );

// Prism: Lightweight, robust, elegant syntax highlighting
// MIT license http://www.opensource.org/licenses/mit-license.php/
// @author Lea Verou http://lea.verou.me
// Custom build for Pendrell

;(function(){

// Private helper vars
var lang = /\blang(?:uage)?-(?!\*)(\w+)\b/i;

var _ = self.Prism = {
	util: {
		type: function (o) {
			return Object.prototype.toString.call(o).match(/\[object (\w+)\]/)[1];
		},

		// Deep clone a language definition (e.g. to extend it)
		clone: function (o) {
			var type = _.util.type(o);

			switch (type) {
				case 'Object':
					var clone = {};

					for (var key in o) {
						if (o.hasOwnProperty(key)) {
							clone[key] = _.util.clone(o[key]);
						}
					}

					return clone;

				case 'Array':
					return o.slice();
			}

			return o;
		}
	},

	languages: {
		extend: function (id, redef) {
			var lang = _.util.clone(_.languages[id]);

			for (var key in redef) {
				lang[key] = redef[key];
			}

			return lang;
		},

		// Insert a token before another token in a language literal
		insertBefore: function (inside, before, insert, root) {
			root = root || _.languages;
			var grammar = root[inside];
			var ret = {};

			for (var token in grammar) {

				if (grammar.hasOwnProperty(token)) {

					if (token === before) {

						for (var newToken in insert) {

							if (insert.hasOwnProperty(newToken)) {
								ret[newToken] = insert[newToken];
							}
						}
					}

					ret[token] = grammar[token];
				}
			}

			return root[inside] = ret;
		},

		// Traverse a language definition with Depth First Search
		DFS: function(o, callback) {
			for (var i in o) {
				callback.call(o, i, o[i]);

				if (_.util.type(o) === 'Object') {
					_.languages.DFS(o[i], callback);
				}
			}
		}
	},

	highlightAll: function(async, callback) {
		var elements = document.querySelectorAll('code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code');

		for (var i=0, element; element = elements[i++];) {
			_.highlightElement(element, async === true, callback);
		}
	},

	highlightElement: function(element, async, callback) {
		// Find language
		var language, grammar, parent = element;

		while (parent && !lang.test(parent.className)) {
			parent = parent.parentNode;
		}

		if (parent) {
			language = (parent.className.match(lang) || [,''])[1];
			grammar = _.languages[language];
		}

		if (!grammar) {
			return;
		}

		// Set language on the element, if not present
		element.className = element.className.replace(lang, '').replace(/\s+/g, ' ') + ' language-' + language;

		// Set language on the parent, for styling
		parent = element.parentNode;

		if (/pre/i.test(parent.nodeName)) {
			parent.className = parent.className.replace(lang, '').replace(/\s+/g, ' ') + ' language-' + language;
		}

		var code = element.textContent;

		if(!code) {
			return;
		}

		code = code.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/\u00a0/g, ' ');

		var env = {
			element: element,
			language: language,
			grammar: grammar,
			code: code
		};

		_.hooks.run('before-highlight', env);

		if (async && self.Worker) {
			var worker = new Worker(_.filename);

			worker.onmessage = function(evt) {
				env.highlightedCode = Token.stringify(JSON.parse(evt.data), language);

				_.hooks.run('before-insert', env);

				env.element.innerHTML = env.highlightedCode;

				callback && callback.call(env.element);
				_.hooks.run('after-highlight', env);
			};

			worker.postMessage(JSON.stringify({
				language: env.language,
				code: env.code
			}));
		}
		else {
			env.highlightedCode = _.highlight(env.code, env.grammar, env.language)

			_.hooks.run('before-insert', env);

			env.element.innerHTML = env.highlightedCode;

			callback && callback.call(element);

			_.hooks.run('after-highlight', env);
		}
	},

	highlight: function (text, grammar, language) {
		return Token.stringify(_.tokenize(text, grammar), language);
	},

	tokenize: function(text, grammar, language) {
		var Token = _.Token;

		var strarr = [text];

		var rest = grammar.rest;

		if (rest) {
			for (var token in rest) {
				grammar[token] = rest[token];
			}

			delete grammar.rest;
		}

		tokenloop: for (var token in grammar) {
			if(!grammar.hasOwnProperty(token) || !grammar[token]) {
				continue;
			}

			var pattern = grammar[token],
				inside = pattern.inside,
				lookbehind = !!pattern.lookbehind,
				lookbehindLength = 0;

			pattern = pattern.pattern || pattern;

			for (var i=0; i<strarr.length; i++) { // Don’t cache length as it changes during the loop

				var str = strarr[i];

				if (strarr.length > text.length) {
					// Something went terribly wrong, ABORT, ABORT!
					break tokenloop;
				}

				if (str instanceof Token) {
					continue;
				}

				pattern.lastIndex = 0;

				var match = pattern.exec(str);

				if (match) {
					if(lookbehind) {
						lookbehindLength = match[1].length;
					}

					var from = match.index - 1 + lookbehindLength,
					    match = match[0].slice(lookbehindLength),
					    len = match.length,
					    to = from + len,
						before = str.slice(0, from + 1),
						after = str.slice(to + 1);

					var args = [i, 1];

					if (before) {
						args.push(before);
					}

					var wrapped = new Token(token, inside? _.tokenize(match, inside) : match);

					args.push(wrapped);

					if (after) {
						args.push(after);
					}

					Array.prototype.splice.apply(strarr, args);
				}
			}
		}

		return strarr;
	},

	hooks: {
		all: {},

		add: function (name, callback) {
			var hooks = _.hooks.all;

			hooks[name] = hooks[name] || [];

			hooks[name].push(callback);
		},

		run: function (name, env) {
			var callbacks = _.hooks.all[name];

			if (!callbacks || !callbacks.length) {
				return;
			}

			for (var i=0, callback; callback = callbacks[i++];) {
				callback(env);
			}
		}
	}
};

var Token = _.Token = function(type, content) {
	this.type = type;
	this.content = content;
};

Token.stringify = function(o, language, parent) {
	if (typeof o == 'string') {
		return o;
	}

	if (Object.prototype.toString.call(o) == '[object Array]') {
		return o.map(function(element) {
			return Token.stringify(element, language, o);
		}).join('');
	}

	var env = {
		type: o.type,
		content: Token.stringify(o.content, language, parent),
		tag: 'span',
		classes: ['token', o.type],
		attributes: {},
		language: language,
		parent: parent
	};

	if (env.type == 'comment') {
		env.attributes['spellcheck'] = 'true';
	}

	_.hooks.run('wrap', env);

	var attributes = '';

	for (var name in env.attributes) {
		attributes += name + '="' + (env.attributes[name] || '') + '"';
	}

	return '<' + env.tag + ' class="' + env.classes.join(' ') + '" ' + attributes + '>' + env.content + '</' + env.tag + '>';

};

if (!self.document) {
	// In worker
	self.addEventListener('message', function(evt) {
		var message = JSON.parse(evt.data),
		    lang = message.language,
		    code = message.code;

		self.postMessage(JSON.stringify(_.tokenize(code, _.languages[lang])));
		self.close();
	}, false);

	return;
}

// Get current script and highlight
var script = document.getElementsByTagName('script');

script = script[script.length - 1];

if (script) {
	_.filename = script.src;

	if (document.addEventListener && !script.hasAttribute('data-manual')) {
		document.addEventListener('DOMContentLoaded', _.highlightAll);
	}
}

})();;
Prism.languages.markup = {
	'comment': /&lt;!--[\w\W]*?-->/g,
	'prolog': /&lt;\?.+?\?>/,
	'doctype': /&lt;!DOCTYPE.+?>/,
	'cdata': /&lt;!\[CDATA\[[\w\W]*?]]>/i,
	'tag': {
		pattern: /&lt;\/?[\w:-]+\s*(?:\s+[\w:-]+(?:=(?:("|')(\\?[\w\W])*?\1|[^\s'">=]+))?\s*)*\/?>/gi,
		inside: {
			'tag': {
				pattern: /^&lt;\/?[\w:-]+/i,
				inside: {
					'punctuation': /^&lt;\/?/,
					'namespace': /^[\w-]+?:/
				}
			},
			'attr-value': {
				pattern: /=(?:('|")[\w\W]*?(\1)|[^\s>]+)/gi,
				inside: {
					'punctuation': /=|>|"/g
				}
			},
			'punctuation': /\/?>/g,
			'attr-name': {
				pattern: /[\w:-]+/g,
				inside: {
					'namespace': /^[\w-]+?:/
				}
			}

		}
	},
	'entity': /&amp;#?[\da-z]{1,8};/gi
};

// Plugin to make entity title show the real entity, idea by Roman Komarov
Prism.hooks.add('wrap', function(env) {

	if (env.type === 'entity') {
		env.attributes['title'] = env.content.replace(/&amp;/, '&');
	}
});
;
Prism.languages.css = {
	'comment': /\/\*[\w\W]*?\*\//g,
	'atrule': {
		pattern: /@[\w-]+?.*?(;|(?=\s*{))/gi,
		inside: {
			'punctuation': /[;:]/g
		}
	},
	'url': /url\((["']?).*?\1\)/gi,
	'selector': /[^\{\}\s][^\{\};]*(?=\s*\{)/g,
	'property': /(\b|\B)[\w-]+(?=\s*:)/ig,
	'string': /("|')(\\?.)*?\1/g,
	'important': /\B!important\b/gi,
	'ignore': /&(lt|gt|amp);/gi,
	'punctuation': /[\{\};:]/g
};

if (Prism.languages.markup) {
	Prism.languages.insertBefore('markup', 'tag', {
		'style': {
			pattern: /(&lt;|<)style[\w\W]*?(>|&gt;)[\w\W]*?(&lt;|<)\/style(>|&gt;)/ig,
			inside: {
				'tag': {
					pattern: /(&lt;|<)style[\w\W]*?(>|&gt;)|(&lt;|<)\/style(>|&gt;)/ig,
					inside: Prism.languages.markup.tag.inside
				},
				rest: Prism.languages.css
			}
		}
	});
};
Prism.languages.clike = {
	'comment': {
		pattern: /(^|[^\\])(\/\*[\w\W]*?\*\/|(^|[^:])\/\/.*?(\r?\n|$))/g,
		lookbehind: true
	},
	'string': /("|')(\\?.)*?\1/g,
	'class-name': {
		pattern: /((?:(?:class|interface|extends|implements|trait|instanceof|new)\s+)|(?:catch\s+\())[a-z0-9_\.\\]+/ig,
		lookbehind: true,
		inside: {
			punctuation: /(\.|\\)/
		}
	},
	'keyword': /\b(if|else|while|do|for|return|in|instanceof|function|new|try|throw|catch|finally|null|break|continue)\b/g,
	'boolean': /\b(true|false)\b/g,
	'function': {
		pattern: /[a-z0-9_]+\(/ig,
		inside: {
			punctuation: /\(/
		}
	},
	'number': /\b-?(0x[\dA-Fa-f]+|\d*\.?\d+([Ee]-?\d+)?)\b/g,
	'operator': /[-+]{1,2}|!|&lt;=?|>=?|={1,3}|(&amp;){1,2}|\|?\||\?|\*|\/|\~|\^|\%/g,
	'ignore': /&(lt|gt|amp);/gi,
	'punctuation': /[{}[\];(),.:]/g
};
;
Prism.languages.javascript = Prism.languages.extend('clike', {
	'keyword': /\b(var|let|if|else|while|do|for|return|in|instanceof|function|get|set|new|with|typeof|try|throw|catch|finally|null|break|continue)\b/g,
	'number': /\b-?(0x[\dA-Fa-f]+|\d*\.?\d+([Ee]-?\d+)?|NaN|-?Infinity)\b/g
});

Prism.languages.insertBefore('javascript', 'keyword', {
	'regex': {
		pattern: /(^|[^/])\/(?!\/)(\[.+?]|\\.|[^/\r\n])+\/[gim]{0,3}(?=\s*($|[\r\n,.;})]))/g,
		lookbehind: true
	}
});

if (Prism.languages.markup) {
	Prism.languages.insertBefore('markup', 'tag', {
		'script': {
			pattern: /(&lt;|<)script[\w\W]*?(>|&gt;)[\w\W]*?(&lt;|<)\/script(>|&gt;)/ig,
			inside: {
				'tag': {
					pattern: /(&lt;|<)script[\w\W]*?(>|&gt;)|(&lt;|<)\/script(>|&gt;)/ig,
					inside: Prism.languages.markup.tag.inside
				},
				rest: Prism.languages.javascript
			}
		}
	});
}
;
/**
 * Original by Aaron Harun: http://aahacreative.com/2012/07/31/php-syntax-highlighting-prism/
 * Modified by Miles Johnson: http://milesj.me
 *
 * Supports the following:
 * 		- Extends clike syntax
 * 		- Support for PHP 5.3 and 5.4 (namespaces, traits, etc)
 * 		- Smarter constant and function matching
 *
 * Adds the following new token classes:
 * 		constant, delimiter, variable, function, package
 */

Prism.languages.php = Prism.languages.extend('clike', {
	'keyword': /\b(and|or|xor|array|as|break|case|cfunction|class|const|continue|declare|default|die|do|else|elseif|enddeclare|endfor|endforeach|endif|endswitch|endwhile|extends|for|foreach|function|include|include_once|global|if|new|return|static|switch|use|require|require_once|var|while|abstract|interface|public|implements|private|protected|parent|throw|null|echo|print|trait|namespace|final|yield|goto|instanceof|finally|try|catch)\b/ig,
	'constant': /\b[A-Z0-9_]{2,}\b/g
});

Prism.languages.insertBefore('php', 'keyword', {
	'delimiter': /(\?>|&lt;\?php|&lt;\?)/ig,
	'variable': /(\$\w+)\b/ig,
	'package': {
		pattern: /(\\|namespace\s+|use\s+)[\w\\]+/g,
		lookbehind: true,
		inside: {
			punctuation: /\\/
		}
	}
});

// Must be defined after the function pattern
Prism.languages.insertBefore('php', 'operator', {
	'property': {
		pattern: /(->)[\w]+/g,
		lookbehind: true
	}
});

// Add HTML support of the markup language exists
if (Prism.languages.markup) {

	// Tokenize all inline PHP blocks that are wrapped in <?php ?>
	// This allows for easy PHP + markup highlighting
	Prism.hooks.add('before-highlight', function(env) {
		if (env.language !== 'php') {
			return;
		}

		env.tokenStack = [];

		env.code = env.code.replace(/(?:&lt;\?php|&lt;\?|<\?php|<\?)[\w\W]*?(?:\?&gt;|\?>)/ig, function(match) {
			env.tokenStack.push(match);

			return '{{{PHP' + env.tokenStack.length + '}}}';
		});
	});

	// Re-insert the tokens after highlighting
	Prism.hooks.add('after-highlight', function(env) {
		if (env.language !== 'php') {
			return;
		}

		for (var i = 0, t; t = env.tokenStack[i]; i++) {
			env.highlightedCode = env.highlightedCode.replace('{{{PHP' + (i + 1) + '}}}', Prism.highlight(t, env.grammar, 'php'));
		}

		env.element.innerHTML = env.highlightedCode;
	});

	// Wrap tokens in classes that are missing them
	Prism.hooks.add('wrap', function(env) {
		if (env.language === 'php' && env.type === 'markup') {
			env.content = env.content.replace(/(\{\{\{PHP[0-9]+\}\}\})/g, "<span class=\"token php\">$1</span>");
		}
	});

	// Add the rules before all others
	Prism.languages.insertBefore('php', 'comment', {
		'markup': {
			pattern: /(&lt;|<)[^?]\/?(.*?)(>|&gt;)/g,
			inside: Prism.languages.markup
		},
		'php': /\{\{\{PHP[0-9]+\}\}\}/g
	});
}
;
Prism.languages.coffeescript = Prism.languages.extend('javascript', {
	'block-comment': /([#]{3}\s*\r?\n(.*\s*\r*\n*)\s*?\r?\n[#]{3})/g,
	'comment': /(\s|^)([#]{1}[^#^\r^\n]{2,}?(\r?\n|$))/g,
	'keyword': /\b(this|window|delete|class|extends|namespace|extend|ar|let|if|else|while|do|for|each|of|return|in|instanceof|new|with|typeof|try|catch|finally|null|undefined|break|continue)\b/g
});

Prism.languages.insertBefore('coffeescript', 'keyword', {
	'function': {
		pattern: /[a-z|A-z]+\s*[:|=]\s*(\([.|a-z\s|,|:|{|}|\"|\'|=]*\))?\s*-&gt;/gi,
		inside: {
			'function-name': /[_?a-z-|A-Z-]+(\s*[:|=])| @[_?$?a-z-|A-Z-]+(\s*)| /g,
			'operator': /[-+]{1,2}|!|=?&lt;|=?&gt;|={1,2}|(&amp;){1,2}|\|?\||\?|\*|\//g
		}
	},
	'attr-name': /[_?a-z-|A-Z-]+(\s*:)| @[_?$?a-z-|A-Z-]+(\s*)| /g
});
;
Prism.languages.scss = Prism.languages.extend('css', {
	'comment': {
		pattern: /(^|[^\\])(\/\*[\w\W]*?\*\/|\/\/.*?(\r?\n|$))/g,
		lookbehind: true
	},
	// aturle is just the @***, not the entire rule (to highlight var & stuffs)
	// + add ability to highlight number & unit for media queries
	'atrule': /@[\w-]+(?=\s+(\(|\{|;))/gi,
	// url, compassified
	'url': /([-a-z]+-)*url(?=\()/gi,
	// CSS selector regex is not appropriate for Sass
	// since there can be lot more things (var, @ directive, nesting..)
	// a selector must start at the end of a property or after a brace (end of other rules or nesting)
	// it can contain some caracters that aren't used for defining rules or end of selector, & (parent selector), or interpolated variable
	// the end of a selector is found when there is no rules in it ( {} or {\s}) or if there is a property (because an interpolated var
	// can "pass" as a selector- e.g: proper#{$erty})
	// this one was ard to do, so please be careful if you edit this one :)
	'selector': /([^@;\{\}\(\)]?([^@;\{\}\(\)]|&amp;|\#\{\$[-_\w]+\})+)(?=\s*\{(\}|\s|[^\}]+(:|\{)[^\}]+))/gm
});

Prism.languages.insertBefore('scss', 'atrule', {
	'keyword': /@(if|else if|else|for|each|while|import|extend|debug|warn|mixin|include|function|return)|(?=@for\s+\$[-_\w]+\s)+from/i
});

Prism.languages.insertBefore('scss', 'property', {
	// var and interpolated vars
	'variable': /((\$[-_\w]+)|(#\{\$[-_\w]+\}))/i
});

Prism.languages.insertBefore('scss', 'ignore', {
	'placeholder': /%[-_\w]+/i,
	'statement': /\B!(default|optional)\b/gi,
	'boolean': /\b(true|false)\b/g,
	'null': /\b(null)\b/g,
	'operator': /\s+([-+]{1,2}|={1,2}|!=|\|?\||\?|\*|\/|\%)\s+/g
});
;
Prism.languages.sql= {
	'comment': {
		pattern: /(^|[^\\])(\/\*[\w\W]*?\*\/|((--)|(\/\/)).*?(\r?\n|$))/g,
		lookbehind: true
	},
	'string' : /("|')(\\?.)*?\1/g,
	'keyword' : /\b(ACTION|ADD|AFTER|ALGORITHM|ALTER|ANALYZE|APPLY|AS|ASC|AUTHORIZATION|BACKUP|BDB|BEGIN|BERKELEYDB|BIGINT|BINARY|BIT|BLOB|BOOL|BOOLEAN|BREAK|BROWSE|BTREE|BULK|BY|CALL|CASCADE|CASCADED|CASE|CHAIN|CHAR VARYING|CHARACTER VARYING|CHECK|CHECKPOINT|CLOSE|CLUSTERED|COALESCE|COLUMN|COLUMNS|COMMENT|COMMIT|COMMITTED|COMPUTE|CONNECT|CONSISTENT|CONSTRAINT|CONTAINS|CONTAINSTABLE|CONTINUE|CONVERT|CREATE|CROSS|CURRENT|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURSOR|DATA|DATABASE|DATABASES|DATETIME|DBCC|DEALLOCATE|DEC|DECIMAL|DECLARE|DEFAULT|DEFINER|DELAYED|DELETE|DENY|DESC|DESCRIBE|DETERMINISTIC|DISABLE|DISCARD|DISK|DISTINCT|DISTINCTROW|DISTRIBUTED|DO|DOUBLE|DOUBLE PRECISION|DROP|DUMMY|DUMP|DUMPFILE|DUPLICATE KEY|ELSE|ENABLE|ENCLOSED BY|END|ENGINE|ENUM|ERRLVL|ERRORS|ESCAPE|ESCAPED BY|EXCEPT|EXEC|EXECUTE|EXIT|EXPLAIN|EXTENDED|FETCH|FIELDS|FILE|FILLFACTOR|FIRST|FIXED|FLOAT|FOLLOWING|FOR|FOR EACH ROW|FORCE|FOREIGN|FREETEXT|FREETEXTTABLE|FROM|FULL|FUNCTION|GEOMETRY|GEOMETRYCOLLECTION|GLOBAL|GOTO|GRANT|GROUP|HANDLER|HASH|HAVING|HOLDLOCK|IDENTITY|IDENTITY_INSERT|IDENTITYCOL|IF|IGNORE|IMPORT|INDEX|INFILE|INNER|INNODB|INOUT|INSERT|INT|INTEGER|INTERSECT|INTO|INVOKER|ISOLATION LEVEL|JOIN|KEY|KEYS|KILL|LANGUAGE SQL|LAST|LEFT|LIMIT|LINENO|LINES|LINESTRING|LOAD|LOCAL|LOCK|LONGBLOB|LONGTEXT|MATCH|MATCHED|MEDIUMBLOB|MEDIUMINT|MEDIUMTEXT|MERGE|MIDDLEINT|MODIFIES SQL DATA|MODIFY|MULTILINESTRING|MULTIPOINT|MULTIPOLYGON|NATIONAL|NATIONAL CHAR VARYING|NATIONAL CHARACTER|NATIONAL CHARACTER VARYING|NATIONAL VARCHAR|NATURAL|NCHAR|NCHAR VARCHAR|NEXT|NO|NO SQL|NOCHECK|NOCYCLE|NONCLUSTERED|NULLIF|NUMERIC|OF|OFF|OFFSETS|ON|OPEN|OPENDATASOURCE|OPENQUERY|OPENROWSET|OPTIMIZE|OPTION|OPTIONALLY|ORDER|OUT|OUTER|OUTFILE|OVER|PARTIAL|PARTITION|PERCENT|PIVOT|PLAN|POINT|POLYGON|PRECEDING|PRECISION|PREV|PRIMARY|PRINT|PRIVILEGES|PROC|PROCEDURE|PUBLIC|PURGE|QUICK|RAISERROR|READ|READS SQL DATA|READTEXT|REAL|RECONFIGURE|REFERENCES|RELEASE|RENAME|REPEATABLE|REPLICATION|REQUIRE|RESTORE|RESTRICT|RETURN|RETURNS|REVOKE|RIGHT|ROLLBACK|ROUTINE|ROWCOUNT|ROWGUIDCOL|ROWS?|RTREE|RULE|SAVE|SAVEPOINT|SCHEMA|SELECT|SERIAL|SERIALIZABLE|SESSION|SESSION_USER|SET|SETUSER|SHARE MODE|SHOW|SHUTDOWN|SIMPLE|SMALLINT|SNAPSHOT|SOME|SONAME|START|STARTING BY|STATISTICS|STATUS|STRIPED|SYSTEM_USER|TABLE|TABLES|TABLESPACE|TEMPORARY|TEMPTABLE|TERMINATED BY|TEXT|TEXTSIZE|THEN|TIMESTAMP|TINYBLOB|TINYINT|TINYTEXT|TO|TOP|TRAN|TRANSACTION|TRANSACTIONS|TRIGGER|TRUNCATE|TSEQUAL|TYPE|TYPES|UNBOUNDED|UNCOMMITTED|UNDEFINED|UNION|UNPIVOT|UPDATE|UPDATETEXT|USAGE|USE|USER|USING|VALUE|VALUES|VARBINARY|VARCHAR|VARCHARACTER|VARYING|VIEW|WAITFOR|WARNINGS|WHEN|WHERE|WHILE|WITH|WITH ROLLUP|WITHIN|WORK|WRITE|WRITETEXT)\b/gi,
	'boolean' : /\b(TRUE|FALSE|NULL)\b/gi,
	'number' : /\b-?(0x)?\d*\.?[\da-f]+\b/g,
	'operator' : /\b(ALL|AND|ANY|BETWEEN|EXISTS|IN|LIKE|NOT|OR|IS|UNIQUE|CHARACTER SET|COLLATE|DIV|OFFSET|REGEXP|RLIKE|SOUNDS LIKE|XOR)\b|[-+]{1}|!|=?&lt;|=?&gt;|={1}|(&amp;){1,2}|\|?\||\?|\*|\//gi,
	'ignore' : /&(lt|gt|amp);/gi,
	'punctuation' : /[;[\]()`,.]/g
};
;

// Navigation.js adapted from _s; changed to toggled the entire site navigation element, not just the menu within it
;(function() {
	var nav 			= document.getElementById('site-navigation'),
			menu 			= nav.getElementsByTagName('ul')[0],
			button 		= document.getElementById('responsive-menu-toggle');

	// Early exit if we're missing anything essential
	if (!nav || typeof button === 'undefined') {
		return;
	}

	// Hide button if menu is empty and return early
	if (typeof menu === 'undefined') {
		button.style.display = 'none';
		return;
	}

	// Toggle navigation; add or remove a class to both the button and the nav element itself
	button.onclick = function() {
		if (button.className.indexOf( 'toggled' ) !== -1) {
			button.className = button.className.replace(' toggled', '');
		} else {
			button.className += ' toggled';
		}

		if (nav.className.indexOf( 'toggled' ) !== -1) {
			nav.className = nav.className.replace(' toggled', '');
		} else {
			nav.className += ' toggled';
		}
	};
} )();

// ==== CORE ==== //

// Anything entered here will end up at the top of pendrell-core.js

// For compatibility with Ajaxinate be sure to bind events to a DOM element that isn't ever replaced (e.g. `body`)

;(function($){
  $(function(){ // Shortcut to $(document).ready(handler);
    // Insert code here
  });
}(jQuery));
