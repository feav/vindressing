/**
 * Makes "skip to content" link work correctly in IE9, Chrome, and Opera
 * for better accessibility.
 *
 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
 */

( function() {
    var ua = navigator.userAgent.toLowerCase();

    if ( ( ua.indexOf( 'webkit' ) > -1 || ua.indexOf( 'opera' ) > -1 || ua.indexOf( 'msie' ) > -1 ) &&
        document.getElementById && window.addEventListener ) {

        window.addEventListener( 'hashchange', function() {
            var element = document.getElementById( location.hash.substring( 1 ) );

            if ( element ) {
                if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.nodeName ) ) {
                    element.tabIndex = -1;
                }

                element.focus();
            }
        }, false );
    }
} )();
jQuery(function() {
    jQuery('[href=#down]').on('click', function(e) {
        e.preventDefault();
        jQuery('html, body').animate({ scrollTop: jQuery(jQuery(this).attr('href')).offset().top}, 500, 'linear');
    });
});
/*!
 * EventEmitter v4.2.6 - git.io/ee
 * Oliver Caldwell
 * MIT license
 * @preserve
 */

(function () {
    
    'use strict';
    /**
     * Class for managing events.
     * Can be extended to provide event functionality in other classes.
     *
     * @class EventEmitter Manages event registering and emitting.
     */
    function EventEmitter() {}

    // Shortcuts to improve speed and size
    var proto = EventEmitter.prototype;
    var exports = this;
    var originalGlobalValue = exports.EventEmitter;

    /**
     * Finds the index of the listener for the event in it's storage array.
     *
     * @param {Function[]} listeners Array of listeners to search through.
     * @param {Function} listener Method to look for.
     * @return {Number} Index of the specified listener, -1 if not found
     * @api private
     */
    function indexOfListener(listeners, listener) {
        var i = listeners.length;
        while (i--) {
            if (listeners[i].listener === listener) {
                return i;
            }
        }

        return -1;
    }

    /**
     * Alias a method while keeping the context correct, to allow for overwriting of target method.
     *
     * @param {String} name The name of the target method.
     * @return {Function} The aliased method
     * @api private
     */
    function alias(name) {
        return function aliasClosure() {
            return this[name].apply(this, arguments);
        };
    }

    /**
     * Returns the listener array for the specified event.
     * Will initialise the event object and listener arrays if required.
     * Will return an object if you use a regex search. The object contains keys for each matched event. So /ba[rz]/ might return an object containing bar and baz. But only if you have either defined them with defineEvent or added some listeners to them.
     * Each property in the object response is an array of listener functions.
     *
     * @param {String|RegExp} evt Name of the event to return the listeners from.
     * @return {Function[]|Object} All listener functions for the event.
     */
    proto.getListeners = function getListeners(evt) {
        var events = this._getEvents();
        var response;
        var key;

        // Return a concatenated array of all matching events if
        // the selector is a regular expression.
        if (typeof evt === 'object') {
            response = {};
            for (key in events) {
                if (events.hasOwnProperty(key) && evt.test(key)) {
                    response[key] = events[key];
                }
            }
        }
        else {
            response = events[evt] || (events[evt] = []);
        }

        return response;
    };

    /**
     * Takes a list of listener objects and flattens it into a list of listener functions.
     *
     * @param {Object[]} listeners Raw listener objects.
     * @return {Function[]} Just the listener functions.
     */
    proto.flattenListeners = function flattenListeners(listeners) {
        var flatListeners = [];
        var i;

        for (i = 0; i < listeners.length; i += 1) {
            flatListeners.push(listeners[i].listener);
        }

        return flatListeners;
    };

    /**
     * Fetches the requested listeners via getListeners but will always return the results inside an object. This is mainly for internal use but others may find it useful.
     *
     * @param {String|RegExp} evt Name of the event to return the listeners from.
     * @return {Object} All listener functions for an event in an object.
     */
    proto.getListenersAsObject = function getListenersAsObject(evt) {
        var listeners = this.getListeners(evt);
        var response;

        if (listeners instanceof Array) {
            response = {};
            response[evt] = listeners;
        }

        return response || listeners;
    };

    /**
     * Adds a listener function to the specified event.
     * The listener will not be added if it is a duplicate.
     * If the listener returns true then it will be removed after it is called.
     * If you pass a regular expression as the event name then the listener will be added to all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to attach the listener to.
     * @param {Function} listener Method to be called when the event is emitted. If the function returns true then it will be removed after calling.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.addListener = function addListener(evt, listener) {
        var listeners = this.getListenersAsObject(evt);
        var listenerIsWrapped = typeof listener === 'object';
        var key;

        for (key in listeners) {
            if (listeners.hasOwnProperty(key) && indexOfListener(listeners[key], listener) === -1) {
                listeners[key].push(listenerIsWrapped ? listener : {
                    listener: listener,
                    once: false
                });
            }
        }

        return this;
    };

    /**
     * Alias of addListener
     */
    proto.on = alias('addListener');

    /**
     * Semi-alias of addListener. It will add a listener that will be
     * automatically removed after it's first execution.
     *
     * @param {String|RegExp} evt Name of the event to attach the listener to.
     * @param {Function} listener Method to be called when the event is emitted. If the function returns true then it will be removed after calling.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.addOnceListener = function addOnceListener(evt, listener) {
        return this.addListener(evt, {
            listener: listener,
            once: true
        });
    };

    /**
     * Alias of addOnceListener.
     */
    proto.once = alias('addOnceListener');

    /**
     * Defines an event name. This is required if you want to use a regex to add a listener to multiple events at once. If you don't do this then how do you expect it to know what event to add to? Should it just add to every possible match for a regex? No. That is scary and bad.
     * You need to tell it what event names should be matched by a regex.
     *
     * @param {String} evt Name of the event to create.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.defineEvent = function defineEvent(evt) {
        this.getListeners(evt);
        return this;
    };

    /**
     * Uses defineEvent to define multiple events.
     *
     * @param {String[]} evts An array of event names to define.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.defineEvents = function defineEvents(evts) {
        for (var i = 0; i < evts.length; i += 1) {
            this.defineEvent(evts[i]);
        }
        return this;
    };

    /**
     * Removes a listener function from the specified event.
     * When passed a regular expression as the event name, it will remove the listener from all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to remove the listener from.
     * @param {Function} listener Method to remove from the event.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.removeListener = function removeListener(evt, listener) {
        var listeners = this.getListenersAsObject(evt);
        var index;
        var key;

        for (key in listeners) {
            if (listeners.hasOwnProperty(key)) {
                index = indexOfListener(listeners[key], listener);

                if (index !== -1) {
                    listeners[key].splice(index, 1);
                }
            }
        }

        return this;
    };

    /**
     * Alias of removeListener
     */
    proto.off = alias('removeListener');

    /**
     * Adds listeners in bulk using the manipulateListeners method.
     * If you pass an object as the second argument you can add to multiple events at once. The object should contain key value pairs of events and listeners or listener arrays. You can also pass it an event name and an array of listeners to be added.
     * You can also pass it a regular expression to add the array of listeners to all events that match it.
     * Yeah, this function does quite a bit. That's probably a bad thing.
     *
     * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add to multiple events at once.
     * @param {Function[]} [listeners] An optional array of listener functions to add.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.addListeners = function addListeners(evt, listeners) {
        // Pass through to manipulateListeners
        return this.manipulateListeners(false, evt, listeners);
    };

    /**
     * Removes listeners in bulk using the manipulateListeners method.
     * If you pass an object as the second argument you can remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
     * You can also pass it an event name and an array of listeners to be removed.
     * You can also pass it a regular expression to remove the listeners from all events that match it.
     *
     * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to remove from multiple events at once.
     * @param {Function[]} [listeners] An optional array of listener functions to remove.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.removeListeners = function removeListeners(evt, listeners) {
        // Pass through to manipulateListeners
        return this.manipulateListeners(true, evt, listeners);
    };

    /**
     * Edits listeners in bulk. The addListeners and removeListeners methods both use this to do their job. You should really use those instead, this is a little lower level.
     * The first argument will determine if the listeners are removed (true) or added (false).
     * If you pass an object as the second argument you can add/remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
     * You can also pass it an event name and an array of listeners to be added/removed.
     * You can also pass it a regular expression to manipulate the listeners of all events that match it.
     *
     * @param {Boolean} remove True if you want to remove listeners, false if you want to add.
     * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add/remove from multiple events at once.
     * @param {Function[]} [listeners] An optional array of listener functions to add/remove.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.manipulateListeners = function manipulateListeners(remove, evt, listeners) {
        var i;
        var value;
        var single = remove ? this.removeListener : this.addListener;
        var multiple = remove ? this.removeListeners : this.addListeners;

        // If evt is an object then pass each of it's properties to this method
        if (typeof evt === 'object' && !(evt instanceof RegExp)) {
            for (i in evt) {
                if (evt.hasOwnProperty(i) && (value = evt[i])) {
                    // Pass the single listener straight through to the singular method
                    if (typeof value === 'function') {
                        single.call(this, i, value);
                    }
                    else {
                        // Otherwise pass back to the multiple function
                        multiple.call(this, i, value);
                    }
                }
            }
        }
        else {
            // So evt must be a string
            // And listeners must be an array of listeners
            // Loop over it and pass each one to the multiple method
            i = listeners.length;
            while (i--) {
                single.call(this, evt, listeners[i]);
            }
        }

        return this;
    };

    /**
     * Removes all listeners from a specified event.
     * If you do not specify an event then all listeners will be removed.
     * That means every event will be emptied.
     * You can also pass a regex to remove all events that match it.
     *
     * @param {String|RegExp} [evt] Optional name of the event to remove all listeners for. Will remove from every event if not passed.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.removeEvent = function removeEvent(evt) {
        var type = typeof evt;
        var events = this._getEvents();
        var key;

        // Remove different things depending on the state of evt
        if (type === 'string') {
            // Remove all listeners for the specified event
            delete events[evt];
        }
        else if (type === 'object') {
            // Remove all events matching the regex.
            for (key in events) {
                if (events.hasOwnProperty(key) && evt.test(key)) {
                    delete events[key];
                }
            }
        }
        else {
            // Remove all listeners in all events
            delete this._events;
        }

        return this;
    };

    /**
     * Alias of removeEvent.
     *
     * Added to mirror the node API.
     */
    proto.removeAllListeners = alias('removeEvent');

    /**
     * Emits an event of your choice.
     * When emitted, every listener attached to that event will be executed.
     * If you pass the optional argument array then those arguments will be passed to every listener upon execution.
     * Because it uses `apply`, your array of arguments will be passed as if you wrote them out separately.
     * So they will not arrive within the array on the other side, they will be separate.
     * You can also pass a regular expression to emit to all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
     * @param {Array} [args] Optional array of arguments to be passed to each listener.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.emitEvent = function emitEvent(evt, args) {
        var listeners = this.getListenersAsObject(evt);
        var listener;
        var i;
        var key;
        var response;

        for (key in listeners) {
            if (listeners.hasOwnProperty(key)) {
                i = listeners[key].length;

                while (i--) {
                    // If the listener returns true then it shall be removed from the event
                    // The function is executed either with a basic call or an apply if there is an args array
                    listener = listeners[key][i];

                    if (listener.once === true) {
                        this.removeListener(evt, listener.listener);
                    }

                    response = listener.listener.apply(this, args || []);

                    if (response === this._getOnceReturnValue()) {
                        this.removeListener(evt, listener.listener);
                    }
                }
            }
        }

        return this;
    };

    /**
     * Alias of emitEvent
     */
    proto.trigger = alias('emitEvent');

    /**
     * Subtly different from emitEvent in that it will pass its arguments on to the listeners, as opposed to taking a single array of arguments to pass on.
     * As with emitEvent, you can pass a regex in place of the event name to emit to all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
     * @param {...*} Optional additional arguments to be passed to each listener.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.emit = function emit(evt) {
        var args = Array.prototype.slice.call(arguments, 1);
        return this.emitEvent(evt, args);
    };

    /**
     * Sets the current value to check against when executing listeners. If a
     * listeners return value matches the one set here then it will be removed
     * after execution. This value defaults to true.
     *
     * @param {*} value The new value to check for when executing listeners.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.setOnceReturnValue = function setOnceReturnValue(value) {
        this._onceReturnValue = value;
        return this;
    };

    /**
     * Fetches the current value to check against when executing listeners. If
     * the listeners return value matches this one then it should be removed
     * automatically. It will return true by default.
     *
     * @return {*|Boolean} The current value to check for or the default, true.
     * @api private
     */
    proto._getOnceReturnValue = function _getOnceReturnValue() {
        if (this.hasOwnProperty('_onceReturnValue')) {
            return this._onceReturnValue;
        }
        else {
            return true;
        }
    };

    /**
     * Fetches the events object and creates one if required.
     *
     * @return {Object} The events storage object.
     * @api private
     */
    proto._getEvents = function _getEvents() {
        return this._events || (this._events = {});
    };

    /**
     * Reverts the global {@link EventEmitter} to its previous value and returns a reference to this version.
     *
     * @return {Function} Non conflicting EventEmitter class.
     */
    EventEmitter.noConflict = function noConflict() {
        exports.EventEmitter = originalGlobalValue;
        return EventEmitter;
    };

    // Expose the class either via AMD, CommonJS or the global object
    if (typeof define === 'function' && define.amd) {
        define('eventEmitter/EventEmitter',[],function () {
            return EventEmitter;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = EventEmitter;
    }
    else {
        this.EventEmitter = EventEmitter;
    }
}.call(this));

/*!
 * eventie v1.0.4
 * event binding helper
 *   eventie.bind( elem, 'click', myFn )
 *   eventie.unbind( elem, 'click', myFn )
 */

/*jshint browser: true, undef: true, unused: true */
/*global define: false */

( function( window ) {



var docElem = document.documentElement;

var bind = function() {};

function getIEEvent( obj ) {
  var event = window.event;
  // add event.target
  event.target = event.target || event.srcElement || obj;
  return event;
}

if ( docElem.addEventListener ) {
  bind = function( obj, type, fn ) {
    obj.addEventListener( type, fn, false );
  };
} else if ( docElem.attachEvent ) {
  bind = function( obj, type, fn ) {
    obj[ type + fn ] = fn.handleEvent ?
      function() {
        var event = getIEEvent( obj );
        fn.handleEvent.call( fn, event );
      } :
      function() {
        var event = getIEEvent( obj );
        fn.call( obj, event );
      };
    obj.attachEvent( "on" + type, obj[ type + fn ] );
  };
}

var unbind = function() {};

if ( docElem.removeEventListener ) {
  unbind = function( obj, type, fn ) {
    obj.removeEventListener( type, fn, false );
  };
} else if ( docElem.detachEvent ) {
  unbind = function( obj, type, fn ) {
    obj.detachEvent( "on" + type, obj[ type + fn ] );
    try {
      delete obj[ type + fn ];
    } catch ( err ) {
      // can't delete window object properties
      obj[ type + fn ] = undefined;
    }
  };
}

var eventie = {
  bind: bind,
  unbind: unbind
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( 'eventie/eventie',eventie );
} else {
  // browser global
  window.eventie = eventie;
}

})( this );

/*!
 * imagesLoaded v3.1.8
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

( function( window, factory ) { 
  // universal module definition

  /*global define: false, module: false, require: false */

  if ( typeof define === 'function' && define.amd ) {
    // AMD
    define( [
      'eventEmitter/EventEmitter',
      'eventie/eventie'
    ], function( EventEmitter, eventie ) {
      return factory( window, EventEmitter, eventie );
    });
  } else if ( typeof exports === 'object' ) {
    // CommonJS
    module.exports = factory(
      window,
      require('wolfy87-eventemitter'),
      require('eventie')
    );
  } else {
    // browser global
    window.imagesLoaded = factory(
      window,
      window.EventEmitter,
      window.eventie
    );
  }

})( window,

// --------------------------  factory -------------------------- //

function factory( window, EventEmitter, eventie ) {



var $ = window.jQuery;
var console = window.console;
var hasConsole = typeof console !== 'undefined';

// -------------------------- helpers -------------------------- //

// extend objects
function extend( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
}

var objToString = Object.prototype.toString;
function isArray( obj ) {
  return objToString.call( obj ) === '[object Array]';
}

// turn element or nodeList into an array
function makeArray( obj ) {
  var ary = [];
  if ( isArray( obj ) ) {
    // use object if already an array
    ary = obj;
  } else if ( typeof obj.length === 'number' ) {
    // convert nodeList to array
    for ( var i=0, len = obj.length; i < len; i++ ) {
      ary.push( obj[i] );
    }
  } else {
    // array of single index
    ary.push( obj );
  }
  return ary;
}

  // -------------------------- imagesLoaded -------------------------- //

  /**
   * @param {Array, Element, NodeList, String} elem
   * @param {Object or Function} options - if function, use as callback
   * @param {Function} onAlways - callback function
   */
  function ImagesLoaded( elem, options, onAlways ) {
    // coerce ImagesLoaded() without new, to be new ImagesLoaded()
    if ( !( this instanceof ImagesLoaded ) ) {
      return new ImagesLoaded( elem, options );
    }
    // use elem as selector string
    if ( typeof elem === 'string' ) {
      elem = document.querySelectorAll( elem );
    }

    this.elements = makeArray( elem );
    this.options = extend( {}, this.options );

    if ( typeof options === 'function' ) {
      onAlways = options;
    } else {
      extend( this.options, options );
    }

    if ( onAlways ) {
      this.on( 'always', onAlways );
    }

    this.getImages();

    if ( $ ) {
      // add jQuery Deferred object
      this.jqDeferred = new $.Deferred();
    }

    // HACK check async to allow time to bind listeners
    var _this = this;
    setTimeout( function() {
      _this.check();
    });
  }

  ImagesLoaded.prototype = new EventEmitter();

  ImagesLoaded.prototype.options = {};

  ImagesLoaded.prototype.getImages = function() {
    this.images = [];

    // filter & find items if we have an item selector
    for ( var i=0, len = this.elements.length; i < len; i++ ) {
      var elem = this.elements[i];
      // filter siblings
      if ( elem.nodeName === 'IMG' ) {
        this.addImage( elem );
      }
      // find children
      // no non-element nodes, #143
      var nodeType = elem.nodeType;
      if ( !nodeType || !( nodeType === 1 || nodeType === 9 || nodeType === 11 ) ) {
        continue;
      }
      var childElems = elem.querySelectorAll('img');
      // concat childElems to filterFound array
      for ( var j=0, jLen = childElems.length; j < jLen; j++ ) {
        var img = childElems[j];
        this.addImage( img );
      }
    }
  };

  /**
   * @param {Image} img
   */
  ImagesLoaded.prototype.addImage = function( img ) {
    var loadingImage = new LoadingImage( img );
    this.images.push( loadingImage );
  };

  ImagesLoaded.prototype.check = function() {
    var _this = this;
    var checkedCount = 0;
    var length = this.images.length;
    this.hasAnyBroken = false;
    // complete if no images
    if ( !length ) {
      this.complete();
      return;
    }

    function onConfirm( image, message ) {
      if ( _this.options.debug && hasConsole ) {
        console.log( 'confirm', image, message );
      }

      _this.progress( image );
      checkedCount++;
      if ( checkedCount === length ) {
        _this.complete();
      }
      return true; // bind once
    }

    for ( var i=0; i < length; i++ ) {
      var loadingImage = this.images[i];
      loadingImage.on( 'confirm', onConfirm );
      loadingImage.check();
    }
  };

  ImagesLoaded.prototype.progress = function( image ) {
    this.hasAnyBroken = this.hasAnyBroken || !image.isLoaded;
    // HACK - Chrome triggers event before object properties have changed. #83
    var _this = this;
    setTimeout( function() {
      _this.emit( 'progress', _this, image );
      if ( _this.jqDeferred && _this.jqDeferred.notify ) {
        _this.jqDeferred.notify( _this, image );
      }
    });
  };

  ImagesLoaded.prototype.complete = function() {
    var eventName = this.hasAnyBroken ? 'fail' : 'done';
    this.isComplete = true;
    var _this = this;
    // HACK - another setTimeout so that confirm happens after progress
    setTimeout( function() {
      _this.emit( eventName, _this );
      _this.emit( 'always', _this );
      if ( _this.jqDeferred ) {
        var jqMethod = _this.hasAnyBroken ? 'reject' : 'resolve';
        _this.jqDeferred[ jqMethod ]( _this );
      }
    });
  };

  // -------------------------- jquery -------------------------- //

  if ( $ ) {
    $.fn.imagesLoaded = function( options, callback ) {
      var instance = new ImagesLoaded( this, options, callback );
      return instance.jqDeferred.promise( $(this) );
    };
  }


  // --------------------------  -------------------------- //

  function LoadingImage( img ) {
    this.img = img;
  }

  LoadingImage.prototype = new EventEmitter();

  LoadingImage.prototype.check = function() {
    // first check cached any previous images that have same src
    var resource = cache[ this.img.src ] || new Resource( this.img.src );
    if ( resource.isConfirmed ) {
      this.confirm( resource.isLoaded, 'cached was confirmed' );
      return;
    }

    // If complete is true and browser supports natural sizes,
    // try to check for image status manually.
    if ( this.img.complete && this.img.naturalWidth !== undefined ) {
      // report based on naturalWidth
      this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
      return;
    }

    // If none of the checks above matched, simulate loading on detached element.
    var _this = this;
    resource.on( 'confirm', function( resrc, message ) {
      _this.confirm( resrc.isLoaded, message );
      return true;
    });

    resource.check();
  };

  LoadingImage.prototype.confirm = function( isLoaded, message ) {
    this.isLoaded = isLoaded;
    this.emit( 'confirm', this, message );
  };

  // -------------------------- Resource -------------------------- //

  // Resource checks each src, only once
  // separate class from LoadingImage to prevent memory leaks. See #115

  var cache = {};

  function Resource( src ) {
    this.src = src;
    // add to cache
    cache[ src ] = this;
  }

  Resource.prototype = new EventEmitter();

  Resource.prototype.check = function() {
    // only trigger checking once
    if ( this.isChecked ) {
      return;
    }
    // simulate loading on detached element
    var proxyImage = new Image();
    eventie.bind( proxyImage, 'load', this );
    eventie.bind( proxyImage, 'error', this );
    proxyImage.src = this.src;
    // set flag
    this.isChecked = true;
  };

  // ----- events ----- //

  // trigger specified handler for event type
  Resource.prototype.handleEvent = function( event ) {
    var method = 'on' + event.type;
    if ( this[ method ] ) {
      this[ method ]( event );
    }
  };

  Resource.prototype.onload = function( event ) {
    this.confirm( true, 'onload' );
    this.unbindProxyEvents( event );
  };

  Resource.prototype.onerror = function( event ) {
    this.confirm( false, 'onerror' );
    this.unbindProxyEvents( event );
  };

  // ----- confirm ----- //

  Resource.prototype.confirm = function( isLoaded, message ) {
    this.isConfirmed = true;
    this.isLoaded = isLoaded;
    this.emit( 'confirm', this, message );
  };

  Resource.prototype.unbindProxyEvents = function( event ) {
    eventie.unbind( event.target, 'load', this );
    eventie.unbind( event.target, 'error', this );
  };

  // -----  ----- //

  return ImagesLoaded;

});

/**
 * @preserve
 * Project: Bootstrap Hover Dropdown
 * Author: Cameron Spear
 * Version: v2.1.3
 * Contributors: Mattia Larentis
 * Dependencies: Bootstrap's Dropdown plugin, jQuery
 * Description: A simple plugin to enable Bootstrap dropdowns to active on hover and provide a nice user experience.
 * License: MIT
 * Homepage: http://cameronspear.com/blog/bootstrap-dropdown-on-hover-plugin/
 */
;(function ($, window, undefined) {
    // outside the scope of the jQuery plugin to
    // keep track of all dropdowns
    var $allDropdowns = $();

    // if instantlyCloseOthers is true, then it will instantly
    // shut other nav items when a new one is hovered over
    $.fn.dropdownHover = function (options) {
        // don't do anything if touch is supported
        // (plugin causes some issues on mobile)
        if('ontouchstart' in document) return this; // don't want to affect chaining

        // the element we really care about
        // is the dropdown-toggle's parent
        $allDropdowns = $allDropdowns.add(this.parent());

        return this.each(function () {
            var $this = $(this),
                $parent = $this.parent(),
                defaults = {
                    delay: 500,
                    hoverDelay: 0,
                    instantlyCloseOthers: true
                },
                data = {
                    delay: $(this).data('delay'),
                    hoverDelay: $(this).data('hover-delay'),
                    instantlyCloseOthers: $(this).data('close-others')
                },
                showEvent   = 'show.bs.dropdown',
                hideEvent   = 'hide.bs.dropdown',
                // shownEvent  = 'shown.bs.dropdown',
                // hiddenEvent = 'hidden.bs.dropdown',
                settings = $.extend(true, {}, defaults, options, data),
                timeout, timeoutHover;

            $parent.hover(function (event) {
                // so a neighbor can't open the dropdown
                if(!$parent.hasClass('open') && !$this.is(event.target)) {
                    // stop this event, stop executing any code
                    // in this callback but continue to propagate
                    return true;
                }

                openDropdown(event);
            }, function () {
                // clear timer for hover event
                window.clearTimeout(timeoutHover)
                timeout = window.setTimeout(function () {
                    $this.attr('aria-expanded', 'false');
                    $parent.removeClass('open');
                    $this.trigger(hideEvent);
                }, settings.delay);
            });

            // this helps with button groups!
            $this.hover(function (event) {
                // this helps prevent a double event from firing.
                // see https://github.com/CWSpear/bootstrap-hover-dropdown/issues/55
                if(!$parent.hasClass('open') && !$parent.is(event.target)) {
                    // stop this event, stop executing any code
                    // in this callback but continue to propagate
                    return true;
                }

                openDropdown(event);
            });

            // handle submenus
            $parent.find('.dropdown-submenu').each(function (){
                var $this = $(this);
                var subTimeout;
                $this.hover(function () {
                    window.clearTimeout(subTimeout);
                    $this.children('.dropdown-menu').show();
                    // always close submenu siblings instantly
                    $this.siblings().children('.dropdown-menu').hide();
                }, function () {
                    var $submenu = $this.children('.dropdown-menu');
                    subTimeout = window.setTimeout(function () {
                        $submenu.hide();
                    }, settings.delay);
                });
            });

            function openDropdown(event) {
                // clear dropdown timeout here so it doesnt close before it should
                window.clearTimeout(timeout);
                // restart hover timer
                window.clearTimeout(timeoutHover);
                
                // delay for hover event.  
                timeoutHover = window.setTimeout(function () {
                    $allDropdowns.find(':focus').blur();

                    if(settings.instantlyCloseOthers === true)
                        $allDropdowns.removeClass('open');
                    
                    // clear timer for hover event
                    window.clearTimeout(timeoutHover);
                    $this.attr('aria-expanded', 'true');
                    $parent.addClass('open');
                    $this.trigger(showEvent);
                }, settings.hoverDelay);
            }
        });
    };

    $(document).ready(function () {
        // apply dropdownHover to all elements with the data-hover="dropdown" attribute
        $('[data-hover="dropdown"]').dropdownHover();
          //  Fix First Click Menu /

    });
    $(document.body).on('click', '.nav [data-toggle="dropdown"]' ,function(){
      	if(  this.href && this.href != '#'){
          	window.location.href = this.href;
      	}
    });

})(jQuery, window);




(function ($) {
    'use strict';
    $("[data-progress-animation]").each(function() {
        var $this = $(this);
        $this.appear(function() {
          	var delay = ($this.attr("data-appear-animation-delay") ? $this.attr("data-appear-animation-delay") : 1);
          	if(delay > 1) $this.css("animation-delay", delay + "ms");
          	setTimeout(function() { $this.animate({width: $this.attr("data-progress-animation")}, 800);}, delay);
        }, {accX: 0, accY: -50});
      });

    $.fn.wrapStart = function(numWords){
        return this.each(function(){
            var $this = $(this);
            var node = $this.contents().filter(function(){
                return this.nodeType == 3;
            }).first(),
            text = node.text().trim(),
            first = text.split(' ', 1).join(" ");
            if (!node.length) return;
            node[0].nodeValue = text.slice(first.length);
            node.before('<b>' + first + '</b>');
        });
    }; 

    jQuery(document).ready(function() {
        $('.mod-heading .widget-title > span').wrapStart(1);
        function init_slick(self) {
            self.each( function(){
                var config = {
                    infinite: false,
                    arrows: $(this).data( 'nav' ),
                    dots: $(this).data( 'pagination' ),
                    slidesToShow: 4,
                    slidesToScroll: 4
                };
            
                var slick = $(this);
                if( $(this).data('items') ){
                    config.slidesToShow = $(this).data( 'items' );
                    config.slidesToScroll = $(this).data( 'items' );
                }
                if( $(this).data('infinite') ){
                    config.infinite = true;
                }
                if( $(this).data('rows') ){
                    config.rows = $(this).data( 'rows' );
                }
                if( $(this).data('asnavfor') ){
                    config.asNavFor = $(this).data( 'asnavfor' );
                }
                if ($(this).data('large')) {
                    var desktop = $(this).data('large');
                } else {
                    var desktop = config.items;
                }
                if ($(this).data('medium')) {
                    var medium = $(this).data('medium');
                } else {
                    var medium = config.items;
                }
                if ($(this).data('smallmedium')) {
                    var smallmedium = $(this).data('smallmedium');
                } else {
                    var smallmedium = 2;
                }
                if ($(this).data('extrasmall')) {
                    var extrasmall = $(this).data('extrasmall');
                } else {
                    var extrasmall = 1;
                }
                config.responsive = [
                    {
                        breakpoint: 321,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: extrasmall,
                            slidesToScroll: extrasmall,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 769,
                        settings: {
                            slidesToShow: smallmedium,
                            slidesToScroll: smallmedium
                        }
                    },
                    {
                        breakpoint: 981,
                        settings: {
                            slidesToShow: medium,
                            slidesToScroll: medium
                        }
                    },
                    {
                        breakpoint: 1501,
                        settings: {
                            slidesToShow: desktop,
                            slidesToScroll: desktop
                        }
                    }
                ];
                if ( $('html').attr('dir') == 'rtl' ) {
                    config.rtl = true;
                }

                $(this).slick( config );

            } );
        }
        init_slick($("[data-carousel=slick]"));

        // Fix owl in bootstrap tabs
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            var $slick = $("[data-carousel=slick]", target);

            if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                $slick.slick('refresh');
            }
            initProductImageLoad();
        });

        $('#special-product-carousel').on('slid.bs.carousel', function () {
            var $slick = $('#special-product-carousel .slick-carousel');
            if ($slick.hasClass('slick-initialized')) {
                $slick.slick('refresh');
            }
            initProductImageLoad();
        });
        // loading ajax
        $('[data-load="ajax"] a').click(function(e){
            e.preventDefault();
            var $href = $(this).attr('href');
            $(this).parent().parent().find('li').removeClass('active');
            $(this).parent().addClass('active');

            var self = $(this);
            var main = $($href);
            if (main.length > 0) {
                if ( main.data('loaded') == false ) {
                    main.parent().addClass('loading');
                    main.data('loaded', 'true');
                    
                    $.ajax({
                        url: denso_ajax.ajaxurl,
                        type:'POST',
                        dataType: 'html',
                        data:  'action=denso_get_products&columns=' + main.data('columns') + '&product_type=' + main.data('product_type') + '&number=' + main.data('number')
                            + '&categories=' + main.data('categories') + '&layout_type=' + main.data('layout_type')
                    }).done(function(reponse) {
                        main.html( reponse );
                        main.parent().removeClass('loading');
                        main.parent().find('.tab-pane').removeClass('active');
                        main.addClass('active');
                        if ( main.find('.slick-carousel') ) {
                            init_slick(main.find('.slick-carousel'));
                        }
                        $('[data-time="timmer"]', main).each(function(index, el) {
                            var $this = $(this);
                            var $date = $this.data('date').split("-");
                            if ($('.time-format', $(this).parent()).length) {
                                var display_format = $('.time-format', $(this).parent()).html();
                            } else {
                                var display_format = "<div class=\"times\"><div class=\"day\">%%D%% Days </div><div class=\"hours\">%%H%% Hrs </div><div class=\"minutes\">%%M%% Mins </div><div class=\"seconds\">%%S%% Secs </div></div>";
                            }
                            $this.apusCountDown({
                                TargetDate:$date[0]+"/"+$date[1]+"/"+$date[2]+" "+$date[3]+":"+$date[4]+":"+$date[5],
                                DisplayFormat: display_format,
                                FinishMessage: ""
                            });
                        });
                        initProductImageLoad();
                    });
                    return true;
                } else {
                    main.parent().removeClass('loading');
                    main.parent().find('.tab-pane').removeClass('active');
                    main.addClass('active');
                }
            }
        });
    })    
    setTimeout(function(){
        initProductImageLoad();
    }, 500);
    function initProductImageLoad() {
        $(window).off('scroll.unveil resize.unveil lookup.unveil');
        var $images = $('.image-wrapper:not(.image-loaded) .unveil-image'); // Get un-loaded images only
        if ($images.length) {
            var scrollTolerance = 1;
            $images.unveil(scrollTolerance, function() {
                $(this).parents('.image-wrapper').first().addClass('image-loaded');
            });
        }

        var $images = $('.product-image:not(.image-loaded) .unveil-image'); // Get un-loaded images only
        if ($images.length) {
            var scrollTolerance = 1;
            $images.unveil(scrollTolerance, function() {
                $(this).parents('.product-image').first().addClass('image-loaded');
            });
        }
    }

})(jQuery)

/** 
 * 
 * ISO PROTYPO AUTOMATIC PLAY
 */
jQuery( document).ready( function($){
        
    //Offcanvas Menu
    $('body').on('click', '[data-toggle="offcanvas"], .btn-offcanvas', function (e) {
        e.stopPropagation();
        $('.row-offcanvas').toggleClass('active')           
    });
    $('body').click(function() {
        $('.row-offcanvas.active').toggleClass('active');
    });
    $('body').on('click', '.row-offcanvas.active .sidebar-offcanvas', function(e) {
        e.stopPropagation();
    });

    //counter up
    if($('.counterUp').length > 0){
        $('.counterUp').counterUp({
            delay: 10,
            time: 800
        });
    }

    /*---------------------------------------------- 
     * Play Isotope masonry
     *----------------------------------------------*/  
    jQuery('.isotope-items,.blog-masonry').each(function(){  
        var $container = jQuery(this);
        
        $container.imagesLoaded( function(){
            $container.isotope({
                itemSelector : '.isotope-item',
                transformsEnabled: true         // Important for videos
            }); 
        });
    });
    /*---------------------------------------------- 
     *    Apply Filter        
     *----------------------------------------------*/
    jQuery('.isotope-filter li a').on('click', function(){
       
        var parentul = jQuery(this).parents('ul.isotope-filter').data('related-grid');
        jQuery(this).parents('ul.isotope-filter').find('li a').removeClass('active');
        jQuery(this).addClass('active');
        var selector = jQuery(this).attr('data-filter'); 
        jQuery('#'+parentul).isotope({ filter: selector }, function(){ });
        
        return(false);
    });

    //Sticky Header
    setTimeout(function(){
        change_margin_top();
    }, 100);
    $(window).resize(function(){
        change_margin_top();
    });
    function change_margin_top() {
        if ($(window).width() > 991) {
            if ( $('.main-sticky-header').length > 0 ) {
                var header_height = $('.main-sticky-header').outerHeight();
                $('.main-sticky-header-wrapper').css({'height': header_height});
            }
        }
    }
    var main_sticky = $('.main-sticky-header');
    
    if ( main_sticky.length > 0 ){
        var _menu_action = main_sticky.offset().top;
        var Apus_Menu_Fixed = function(){
            "use strict";

            if( $(document).scrollTop() > _menu_action ){
              main_sticky.addClass('sticky-header');
            }else{
              main_sticky.removeClass('sticky-header');
            }
        }
        if ($(window).width() > 991) {
            $(window).scroll(function(event) {
                Apus_Menu_Fixed();
            });
            Apus_Menu_Fixed();
        }
    }

    //Tooltip
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

    $('.topbar-mobile .dropdown-menu').on('click', function(e) {
      	e.stopPropagation();
    });

    var back_to_top = function () {
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 400) {
                jQuery('#back-to-top').addClass('active');
            } else {
                jQuery('#back-to-top').removeClass('active');
            }
        });
        jQuery('#back-to-top').on('click', function () {
            jQuery('html, body').animate({scrollTop: '0px'}, 800);
            return false;
        });
    };
    back_to_top();
    
    // popup
    $(".popup-image").magnificPopup({type:'image'});
    $('.popup-video').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });
    $('.popup-gallery').magnificPopup({
        type: 'image',
        gallery:{
            enabled:true
        }
    });

    // mobile menu
    // mobile menu
    $('.header-mobile [data-toggle="offcanvas"]').on('click', function (e) {
        e.stopPropagation();
        $('#wrapper-container').toggleClass('active');
        $('#apus-mobile-menu').toggleClass('active');           
    });
    
    $('body').click(function() {
        if ($('#wrapper-container').hasClass('active')) {
            $('#wrapper-container').toggleClass('active');
            $('#apus-mobile-menu').toggleClass('active');
        }
    });
    $('#apus-mobile-menu').click(function(e) {
        e.stopPropagation();
    });

    $("#main-mobile-menu .icon-toggle,#main-mobile-menu-vertical .icon-toggle").on('click', function(){
        $(this).parent().find('.sub-menu').first().slideToggle();
        if ( $(this).find('i').hasClass('fa-angle-right') ) {
            $(this).find('i').removeClass('fa-angle-right').addClass('fa-angle-up');
        } else {
            $(this).find('i').removeClass('fa-angle-up').addClass('fa-angle-right');
        }
        return false;
    } );

    // preload page
    var $body = $('body');
    if ( $body.hasClass('apus-body-loading') ) {

        setTimeout(function() {
            $body.removeClass('apus-body-loading');
            $('.apus-page-loading').fadeOut(250);
        }, 200);
    }

    // full width video
    // Find all YouTube videos
    iframe_full_width();

    function iframe_full_width(){
        var $fluidEl = $(".pro-fluid-inner");
        var $videoEls = $(".pro-fluid-inner iframe");
        $videoEls.each(function() {
            $(this).data('aspectRatio', this.height / this.width)
            .removeAttr('height')
            .removeAttr('width');
        });

        $(window).resize(function() {
            $fluidEl.each(function(){
                var newWidth = $(this).width();
                var $videoEl = $(this).find("iframe");
                $videoEl.each(function() {
                    var $el = $(this);
                    $el.width(newWidth).height(newWidth * $el.data('aspectRatio'));
                });
            });
        }).resize();
    }

    // perfect scroll
    $('.widget-categories-tabs .nav-tabs-selector').perfectScrollbar();
    $('.apus-categories-wrapper').perfectScrollbar();
    
    // popup
    if ($('.popuppromotion').length > 0) {
        setTimeout(function(){
            var hiddenmodal = getCookie('hidde_popup_promotion');
            if (hiddenmodal == "") {
                var popup_content = $('.popuppromotion').html();
                $.magnificPopup.open({
                    mainClass: 'apus-mfp-zoom-in popuppromotion-wrapper',
                    modal:true,
                    items    : {
                        src : popup_content,
                        type: 'inline'
                    },
                    callbacks: {
                        close: function() {
                            setCookie('hidde_popup_promotion', 1, 30);
                        }
                    }
                });
            }
        }, 3000);
    }
    if ($('.popupnewsletter').length > 0) {
        setTimeout(function(){
            var hiddenmodal = getCookie('hidde_popup_newsletter');
            if (hiddenmodal == "") {
                var popup_content = $('.popupnewsletter').html();
                $.magnificPopup.open({
                    mainClass: 'apus-mfp-zoom-in popupnewsletter-wrapper',
                    modal:true,
                    items    : {
                        src : popup_content,
                        type: 'inline'
                    },
                    callbacks: {
                        close: function() {
                            setCookie('hidde_popup_newsletter', 1, 30);
                        }
                    }
                });
            }
        }, 3000);
    }
    $('.apus-mfp-close').click(function(){
        magnificPopup.close();
    });
});
/**
* countdown
*/
(function($){
    $.fn.apusCountDown = function( options ) {
        return this.each(function() {
            new $.apusCountDown( this, options ); 
        });
    }
    $.apusCountDown = function( obj, options ) {
        this.options = $.extend({
            autoStart : true,
            LeadingZero:true,
            DisplayFormat:"<div>%%D%% Days</div><div>%%H%% Hours</div><div>%%M%% Minutes</div><div>%%S%% Seconds</div>",
            FinishMessage:"Expired",
            CountActive:true,
            TargetDate:null
        }, options || {} );
        if ( this.options.TargetDate == null || this.options.TargetDate == '' ){
            return ;
        }
        this.timer  = null;
        this.element = obj;
        this.CountStepper = -1;
        this.CountStepper = Math.ceil(this.CountStepper);
        this.SetTimeOutPeriod = (Math.abs(this.CountStepper)-1)*1000 + 990;
        var dthen = new Date(this.options.TargetDate);
        var dnow = new Date();
        if ( this.CountStepper > 0 ) {
            ddiff = new Date(dnow-dthen);
        } else {
            ddiff = new Date(dthen-dnow);
        }
        gsecs = Math.floor(ddiff.valueOf()/1000); 
        this.CountBack(gsecs, this);
    };
    $.apusCountDown.fn = $.apusCountDown.prototype;
    $.apusCountDown.fn.extend = $.apusCountDown.extend = $.extend;
    $.apusCountDown.fn.extend({
        calculateDate:function( secs, num1, num2 ){
            var s = ((Math.floor(secs/num1))%num2).toString();
            if ( this.options.LeadingZero && s.length < 2) {
                s = "0" + s;
            }
            return "<span>" + s + "</span>";
        },
        CountBack:function( secs, self ){
            if (secs < 0) {
                self.element.innerHTML = '<div class="lof-labelexpired"> '+self.options.FinishMessage+"</div>";
                return;
            }
            clearInterval(self.timer);
            DisplayStr = self.options.DisplayFormat.replace(/%%D%%/g, self.calculateDate( secs,86400,100000) );
            DisplayStr = DisplayStr.replace(/%%H%%/g, self.calculateDate(secs,3600,24));
            DisplayStr = DisplayStr.replace(/%%M%%/g, self.calculateDate(secs,60,60));
            DisplayStr = DisplayStr.replace(/%%S%%/g, self.calculateDate(secs,1,60));
            self.element.innerHTML = DisplayStr;
            if (self.options.CountActive) {
                self.timer = null;
                self.timer =  setTimeout( function(){
                    self.CountBack((secs+self.CountStepper),self);          
                },( self.SetTimeOutPeriod ) );
            }
        }
    });
    jQuery(document).ready(function() {
        $('[data-time="timmer"]').each(function(index, el) {
            var $this = $(this);
            var $date = $this.data('date').split("-");
            if ($('.time-format', $(this).parent()).length) {
                var display_format = $('.time-format', $(this).parent()).html();
            } else {
                var display_format = "<div class=\"times\"><div class=\"day\">%%D%% Days </div><div class=\"hours\">%%H%% Hrs </div><div class=\"minutes\">%%M%% Mins </div><div class=\"seconds\">%%S%% Secs </div></div>";
            }
            $this.apusCountDown({
                TargetDate:$date[0]+"/"+$date[1]+"/"+$date[2]+" "+$date[3]+":"+$date[4]+":"+$date[5],
                DisplayFormat: display_format,
                FinishMessage: ""
            });
        });
    });

    // search form
    $('.close-search-form').click(function(){
        $('.full-top-search-form').removeClass('show');
        $('#searchverlay').removeClass('show');
    });
    // full top search
    $('.button-show-search').click(function(){
        $('.full-top-search-form').toggleClass('show');
        $('#searchverlay').toggleClass('show');
        $('.header-v4 .header-search').toggleClass('active');
    });
    // close search
    $('.header-v4 .close-search-form').click(function(){
        $('.header-v4 .header-search').toggleClass('active');
    });

    // scroll map
    $('.kc-google-maps').click(function () {
        $('.kc-google-maps iframe').css("pointer-events", "auto");
    });


    setTimeout(function(){
        // sticky afflix
        var affix_height = 0;
        var affix_height_top = 0;
        setTimeout(function(){
            change_margin_top_affix();
        }, 50);
        $(window).resize(function(){
            change_margin_top_affix();
        });

        function change_margin_top_affix() {
            if ($(window).width() > 991) {
                if ( $('.panel-affix').length > 0 ) {
                    affix_height_top = affix_height = $('.panel-affix').height();
                    $('.panel-affix-wrapper').css({'height': affix_height});
                }
            }
        }
        //Function from Bluthemes, lets you add li elemants to affix object without having to alter and data attributes set out by bootstrap
        
        // name your elements here
        var stickyElement   = '.panel-affix',   // the element you want to make sticky
            bottomElement   = '#apus-footer'; // the bottom element where you want the sticky element to stop (usually the footer) 

        // make sure the element exists on the page before trying to initalize
        
        if($( stickyElement ).length){
            $( stickyElement ).each(function(){
                var header_height = 0;
                if ($('.main-sticky-header').length > 0) {
                    header_height = $('.main-sticky-header').outerHeight();
                    affix_height_top = affix_height + header_height;
                }
                // let's save some messy code in clean variables
                // when should we start affixing? (the amount of pixels to the top from the element)

                var fromTop = $( this ).offset().top, 
                    // where is the bottom of the element?
                    fromBottom = $( document ).height()-($( this ).offset().top + $( this ).outerHeight()),
                    // where should we stop? (the amount of pixels from the top where the bottom element is)
                    // also add the outer height mismatch to the height of the element to account for padding and borders
                    stopOn = $( document ).height()-( $( bottomElement ).offset().top)+($( this ).outerHeight() - $( this ).height());
                // if the element doesn't need to get sticky, then skip it so it won't mess up your layout
                if( (fromBottom-stopOn) > 200 ){
                    // let's put a sticky width on the element and assign it to the top
                    $( this ).css('width', $( this ).width()).css('top', 0).css('position', '');
                    // assign the affix to the element
                    $( this ).affix({
                        offset: { 
                            // make it stick where the top pixel of the element is
                            top: fromTop - header_height,  
                            // make it stop where the top pixel of the bottom element is
                            bottom: stopOn
                        }
                    // when the affix get's called then make sure the position is the default (fixed) and it's at the top
                    }).on('affixed.bs.affix', function(){
                        var header_height = 0;
                        if ($('.main-sticky-header').length > 0) {
                            header_height = $('.main-sticky-header').outerHeight();
                        }
                        affix_height_top = affix_height + header_height;
                        $( this ).css('top', header_height).css('position', '');
                    }).on('affixed-top.bs.affix', function(){
                        $( this ).css('top', '0px').css('position', '');
                    });
                }
                // trigger the scroll event so it always activates 
                $( window ).trigger('scroll'); 
            }); 
        }
        
        //Offset scrollspy height to highlight li elements at good window height
        $('body').scrollspy({
            offset: 80,
            target: ".product-tabs-info"
        });

        //Smooth Scrolling For Internal Page Links
        $('.apus-tabs.panel-affix a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
              if (target.length) {
                $('html,body').animate({
                  scrollTop: target.offset().top - affix_height_top
                }, 1000);
                return false;
              }
            }
        });
    }, 100);
})(jQuery)

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires+";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}