/*
Copyright 2012 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

/**
* @fileoverview Bookmark bubble library. This is meant to be included in the
* main JavaScript binary of a mobile web application.
*
* Supported browsers: iPhone / iPod / iPad Safari 3.0+ / Android 2.3 and 3.2
*/

var google = google || {};
google.bookmarkbubble = google.bookmarkbubble || {};


/**
* Binds a context object to the function.
* @param {Function} fn The function to bind to.
* @param {Object} context The "this" object to use when the function is run.
* @return {Function} A partially-applied form of fn.
*/
google.bind = function (fn, context) {
    return function () {
        return fn.apply(context, arguments);
    };
};


/**
* Function used to define an abstract method in a base class. If a subclass
* fails to override the abstract method, then an error will be thrown whenever
* that method is invoked.
*/
google.abstractMethod = function () {
    throw Error('Unimplemented abstract method.');
};



/**
* The bubble constructor. Instantiating an object does not cause anything to
* be rendered yet, so if necessary you can set instance properties before
* showing the bubble.
* @constructor
*/
google.bookmarkbubble.Bubble = function () {
    /**
    * Handler for the scroll event. Keep a reference to it here, so it can be
    * unregistered when the bubble is destroyed.
    * @type {function()}
    * @private
    */
    this.boundScrollHandler_ = google.bind(this.setPosition, this);

    /**
    * The bubble element.
    * @type {Element}
    * @private
    */
    this.element_ = null;

    /**
    * Whether the bubble has been destroyed.
    * @type {boolean}
    * @private
    */
    this.hasBeenDestroyed_ = false;
};


/**
* The instructions for saving a bookmark on Mobile Safari 4.2+.
* @type {string}
* @public
*/
google.bookmarkbubble.Bubble.prototype.MOBILE_SAFARI_INSTRUCTIONS = 'Install this web app on your phone: ' +
        'tap on the arrow and then <b>\'Add to Home Screen\'</b>';


/**
* The instructions for saving a bookmark on Mobile Safari 3.x.
* @type {string}
* @public
*/
google.bookmarkbubble.Bubble.prototype.MOBILE_SAFARI_OLD_INSTRUCTIONS = 'Install this web app on your phone: ' +
        'tap <b style="font-size:15px">+</b> and then ' +
        '<b>\'Add to Home Screen\'</b>';


/**
* The instructions for saving a bookmark on Android Browser 2.3+.
* @type {string}
* @public
*/
google.bookmarkbubble.Bubble.prototype.ANDROID_BROWSER_INSTRUCTIONS = 'Install this web app on your phone: ' +
        'tap bookmark icon then <b>\'Add\'</b>. You can then long-press on bookmark and <b>\'Add to Home Screen\'</b>';


/**
* Time in milliseconds. If the user does not dismiss the bubble, it will auto
* destruct after this amount of time.
* @type {number}
*/
google.bookmarkbubble.Bubble.prototype.TIME_UNTIL_AUTO_DESTRUCT = 15000;


/**
* The arrow image in base64 data url format.
* @type {string}
* @private
*/
google.bookmarkbubble.Bubble.prototype.IMAGE_ARROW_DATA_URL_ = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAATCAMAAABSrFY3AAABKVBMVEUAAAD///8AAAAAAAAAAAAAAAAAAADf398AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD09PQAAAAAAAAAAAC9vb0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD19fUAAAAAAAAAAAAAAADq6uoAAAAAAAAAAAC8vLzU1NTT09MAAADg4OAAAADs7OwAAAAAAAAAAAD///+cueenwerA0vC1y+3a5fb5+/3t8vr4+v3w9PuwyOy3zO3h6vfh6vjq8Pqkv+mat+fE1fHB0/Cduuifu+iuxuuivemrxOvC1PDz9vzJ2fKpwuqmwOrb5vapw+q/0vDf6ffK2vLN3PPprJISAAAAQHRSTlMAAAEGExES7FM+JhUoQSxIRwMbNfkJUgXXBE4kDQIMHSA0Tw4xIToeTSc4Chz4OyIjPfI3QD/X5OZR6zzwLSUPrm1y3gAAAQZJREFUeF5lzsVyw0AURNE3IMsgmZmZgszQZoeZOf//EYlG5Yrhbs+im4Dj7slM5wBJ4OJ+undAUr68gK/Hyb6Bcp5yBR/w8jreNeAr5Eg2XE7g6e2/0z6cGw1JQhpmHP3u5aiPPnTTkIK48Hj9Op7bD3btAXTfgUdwYjwSDCVXMbizO0O4uDY/x4kYC5SWFnfC6N1a9RCO7i2XEmQJj2mHK1Hgp9Vq3QBRl9shuBLGhcNtHexcdQCnDUoUGetxDD+H2DQNG2xh6uAWgG2/17o1EmLqYH0Xej0UjHAaFxZIV6rJ/WK1kg7QZH8HU02zmdJinKZJaDV3TVMjM5Q9yiqYpUwiMwa/1apDXTNESjsAAAAASUVORK5CYII=';


/**
* The close image in base64 data url format.
* @type {string}
* @private
*/
google.bookmarkbubble.Bubble.prototype.IMAGE_CLOSE_DATA_URL_ = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAALVBMVEXM3fm+1Pfb5/rF2fjw9f23z/aavPOhwfTp8PyTt/L3+v7T4vqMs/K7zP////+qRWzhAAAAXElEQVQIW2O4CwUM996BwVskxtOqd++2rwMyPI+ve31GD8h4Madqz2mwms5jZ/aBGS/mHIDoen3m+DowY8/hOVUgxusz+zqPg7SvPA1UxQfSvu/du0YUK2AMmDMA5H1qhVX33T8AAAAASUVORK5CYII=';


/**
* The link used to locate the application's home screen icon to display inside
* the bubble. The default link used here is for an iPhone home screen icon
* without gloss. If your application uses a glossy icon, change this to
* 'apple-touch-icon'.
* @type {string}
* @private
*/
google.bookmarkbubble.Bubble.prototype.REL_ICON_ =
    'apple-touch-icon-precomposed';


/**
* The url of the app's bookmark icon.
* @type {string|undefined}
* @private
*/
google.bookmarkbubble.Bubble.prototype.iconUrl_;


/**
* Shows the bubble if allowed. It is not allowed if:
* - The browser is not Mobile Safari, or
* - The user has dismissed it too often already, or
* - The hash parameter is present in the location hash, or
* - The application is in fullscreen mode, which means it was already loaded
*   from a homescreen bookmark.
* @return {boolean} True if the bubble is being shown, false if it is not
*     allowed to show for one of the aforementioned reasons.
*/
google.bookmarkbubble.Bubble.prototype.showIfAllowed = function () {
    if (!this.isAllowedToShow_()) {
        return false;
    }

    this.show_();
    return true;
};


/**
* Shows the bubble if allowed after loading the icon image. This method creates
* an image element to load the image into the browser's cache before showing
* the bubble to ensure that the image isn't blank. Use this instead of
* showIfAllowed if the image url is http and cacheable.
* This hack is necessary because Mobile Safari does not properly render
* image elements with border-radius CSS.
* @param {function()} opt_callback Closure to be called if and when the bubble
*        actually shows.
* @return {boolean} True if the bubble is allowed to show.
*/
google.bookmarkbubble.Bubble.prototype.showIfAllowedWhenLoaded =
    function (opt_callback) {
        if (!this.isAllowedToShow_()) {
            return false;
        }

        var self = this;
        // Attach to self to avoid garbage collection.
        var img = self.loadImg_ = document.createElement('img');
        img.src = self.getIconUrl_();
        img.onload = function () {
            if (img.complete) {
                delete self.loadImg_;
                img.onload = null;  // Break the circular reference.

                self.show_();
                opt_callback && opt_callback();
            }
        };
        img.onload();

        return true;
    };


/**
* Regular expression for detecting an iPhone or iPod or iPad.
* @type {!RegExp}
* @private
*/
google.bookmarkbubble.Bubble.prototype.MOBILE_SAFARI_USERAGENT_REGEX_ =
    /iPhone|iPod|iPad/;


/**
* Regular expression for detecting an iPad.
* @type {!RegExp}
* @private
*/
google.bookmarkbubble.Bubble.prototype.IPAD_USERAGENT_REGEX_ = /iPad/;


/**
* Regular expression for extracting the iOS version. Only matches 2.0 and up.
* @type {!RegExp}
* @private
*/
google.bookmarkbubble.Bubble.prototype.IOS_VERSION_USERAGENT_REGEX_ =
    /OS (\d)_(\d)(?:_(\d))?/;


/**
* Regular expression for detecting Android.
* @type {!RegExp}
* @private
*/
google.bookmarkbubble.Bubble.prototype.ANDROID_USERAGENT_REGEX_ = /Android/;


/**
* Regular expression for detecting Android version.
* @type {!RegExp}
* @private
*/
google.bookmarkbubble.Bubble.prototype.ANDROID_VERSION_USERAGENT_REGEX_ = /Android (\d).(\d)/;


/**
* Determines whether the bubble should be shown or not.
* @return {boolean} Whether the bubble should be shown or not.
* @private
*/
google.bookmarkbubble.Bubble.prototype.isAllowedToShow_ = function () {
    return (this.isMobileSafari_() || this.isAndroid_()) &&
      !this.isFullscreen_();
};


/**
* Builds and shows the bubble.
* @private
*/
google.bookmarkbubble.Bubble.prototype.show_ = function () {
    this.element_ = this.build_();

    document.body.appendChild(this.element_);
    this.element_.style.WebkitTransform =
      'translate3d(0,' + this.getHiddenYPosition_() + 'px,0)';

    window.setTimeout(this.boundScrollHandler_, 1);
    window.addEventListener('scroll', this.boundScrollHandler_, false);

    // If the user does not dismiss the bubble, slide out and destroy it after
    // some time.
    window.setTimeout(google.bind(this.autoDestruct_, this),
      this.TIME_UNTIL_AUTO_DESTRUCT);
};


/**
* Destroys the bubble by removing its DOM nodes from the document.
*/
google.bookmarkbubble.Bubble.prototype.destroy = function () {
    if (this.hasBeenDestroyed_) {
        return;
    }
    window.removeEventListener('scroll', this.boundScrollHandler_, false);
    if (this.element_ && this.element_.parentNode == document.body) {
        document.body.removeChild(this.element_);
        this.element_ = null;
    }
    this.hasBeenDestroyed_ = true;
};


/**
* Whether the application is running in fullscreen mode.
* @return {boolean} Whether the application is running in fullscreen mode.
* @private
*/
google.bookmarkbubble.Bubble.prototype.isFullscreen_ = function () {
    return !!window.navigator.standalone;
};


/**
* Whether the application is running inside Mobile Safari.
* @return {boolean} True if the current user agent looks like Mobile Safari.
* @private
*/
google.bookmarkbubble.Bubble.prototype.isMobileSafari_ = function () {
    return this.MOBILE_SAFARI_USERAGENT_REGEX_.test(window.navigator.userAgent);
};


/**
* Whether the application is running on an iPad.
* @return {boolean} True if the current user agent looks like an iPad.
* @private
*/
google.bookmarkbubble.Bubble.prototype.isIpad_ = function () {
    return this.IPAD_USERAGENT_REGEX_.test(window.navigator.userAgent);
};


/**
* Whether the application is running on an Android device.
* @return {boolean} True if the current user agent looks like Android.
* @private
*/
google.bookmarkbubble.Bubble.prototype.isAndroid_ = function () {
    return this.ANDROID_USERAGENT_REGEX_.test(window.navigator.userAgent);
};


/**
* Whether the application is running in portrait or landscape mode.
* @return {boolean} True if the current window is in portrait mode.
* @private
*/
google.bookmarkbubble.Bubble.prototype.isPortrait_ = function () {
    return window.innerHeight > window.innerWidth;
};


/**
* Creates a version number from 4 integer pieces between 0 and 127 (inclusive).
* @param {*=} opt_a The major version.
* @param {*=} opt_b The minor version.
* @param {*=} opt_c The revision number.
* @param {*=} opt_d The build number.
* @return {number} A representation of the version.
* @private
*/
google.bookmarkbubble.Bubble.prototype.getVersion_ = function (opt_a, opt_b,
    opt_c, opt_d) {
    // We want to allow implicit conversion of any type to number while avoiding
    // compiler warnings about the type.
    return /** @type {number} */(opt_a) << 21 |
    /** @type {number} */(opt_b) << 14 |
    /** @type {number} */(opt_c) << 7 |
    /** @type {number} */(opt_d);
};


/**
* Gets the iOS version of the device. Only works for 2.0+.
* @return {number} The iOS version.
* @private
*/
google.bookmarkbubble.Bubble.prototype.getIosVersion_ = function () {
    var groups = this.IOS_VERSION_USERAGENT_REGEX_.exec(
      window.navigator.userAgent) || [];
    groups.shift();
    return this.getVersion_.apply(this, groups);
};


/**
* Gets the Android version of the device. 
* @return {number} The Android version.
* @private
*/
google.bookmarkbubble.Bubble.prototype.getAndroidVersion_ = function (uagent) {
    var groups = this.ANDROID_VERSION_USERAGENT_REGEX_.exec(
        window.navigator.userAgent) || [];
    groups.shift();
    return this.getVersion_.apply(this, groups);
};

/**
* Positions the bubble at the bottom of the viewport using an animated
* transition.
*/
google.bookmarkbubble.Bubble.prototype.setPosition = function () {
    this.element_.style.WebkitTransition = '-webkit-transform 0.7s ease-out';
    this.element_.style.WebkitTransform =
      'translate3d(0,' + this.getVisibleYPosition_() + 'px,0)';
};


/**
* Destroys the bubble by removing its DOM nodes from the document, and
* remembers that it was dismissed.
* @private
*/
google.bookmarkbubble.Bubble.prototype.closeClickHandler_ = function () {
    this.destroy();
};


/**
* Gets called after a while if the user ignores the bubble.
* @private
*/
google.bookmarkbubble.Bubble.prototype.autoDestruct_ = function () {
    if (this.hasBeenDestroyed_) {
        return;
    }
    this.element_.style.WebkitTransition = '-webkit-transform 0.7s ease-in';
    this.element_.style.WebkitTransform =
      'translate3d(0,' + this.getHiddenYPosition_() + 'px,0)';
    window.setTimeout(google.bind(this.destroy, this), 700);
};


/**
* Gets the y offset used to show the bubble (i.e., position it on-screen).
* @return {number} The y offset.
* @private
*/
google.bookmarkbubble.Bubble.prototype.getVisibleYPosition_ = function () {
    var yOffSet;
    if (this.isIpad_()) {
        yOffSet = window.pageYOffset + 17;
    } else if (this.isMobileSafari_()) {
        yOffSet = window.pageYOffset - this.element_.offsetHeight + window.innerHeight - 17;
    } else if (this.isAndroid_()) {
        yOffSet = window.pageYOffset + 17;
    }
    return yOffSet;
};


/**
* Gets the y offset used to hide the bubble (i.e., position it off-screen).
* @return {number} The y offset.
* @private
*/
google.bookmarkbubble.Bubble.prototype.getHiddenYPosition_ = function () {
    var yOffSet;
    if (this.isIpad_()) {
        yOffSet = window.pageYOffset - this.element_.offsetHeight;
    } else if (this.isMobileSafari_()) {
        yOffSet = window.pageYOffset + window.innerHeight;
    } else if (this.isAndroid_()) {
        yOffSet = window.pageYOffset - this.element_.offsetHeight;
    }
    return yOffSet;
};


/**
* Scrapes the document for a link element that specifies an Apple favicon and
* returns the icon url. Returns an empty data url if nothing can be found.
* @return {string} A url string.
* @private
*/
google.bookmarkbubble.Bubble.prototype.getIconUrl_ = function () {
    if (!this.iconUrl_) {
        var link = this.getLink(this.REL_ICON_);
        if (!link || !(this.iconUrl_ = link.href)) {
            this.iconUrl_ = 'data:image/png;base64,';
        }
    }
    return this.iconUrl_;
};


/**
* Gets the requested link tag if it exists.
* @param {string} rel The rel attribute of the link tag to get.
* @return {Element} The requested link tag or null.
*/
google.bookmarkbubble.Bubble.prototype.getLink = function (rel) {
    rel = rel.toLowerCase();
    var links = document.getElementsByTagName('link');
    for (var i = 0; i < links.length; ++i) {
        var currLink = /** @type {Element} */(links[i]);
        if (currLink.getAttribute('rel').toLowerCase() == rel) {
            return currLink;
        }
    }
    return null;
};


/**
* Creates the bubble and appends it to the document.
* @return {Element} The bubble element.
* @private
*/
google.bookmarkbubble.Bubble.prototype.build_ = function () {
    var bubble = document.createElement('div');
    var isIpad = this.isIpad_();
    var isIphone = this.isMobileSafari_();
    var isAndroid = this.isAndroid_();
    var isPortrait = this.isPortrait_();

    bubble.style.position = 'absolute';
    bubble.style.zIndex = 1000;
    bubble.style.width = '100%';
    bubble.style.left = '0';
    bubble.style.top = '0';

    var bubbleInner = document.createElement('div');
    bubbleInner.style.position = 'relative';
    bubbleInner.style.width = '214px';
    bubbleInner.style.border = '2px solid #fff';
    bubbleInner.style.padding = '20px 20px 20px 10px';
    bubbleInner.style.WebkitBorderRadius = '8px';
    bubbleInner.style.WebkitBoxShadow = '0 0 8px rgba(0, 0, 0, 0.7)';
    bubbleInner.style.WebkitBackgroundSize = '100% 8px';
    bubbleInner.style.backgroundColor = '#b0c8ec';
    bubbleInner.style.background = '#cddcf3 -webkit-gradient(linear, ' +
      'left bottom, left top, ' + isIpad ?
          'from(#cddcf3), to(#b3caed)) no-repeat top' :
          'from(#b3caed), to(#cddcf3)) no-repeat bottom';
    bubbleInner.style.font = '13px/17px sans-serif';
    if (isIpad) {
        bubbleInner.style.margin = '0 0 0 36px';
    } else if (isIphone) {
        bubbleInner.style.margin = '0 auto';
    } else if (isAndroid) {
        if (this.getAndroidVersion_() >= this.getVersion_(3, 0)) {
            if (this.isPortrait_()) {
                bubbleInner.style.margin = '0 0 0 55%';
            } else {
                bubbleInner.style.margin = '0 0 0 80%';
            }
        } else {
            if (this.isPortrait_()) {
                bubbleInner.style.margin = '0 0 0 20%';
            } else {
                bubbleInner.style.margin = '0 0 0 55%';
            }
        }
    }
    bubble.appendChild(bubbleInner);

    if (isIpad || isIphone) {
        if (this.getIosVersion_() >= this.getVersion_(4, 2)) {
            bubbleInner.innerHTML = google.bookmarkbubble.Bubble.prototype.MOBILE_SAFARI_INSTRUCTIONS;
        } else {
            bubbleInner.innerHTML = google.bookmarkbubble.Bubble.prototype.MOBILE_SAFARI_OLD_INSTRUCTIONS;
        }
    } else if (isAndroid) {
        bubbleInner.innerHTML = google.bookmarkbubble.Bubble.prototype.ANDROID_BROWSER_INSTRUCTIONS;
    } else {
        bubbleInner.innerHTML = 'Not supported.';
    }

    var icon = document.createElement('div');
    icon.style['float'] = 'left';
    icon.style.width = '55px';
    icon.style.height = '55px';
    icon.style.margin = '-2px 7px 3px 5px';
    icon.style.background =
    '#fff url(' + this.getIconUrl_() + ') no-repeat -1px -1px';
    icon.style.WebkitBackgroundSize = '57px';
    icon.style.WebkitBorderRadius = '10px';
    icon.style.WebkitBoxShadow = '0 2px 5px rgba(0, 0, 0, 0.4)';
    bubbleInner.insertBefore(icon, bubbleInner.firstChild);

    var arrow = document.createElement('div');
    arrow.style.backgroundImage = 'url(' + this.IMAGE_ARROW_DATA_URL_ + ')';
    arrow.style.width = '25px';
    arrow.style.height = '19px';
    arrow.style.position = 'absolute';
    arrow.style.left = '111px';
    if (isIpad) {
        arrow.style.WebkitTransform = 'rotate(180deg)';
        arrow.style.top = '-19px';
    } else if (isIphone) {
        arrow.style.bottom = '-19px';
    } else if (isAndroid) {
        arrow.style.WebkitTransform = 'rotate(180deg)';
        arrow.style.top = '-19px';
        arrow.style.left = '210px';
    }
    bubbleInner.appendChild(arrow);

    var close = document.createElement('a');
    close.onclick = google.bind(this.closeClickHandler_, this);
    close.style.position = 'absolute';
    close.style.display = 'block';
    close.style.top = '-3px';
    close.style.right = '-3px';
    close.style.width = '16px';
    close.style.height = '16px';
    close.style.border = '10px solid transparent';
    close.style.background =
      'url(' + this.IMAGE_CLOSE_DATA_URL_ + ') no-repeat';
    bubbleInner.appendChild(close);

    return bubble;
};
