// page init
jQuery(function () {
	initFixedBlock();
	initCycleCarousel();
	initOpenClose();
	initSameHeight();
	initAjaxFilters();
	initProductsList();
	initTabs();
	initCustomForms();
	// initLightbox();
	initCustomMap();
	initRemoveItems();
	initTouchNav();
	initCustomHover();
	initFormValidation();
	initAddClasses();
	initPopups();
	initLevelMenu();
	initReloadProducts();
	initPopup();
});

function initLevelMenu() {

	//TypeScript
	try {
		Typekit.load({async: true});
	} catch (e) {
	}

	// jQuery('body').dropLevelMenu({
	// 	activeHolderClass: 'open-side-menu',
	// 	opener: '.side-opener',
	// 	animationClasses: {
	// 		classin: 'dl-animate-in-5',
	// 		classout: 'dl-animate-out-5'
	// 	}
	// });

	var menu_btn = '.side-opener',
		magazine_link = '.side-menu .magazine',
		loading_class = 'loading',
		menu_wrapper = '.side-menu',
		holder = menu_wrapper + ' .holder',
		main_menu = menu_wrapper + ' .dl-menuwrapper',
		sub_menu = menu_wrapper + ' .dl-menuwrapper-sub',
		back_btn = menu_wrapper + ' .back',
		read_magazine = menu_wrapper + ' .read_magazine',
		nonce = jQuery(menu_btn).data('nonce');

	jQuery(menu_btn).on('click', function () {

		var body = jQuery('body');
		if (body.hasClass('opened')) {

			body.removeClass('opened open-side-menu');

		} else {

			body.addClass('opened open-side-menu');

			jQuery(main_menu).show();
			jQuery(sub_menu).empty();

		}

	});

	jQuery(magazine_link).on('click', function (e) {

		e.preventDefault();

		jQuery(main_menu).fadeOut('fast', function () {

			jQuery(holder).addClass('loading');

			jQuery.post(wp.ajax.settings.url, {

					action: 'show_sub_menu', /* action ajax hook name */
					option: 'magazine',
					_wpnonce: nonce

				}, function (data) {

					if (data.length > 1) {

						jQuery(sub_menu).html(data);

						jQuery(holder).removeClass('loading');
						jQuery(sub_menu).fadeIn('fast', function () {
							jQuery(sub_menu).find('.owl-carousel').owlCarousel({
								loop: true,
								margin: 10,
								nav: true,
								items: 1
							});
						});

						jQuery('.side-menu').sameHeight({
							elements: '.inner',
							flexible: true,
							multiLine: true
						});

					}
				}
			);

		});

	});

	jQuery(document).on('click', back_btn, function (e) {

		e.preventDefault();

		if (jQuery(this).data('link') == 'main') {

			jQuery(holder).addClass('loading');

			jQuery(sub_menu).fadeOut('fast', function () {

				jQuery(holder).removeClass('loading');
				jQuery(main_menu).fadeIn('fast');

			});

		}

		if (jQuery(this).data('link') == 'sub') {

			jQuery(sub_menu).empty();

			jQuery(sub_menu).fadeOut('fast', function () {

				jQuery(holder).addClass('loading');

				jQuery.post(wp.ajax.settings.url, {

						action: 'show_sub_menu', /* action ajax hook name */
						option: 'magazine',
						_wpnonce: nonce

					}, function (data) {

						if (data.length > 1) {

							jQuery(sub_menu).html(data);

							jQuery(holder).removeClass('loading');
							jQuery(sub_menu).fadeIn('fast', function () {
								jQuery(sub_menu).find('.owl-carousel').owlCarousel({
									loop: true,
									margin: 10,
									nav: true,
									items: 1
								});
							});

							jQuery('.side-menu').sameHeight({
								elements: '.inner',
								flexible: true,
								multiLine: true
							});

						}
					}
				);

			});

		}

	});

	jQuery(document).on('click', read_magazine, function (e) {

		e.preventDefault();

		var id = jQuery(this).data('id');

		if (id != '') {

			jQuery(sub_menu).fadeOut('fast', function () {

				jQuery(holder).addClass('loading');

				jQuery.post(wp.ajax.settings.url, {

						action: 'show_sub_menu', /* action ajax hook name */
						option: 'issue',
						id: id,
						_wpnonce: nonce

					}, function (data) {

						if (data.length > 1) {

							jQuery(sub_menu).html(data);

							jQuery(holder).removeClass('loading');
							jQuery(sub_menu).fadeIn('fast', function () {
								jQuery(sub_menu).find('.owl-carousel').owlCarousel({
									loop: true,
									margin: 10,
									nav: true,
									items: 1
								});
							});

						} else {

							jQuery(sub_menu).fadeOut('fast', function () {
								jQuery(main_menu).fadeIn('fast');
							});

						}
					}
				);

			});

		}

	});

}

// handle dropdowns on mobile devices
function initTouchNav() {
	jQuery('#tabset').each(function () {
		new TouchNav({
			navBlock: this
		});
	});
}

// navigation accesibility module
function TouchNav(opt) {
	this.options = {
		hoverClass: 'hover',
		menuItems: 'li',
		menuOpener: 'a',
		menuDrop: 'ul',
		navBlock: null
	};
	for (var p in opt) {
		if (opt.hasOwnProperty(p)) {
			this.options[p] = opt[p];
		}
	}
	this.init();
}
TouchNav.isActiveOn = function (elem) {
	return elem && elem.touchNavActive;
};
TouchNav.prototype = {
	init: function () {
		if (typeof this.options.navBlock === 'string') {
			this.menu = document.getElementById(this.options.navBlock);
		} else if (typeof this.options.navBlock === 'object') {
			this.menu = this.options.navBlock;
		}
		if (this.menu) {
			this.addEvents();
		}
	},
	addEvents: function () {
		// attach event handlers
		var self = this;
		var touchEvent = (navigator.pointerEnabled && 'pointerdown') || (navigator.msPointerEnabled && 'MSPointerDown') || (this.isTouchDevice && 'touchstart');
		this.menuItems = lib.queryElementsBySelector(this.options.menuItems, this.menu);

		var initMenuItem = function (item) {
			var currentDrop = lib.queryElementsBySelector(self.options.menuDrop, item)[0],
				currentOpener = lib.queryElementsBySelector(self.options.menuOpener, item)[0];

			// only for touch input devices
			if (currentDrop && currentOpener && (self.isTouchDevice || self.isPointerDevice)) {
				lib.event.add(currentOpener, 'click', lib.bind(self.clickHandler, self));
				lib.event.add(currentOpener, 'mousedown', lib.bind(self.mousedownHandler, self));
				lib.event.add(currentOpener, touchEvent, function (e) {
					if (!self.isTouchPointerEvent(e)) {
						self.preventCurrentClick = false;
						return;
					}
					self.touchFlag = true;
					self.currentItem = item;
					self.currentLink = currentOpener;
					self.pressHandler.apply(self, arguments);
				});
			}
			// for desktop computers and touch devices
			jQuery(item).bind('mouseenter', function () {
				if (!self.touchFlag) {
					self.currentItem = item;
					self.mouseoverHandler();
				}
			});
			jQuery(item).bind('mouseleave', function () {
				if (!self.touchFlag) {
					self.currentItem = item;
					self.mouseoutHandler();
				}
			});
			item.touchNavActive = true;
		};

		// addd handlers for all menu items
		for (var i = 0; i < this.menuItems.length; i++) {
			initMenuItem(self.menuItems[i]);
		}

		// hide dropdowns when clicking outside navigation
		if (this.isTouchDevice || this.isPointerDevice) {
			lib.event.add(document.documentElement, 'mousedown', lib.bind(this.clickOutsideHandler, this));
			lib.event.add(document.documentElement, touchEvent, lib.bind(this.clickOutsideHandler, this));
		}
	},
	mousedownHandler: function (e) {
		if (this.touchFlag) {
			e.preventDefault();
			this.touchFlag = false;
			this.preventCurrentClick = false;
		}
	},
	mouseoverHandler: function () {
		lib.addClass(this.currentItem, this.options.hoverClass);
		jQuery(this.currentItem).trigger('itemhover');
	},
	mouseoutHandler: function () {
		lib.removeClass(this.currentItem, this.options.hoverClass);
		jQuery(this.currentItem).trigger('itemleave');
	},
	hideActiveDropdown: function () {
		for (var i = 0; i < this.menuItems.length; i++) {
			if (lib.hasClass(this.menuItems[i], this.options.hoverClass)) {
				lib.removeClass(this.menuItems[i], this.options.hoverClass);
				jQuery(this.menuItems[i]).trigger('itemleave');
			}
		}
		this.activeParent = null;
	},
	pressHandler: function (e) {
		// hide previous drop (if active)
		if (this.currentItem !== this.activeParent) {
			if (this.activeParent && this.currentItem.parentNode === this.activeParent.parentNode) {
				lib.removeClass(this.activeParent, this.options.hoverClass);
			} else if (!this.isParent(this.activeParent, this.currentLink)) {
				this.hideActiveDropdown();
			}
		}
		// handle current drop
		this.activeParent = this.currentItem;
		if (lib.hasClass(this.currentItem, this.options.hoverClass)) {
			this.preventCurrentClick = false;
		} else {
			e.preventDefault();
			this.preventCurrentClick = true;
			lib.addClass(this.currentItem, this.options.hoverClass);
			jQuery(this.currentItem).trigger('itemhover');
		}
	},
	clickHandler: function (e) {
		// prevent first click on link
		if (this.preventCurrentClick) {
			e.preventDefault();
		}
	},
	clickOutsideHandler: function (event) {
		var e = event.changedTouches ? event.changedTouches[0] : event;
		if (this.activeParent && !this.isParent(this.menu, e.target)) {
			this.hideActiveDropdown();
			this.touchFlag = false;
		}
	},
	isParent: function (parent, child) {
		while (child.parentNode) {
			if (child.parentNode == parent) {
				return true;
			}
			child = child.parentNode;
		}
		return false;
	},
	isTouchPointerEvent: function (e) {
		return (e.type.indexOf('touch') > -1) ||
			(navigator.pointerEnabled && e.pointerType === 'touch') ||
			(navigator.msPointerEnabled && e.pointerType == e.MSPOINTER_TYPE_TOUCH);
	},
	isPointerDevice: (function () {
		return !!(navigator.pointerEnabled || navigator.msPointerEnabled);
	}()),
	isTouchDevice: (function () {
		return !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
	}())
};

/*
 * Utility module
 */
lib = {
	hasClass: function (el, cls) {
		return el && el.className ? el.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)')) : false;
	},
	addClass: function (el, cls) {
		if (el && !this.hasClass(el, cls)) el.className += " " + cls;
	},
	removeClass: function (el, cls) {
		if (el && this.hasClass(el, cls)) {
			el.className = el.className.replace(new RegExp('(\\s|^)' + cls + '(\\s|$)'), ' ');
		}
	},
	extend: function (obj) {
		for (var i = 1; i < arguments.length; i++) {
			for (var p in arguments[i]) {
				if (arguments[i].hasOwnProperty(p)) {
					obj[p] = arguments[i][p];
				}
			}
		}
		return obj;
	},
	each: function (obj, callback) {
		var property, len;
		if (typeof obj.length === 'number') {
			for (property = 0, len = obj.length; property < len; property++) {
				if (callback.call(obj[property], property, obj[property]) === false) {
					break;
				}
			}
		} else {
			for (property in obj) {
				if (obj.hasOwnProperty(property)) {
					if (callback.call(obj[property], property, obj[property]) === false) {
						break;
					}
				}
			}
		}
	},
	event: (function () {
		var fixEvent = function (e) {
			e = e || window.event;
			if (e.isFixed) return e; else e.isFixed = true;
			if (!e.target) e.target = e.srcElement;
			e.preventDefault = e.preventDefault || function () {
					this.returnValue = false;
				};
			e.stopPropagation = e.stopPropagation || function () {
					this.cancelBubble = true;
				};
			return e;
		};
		return {
			add: function (elem, event, handler) {
				if (!elem.events) {
					elem.events = {};
					elem.handle = function (e) {
						var ret, handlers = elem.events[e.type];
						e = fixEvent(e);
						for (var i = 0, len = handlers.length; i < len; i++) {
							if (handlers[i]) {
								ret = handlers[i].call(elem, e);
								if (ret === false) {
									e.preventDefault();
									e.stopPropagation();
								}
							}
						}
					};
				}
				if (!elem.events[event]) {
					elem.events[event] = [];
					if (elem.addEventListener) elem.addEventListener(event, elem.handle, false);
					else if (elem.attachEvent) elem.attachEvent('on' + event, elem.handle);
				}
				elem.events[event].push(handler);
			},
			remove: function (elem, event, handler) {
				var handlers = elem.events[event];
				for (var i = handlers.length - 1; i >= 0; i--) {
					if (handlers[i] === handler) {
						handlers.splice(i, 1);
					}
				}
				if (!handlers.length) {
					delete elem.events[event];
					if (elem.removeEventListener) elem.removeEventListener(event, elem.handle, false);
					else if (elem.detachEvent) elem.detachEvent('on' + event, elem.handle);
				}
			}
		};
	}()),
	queryElementsBySelector: function (selector, scope) {
		scope = scope || document;
		if (!selector) return [];
		if (selector === '>*') return scope.children;
		if (typeof document.querySelectorAll === 'function') {
			return scope.querySelectorAll(selector);
		}
		var selectors = selector.split(',');
		var resultList = [];
		for (var s = 0; s < selectors.length; s++) {
			var currentContext = [scope || document];
			var tokens = selectors[s].replace(/^\s+/, '').replace(/\s+$/, '').split(' ');
			for (var i = 0; i < tokens.length; i++) {
				token = tokens[i].replace(/^\s+/, '').replace(/\s+$/, '');
				if (token.indexOf('#') > -1) {
					var bits = token.split('#'), tagName = bits[0], id = bits[1];
					var element = document.getElementById(id);
					if (element && tagName && element.nodeName.toLowerCase() != tagName) {
						return [];
					}
					currentContext = element ? [element] : [];
					continue;
				}
				if (token.indexOf('.') > -1) {
					var bits = token.split('.'), tagName = bits[0] || '*', className = bits[1], found = [], foundCount = 0;
					for (var h = 0; h < currentContext.length; h++) {
						var elements;
						if (tagName == '*') {
							elements = currentContext[h].getElementsByTagName('*');
						} else {
							elements = currentContext[h].getElementsByTagName(tagName);
						}
						for (var j = 0; j < elements.length; j++) {
							found[foundCount++] = elements[j];
						}
					}
					currentContext = [];
					var currentContextIndex = 0;
					for (var k = 0; k < found.length; k++) {
						if (found[k].className && found[k].className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'))) {
							currentContext[currentContextIndex++] = found[k];
						}
					}
					continue;
				}
				if (token.match(/^(\w*)\[(\w+)([=~\|\^\$\*]?)=?"?([^\]"]*)"?\]$/)) {
					var tagName = RegExp.$1 || '*', attrName = RegExp.$2, attrOperator = RegExp.$3, attrValue = RegExp.$4;
					if (attrName.toLowerCase() == 'for' && this.browser.msie && this.browser.version < 8) {
						attrName = 'htmlFor';
					}
					var found = [], foundCount = 0;
					for (var h = 0; h < currentContext.length; h++) {
						var elements;
						if (tagName == '*') {
							elements = currentContext[h].getElementsByTagName('*');
						} else {
							elements = currentContext[h].getElementsByTagName(tagName);
						}
						for (var j = 0; elements[j]; j++) {
							found[foundCount++] = elements[j];
						}
					}
					currentContext = [];
					var currentContextIndex = 0, checkFunction;
					switch (attrOperator) {
						case '=':
							checkFunction = function (e) {
								return (e.getAttribute(attrName) == attrValue)
							};
							break;
						case '~':
							checkFunction = function (e) {
								return (e.getAttribute(attrName).match(new RegExp('(\\s|^)' + attrValue + '(\\s|$)')))
							};
							break;
						case '|':
							checkFunction = function (e) {
								return (e.getAttribute(attrName).match(new RegExp('^' + attrValue + '-?')))
							};
							break;
						case '^':
							checkFunction = function (e) {
								return (e.getAttribute(attrName).indexOf(attrValue) == 0)
							};
							break;
						case '$':
							checkFunction = function (e) {
								return (e.getAttribute(attrName).lastIndexOf(attrValue) == e.getAttribute(attrName).length - attrValue.length)
							};
							break;
						case '*':
							checkFunction = function (e) {
								return (e.getAttribute(attrName).indexOf(attrValue) > -1)
							};
							break;
						default :
							checkFunction = function (e) {
								return e.getAttribute(attrName)
							};
					}
					currentContext = [];
					var currentContextIndex = 0;
					for (var k = 0; k < found.length; k++) {
						if (checkFunction(found[k])) {
							currentContext[currentContextIndex++] = found[k];
						}
					}
					continue;
				}
				tagName = token;
				var found = [], foundCount = 0;
				for (var h = 0; h < currentContext.length; h++) {
					var elements = currentContext[h].getElementsByTagName(tagName);
					for (var j = 0; j < elements.length; j++) {
						found[foundCount++] = elements[j];
					}
				}
				currentContext = found;
			}
			resultList = [].concat(resultList, currentContext);
		}
		return resultList;
	},
	trim: function (str) {
		return str.replace(/^\s+/, '').replace(/\s+$/, '');
	},
	bind: function (f, scope, forceArgs) {
		return function () {
			return f.apply(scope, typeof forceArgs !== 'undefined' ? [forceArgs] : arguments);
		};
	}
};


// popups init
function initPopups() {
	jQuery('.apartment-block').contentPopup({
		mode: 'click',
		popup: '.popup-remove',
		hideOnClickOutside: false
	});
}

// add class on click
function initAddClasses() {
	jQuery('.congratilations-close').clickClass({
		classAdd: 'enter-page',
		addToParent: 'loader'
	});
}

jQuery('#nav [data-toggle="dropdown"][href!="#"]').on('click', function () {
	if (jQuery(this.parentNode).hasClass('open')) {
		location.href = this.href;
	}
});

function initReloadProducts() {
	// jQuery('.navigation-list').each(function () {
	// 	var form = jQuery(this);
	// 	var select = form.find('select[name="buy"]');
	// 	var index = select[0].selectedIndex;
	//
	// 	select.on('change', function () {
	// 		if (index !== select[0].selectedIndex) {
	// 			form.submit();
	// 		}
	// 	});
	// });

	var links = '#header .right_wrapper .filter',
		selects = '#header .right_wrapper .select_filter_big';

	jQuery(document).on('click', links, function (e) {
		e.preventDefault();

		var _this = jQuery(this),
			form = _this.closest('form');


		if (_this.data('filter') == 'city') {
			jQuery('input#city').val(_this.data('value'));
		}

		if (_this.data('filter') == 'area') {
			jQuery('input#area').val(_this.data('value'));
		}

		form.submit();

	});

	jQuery(selects).on('change', function (e) {

		e.preventDefault();
		var _this = jQuery(this),
			form = _this.closest('form');

		if (_this.data('filter') == 'beds') {
			jQuery('input#beds').val(_this.find('option:selected').val());
		}

		if (_this.data('filter') == 'price') {
			jQuery('input#price').val(_this.find('option:selected').val());
		}


		form.submit();

	});
}

function uploadMedia() {
	var frame;
	var button = jQuery('#frontend-media');
	var button2 = jQuery('#frontend-media2');
	var container = jQuery('#apartment-gallery');
	var container2 = jQuery('.featured-image');

	button.on('click', function (e) {
		e.preventDefault();

		if (frame) {
			frame.open();
			return;
		}

		frame = wp.media({
			title: 'Select or Upload',
			button: {
				text: 'Select'
			},
			multiple: true
		});

		frame.on('select', function () {
			var attachments = frame.state().get('selection');
			var html = '';

			if (attachments.length) {
				for (var i = 0; i < attachments.length; i++) {
					var attributes = attachments.models[i].attributes;
					html += '<li><picture><img src="' + attributes.sizes.thumbnail_76x76.url + '" /></picture><input type="hidden" name="attachments[]" value="' + attributes.id + '"></li>';
				}

				container.empty().append(html);
			}
		});

		frame.open();
	});

	button2.on('click', function (e) {
		e.preventDefault();

		if (frame) {
			frame.open();
			return;
		}

		frame = wp.media({
			title: 'Select or Upload',
			button: {
				text: 'Select'
			},
			multiple: true
		});

		frame.on('select', function () {
			var attachments = frame.state().get('selection');
			var html = '';

			if (attachments.length) {
				for (var i = 0; i < attachments.length; i++) {
					var attributes = attachments.models[i].attributes;
					html += '<picture><img src="' + attributes.sizes.thumbnail_76x76.url + '" /></picture><input type="hidden" name="featured" value="' + attributes.id + '">';
				}

				container2.empty().append(html);
			}
		});

		frame.open();
	});
}

function initializePlaces(q) {
	googleAutocompleteService = new google.maps.places.AutocompleteService();
	if (q) {
		googleAutocompleteService.getPlacePredictions({
			input: q
		}, callbackPlaces);
	}
}

function callbackPlaces(predictions, status) {
	if (status == 'OK') {
		var results = [];
		for (var i = 0; i < predictions.length; i++) {
			results.push(predictions[i].description);
		}

		jQuery('#searchTextField').autocomplete({
			source: results,
			minLength: 1,
			open: function () {
				jQuery(this).autocomplete("widget")
					.appendTo("#results")
					.css("position", "static");
			}
		});
	}
};

function modalEditForm() {
	jQuery('.modal-lg .edit-form').each(function () {
		var holder = jQuery(this);
		var src = holder.data('src');
		var address = jQuery('#searchTextField');

		address.on('keyup', function (e) {
			initializePlaces(address.val());
		});

		uploadMedia();

		holder.on('submit', function (e) {
			e.preventDefault();

			if (pathInfo.savedSettings) {
				/*
				 if ( address.val() ) {
				 var latitude = 0;
				 var longitude = 0;
				 var geocoder = new google.maps.Geocoder();
				 geocoder.geocode({ 'address': address.val() }, function (results, status) {
				 if (status == google.maps.GeocoderStatus.OK) {
				 latitude = results[0].geometry.location.lat();
				 longitude = results[0].geometry.location.lng();
				 console.log("Latitude: " + latitude + "\nLongitude: " + longitude);
				 }
				 });
				 console.log(latitude, longitude);
				 }
				 */
				var formData = {
					'action': 'ajax_settings_apartment_save',
					'security': pathInfo.ajax_nonce,
					'data': holder.serialize()
				};

				var ajaxResponse = jQuery('#ajax_response');

				jQuery.ajax({
					url: src,
					dataType: 'json',
					type: 'post',
					data: formData,
					success: function (response) {
						if (response.status) {
							holder.hide();
							ajaxResponse.css('color', '#00FF00');
						} else {
							ajaxResponse.css('color', '#F00');
						}

						if (jQuery.type(response) == 'object') {
							ajaxResponse.empty().append(response.message);
						}
					}
				});
			}
		});
	});
}


function filter_reset(url) {
	window.location = url;
}

function initFormValidation() {
	jQuery('.contact-form').formValidation({
		addClassToParent: '.contact-form',
		successSendClass: 'success',
		errorClass: 'input-error'
	});
}

function initFixedBlock() {
	var win = jQuery(window);
	var activeClass = 'fixed-position';
	var flag = true;

	jQuery('#bar').each(function () {
		var block = jQuery(this);
		var blockTop;

		function scrollHandler() {
			if (win.scrollTop() > blockTop) {
				if (flag) {
					block.addClass(activeClass);
					flag = false;
				}
			} else {
				if (!flag) {
					block.removeClass(activeClass);
					flag = true;
				}
			}
		}

		function resizeHandler() {
			flag = true;
			block.removeClass(activeClass);
			blockTop = block.offset().top;
			scrollHandler();
		}

		win.on('scroll', scrollHandler);
		win.on('resize load', resizeHandler);
		resizeHandler();
	});

	var wrapper = '#wrapper',
		banner = '.banner-home',
		banner_title = '.title_wrapper .title',
		header = '#header',
		range = 250

	jQuery(window).scroll(function () {
		if (jQuery('body').hasClass('home')) {

			var alpha = Math.min(0.4 * jQuery(this).scrollTop() / 250, 1);
			var scrollTop = jQuery(this).scrollTop();


			// No need for hero image effect

			// jQuery(banner).css({'opacity': calc});
			if (scrollTop > 50) {
				jQuery(header).css({'background-color': 'rgba(255,255,255,' + alpha + ')'});
			} else {
				jQuery(header).css({'background-color': 'rgba(255,255,255,' + 0 + ')'});
			}

			if (scrollTop > 50) {
				console.log(alpha);
				jQuery(banner_title).css({'opacity': 1 - alpha * 1.5});
			} else {
				jQuery(banner_title).css({'opacity': 1});
			}

			if (scrollTop > 300) {
				jQuery(wrapper).addClass('scrolling');
			} else {
				jQuery(wrapper).removeClass('scrolling');
			}

		} else {

			if (jQuery(this).scrollTop() > 1) {
				jQuery(wrapper).addClass('scrolling');
			} else {
				jQuery(wrapper).removeClass('scrolling');
			}

		}

	});

	if (jQuery(window).scrollTop() > 0) {
		jQuery('#wrapper').addClass('scrolling');
	}
}

// add classes on hover/touch
function initCustomHover() {
	jQuery('.apartments-list .visual').touchHover();
	jQuery('.apartment-block .visual').touchHover();
}

function initRemoveItems() {
	jQuery('.remove-form').each(function () {
		var form = jQuery(this);
		var links = form.find('.links a');
		var checkboxes = form.find('input[type="checkbox"]');

		links.on('click', function (e) {
			e.preventDefault();

			var formData = {
				'action': 'ajax_settings_user_remove',
				'security': pathInfo.ajax_nonce,
				'data': form.serialize()
			};

			ajaxSend({
				type: 'post',
				url: form.data('action'),
				ajaxData: formData
			});

			if (jQuery(this).hasClass('remove')) {
				removeItems();
			}
		});

		function removeItems() {
			checkboxes.each(function () {
				var checkbox = jQuery(this);

				if (checkbox.is(':checked')) {
					checkbox.closest('tr').remove();
				}
			});
		}
	});

	jQuery('.listings-block').each(function () {
		var holder = jQuery(this);
		var items = holder.find('[data-post-id]');
		var src = holder.data('src');
		var loadingClass = 'loading';

		holder.on('click', 'a.remove-block', function (e) {
			e.preventDefault();
			var item = jQuery(this).closest('[data-post-id]');

			var formData = {
				'action': 'ajax_settings_apartment_remove',
				'security': pathInfo.ajax_nonce,
				'id': item.attr('data-post-id')
			};

			ajaxSend({
				type: 'post',
				url: src,
				ajaxData: formData
			});
			item.remove();
		});
		holder.on('click', 'a.prime', function (e) {
			e.preventDefault();
			var item = jQuery(this).closest('[data-post-id]');

			var formData = {
				'action': 'ajax_settings_apartment_prime',
				'security': pathInfo.ajax_nonce,
				'id': item.attr('data-post-id')
			};

			holder.addClass(loadingClass);

			ajaxSend({
				type: 'post',
				url: src,
				ajaxData: formData,
				success: function (data) {
					holder.animate({
						opacity: 0
					}, 300, function () {
						holder.empty().html(data).animate({opacity: 1});
						holder.removeClass(loadingClass);
						initPopups();
					});
				}
			});
		});
	});

	function ajaxSend(obj) {
		jQuery.ajax({
			type: obj.type,
			url: obj.url,
			data: obj.ajaxData,
			success: obj.success
		});
	}
}

// custom map init
function initCustomMap() {
	var isTouchDevice = /Windows Phone/.test(navigator.userAgent) || ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch;
	jQuery('.map-box').each(function () {
		jQuery(this).data('CustomMap', new CustomMap({
			holder: this,
			defaultOptions: {
				maxZoom: 13,
				zoom: 12,
				draggable: !isTouchDevice,
				scrollwheel: false,
				disableDefaultUI: false,
				mapTypeControl: false,
				streetViewControl: false
			}
		}));
	});
}

// fancybox modal popup init
function initLightbox() {
	var win = jQuery(window);

	// jQuery('a.lightbox, a[rel*="lightbox"]').fancybox({
	// 	helpers: {
	// 		overlay: {
	// 			css: {
	// 				background: 'rgba(0, 0, 0, 0.50)'
	// 			}
	// 		}
	// 	},
	// 	afterShow: function () {
	// 		win.trigger('mapRefresh');
	// 		jcf.replaceAll();
	// 	},
	// 	afterClose: function () {
	// 		win.trigger('mapRefresh');
	// 	},
	// 	afterLoad: function (current, previous) {
	// 		// handle custom close button in inline modal
	// 		if (current.href.indexOf('#') === 0) {
	// 			jQuery(current.href).find('a.close').off('click.fb').on('click.fb', function (e) {
	// 				e.preventDefault();
	// 				jQuery.fancybox.close();
	// 			});
	// 		}
	// 	},
	// 	padding: 0,
	// 	beforeShow: function () {
	// 		jcf.replaceAll();
	// 		modalEditForm();
	// 	}
	// });
	/*jQuery('a.lightbox, a[rel*="lightbox"]').fancybox({
	 helpers: {
	 overlay: {
	 css: {
	 background: 'rgba(0, 0, 0, 0.65)'
	 }
	 }
	 },
	 afterLoad: function(current, previous) {
	 // handle custom close button in inline modal
	 if(current.href.indexOf('#') === 0) {
	 jQuery(current.href).find('a.close').off('click.fb').on('click.fb', function(e){
	 e.preventDefault();
	 jQuery.fancybox.close();
	 });
	 }
	 }
	 });
	 */
}

;(function (w) {
	w.addEventListener('load', function () {
		var loader = document.querySelector('body.loader');

		if (loader) {
			loader.classList.add('loaded');
		}
	});
}(window));

// content tabs init
function initTabs() {
	jQuery('ul.tabset').tabset({
		tabLinks: 'a',
		addToParent: true,
		defaultTab: false,
		checkHash: true
	});

	/*jQuery('ul.sub-tabset').tabset({
	 tabLinks: 'a',
	 addToParent: true,
	 defaultTab: false,
	 checkHash: true
	 });
	 */
}

// initialize custom form elements
function initCustomForms() {
	// jcf.setOptions('Select', {
	// 	wrapNative: false,
	// 	wrapNativeOnMobile: false,
	// 	maxVisibleItems: 10,
	// 	flipDropToFit: false,
	// 	fakeDropInBody: false,
	// 	useCustomScroll: true
	// });
	// jcf.replaceAll();

	var filters = '#header .filter-dropdown',
		dropdown = jQuery(filters).parent().find('.dropdown');

	jQuery(document).on('click', filters, function (e) {

		e.preventDefault();

		jQuery('html').one('click', function (e) {
			if (!jQuery(event.target).is('select')) {
				dropdown.removeClass('active');
			}
		});

		dropdown.removeClass('active');
		jQuery(this).parent().find('.dropdown').addClass('active');
	});
}

function initProductsList() {
	var amountProduct = jQuery('.amount-items');
	var activeClass = 'panel-active';
	var activeItemClass = 'saved';
	var page = jQuery('#wrapper');
	var header = '#header',
		bottom_wrapper = jQuery(header).find('.wrapper_bottom'),
		saved_items = bottom_wrapper.find('.saved-items');


	jQuery(document).on('click', 'a[data-product-id]', function (e) {
		e.preventDefault();
		var link = jQuery(this);
		var productID = getProductID(this);
		var amount = StorageCookie.amount();

		if (link.hasClass(activeItemClass)) {
			if (amount == 1) {

				console.log(window.location.pathname.indexOf('\/rent') == 0);
				console.log(window.location.pathname.indexOf('\/buy') == 0);
				console.log(window.location.pathname.indexOf('\/location') == 0);
				console.log(window.location.pathname.indexOf('\/area') == 0);


				if (!(window.location.pathname.indexOf('/rent') == 0 ||
					window.location.pathname.indexOf('/buy') == 0 ||
					window.location.pathname.indexOf('/location') == 0 ||
					window.location.pathname.indexOf('/area') == 0)) {
					bottom_wrapper.addClass('hidden');
					saved_items.addClass('hidden');
				}
			}
			link.removeClass(activeItemClass);
			removeProductByID(productID, link);
		} else {
			if (amount == 0) {
				bottom_wrapper.removeClass('hidden');
				saved_items.removeClass('hidden');
			}
			link.addClass(activeItemClass);
			addProductByID(productID, this);
		}
	});

	jQuery(document).on('click', 'a.remove[data-product-id]', function (e) {
		e.preventDefault();
		var productID = getProductID(this);
		removeProductByID(productID, jQuery(this));
		if (amount == 0) {
			bottom_wrapper.removeClass('hidden');
			saved_items.removeClass('hidden');
		}
	});

	function getProductID(link) {
		return jQuery(link).attr('data-product-id');
	}

	function addProductByID(id, link) {
		if (!id) return;
		StorageCookie.set(id);
		productInfo();
	}

	function removeProductByID(id, link) {
		if (!id) return;
		StorageCookie.remove(id);
		var productItem = jQuery('#' + id);
		productItem.remove();
		productInfo();
	}

	function productInfo() {
		var amount = StorageCookie.amount();

		if (amount > 0) {
			page.addClass(activeClass);
		} else {
			page.removeClass(activeClass);
		}

		amountProduct.text(amount);
	}

	productInfo();
}


;(function ($) {
	var cookieName = 'StorageCookie',
		expiresTime = false;

	window.StorageCookie = {
		set: function (value) {
			var storage;
			if (!$.cookie(cookieName)) {
				$.cookie(cookieName, '[]', {expires: expiresTime, path: '/'});
			}
			storage = JSON.parse($.cookie(cookieName));
			storage[name] = value;
			if (getItemIndex(storage, value) == -1) {
				storage.push(value);
				$.cookie(cookieName, JSON.stringify(storage), {expires: expiresTime, path: '/'});
				return true;
			}
		},
		remove: function (value) {
			var storage,
				cookieStr = $.cookie(cookieName),
				itemIndex;

			if (cookieStr) {
				storage = JSON.parse(cookieStr);
				itemIndex = getItemIndex(storage, value);

				if (itemIndex == -1) {
					return null;
				} else {
					storage.splice(itemIndex, 1);
					$.cookie(cookieName, JSON.stringify(storage), {expires: expiresTime, path: '/'});
				}
			} else {
				return null;
			}

			if (pathInfo.saved) {
				window.location = location.href;

			} else if (pathInfo.savedSettings) {
				jQuery('.apartments-list #apartment-' + value).hide();
			}
		},
		amount: function () {
			if (!$.cookie(cookieName)) {
				return 0;
			} else {
				return JSON.parse($.cookie(cookieName)).length;
			}

		}
	};

	function getItemIndex(array, item) {
		var indexInArray = -1;
		jQuery.each(array, function (index, el) {
			if (item == el) {
				indexInArray = index;
				return false;
			}
		});
		return indexInArray;
	}
})(jQuery);

/*!
 * jQuery Cookie Plugin
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
;(function ($) {
	$.cookie = function (key, value, options) {

		// key and at least value given, set cookie...
		if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
			options = $.extend({}, options);

			if (value === null || value === undefined) {
				options.expires = -1;
			}

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}

			value = String(value);

			return (document.cookie = [
				encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path ? '; path=' + options.path : '',
				options.domain ? '; domain=' + options.domain : '',
				options.secure ? '; secure' : ''
			].join(''));
		}

		// key and possibly options given, get cookie...
		options = value || {};
		var decode = options.raw ? function (s) {
			return s;
		} : decodeURIComponent;

		var pairs = document.cookie.split('; ');
		for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) {
			if (decode(pair[0]) === key) return decode(pair[1] || ''); // IE saves cookies with empty string as "c; ", e.g. without "=" as opposed to EOMB, thus pair[1] may be undefined
		}
		return null;
	};
})(jQuery);


function initAjaxFilters() {
	jQuery('.filter-form').ajaxFilters({
		ajaxData: 'ajax=true',
		ajaxBlockAttr: 'data-load-container',
		items: '> li',
		dropNav: '.dropdown-menu'
	});
}

// cycle scroll gallery init
function initCycleCarousel() {
	jQuery('div.cycle-gallery').scrollAbsoluteGallery({
		mask: 'div.mask',
		slider: 'div.slideset',
		slides: 'div.slide',
		btnPrev: 'a.btn-prev',
		btnNext: 'a.btn-next',
		pagerLinks: '.pagination li',
		stretchSlideToMask: true,
		pauseOnHover: true,
		maskAutoSize: true,
		autoRotation: false,
		switchTime: 3000,
		animSpeed: 500,
		onInit: function (self) {
			self.holder.find('.zoom').on('click', function (e) {
				e.preventDefault();
				var lightboxOpener = self.slides.eq(self.currentIndex).find('a[rel]');

				if (lightboxOpener) {
					lightboxOpener.trigger('click');
				}
			});
		}
	});
	jQuery('div.cycle-images').scrollAbsoluteGallery({
		mask: 'div.mask',
		slider: 'div.slideset',
		slides: 'div.slide',
		btnPrev: 'a.btn-prev',
		btnNext: 'a.btn-next',
		pagerLinks: '.pagination li',
		stretchSlideToMask: true,
		pauseOnHover: true,
		maskAutoSize: true,
		autoRotation: false,
		switchTime: 3000,
		animSpeed: 500
	});
	jQuery('div.post-gallery').scrollAbsoluteGallery({
		mask: 'div.mask',
		slider: 'div.slideset',
		slides: 'div.slide',
		btnPrev: 'a.btn-prev',
		btnNext: 'a.btn-next',
		pagerLinks: '.pagination li',
		stretchSlideToMask: true,
		pauseOnHover: true,
		maskAutoSize: true,
		autoRotation: false,
		switchTime: 3000,
		animSpeed: 500
	});
}

// open-close init
function initOpenClose() {
	jQuery('div.open-close').openClose({
		activeClass: 'active',
		opener: '.opener',
		slider: '.slide',
		animSpeed: 400,
		effect: 'slide'
	});
	jQuery('.tabset-slide').openClose({
		activeClass: 'open-tabset',
		opener: '.tabset-opener',
		slider: '.tabset',
		animSpeed: 400,
		effect: 'slide'
	});
}

/*// initialize fixed blocks on scroll
 function initFixedScrollBlock() {
 jQuery('#wrapper').fixedScrollBlock({
 slideBlock: '#bar',
 positionType: 'fixed'
 });
 }*/

// align blocks height
function initSameHeight() {

	jQuery('.block-content').sameHeight({
		elements: '.contact-col',
		flexible: true,
		multiLine: true
	});

	jQuery('.side-menu').sameHeight({
		elements: '.inner',
		flexible: true,
		multiLine: true
	});
}

/*
 * Popups plugin
 */
;(function ($) {
	function ContentPopup(opt) {
		this.options = $.extend({
			holder: null,
			popup: '.popup',
			btnOpen: '.open',
			btnClose: '.close',
			openClass: 'popup-active',
			clickEvent: 'click',
			mode: 'click',
			hideOnClickLink: true,
			hideOnClickOutside: true,
			delay: 50
		}, opt);
		if (this.options.holder) {
			this.holder = $(this.options.holder);
			this.init();
		}
	}

	ContentPopup.prototype = {
		init: function () {
			this.findElements();
			this.attachEvents();
		},
		findElements: function () {
			this.popup = this.holder.find(this.options.popup);
			this.btnOpen = this.holder.find(this.options.btnOpen);
			this.btnClose = this.holder.find(this.options.btnClose);
		},
		attachEvents: function () {
			// handle popup openers
			var self = this;
			this.clickMode = isTouchDevice || (self.options.mode === self.options.clickEvent);

			if (this.clickMode) {
				// handle click mode
				this.btnOpen.bind(self.options.clickEvent, function (e) {
					if (self.holder.hasClass(self.options.openClass)) {
						if (self.options.hideOnClickLink) {
							self.hidePopup();
						}
					} else {
						self.showPopup();
					}
					e.preventDefault();
				});

				// prepare outside click handler
				this.outsideClickHandler = this.bind(this.outsideClickHandler, this);
			} else {
				// handle hover mode
				var timer, delayedFunc = function (func) {
					clearTimeout(timer);
					timer = setTimeout(function () {
						func.call(self);
					}, self.options.delay);
				};
				this.btnOpen.bind('mouseover', function () {
					delayedFunc(self.showPopup);
				}).bind('mouseout', function () {
					delayedFunc(self.hidePopup);
				});
				this.popup.bind('mouseover', function () {
					delayedFunc(self.showPopup);
				}).bind('mouseout', function () {
					delayedFunc(self.hidePopup);
				});
			}

			// handle close buttons
			this.btnClose.bind(self.options.clickEvent, function (e) {
				self.hidePopup();
				e.preventDefault();
			});
		},
		outsideClickHandler: function (e) {
			// hide popup if clicked outside
			var targetNode = $((e.changedTouches ? e.changedTouches[0] : e).target);
			if (!targetNode.closest(this.popup).length && !targetNode.closest(this.btnOpen).length) {
				this.hidePopup();
			}
		},
		showPopup: function () {
			// reveal popup
			this.holder.addClass(this.options.openClass);
			this.popup.css({display: 'block'});

			// outside click handler
			if (this.clickMode && this.options.hideOnClickOutside && !this.outsideHandlerActive) {
				this.outsideHandlerActive = true;
				$(document).bind('click touchstart', this.outsideClickHandler);
			}
		},
		hidePopup: function () {
			// hide popup
			this.holder.removeClass(this.options.openClass);
			this.popup.css({display: 'none'});

			// outside click handler
			if (this.clickMode && this.options.hideOnClickOutside && this.outsideHandlerActive) {
				this.outsideHandlerActive = false;
				$(document).unbind('click touchstart', this.outsideClickHandler);
			}
		},
		bind: function (f, scope, forceArgs) {
			return function () {
				return f.apply(scope, forceArgs ? [forceArgs] : arguments);
			};
		}
	};

	// detect touch devices
	var isTouchDevice = /Windows Phone/.test(navigator.userAgent) || ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch;

	// jQuery plugin interface
	$.fn.contentPopup = function (opt) {
		return this.each(function () {
			new ContentPopup($.extend(opt, {holder: this}));
		});
	};
}(jQuery));

/*
 * Add class plugin
 */
jQuery.fn.clickClass = function (opt) {
	var options = jQuery.extend({
		classAdd: 'add-class',
		addToParent: false,
		event: 'click'
	}, opt);

	return this.each(function () {
		var classItem = jQuery(this);
		if (options.addToParent) {
			if (typeof options.addToParent === 'boolean') {
				classItem = classItem.parent();
			} else {
				classItem = classItem.parents('.' + options.addToParent);
			}
		}
		jQuery(this).bind(options.event, function (e) {
			classItem.toggleClass(options.classAdd);
			e.preventDefault();
		});
	});
};


/*
 * jQuery form validation plugin
 */
;(function ($) {
	'use strict';

	var FormValidation = (function () {
		var Validator = function ($field, $fields) {
			this.$field = $field;
			this.$fields = $fields;
		};

		Validator.prototype = {
			reg: {
				email: '^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$',
				number: '^[0-9]+$'
			},

			checkField: function () {
				return {
					state: this.run(),
					$fields: this.$field.add(this.additionalFields)
				}
			},

			run: function () {
				var fieldType;

				switch (this.$field.get(0).tagName.toUpperCase()) {
					case 'SELECT':
						fieldType = 'select';
						break;

					case 'TEXTAREA':
						fieldType = 'text';
						break;

					default:
						fieldType = this.$field.data('type') || this.$field.attr('type');
				}

				var functionName = 'check_' + fieldType;
				var state = true;

				if ($.isFunction(this[functionName])) {
					state = this[functionName]();

					if (state && this.$field.data('confirm')) {
						state = this.check_confirm();
					}
				}

				return state;
			},

			check_email: function () {
				var value = this.getValue();
				var required = this.$field.data('required');
				var requiredOrValue = required || value.length;

				if ((requiredOrValue && !this.check_regexp(value, this.reg.email))) {
					return false;
				}

				return requiredOrValue ? true : null;
			},

			check_number: function () {
				var value = this.getValue();
				var required = this.$field.data('required');
				var isNumber = this.check_regexp(value, this.reg.number);
				var requiredOrValue = required || value.length;

				if (requiredOrValue && !isNumber) {
					return false;
				}

				var min = this.$field.data('min');
				var max = this.$field.data('max');
				value = +value;

				if ((min && (value < min || !isNumber)) || (max && (value > max || !isNumber))) {
					return false;
				}

				return (requiredOrValue || min || max) ? true : null;
			},

			check_password: function () {
				return this.check_text();
			},

			check_text: function () {
				var value = this.getValue();
				var required = this.$field.data('required');

				if (this.$field.data('required') && !value.length) {
					return false;
				}

				var min = +this.$field.data('min');
				var max = +this.$field.data('max');

				if ((min && value.length < min) || (max && value.length > max)) {
					return false;
				}

				var regExp = this.$field.data('regexp');

				if (regExp && !this.check_regexp(value, regExp)) {
					return false;
				}

				return (required || min || max || regExp) ? true : null;
			},

			check_confirm: function () {
				var value = this.getValue();
				var $confirmFields = this.$fields.filter('[data-confirm="' + this.$field.data('confirm') + '"]');
				var confirmState = true;

				for (var i = $confirmFields.length - 1; i >= 0; i--) {
					if ($confirmFields.eq(i).val() !== value || !value.length) {
						confirmState = false;
						break;
					}
				}

				this.additionalFields = $confirmFields;

				return confirmState;
			},

			check_select: function () {
				var required = this.$field.data('required');

				if (required && this.$field.get(0).selectedIndex === 0) {
					return false;
				}

				return required ? true : null;
			},

			check_radio: function () {
				var $fields = this.$fields.filter('[name="' + this.$field.attr('name') + '"]');
				var required = this.$field.data('required');

				if (required && !$fields.filter(':checked').length) {
					return false;
				}

				this.additionalFields = $fields;

				return required ? true : null;
			},

			check_checkbox: function () {
				var required = this.$field.data('required');

				if (required && !this.$field.prop('checked')) {
					return false;
				}

				return required ? true : null;
			},

			check_at_least_one: function () {
				var $fields = this.$fields.filter('[data-name="' + this.$field.data('name') + '"]');

				if (!$fields.filter(':checked').length) {
					return false;
				}

				this.additionalFields = $fields;

				return true;
			},

			check_regexp: function (val, exp) {
				return new RegExp(exp).test(val);
			},

			getValue: function () {
				if (this.$field.data('trim')) {
					this.$field.val($.trim(this.$field.val()));
				}

				return this.$field.val();
			}
		};

		var publicClass = function (form, options) {
			this.$form = $(form).attr('novalidate', 'novalidate');
			this.options = options;
		};

		publicClass.prototype = {
			buildSelector: function (input) {
				return ':input:not(' + this.options.skipDefaultFields + (this.options.skipFields ? ',' + this.options.skipFields : '') + ')';
			},

			init: function () {
				this.fieldsSelector = this.buildSelector(':input');

				this.$form
					.on('submit', this.submitHandler.bind(this))
					.on('keyup blur', this.fieldsSelector, this.changeHandler.bind(this))
					.on('change', this.buildSelector('select'), this.changeHandler.bind(this))
					.on('focus', this.fieldsSelector, this.focusHandler.bind(this));
			},

			submitHandler: function (e) {
				var self = this;
				var $fields = this.getFormFields();

				this.getClassTarget($fields)
					.removeClass(this.options.errorClass + ' ' + this.options.successClass);

				this.setFormState(true);

				$fields.each(function (i, input) {
					var $field = $(input);
					var $classTarget = self.getClassTarget($field);

					// continue iteration if $field has error class already
					if ($classTarget.hasClass(self.options.errorClass)) {
						return;
					}

					self.setState(new Validator($field, $fields).checkField());
				});

				return this.checkSuccess($fields, e);
			},

			checkSuccess: function ($fields, e) {
				var self = this;
				var success = this.getClassTarget($fields || this.getFormFields())
						.filter('.' + this.options.errorClass).length === 0;

				if (e && success && this.options.successSendClass) {
					e.preventDefault();

					var formData = this.$form.serialize();

					if (pathInfo.single == 'apartments') {
						formData = {
							'action': 'ajax_apartments_request',
							'security': pathInfo.ajax_nonce,
							'data': this.$form.serialize()
						};
					}

					$.ajax({
						url: this.$form.removeClass(this.options.successSendClass).attr('action') || '/',
						type: this.$form.attr('method') || 'POST',
						data: formData,
						success: function (response) {
							//console.log(response);
							self.$form.addClass(self.options.successSendClass);
						}
					});
				}

				this.setFormState(success);

				return success;
			},

			changeHandler: function (e) {
				var $field = $(e.target);

				if ($field.data('interactive')) {
					this.setState(new Validator($field, this.getFormFields()).checkField());
				}

				this.checkSuccess();
			},

			focusHandler: function (e) {
				var $field = $(e.target);

				this.getClassTarget($field)
					.removeClass(this.options.errorClass + ' ' + this.options.successClass);

				this.checkSuccess();
			},

			setState: function (result) {
				this.getClassTarget(result.$fields)
					.toggleClass(this.options.errorClass, result.state !== null && !result.state)
					.toggleClass(this.options.successClass, result.state !== null && this.options.successClass && !!result.state);
			},

			setFormState: function (state) {
				if (this.options.errorFormClass) {
					this.$form.toggleClass(this.options.errorFormClass, !state);
				}
			},

			getClassTarget: function ($input) {
				return (this.options.addClassToParent ? $input.closest(this.options.addClassToParent) : $input);
			},

			getFormFields: function () {
				return this.$form.find(this.fieldsSelector);
			}
		};

		return publicClass;
	}());

	$.fn.formValidation = function (options) {
		options = $.extend({}, {
			errorClass: 'input-error',
			successClass: '',
			errorFormClass: '',
			addClassToParent: '',
			skipDefaultFields: ':button, :submit, :image, :hidden, :reset',
			skipFields: '',
			successSendClass: ''
		}, options);

		return this.each(function () {
			new FormValidation(this, options).init();
		});
	};
}(jQuery));

/*
 * Mobile hover plugin
 */
;(function ($) {

	// detect device type
	var isTouchDevice = ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch,
		isWinPhoneDevice = /Windows Phone/.test(navigator.userAgent);

	// define events
	var eventOn = (isTouchDevice && 'touchstart') || (isWinPhoneDevice && navigator.pointerEnabled && 'pointerdown') || (isWinPhoneDevice && navigator.msPointerEnabled && 'MSPointerDown') || 'mouseenter',
		eventOff = (isTouchDevice && 'touchend') || (isWinPhoneDevice && navigator.pointerEnabled && 'pointerup') || (isWinPhoneDevice && navigator.msPointerEnabled && 'MSPointerUp') || 'mouseleave';

	// event handlers
	var toggleOn, toggleOff, preventHandler;
	if (isTouchDevice || isWinPhoneDevice) {
		// prevent click handler
		preventHandler = function (e) {
			e.preventDefault();
		};

		// touch device handlers
		toggleOn = function (e) {
			var options = e.data, element = $(this);

			var toggleOff = function (e) {
				var target = $(e.target);
				if (!target.is(element) && !target.closest(element).length) {
					element.removeClass(options.hoverClass);
					element.off('click', preventHandler);
					if (options.onLeave) options.onLeave(element);
					$(document).off(eventOn, toggleOff);
				}
			};

			if (!element.hasClass(options.hoverClass)) {
				element.addClass(options.hoverClass);
				element.one('click', preventHandler);
				$(document).on(eventOn, toggleOff);
				if (options.onHover) options.onHover(element);
			}
		};
	} else {
		// desktop browser handlers
		toggleOn = function (e) {
			var options = e.data, element = $(this);
			element.addClass(options.hoverClass);
			$(options.context).on(eventOff, options.selector, options, toggleOff);
			if (options.onHover) options.onHover(element);
		};
		toggleOff = function (e) {
			var options = e.data, element = $(this);
			element.removeClass(options.hoverClass);
			$(options.context).off(eventOff, options.selector, toggleOff);
			if (options.onLeave) options.onLeave(element);
		};
	}

	// jQuery plugin
	$.fn.touchHover = function (opt) {
		var options = $.extend({
			context: this.context,
			selector: this.selector,
			hoverClass: 'hover'
		}, opt);

		$(this.context).on(eventOn, this.selector, options, toggleOn);
		return this;
	};
}(jQuery));

/*!
 * JavaScript Custom Forms
 *
 * Copyright 2014-2015 PSD2HTML - http://psd2html.com/jcf
 * Released under the MIT license (LICENSE.txt)
 *
 * Version: 1.1.3
 */
;(function (root, factory) {
	'use strict';
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		module.exports = factory(require('jquery'));
	} else {
		root.jcf = factory(jQuery);
	}
}(this, function ($) {
	'use strict';

	// define version
	var version = '1.1.3';

	// private variables
	var customInstances = [];

	// default global options
	var commonOptions = {
		optionsKey: 'jcf',
		dataKey: 'jcf-instance',
		rtlClass: 'jcf-rtl',
		focusClass: 'jcf-focus',
		pressedClass: 'jcf-pressed',
		disabledClass: 'jcf-disabled',
		hiddenClass: 'jcf-hidden',
		resetAppearanceClass: 'jcf-reset-appearance',
		unselectableClass: 'jcf-unselectable'
	};

	// detect device type
	var isTouchDevice = ('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch,
		isWinPhoneDevice = /Windows Phone/.test(navigator.userAgent);
	commonOptions.isMobileDevice = !!(isTouchDevice || isWinPhoneDevice);

	// create global stylesheet if custom forms are used
	var createStyleSheet = function () {
		var styleTag = $('<style>').appendTo('head'),
			styleSheet = styleTag.prop('sheet') || styleTag.prop('styleSheet');

		// crossbrowser style handling
		var addCSSRule = function (selector, rules, index) {
			if (styleSheet.insertRule) {
				styleSheet.insertRule(selector + '{' + rules + '}', index);
			} else {
				styleSheet.addRule(selector, rules, index);
			}
		};

		// add special rules
		addCSSRule('.' + commonOptions.hiddenClass, 'position:absolute !important;left:-9999px !important;height:1px !important;width:1px !important;margin:0 !important;border-width:0 !important;-webkit-appearance:none;-moz-appearance:none;appearance:none');
		addCSSRule('.' + commonOptions.rtlClass + ' .' + commonOptions.hiddenClass, 'right:-9999px !important; left: auto !important');
		addCSSRule('.' + commonOptions.unselectableClass, '-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; -webkit-tap-highlight-color: rgba(0,0,0,0);');
		addCSSRule('.' + commonOptions.resetAppearanceClass, 'background: none; border: none; -webkit-appearance: none; appearance: none; opacity: 0; filter: alpha(opacity=0);');

		// detect rtl pages
		var html = $('html'), body = jQuery('body');
		if (html.css('direction') === 'rtl' || body.css('direction') === 'rtl') {
			html.addClass(commonOptions.rtlClass);
		}

		// handle form reset event
		html.on('reset', function () {
			setTimeout(function () {
				api.refreshAll();
			}, 0);
		});

		// mark stylesheet as created
		commonOptions.styleSheetCreated = true;
	};

	// simplified pointer events handler
	(function () {
		var pointerEventsSupported = navigator.pointerEnabled || navigator.msPointerEnabled,
			touchEventsSupported = ('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch,
			eventList, eventMap = {}, eventPrefix = 'jcf-';

		// detect events to attach
		if (pointerEventsSupported) {
			eventList = {
				pointerover: navigator.pointerEnabled ? 'pointerover' : 'MSPointerOver',
				pointerdown: navigator.pointerEnabled ? 'pointerdown' : 'MSPointerDown',
				pointermove: navigator.pointerEnabled ? 'pointermove' : 'MSPointerMove',
				pointerup: navigator.pointerEnabled ? 'pointerup' : 'MSPointerUp'
			};
		} else {
			eventList = {
				pointerover: 'mouseover',
				pointerdown: 'mousedown' + (touchEventsSupported ? ' touchstart' : ''),
				pointermove: 'mousemove' + (touchEventsSupported ? ' touchmove' : ''),
				pointerup: 'mouseup' + (touchEventsSupported ? ' touchend' : '')
			};
		}

		// create event map
		$.each(eventList, function (targetEventName, fakeEventList) {
			$.each(fakeEventList.split(' '), function (index, fakeEventName) {
				eventMap[fakeEventName] = targetEventName;
			});
		});

		// jQuery event hooks
		$.each(eventList, function (eventName, eventHandlers) {
			eventHandlers = eventHandlers.split(' ');
			$.event.special[eventPrefix + eventName] = {
				setup: function () {
					var self = this;
					$.each(eventHandlers, function (index, fallbackEvent) {
						if (self.addEventListener) self.addEventListener(fallbackEvent, fixEvent, false);
						else self['on' + fallbackEvent] = fixEvent;
					});
				},
				teardown: function () {
					var self = this;
					$.each(eventHandlers, function (index, fallbackEvent) {
						if (self.addEventListener) self.removeEventListener(fallbackEvent, fixEvent, false);
						else self['on' + fallbackEvent] = null;
					});
				}
			};
		});

		// check that mouse event are not simulated by mobile browsers
		var lastTouch = null;
		var mouseEventSimulated = function (e) {
			var dx = Math.abs(e.pageX - lastTouch.x),
				dy = Math.abs(e.pageY - lastTouch.y),
				rangeDistance = 25;

			if (dx <= rangeDistance && dy <= rangeDistance) {
				return true;
			}
		};

		// normalize event
		var fixEvent = function (e) {
			var origEvent = e || window.event,
				touchEventData = null,
				targetEventName = eventMap[origEvent.type];

			e = $.event.fix(origEvent);
			e.type = eventPrefix + targetEventName;

			if (origEvent.pointerType) {
				switch (origEvent.pointerType) {
					case 2:
						e.pointerType = 'touch';
						break;
					case 3:
						e.pointerType = 'pen';
						break;
					case 4:
						e.pointerType = 'mouse';
						break;
					default:
						e.pointerType = origEvent.pointerType;
				}
			} else {
				e.pointerType = origEvent.type.substr(0, 5); // "mouse" or "touch" word length
			}

			if (!e.pageX && !e.pageY) {
				touchEventData = origEvent.changedTouches ? origEvent.changedTouches[0] : origEvent;
				e.pageX = touchEventData.pageX;
				e.pageY = touchEventData.pageY;
			}

			if (origEvent.type === 'touchend') {
				lastTouch = {x: e.pageX, y: e.pageY};
			}
			if (e.pointerType === 'mouse' && lastTouch && mouseEventSimulated(e)) {
				return;
			} else {
				return ($.event.dispatch || $.event.handle).call(this, e);
			}
		};
	}());

	// custom mousewheel/trackpad handler
	(function () {
		var wheelEvents = ('onwheel' in document || document.documentMode >= 9 ? 'wheel' : 'mousewheel DOMMouseScroll').split(' '),
			shimEventName = 'jcf-mousewheel';

		$.event.special[shimEventName] = {
			setup: function () {
				var self = this;
				$.each(wheelEvents, function (index, fallbackEvent) {
					if (self.addEventListener) self.addEventListener(fallbackEvent, fixEvent, false);
					else self['on' + fallbackEvent] = fixEvent;
				});
			},
			teardown: function () {
				var self = this;
				$.each(wheelEvents, function (index, fallbackEvent) {
					if (self.addEventListener) self.removeEventListener(fallbackEvent, fixEvent, false);
					else self['on' + fallbackEvent] = null;
				});
			}
		};

		var fixEvent = function (e) {
			var origEvent = e || window.event;
			e = $.event.fix(origEvent);
			e.type = shimEventName;

			// old wheel events handler
			if ('detail' in origEvent) {
				e.deltaY = -origEvent.detail;
			}
			if ('wheelDelta' in origEvent) {
				e.deltaY = -origEvent.wheelDelta;
			}
			if ('wheelDeltaY' in origEvent) {
				e.deltaY = -origEvent.wheelDeltaY;
			}
			if ('wheelDeltaX' in origEvent) {
				e.deltaX = -origEvent.wheelDeltaX;
			}

			// modern wheel event handler
			if ('deltaY' in origEvent) {
				e.deltaY = origEvent.deltaY;
			}
			if ('deltaX' in origEvent) {
				e.deltaX = origEvent.deltaX;
			}

			// handle deltaMode for mouse wheel
			e.delta = e.deltaY || e.deltaX;
			if (origEvent.deltaMode === 1) {
				var lineHeight = 16;
				e.delta *= lineHeight;
				e.deltaY *= lineHeight;
				e.deltaX *= lineHeight;
			}

			return ($.event.dispatch || $.event.handle).call(this, e);
		};
	}());

	// extra module methods
	var moduleMixin = {
		// provide function for firing native events
		fireNativeEvent: function (elements, eventName) {
			$(elements).each(function () {
				var element = this, eventObject;
				if (element.dispatchEvent) {
					eventObject = document.createEvent('HTMLEvents');
					eventObject.initEvent(eventName, true, true);
					element.dispatchEvent(eventObject);
				} else if (document.createEventObject) {
					eventObject = document.createEventObject();
					eventObject.target = element;
					element.fireEvent('on' + eventName, eventObject);
				}
			});
		},
		// bind event handlers for module instance (functions beggining with "on")
		bindHandlers: function () {
			var self = this;
			$.each(self, function (propName, propValue) {
				if (propName.indexOf('on') === 0 && $.isFunction(propValue)) {
					// dont use $.proxy here because it doesn't create unique handler
					self[propName] = function () {
						return propValue.apply(self, arguments);
					};
				}
			});
		}
	};

	// public API
	var api = {
		version: version,
		modules: {},
		getOptions: function () {
			return $.extend({}, commonOptions);
		},
		setOptions: function (moduleName, moduleOptions) {
			if (arguments.length > 1) {
				// set module options
				if (this.modules[moduleName]) {
					$.extend(this.modules[moduleName].prototype.options, moduleOptions);
				}
			} else {
				// set common options
				$.extend(commonOptions, moduleName);
			}
		},
		addModule: function (proto) {
			// add module to list
			var Module = function (options) {
				// save instance to collection
				if (!options.element.data(commonOptions.dataKey)) {
					options.element.data(commonOptions.dataKey, this);
				}
				customInstances.push(this);

				// save options
				this.options = $.extend({}, commonOptions, this.options, getInlineOptions(options.element), options);

				// bind event handlers to instance
				this.bindHandlers();

				// call constructor
				this.init.apply(this, arguments);
			};

			// parse options from HTML attribute
			var getInlineOptions = function (element) {
				var dataOptions = element.data(commonOptions.optionsKey),
					attrOptions = element.attr(commonOptions.optionsKey);

				if (dataOptions) {
					return dataOptions;
				} else if (attrOptions) {
					try {
						return $.parseJSON(attrOptions);
					} catch (e) {
						// ignore invalid attributes
					}
				}
			};

			// set proto as prototype for new module
			Module.prototype = proto;

			// add mixin methods to module proto
			$.extend(proto, moduleMixin);
			if (proto.plugins) {
				$.each(proto.plugins, function (pluginName, plugin) {
					$.extend(plugin.prototype, moduleMixin);
				});
			}

			// override destroy method
			var originalDestroy = Module.prototype.destroy;
			Module.prototype.destroy = function () {
				this.options.element.removeData(this.options.dataKey);

				for (var i = customInstances.length - 1; i >= 0; i--) {
					if (customInstances[i] === this) {
						customInstances.splice(i, 1);
						break;
					}
				}

				if (originalDestroy) {
					originalDestroy.apply(this, arguments);
				}
			};

			// save module to list
			this.modules[proto.name] = Module;
		},
		getInstance: function (element) {
			return $(element).data(commonOptions.dataKey);
		},
		replace: function (elements, moduleName, customOptions) {
			var self = this,
				instance;

			if (!commonOptions.styleSheetCreated) {
				createStyleSheet();
			}

			$(elements).each(function () {
				var moduleOptions,
					element = $(this);

				instance = element.data(commonOptions.dataKey);
				if (instance) {
					instance.refresh();
				} else {
					if (!moduleName) {
						$.each(self.modules, function (currentModuleName, module) {
							if (module.prototype.matchElement.call(module.prototype, element)) {
								moduleName = currentModuleName;
								return false;
							}
						});
					}
					if (moduleName) {
						moduleOptions = $.extend({element: element}, customOptions);
						instance = new self.modules[moduleName](moduleOptions);
					}
				}
			});
			return instance;
		},
		refresh: function (elements) {
			$(elements).each(function () {
				var instance = $(this).data(commonOptions.dataKey);
				if (instance) {
					instance.refresh();
				}
			});
		},
		destroy: function (elements) {
			$(elements).each(function () {
				var instance = $(this).data(commonOptions.dataKey);
				if (instance) {
					instance.destroy();
				}
			});
		},
		replaceAll: function (context) {
			var self = this;
			$.each(this.modules, function (moduleName, module) {
				$(module.prototype.selector, context).each(function () {
					if (this.className.indexOf('jcf-ignore') < 0) {
						self.replace(this, moduleName);
					}
				});
			});
		},
		refreshAll: function (context) {
			if (context) {
				$.each(this.modules, function (moduleName, module) {
					$(module.prototype.selector, context).each(function () {
						var instance = $(this).data(commonOptions.dataKey);
						if (instance) {
							instance.refresh();
						}
					});
				});
			} else {
				for (var i = customInstances.length - 1; i >= 0; i--) {
					customInstances[i].refresh();
				}
			}
		},
		destroyAll: function (context) {
			if (context) {
				$.each(this.modules, function (moduleName, module) {
					$(module.prototype.selector, context).each(function (index, element) {
						var instance = $(element).data(commonOptions.dataKey);
						if (instance) {
							instance.destroy();
						}
					});
				});
			} else {
				while (customInstances.length) {
					customInstances[0].destroy();
				}
			}
		}
	};

	// always export API to the global window object
	window.jcf = api;

	return api;
}));

/*!
 * JavaScript Custom Forms : Select Module
 *
 * Copyright 2014-2015 PSD2HTML - http://psd2html.com/jcf
 * Released under the MIT license (LICENSE.txt)
 *
 * Version: 1.1.3
 */
;(function ($, window) {
	'use strict';

	jcf.addModule({
		name: 'Select',
		selector: 'select',
		options: {
			element: null,
			multipleCompactStyle: false
		},
		plugins: {
			ListBox: ListBox,
			ComboBox: ComboBox,
			SelectList: SelectList
		},
		matchElement: function (element) {
			return element.is('select');
		},
		init: function () {
			this.element = $(this.options.element);
			this.createInstance();
		},
		isListBox: function () {
			return this.element.is('[size]:not([jcf-size]), [multiple]');
		},
		createInstance: function () {
			if (this.instance) {
				this.instance.destroy();
			}
			if (this.isListBox() && !this.options.multipleCompactStyle) {
				this.instance = new ListBox(this.options);
			} else {
				this.instance = new ComboBox(this.options);
			}
		},
		refresh: function () {
			var typeMismatch = (this.isListBox() && this.instance instanceof ComboBox) ||
				(!this.isListBox() && this.instance instanceof ListBox);

			if (typeMismatch) {
				this.createInstance();
			} else {
				this.instance.refresh();
			}
		},
		destroy: function () {
			this.instance.destroy();
		}
	});

	// combobox module
	function ComboBox(options) {
		this.options = $.extend({
			wrapNative: true,
			wrapNativeOnMobile: true,
			fakeDropInBody: true,
			useCustomScroll: true,
			flipDropToFit: true,
			maxVisibleItems: 10,
			fakeAreaStructure: '<span class="jcf-select"><span class="jcf-select-text"></span><span class="jcf-select-opener"></span></span>',
			fakeDropStructure: '<div class="jcf-select-drop"><div class="jcf-select-drop-content"></div></div>',
			optionClassPrefix: 'jcf-option-',
			selectClassPrefix: 'jcf-select-',
			dropContentSelector: '.jcf-select-drop-content',
			selectTextSelector: '.jcf-select-text',
			dropActiveClass: 'jcf-drop-active',
			flipDropClass: 'jcf-drop-flipped'
		}, options);
		this.init();
	}

	$.extend(ComboBox.prototype, {
		init: function () {
			this.initStructure();
			this.bindHandlers();
			this.attachEvents();
			this.refresh();
		},
		initStructure: function () {
			// prepare structure
			this.win = $(window);
			this.doc = $(document);
			this.realElement = $(this.options.element);
			this.fakeElement = $(this.options.fakeAreaStructure).insertAfter(this.realElement);
			this.selectTextContainer = this.fakeElement.find(this.options.selectTextSelector);
			this.selectText = $('<span></span>').appendTo(this.selectTextContainer);
			makeUnselectable(this.fakeElement);

			// copy classes from original select
			this.fakeElement.addClass(getPrefixedClasses(this.realElement.prop('className'), this.options.selectClassPrefix));

			// handle compact multiple style
			if (this.realElement.prop('multiple')) {
				this.fakeElement.addClass('jcf-compact-multiple');
			}

			// detect device type and dropdown behavior
			if (this.options.isMobileDevice && this.options.wrapNativeOnMobile && !this.options.wrapNative) {
				this.options.wrapNative = true;
			}

			if (this.options.wrapNative) {
				// wrap native select inside fake block
				this.realElement.prependTo(this.fakeElement).css({
					position: 'absolute',
					height: '100%',
					width: '100%'
				}).addClass(this.options.resetAppearanceClass);
			} else {
				// just hide native select
				this.realElement.addClass(this.options.hiddenClass);
				this.fakeElement.attr('title', this.realElement.attr('title'));
				this.fakeDropTarget = this.options.fakeDropInBody ? jQuery('body') : this.fakeElement;
			}
		},
		attachEvents: function () {
			// delayed refresh handler
			var self = this;
			this.delayedRefresh = function () {
				setTimeout(function () {
					self.refresh();
					if (self.list) {
						self.list.refresh();
						self.list.scrollToActiveOption();
					}
				}, 1);
			};

			// native dropdown event handlers
			if (this.options.wrapNative) {
				this.realElement.on({
					focus: this.onFocus,
					change: this.onChange,
					click: this.onChange,
					keydown: this.onChange
				});
			} else {
				// custom dropdown event handlers
				this.realElement.on({
					focus: this.onFocus,
					change: this.onChange,
					keydown: this.onKeyDown
				});
				this.fakeElement.on({
					'jcf-pointerdown': this.onSelectAreaPress
				});
			}
		},
		onKeyDown: function (e) {
			if (e.which === 13) {
				this.toggleDropdown();
			} else if (this.dropActive) {
				this.delayedRefresh();
			}
		},
		onChange: function () {
			this.refresh();
		},
		onFocus: function () {
			if (!this.pressedFlag || !this.focusedFlag) {
				this.fakeElement.addClass(this.options.focusClass);
				this.realElement.on('blur', this.onBlur);
				this.toggleListMode(true);
				this.focusedFlag = true;
			}
		},
		onBlur: function () {
			if (!this.pressedFlag) {
				this.fakeElement.removeClass(this.options.focusClass);
				this.realElement.off('blur', this.onBlur);
				this.toggleListMode(false);
				this.focusedFlag = false;
			}
		},
		onResize: function () {
			if (this.dropActive) {
				this.hideDropdown();
			}
		},
		onSelectDropPress: function () {
			this.pressedFlag = true;
		},
		onSelectDropRelease: function (e, pointerEvent) {
			this.pressedFlag = false;
			if (pointerEvent.pointerType === 'mouse') {
				this.realElement.focus();
			}
		},
		onSelectAreaPress: function (e) {
			// skip click if drop inside fake element or real select is disabled
			var dropClickedInsideFakeElement = !this.options.fakeDropInBody && $(e.target).closest(this.dropdown).length;
			if (dropClickedInsideFakeElement || e.button > 1 || this.realElement.is(':disabled')) {
				return;
			}

			// toggle dropdown visibility
			this.selectOpenedByEvent = e.pointerType;
			this.toggleDropdown();

			// misc handlers
			if (!this.focusedFlag) {
				if (e.pointerType === 'mouse') {
					this.realElement.focus();
				} else {
					this.onFocus(e);
				}
			}
			this.pressedFlag = true;
			this.fakeElement.addClass(this.options.pressedClass);
			this.doc.on('jcf-pointerup', this.onSelectAreaRelease);
		},
		onSelectAreaRelease: function (e) {
			if (this.focusedFlag && e.pointerType === 'mouse') {
				this.realElement.focus();
			}
			this.pressedFlag = false;
			this.fakeElement.removeClass(this.options.pressedClass);
			this.doc.off('jcf-pointerup', this.onSelectAreaRelease);
		},
		onOutsideClick: function (e) {
			var target = $(e.target),
				clickedInsideSelect = target.closest(this.fakeElement).length || target.closest(this.dropdown).length;

			if (!clickedInsideSelect) {
				this.hideDropdown();
			}
		},
		onSelect: function () {
			this.refresh();

			if (this.realElement.prop('multiple')) {
				this.repositionDropdown();
			} else {
				this.hideDropdown();
			}

			this.fireNativeEvent(this.realElement, 'change');
		},
		toggleListMode: function (state) {
			if (!this.options.wrapNative) {
				if (state) {
					// temporary change select to list to avoid appearing of native dropdown
					this.realElement.attr({
						size: 4,
						'jcf-size': ''
					});
				} else {
					// restore select from list mode to dropdown select
					if (!this.options.wrapNative) {
						this.realElement.removeAttr('size jcf-size');
					}
				}
			}
		},
		createDropdown: function () {
			// destroy previous dropdown if needed
			if (this.dropdown) {
				this.list.destroy();
				this.dropdown.remove();
			}

			// create new drop container
			this.dropdown = $(this.options.fakeDropStructure).appendTo(this.fakeDropTarget);
			this.dropdown.addClass(getPrefixedClasses(this.realElement.prop('className'), this.options.selectClassPrefix));
			makeUnselectable(this.dropdown);

			// handle compact multiple style
			if (this.realElement.prop('multiple')) {
				this.dropdown.addClass('jcf-compact-multiple');
			}

			// set initial styles for dropdown in body
			if (this.options.fakeDropInBody) {
				this.dropdown.css({
					position: 'absolute',
					top: -9999
				});
			}

			// create new select list instance
			this.list = new SelectList({
				useHoverClass: true,
				handleResize: false,
				alwaysPreventMouseWheel: true,
				maxVisibleItems: this.options.maxVisibleItems,
				useCustomScroll: this.options.useCustomScroll,
				holder: this.dropdown.find(this.options.dropContentSelector),
				multipleSelectWithoutKey: this.realElement.prop('multiple'),
				element: this.realElement
			});
			$(this.list).on({
				select: this.onSelect,
				press: this.onSelectDropPress,
				release: this.onSelectDropRelease
			});
		},
		repositionDropdown: function () {
			var selectOffset = this.fakeElement.offset(),
				selectWidth = this.fakeElement.outerWidth(),
				selectHeight = this.fakeElement.outerHeight(),
				dropHeight = this.dropdown.css('width', selectWidth).outerHeight(),
				winScrollTop = this.win.scrollTop(),
				winHeight = this.win.height(),
				calcTop, calcLeft, bodyOffset, needFlipDrop = false;

			// check flip drop position
			if (selectOffset.top + selectHeight + dropHeight > winScrollTop + winHeight && selectOffset.top - dropHeight > winScrollTop) {
				needFlipDrop = true;
			}

			if (this.options.fakeDropInBody) {
				bodyOffset = this.fakeDropTarget.css('position') !== 'static' ? this.fakeDropTarget.offset().top : 0;
				if (this.options.flipDropToFit && needFlipDrop) {
					// calculate flipped dropdown position
					calcLeft = selectOffset.left;
					calcTop = selectOffset.top - dropHeight - bodyOffset;
				} else {
					// calculate default drop position
					calcLeft = selectOffset.left;
					calcTop = selectOffset.top + selectHeight - bodyOffset;
				}

				// update drop styles
				this.dropdown.css({
					width: selectWidth,
					left: calcLeft,
					top: calcTop
				});
			}

			// refresh flipped class
			this.dropdown.add(this.fakeElement).toggleClass(this.options.flipDropClass, this.options.flipDropToFit && needFlipDrop);
		},
		showDropdown: function () {
			// do not show empty custom dropdown
			if (!this.realElement.prop('options').length) {
				return;
			}

			// create options list if not created
			if (!this.dropdown) {
				this.createDropdown();
			}

			// show dropdown
			this.dropActive = true;
			this.dropdown.appendTo(this.fakeDropTarget);
			this.fakeElement.addClass(this.options.dropActiveClass);
			this.refreshSelectedText();
			this.repositionDropdown();
			this.list.setScrollTop(this.savedScrollTop);
			this.list.refresh();

			// add temporary event handlers
			this.win.on('resize', this.onResize);
			this.doc.on('jcf-pointerdown', this.onOutsideClick);
		},
		hideDropdown: function () {
			if (this.dropdown) {
				this.savedScrollTop = this.list.getScrollTop();
				this.fakeElement.removeClass(this.options.dropActiveClass + ' ' + this.options.flipDropClass);
				this.dropdown.removeClass(this.options.flipDropClass).detach();
				this.doc.off('jcf-pointerdown', this.onOutsideClick);
				this.win.off('resize', this.onResize);
				this.dropActive = false;
				if (this.selectOpenedByEvent === 'touch') {
					this.onBlur();
				}
			}
		},
		toggleDropdown: function () {
			if (this.dropActive) {
				this.hideDropdown();
			} else {
				this.showDropdown();
			}
		},
		refreshSelectedText: function () {
			// redraw selected area
			var selectedIndex = this.realElement.prop('selectedIndex'),
				selectedOption = this.realElement.prop('options')[selectedIndex],
				selectedOptionImage = selectedOption ? selectedOption.getAttribute('data-image') : null,
				selectedOptionText = '',
				selectedOptionClasses,
				self = this;

			if (this.realElement.prop('multiple')) {
				$.each(this.realElement.prop('options'), function (index, option) {
					if (option.selected) {
						selectedOptionText += (selectedOptionText ? ', ' : '') + option.innerHTML;
					}
				});
				if (!selectedOptionText) {
					selectedOptionText = self.realElement.attr('placeholder') || '';
				}
				this.selectText.removeAttr('class').html(selectedOptionText);
			} else if (!selectedOption) {
				if (this.selectImage) {
					this.selectImage.hide();
				}
				this.selectText.removeAttr('class').empty();
			} else if (this.currentSelectedText !== selectedOption.innerHTML || this.currentSelectedImage !== selectedOptionImage) {
				selectedOptionClasses = getPrefixedClasses(selectedOption.className, this.options.optionClassPrefix);
				this.selectText.attr('class', selectedOptionClasses).html(selectedOption.innerHTML);

				if (selectedOptionImage) {
					if (!this.selectImage) {
						this.selectImage = $('<img>').prependTo(this.selectTextContainer).hide();
					}
					this.selectImage.attr('src', selectedOptionImage).show();
				} else if (this.selectImage) {
					this.selectImage.hide();
				}

				this.currentSelectedText = selectedOption.innerHTML;
				this.currentSelectedImage = selectedOptionImage;
			}
		},
		refresh: function () {
			// refresh fake select visibility
			if (this.realElement.prop('style').display === 'none') {
				this.fakeElement.hide();
			} else {
				this.fakeElement.show();
			}

			// refresh selected text
			this.refreshSelectedText();

			// handle disabled state
			this.fakeElement.toggleClass(this.options.disabledClass, this.realElement.is(':disabled'));
		},
		destroy: function () {
			// restore structure
			if (this.options.wrapNative) {
				this.realElement.insertBefore(this.fakeElement).css({
					position: '',
					height: '',
					width: ''
				}).removeClass(this.options.resetAppearanceClass);
			} else {
				this.realElement.removeClass(this.options.hiddenClass);
				if (this.realElement.is('[jcf-size]')) {
					this.realElement.removeAttr('size jcf-size');
				}
			}

			// removing element will also remove its event handlers
			this.fakeElement.remove();

			// remove other event handlers
			this.doc.off('jcf-pointerup', this.onSelectAreaRelease);
			this.realElement.off({
				focus: this.onFocus
			});
		}
	});

	// listbox module
	function ListBox(options) {
		this.options = $.extend({
			wrapNative: true,
			useCustomScroll: true,
			fakeStructure: '<span class="jcf-list-box"><span class="jcf-list-wrapper"></span></span>',
			selectClassPrefix: 'jcf-select-',
			listHolder: '.jcf-list-wrapper'
		}, options);
		this.init();
	}

	$.extend(ListBox.prototype, {
		init: function () {
			this.bindHandlers();
			this.initStructure();
			this.attachEvents();
		},
		initStructure: function () {
			this.realElement = $(this.options.element);
			this.fakeElement = $(this.options.fakeStructure).insertAfter(this.realElement);
			this.listHolder = this.fakeElement.find(this.options.listHolder);
			makeUnselectable(this.fakeElement);

			// copy classes from original select
			this.fakeElement.addClass(getPrefixedClasses(this.realElement.prop('className'), this.options.selectClassPrefix));
			this.realElement.addClass(this.options.hiddenClass);

			this.list = new SelectList({
				useCustomScroll: this.options.useCustomScroll,
				holder: this.listHolder,
				selectOnClick: false,
				element: this.realElement
			});
		},
		attachEvents: function () {
			// delayed refresh handler
			var self = this;
			this.delayedRefresh = function (e) {
				if (e && e.which === 16) {
					// ignore SHIFT key
					return;
				} else {
					clearTimeout(self.refreshTimer);
					self.refreshTimer = setTimeout(function () {
						self.refresh();
						self.list.scrollToActiveOption();
					}, 1);
				}
			};

			// other event handlers
			this.realElement.on({
				focus: this.onFocus,
				click: this.delayedRefresh,
				keydown: this.delayedRefresh
			});

			// select list event handlers
			$(this.list).on({
				select: this.onSelect,
				press: this.onFakeOptionsPress,
				release: this.onFakeOptionsRelease
			});
		},
		onFakeOptionsPress: function (e, pointerEvent) {
			this.pressedFlag = true;
			if (pointerEvent.pointerType === 'mouse') {
				this.realElement.focus();
			}
		},
		onFakeOptionsRelease: function (e, pointerEvent) {
			this.pressedFlag = false;
			if (pointerEvent.pointerType === 'mouse') {
				this.realElement.focus();
			}
		},
		onSelect: function () {
			this.fireNativeEvent(this.realElement, 'change');
			this.fireNativeEvent(this.realElement, 'click');
		},
		onFocus: function () {
			if (!this.pressedFlag || !this.focusedFlag) {
				this.fakeElement.addClass(this.options.focusClass);
				this.realElement.on('blur', this.onBlur);
				this.focusedFlag = true;
			}
		},
		onBlur: function () {
			if (!this.pressedFlag) {
				this.fakeElement.removeClass(this.options.focusClass);
				this.realElement.off('blur', this.onBlur);
				this.focusedFlag = false;
			}
		},
		refresh: function () {
			this.fakeElement.toggleClass(this.options.disabledClass, this.realElement.is(':disabled'));
			this.list.refresh();
		},
		destroy: function () {
			this.list.destroy();
			this.realElement.insertBefore(this.fakeElement).removeClass(this.options.hiddenClass);
			this.fakeElement.remove();
		}
	});

	// options list module
	function SelectList(options) {
		this.options = $.extend({
			holder: null,
			maxVisibleItems: 10,
			selectOnClick: true,
			useHoverClass: false,
			useCustomScroll: false,
			handleResize: true,
			multipleSelectWithoutKey: false,
			alwaysPreventMouseWheel: false,
			indexAttribute: 'data-index',
			cloneClassPrefix: 'jcf-option-',
			containerStructure: '<span class="jcf-list"><span class="jcf-list-content"></span></span>',
			containerSelector: '.jcf-list-content',
			captionClass: 'jcf-optgroup-caption',
			disabledClass: 'jcf-disabled',
			optionClass: 'jcf-option',
			groupClass: 'jcf-optgroup',
			hoverClass: 'jcf-hover',
			selectedClass: 'jcf-selected',
			scrollClass: 'jcf-scroll-active'
		}, options);
		this.init();
	}

	$.extend(SelectList.prototype, {
		init: function () {
			this.initStructure();
			this.refreshSelectedClass();
			this.attachEvents();
		},
		initStructure: function () {
			this.element = $(this.options.element);
			this.indexSelector = '[' + this.options.indexAttribute + ']';
			this.container = $(this.options.containerStructure).appendTo(this.options.holder);
			this.listHolder = this.container.find(this.options.containerSelector);
			this.lastClickedIndex = this.element.prop('selectedIndex');
			this.rebuildList();
		},
		attachEvents: function () {
			this.bindHandlers();
			this.listHolder.on('jcf-pointerdown', this.indexSelector, this.onItemPress);
			this.listHolder.on('jcf-pointerdown', this.onPress);

			if (this.options.useHoverClass) {
				this.listHolder.on('jcf-pointerover', this.indexSelector, this.onHoverItem);
			}
		},
		onPress: function (e) {
			$(this).trigger('press', e);
			this.listHolder.on('jcf-pointerup', this.onRelease);
		},
		onRelease: function (e) {
			$(this).trigger('release', e);
			this.listHolder.off('jcf-pointerup', this.onRelease);
		},
		onHoverItem: function (e) {
			var hoverIndex = parseFloat(e.currentTarget.getAttribute(this.options.indexAttribute));
			this.fakeOptions.removeClass(this.options.hoverClass).eq(hoverIndex).addClass(this.options.hoverClass);
		},
		onItemPress: function (e) {
			if (e.pointerType === 'touch' || this.options.selectOnClick) {
				// select option after "click"
				this.tmpListOffsetTop = this.list.offset().top;
				this.listHolder.on('jcf-pointerup', this.indexSelector, this.onItemRelease);
			} else {
				// select option immediately
				this.onSelectItem(e);
			}
		},
		onItemRelease: function (e) {
			// remove event handlers and temporary data
			this.listHolder.off('jcf-pointerup', this.indexSelector, this.onItemRelease);

			// simulate item selection
			if (this.tmpListOffsetTop === this.list.offset().top) {
				this.listHolder.on('click', this.indexSelector, {savedPointerType: e.pointerType}, this.onSelectItem);
			}
			delete this.tmpListOffsetTop;
		},
		onSelectItem: function (e) {
			var clickedIndex = parseFloat(e.currentTarget.getAttribute(this.options.indexAttribute)),
				pointerType = e.data && e.data.savedPointerType || e.pointerType || 'mouse',
				range;

			// remove click event handler
			this.listHolder.off('click', this.indexSelector, this.onSelectItem);

			// ignore clicks on disabled options
			if (e.button > 1 || this.realOptions[clickedIndex].disabled) {
				return;
			}

			if (this.element.prop('multiple')) {
				if (e.metaKey || e.ctrlKey || pointerType === 'touch' || this.options.multipleSelectWithoutKey) {
					// if CTRL/CMD pressed or touch devices - toggle selected option
					this.realOptions[clickedIndex].selected = !this.realOptions[clickedIndex].selected;
				} else if (e.shiftKey) {
					// if SHIFT pressed - update selection
					range = [this.lastClickedIndex, clickedIndex].sort(function (a, b) {
						return a - b;
					});
					this.realOptions.each(function (index, option) {
						option.selected = (index >= range[0] && index <= range[1]);
					});
				} else {
					// set single selected index
					this.element.prop('selectedIndex', clickedIndex);
				}
			} else {
				this.element.prop('selectedIndex', clickedIndex);
			}

			// save last clicked option
			if (!e.shiftKey) {
				this.lastClickedIndex = clickedIndex;
			}

			// refresh classes
			this.refreshSelectedClass();

			// scroll to active item in desktop browsers
			if (pointerType === 'mouse') {
				this.scrollToActiveOption();
			}

			// make callback when item selected
			$(this).trigger('select');
		},
		rebuildList: function () {
			// rebuild options
			var self = this,
				rootElement = this.element[0];

			// recursively create fake options
			this.storedSelectHTML = rootElement.innerHTML;
			this.optionIndex = 0;
			this.list = $(this.createOptionsList(rootElement));
			this.listHolder.empty().append(this.list);
			this.realOptions = this.element.find('option');
			this.fakeOptions = this.list.find(this.indexSelector);
			this.fakeListItems = this.list.find('.' + this.options.captionClass + ',' + this.indexSelector);
			delete this.optionIndex;

			// detect max visible items
			var maxCount = this.options.maxVisibleItems,
				sizeValue = this.element.prop('size');
			if (sizeValue > 1 && !this.element.is('[jcf-size]')) {
				maxCount = sizeValue;
			}

			// handle scrollbar
			var needScrollBar = this.fakeOptions.length > maxCount;
			this.container.toggleClass(this.options.scrollClass, needScrollBar);
			if (needScrollBar) {
				// change max-height
				this.listHolder.css({
					maxHeight: this.getOverflowHeight(maxCount),
					overflow: 'auto'
				});

				if (this.options.useCustomScroll && jcf.modules.Scrollable) {
					// add custom scrollbar if specified in options
					jcf.replace(this.listHolder, 'Scrollable', {
						handleResize: this.options.handleResize,
						alwaysPreventMouseWheel: this.options.alwaysPreventMouseWheel
					});
					return;
				}
			}

			// disable edge wheel scrolling
			if (this.options.alwaysPreventMouseWheel) {
				this.preventWheelHandler = function (e) {
					var currentScrollTop = self.listHolder.scrollTop(),
						maxScrollTop = self.listHolder.prop('scrollHeight') - self.listHolder.innerHeight();

					// check edge cases
					if ((currentScrollTop <= 0 && e.deltaY < 0) || (currentScrollTop >= maxScrollTop && e.deltaY > 0)) {
						e.preventDefault();
					}
				};
				this.listHolder.on('jcf-mousewheel', this.preventWheelHandler);
			}
		},
		refreshSelectedClass: function () {
			var self = this,
				selectedItem,
				isMultiple = this.element.prop('multiple'),
				selectedIndex = this.element.prop('selectedIndex');

			if (isMultiple) {
				this.realOptions.each(function (index, option) {
					self.fakeOptions.eq(index).toggleClass(self.options.selectedClass, !!option.selected);
				});
			} else {
				this.fakeOptions.removeClass(this.options.selectedClass + ' ' + this.options.hoverClass);
				selectedItem = this.fakeOptions.eq(selectedIndex).addClass(this.options.selectedClass);
				if (this.options.useHoverClass) {
					selectedItem.addClass(this.options.hoverClass);
				}
			}
		},
		scrollToActiveOption: function () {
			// scroll to target option
			var targetOffset = this.getActiveOptionOffset();
			if (typeof targetOffset === 'number') {
				this.listHolder.prop('scrollTop', targetOffset);
			}
		},
		getSelectedIndexRange: function () {
			var firstSelected = -1, lastSelected = -1;
			this.realOptions.each(function (index, option) {
				if (option.selected) {
					if (firstSelected < 0) {
						firstSelected = index;
					}
					lastSelected = index;
				}
			});
			return [firstSelected, lastSelected];
		},
		getChangedSelectedIndex: function () {
			var selectedIndex = this.element.prop('selectedIndex'),
				targetIndex;

			if (this.element.prop('multiple')) {
				// multiple selects handling
				if (!this.previousRange) {
					this.previousRange = [selectedIndex, selectedIndex];
				}
				this.currentRange = this.getSelectedIndexRange();
				targetIndex = this.currentRange[this.currentRange[0] !== this.previousRange[0] ? 0 : 1];
				this.previousRange = this.currentRange;
				return targetIndex;
			} else {
				// single choice selects handling
				return selectedIndex;
			}
		},
		getActiveOptionOffset: function () {
			// calc values
			var dropHeight = this.listHolder.height(),
				dropScrollTop = this.listHolder.prop('scrollTop'),
				currentIndex = this.getChangedSelectedIndex(),
				fakeOption = this.fakeOptions.eq(currentIndex),
				fakeOptionOffset = fakeOption.offset().top - this.list.offset().top,
				fakeOptionHeight = fakeOption.innerHeight();

			// scroll list
			if (fakeOptionOffset + fakeOptionHeight >= dropScrollTop + dropHeight) {
				// scroll down (always scroll to option)
				return fakeOptionOffset - dropHeight + fakeOptionHeight;
			} else if (fakeOptionOffset < dropScrollTop) {
				// scroll up to option
				return fakeOptionOffset;
			}
		},
		getOverflowHeight: function (sizeValue) {
			var item = this.fakeListItems.eq(sizeValue - 1),
				listOffset = this.list.offset().top,
				itemOffset = item.offset().top,
				itemHeight = item.innerHeight();

			return itemOffset + itemHeight - listOffset;
		},
		getScrollTop: function () {
			return this.listHolder.scrollTop();
		},
		setScrollTop: function (value) {
			this.listHolder.scrollTop(value);
		},
		createOption: function (option) {
			var newOption = document.createElement('span');
			newOption.className = this.options.optionClass;
			newOption.innerHTML = option.innerHTML;
			newOption.setAttribute(this.options.indexAttribute, this.optionIndex++);

			var optionImage, optionImageSrc = option.getAttribute('data-image');
			if (optionImageSrc) {
				optionImage = document.createElement('img');
				optionImage.src = optionImageSrc;
				newOption.insertBefore(optionImage, newOption.childNodes[0]);
			}
			if (option.disabled) {
				newOption.className += ' ' + this.options.disabledClass;
			}
			if (option.className) {
				newOption.className += ' ' + getPrefixedClasses(option.className, this.options.cloneClassPrefix);
			}
			return newOption;
		},
		createOptGroup: function (optgroup) {
			var optGroupContainer = document.createElement('span'),
				optGroupName = optgroup.getAttribute('label'),
				optGroupCaption, optGroupList;

			// create caption
			optGroupCaption = document.createElement('span');
			optGroupCaption.className = this.options.captionClass;
			optGroupCaption.innerHTML = optGroupName;
			optGroupContainer.appendChild(optGroupCaption);

			// create list of options
			if (optgroup.children.length) {
				optGroupList = this.createOptionsList(optgroup);
				optGroupContainer.appendChild(optGroupList);
			}

			optGroupContainer.className = this.options.groupClass;
			return optGroupContainer;
		},
		createOptionContainer: function () {
			var optionContainer = document.createElement('li');
			return optionContainer;
		},
		createOptionsList: function (container) {
			var self = this,
				list = document.createElement('ul');

			$.each(container.children, function (index, currentNode) {
				var item = self.createOptionContainer(currentNode),
					newNode;

				switch (currentNode.tagName.toLowerCase()) {
					case 'option':
						newNode = self.createOption(currentNode);
						break;
					case 'optgroup':
						newNode = self.createOptGroup(currentNode);
						break;
				}
				list.appendChild(item).appendChild(newNode);
			});
			return list;
		},
		refresh: function () {
			// check for select innerHTML changes
			if (this.storedSelectHTML !== this.element.prop('innerHTML')) {
				this.rebuildList();
			}

			// refresh custom scrollbar
			var scrollInstance = jcf.getInstance(this.listHolder);
			if (scrollInstance) {
				scrollInstance.refresh();
			}

			// refresh selectes classes
			this.refreshSelectedClass();
		},
		destroy: function () {
			this.listHolder.off('jcf-mousewheel', this.preventWheelHandler);
			this.listHolder.off('jcf-pointerdown', this.indexSelector, this.onSelectItem);
			this.listHolder.off('jcf-pointerover', this.indexSelector, this.onHoverItem);
			this.listHolder.off('jcf-pointerdown', this.onPress);
		}
	});

	// helper functions
	var getPrefixedClasses = function (className, prefixToAdd) {
		return className ? className.replace(/[\s]*([\S]+)+[\s]*/gi, prefixToAdd + '$1 ') : '';
	};
	var makeUnselectable = (function () {
		var unselectableClass = jcf.getOptions().unselectableClass;

		function preventHandler(e) {
			e.preventDefault();
		}

		return function (node) {
			node.addClass(unselectableClass).on('selectstart', preventHandler);
		};
	}());

}(jQuery, this));

/*!
 * JavaScript Custom Forms : Radio Module
 *
 * Copyright 2014-2015 PSD2HTML - http://psd2html.com/jcf
 * Released under the MIT license (LICENSE.txt)
 *
 * Version: 1.1.3
 */
;(function ($) {
	'use strict';

	jcf.addModule({
		name: 'Radio',
		selector: 'input[type="radio"]',
		options: {
			wrapNative: true,
			checkedClass: 'jcf-checked',
			uncheckedClass: 'jcf-unchecked',
			labelActiveClass: 'jcf-label-active',
			fakeStructure: '<span class="jcf-radio"><span></span></span>'
		},
		matchElement: function (element) {
			return element.is(':radio');
		},
		init: function () {
			this.initStructure();
			this.attachEvents();
			this.refresh();
		},
		initStructure: function () {
			// prepare structure
			this.doc = $(document);
			this.realElement = $(this.options.element);
			this.fakeElement = $(this.options.fakeStructure).insertAfter(this.realElement);
			this.labelElement = this.getLabelFor();

			if (this.options.wrapNative) {
				// wrap native radio inside fake block
				this.realElement.prependTo(this.fakeElement).css({
					position: 'absolute',
					opacity: 0
				});
			} else {
				// just hide native radio
				this.realElement.addClass(this.options.hiddenClass);
			}
		},
		attachEvents: function () {
			// add event handlers
			this.realElement.on({
				focus: this.onFocus,
				click: this.onRealClick
			});
			this.fakeElement.on('click', this.onFakeClick);
			this.fakeElement.on('jcf-pointerdown', this.onPress);
		},
		onRealClick: function (e) {
			// redraw current radio and its group (setTimeout handles click that might be prevented)
			var self = this;
			this.savedEventObject = e;
			setTimeout(function () {
				self.refreshRadioGroup();
			}, 0);
		},
		onFakeClick: function (e) {
			// skip event if clicked on real element inside wrapper
			if (this.options.wrapNative && this.realElement.is(e.target)) {
				return;
			}

			// toggle checked class
			if (!this.realElement.is(':disabled')) {
				delete this.savedEventObject;
				this.currentActiveRadio = this.getCurrentActiveRadio();
				this.stateChecked = this.realElement.prop('checked');
				this.realElement.prop('checked', true);
				this.fireNativeEvent(this.realElement, 'click');
				if (this.savedEventObject && this.savedEventObject.isDefaultPrevented()) {
					this.realElement.prop('checked', this.stateChecked);
					this.currentActiveRadio.prop('checked', true);
				} else {
					this.fireNativeEvent(this.realElement, 'change');
				}
				delete this.savedEventObject;
			}
		},
		onFocus: function () {
			if (!this.pressedFlag || !this.focusedFlag) {
				this.focusedFlag = true;
				this.fakeElement.addClass(this.options.focusClass);
				this.realElement.on('blur', this.onBlur);
			}
		},
		onBlur: function () {
			if (!this.pressedFlag) {
				this.focusedFlag = false;
				this.fakeElement.removeClass(this.options.focusClass);
				this.realElement.off('blur', this.onBlur);
			}
		},
		onPress: function (e) {
			if (!this.focusedFlag && e.pointerType === 'mouse') {
				this.realElement.focus();
			}
			this.pressedFlag = true;
			this.fakeElement.addClass(this.options.pressedClass);
			this.doc.on('jcf-pointerup', this.onRelease);
		},
		onRelease: function (e) {
			if (this.focusedFlag && e.pointerType === 'mouse') {
				this.realElement.focus();
			}
			this.pressedFlag = false;
			this.fakeElement.removeClass(this.options.pressedClass);
			this.doc.off('jcf-pointerup', this.onRelease);
		},
		getCurrentActiveRadio: function () {
			return this.getRadioGroup(this.realElement).filter(':checked');
		},
		getRadioGroup: function (radio) {
			// find radio group for specified radio button
			var name = radio.attr('name'),
				parentForm = radio.parents('form');

			if (name) {
				if (parentForm.length) {
					return parentForm.find('input[name="' + name + '"]');
				} else {
					return $('input[name="' + name + '"]:not(form input)');
				}
			} else {
				return radio;
			}
		},
		getLabelFor: function () {
			var parentLabel = this.realElement.closest('label'),
				elementId = this.realElement.prop('id');

			if (!parentLabel.length && elementId) {
				parentLabel = $('label[for="' + elementId + '"]');
			}
			return parentLabel.length ? parentLabel : null;
		},
		refreshRadioGroup: function () {
			// redraw current radio and its group
			this.getRadioGroup(this.realElement).each(function () {
				jcf.refresh(this);
			});
		},
		refresh: function () {
			// redraw current radio button
			var isChecked = this.realElement.is(':checked'),
				isDisabled = this.realElement.is(':disabled');

			this.fakeElement.toggleClass(this.options.checkedClass, isChecked)
				.toggleClass(this.options.uncheckedClass, !isChecked)
				.toggleClass(this.options.disabledClass, isDisabled);

			if (this.labelElement) {
				this.labelElement.toggleClass(this.options.labelActiveClass, isChecked);
			}
		},
		destroy: function () {
			// restore structure
			if (this.options.wrapNative) {
				this.realElement.insertBefore(this.fakeElement).css({
					position: '',
					width: '',
					height: '',
					opacity: '',
					margin: ''
				});
			} else {
				this.realElement.removeClass(this.options.hiddenClass);
			}

			// removing element will also remove its event handlers
			this.fakeElement.off('jcf-pointerdown', this.onPress);
			this.fakeElement.remove();

			// remove other event handlers
			this.doc.off('jcf-pointerup', this.onRelease);
			this.realElement.off({
				blur: this.onBlur,
				focus: this.onFocus,
				click: this.onRealClick
			});
		}
	});

}(jQuery));

/*!
 * JavaScript Custom Forms : Checkbox Module
 *
 * Copyright 2014-2015 PSD2HTML - http://psd2html.com/jcf
 * Released under the MIT license (LICENSE.txt)
 *
 * Version: 1.1.3
 */
;(function ($) {
	'use strict';

	jcf.addModule({
		name: 'Checkbox',
		selector: 'input[type="checkbox"]',
		options: {
			wrapNative: true,
			checkedClass: 'jcf-checked',
			uncheckedClass: 'jcf-unchecked',
			labelActiveClass: 'jcf-label-active',
			fakeStructure: '<span class="jcf-checkbox"><span></span></span>'
		},
		matchElement: function (element) {
			return element.is(':checkbox');
		},
		init: function () {
			this.initStructure();
			this.attachEvents();
			this.refresh();
		},
		initStructure: function () {
			// prepare structure
			this.doc = $(document);
			this.realElement = $(this.options.element);
			this.fakeElement = $(this.options.fakeStructure).insertAfter(this.realElement);
			this.labelElement = this.getLabelFor();

			if (this.options.wrapNative) {
				// wrap native checkbox inside fake block
				this.realElement.appendTo(this.fakeElement).css({
					position: 'absolute',
					height: '100%',
					width: '100%',
					opacity: 0,
					margin: 0
				});
			} else {
				// just hide native checkbox
				this.realElement.addClass(this.options.hiddenClass);
			}
		},
		attachEvents: function () {
			// add event handlers
			this.realElement.on({
				focus: this.onFocus,
				click: this.onRealClick
			});
			this.fakeElement.on('click', this.onFakeClick);
			this.fakeElement.on('jcf-pointerdown', this.onPress);
		},
		onRealClick: function (e) {
			// just redraw fake element (setTimeout handles click that might be prevented)
			var self = this;
			this.savedEventObject = e;
			setTimeout(function () {
				self.refresh();
			}, 0);
		},
		onFakeClick: function (e) {
			// skip event if clicked on real element inside wrapper
			if (this.options.wrapNative && this.realElement.is(e.target)) {
				return;
			}

			// toggle checked class
			if (!this.realElement.is(':disabled')) {
				delete this.savedEventObject;
				this.stateChecked = this.realElement.prop('checked');
				this.realElement.prop('checked', !this.stateChecked);
				this.fireNativeEvent(this.realElement, 'click');
				if (this.savedEventObject && this.savedEventObject.isDefaultPrevented()) {
					this.realElement.prop('checked', this.stateChecked);
				} else {
					this.fireNativeEvent(this.realElement, 'change');
				}
				delete this.savedEventObject;
			}
		},
		onFocus: function () {
			if (!this.pressedFlag || !this.focusedFlag) {
				this.focusedFlag = true;
				this.fakeElement.addClass(this.options.focusClass);
				this.realElement.on('blur', this.onBlur);
			}
		},
		onBlur: function () {
			if (!this.pressedFlag) {
				this.focusedFlag = false;
				this.fakeElement.removeClass(this.options.focusClass);
				this.realElement.off('blur', this.onBlur);
			}
		},
		onPress: function (e) {
			if (!this.focusedFlag && e.pointerType === 'mouse') {
				this.realElement.focus();
			}
			this.pressedFlag = true;
			this.fakeElement.addClass(this.options.pressedClass);
			this.doc.on('jcf-pointerup', this.onRelease);
		},
		onRelease: function (e) {
			if (this.focusedFlag && e.pointerType === 'mouse') {
				this.realElement.focus();
			}
			this.pressedFlag = false;
			this.fakeElement.removeClass(this.options.pressedClass);
			this.doc.off('jcf-pointerup', this.onRelease);
		},
		getLabelFor: function () {
			var parentLabel = this.realElement.closest('label'),
				elementId = this.realElement.prop('id');

			if (!parentLabel.length && elementId) {
				parentLabel = $('label[for="' + elementId + '"]');
			}
			return parentLabel.length ? parentLabel : null;
		},
		refresh: function () {
			// redraw custom checkbox
			var isChecked = this.realElement.is(':checked'),
				isDisabled = this.realElement.is(':disabled');

			this.fakeElement.toggleClass(this.options.checkedClass, isChecked)
				.toggleClass(this.options.uncheckedClass, !isChecked)
				.toggleClass(this.options.disabledClass, isDisabled);

			if (this.labelElement) {
				this.labelElement.toggleClass(this.options.labelActiveClass, isChecked);
			}
		},
		destroy: function () {
			// restore structure
			if (this.options.wrapNative) {
				this.realElement.insertBefore(this.fakeElement).css({
					position: '',
					width: '',
					height: '',
					opacity: '',
					margin: ''
				});
			} else {
				this.realElement.removeClass(this.options.hiddenClass);
			}

			// removing element will also remove its event handlers
			this.fakeElement.off('jcf-pointerdown', this.onPress);
			this.fakeElement.remove();

			// remove other event handlers
			this.doc.off('jcf-pointerup', this.onRelease);
			this.realElement.off({
				focus: this.onFocus,
				click: this.onRealClick
			});
		}
	});

}(jQuery));
/*!
 * JavaScript Custom Forms : File Module
 *
 * Copyright 2014-2015 PSD2HTML - http://psd2html.com/jcf
 * Released under the MIT license (LICENSE.txt)
 *
 * Version: 1.1.3
 */
;(function ($) {
	'use strict';

	jcf.addModule({
		name: 'File',
		selector: 'input[type="file"]',
		options: {
			fakeStructure: '<span class="jcf-file"><span class="jcf-fake-input"></span><span class="jcf-upload-button"><span class="jcf-button-content"></span></span></span>',
			buttonText: 'Choose file',
			placeholderText: 'No file chosen',
			realElementClass: 'jcf-real-element',
			extensionPrefixClass: 'jcf-extension-',
			selectedFileBlock: '.jcf-fake-input',
			buttonTextBlock: '.jcf-button-content'
		},
		matchElement: function (element) {
			return element.is('input[type="file"]');
		},
		init: function () {
			this.initStructure();
			this.attachEvents();
			this.refresh();
		},
		initStructure: function () {
			this.doc = $(document);
			this.realElement = $(this.options.element).addClass(this.options.realElementClass);
			this.fakeElement = $(this.options.fakeStructure).insertBefore(this.realElement);
			this.fileNameBlock = this.fakeElement.find(this.options.selectedFileBlock);
			this.buttonTextBlock = this.fakeElement.find(this.options.buttonTextBlock).text(this.options.buttonText);

			this.realElement.appendTo(this.fakeElement).css({
				position: 'absolute',
				opacity: 0
			});
		},
		attachEvents: function () {
			this.realElement.on({
				'jcf-pointerdown': this.onPress,
				change: this.onChange,
				focus: this.onFocus
			});
		},
		onChange: function () {
			this.refresh();
		},
		onFocus: function () {
			this.fakeElement.addClass(this.options.focusClass);
			this.realElement.on('blur', this.onBlur);
		},
		onBlur: function () {
			this.fakeElement.removeClass(this.options.focusClass);
			this.realElement.off('blur', this.onBlur);
		},
		onPress: function () {
			this.fakeElement.addClass(this.options.pressedClass);
			this.doc.on('jcf-pointerup', this.onRelease);
		},
		onRelease: function () {
			this.fakeElement.removeClass(this.options.pressedClass);
			this.doc.off('jcf-pointerup', this.onRelease);
		},
		getFileName: function () {
			var resultFileName = '',
				files = this.realElement.prop('files');

			if (files && files.length) {
				$.each(files, function (index, file) {
					resultFileName += (index > 0 ? ', ' : '') + file.name;
				});
			} else {
				resultFileName = this.realElement.val().replace(/^[\s\S]*(?:\\|\/)([\s\S^\\\/]*)$/g, '$1');
			}

			return resultFileName;
		},
		getFileExtension: function () {
			var fileName = this.realElement.val();
			return fileName.lastIndexOf('.') < 0 ? '' : fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
		},
		updateExtensionClass: function () {
			var currentExtension = this.getFileExtension(),
				currentClassList = this.fakeElement.prop('className'),
				cleanedClassList = currentClassList.replace(new RegExp('(\\s|^)' + this.options.extensionPrefixClass + '[^ ]+', 'gi'), '');

			this.fakeElement.prop('className', cleanedClassList);
			if (currentExtension) {
				this.fakeElement.addClass(this.options.extensionPrefixClass + currentExtension);
			}
		},
		refresh: function () {
			var selectedFileName = this.getFileName() || this.options.placeholderText;
			this.fakeElement.toggleClass(this.options.disabledClass, this.realElement.is(':disabled'));
			this.fileNameBlock.text(selectedFileName);
			this.updateExtensionClass();
		},
		destroy: function () {
			// reset styles and restore element position
			this.realElement.insertBefore(this.fakeElement).removeClass(this.options.realElementClass).css({
				position: '',
				opacity: ''
			});
			this.fakeElement.remove();

			// remove event handlers
			this.realElement.off({
				'jcf-pointerdown': this.onPress,
				change: this.onChange,
				focus: this.onFocus,
				blur: this.onBlur
			});
			this.doc.off('jcf-pointerup', this.onRelease);
		}
	});

}(jQuery));

/*
 * jQuery Tabs plugin
 */

;(function ($, $win) {
	'use strict';

	function Tabset($holder, options) {
		this.$holder = $holder;
		this.options = options;

		this.init();
	}

	Tabset.prototype = {
		init: function () {
			this.$tabLinks = this.$holder.find(this.options.tabLinks);

			this.setStartActiveIndex();
			this.setActiveTab();

			if (this.options.autoHeight) {
				this.$tabHolder = $(this.$tabLinks.eq(0).attr(this.options.attrib)).parent();
			}
		},

		setStartActiveIndex: function () {
			var $classTargets = this.getClassTarget(this.$tabLinks);
			var $activeLink = $classTargets.filter('.' + this.options.activeClass);
			var $hashLink = this.$tabLinks.filter('[' + this.options.attrib + '="' + location.hash + '"]').parent();
			var activeIndex;

			if (this.options.checkHash && $hashLink.length) {
				$activeLink = $hashLink;
			}

			activeIndex = $classTargets.index($activeLink);

			this.activeTabIndex = this.prevTabIndex = (activeIndex === -1 ? (this.options.defaultTab ? 0 : null) : activeIndex);
		},

		setActiveTab: function () {
			var self = this;

			this.$tabLinks.each(function (i, link) {
				var $link = $(link);
				var $classTarget = self.getClassTarget($link);
				var $tab = $($link.attr(self.options.attrib));

				if (i !== self.activeTabIndex) {
					$classTarget.removeClass(self.options.activeClass);
					$tab.addClass(self.options.tabHiddenClass).removeClass(self.options.activeClass);
				} else {
					$classTarget.addClass(self.options.activeClass);
					$tab.removeClass(self.options.tabHiddenClass).addClass(self.options.activeClass);
				}

				self.attachTabLink($link, i);
			});
		},

		attachTabLink: function ($link, i) {
			var self = this;

			$link.on(this.options.event + '.tabset', function (e) {
				e.preventDefault();

				if (self.activeTabIndex === self.prevTabIndex && self.activeTabIndex !== i) {
					self.activeTabIndex = i;
					self.switchTabs();
				}
			});
		},

		resizeHolder: function (height) {
			var self = this;

			if (height) {
				this.$tabHolder.height(height);
				setTimeout(function () {
					self.$tabHolder.addClass('transition');
				}, 10);
			} else {
				self.$tabHolder.removeClass('transition').height('');
			}
		},

		switchTabs: function () {
			var self = this;

			var $prevLink = this.$tabLinks.eq(this.prevTabIndex);
			var $nextLink = this.$tabLinks.eq(this.activeTabIndex);

			var $prevTab = this.getTab($prevLink);
			var $nextTab = this.getTab($nextLink);

			$prevTab.removeClass(this.options.activeClass);

			if (self.haveTabHolder()) {
				this.resizeHolder($prevTab.outerHeight());
			}

			setTimeout(function () {
				self.getClassTarget($prevLink).removeClass(self.options.activeClass);

				$prevTab.addClass(self.options.tabHiddenClass);
				$nextTab.removeClass(self.options.tabHiddenClass).addClass(self.options.activeClass);

				self.getClassTarget($nextLink).addClass(self.options.activeClass);

				if (self.haveTabHolder()) {
					self.resizeHolder($nextTab.outerHeight());

					setTimeout(function () {
						self.resizeHolder();
						self.prevTabIndex = self.activeTabIndex;
					}, self.options.animSpeed);
				} else {
					self.prevTabIndex = self.activeTabIndex;
				}
			}, this.options.autoHeight ? this.options.animSpeed : 1);

			if (this.options.checkHash) {
				location.hash = $nextLink.attr('href');
			}
		},

		getClassTarget: function ($link) {
			return this.options.addToParent ? $link.parent() : $link;
		},

		getActiveTab: function () {
			return this.getTab(this.$tabLinks.eq(this.activeTabIndex));
		},

		getTab: function ($link) {
			return $($link.attr(this.options.attrib));
		},

		haveTabHolder: function () {
			return this.$tabHolder && this.$tabHolder.length;
		},

		destroy: function () {
			var self = this;

			this.$tabLinks.off('.tabset').each(function () {
				var $link = $(this);

				self.getClassTarget($link).removeClass(self.options.activeClass);
				$($link.attr(self.options.attrib)).removeClass(self.options.activeClass + ' ' + self.options.tabHiddenClass);
			});

			this.$holder.removeData('Tabset');
		}
	};

	$.fn.tabset = function (options) {
		options = $.extend({
			activeClass: 'active',
			addToParent: false,
			autoHeight: false,
			checkHash: false,
			defaultTab: true,
			animSpeed: 500,
			tabLinks: 'a',
			attrib: 'href',
			event: 'click',
			tabHiddenClass: 'js-tab-hidden'
		}, options);
		options.autoHeight = options.autoHeight && $.support.opacity;

		return this.each(function () {
			var $holder = $(this);

			if (!$holder.data('Tabset')) {
				$holder.data('Tabset', new Tabset($holder, options));
			}
		});
	};
}(jQuery, jQuery(window)));

/*
 * jQuery Cycle Carousel plugin
 */
;(function ($) {
	function ScrollAbsoluteGallery(options) {
		this.options = $.extend({
			activeClass: 'active',
			mask: 'div.slides-mask',
			slider: '>ul',
			slides: '>li',
			btnPrev: '.btn-prev',
			btnNext: '.btn-next',
			pagerLinks: 'ul.pager > li',
			generatePagination: false,
			pagerList: '<ul>',
			pagerListItem: '<li><a href="#"></a></li>',
			pagerListItemText: 'a',
			galleryReadyClass: 'gallery-js-ready',
			currentNumber: 'span.current-num',
			totalNumber: 'span.total-num',
			maskAutoSize: false,
			autoRotation: false,
			pauseOnHover: false,
			stretchSlideToMask: false,
			switchTime: 3000,
			animSpeed: 500,
			handleTouch: true,
			swipeThreshold: 15,
			vertical: false
		}, options);
		this.init();
	}

	ScrollAbsoluteGallery.prototype = {
		init: function () {
			if (this.options.holder) {
				this.findElements();
				this.attachEvents();
				this.makeCallback('onInit', this);
			}
		},
		findElements: function () {
			// find structure elements
			this.holder = $(this.options.holder).addClass(this.options.galleryReadyClass);
			this.mask = this.holder.find(this.options.mask);
			this.slider = this.mask.find(this.options.slider);
			this.slides = this.slider.find(this.options.slides);
			this.btnPrev = this.holder.find(this.options.btnPrev);
			this.btnNext = this.holder.find(this.options.btnNext);

			// slide count display
			this.currentNumber = this.holder.find(this.options.currentNumber);
			this.totalNumber = this.holder.find(this.options.totalNumber);

			// create gallery pagination
			if (typeof this.options.generatePagination === 'string') {
				this.pagerLinks = this.buildPagination();
			} else {
				this.pagerLinks = this.holder.find(this.options.pagerLinks);
			}

			// define index variables
			this.sizeProperty = this.options.vertical ? 'height' : 'width';
			this.positionProperty = this.options.vertical ? 'top' : 'left';
			this.animProperty = this.options.vertical ? 'marginTop' : 'marginLeft';

			this.slideSize = this.slides[this.sizeProperty]();
			this.currentIndex = 0;
			this.prevIndex = 0;

			// reposition elements
			this.options.maskAutoSize = this.options.vertical ? false : this.options.maskAutoSize;
			if (this.options.vertical) {
				this.mask.css({
					height: this.slides.innerHeight()
				});
			}
			if (this.options.maskAutoSize) {
				this.mask.css({
					height: this.slider.height()
				});
			}
			this.slider.css({
				position: 'relative',
				height: this.options.vertical ? this.slideSize * this.slides.length : '100%'
			});
			this.slides.css({
				position: 'absolute'
			}).css(this.positionProperty, -9999).eq(this.currentIndex).css(this.positionProperty, 0);
			this.refreshState();
		},
		buildPagination: function () {
			var pagerLinks = $();
			if (!this.pagerHolder) {
				this.pagerHolder = this.holder.find(this.options.generatePagination);
			}
			if (this.pagerHolder.length) {
				this.pagerHolder.empty();
				this.pagerList = $(this.options.pagerList).appendTo(this.pagerHolder);
				for (var i = 0; i < this.slides.length; i++) {
					$(this.options.pagerListItem).appendTo(this.pagerList).find(this.options.pagerListItemText).text(i + 1);
				}
				pagerLinks = this.pagerList.children();
			}
			return pagerLinks;
		},
		attachEvents: function () {
			// attach handlers
			var self = this;
			if (this.btnPrev.length) {
				this.btnPrevHandler = function (e) {
					e.preventDefault();
					self.prevSlide();
				};
				this.btnPrev.click(this.btnPrevHandler);
			}
			if (this.btnNext.length) {
				this.btnNextHandler = function (e) {
					e.preventDefault();
					self.nextSlide();
				};
				this.btnNext.click(this.btnNextHandler);
			}
			if (this.pagerLinks.length) {
				this.pagerLinksHandler = function (e) {
					e.preventDefault();
					self.numSlide(self.pagerLinks.index(e.currentTarget));
				};
				this.pagerLinks.click(this.pagerLinksHandler);
			}

			// handle autorotation pause on hover
			if (this.options.pauseOnHover) {
				this.hoverHandler = function () {
					clearTimeout(self.timer);
				};
				this.leaveHandler = function () {
					self.autoRotate();
				};
				this.holder.bind({mouseenter: this.hoverHandler, mouseleave: this.leaveHandler});
			}

			// handle holder and slides dimensions
			this.resizeHandler = function () {
				if (!self.animating) {
					if (self.options.stretchSlideToMask) {
						self.resizeSlides();
					}
					self.resizeHolder();
					self.setSlidesPosition(self.currentIndex);
				}
			};
			$(window).bind('load resize orientationchange', this.resizeHandler);
			if (self.options.stretchSlideToMask) {
				self.resizeSlides();
			}

			// handle swipe on mobile devices
			if (this.options.handleTouch && window.Hammer && this.mask.length && this.slides.length > 1 && isTouchDevice) {
				this.swipeHandler = new Hammer.Manager(this.mask[0]);
				this.swipeHandler.add(new Hammer.Pan({
					direction: self.options.vertical ? Hammer.DIRECTION_VERTICAL : Hammer.DIRECTION_HORIZONTAL,
					threshold: self.options.swipeThreshold
				}));

				this.swipeHandler.on('panstart', function () {
					if (self.animating) {
						self.swipeHandler.stop();
					} else {
						clearTimeout(self.timer);
					}
				}).on('panmove', function (e) {
					self.swipeOffset = -self.slideSize + e[self.options.vertical ? 'deltaY' : 'deltaX'];
					self.slider.css(self.animProperty, self.swipeOffset);
					clearTimeout(self.timer);
				}).on('panend', function (e) {
					if (e.distance > self.options.swipeThreshold) {
						if (e.offsetDirection === Hammer.DIRECTION_RIGHT || e.offsetDirection === Hammer.DIRECTION_DOWN) {
							self.nextSlide();
						} else {
							self.prevSlide();
						}
					} else {
						var tmpObj = {};
						tmpObj[self.animProperty] = -self.slideSize;
						self.slider.animate(tmpObj, {duration: self.options.animSpeed});
						self.autoRotate();
					}
					self.swipeOffset = 0;
				});
			}

			// start autorotation
			this.autoRotate();
			this.resizeHolder();
			this.setSlidesPosition(this.currentIndex);
		},
		resizeSlides: function () {
			this.slideSize = this.mask[this.options.vertical ? 'height' : 'width']();
			this.slides.css(this.sizeProperty, this.slideSize);
		},
		resizeHolder: function () {
			if (this.options.maskAutoSize) {
				this.mask.css({
					height: this.slides.eq(this.currentIndex).outerHeight(true)
				});
			}
		},
		prevSlide: function () {
			if (!this.animating && this.slides.length > 1) {
				this.direction = -1;
				this.prevIndex = this.currentIndex;
				if (this.currentIndex > 0) this.currentIndex--;
				else this.currentIndex = this.slides.length - 1;
				this.switchSlide();
			}
		},
		nextSlide: function (fromAutoRotation) {
			if (!this.animating && this.slides.length > 1) {
				this.direction = 1;
				this.prevIndex = this.currentIndex;
				if (this.currentIndex < this.slides.length - 1) this.currentIndex++;
				else this.currentIndex = 0;
				this.switchSlide();
			}
		},
		numSlide: function (c) {
			if (!this.animating && this.currentIndex !== c && this.slides.length > 1) {
				this.direction = c > this.currentIndex ? 1 : -1;
				this.prevIndex = this.currentIndex;
				this.currentIndex = c;
				this.switchSlide();
			}
		},
		preparePosition: function () {
			// prepare slides position before animation
			this.setSlidesPosition(this.prevIndex, this.direction < 0 ? this.currentIndex : null, this.direction > 0 ? this.currentIndex : null, this.direction);
		},
		setSlidesPosition: function (index, slideLeft, slideRight, direction) {
			// reposition holder and nearest slides
			if (this.slides.length > 1) {
				var prevIndex = (typeof slideLeft === 'number' ? slideLeft : index > 0 ? index - 1 : this.slides.length - 1);
				var nextIndex = (typeof slideRight === 'number' ? slideRight : index < this.slides.length - 1 ? index + 1 : 0);

				this.slider.css(this.animProperty, this.swipeOffset ? this.swipeOffset : -this.slideSize);
				this.slides.css(this.positionProperty, -9999).eq(index).css(this.positionProperty, this.slideSize);
				if (prevIndex === nextIndex && typeof direction === 'number') {
					var calcOffset = direction > 0 ? this.slideSize * 2 : 0;
					this.slides.eq(nextIndex).css(this.positionProperty, calcOffset);
				} else {
					this.slides.eq(prevIndex).css(this.positionProperty, 0);
					this.slides.eq(nextIndex).css(this.positionProperty, this.slideSize * 2);
				}
			}
		},
		switchSlide: function () {
			// prepare positions and calculate offset
			var self = this;
			var oldSlide = this.slides.eq(this.prevIndex);
			var newSlide = this.slides.eq(this.currentIndex);
			this.animating = true;

			// resize mask to fit slide
			if (this.options.maskAutoSize) {
				this.mask.animate({
					height: newSlide.outerHeight(true)
				}, {
					duration: this.options.animSpeed
				});
			}

			// start animation
			var animProps = {};
			animProps[this.animProperty] = this.direction > 0 ? -this.slideSize * 2 : 0;
			this.preparePosition();
			this.slider.animate(animProps, {
				duration: this.options.animSpeed, complete: function () {
					self.setSlidesPosition(self.currentIndex);

					// start autorotation
					self.animating = false;
					self.autoRotate();

					// onchange callback
					self.makeCallback('onChange', self);
				}
			});

			// refresh classes
			this.refreshState();

			// onchange callback
			this.makeCallback('onBeforeChange', this);
		},
		refreshState: function (initial) {
			// slide change function
			this.slides.removeClass(this.options.activeClass).eq(this.currentIndex).addClass(this.options.activeClass);
			this.pagerLinks.removeClass(this.options.activeClass).eq(this.currentIndex).addClass(this.options.activeClass);

			// display current slide number
			this.currentNumber.html(this.currentIndex + 1);
			this.totalNumber.html(this.slides.length);

			// add class if not enough slides
			this.holder.toggleClass('not-enough-slides', this.slides.length === 1);
		},
		autoRotate: function () {
			var self = this;
			clearTimeout(this.timer);
			if (this.options.autoRotation) {
				this.timer = setTimeout(function () {
					self.nextSlide();
				}, this.options.switchTime);
			}
		},
		makeCallback: function (name) {
			if (typeof this.options[name] === 'function') {
				var args = Array.prototype.slice.call(arguments);
				args.shift();
				this.options[name].apply(this, args);
			}
		},
		destroy: function () {
			// destroy handler
			this.btnPrev.unbind('click', this.btnPrevHandler);
			this.btnNext.unbind('click', this.btnNextHandler);
			this.pagerLinks.unbind('click', this.pagerLinksHandler);
			this.holder.unbind('mouseenter', this.hoverHandler);
			this.holder.unbind('mouseleave', this.leaveHandler);
			$(window).unbind('load resize orientationchange', this.resizeHandler);
			clearTimeout(this.timer);

			// destroy swipe handler
			if (this.swipeHandler) {
				this.swipeHandler.destroy();
			}

			// remove inline styles, classes and pagination
			this.holder.removeClass(this.options.galleryReadyClass);
			this.slider.add(this.slides).removeAttr('style');
			if (typeof this.options.generatePagination === 'string') {
				this.pagerHolder.empty();
			}
		}
	};

	// detect device type
	var isTouchDevice = /Windows Phone/.test(navigator.userAgent) || ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch;

	// jquery plugin
	$.fn.scrollAbsoluteGallery = function (opt) {
		return this.each(function () {
			$(this).data('ScrollAbsoluteGallery', new ScrollAbsoluteGallery($.extend(opt, {holder: this})));
		});
	};
}(jQuery));

/*
 * jQuery Open/Close plugin
 */
;(function ($) {
	function OpenClose(options) {
		this.options = $.extend({
			addClassBeforeAnimation: true,
			hideOnClickOutside: false,
			activeClass: 'active',
			opener: '.opener',
			slider: '.slide',
			animSpeed: 400,
			effect: 'fade',
			event: 'click'
		}, options);
		this.init();
	}

	OpenClose.prototype = {
		init: function () {
			if (this.options.holder) {
				this.findElements();
				this.attachEvents();
				this.makeCallback('onInit', this);
			}
		},
		findElements: function () {
			this.holder = $(this.options.holder);
			this.opener = this.holder.find(this.options.opener);
			this.slider = this.holder.find(this.options.slider);
		},
		attachEvents: function () {
			// add handler
			var self = this;
			this.eventHandler = function (e) {
				e.preventDefault();
				if (self.slider.hasClass(slideHiddenClass)) {
					self.showSlide();
				} else {
					self.hideSlide();
				}
			};
			self.opener.bind(self.options.event, this.eventHandler);

			// hover mode handler
			if (self.options.event === 'over') {
				self.opener.bind('mouseenter', function () {
					if (!self.holder.hasClass(self.options.activeClass)) {
						self.showSlide();
					}
				});
				self.holder.bind('mouseleave', function () {
					self.hideSlide();
				});
			}

			// outside click handler
			self.outsideClickHandler = function (e) {
				if (self.options.hideOnClickOutside) {
					var target = $(e.target);
					if (!target.is(self.holder) && !target.closest(self.holder).length) {
						self.hideSlide();
					}
				}
			};

			// set initial styles
			if (this.holder.hasClass(this.options.activeClass)) {
				$(document).bind('click touchstart', self.outsideClickHandler);
			} else {
				this.slider.addClass(slideHiddenClass);
			}
		},
		showSlide: function () {
			var self = this;
			if (self.options.addClassBeforeAnimation) {
				self.holder.addClass(self.options.activeClass);
			}
			self.slider.removeClass(slideHiddenClass);
			$(document).bind('click touchstart', self.outsideClickHandler);

			self.makeCallback('animStart', true);
			toggleEffects[self.options.effect].show({
				box: self.slider,
				speed: self.options.animSpeed,
				complete: function () {
					if (!self.options.addClassBeforeAnimation) {
						self.holder.addClass(self.options.activeClass);
					}
					self.makeCallback('animEnd', true);
				}
			});
		},
		hideSlide: function () {
			var self = this;
			if (self.options.addClassBeforeAnimation) {
				self.holder.removeClass(self.options.activeClass);
			}
			$(document).unbind('click touchstart', self.outsideClickHandler);

			self.makeCallback('animStart', false);
			toggleEffects[self.options.effect].hide({
				box: self.slider,
				speed: self.options.animSpeed,
				complete: function () {
					if (!self.options.addClassBeforeAnimation) {
						self.holder.removeClass(self.options.activeClass);
					}
					self.slider.addClass(slideHiddenClass);
					self.makeCallback('animEnd', false);
				}
			});
		},
		destroy: function () {
			this.slider.removeClass(slideHiddenClass).css({display: ''});
			this.opener.unbind(this.options.event, this.eventHandler);
			this.holder.removeClass(this.options.activeClass).removeData('OpenClose');
			$(document).unbind('click touchstart', this.outsideClickHandler);
		},
		makeCallback: function (name) {
			if (typeof this.options[name] === 'function') {
				var args = Array.prototype.slice.call(arguments);
				args.shift();
				this.options[name].apply(this, args);
			}
		}
	};

	// add stylesheet for slide on DOMReady
	var slideHiddenClass = 'js-slide-hidden';
	(function () {
		var tabStyleSheet = $('<style type="text/css">')[0];
		var tabStyleRule = '.' + slideHiddenClass;
		tabStyleRule += '{position:absolute !important;left:-9999px !important;top:-9999px !important;display:block !important}';
		if (tabStyleSheet.styleSheet) {
			tabStyleSheet.styleSheet.cssText = tabStyleRule;
		} else {
			tabStyleSheet.appendChild(document.createTextNode(tabStyleRule));
		}
		$('head').append(tabStyleSheet);
	}());

	// animation effects
	var toggleEffects = {
		slide: {
			show: function (o) {
				o.box.stop(true).hide().slideDown(o.speed, o.complete);
			},
			hide: function (o) {
				o.box.stop(true).slideUp(o.speed, o.complete);
			}
		},
		fade: {
			show: function (o) {
				o.box.stop(true).hide().fadeIn(o.speed, o.complete);
			},
			hide: function (o) {
				o.box.stop(true).fadeOut(o.speed, o.complete);
			}
		},
		none: {
			show: function (o) {
				o.box.hide().show(0, o.complete);
			},
			hide: function (o) {
				o.box.hide(0, o.complete);
			}
		}
	};

	// jQuery plugin interface
	$.fn.openClose = function (opt) {
		return this.each(function () {
			jQuery(this).data('OpenClose', new OpenClose($.extend(opt, {holder: this})));
		});
	};
}(jQuery));

/*
 * FixedScrollBlock
 */
/*;(function($, window) {
 'use strict';
 var isMobileDevice = ('ontouchstart' in window) ||
 (window.DocumentTouch && document instanceof DocumentTouch) ||
 /Windows Phone/.test(navigator.userAgent);

 function FixedScrollBlock(options) {
 this.options = $.extend({
 fixedActiveClass: 'fixed-position',
 slideBlock: '[data-scroll-block]',
 positionType: 'auto',
 fixedOnlyIfFits: true,
 container: null,
 animDelay: 100,
 animSpeed: 200,
 extraBottom: 0,
 extraTop: 0
 }, options);
 this.initStructure();
 this.attachEvents();
 }
 FixedScrollBlock.prototype = {
 initStructure: function() {
 // find elements
 this.win = $(window);
 this.container = $(this.options.container);
 this.slideBlock = this.container.find(this.options.slideBlock);

 // detect method
 if(this.options.positionType === 'auto') {
 this.options.positionType = isMobileDevice ? 'absolute' : 'fixed';
 }
 },
 attachEvents: function() {
 var self = this;

 // bind events
 this.onResize = function() {
 self.resizeHandler();
 };
 this.onScroll = function() {
 self.scrollHandler();
 };

 // handle events
 this.win.on({
 resize: this.onResize,
 scroll: this.onScroll
 });

 // initial handler call
 this.resizeHandler();
 },
 recalculateOffsets: function() {
 var defaultOffset = this.slideBlock.offset(),
 defaultPosition = this.slideBlock.position(),
 holderOffset = this.container.offset(),
 windowSize = this.win.height();

 this.data = {
 windowHeight: this.win.height(),
 windowWidth: this.win.width(),

 blockPositionLeft: defaultPosition.left,
 blockPositionTop: defaultPosition.top,

 blockOffsetLeft: defaultOffset.left,
 blockOffsetTop: defaultOffset.top,
 blockHeight: this.slideBlock.innerHeight(),

 holderOffsetLeft: holderOffset.left,
 holderOffsetTop: holderOffset.top,
 holderHeight: this.container.innerHeight()
 };
 },
 isVisible: function() {
 return this.slideBlock.prop('offsetHeight');
 },
 fitsInViewport: function() {
 if(this.options.fixedOnlyIfFits && this.data) {
 return this.data.blockHeight + this.options.extraTop <= this.data.windowHeight;
 } else {
 return true;
 }
 },
 resizeHandler: function() {
 if(this.isVisible()) {
 FixedScrollBlock.stickyMethods[this.options.positionType].onResize.apply(this, arguments);
 this.scrollHandler();
 }
 },
 scrollHandler: function() {
 if(this.isVisible()) {
 if(!this.data) {
 this.resizeHandler();
 return;
 }
 this.currentScrollTop = this.win.scrollTop();
 this.currentScrollLeft = this.win.scrollLeft();
 FixedScrollBlock.stickyMethods[this.options.positionType].onScroll.apply(this, arguments);
 }
 },
 refresh: function() {
 // refresh dimensions and state if needed
 if(this.data) {
 this.data.holderHeight = this.container.innerHeight();
 this.data.blockHeight = this.slideBlock.innerHeight();
 this.scrollHandler();
 }
 },
 destroy: function() {
 // remove event handlers and styles
 this.slideBlock.removeAttr('style').removeClass(this.options.fixedActiveClass);
 this.win.off({
 resize: this.onResize,
 scroll: this.onScroll
 });
 }
 };

 // sticky methods
 FixedScrollBlock.stickyMethods = {
 fixed: {
 onResize: function() {
 this.slideBlock.removeAttr('style');
 this.recalculateOffsets();
 },
 onScroll: function() {
 if(this.fitsInViewport() && this.currentScrollTop + this.options.extraTop > this.data.blockOffsetTop) {
 if(this.currentScrollTop + this.options.extraTop + this.data.blockHeight > this.data.holderOffsetTop + this.data.holderHeight - this.options.extraBottom) {
 this.slideBlock.css({
 position: 'absolute',
 top: this.data.blockPositionTop + this.data.holderHeight - this.data.blockHeight - this.options.extraBottom - (this.data.blockOffsetTop - this.data.holderOffsetTop),
 left: this.data.blockPositionLeft
 });
 } else {
 this.slideBlock.css({
 position: 'fixed',
 top: this.options.extraTop,
 left: this.data.blockOffsetLeft - this.currentScrollLeft
 });
 }
 this.slideBlock.addClass(this.options.fixedActiveClass);
 } else {
 this.slideBlock.removeClass(this.options.fixedActiveClass).removeAttr('style');
 }
 }
 },
 absolute: {
 onResize: function() {
 this.slideBlock.removeAttr('style');
 this.recalculateOffsets();

 this.slideBlock.css({
 position: 'absolute',
 top: this.data.blockPositionTop,
 left: this.data.blockPositionLeft
 });

 this.slideBlock.addClass(this.options.fixedActiveClass);
 },
 onScroll: function() {
 var self = this;
 clearTimeout(this.animTimer);
 this.animTimer = setTimeout(function() {
 var currentScrollTop = self.currentScrollTop + self.options.extraTop,
 initialPosition = self.data.blockPositionTop - (self.data.blockOffsetTop - self.data.holderOffsetTop),
 maxTopPosition =  self.data.holderHeight - self.data.blockHeight - self.options.extraBottom,
 currentTopPosition = initialPosition + Math.min(currentScrollTop - self.data.holderOffsetTop, maxTopPosition),
 calcTopPosition = self.fitsInViewport() && currentScrollTop > self.data.blockOffsetTop ? currentTopPosition : self.data.blockPositionTop;

 self.slideBlock.stop().animate({
 top: calcTopPosition
 }, self.options.animSpeed);
 }, this.options.animDelay);
 }
 }
 };

 // jQuery plugin interface
 $.fn.fixedScrollBlock = function(options) {
 return this.each(function() {
 var params = $.extend({}, options, {container: this}),
 instance = new FixedScrollBlock(params);
 $.data(this, 'FixedScrollBlock', instance);
 });
 };

 // module exports
 window.FixedScrollBlock = FixedScrollBlock;
 }(jQuery, this));*/

/*
 * jQuery SameHeight plugin
 */
;(function ($) {
	$.fn.sameHeight = function (opt) {
		var options = $.extend({
			skipClass: 'same-height-ignore',
			leftEdgeClass: 'same-height-left',
			rightEdgeClass: 'same-height-right',
			elements: '>*',
			flexible: false,
			multiLine: false,
			useMinHeight: false,
			biggestHeight: false
		}, opt);
		return this.each(function () {
			var holder = $(this), postResizeTimer, ignoreResize;
			var elements = holder.find(options.elements).not('.' + options.skipClass);
			if (!elements.length) return;

			// resize handler
			function doResize() {
				elements.css(options.useMinHeight && supportMinHeight ? 'minHeight' : 'height', '');
				if (options.multiLine) {
					// resize elements row by row
					resizeElementsByRows(elements, options);
				} else {
					// resize elements by holder
					resizeElements(elements, holder, options);
				}
			}

			doResize();

			// handle flexible layout / font resize
			var delayedResizeHandler = function () {
				if (!ignoreResize) {
					ignoreResize = true;
					doResize();
					clearTimeout(postResizeTimer);
					postResizeTimer = setTimeout(function () {
						doResize();
						setTimeout(function () {
							ignoreResize = false;
						}, 10);
					}, 100);
				}
			};

			// handle flexible/responsive layout
			if (options.flexible) {
				$(window).bind('resize orientationchange fontresize', delayedResizeHandler);
			}

			// handle complete page load including images and fonts
			$(window).bind('load', delayedResizeHandler);
		});
	};

	// detect css min-height support
	var supportMinHeight = typeof document.documentElement.style.maxHeight !== 'undefined';

	// get elements by rows
	function resizeElementsByRows(boxes, options) {
		var currentRow = $(), maxHeight, maxCalcHeight = 0, firstOffset = boxes.eq(0).offset().top;
		boxes.each(function (ind) {
			var curItem = $(this);
			if (curItem.offset().top === firstOffset) {
				currentRow = currentRow.add(this);
			} else {
				maxHeight = getMaxHeight(currentRow);
				maxCalcHeight = Math.max(maxCalcHeight, resizeElements(currentRow, maxHeight, options));
				currentRow = curItem;
				firstOffset = curItem.offset().top;
			}
		});
		if (currentRow.length) {
			maxHeight = getMaxHeight(currentRow);
			maxCalcHeight = Math.max(maxCalcHeight, resizeElements(currentRow, maxHeight, options));
		}
		if (options.biggestHeight) {
			boxes.css(options.useMinHeight && supportMinHeight ? 'minHeight' : 'height', maxCalcHeight);
		}
	}

	// calculate max element height
	function getMaxHeight(boxes) {
		var maxHeight = 0;
		boxes.each(function () {
			maxHeight = Math.max(maxHeight, $(this).outerHeight());
		});
		return maxHeight;
	}

	// resize helper function
	function resizeElements(boxes, parent, options) {
		var calcHeight;
		var parentHeight = typeof parent === 'number' ? parent : parent.height();
		boxes.removeClass(options.leftEdgeClass).removeClass(options.rightEdgeClass).each(function (i) {
			var element = $(this);
			var depthDiffHeight = 0;
			var isBorderBox = element.css('boxSizing') === 'border-box' || element.css('-moz-box-sizing') === 'border-box' || element.css('-webkit-box-sizing') === 'border-box';

			if (typeof parent !== 'number') {
				element.parents().each(function () {
					var tmpParent = $(this);
					if (parent.is(this)) {
						return false;
					} else {
						depthDiffHeight += tmpParent.outerHeight() - tmpParent.height();
					}
				});
			}
			calcHeight = parentHeight - depthDiffHeight;
			calcHeight -= isBorderBox ? 0 : element.outerHeight() - element.height();

			if (calcHeight > 0) {
				element.css(options.useMinHeight && supportMinHeight ? 'minHeight' : 'height', calcHeight);
			}
		});
		boxes.filter(':first').addClass(options.leftEdgeClass);
		boxes.filter(':last').addClass(options.rightEdgeClass);
		return calcHeight;
	}
}(jQuery));

/*
 * jQuery FontResize Event
 */
jQuery.onFontResize = (function ($) {
	$(function () {
		var randomID = 'font-resize-frame-' + Math.floor(Math.random() * 1000);
		var resizeFrame = $('<iframe>').attr('id', randomID).addClass('font-resize-helper');

		// required styles
		resizeFrame.css({
			width: '100em',
			height: '10px',
			position: 'absolute',
			borderWidth: 0,
			top: '-9999px',
			left: '-9999px'
		}).appendTo('body');

		// use native IE resize event if possible
		if (window.attachEvent && !window.addEventListener) {
			resizeFrame.bind('resize', function () {
				$.onFontResize.trigger(resizeFrame[0].offsetWidth / 100);
			});
		}
		// use script inside the iframe to detect resize for other browsers
		else {
			var doc = resizeFrame[0].contentWindow.document;
			doc.open();
			doc.write('<scri' + 'pt>window.onload = function(){var em = parent.jQuery("#' + randomID + '")[0];window.onresize = function(){if(parent.jQuery.onFontResize){parent.jQuery.onFontResize.trigger(em.offsetWidth / 100);}}};</scri' + 'pt>');
			doc.close();
		}
		jQuery.onFontResize.initialSize = resizeFrame[0].offsetWidth / 100;
	});
	return {
		// public method, so it can be called from within the iframe
		trigger: function (em) {
			$(window).trigger("fontresize", [em]);
		}
	};
}(jQuery));


;(function ($) {
	function AjaxFilters(options) {
		this.options = jQuery.extend({
			ajaxBlockAttr: 'data-load-container',
			items: 'li',
			loadingClass: 'loading',
			ajaxData: 'ajax=true',
			activeClass: 'active'
		}, options);
		this.init();
	}

	AjaxFilters.prototype = {
		init: function () {
			if (this.options.filterForm) {
				this.findElements();
				this.makeCallback('onBeforeInit', this);
				this.attachEvents();
				this.makeCallback('onInit', this);
			}
		},
		findElements: function () {
			var self = this;
			this.filterForm = jQuery(this.options.filterForm);
			this.selects = this.filterForm.find('select');
			this.ajaxBlock = jQuery(this.filterForm.attr(this.options.ajaxBlockAttr));
			this.items = this.ajaxBlock.find(this.options.items);
			this.dropNav = this.filterForm.find(this.options.dropNav);
		},
		attachEvents: function () {
			var self = this;

			this.selects.on('change', function () {
				self.changeFilter();
			});
			this.filterForm.on('submit', function (e) {
				e.preventDefault();
			});
		},
		prepareAjax: function () {

			var ajaxData = this.options.ajaxData;
			if (this.filterForm.length) {
				ajaxData += '&' + this.filterForm.serialize();
			}
			return {
				ajaxData: ajaxData,
				url: this.filterForm.attr('action'),
				type: this.filterForm.attr('method') || 'get'
			};
		},
		changeFilter: function () {
			var obj = this.prepareAjax();
			this.loadAjax(obj);
		},
		loadAjax: function (obj) {
			var self = this;

			this.ajaxBlock.addClass(this.options.loadingClass);
			if (this.ajaxRequest) {

				this.isBusy = true;
				this.ajaxRequest.abort();
			}

			this.ajaxRequest = jQuery.ajax({
				type: obj.type,
				url: obj.url,
				data: obj.ajaxData,
				success: function (data) {
					self.ajaxLoaded(data);
				}
			});
		},
		ajaxLoaded: function (data) {
			var self = this;
			var newContent = jQuery('<div>', {html: data});
			var newItems = newContent.find(this.options.items);

			this.ajaxBlock.css({minHeight: this.ajaxBlock.innerHeight()});
			this.ajaxBlock.stop().animate({opacity: 0}, 300, function () {
				self.ajaxBlock.empty();
				self.ajaxBlock.append(newItems);

				self.imagesLoaded(function () {
					self.ajaxBlock.css({minHeight: ''}).stop().animate({opacity: 1}, 300);
					self.ajaxBlock.removeClass(self.options.loadingClass);
					self.isBusy = false;
					self.makeCallback('onLoad', newItems);
				});
			});

			this.makeCallback('onBeforeShow', this);
		},
		imagesLoaded: function (complete) {
			var self = this;
			if (jQuery.isFunction(window.picturefill)) {
				picturefill();
			}
			setTimeout(function () {
				var newImages = self.ajaxBlock.find('img');
				var imagesCount = newImages.length;
				var loadedCount = 0;
				if (imagesCount) {
					newImages.each(function () {
						var image = jQuery(this);
						jQuery('<img />').on('load error', function () {
							loadedCount++;
							if (loadedCount === imagesCount) {
								complete();
							}
						}).attr('src', image.attr('src'));
					});

				} else {
					complete();
				}
			}, 100);
		},
		makeCallback: function (name) {
			if (typeof this.options[name] === 'function') {
				var args = Array.prototype.slice.call(arguments);
				args.shift();
				this.options[name].apply(this, args);
			}
		}
	};
	// jQuery plugin interface
	$.fn.ajaxFilters = function (opt) {
		return this.each(function () {
			jQuery(this).data('AjaxFilters', new AjaxFilters($.extend(opt, {filterForm: this})));
		});
	};
}(jQuery));

// custom map plugin
function CustomMap(opt) {
	this.options = jQuery.extend({
		holder: null,
		map: '.map-canvas',
		startCooords: [-33.512065, 143.125073],
		defaultOptions: {
			maxZoom: 16,
			zoom: 12
		}
	}, opt);
	this.init();
}
CustomMap.prototype = {
	init: function () {
		if (this.options.holder) {
			this.findElements();
			this.createMap();
			this.attachEvents();
			this.makeCallback('onInit', this);
		}
	},
	findElements: function () {
		this.holder = jQuery(this.options.holder);
		this.map = this.holder.find(this.options.map);
	},
	attachEvents: function () {
		var self = this;
		var timer;

		jQuery(window).on('resize mapRefresh', function () {
			if (self.markers && self.markers.length < 2) {
				clearTimeout(timer);
				timer = setTimeout(function () {
					google.maps.event.trigger(self.mapCanvas, 'resize');
					self.mapCanvas.setCenter(self.addedMarker[0].position);
				}, 100);
			}
		});
	},
	createMap: function () {
		var self = this,
			usRoadMapType;

		this.mapOptions = jQuery.extend({}, this.options.defaultOptions, {
			center: new google.maps.LatLng(this.options.startCooords[0], this.options.startCooords[1]),
			mapTypeControlOptions: {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'usroadatlas']
			}
		});
		this.mapCanvas = new google.maps.Map(this.map[0], this.mapOptions);
		this.getMapStyles(function () {
			usRoadMapType = new google.maps.StyledMapType(self.mapStyles, {name: 'Custom Map'});
			self.mapCanvas.mapTypes.set('usroadatlas', usRoadMapType);
			self.mapCanvas.setMapTypeId('usroadatlas');
		});
		this.loadMarkersJSON(this.map.attr('data-markers'), function (data) {
			self.prepareMarkers(data);
		});
	},
	getMapStyles: function (complete) {
		var self = this;
		jQuery.getJSON(this.map.attr('data-styles'), function (data) {
			self.mapStyles = data;
			complete();
		});
	},
	loadMarkersJSON: function (url, complete) {
		jQuery.getJSON(url, function (data) {
			complete(data);
		});
	},
	prepareMarkers: function (data) {
		var self = this,
			currentIndex = 0;

		this.markers = [];
		jQuery.each(data.markers, function (key, val) {
			currentIndex++;
			var newMarker = {
				position: new google.maps.LatLng(val.location[0], val.location[1])
			};
			if (val.imageURL && val.imageURL.length) {
				newMarker.icon = val.imageURL;
			}
			self.markers.push(newMarker);
			if (currentIndex == data.markers.length) {
				self.makeCallback('onMapReady', self);
				self.addMarkers();
			}
		});
	},
	addMarkers: function () {
		if (!this.markers.length) return;
		var self = this;
		this.addedMarker = [];

		this.bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < this.markers.length; i++) {
			this.addMarker(this.markers[i]);
		}
		setTimeout(function () {
			if (self.markers.length > 2) {
				self.mapCanvas.fitBounds(self.bounds);
			} else {
				self.mapCanvas.setCenter(self.addedMarker[0].position);
			}
		}, 100);
		this.makeCallback('onMarkersAdded', this);
	},
	addMarker: function (obj) {
		var self = this;
		var markerOptions = jQuery.extend({}, obj, {map: this.mapCanvas});
		var marker = new google.maps.Marker(markerOptions);
		google.maps.event.addListener(marker, 'click', function () {
			window.location = self.getDirrectionLink(obj.position);
		});

		this.addedMarker.push(marker);
		this.bounds.extend(obj.position);
	},
	makeCallback: function (name) {
		if (typeof this.options[name] === 'function') {
			var args = Array.prototype.slice.call(arguments);
			args.shift();
			this.options[name].apply(this, args);
		}
	},
	getDirrectionLink: function (coordinates) {
		return 'https://maps.google.com/maps?daddr=' + coordinates.lat() + ',' + coordinates.lng();
	}
};

/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-cssanimations-csstransitions-touch-shiv-cssclasses-prefixed-teststyles-testprop-testallprops-prefixes-domprefixes-load
 */
;window.Modernizr = function (a, b, c) {
	function z(a) {
		j.cssText = a
	}

	function A(a, b) {
		return z(m.join(a + ";") + (b || ""))
	}

	function B(a, b) {
		return typeof a === b
	}

	function C(a, b) {
		return !!~("" + a).indexOf(b)
	}

	function D(a, b) {
		for (var d in a) {
			var e = a[d];
			if (!C(e, "-") && j[e] !== c)return b == "pfx" ? e : !0
		}
		return !1
	}

	function E(a, b, d) {
		for (var e in a) {
			var f = b[a[e]];
			if (f !== c)return d === !1 ? a[e] : B(f, "function") ? f.bind(d || b) : f
		}
		return !1
	}

	function F(a, b, c) {
		var d = a.charAt(0).toUpperCase() + a.slice(1), e = (a + " " + o.join(d + " ") + d).split(" ");
		return B(b, "string") || B(b, "undefined") ? D(e, b) : (e = (a + " " + p.join(d + " ") + d).split(" "), E(e, b, c))
	}

	var d = "2.6.2", e = {}, f = !0, g = b.documentElement, h = "modernizr", i = b.createElement(h), j = i.style, k, l = {}.toString, m = " -webkit- -moz- -o- -ms- ".split(" "), n = "Webkit Moz O ms", o = n.split(" "), p = n.toLowerCase().split(" "), q = {}, r = {}, s = {}, t = [], u = t.slice, v, w = function (a, c, d, e) {
		var f, i, j, k, l = b.createElement("div"), m = b.body, n = m || b.createElement("body");
		if (parseInt(d, 10))while (d--)j = b.createElement("div"), j.id = e ? e[d] : h + (d + 1), l.appendChild(j);
		return f = ["&#173;", '<style id="s', h, '">', a, "</style>"].join(""), l.id = h, (m ? l : n).innerHTML += f, n.appendChild(l), m || (n.style.background = "", n.style.overflow = "hidden", k = g.style.overflow, g.style.overflow = "hidden", g.appendChild(n)), i = c(l, a), m ? l.parentNode.removeChild(l) : (n.parentNode.removeChild(n), g.style.overflow = k), !!i
	}, x = {}.hasOwnProperty, y;
	!B(x, "undefined") && !B(x.call, "undefined") ? y = function (a, b) {
		return x.call(a, b)
	} : y = function (a, b) {
		return b in a && B(a.constructor.prototype[b], "undefined")
	}, Function.prototype.bind || (Function.prototype.bind = function (b) {
		var c = this;
		if (typeof c != "function")throw new TypeError;
		var d = u.call(arguments, 1), e = function () {
			if (this instanceof e) {
				var a = function () {
				};
				a.prototype = c.prototype;
				var f = new a, g = c.apply(f, d.concat(u.call(arguments)));
				return Object(g) === g ? g : f
			}
			return c.apply(b, d.concat(u.call(arguments)))
		};
		return e
	}), q.touch = function () {
		var c;
		return "ontouchstart" in a || a.DocumentTouch && b instanceof DocumentTouch ? c = !0 : w(["@media (", m.join("touch-enabled),("), h, ")", "{#modernizr{top:9px;position:absolute}}"].join(""), function (a) {
			c = a.offsetTop === 9
		}), c
	}, q.cssanimations = function () {
		return F("animationName")
	}, q.csstransitions = function () {
		return F("transition")
	};
	for (var G in q)y(q, G) && (v = G.toLowerCase(), e[v] = q[G](), t.push((e[v] ? "" : "no-") + v));
	return e.addTest = function (a, b) {
		if (typeof a == "object")for (var d in a)y(a, d) && e.addTest(d, a[d]); else {
			a = a.toLowerCase();
			if (e[a] !== c)return e;
			b = typeof b == "function" ? b() : b, typeof f != "undefined" && f && (g.className += " " + (b ? "" : "no-") + a), e[a] = b
		}
		return e
	}, z(""), i = k = null, function (a, b) {
		function k(a, b) {
			var c = a.createElement("p"), d = a.getElementsByTagName("head")[0] || a.documentElement;
			return c.innerHTML = "x<style>" + b + "</style>", d.insertBefore(c.lastChild, d.firstChild)
		}

		function l() {
			var a = r.elements;
			return typeof a == "string" ? a.split(" ") : a
		}

		function m(a) {
			var b = i[a[g]];
			return b || (b = {}, h++, a[g] = h, i[h] = b), b
		}

		function n(a, c, f) {
			c || (c = b);
			if (j)return c.createElement(a);
			f || (f = m(c));
			var g;
			return f.cache[a] ? g = f.cache[a].cloneNode() : e.test(a) ? g = (f.cache[a] = f.createElem(a)).cloneNode() : g = f.createElem(a), g.canHaveChildren && !d.test(a) ? f.frag.appendChild(g) : g
		}

		function o(a, c) {
			a || (a = b);
			if (j)return a.createDocumentFragment();
			c = c || m(a);
			var d = c.frag.cloneNode(), e = 0, f = l(), g = f.length;
			for (; e < g; e++)d.createElement(f[e]);
			return d
		}

		function p(a, b) {
			b.cache || (b.cache = {}, b.createElem = a.createElement, b.createFrag = a.createDocumentFragment, b.frag = b.createFrag()), a.createElement = function (c) {
				return r.shivMethods ? n(c, a, b) : b.createElem(c)
			}, a.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + l().join().replace(/\w+/g, function (a) {
					return b.createElem(a), b.frag.createElement(a), 'c("' + a + '")'
				}) + ");return n}")(r, b.frag)
		}

		function q(a) {
			a || (a = b);
			var c = m(a);
			return r.shivCSS && !f && !c.hasCSS && (c.hasCSS = !!k(a, "article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")), j || p(a, c), a
		}

		var c = a.html5 || {}, d = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i, e = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i, f, g = "_html5shiv", h = 0, i = {}, j;
		(function () {
			try {
				var a = b.createElement("a");
				a.innerHTML = "<xyz></xyz>", f = "hidden" in a, j = a.childNodes.length == 1 || function () {
						b.createElement("a");
						var a = b.createDocumentFragment();
						return typeof a.cloneNode == "undefined" || typeof a.createDocumentFragment == "undefined" || typeof a.createElement == "undefined"
					}()
			} catch (c) {
				f = !0, j = !0
			}
		})();
		var r = {
			elements: c.elements || "abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",
			shivCSS: c.shivCSS !== !1,
			supportsUnknownElements: j,
			shivMethods: c.shivMethods !== !1,
			type: "default",
			shivDocument: q,
			createElement: n,
			createDocumentFragment: o
		};
		a.html5 = r, q(b)
	}(this, b), e._version = d, e._prefixes = m, e._domPrefixes = p, e._cssomPrefixes = o, e.testProp = function (a) {
		return D([a])
	}, e.testAllProps = F, e.testStyles = w, e.prefixed = function (a, b, c) {
		return b ? F(a, b, c) : F(a, "pfx")
	}, g.className = g.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + (f ? " js " + t.join(" ") : ""), e
}(this, this.document), function (a, b, c) {
	function d(a) {
		return "[object Function]" == o.call(a)
	}

	function e(a) {
		return "string" == typeof a
	}

	function f() {
	}

	function g(a) {
		return !a || "loaded" == a || "complete" == a || "uninitialized" == a
	}

	function h() {
		var a = p.shift();
		q = 1, a ? a.t ? m(function () {
			("c" == a.t ? B.injectCss : B.injectJs)(a.s, 0, a.a, a.x, a.e, 1)
		}, 0) : (a(), h()) : q = 0
	}

	function i(a, c, d, e, f, i, j) {
		function k(b) {
			if (!o && g(l.readyState) && (u.r = o = 1, !q && h(), l.onload = l.onreadystatechange = null, b)) {
				"img" != a && m(function () {
					t.removeChild(l)
				}, 50);
				for (var d in y[c])y[c].hasOwnProperty(d) && y[c][d].onload()
			}
		}

		var j = j || B.errorTimeout, l = b.createElement(a), o = 0, r = 0, u = {t: d, s: c, e: f, a: i, x: j};
		1 === y[c] && (r = 1, y[c] = []), "object" == a ? l.data = c : (l.src = c, l.type = a), l.width = l.height = "0", l.onerror = l.onload = l.onreadystatechange = function () {
			k.call(this, r)
		}, p.splice(e, 0, u), "img" != a && (r || 2 === y[c] ? (t.insertBefore(l, s ? null : n), m(k, j)) : y[c].push(l))
	}

	function j(a, b, c, d, f) {
		return q = 0, b = b || "j", e(a) ? i("c" == b ? v : u, a, b, this.i++, c, d, f) : (p.splice(this.i++, 0, a), 1 == p.length && h()), this
	}

	function k() {
		var a = B;
		return a.loader = {load: j, i: 0}, a
	}

	var l = b.documentElement, m = a.setTimeout, n = b.getElementsByTagName("script")[0], o = {}.toString, p = [], q = 0, r = "MozAppearance" in l.style, s = r && !!b.createRange().compareNode, t = s ? l : n.parentNode, l = a.opera && "[object Opera]" == o.call(a.opera), l = !!b.attachEvent && !l, u = r ? "object" : l ? "script" : "img", v = l ? "script" : u, w = Array.isArray || function (a) {
			return "[object Array]" == o.call(a)
		}, x = [], y = {}, z = {
		timeout: function (a, b) {
			return b.length && (a.timeout = b[0]), a
		}
	}, A, B;
	B = function (a) {
		function b(a) {
			var a = a.split("!"), b = x.length, c = a.pop(), d = a.length, c = {
				url: c,
				origUrl: c,
				prefixes: a
			}, e, f, g;
			for (f = 0; f < d; f++)g = a[f].split("="), (e = z[g.shift()]) && (c = e(c, g));
			for (f = 0; f < b; f++)c = x[f](c);
			return c
		}

		function g(a, e, f, g, h) {
			var i = b(a), j = i.autoCallback;
			i.url.split(".").pop().split("?").shift(), i.bypass || (e && (e = d(e) ? e : e[a] || e[g] || e[a.split("/").pop().split("?")[0]]), i.instead ? i.instead(a, e, f, g, h) : (y[i.url] ? i.noexec = !0 : y[i.url] = 1, f.load(i.url, i.forceCSS || !i.forceJS && "css" == i.url.split(".").pop().split("?").shift() ? "c" : c, i.noexec, i.attrs, i.timeout), (d(e) || d(j)) && f.load(function () {
				k(), e && e(i.origUrl, h, g), j && j(i.origUrl, h, g), y[i.url] = 2
			})))
		}

		function h(a, b) {
			function c(a, c) {
				if (a) {
					if (e(a))c || (j = function () {
						var a = [].slice.call(arguments);
						k.apply(this, a), l()
					}), g(a, j, b, 0, h); else if (Object(a) === a)for (n in m = function () {
						var b = 0, c;
						for (c in a)a.hasOwnProperty(c) && b++;
						return b
					}(), a)a.hasOwnProperty(n) && (!c && !--m && (d(j) ? j = function () {
						var a = [].slice.call(arguments);
						k.apply(this, a), l()
					} : j[n] = function (a) {
						return function () {
							var b = [].slice.call(arguments);
							a && a.apply(this, b), l()
						}
					}(k[n])), g(a[n], j, b, n, h))
				} else!c && l()
			}

			var h = !!a.test, i = a.load || a.both, j = a.callback || f, k = j, l = a.complete || f, m, n;
			c(h ? a.yep : a.nope, !!i), i && c(i)
		}

		var i, j, l = this.yepnope.loader;
		if (e(a))g(a, 0, l, 0); else if (w(a))for (i = 0; i < a.length; i++)j = a[i], e(j) ? g(j, 0, l, 0) : w(j) ? B(j) : Object(j) === j && h(j, l); else Object(a) === a && h(a, l)
	}, B.addPrefix = function (a, b) {
		z[a] = b
	}, B.addFilter = function (a) {
		x.push(a)
	}, B.errorTimeout = 1e4, null == b.readyState && b.addEventListener && (b.readyState = "loading", b.addEventListener("DOMContentLoaded", A = function () {
		b.removeEventListener("DOMContentLoaded", A, 0), b.readyState = "complete"
	}, 0)), a.yepnope = k(), a.yepnope.executeStack = h, a.yepnope.injectJs = function (a, c, d, e, i, j) {
		var k = b.createElement("script"), l, o, e = e || B.errorTimeout;
		k.src = a;
		for (o in d)k.setAttribute(o, d[o]);
		c = j ? h : c || f, k.onreadystatechange = k.onload = function () {
			!l && g(k.readyState) && (l = 1, c(), k.onload = k.onreadystatechange = null)
		}, m(function () {
			l || (l = 1, c(1))
		}, e), i ? k.onload() : n.parentNode.insertBefore(k, n)
	}, a.yepnope.injectCss = function (a, c, d, e, g, i) {
		var e = b.createElement("link"), j, c = i ? h : c || f;
		e.href = a, e.rel = "stylesheet", e.type = "text/css";
		for (j in d)e.setAttribute(j, d[j]);
		g || (n.parentNode.insertBefore(e, n), m(c, 0))
	}
}(this, document), Modernizr.load = function () {
	yepnope.apply(window, [].slice.call(arguments, 0))
};

/*
 * jQuery DropLevelMenu plugin
 */
;(function ($) {
	'use strict';

	function DropLevelMenu(options) {
		this.options = $.extend({
			animationClasses: {
				classin: 'dl-animate-in-1',
				classout: 'dl-animate-out-1'
			},
			backLabel: 'Back',
			useActiveItemAsBackLabel: false,
			useActiveItemAsLink: false,
			resetOnClose: true,
			activeHolderClass: 'dl-menu-active',
			activeOpenerClass: 'dl-active',
			menuOpenClass: 'dl-menuopen',
			menuToggleClass: 'dl-menu-toggle',
			viewClass: 'dl-subview',
			viewOpenClass: 'dl-subviewopen',
			backClass: 'dl-back',
			opener: '.dl-trigger',
			menu: 'ul.dl-menu',
			submenu: 'ul.dl-submenu'
		}, options);
		this.init();
	}

	DropLevelMenu.prototype = {
		init: function () {
			if (this.options.holder) {
				this.findElements();

				var animEndEventNames = {
						'WebkitAnimation': 'webkitAnimationEnd',
						'OAnimation': 'oAnimationEnd',
						'msAnimation': 'MSAnimationEnd',
						'animation': 'animationend'
					},
					transEndEventNames = {
						'WebkitTransition': 'webkitTransitionEnd',
						'MozTransition': 'transitionend',
						'OTransition': 'oTransitionEnd',
						'msTransition': 'MSTransitionEnd',
						'transition': 'transitionend'
					};
				this.animEndEventName = animEndEventNames[Modernizr.prefixed('animation')] + '.dlmenu';
				this.transEndEventName = transEndEventNames[Modernizr.prefixed('transition')] + '.dlmenu';
				this.supportAnimations = Modernizr.cssanimations;
				this.supportTransitions = Modernizr.csstransitions;

				this.attachEvents();
			}
		},
		findElements: function () {
			var self = this;
			this.open = false;
			this.holder = $(this.options.holder);
			this.btnToggleMenu = this.holder.find(this.options.opener);
			this.menu = this.holder.find(this.options.menu);
			this.menuItems = this.menu.find('li').not('.' + this.options.backClass);
			this.holder.find(this.options.submenu).prepend('<li class="' + this.options.backClass + '"><a href="#">' + this.options.backLabel + '</a></li>');
			this.btnBack = this.menu.find('.' + this.options.backClass);

			// Set the label text for the back link.
			if (this.options.useActiveItemAsBackLabel) {
				this.btnBack.each(function () {
					var $this = $(this),
						parentLabel = $this.parents('li:first').find('a:first').text();

					$this.find('a').html(parentLabel);
				});
			}
			// If the active item should also be a clickable link, create one and put
			// it at the top of our menu.
			if (this.options.useActiveItemAsLink) {
				this.holder.find(this.options.submenu).prepend(function () {
					var parentli = $(this).parents('li:first').not('.' + self.options.backClass).find('a:first');
					return '<li class="dl-parent"><a href="' + parentli.attr('href') + '">' + parentli.text() + '</a></li>';
				});
			}
		},
		attachEvents: function () {

			var self = this;

			this.btnToggleMenu.on('click.dropLevelMenu', function (e) {
				e.preventDefault();

				self.toggleMenu();
			});

			this.menuItems.on('click.dropLevelMenu', function (e) {
				e.stopPropagation();

				if ($(this).has(self.options.submenu).length) {
					e.preventDefault();
				}

				var item = $(this),
					submenu = item.children(self.options.submenu);

				if ((submenu.length > 0) && !($(e.currentTarget).hasClass(self.options.viewOpenClass))) {

					var flyin = submenu.clone().css('opacity', 0).insertAfter(self.menu),
						onAnimationEndFn = function () {
							self.menu.off(self.animEndEventName).removeClass(self.options.animationClasses.classout).addClass(self.options.viewClass);

							item.addClass(self.options.viewOpenClass)
								.parents('.' + self.options.viewOpenClass + ':first')
								.removeClass(self.options.viewOpenClass)
								.addClass(self.options.viewClass);
							flyin.remove();
							self.makeCallback('onAnimEnd', item, self);
						};

					setTimeout(function () {
						flyin.addClass(self.options.animationClasses.classin);
						self.menu.addClass(self.options.animationClasses.classout);
						if (self.supportAnimations) {
							self.menu.on(self.animEndEventName, onAnimationEndFn);
						} else {
							onAnimationEndFn.call();
						}
						self.makeCallback('onLevelClick', item, self);
					});
				}

			});

			this.btnBack.on('click.dropLevelMenu', function (e) {
				var btnBack = $(this),
					submenu = btnBack.parents(self.options.submenu + ':first'),
					item = submenu.parent(),
					flyin = submenu.clone().insertAfter(self.menu);

				var onAnimationEndFn = function () {
					self.menu.off(self.animEndEventName).removeClass(self.options.animationClasses.classin);
					flyin.remove();
					self.makeCallback('onAnimEnd', item, self);
				};

				setTimeout(function () {
					flyin.addClass(self.options.animationClasses.classout);
					self.menu.addClass(self.options.animationClasses.classin);
					if (self.supportAnimations) {
						self.menu.on(self.animEndEventName, onAnimationEndFn);
					} else {
						onAnimationEndFn.call();
					}

					item.removeClass(self.options.viewOpenClass);

					var subview = btnBack.parents('.' + self.options.viewClass + ':first');
					if (subview.is('li')) {
						subview.addClass(self.options.viewOpenClass);
					}
					subview.removeClass(self.options.viewClass);
				});

				e.preventDefault();

			});
		},
		toggleMenu: function () {
			if (!this.open) {
				this.openMenu();
			} else {
				this.closeMenu();
			}
		},
		closeMenu: function () {
			var self = this,
				onTransitionEndFn = function () {
					self.menu.off(self.transEndEventName);
					if (self.options.resetOnClose) {
						self.resetMenu();
					}
				};

			this.holder.removeClass(this.options.activeHolderClass);
			this.menu.removeClass(this.options.menuOpenClass);
			this.menu.addClass(this.options.menuToggleClass);
			this.btnToggleMenu.removeClass(this.options.activeOpenerClass);

			if (this.supportTransitions) {
				this.menu.on(this.transEndEventName, onTransitionEndFn);
			} else {
				onTransitionEndFn.call();
			}

			this.open = false;
		},
		openMenu: function () {
			var self = this;
			this.holder.addClass(this.options.activeHolderClass);
			this.menu.addClass(self.options.menuOpenClass).addClass(self.options.menuToggleClass).on(this.transEndEventName, function () {
				$(this).removeClass(self.options.menuToggleClass);
			});
			this.btnToggleMenu.addClass(self.activeOpenerClass);
			this.open = true;
		},
		resetMenu: function () {
			this.menu.removeClass(this.options.viewClass);
			this.menuItems.removeClass(this.options.viewClass).removeClass(this.options.viewOpenClass);
		},
		destroy: function () {
			this.btnToggleMenu.off('.dropLevelMenu');
			this.btnBack.off('.dropLevelMenu').remove();
			this.menuItems.removeClass(this.options.viewOpenClass)
				.removeClass(this.options.viewClass)
				.off('.dropLevelMenu');
			this.btnToggleMenu.removeClass(this.options.activeOpenerClass);
			this.menu
				.removeClass(this.options.menuOpenClass)
				.removeClass(this.options.menuToggleClass)
				.off(this.animEndEventName)
				.off(this.transEndEventName);
			this.holder.removeClass(this.options.activeHolderClass).removeData('DropLevelMenu');
			this.menuItems.removeData('isOpen');
		},
		makeCallback: function (name) {
			if (typeof this.options[name] === 'function') {
				var args = Array.prototype.slice.call(arguments);
				args.shift();
				this.options[name].apply(this, args);
			}
		}
	};

	// jQuery plugin interface
	$.fn.dropLevelMenu = function (opt) {
		return this.each(function () {
			jQuery(this).data('DropLevelMenu', new DropLevelMenu($.extend(true, {}, opt, {
				holder: this
			})));
		});
	};
}(jQuery));

/*! fancyBox v2.1.5 fancyapps.com | fancyapps.com/fancybox/#license */
;(function (r, G, f, v) {
	var J = f("html"), n = f(r), p = f(G), b = f.fancybox = function () {
		b.open.apply(this, arguments)
	}, I = navigator.userAgent.match(/msie/i), B = null, s = G.createTouch !== v, t = function (a) {
		return a && a.hasOwnProperty && a instanceof f
	}, q = function (a) {
		return a && "string" === f.type(a)
	}, E = function (a) {
		return q(a) && 0 < a.indexOf("%")
	}, l = function (a, d) {
		var e = parseInt(a, 10) || 0;
		d && E(a) && (e *= b.getViewport()[d] / 100);
		return Math.ceil(e)
	}, w = function (a, b) {
		return l(a, b) + "px"
	};
	f.extend(b, {
		version: "2.1.5",
		defaults: {
			padding: 15,
			margin: 20,
			width: 800,
			height: 600,
			minWidth: 100,
			minHeight: 100,
			maxWidth: 9999,
			maxHeight: 9999,
			pixelRatio: 1,
			autoSize: !0,
			autoHeight: !1,
			autoWidth: !1,
			autoResize: !0,
			autoCenter: !s,
			fitToView: !0,
			aspectRatio: !1,
			topRatio: 0.5,
			leftRatio: 0.5,
			scrolling: "auto",
			wrapCSS: "",
			arrows: !0,
			closeBtn: !0,
			closeClick: !1,
			nextClick: !1,
			mouseWheel: !0,
			autoPlay: !1,
			playSpeed: 3E3,
			preload: 3,
			modal: !1,
			loop: !0,
			ajax: {dataType: "html", headers: {"X-fancyBox": !0}},
			iframe: {scrolling: "auto", preload: !0},
			swf: {wmode: "transparent", allowfullscreen: "true", allowscriptaccess: "always"},
			keys: {
				next: {13: "left", 34: "up", 39: "left", 40: "up"},
				prev: {8: "right", 33: "down", 37: "right", 38: "down"},
				close: [27],
				play: [32],
				toggle: [70]
			},
			direction: {next: "left", prev: "right"},
			scrollOutside: !0,
			index: 0,
			type: null,
			href: null,
			content: null,
			title: null,
			tpl: {
				wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
				image: '<img class="fancybox-image" src="{href}" alt="" />',
				iframe: '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen' + (I ? ' allowtransparency="true"' : "") + "></iframe>",
				error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
				closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
				next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
				prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
			},
			openEffect: "fade",
			openSpeed: 250,
			openEasing: "swing",
			openOpacity: !0,
			openMethod: "zoomIn",
			closeEffect: "fade",
			closeSpeed: 250,
			closeEasing: "swing",
			closeOpacity: !0,
			closeMethod: "zoomOut",
			nextEffect: "elastic",
			nextSpeed: 250,
			nextEasing: "swing",
			nextMethod: "changeIn",
			prevEffect: "elastic",
			prevSpeed: 250,
			prevEasing: "swing",
			prevMethod: "changeOut",
			helpers: {overlay: !0, title: !0},
			onCancel: f.noop,
			beforeLoad: f.noop,
			afterLoad: f.noop,
			beforeShow: f.noop,
			afterShow: f.noop,
			beforeChange: f.noop,
			beforeClose: f.noop,
			afterClose: f.noop
		},
		group: {},
		opts: {},
		previous: null,
		coming: null,
		current: null,
		isActive: !1,
		isOpen: !1,
		isOpened: !1,
		wrap: null,
		skin: null,
		outer: null,
		inner: null,
		player: {timer: null, isActive: !1},
		ajaxLoad: null,
		imgPreload: null,
		transitions: {},
		helpers: {},
		open: function (a, d) {
			if (a && (f.isPlainObject(d) || (d = {}), !1 !== b.close(!0)))return f.isArray(a) || (a = t(a) ? f(a).get() : [a]), f.each(a, function (e, c) {
				var k = {}, g, h, j, m, l;
				"object" === f.type(c) && (c.nodeType && (c = f(c)), t(c) ? (k = {
					href: c.data("fancybox-href") || c.attr("href"),
					title: c.data("fancybox-title") || c.attr("title"),
					isDom: !0,
					element: c
				}, f.metadata && f.extend(!0, k, c.metadata())) : k = c);
				g = d.href || k.href || (q(c) ? c : null);
				h = d.title !== v ? d.title : k.title || "";
				m = (j = d.content || k.content) ? "html" : d.type || k.type;
				!m && k.isDom && (m = c.data("fancybox-type"), m || (m = (m = c.prop("class").match(/fancybox\.(\w+)/)) ? m[1] : null));
				q(g) && (m || (b.isImage(g) ? m = "image" : b.isSWF(g) ? m = "swf" : "#" === g.charAt(0) ? m = "inline" : q(c) && (m = "html", j = c)), "ajax" === m && (l = g.split(/\s+/, 2), g = l.shift(), l = l.shift()));
				j || ("inline" === m ? g ? j = f(q(g) ? g.replace(/.*(?=#[^\s]+$)/, "") : g) : k.isDom && (j = c) : "html" === m ? j = g : !m && (!g && k.isDom) && (m = "inline", j = c));
				f.extend(k, {href: g, type: m, content: j, title: h, selector: l});
				a[e] = k
			}), b.opts = f.extend(!0, {}, b.defaults, d), d.keys !== v && (b.opts.keys = d.keys ? f.extend({}, b.defaults.keys, d.keys) : !1), b.group = a, b._start(b.opts.index)
		},
		cancel: function () {
			var a = b.coming;
			a && !1 !== b.trigger("onCancel") && (b.hideLoading(), b.ajaxLoad && b.ajaxLoad.abort(), b.ajaxLoad = null, b.imgPreload && (b.imgPreload.onload = b.imgPreload.onerror = null), a.wrap && a.wrap.stop(!0, !0).trigger("onReset").remove(), b.coming = null, b.current || b._afterZoomOut(a))
		},
		close: function (a) {
			b.cancel();
			!1 !== b.trigger("beforeClose") && (b.unbindEvents(), b.isActive && (!b.isOpen || !0 === a ? (f(".fancybox-wrap").stop(!0).trigger("onReset").remove(), b._afterZoomOut()) : (b.isOpen = b.isOpened = !1, b.isClosing = !0, f(".fancybox-item, .fancybox-nav").remove(), b.wrap.stop(!0, !0).removeClass("fancybox-opened"), b.transitions[b.current.closeMethod]())))
		},
		play: function (a) {
			var d = function () {
				clearTimeout(b.player.timer)
			}, e = function () {
				d();
				b.current && b.player.isActive && (b.player.timer = setTimeout(b.next, b.current.playSpeed))
			}, c = function () {
				d();
				p.unbind(".player");
				b.player.isActive = !1;
				b.trigger("onPlayEnd")
			};
			if (!0 === a || !b.player.isActive && !1 !== a) {
				if (b.current && (b.current.loop || b.current.index < b.group.length - 1))b.player.isActive = !0, p.bind({
					"onCancel.player beforeClose.player": c,
					"onUpdate.player": e,
					"beforeLoad.player": d
				}), e(), b.trigger("onPlayStart")
			} else c()
		},
		next: function (a) {
			var d = b.current;
			d && (q(a) || (a = d.direction.next), b.jumpto(d.index + 1, a, "next"))
		},
		prev: function (a) {
			var d = b.current;
			d && (q(a) || (a = d.direction.prev), b.jumpto(d.index - 1, a, "prev"))
		},
		jumpto: function (a, d, e) {
			var c = b.current;
			c && (a = l(a), b.direction = d || c.direction[a >= c.index ? "next" : "prev"], b.router = e || "jumpto", c.loop && (0 > a && (a = c.group.length + a % c.group.length), a %= c.group.length), c.group[a] !== v && (b.cancel(), b._start(a)))
		},
		reposition: function (a, d) {
			var e = b.current, c = e ? e.wrap : null, k;
			c && (k = b._getPosition(d), a && "scroll" === a.type ? (delete k.position, c.stop(!0, !0).animate(k, 200)) : (c.css(k), e.pos = f.extend({}, e.dim, k)))
		},
		update: function (a) {
			var d = a && a.type, e = !d || "orientationchange" === d;
			e && (clearTimeout(B), B = null);
			b.isOpen && !B && (B = setTimeout(function () {
				var c = b.current;
				c && !b.isClosing && (b.wrap.removeClass("fancybox-tmp"), (e || "load" === d || "resize" === d && c.autoResize) && b._setDimension(), "scroll" === d && c.canShrink || b.reposition(a), b.trigger("onUpdate"), B = null)
			}, e && !s ? 0 : 300))
		},
		toggle: function (a) {
			b.isOpen && (b.current.fitToView = "boolean" === f.type(a) ? a : !b.current.fitToView, s && (b.wrap.removeAttr("style").addClass("fancybox-tmp"), b.trigger("onUpdate")), b.update())
		},
		hideLoading: function () {
			p.unbind(".loading");
			f("#fancybox-loading").remove()
		},
		showLoading: function () {
			var a, d;
			b.hideLoading();
			a = f('<div id="fancybox-loading"><div></div></div>').click(b.cancel).appendTo("body");
			p.bind("keydown.loading", function (a) {
				if (27 === (a.which || a.keyCode))a.preventDefault(), b.cancel()
			});
			b.defaults.fixed || (d = b.getViewport(), a.css({
				position: "absolute",
				top: 0.5 * d.h + d.y,
				left: 0.5 * d.w + d.x
			}))
		},
		getViewport: function () {
			var a = b.current && b.current.locked || !1, d = {x: n.scrollLeft(), y: n.scrollTop()};
			a ? (d.w = a[0].clientWidth, d.h = a[0].clientHeight) : (d.w = s && r.innerWidth ? r.innerWidth : n.width(), d.h = s && r.innerHeight ? r.innerHeight : n.height());
			return d
		},
		unbindEvents: function () {
			b.wrap && t(b.wrap) && b.wrap.unbind(".fb");
			p.unbind(".fb");
			n.unbind(".fb")
		},
		bindEvents: function () {
			var a = b.current, d;
			a && (n.bind("orientationchange.fb" + (s ? "" : " resize.fb") + (a.autoCenter && !a.locked ? " scroll.fb" : ""), b.update), (d = a.keys) && p.bind("keydown.fb", function (e) {
				var c = e.which || e.keyCode, k = e.target || e.srcElement;
				if (27 === c && b.coming)return !1;
				!e.ctrlKey && (!e.altKey && !e.shiftKey && !e.metaKey && (!k || !k.type && !f(k).is("[contenteditable]"))) && f.each(d, function (d, k) {
					if (1 < a.group.length && k[c] !== v)return b[d](k[c]), e.preventDefault(), !1;
					if (-1 < f.inArray(c, k))return b[d](), e.preventDefault(), !1
				})
			}), f.fn.mousewheel && a.mouseWheel && b.wrap.bind("mousewheel.fb", function (d, c, k, g) {
				for (var h = f(d.target || null), j = !1; h.length && !j && !h.is(".fancybox-skin") && !h.is(".fancybox-wrap");)j = h[0] && !(h[0].style.overflow && "hidden" === h[0].style.overflow) && (h[0].clientWidth && h[0].scrollWidth > h[0].clientWidth || h[0].clientHeight && h[0].scrollHeight > h[0].clientHeight), h = f(h).parent();
				if (0 !== c && !j && 1 < b.group.length && !a.canShrink) {
					if (0 < g || 0 < k)b.prev(0 < g ? "down" : "left"); else if (0 > g || 0 > k)b.next(0 > g ? "up" : "right");
					d.preventDefault()
				}
			}))
		},
		trigger: function (a, d) {
			var e, c = d || b.coming || b.current;
			if (c) {
				f.isFunction(c[a]) && (e = c[a].apply(c, Array.prototype.slice.call(arguments, 1)));
				if (!1 === e)return !1;
				c.helpers && f.each(c.helpers, function (d, e) {
					if (e && b.helpers[d] && f.isFunction(b.helpers[d][a]))b.helpers[d][a](f.extend(!0, {}, b.helpers[d].defaults, e), c)
				});
				p.trigger(a)
			}
		},
		isImage: function (a) {
			return q(a) && a.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i)
		},
		isSWF: function (a) {
			return q(a) && a.match(/\.(swf)((\?|#).*)?$/i)
		},
		_start: function (a) {
			var d = {}, e, c;
			a = l(a);
			e = b.group[a] || null;
			if (!e)return !1;
			d = f.extend(!0, {}, b.opts, e);
			e = d.margin;
			c = d.padding;
			"number" === f.type(e) && (d.margin = [e, e, e, e]);
			"number" === f.type(c) && (d.padding = [c, c, c, c]);
			d.modal && f.extend(!0, d, {
				closeBtn: !1,
				closeClick: !1,
				nextClick: !1,
				arrows: !1,
				mouseWheel: !1,
				keys: null,
				helpers: {overlay: {closeClick: !1}}
			});
			d.autoSize && (d.autoWidth = d.autoHeight = !0);
			"auto" === d.width && (d.autoWidth = !0);
			"auto" === d.height && (d.autoHeight = !0);
			d.group = b.group;
			d.index = a;
			b.coming = d;
			if (!1 === b.trigger("beforeLoad"))b.coming = null; else {
				c = d.type;
				e = d.href;
				if (!c)return b.coming = null, b.current && b.router && "jumpto" !== b.router ? (b.current.index = a, b[b.router](b.direction)) : !1;
				b.isActive = !0;
				if ("image" === c || "swf" === c)d.autoHeight = d.autoWidth = !1, d.scrolling = "visible";
				"image" === c && (d.aspectRatio = !0);
				"iframe" === c && s && (d.scrolling = "scroll");
				d.wrap = f(d.tpl.wrap).addClass("fancybox-" + (s ? "mobile" : "desktop") + " fancybox-type-" + c + " fancybox-tmp " + d.wrapCSS).appendTo(d.parent || "body");
				f.extend(d, {
					skin: f(".fancybox-skin", d.wrap),
					outer: f(".fancybox-outer", d.wrap),
					inner: f(".fancybox-inner", d.wrap)
				});
				f.each(["Top", "Right", "Bottom", "Left"], function (a, b) {
					d.skin.css("padding" + b, w(d.padding[a]))
				});
				b.trigger("onReady");
				if ("inline" === c || "html" === c) {
					if (!d.content || !d.content.length)return b._error("content")
				} else if (!e)return b._error("href");
				"image" === c ? b._loadImage() : "ajax" === c ? b._loadAjax() : "iframe" === c ? b._loadIframe() : b._afterLoad()
			}
		},
		_error: function (a) {
			f.extend(b.coming, {
				type: "html",
				autoWidth: !0,
				autoHeight: !0,
				minWidth: 0,
				minHeight: 0,
				scrolling: "no",
				hasError: a,
				content: b.coming.tpl.error
			});
			b._afterLoad()
		},
		_loadImage: function () {
			var a = b.imgPreload = new Image;
			a.onload = function () {
				this.onload = this.onerror = null;
				b.coming.width = this.width / b.opts.pixelRatio;
				b.coming.height = this.height / b.opts.pixelRatio;
				b._afterLoad()
			};
			a.onerror = function () {
				this.onload = this.onerror = null;
				b._error("image")
			};
			a.src = b.coming.href;
			!0 !== a.complete && b.showLoading()
		},
		_loadAjax: function () {
			var a = b.coming;
			b.showLoading();
			b.ajaxLoad = f.ajax(f.extend({}, a.ajax, {
				url: a.href, error: function (a, e) {
					b.coming && "abort" !== e ? b._error("ajax", a) : b.hideLoading()
				}, success: function (d, e) {
					"success" === e && (a.content = d, b._afterLoad())
				}
			}))
		},
		_loadIframe: function () {
			var a = b.coming, d = f(a.tpl.iframe.replace(/\{rnd\}/g, (new Date).getTime())).attr("scrolling", s ? "auto" : a.iframe.scrolling).attr("src", a.href);
			f(a.wrap).bind("onReset", function () {
				try {
					f(this).find("iframe").hide().attr("src", "//about:blank").end().empty()
				} catch (a) {
				}
			});
			a.iframe.preload && (b.showLoading(), d.one("load", function () {
				f(this).data("ready", 1);
				s || f(this).bind("load.fb", b.update);
				f(this).parents(".fancybox-wrap").width("100%").removeClass("fancybox-tmp").show();
				b._afterLoad()
			}));
			a.content = d.appendTo(a.inner);
			a.iframe.preload || b._afterLoad()
		},
		_preloadImages: function () {
			var a = b.group, d = b.current, e = a.length, c = d.preload ? Math.min(d.preload, e - 1) : 0, f, g;
			for (g = 1; g <= c; g += 1)f = a[(d.index + g) % e], "image" === f.type && f.href && ((new Image).src = f.href)
		},
		_afterLoad: function () {
			var a = b.coming, d = b.current, e, c, k, g, h;
			b.hideLoading();
			if (a && !1 !== b.isActive)if (!1 === b.trigger("afterLoad", a, d))a.wrap.stop(!0).trigger("onReset").remove(), b.coming = null; else {
				d && (b.trigger("beforeChange", d), d.wrap.stop(!0).removeClass("fancybox-opened").find(".fancybox-item, .fancybox-nav").remove());
				b.unbindEvents();
				e = a.content;
				c = a.type;
				k = a.scrolling;
				f.extend(b, {wrap: a.wrap, skin: a.skin, outer: a.outer, inner: a.inner, current: a, previous: d});
				g = a.href;
				switch (c) {
					case "inline":
					case "ajax":
					case "html":
						a.selector ? e = f("<div>").html(e).find(a.selector) : t(e) && (e.data("fancybox-placeholder") || e.data("fancybox-placeholder", f('<div class="fancybox-placeholder"></div>').insertAfter(e).hide()), e = e.show().detach(), a.wrap.bind("onReset", function () {
							f(this).find(e).length && e.hide().replaceAll(e.data("fancybox-placeholder")).data("fancybox-placeholder", !1)
						}));
						break;
					case "image":
						e = a.tpl.image.replace("{href}", g);
						break;
					case "swf":
						e = '<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + g + '"></param>', h = "", f.each(a.swf, function (a, b) {
							e += '<param name="' + a + '" value="' + b + '"></param>';
							h += " " + a + '="' + b + '"'
						}), e += '<embed src="' + g + '" type="application/x-shockwave-flash" width="100%" height="100%"' + h + "></embed></object>"
				}
				(!t(e) || !e.parent().is(a.inner)) && a.inner.append(e);
				b.trigger("beforeShow");
				a.inner.css("overflow", "yes" === k ? "scroll" : "no" === k ? "hidden" : k);
				b._setDimension();
				b.reposition();
				b.isOpen = !1;
				b.coming = null;
				b.bindEvents();
				if (b.isOpened) {
					if (d.prevMethod)b.transitions[d.prevMethod]()
				} else f(".fancybox-wrap").not(a.wrap).stop(!0).trigger("onReset").remove();
				b.transitions[b.isOpened ? a.nextMethod : a.openMethod]();
				b._preloadImages()
			}
		},
		_setDimension: function () {
			var a = b.getViewport(), d = 0, e = !1, c = !1, e = b.wrap, k = b.skin, g = b.inner, h = b.current, c = h.width, j = h.height, m = h.minWidth, u = h.minHeight, n = h.maxWidth, p = h.maxHeight, s = h.scrolling, q = h.scrollOutside ? h.scrollbarWidth : 0, x = h.margin, y = l(x[1] + x[3]), r = l(x[0] + x[2]), v, z, t, C, A, F, B, D, H;
			e.add(k).add(g).width("auto").height("auto").removeClass("fancybox-tmp");
			x = l(k.outerWidth(!0) - k.width());
			v = l(k.outerHeight(!0) - k.height());
			z = y + x;
			t = r + v;
			C = E(c) ? (a.w - z) * l(c) / 100 : c;
			A = E(j) ? (a.h - t) * l(j) / 100 : j;
			if ("iframe" === h.type) {
				if (H = h.content, h.autoHeight && 1 === H.data("ready"))try {
					H[0].contentWindow.document.location && (g.width(C).height(9999), F = H.contents().find("body"), q && F.css("overflow-x", "hidden"), A = F.outerHeight(!0))
				} catch (G) {
				}
			} else if (h.autoWidth || h.autoHeight)g.addClass("fancybox-tmp"), h.autoWidth || g.width(C), h.autoHeight || g.height(A), h.autoWidth && (C = g.width()), h.autoHeight && (A = g.height()), g.removeClass("fancybox-tmp");
			c = l(C);
			j = l(A);
			D = C / A;
			m = l(E(m) ? l(m, "w") - z : m);
			n = l(E(n) ? l(n, "w") - z : n);
			u = l(E(u) ? l(u, "h") - t : u);
			p = l(E(p) ? l(p, "h") - t : p);
			F = n;
			B = p;
			h.fitToView && (n = Math.min(a.w - z, n), p = Math.min(a.h - t, p));
			z = a.w - y;
			r = a.h - r;
			h.aspectRatio ? (c > n && (c = n, j = l(c / D)), j > p && (j = p, c = l(j * D)), c < m && (c = m, j = l(c / D)), j < u && (j = u, c = l(j * D))) : (c = Math.max(m, Math.min(c, n)), h.autoHeight && "iframe" !== h.type && (g.width(c), j = g.height()), j = Math.max(u, Math.min(j, p)));
			if (h.fitToView)if (g.width(c).height(j), e.width(c + x), a = e.width(), y = e.height(), h.aspectRatio)for (; (a > z || y > r) && (c > m && j > u) && !(19 < d++);)j = Math.max(u, Math.min(p, j - 10)), c = l(j * D), c < m && (c = m, j = l(c / D)), c > n && (c = n, j = l(c / D)), g.width(c).height(j), e.width(c + x), a = e.width(), y = e.height(); else c = Math.max(m, Math.min(c, c - (a - z))), j = Math.max(u, Math.min(j, j - (y - r)));
			q && ("auto" === s && j < A && c + x + q < z) && (c += q);
			g.width(c).height(j);
			e.width(c + x);
			a = e.width();
			y = e.height();
			e = (a > z || y > r) && c > m && j > u;
			c = h.aspectRatio ? c < F && j < B && c < C && j < A : (c < F || j < B) && (c < C || j < A);
			f.extend(h, {
				dim: {width: w(a), height: w(y)},
				origWidth: C,
				origHeight: A,
				canShrink: e,
				canExpand: c,
				wPadding: x,
				hPadding: v,
				wrapSpace: y - k.outerHeight(!0),
				skinSpace: k.height() - j
			});
			!H && (h.autoHeight && j > u && j < p && !c) && g.height("auto")
		},
		_getPosition: function (a) {
			var d = b.current, e = b.getViewport(), c = d.margin, f = b.wrap.width() + c[1] + c[3], g = b.wrap.height() + c[0] + c[2], c = {
				position: "absolute",
				top: c[0],
				left: c[3]
			};
			d.autoCenter && d.fixed && !a && g <= e.h && f <= e.w ? c.position = "fixed" : d.locked || (c.top += e.y, c.left += e.x);
			c.top = w(Math.max(c.top, c.top + (e.h - g) * d.topRatio));
			c.left = w(Math.max(c.left, c.left + (e.w - f) * d.leftRatio));
			return c
		},
		_afterZoomIn: function () {
			var a = b.current;
			a && (b.isOpen = b.isOpened = !0, b.wrap.css("overflow", "visible").addClass("fancybox-opened"), b.update(), (a.closeClick || a.nextClick && 1 < b.group.length) && b.inner.css("cursor", "pointer").bind("click.fb", function (d) {
				!f(d.target).is("a") && !f(d.target).parent().is("a") && (d.preventDefault(), b[a.closeClick ? "close" : "next"]())
			}), a.closeBtn && f(a.tpl.closeBtn).appendTo(b.skin).bind("click.fb", function (a) {
				a.preventDefault();
				b.close()
			}), a.arrows && 1 < b.group.length && ((a.loop || 0 < a.index) && f(a.tpl.prev).appendTo(b.outer).bind("click.fb", b.prev), (a.loop || a.index < b.group.length - 1) && f(a.tpl.next).appendTo(b.outer).bind("click.fb", b.next)), b.trigger("afterShow"), !a.loop && a.index === a.group.length - 1 ? b.play(!1) : b.opts.autoPlay && !b.player.isActive && (b.opts.autoPlay = !1, b.play()))
		},
		_afterZoomOut: function (a) {
			a = a || b.current;
			f(".fancybox-wrap").trigger("onReset").remove();
			f.extend(b, {
				group: {},
				opts: {},
				router: !1,
				current: null,
				isActive: !1,
				isOpened: !1,
				isOpen: !1,
				isClosing: !1,
				wrap: null,
				skin: null,
				outer: null,
				inner: null
			});
			b.trigger("afterClose", a)
		}
	});
	b.transitions = {
		getOrigPosition: function () {
			var a = b.current, d = a.element, e = a.orig, c = {}, f = 50, g = 50, h = a.hPadding, j = a.wPadding, m = b.getViewport();
			!e && (a.isDom && d.is(":visible")) && (e = d.find("img:first"), e.length || (e = d));
			t(e) ? (c = e.offset(), e.is("img") && (f = e.outerWidth(), g = e.outerHeight())) : (c.top = m.y + (m.h - g) * a.topRatio, c.left = m.x + (m.w - f) * a.leftRatio);
			if ("fixed" === b.wrap.css("position") || a.locked)c.top -= m.y, c.left -= m.x;
			return c = {
				top: w(c.top - h * a.topRatio),
				left: w(c.left - j * a.leftRatio),
				width: w(f + j),
				height: w(g + h)
			}
		}, step: function (a, d) {
			var e, c, f = d.prop;
			c = b.current;
			var g = c.wrapSpace, h = c.skinSpace;
			if ("width" === f || "height" === f)e = d.end === d.start ? 1 : (a - d.start) / (d.end - d.start), b.isClosing && (e = 1 - e), c = "width" === f ? c.wPadding : c.hPadding, c = a - c, b.skin[f](l("width" === f ? c : c - g * e)), b.inner[f](l("width" === f ? c : c - g * e - h * e))
		}, zoomIn: function () {
			var a = b.current, d = a.pos, e = a.openEffect, c = "elastic" === e, k = f.extend({opacity: 1}, d);
			delete k.position;
			c ? (d = this.getOrigPosition(), a.openOpacity && (d.opacity = 0.1)) : "fade" === e && (d.opacity = 0.1);
			b.wrap.css(d).animate(k, {
				duration: "none" === e ? 0 : a.openSpeed,
				easing: a.openEasing,
				step: c ? this.step : null,
				complete: b._afterZoomIn
			})
		}, zoomOut: function () {
			var a = b.current, d = a.closeEffect, e = "elastic" === d, c = {opacity: 0.1};
			e && (c = this.getOrigPosition(), a.closeOpacity && (c.opacity = 0.1));
			b.wrap.animate(c, {
				duration: "none" === d ? 0 : a.closeSpeed,
				easing: a.closeEasing,
				step: e ? this.step : null,
				complete: b._afterZoomOut
			})
		}, changeIn: function () {
			var a = b.current, d = a.nextEffect, e = a.pos, c = {opacity: 1}, f = b.direction, g;
			e.opacity = 0.1;
			"elastic" === d && (g = "down" === f || "up" === f ? "top" : "left", "down" === f || "right" === f ? (e[g] = w(l(e[g]) - 200), c[g] = "+=200px") : (e[g] = w(l(e[g]) + 200), c[g] = "-=200px"));
			"none" === d ? b._afterZoomIn() : b.wrap.css(e).animate(c, {
				duration: a.nextSpeed,
				easing: a.nextEasing,
				complete: b._afterZoomIn
			})
		}, changeOut: function () {
			var a = b.previous, d = a.prevEffect, e = {opacity: 0.1}, c = b.direction;
			"elastic" === d && (e["down" === c || "up" === c ? "top" : "left"] = ("up" === c || "left" === c ? "-" : "+") + "=200px");
			a.wrap.animate(e, {
				duration: "none" === d ? 0 : a.prevSpeed, easing: a.prevEasing, complete: function () {
					f(this).trigger("onReset").remove()
				}
			})
		}
	};
	b.helpers.overlay = {
		defaults: {closeClick: !0, speedOut: 200, showEarly: !0, css: {}, locked: !s, fixed: !0},
		overlay: null,
		fixed: !1,
		el: f("html"),
		create: function (a) {
			a = f.extend({}, this.defaults, a);
			this.overlay && this.close();
			this.overlay = f('<div class="fancybox-overlay"></div>').appendTo(b.coming ? b.coming.parent : a.parent);
			this.fixed = !1;
			a.fixed && b.defaults.fixed && (this.overlay.addClass("fancybox-overlay-fixed"), this.fixed = !0)
		},
		open: function (a) {
			var d = this;
			a = f.extend({}, this.defaults, a);
			this.overlay ? this.overlay.unbind(".overlay").width("auto").height("auto") : this.create(a);
			this.fixed || (n.bind("resize.overlay", f.proxy(this.update, this)), this.update());
			a.closeClick && this.overlay.bind("click.overlay", function (a) {
				if (f(a.target).hasClass("fancybox-overlay"))return b.isActive ? b.close() : d.close(), !1
			});
			this.overlay.css(a.css).show()
		},
		close: function () {
			var a, b;
			n.unbind("resize.overlay");
			this.el.hasClass("fancybox-lock") && (f(".fancybox-margin").removeClass("fancybox-margin"), a = n.scrollTop(), b = n.scrollLeft(), this.el.removeClass("fancybox-lock"), n.scrollTop(a).scrollLeft(b));
			f(".fancybox-overlay").remove().hide();
			f.extend(this, {overlay: null, fixed: !1})
		},
		update: function () {
			var a = "100%", b;
			this.overlay.width(a).height("100%");
			I ? (b = Math.max(G.documentElement.offsetWidth, G.body.offsetWidth), p.width() > b && (a = p.width())) : p.width() > n.width() && (a = p.width());
			this.overlay.width(a).height(p.height())
		},
		onReady: function (a, b) {
			var e = this.overlay;
			f(".fancybox-overlay").stop(!0, !0);
			e || this.create(a);
			a.locked && (this.fixed && b.fixed) && (e || (this.margin = p.height() > n.height() ? f("html").css("margin-right").replace("px", "") : !1), b.locked = this.overlay.append(b.wrap), b.fixed = !1);
			!0 === a.showEarly && this.beforeShow.apply(this, arguments)
		},
		beforeShow: function (a, b) {
			var e, c;
			b.locked && (!1 !== this.margin && (f("*").filter(function () {
				return "fixed" === f(this).css("position") && !f(this).hasClass("fancybox-overlay") && !f(this).hasClass("fancybox-wrap")
			}).addClass("fancybox-margin"), this.el.addClass("fancybox-margin")), e = n.scrollTop(), c = n.scrollLeft(), this.el.addClass("fancybox-lock"), n.scrollTop(e).scrollLeft(c));
			this.open(a)
		},
		onUpdate: function () {
			this.fixed || this.update()
		},
		afterClose: function (a) {
			this.overlay && !b.coming && this.overlay.fadeOut(a.speedOut, f.proxy(this.close, this))
		}
	};
	b.helpers.title = {
		defaults: {type: "float", position: "bottom"}, beforeShow: function (a) {
			var d = b.current, e = d.title, c = a.type;
			f.isFunction(e) && (e = e.call(d.element, d));
			if (q(e) && "" !== f.trim(e)) {
				d = f('<div class="fancybox-title fancybox-title-' + c + '-wrap">' + e + "</div>");
				switch (c) {
					case "inside":
						c = b.skin;
						break;
					case "outside":
						c = b.wrap;
						break;
					case "over":
						c = b.inner;
						break;
					default:
						c = b.skin, d.appendTo("body"), I && d.width(d.width()), d.wrapInner('<span class="child"></span>'), b.current.margin[2] += Math.abs(l(d.css("margin-bottom")))
				}
				d["top" === a.position ? "prependTo" : "appendTo"](c)
			}
		}
	};
	f.fn.fancybox = function (a) {
		var d, e = f(this), c = this.selector || "", k = function (g) {
			var h = f(this).blur(), j = d, k, l;
			!g.ctrlKey && (!g.altKey && !g.shiftKey && !g.metaKey) && !h.is(".fancybox-wrap") && (k = a.groupAttr || "data-fancybox-group", l = h.attr(k), l || (k = "rel", l = h.get(0)[k]), l && ("" !== l && "nofollow" !== l) && (h = c.length ? f(c) : e, h = h.filter("[" + k + '="' + l + '"]'), j = h.index(this)), a.index = j, !1 !== b.open(h, a) && g.preventDefault())
		};
		a = a || {};
		d = a.index || 0;
		!c || !1 === a.live ? e.unbind("click.fb-start").bind("click.fb-start", k) : p.undelegate(c, "click.fb-start").delegate(c + ":not('.fancybox-item, .fancybox-nav')", "click.fb-start", k);
		this.filter("[data-fancybox-start=1]").trigger("click");
		return this
	};
	p.ready(function () {
		var a, d;
		f.scrollbarWidth === v && (f.scrollbarWidth = function () {
			var a = f('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"), b = a.children(), b = b.innerWidth() - b.height(99).innerWidth();
			a.remove();
			return b
		});
		if (f.support.fixedPosition === v) {
			a = f.support;
			d = f('<div style="position:fixed;top:20px;"></div>').appendTo("body");
			var e = 20 === d[0].offsetTop || 15 === d[0].offsetTop;
			d.remove();
			a.fixedPosition = e
		}
		f.extend(b.defaults, {scrollbarWidth: f.scrollbarWidth(), fixed: f.support.fixedPosition, parent: f("body")});
		a = f(r).width();
		J.addClass("fancybox-lock-test");
		d = f(r).width();
		J.removeClass("fancybox-lock-test");
		f("<style type='text/css'>.fancybox-margin{margin-right:" + (d - a) + "px;}</style>").appendTo("head")
	})
})(window, document, jQuery);

/*! Hammer.JS - v2.0.4 - 2014-09-28
 * http://hammerjs.github.io/
 *
 * Copyright (c) 2014 Jorik Tangelder;
 * Licensed under the MIT license */
if (Object.create) {
	!function (a, b, c, d) {
		"use strict";
		function e(a, b, c) {
			return setTimeout(k(a, c), b)
		}

		function f(a, b, c) {
			return Array.isArray(a) ? (g(a, c[b], c), !0) : !1
		}

		function g(a, b, c) {
			var e;
			if (a)if (a.forEach)a.forEach(b, c); else if (a.length !== d)for (e = 0; e < a.length;)b.call(c, a[e], e, a), e++; else for (e in a)a.hasOwnProperty(e) && b.call(c, a[e], e, a)
		}

		function h(a, b, c) {
			for (var e = Object.keys(b), f = 0; f < e.length;)(!c || c && a[e[f]] === d) && (a[e[f]] = b[e[f]]), f++;
			return a
		}

		function i(a, b) {
			return h(a, b, !0)
		}

		function j(a, b, c) {
			var d, e = b.prototype;
			d = a.prototype = Object.create(e), d.constructor = a, d._super = e, c && h(d, c)
		}

		function k(a, b) {
			return function () {
				return a.apply(b, arguments)
			}
		}

		function l(a, b) {
			return typeof a == kb ? a.apply(b ? b[0] || d : d, b) : a
		}

		function m(a, b) {
			return a === d ? b : a
		}

		function n(a, b, c) {
			g(r(b), function (b) {
				a.addEventListener(b, c, !1)
			})
		}

		function o(a, b, c) {
			g(r(b), function (b) {
				a.removeEventListener(b, c, !1)
			})
		}

		function p(a, b) {
			for (; a;) {
				if (a == b)return !0;
				a = a.parentNode
			}
			return !1
		}

		function q(a, b) {
			return a.indexOf(b) > -1
		}

		function r(a) {
			return a.trim().split(/\s+/g)
		}

		function s(a, b, c) {
			if (a.indexOf && !c)return a.indexOf(b);
			for (var d = 0; d < a.length;) {
				if (c && a[d][c] == b || !c && a[d] === b)return d;
				d++
			}
			return -1
		}

		function t(a) {
			return Array.prototype.slice.call(a, 0)
		}

		function u(a, b, c) {
			for (var d = [], e = [], f = 0; f < a.length;) {
				var g = b ? a[f][b] : a[f];
				s(e, g) < 0 && d.push(a[f]), e[f] = g, f++
			}
			return c && (d = b ? d.sort(function (a, c) {
				return a[b] > c[b]
			}) : d.sort()), d
		}

		function v(a, b) {
			for (var c, e, f = b[0].toUpperCase() + b.slice(1), g = 0; g < ib.length;) {
				if (c = ib[g], e = c ? c + f : b, e in a)return e;
				g++
			}
			return d
		}

		function w() {
			return ob++
		}

		function x(a) {
			var b = a.ownerDocument;
			return b.defaultView || b.parentWindow
		}

		function y(a, b) {
			var c = this;
			this.manager = a, this.callback = b, this.element = a.element, this.target = a.options.inputTarget, this.domHandler = function (b) {
				l(a.options.enable, [a]) && c.handler(b)
			}, this.init()
		}

		function z(a) {
			var b, c = a.options.inputClass;
			return new (b = c ? c : rb ? N : sb ? Q : qb ? S : M)(a, A)
		}

		function A(a, b, c) {
			var d = c.pointers.length, e = c.changedPointers.length, f = b & yb && d - e === 0, g = b & (Ab | Bb) && d - e === 0;
			c.isFirst = !!f, c.isFinal = !!g, f && (a.session = {}), c.eventType = b, B(a, c), a.emit("hammer.input", c), a.recognize(c), a.session.prevInput = c
		}

		function B(a, b) {
			var c = a.session, d = b.pointers, e = d.length;
			c.firstInput || (c.firstInput = E(b)), e > 1 && !c.firstMultiple ? c.firstMultiple = E(b) : 1 === e && (c.firstMultiple = !1);
			var f = c.firstInput, g = c.firstMultiple, h = g ? g.center : f.center, i = b.center = F(d);
			b.timeStamp = nb(), b.deltaTime = b.timeStamp - f.timeStamp, b.angle = J(h, i), b.distance = I(h, i), C(c, b), b.offsetDirection = H(b.deltaX, b.deltaY), b.scale = g ? L(g.pointers, d) : 1, b.rotation = g ? K(g.pointers, d) : 0, D(c, b);
			var j = a.element;
			p(b.srcEvent.target, j) && (j = b.srcEvent.target), b.target = j
		}

		function C(a, b) {
			var c = b.center, d = a.offsetDelta || {}, e = a.prevDelta || {}, f = a.prevInput || {};
			(b.eventType === yb || f.eventType === Ab) && (e = a.prevDelta = {
				x: f.deltaX || 0,
				y: f.deltaY || 0
			}, d = a.offsetDelta = {x: c.x, y: c.y}), b.deltaX = e.x + (c.x - d.x), b.deltaY = e.y + (c.y - d.y)
		}

		function D(a, b) {
			var c, e, f, g, h = a.lastInterval || b, i = b.timeStamp - h.timeStamp;
			if (b.eventType != Bb && (i > xb || h.velocity === d)) {
				var j = h.deltaX - b.deltaX, k = h.deltaY - b.deltaY, l = G(i, j, k);
				e = l.x, f = l.y, c = mb(l.x) > mb(l.y) ? l.x : l.y, g = H(j, k), a.lastInterval = b
			} else c = h.velocity, e = h.velocityX, f = h.velocityY, g = h.direction;
			b.velocity = c, b.velocityX = e, b.velocityY = f, b.direction = g
		}

		function E(a) {
			for (var b = [], c = 0; c < a.pointers.length;)b[c] = {
				clientX: lb(a.pointers[c].clientX),
				clientY: lb(a.pointers[c].clientY)
			}, c++;
			return {timeStamp: nb(), pointers: b, center: F(b), deltaX: a.deltaX, deltaY: a.deltaY}
		}

		function F(a) {
			var b = a.length;
			if (1 === b)return {x: lb(a[0].clientX), y: lb(a[0].clientY)};
			for (var c = 0, d = 0, e = 0; b > e;)c += a[e].clientX, d += a[e].clientY, e++;
			return {x: lb(c / b), y: lb(d / b)}
		}

		function G(a, b, c) {
			return {x: b / a || 0, y: c / a || 0}
		}

		function H(a, b) {
			return a === b ? Cb : mb(a) >= mb(b) ? a > 0 ? Db : Eb : b > 0 ? Fb : Gb
		}

		function I(a, b, c) {
			c || (c = Kb);
			var d = b[c[0]] - a[c[0]], e = b[c[1]] - a[c[1]];
			return Math.sqrt(d * d + e * e)
		}

		function J(a, b, c) {
			c || (c = Kb);
			var d = b[c[0]] - a[c[0]], e = b[c[1]] - a[c[1]];
			return 180 * Math.atan2(e, d) / Math.PI
		}

		function K(a, b) {
			return J(b[1], b[0], Lb) - J(a[1], a[0], Lb)
		}

		function L(a, b) {
			return I(b[0], b[1], Lb) / I(a[0], a[1], Lb)
		}

		function M() {
			this.evEl = Nb, this.evWin = Ob, this.allow = !0, this.pressed = !1, y.apply(this, arguments)
		}

		function N() {
			this.evEl = Rb, this.evWin = Sb, y.apply(this, arguments), this.store = this.manager.session.pointerEvents = []
		}

		function O() {
			this.evTarget = Ub, this.evWin = Vb, this.started = !1, y.apply(this, arguments)
		}

		function P(a, b) {
			var c = t(a.touches), d = t(a.changedTouches);
			return b & (Ab | Bb) && (c = u(c.concat(d), "identifier", !0)), [c, d]
		}

		function Q() {
			this.evTarget = Xb, this.targetIds = {}, y.apply(this, arguments)
		}

		function R(a, b) {
			var c = t(a.touches), d = this.targetIds;
			if (b & (yb | zb) && 1 === c.length)return d[c[0].identifier] = !0, [c, c];
			var e, f, g = t(a.changedTouches), h = [], i = this.target;
			if (f = c.filter(function (a) {
					return p(a.target, i)
				}), b === yb)for (e = 0; e < f.length;)d[f[e].identifier] = !0, e++;
			for (e = 0; e < g.length;)d[g[e].identifier] && h.push(g[e]), b & (Ab | Bb) && delete d[g[e].identifier], e++;
			return h.length ? [u(f.concat(h), "identifier", !0), h] : void 0
		}

		function S() {
			y.apply(this, arguments);
			var a = k(this.handler, this);
			this.touch = new Q(this.manager, a), this.mouse = new M(this.manager, a)
		}

		function T(a, b) {
			this.manager = a, this.set(b)
		}

		function U(a) {
			if (q(a, bc))return bc;
			var b = q(a, cc), c = q(a, dc);
			return b && c ? cc + " " + dc : b || c ? b ? cc : dc : q(a, ac) ? ac : _b
		}

		function V(a) {
			this.id = w(), this.manager = null, this.options = i(a || {}, this.defaults), this.options.enable = m(this.options.enable, !0), this.state = ec, this.simultaneous = {}, this.requireFail = []
		}

		function W(a) {
			return a & jc ? "cancel" : a & hc ? "end" : a & gc ? "move" : a & fc ? "start" : ""
		}

		function X(a) {
			return a == Gb ? "down" : a == Fb ? "up" : a == Db ? "left" : a == Eb ? "right" : ""
		}

		function Y(a, b) {
			var c = b.manager;
			return c ? c.get(a) : a
		}

		function Z() {
			V.apply(this, arguments)
		}

		function $() {
			Z.apply(this, arguments), this.pX = null, this.pY = null
		}

		function _() {
			Z.apply(this, arguments)
		}

		function ab() {
			V.apply(this, arguments), this._timer = null, this._input = null
		}

		function bb() {
			Z.apply(this, arguments)
		}

		function cb() {
			Z.apply(this, arguments)
		}

		function db() {
			V.apply(this, arguments), this.pTime = !1, this.pCenter = !1, this._timer = null, this._input = null, this.count = 0
		}

		function eb(a, b) {
			return b = b || {}, b.recognizers = m(b.recognizers, eb.defaults.preset), new fb(a, b)
		}

		function fb(a, b) {
			b = b || {}, this.options = i(b, eb.defaults), this.options.inputTarget = this.options.inputTarget || a, this.handlers = {}, this.session = {}, this.recognizers = [], this.element = a, this.input = z(this), this.touchAction = new T(this, this.options.touchAction), gb(this, !0), g(b.recognizers, function (a) {
				var b = this.add(new a[0](a[1]));
				a[2] && b.recognizeWith(a[2]), a[3] && b.requireFailure(a[3])
			}, this)
		}

		function gb(a, b) {
			var c = a.element;
			g(a.options.cssProps, function (a, d) {
				c.style[v(c.style, d)] = b ? a : ""
			})
		}

		function hb(a, c) {
			var d = b.createEvent("Event");
			d.initEvent(a, !0, !0), d.gesture = c, c.target.dispatchEvent(d)
		}

		var ib = ["", "webkit", "moz", "MS", "ms", "o"], jb = b.createElement("div"), kb = "function", lb = Math.round, mb = Math.abs, nb = Date.now, ob = 1, pb = /mobile|tablet|ip(ad|hone|od)|android/i, qb = "ontouchstart" in a, rb = v(a, "PointerEvent") !== d, sb = qb && pb.test(navigator.userAgent), tb = "touch", ub = "pen", vb = "mouse", wb = "kinect", xb = 25, yb = 1, zb = 2, Ab = 4, Bb = 8, Cb = 1, Db = 2, Eb = 4, Fb = 8, Gb = 16, Hb = Db | Eb, Ib = Fb | Gb, Jb = Hb | Ib, Kb = ["x", "y"], Lb = ["clientX", "clientY"];
		y.prototype = {
			handler: function () {
			}, init: function () {
				this.evEl && n(this.element, this.evEl, this.domHandler), this.evTarget && n(this.target, this.evTarget, this.domHandler), this.evWin && n(x(this.element), this.evWin, this.domHandler)
			}, destroy: function () {
				this.evEl && o(this.element, this.evEl, this.domHandler), this.evTarget && o(this.target, this.evTarget, this.domHandler), this.evWin && o(x(this.element), this.evWin, this.domHandler)
			}
		};
		var Mb = {mousedown: yb, mousemove: zb, mouseup: Ab}, Nb = "mousedown", Ob = "mousemove mouseup";
		j(M, y, {
			handler: function (a) {
				var b = Mb[a.type];
				b & yb && 0 === a.button && (this.pressed = !0), b & zb && 1 !== a.which && (b = Ab), this.pressed && this.allow && (b & Ab && (this.pressed = !1), this.callback(this.manager, b, {
					pointers: [a],
					changedPointers: [a],
					pointerType: vb,
					srcEvent: a
				}))
			}
		});
		var Pb = {pointerdown: yb, pointermove: zb, pointerup: Ab, pointercancel: Bb, pointerout: Bb}, Qb = {
			2: tb,
			3: ub,
			4: vb,
			5: wb
		}, Rb = "pointerdown", Sb = "pointermove pointerup pointercancel";
		a.MSPointerEvent && (Rb = "MSPointerDown", Sb = "MSPointerMove MSPointerUp MSPointerCancel"), j(N, y, {
			handler: function (a) {
				var b = this.store, c = !1, d = a.type.toLowerCase().replace("ms", ""), e = Pb[d], f = Qb[a.pointerType] || a.pointerType, g = f == tb, h = s(b, a.pointerId, "pointerId");
				e & yb && (0 === a.button || g) ? 0 > h && (b.push(a), h = b.length - 1) : e & (Ab | Bb) && (c = !0), 0 > h || (b[h] = a, this.callback(this.manager, e, {
					pointers: b,
					changedPointers: [a],
					pointerType: f,
					srcEvent: a
				}), c && b.splice(h, 1))
			}
		});
		var Tb = {
			touchstart: yb,
			touchmove: zb,
			touchend: Ab,
			touchcancel: Bb
		}, Ub = "touchstart", Vb = "touchstart touchmove touchend touchcancel";
		j(O, y, {
			handler: function (a) {
				var b = Tb[a.type];
				if (b === yb && (this.started = !0), this.started) {
					var c = P.call(this, a, b);
					b & (Ab | Bb) && c[0].length - c[1].length === 0 && (this.started = !1), this.callback(this.manager, b, {
						pointers: c[0],
						changedPointers: c[1],
						pointerType: tb,
						srcEvent: a
					})
				}
			}
		});
		var Wb = {
			touchstart: yb,
			touchmove: zb,
			touchend: Ab,
			touchcancel: Bb
		}, Xb = "touchstart touchmove touchend touchcancel";
		j(Q, y, {
			handler: function (a) {
				var b = Wb[a.type], c = R.call(this, a, b);
				c && this.callback(this.manager, b, {
					pointers: c[0],
					changedPointers: c[1],
					pointerType: tb,
					srcEvent: a
				})
			}
		}), j(S, y, {
			handler: function (a, b, c) {
				var d = c.pointerType == tb, e = c.pointerType == vb;
				if (d)this.mouse.allow = !1; else if (e && !this.mouse.allow)return;
				b & (Ab | Bb) && (this.mouse.allow = !0), this.callback(a, b, c)
			}, destroy: function () {
				this.touch.destroy(), this.mouse.destroy()
			}
		});
		var Yb = v(jb.style, "touchAction"), Zb = Yb !== d, $b = "compute", _b = "auto", ac = "manipulation", bc = "none", cc = "pan-x", dc = "pan-y";
		T.prototype = {
			set: function (a) {
				a == $b && (a = this.compute()), Zb && (this.manager.element.style[Yb] = a), this.actions = a.toLowerCase().trim()
			}, update: function () {
				this.set(this.manager.options.touchAction)
			}, compute: function () {
				var a = [];
				return g(this.manager.recognizers, function (b) {
					l(b.options.enable, [b]) && (a = a.concat(b.getTouchAction()))
				}), U(a.join(" "))
			}, preventDefaults: function (a) {
				if (!Zb) {
					var b = a.srcEvent, c = a.offsetDirection;
					if (this.manager.session.prevented)return void b.preventDefault();
					var d = this.actions, e = q(d, bc), f = q(d, dc), g = q(d, cc);
					return e || f && c & Hb || g && c & Ib ? this.preventSrc(b) : void 0
				}
			}, preventSrc: function (a) {
				this.manager.session.prevented = !0, a.preventDefault()
			}
		};
		var ec = 1, fc = 2, gc = 4, hc = 8, ic = hc, jc = 16, kc = 32;
		V.prototype = {
			defaults: {}, set: function (a) {
				return h(this.options, a), this.manager && this.manager.touchAction.update(), this
			}, recognizeWith: function (a) {
				if (f(a, "recognizeWith", this))return this;
				var b = this.simultaneous;
				return a = Y(a, this), b[a.id] || (b[a.id] = a, a.recognizeWith(this)), this
			}, dropRecognizeWith: function (a) {
				return f(a, "dropRecognizeWith", this) ? this : (a = Y(a, this), delete this.simultaneous[a.id], this)
			}, requireFailure: function (a) {
				if (f(a, "requireFailure", this))return this;
				var b = this.requireFail;
				return a = Y(a, this), -1 === s(b, a) && (b.push(a), a.requireFailure(this)), this
			}, dropRequireFailure: function (a) {
				if (f(a, "dropRequireFailure", this))return this;
				a = Y(a, this);
				var b = s(this.requireFail, a);
				return b > -1 && this.requireFail.splice(b, 1), this
			}, hasRequireFailures: function () {
				return this.requireFail.length > 0
			}, canRecognizeWith: function (a) {
				return !!this.simultaneous[a.id]
			}, emit: function (a) {
				function b(b) {
					c.manager.emit(c.options.event + (b ? W(d) : ""), a)
				}

				var c = this, d = this.state;
				hc > d && b(!0), b(), d >= hc && b(!0)
			}, tryEmit: function (a) {
				return this.canEmit() ? this.emit(a) : void(this.state = kc)
			}, canEmit: function () {
				for (var a = 0; a < this.requireFail.length;) {
					if (!(this.requireFail[a].state & (kc | ec)))return !1;
					a++
				}
				return !0
			}, recognize: function (a) {
				var b = h({}, a);
				return l(this.options.enable, [this, b]) ? (this.state & (ic | jc | kc) && (this.state = ec), this.state = this.process(b), void(this.state & (fc | gc | hc | jc) && this.tryEmit(b))) : (this.reset(), void(this.state = kc))
			}, process: function () {
			}, getTouchAction: function () {
			}, reset: function () {
			}
		}, j(Z, V, {
			defaults: {pointers: 1}, attrTest: function (a) {
				var b = this.options.pointers;
				return 0 === b || a.pointers.length === b
			}, process: function (a) {
				var b = this.state, c = a.eventType, d = b & (fc | gc), e = this.attrTest(a);
				return d && (c & Bb || !e) ? b | jc : d || e ? c & Ab ? b | hc : b & fc ? b | gc : fc : kc
			}
		}), j($, Z, {
			defaults: {event: "pan", threshold: 10, pointers: 1, direction: Jb}, getTouchAction: function () {
				var a = this.options.direction, b = [];
				return a & Hb && b.push(dc), a & Ib && b.push(cc), b
			}, directionTest: function (a) {
				var b = this.options, c = !0, d = a.distance, e = a.direction, f = a.deltaX, g = a.deltaY;
				return e & b.direction || (b.direction & Hb ? (e = 0 === f ? Cb : 0 > f ? Db : Eb, c = f != this.pX, d = Math.abs(a.deltaX)) : (e = 0 === g ? Cb : 0 > g ? Fb : Gb, c = g != this.pY, d = Math.abs(a.deltaY))), a.direction = e, c && d > b.threshold && e & b.direction
			}, attrTest: function (a) {
				return Z.prototype.attrTest.call(this, a) && (this.state & fc || !(this.state & fc) && this.directionTest(a))
			}, emit: function (a) {
				this.pX = a.deltaX, this.pY = a.deltaY;
				var b = X(a.direction);
				b && this.manager.emit(this.options.event + b, a), this._super.emit.call(this, a)
			}
		}), j(_, Z, {
			defaults: {event: "pinch", threshold: 0, pointers: 2}, getTouchAction: function () {
				return [bc]
			}, attrTest: function (a) {
				return this._super.attrTest.call(this, a) && (Math.abs(a.scale - 1) > this.options.threshold || this.state & fc)
			}, emit: function (a) {
				if (this._super.emit.call(this, a), 1 !== a.scale) {
					var b = a.scale < 1 ? "in" : "out";
					this.manager.emit(this.options.event + b, a)
				}
			}
		}), j(ab, V, {
			defaults: {event: "press", pointers: 1, time: 500, threshold: 5}, getTouchAction: function () {
				return [_b]
			}, process: function (a) {
				var b = this.options, c = a.pointers.length === b.pointers, d = a.distance < b.threshold, f = a.deltaTime > b.time;
				if (this._input = a, !d || !c || a.eventType & (Ab | Bb) && !f)this.reset(); else if (a.eventType & yb)this.reset(), this._timer = e(function () {
					this.state = ic, this.tryEmit()
				}, b.time, this); else if (a.eventType & Ab)return ic;
				return kc
			}, reset: function () {
				clearTimeout(this._timer)
			}, emit: function (a) {
				this.state === ic && (a && a.eventType & Ab ? this.manager.emit(this.options.event + "up", a) : (this._input.timeStamp = nb(), this.manager.emit(this.options.event, this._input)))
			}
		}), j(bb, Z, {
			defaults: {event: "rotate", threshold: 0, pointers: 2}, getTouchAction: function () {
				return [bc]
			}, attrTest: function (a) {
				return this._super.attrTest.call(this, a) && (Math.abs(a.rotation) > this.options.threshold || this.state & fc)
			}
		}), j(cb, Z, {
			defaults: {event: "swipe", threshold: 10, velocity: .65, direction: Hb | Ib, pointers: 1},
			getTouchAction: function () {
				return $.prototype.getTouchAction.call(this)
			},
			attrTest: function (a) {
				var b, c = this.options.direction;
				return c & (Hb | Ib) ? b = a.velocity : c & Hb ? b = a.velocityX : c & Ib && (b = a.velocityY), this._super.attrTest.call(this, a) && c & a.direction && a.distance > this.options.threshold && mb(b) > this.options.velocity && a.eventType & Ab
			},
			emit: function (a) {
				var b = X(a.direction);
				b && this.manager.emit(this.options.event + b, a), this.manager.emit(this.options.event, a)
			}
		}), j(db, V, {
			defaults: {
				event: "tap",
				pointers: 1,
				taps: 1,
				interval: 300,
				time: 250,
				threshold: 2,
				posThreshold: 10
			}, getTouchAction: function () {
				return [ac]
			}, process: function (a) {
				var b = this.options, c = a.pointers.length === b.pointers, d = a.distance < b.threshold, f = a.deltaTime < b.time;
				if (this.reset(), a.eventType & yb && 0 === this.count)return this.failTimeout();
				if (d && f && c) {
					if (a.eventType != Ab)return this.failTimeout();
					var g = this.pTime ? a.timeStamp - this.pTime < b.interval : !0, h = !this.pCenter || I(this.pCenter, a.center) < b.posThreshold;
					this.pTime = a.timeStamp, this.pCenter = a.center, h && g ? this.count += 1 : this.count = 1, this._input = a;
					var i = this.count % b.taps;
					if (0 === i)return this.hasRequireFailures() ? (this._timer = e(function () {
						this.state = ic, this.tryEmit()
					}, b.interval, this), fc) : ic
				}
				return kc
			}, failTimeout: function () {
				return this._timer = e(function () {
					this.state = kc
				}, this.options.interval, this), kc
			}, reset: function () {
				clearTimeout(this._timer)
			}, emit: function () {
				this.state == ic && (this._input.tapCount = this.count, this.manager.emit(this.options.event, this._input))
			}
		}), eb.VERSION = "2.0.4", eb.defaults = {
			domEvents: !1,
			touchAction: $b,
			enable: !0,
			inputTarget: null,
			inputClass: null,
			preset: [[bb, {enable: !1}], [_, {enable: !1}, ["rotate"]], [cb, {direction: Hb}], [$, {direction: Hb}, ["swipe"]], [db], [db, {
				event: "doubletap",
				taps: 2
			}, ["tap"]], [ab]],
			cssProps: {
				userSelect: "none",
				touchSelect: "none",
				touchCallout: "none",
				contentZooming: "none",
				userDrag: "none",
				tapHighlightColor: "rgba(0,0,0,0)"
			}
		};
		var lc = 1, mc = 2;
		fb.prototype = {
			set: function (a) {
				return h(this.options, a), a.touchAction && this.touchAction.update(), a.inputTarget && (this.input.destroy(), this.input.target = a.inputTarget, this.input.init()), this
			}, stop: function (a) {
				this.session.stopped = a ? mc : lc
			}, recognize: function (a) {
				var b = this.session;
				if (!b.stopped) {
					this.touchAction.preventDefaults(a);
					var c, d = this.recognizers, e = b.curRecognizer;
					(!e || e && e.state & ic) && (e = b.curRecognizer = null);
					for (var f = 0; f < d.length;)c = d[f], b.stopped === mc || e && c != e && !c.canRecognizeWith(e) ? c.reset() : c.recognize(a), !e && c.state & (fc | gc | hc) && (e = b.curRecognizer = c), f++
				}
			}, get: function (a) {
				if (a instanceof V)return a;
				for (var b = this.recognizers, c = 0; c < b.length; c++)if (b[c].options.event == a)return b[c];
				return null
			}, add: function (a) {
				if (f(a, "add", this))return this;
				var b = this.get(a.options.event);
				return b && this.remove(b), this.recognizers.push(a), a.manager = this, this.touchAction.update(), a
			}, remove: function (a) {
				if (f(a, "remove", this))return this;
				var b = this.recognizers;
				return a = this.get(a), b.splice(s(b, a), 1), this.touchAction.update(), this
			}, on: function (a, b) {
				var c = this.handlers;
				return g(r(a), function (a) {
					c[a] = c[a] || [], c[a].push(b)
				}), this
			}, off: function (a, b) {
				var c = this.handlers;
				return g(r(a), function (a) {
					b ? c[a].splice(s(c[a], b), 1) : delete c[a]
				}), this
			}, emit: function (a, b) {
				this.options.domEvents && hb(a, b);
				var c = this.handlers[a] && this.handlers[a].slice();
				if (c && c.length) {
					b.type = a, b.preventDefault = function () {
						b.srcEvent.preventDefault()
					};
					for (var d = 0; d < c.length;)c[d](b), d++
				}
			}, destroy: function () {
				this.element && gb(this, !1), this.handlers = {}, this.session = {}, this.input.destroy(), this.element = null
			}
		}, h(eb, {
			INPUT_START: yb,
			INPUT_MOVE: zb,
			INPUT_END: Ab,
			INPUT_CANCEL: Bb,
			STATE_POSSIBLE: ec,
			STATE_BEGAN: fc,
			STATE_CHANGED: gc,
			STATE_ENDED: hc,
			STATE_RECOGNIZED: ic,
			STATE_CANCELLED: jc,
			STATE_FAILED: kc,
			DIRECTION_NONE: Cb,
			DIRECTION_LEFT: Db,
			DIRECTION_RIGHT: Eb,
			DIRECTION_UP: Fb,
			DIRECTION_DOWN: Gb,
			DIRECTION_HORIZONTAL: Hb,
			DIRECTION_VERTICAL: Ib,
			DIRECTION_ALL: Jb,
			Manager: fb,
			Input: y,
			TouchAction: T,
			TouchInput: Q,
			MouseInput: M,
			PointerEventInput: N,
			TouchMouseInput: S,
			SingleTouchInput: O,
			Recognizer: V,
			AttrRecognizer: Z,
			Tap: db,
			Pan: $,
			Swipe: cb,
			Pinch: _,
			Rotate: bb,
			Press: ab,
			on: n,
			off: o,
			each: g,
			merge: i,
			extend: h,
			inherit: j,
			bindFn: k,
			prefixed: v
		}), typeof define == kb && define.amd ? define(function () {
			return eb
		}) : "undefined" != typeof module && module.exports ? module.exports = eb : a[c] = eb
	}(window, document, "Hammer");
}

/*! Picturefill - v3.0.1 - 2015-09-30
 * http://scottjehl.github.io/picturefill
 * Copyright (c) 2015 https://github.com/scottjehl/picturefill/blob/master/Authors.txt; Licensed MIT
 */
!function (a) {
	var b = navigator.userAgent;
	a.HTMLPictureElement && /ecko/.test(b) && b.match(/rv\:(\d+)/) && RegExp.$1 < 41 && addEventListener("resize", function () {
		var b, c = document.createElement("source"), d = function (a) {
			var b, d, e = a.parentNode;
			"PICTURE" === e.nodeName.toUpperCase() ? (b = c.cloneNode(), e.insertBefore(b, e.firstElementChild), setTimeout(function () {
				e.removeChild(b)
			})) : (!a._pfLastSize || a.offsetWidth > a._pfLastSize) && (a._pfLastSize = a.offsetWidth, d = a.sizes, a.sizes += ",100vw", setTimeout(function () {
				a.sizes = d
			}))
		}, e = function () {
			var a, b = document.querySelectorAll("picture > img, img[srcset][sizes]");
			for (a = 0; a < b.length; a++)d(b[a])
		}, f = function () {
			clearTimeout(b), b = setTimeout(e, 99)
		}, g = a.matchMedia && matchMedia("(orientation: landscape)"), h = function () {
			f(), g && g.addListener && g.addListener(f)
		};
		return c.srcset = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==", /^[c|i]|d$/.test(document.readyState || "") ? h() : document.addEventListener("DOMContentLoaded", h), f
	}())
}(window), function (a, b, c) {
	"use strict";
	function d(a) {
		return " " === a || "	" === a || "\n" === a || "\f" === a || "\r" === a
	}

	function e(b, c) {
		var d = new a.Image;
		return d.onerror = function () {
			z[b] = !1, aa()
		}, d.onload = function () {
			z[b] = 1 === d.width, aa()
		}, d.src = c, "pending"
	}

	function f() {
		L = !1, O = a.devicePixelRatio, M = {}, N = {}, s.DPR = O || 1, P.width = Math.max(a.innerWidth || 0, y.clientWidth), P.height = Math.max(a.innerHeight || 0, y.clientHeight), P.vw = P.width / 100, P.vh = P.height / 100, r = [P.height, P.width, O].join("-"), P.em = s.getEmValue(), P.rem = P.em
	}

	function g(a, b, c, d) {
		var e, f, g, h;
		return "saveData" === A.algorithm ? a > 2.7 ? h = c + 1 : (f = b - c, e = Math.pow(a - .6, 1.5), g = f * e, d && (g += .1 * e), h = a + g) : h = c > 1 ? Math.sqrt(a * b) : a, h > c
	}

	function h(a) {
		var b, c = s.getSet(a), d = !1;
		"pending" !== c && (d = r, c && (b = s.setRes(c), s.applySetCandidate(b, a))), a[s.ns].evaled = d
	}

	function i(a, b) {
		return a.res - b.res
	}

	function j(a, b, c) {
		var d;
		return !c && b && (c = a[s.ns].sets, c = c && c[c.length - 1]), d = k(b, c), d && (b = s.makeUrl(b), a[s.ns].curSrc = b, a[s.ns].curCan = d, d.res || _(d, d.set.sizes)), d
	}

	function k(a, b) {
		var c, d, e;
		if (a && b)for (e = s.parseSet(b), a = s.makeUrl(a), c = 0; c < e.length; c++)if (a === s.makeUrl(e[c].url)) {
			d = e[c];
			break
		}
		return d
	}

	function l(a, b) {
		var c, d, e, f, g = a.getElementsByTagName("source");
		for (c = 0, d = g.length; d > c; c++)e = g[c], e[s.ns] = !0, f = e.getAttribute("srcset"), f && b.push({
			srcset: f,
			media: e.getAttribute("media"),
			type: e.getAttribute("type"),
			sizes: e.getAttribute("sizes")
		})
	}

	function m(a, b) {
		function c(b) {
			var c, d = b.exec(a.substring(m));
			return d ? (c = d[0], m += c.length, c) : void 0
		}

		function e() {
			var a, c, d, e, f, i, j, k, l, m = !1, o = {};
			for (e = 0; e < h.length; e++)f = h[e], i = f[f.length - 1], j = f.substring(0, f.length - 1), k = parseInt(j, 10), l = parseFloat(j), W.test(j) && "w" === i ? ((a || c) && (m = !0), 0 === k ? m = !0 : a = k) : X.test(j) && "x" === i ? ((a || c || d) && (m = !0), 0 > l ? m = !0 : c = l) : W.test(j) && "h" === i ? ((d || c) && (m = !0), 0 === k ? m = !0 : d = k) : m = !0;
			m || (o.url = g, a && (o.w = a), c && (o.d = c), d && (o.h = d), d || c || a || (o.d = 1), 1 === o.d && (b.has1x = !0), o.set = b, n.push(o))
		}

		function f() {
			for (c(S), i = "", j = "in descriptor"; ;) {
				if (k = a.charAt(m), "in descriptor" === j)if (d(k))i && (h.push(i), i = "", j = "after descriptor"); else {
					if ("," === k)return m += 1, i && h.push(i), void e();
					if ("(" === k)i += k, j = "in parens"; else {
						if ("" === k)return i && h.push(i), void e();
						i += k
					}
				} else if ("in parens" === j)if (")" === k)i += k, j = "in descriptor"; else {
					if ("" === k)return h.push(i), void e();
					i += k
				} else if ("after descriptor" === j)if (d(k)); else {
					if ("" === k)return void e();
					j = "in descriptor", m -= 1
				}
				m += 1
			}
		}

		for (var g, h, i, j, k, l = a.length, m = 0, n = []; ;) {
			if (c(T), m >= l)return n;
			g = c(U), h = [], "," === g.slice(-1) ? (g = g.replace(V, ""), e()) : f()
		}
	}

	function n(a) {
		function b(a) {
			function b() {
				f && (g.push(f), f = "")
			}

			function c() {
				g[0] && (h.push(g), g = [])
			}

			for (var e, f = "", g = [], h = [], i = 0, j = 0, k = !1; ;) {
				if (e = a.charAt(j), "" === e)return b(), c(), h;
				if (k) {
					if ("*" === e && "/" === a[j + 1]) {
						k = !1, j += 2, b();
						continue
					}
					j += 1
				} else {
					if (d(e)) {
						if (a.charAt(j - 1) && d(a.charAt(j - 1)) || !f) {
							j += 1;
							continue
						}
						if (0 === i) {
							b(), j += 1;
							continue
						}
						e = " "
					} else if ("(" === e)i += 1; else if (")" === e)i -= 1; else {
						if ("," === e) {
							b(), c(), j += 1;
							continue
						}
						if ("/" === e && "*" === a.charAt(j + 1)) {
							k = !0, j += 2;
							continue
						}
					}
					f += e, j += 1
				}
			}
		}

		function c(a) {
			return k.test(a) && parseFloat(a) >= 0 ? !0 : l.test(a) ? !0 : "0" === a || "-0" === a || "+0" === a ? !0 : !1
		}

		var e, f, g, h, i, j, k = /^(?:[+-]?[0-9]+|[0-9]*\.[0-9]+)(?:[eE][+-]?[0-9]+)?(?:ch|cm|em|ex|in|mm|pc|pt|px|rem|vh|vmin|vmax|vw)$/i, l = /^calc\((?:[0-9a-z \.\+\-\*\/\(\)]+)\)$/i;
		for (f = b(a), g = f.length, e = 0; g > e; e++)if (h = f[e], i = h[h.length - 1], c(i)) {
			if (j = i, h.pop(), 0 === h.length)return j;
			if (h = h.join(" "), s.matchesMedia(h))return j
		}
		return "100vw"
	}

	b.createElement("picture");
	var o, p, q, r, s = {}, t = function () {
	}, u = b.createElement("img"), v = u.getAttribute, w = u.setAttribute, x = u.removeAttribute, y = b.documentElement, z = {}, A = {algorithm: ""}, B = "data-pfsrc", C = B + "set", D = navigator.userAgent, E = /rident/.test(D) || /ecko/.test(D) && D.match(/rv\:(\d+)/) && RegExp.$1 > 35, F = "currentSrc", G = /\s+\+?\d+(e\d+)?w/, H = /(\([^)]+\))?\s*(.+)/, I = a.picturefillCFG, J = "position:absolute;left:0;visibility:hidden;display:block;padding:0;border:none;font-size:1em;width:1em;overflow:hidden;clip:rect(0px, 0px, 0px, 0px)", K = "font-size:100%!important;", L = !0, M = {}, N = {}, O = a.devicePixelRatio, P = {
		px: 1,
		"in": 96
	}, Q = b.createElement("a"), R = !1, S = /^[ \t\n\r\u000c]+/, T = /^[, \t\n\r\u000c]+/, U = /^[^ \t\n\r\u000c]+/, V = /[,]+$/, W = /^\d+$/, X = /^-?(?:[0-9]+|[0-9]*\.[0-9]+)(?:[eE][+-]?[0-9]+)?$/, Y = function (a, b, c, d) {
		a.addEventListener ? a.addEventListener(b, c, d || !1) : a.attachEvent && a.attachEvent("on" + b, c)
	}, Z = function (a) {
		var b = {};
		return function (c) {
			return c in b || (b[c] = a(c)), b[c]
		}
	}, $ = function () {
		var a = /^([\d\.]+)(em|vw|px)$/, b = function () {
			for (var a = arguments, b = 0, c = a[0]; ++b in a;)c = c.replace(a[b], a[++b]);
			return c
		}, c = Z(function (a) {
			return "return " + b((a || "").toLowerCase(), /\band\b/g, "&&", /,/g, "||", /min-([a-z-\s]+):/g, "e.$1>=", /max-([a-z-\s]+):/g, "e.$1<=", /calc([^)]+)/g, "($1)", /(\d+[\.]*[\d]*)([a-z]+)/g, "($1 * e.$2)", /^(?!(e.[a-z]|[0-9\.&=|><\+\-\*\(\)\/])).*/gi, "") + ";"
		});
		return function (b, d) {
			var e;
			if (!(b in M))if (M[b] = !1, d && (e = b.match(a)))M[b] = e[1] * P[e[2]]; else try {
				M[b] = new Function("e", c(b))(P)
			} catch (f) {
			}
			return M[b]
		}
	}(), _ = function (a, b) {
		return a.w ? (a.cWidth = s.calcListLength(b || "100vw"), a.res = a.w / a.cWidth) : a.res = a.d, a
	}, aa = function (a) {
		var c, d, e, f = a || {};
		if (f.elements && 1 === f.elements.nodeType && ("IMG" === f.elements.nodeName.toUpperCase() ? f.elements = [f.elements] : (f.context = f.elements, f.elements = null)), c = f.elements || s.qsa(f.context || b, f.reevaluate || f.reselect ? s.sel : s.selShort), e = c.length) {
			for (s.setupRun(f), R = !0, d = 0; e > d; d++)s.fillImg(c[d], f);
			s.teardownRun(f)
		}
	};
	o = a.console && console.warn ? function (a) {
		console.warn(a)
	} : t, F in u || (F = "src"), z["image/jpeg"] = !0, z["image/gif"] = !0, z["image/png"] = !0, z["image/svg+xml"] = b.implementation.hasFeature("http://wwwindow.w3.org/TR/SVG11/feature#Image", "1.1"), s.ns = ("pf" + (new Date).getTime()).substr(0, 9), s.supSrcset = "srcset" in u, s.supSizes = "sizes" in u, s.supPicture = !!a.HTMLPictureElement, s.supSrcset && s.supPicture && !s.supSizes && !function (a) {
		u.srcset = "data:,a", a.src = "data:,a", s.supSrcset = u.complete === a.complete, s.supPicture = s.supSrcset && s.supPicture
	}(b.createElement("img")), s.selShort = "picture>img,img[srcset]", s.sel = s.selShort, s.cfg = A, s.supSrcset && (s.sel += ",img[" + C + "]"), s.DPR = O || 1, s.u = P, s.types = z, q = s.supSrcset && !s.supSizes, s.setSize = t, s.makeUrl = Z(function (a) {
		return Q.href = a, Q.href
	}), s.qsa = function (a, b) {
		return a.querySelectorAll(b)
	}, s.matchesMedia = function () {
		return a.matchMedia && (matchMedia("(min-width: 0.1em)") || {}).matches ? s.matchesMedia = function (a) {
			return !a || matchMedia(a).matches
		} : s.matchesMedia = s.mMQ, s.matchesMedia.apply(this, arguments)
	}, s.mMQ = function (a) {
		return a ? $(a) : !0
	}, s.calcLength = function (a) {
		var b = $(a, !0) || !1;
		return 0 > b && (b = !1), b
	}, s.supportsType = function (a) {
		return a ? z[a] : !0
	}, s.parseSize = Z(function (a) {
		var b = (a || "").match(H);
		return {media: b && b[1], length: b && b[2]}
	}), s.parseSet = function (a) {
		return a.cands || (a.cands = m(a.srcset, a)), a.cands
	}, s.getEmValue = function () {
		var a;
		if (!p && (a = b.body)) {
			var c = b.createElement("div"), d = y.style.cssText, e = a.style.cssText;
			c.style.cssText = J, y.style.cssText = K, a.style.cssText = K, a.appendChild(c), p = c.offsetWidth, a.removeChild(c), p = parseFloat(p, 10), y.style.cssText = d, a.style.cssText = e
		}
		return p || 16
	}, s.calcListLength = function (a) {
		if (!(a in N) || A.uT) {
			var b = s.calcLength(n(a));
			N[a] = b ? b : P.width
		}
		return N[a]
	}, s.setRes = function (a) {
		var b;
		if (a) {
			b = s.parseSet(a);
			for (var c = 0, d = b.length; d > c; c++)_(b[c], a.sizes)
		}
		return b
	}, s.setRes.res = _, s.applySetCandidate = function (a, b) {
		if (a.length) {
			var c, d, e, f, h, k, l, m, n, o = b[s.ns], p = s.DPR;
			if (k = o.curSrc || b[F], l = o.curCan || j(b, k, a[0].set), l && l.set === a[0].set && (n = E && !b.complete && l.res - .1 > p, n || (l.cached = !0, l.res >= p && (h = l))), !h)for (a.sort(i), f = a.length, h = a[f - 1], d = 0; f > d; d++)if (c = a[d], c.res >= p) {
				e = d - 1, h = a[e] && (n || k !== s.makeUrl(c.url)) && g(a[e].res, c.res, p, a[e].cached) ? a[e] : c;
				break
			}
			h && (m = s.makeUrl(h.url), o.curSrc = m, o.curCan = h, m !== k && s.setSrc(b, h), s.setSize(b))
		}
	}, s.setSrc = function (a, b) {
		var c;
		a.src = b.url, "image/svg+xml" === b.set.type && (c = a.style.width, a.style.width = a.offsetWidth + 1 + "px", a.offsetWidth + 1 && (a.style.width = c))
	}, s.getSet = function (a) {
		var b, c, d, e = !1, f = a[s.ns].sets;
		for (b = 0; b < f.length && !e; b++)if (c = f[b], c.srcset && s.matchesMedia(c.media) && (d = s.supportsType(c.type))) {
			"pending" === d && (c = d), e = c;
			break
		}
		return e
	}, s.parseSets = function (a, b, d) {
		var e, f, g, h, i = b && "PICTURE" === b.nodeName.toUpperCase(), j = a[s.ns];
		(j.src === c || d.src) && (j.src = v.call(a, "src"), j.src ? w.call(a, B, j.src) : x.call(a, B)), (j.srcset === c || d.srcset || !s.supSrcset || a.srcset) && (e = v.call(a, "srcset"), j.srcset = e, h = !0), j.sets = [], i && (j.pic = !0, l(b, j.sets)), j.srcset ? (f = {
			srcset: j.srcset,
			sizes: v.call(a, "sizes")
		}, j.sets.push(f), g = (q || j.src) && G.test(j.srcset || ""), g || !j.src || k(j.src, f) || f.has1x || (f.srcset += ", " + j.src, f.cands.push({
			url: j.src,
			d: 1,
			set: f
		}))) : j.src && j.sets.push({
			srcset: j.src,
			sizes: null
		}), j.curCan = null, j.curSrc = c, j.supported = !(i || f && !s.supSrcset || g), h && s.supSrcset && !j.supported && (e ? (w.call(a, C, e), a.srcset = "") : x.call(a, C)), j.supported && !j.srcset && (!j.src && a.src || a.src !== s.makeUrl(j.src)) && (null === j.src ? a.removeAttribute("src") : a.src = j.src), j.parsed = !0
	}, s.fillImg = function (a, b) {
		var c, d = b.reselect || b.reevaluate;
		a[s.ns] || (a[s.ns] = {}), c = a[s.ns], (d || c.evaled !== r) && ((!c.parsed || b.reevaluate) && s.parseSets(a, a.parentNode, b), c.supported ? c.evaled = r : h(a))
	}, s.setupRun = function () {
		(!R || L || O !== a.devicePixelRatio) && f()
	}, s.supPicture ? (aa = t, s.fillImg = t) : !function () {
		var c, d = a.attachEvent ? /d$|^c/ : /d$|^c|^i/, e = function () {
			var a = b.readyState || "";
			f = setTimeout(e, "loading" === a ? 200 : 999), b.body && (s.fillImgs(), c = c || d.test(a), c && clearTimeout(f))
		}, f = setTimeout(e, b.body ? 9 : 99), g = function (a, b) {
			var c, d, e = function () {
				var f = new Date - d;
				b > f ? c = setTimeout(e, b - f) : (c = null, a())
			};
			return function () {
				d = new Date, c || (c = setTimeout(e, b))
			}
		}, h = y.clientHeight, i = function () {
			L = Math.max(a.innerWidth || 0, y.clientWidth) !== P.width || y.clientHeight !== h, h = y.clientHeight, L && s.fillImgs()
		};
		Y(a, "resize", g(i, 99)), Y(b, "readystatechange", e)
	}(), s.picturefill = aa, s.fillImgs = aa, s.teardownRun = t, aa._ = s, a.picturefillCFG = {
		pf: s,
		push: function (a) {
			var b = a.shift();
			"function" == typeof s[b] ? s[b].apply(s, a) : (A[b] = a[0], R && s.fillImgs({reselect: !0}))
		}
	};
	for (; I && I.length;)a.picturefillCFG.push(I.shift());
	a.picturefill = aa, "object" == typeof module && "object" == typeof module.exports ? module.exports = aa : "function" == typeof define && define.amd && define("picturefill", function () {
		return aa
	}), s.supPicture || (z["image/webp"] = e("image/webp", "data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAABBxAR/Q9ERP8DAABWUDggGAAAADABAJ0BKgEAAQADADQlpAADcAD++/1QAA=="))
}(window, document);

/*!
 * jQuery Cycle2; version: 2.1.6 build: 20141007
 * http://jquery.malsup.com/cycle2/
 * Copyright (c) 2014 M. Alsup; Dual licensed: MIT/GPL
 */
!function (a) {
	"use strict";
	function b(a) {
		return (a || "").toLowerCase()
	}

	var c = "2.1.6";
	a.fn.cycle = function (c) {
		var d;
		return 0 !== this.length || a.isReady ? this.each(function () {
			var d, e, f, g, h = a(this), i = a.fn.cycle.log;
			if (!h.data("cycle.opts")) {
				(h.data("cycle-log") === !1 || c && c.log === !1 || e && e.log === !1) && (i = a.noop), i("--c2 init--"), d = h.data();
				for (var j in d)d.hasOwnProperty(j) && /^cycle[A-Z]+/.test(j) && (g = d[j], f = j.match(/^cycle(.*)/)[1].replace(/^[A-Z]/, b), i(f + ":", g, "(" + typeof g + ")"), d[f] = g);
				e = a.extend({}, a.fn.cycle.defaults, d, c || {}), e.timeoutId = 0, e.paused = e.paused || !1, e.container = h, e._maxZ = e.maxZ, e.API = a.extend({_container: h}, a.fn.cycle.API), e.API.log = i, e.API.trigger = function (a, b) {
					return e.container.trigger(a, b), e.API
				}, h.data("cycle.opts", e), h.data("cycle.API", e.API), e.API.trigger("cycle-bootstrap", [e, e.API]), e.API.addInitialSlides(), e.API.preInitSlideshow(), e.slides.length && e.API.initSlideshow()
			}
		}) : (d = {
			s: this.selector,
			c: this.context
		}, a.fn.cycle.log("requeuing slideshow (dom not ready)"), a(function () {
			a(d.s, d.c).cycle(c)
		}), this)
	}, a.fn.cycle.API = {
		opts: function () {
			return this._container.data("cycle.opts")
		}, addInitialSlides: function () {
			var b = this.opts(), c = b.slides;
			b.slideCount = 0, b.slides = a(), c = c.jquery ? c : b.container.find(c), b.random && c.sort(function () {
				return Math.random() - .5
			}), b.API.add(c)
		}, preInitSlideshow: function () {
			var b = this.opts();
			b.API.trigger("cycle-pre-initialize", [b]);
			var c = a.fn.cycle.transitions[b.fx];
			c && a.isFunction(c.preInit) && c.preInit(b), b._preInitialized = !0
		}, postInitSlideshow: function () {
			var b = this.opts();
			b.API.trigger("cycle-post-initialize", [b]);
			var c = a.fn.cycle.transitions[b.fx];
			c && a.isFunction(c.postInit) && c.postInit(b)
		}, initSlideshow: function () {
			var b, c = this.opts(), d = c.container;
			c.API.calcFirstSlide(), "static" == c.container.css("position") && c.container.css("position", "relative"), a(c.slides[c.currSlide]).css({
				opacity: 1,
				display: "block",
				visibility: "visible"
			}), c.API.stackSlides(c.slides[c.currSlide], c.slides[c.nextSlide], !c.reverse), c.pauseOnHover && (c.pauseOnHover !== !0 && (d = a(c.pauseOnHover)), d.hover(function () {
				c.API.pause(!0)
			}, function () {
				c.API.resume(!0)
			})), c.timeout && (b = c.API.getSlideOpts(c.currSlide), c.API.queueTransition(b, b.timeout + c.delay)), c._initialized = !0, c.API.updateView(!0), c.API.trigger("cycle-initialized", [c]), c.API.postInitSlideshow()
		}, pause: function (b) {
			var c = this.opts(), d = c.API.getSlideOpts(), e = c.hoverPaused || c.paused;
			b ? c.hoverPaused = !0 : c.paused = !0, e || (c.container.addClass("cycle-paused"), c.API.trigger("cycle-paused", [c]).log("cycle-paused"), d.timeout && (clearTimeout(c.timeoutId), c.timeoutId = 0, c._remainingTimeout -= a.now() - c._lastQueue, (c._remainingTimeout < 0 || isNaN(c._remainingTimeout)) && (c._remainingTimeout = void 0)))
		}, resume: function (a) {
			var b = this.opts(), c = !b.hoverPaused && !b.paused;
			a ? b.hoverPaused = !1 : b.paused = !1, c || (b.container.removeClass("cycle-paused"), 0 === b.slides.filter(":animated").length && b.API.queueTransition(b.API.getSlideOpts(), b._remainingTimeout), b.API.trigger("cycle-resumed", [b, b._remainingTimeout]).log("cycle-resumed"))
		}, add: function (b, c) {
			var d, e = this.opts(), f = e.slideCount, g = !1;
			"string" == a.type(b) && (b = a.trim(b)), a(b).each(function () {
				var b, d = a(this);
				c ? e.container.prepend(d) : e.container.append(d), e.slideCount++, b = e.API.buildSlideOpts(d), e.slides = c ? a(d).add(e.slides) : e.slides.add(d), e.API.initSlide(b, d, --e._maxZ), d.data("cycle.opts", b), e.API.trigger("cycle-slide-added", [e, b, d])
			}), e.API.updateView(!0), g = e._preInitialized && 2 > f && e.slideCount >= 1, g && (e._initialized ? e.timeout && (d = e.slides.length, e.nextSlide = e.reverse ? d - 1 : 1, e.timeoutId || e.API.queueTransition(e)) : e.API.initSlideshow())
		}, calcFirstSlide: function () {
			var a, b = this.opts();
			a = parseInt(b.startingSlide || 0, 10), (a >= b.slides.length || 0 > a) && (a = 0), b.currSlide = a, b.reverse ? (b.nextSlide = a - 1, b.nextSlide < 0 && (b.nextSlide = b.slides.length - 1)) : (b.nextSlide = a + 1, b.nextSlide == b.slides.length && (b.nextSlide = 0))
		}, calcNextSlide: function () {
			var a, b = this.opts();
			b.reverse ? (a = b.nextSlide - 1 < 0, b.nextSlide = a ? b.slideCount - 1 : b.nextSlide - 1, b.currSlide = a ? 0 : b.nextSlide + 1) : (a = b.nextSlide + 1 == b.slides.length, b.nextSlide = a ? 0 : b.nextSlide + 1, b.currSlide = a ? b.slides.length - 1 : b.nextSlide - 1)
		}, calcTx: function (b, c) {
			var d, e = b;
			return e._tempFx ? d = a.fn.cycle.transitions[e._tempFx] : c && e.manualFx && (d = a.fn.cycle.transitions[e.manualFx]), d || (d = a.fn.cycle.transitions[e.fx]), e._tempFx = null, this.opts()._tempFx = null, d || (d = a.fn.cycle.transitions.fade, e.API.log('Transition "' + e.fx + '" not found.  Using fade.')), d
		}, prepareTx: function (a, b) {
			var c, d, e, f, g, h = this.opts();
			return h.slideCount < 2 ? void(h.timeoutId = 0) : (!a || h.busy && !h.manualTrump || (h.API.stopTransition(), h.busy = !1, clearTimeout(h.timeoutId), h.timeoutId = 0), void(h.busy || (0 !== h.timeoutId || a) && (d = h.slides[h.currSlide], e = h.slides[h.nextSlide], f = h.API.getSlideOpts(h.nextSlide), g = h.API.calcTx(f, a), h._tx = g, a && void 0 !== f.manualSpeed && (f.speed = f.manualSpeed), h.nextSlide != h.currSlide && (a || !h.paused && !h.hoverPaused && h.timeout) ? (h.API.trigger("cycle-before", [f, d, e, b]), g.before && g.before(f, d, e, b), c = function () {
				h.busy = !1, h.container.data("cycle.opts") && (g.after && g.after(f, d, e, b), h.API.trigger("cycle-after", [f, d, e, b]), h.API.queueTransition(f), h.API.updateView(!0))
			}, h.busy = !0, g.transition ? g.transition(f, d, e, b, c) : h.API.doTransition(f, d, e, b, c), h.API.calcNextSlide(), h.API.updateView()) : h.API.queueTransition(f))))
		}, doTransition: function (b, c, d, e, f) {
			var g = b, h = a(c), i = a(d), j = function () {
				i.animate(g.animIn || {opacity: 1}, g.speed, g.easeIn || g.easing, f)
			};
			i.css(g.cssBefore || {}), h.animate(g.animOut || {}, g.speed, g.easeOut || g.easing, function () {
				h.css(g.cssAfter || {}), g.sync || j()
			}), g.sync && j()
		}, queueTransition: function (b, c) {
			var d = this.opts(), e = void 0 !== c ? c : b.timeout;
			return 0 === d.nextSlide && 0 === --d.loop ? (d.API.log("terminating; loop=0"), d.timeout = 0, e ? setTimeout(function () {
				d.API.trigger("cycle-finished", [d])
			}, e) : d.API.trigger("cycle-finished", [d]), void(d.nextSlide = d.currSlide)) : void 0 !== d.continueAuto && (d.continueAuto === !1 || a.isFunction(d.continueAuto) && d.continueAuto() === !1) ? (d.API.log("terminating automatic transitions"), d.timeout = 0, void(d.timeoutId && clearTimeout(d.timeoutId))) : void(e && (d._lastQueue = a.now(), void 0 === c && (d._remainingTimeout = b.timeout), d.paused || d.hoverPaused || (d.timeoutId = setTimeout(function () {
				d.API.prepareTx(!1, !d.reverse)
			}, e))))
		}, stopTransition: function () {
			var a = this.opts();
			a.slides.filter(":animated").length && (a.slides.stop(!1, !0), a.API.trigger("cycle-transition-stopped", [a])), a._tx && a._tx.stopTransition && a._tx.stopTransition(a)
		}, advanceSlide: function (a) {
			var b = this.opts();
			return clearTimeout(b.timeoutId), b.timeoutId = 0, b.nextSlide = b.currSlide + a, b.nextSlide < 0 ? b.nextSlide = b.slides.length - 1 : b.nextSlide >= b.slides.length && (b.nextSlide = 0), b.API.prepareTx(!0, a >= 0), !1
		}, buildSlideOpts: function (c) {
			var d, e, f = this.opts(), g = c.data() || {};
			for (var h in g)g.hasOwnProperty(h) && /^cycle[A-Z]+/.test(h) && (d = g[h], e = h.match(/^cycle(.*)/)[1].replace(/^[A-Z]/, b), f.API.log("[" + (f.slideCount - 1) + "]", e + ":", d, "(" + typeof d + ")"), g[e] = d);
			g = a.extend({}, a.fn.cycle.defaults, f, g), g.slideNum = f.slideCount;
			try {
				delete g.API, delete g.slideCount, delete g.currSlide, delete g.nextSlide, delete g.slides
			} catch (i) {
			}
			return g
		}, getSlideOpts: function (b) {
			var c = this.opts();
			void 0 === b && (b = c.currSlide);
			var d = c.slides[b], e = a(d).data("cycle.opts");
			return a.extend({}, c, e)
		}, initSlide: function (b, c, d) {
			var e = this.opts();
			c.css(b.slideCss || {}), d > 0 && c.css("zIndex", d), isNaN(b.speed) && (b.speed = a.fx.speeds[b.speed] || a.fx.speeds._default), b.sync || (b.speed = b.speed / 2), c.addClass(e.slideClass)
		}, updateView: function (a, b) {
			var c = this.opts();
			if (c._initialized) {
				var d = c.API.getSlideOpts(), e = c.slides[c.currSlide];
				!a && b !== !0 && (c.API.trigger("cycle-update-view-before", [c, d, e]), c.updateView < 0) || (c.slideActiveClass && c.slides.removeClass(c.slideActiveClass).eq(c.currSlide).addClass(c.slideActiveClass), a && c.hideNonActive && c.slides.filter(":not(." + c.slideActiveClass + ")").css("visibility", "hidden"), 0 === c.updateView && setTimeout(function () {
					c.API.trigger("cycle-update-view", [c, d, e, a])
				}, d.speed / (c.sync ? 2 : 1)), 0 !== c.updateView && c.API.trigger("cycle-update-view", [c, d, e, a]), a && c.API.trigger("cycle-update-view-after", [c, d, e]))
			}
		}, getComponent: function (b) {
			var c = this.opts(), d = c[b];
			return "string" == typeof d ? /^\s*[\>|\+|~]/.test(d) ? c.container.find(d) : a(d) : d.jquery ? d : a(d)
		}, stackSlides: function (b, c, d) {
			var e = this.opts();
			b || (b = e.slides[e.currSlide], c = e.slides[e.nextSlide], d = !e.reverse), a(b).css("zIndex", e.maxZ);
			var f, g = e.maxZ - 2, h = e.slideCount;
			if (d) {
				for (f = e.currSlide + 1; h > f; f++)a(e.slides[f]).css("zIndex", g--);
				for (f = 0; f < e.currSlide; f++)a(e.slides[f]).css("zIndex", g--)
			} else {
				for (f = e.currSlide - 1; f >= 0; f--)a(e.slides[f]).css("zIndex", g--);
				for (f = h - 1; f > e.currSlide; f--)a(e.slides[f]).css("zIndex", g--)
			}
			a(c).css("zIndex", e.maxZ - 1)
		}, getSlideIndex: function (a) {
			return this.opts().slides.index(a)
		}
	}, a.fn.cycle.log = function () {
		window.console && console.log && console.log("[cycle2] " + Array.prototype.join.call(arguments, " "))
	}, a.fn.cycle.version = function () {
		return "Cycle2: " + c
	}, a.fn.cycle.transitions = {
		custom: {}, none: {
			before: function (a, b, c, d) {
				a.API.stackSlides(c, b, d), a.cssBefore = {opacity: 1, visibility: "visible", display: "block"}
			}
		}, fade: {
			before: function (b, c, d, e) {
				var f = b.API.getSlideOpts(b.nextSlide).slideCss || {};
				b.API.stackSlides(c, d, e), b.cssBefore = a.extend(f, {
					opacity: 0,
					visibility: "visible",
					display: "block"
				}), b.animIn = {opacity: 1}, b.animOut = {opacity: 0}
			}
		}, fadeout: {
			before: function (b, c, d, e) {
				var f = b.API.getSlideOpts(b.nextSlide).slideCss || {};
				b.API.stackSlides(c, d, e), b.cssBefore = a.extend(f, {
					opacity: 1,
					visibility: "visible",
					display: "block"
				}), b.animOut = {opacity: 0}
			}
		}, scrollHorz: {
			before: function (a, b, c, d) {
				a.API.stackSlides(b, c, d);
				var e = a.container.css("overflow", "hidden").width();
				a.cssBefore = {
					left: d ? e : -e,
					top: 0,
					opacity: 1,
					visibility: "visible",
					display: "block"
				}, a.cssAfter = {zIndex: a._maxZ - 2, left: 0}, a.animIn = {left: 0}, a.animOut = {left: d ? -e : e}
			}
		}
	}, a.fn.cycle.defaults = {
		allowWrap: !0,
		autoSelector: ".cycle-slideshow[data-cycle-auto-init!=false]",
		delay: 0,
		easing: null,
		fx: "fade",
		hideNonActive: !0,
		loop: 0,
		manualFx: void 0,
		manualSpeed: void 0,
		manualTrump: !0,
		maxZ: 100,
		pauseOnHover: !1,
		reverse: !1,
		slideActiveClass: "cycle-slide-active",
		slideClass: "cycle-slide",
		slideCss: {position: "absolute", top: 0, left: 0},
		slides: "> img",
		speed: 500,
		startingSlide: 0,
		sync: !0,
		timeout: 4e3,
		updateView: 0
	}, a(document).ready(function () {
		a(a.fn.cycle.defaults.autoSelector).cycle()
	})
}(jQuery), /*! Cycle2 autoheight plugin; Copyright (c) M.Alsup, 2012; version: 20130913 */
	function (a) {
		"use strict";
		function b(b, d) {
			var e, f, g, h = d.autoHeight;
			if ("container" == h)f = a(d.slides[d.currSlide]).outerHeight(), d.container.height(f); else if (d._autoHeightRatio)d.container.height(d.container.width() / d._autoHeightRatio); else if ("calc" === h || "number" == a.type(h) && h >= 0) {
				if (g = "calc" === h ? c(b, d) : h >= d.slides.length ? 0 : h, g == d._sentinelIndex)return;
				d._sentinelIndex = g, d._sentinel && d._sentinel.remove(), e = a(d.slides[g].cloneNode(!0)), e.removeAttr("id name rel").find("[id],[name],[rel]").removeAttr("id name rel"), e.css({
					position: "static",
					visibility: "hidden",
					display: "block"
				}).prependTo(d.container).addClass("cycle-sentinel cycle-slide").removeClass("cycle-slide-active"), e.find("*").css("visibility", "hidden"), d._sentinel = e
			}
		}

		function c(b, c) {
			var d = 0, e = -1;
			return c.slides.each(function (b) {
				var c = a(this).height();
				c > e && (e = c, d = b)
			}), d
		}

		function d(b, c, d, e) {
			var f = a(e).outerHeight();
			c.container.animate({height: f}, c.autoHeightSpeed, c.autoHeightEasing)
		}

		function e(c, f) {
			f._autoHeightOnResize && (a(window).off("resize orientationchange", f._autoHeightOnResize), f._autoHeightOnResize = null), f.container.off("cycle-slide-added cycle-slide-removed", b), f.container.off("cycle-destroyed", e), f.container.off("cycle-before", d), f._sentinel && (f._sentinel.remove(), f._sentinel = null)
		}

		a.extend(a.fn.cycle.defaults, {
			autoHeight: 0,
			autoHeightSpeed: 250,
			autoHeightEasing: null
		}), a(document).on("cycle-initialized", function (c, f) {
			function g() {
				b(c, f)
			}

			var h, i = f.autoHeight, j = a.type(i), k = null;
			("string" === j || "number" === j) && (f.container.on("cycle-slide-added cycle-slide-removed", b), f.container.on("cycle-destroyed", e), "container" == i ? f.container.on("cycle-before", d) : "string" === j && /\d+\:\d+/.test(i) && (h = i.match(/(\d+)\:(\d+)/), h = h[1] / h[2], f._autoHeightRatio = h), "number" !== j && (f._autoHeightOnResize = function () {
				clearTimeout(k), k = setTimeout(g, 50)
			}, a(window).on("resize orientationchange", f._autoHeightOnResize)), setTimeout(g, 30))
		})
	}(jQuery), /*! caption plugin for Cycle2;  version: 20130306 */
	function (a) {
		"use strict";
		a.extend(a.fn.cycle.defaults, {
			caption: "> .cycle-caption",
			captionTemplate: "{{slideNum}} / {{slideCount}}",
			overlay: "> .cycle-overlay",
			overlayTemplate: "<div>{{title}}</div><div>{{desc}}</div>",
			captionModule: "caption"
		}), a(document).on("cycle-update-view", function (b, c, d, e) {
			if ("caption" === c.captionModule) {
				a.each(["caption", "overlay"], function () {
					var a = this, b = d[a + "Template"], f = c.API.getComponent(a);
					f.length && b ? (f.html(c.API.tmpl(b, d, c, e)), f.show()) : f.hide()
				})
			}
		}), a(document).on("cycle-destroyed", function (b, c) {
			var d;
			a.each(["caption", "overlay"], function () {
				var a = this, b = c[a + "Template"];
				c[a] && b && (d = c.API.getComponent("caption"), d.empty())
			})
		})
	}(jQuery), /*! command plugin for Cycle2;  version: 20140415 */
	function (a) {
		"use strict";
		var b = a.fn.cycle;
		a.fn.cycle = function (c) {
			var d, e, f, g = a.makeArray(arguments);
			return "number" == a.type(c) ? this.cycle("goto", c) : "string" == a.type(c) ? this.each(function () {
				var h;
				return d = c, f = a(this).data("cycle.opts"), void 0 === f ? void b.log('slideshow must be initialized before sending commands; "' + d + '" ignored') : (d = "goto" == d ? "jump" : d, e = f.API[d], a.isFunction(e) ? (h = a.makeArray(g), h.shift(), e.apply(f.API, h)) : void b.log("unknown command: ", d))
			}) : b.apply(this, arguments)
		}, a.extend(a.fn.cycle, b), a.extend(b.API, {
			next: function () {
				var a = this.opts();
				if (!a.busy || a.manualTrump) {
					var b = a.reverse ? -1 : 1;
					a.allowWrap === !1 && a.currSlide + b >= a.slideCount || (a.API.advanceSlide(b), a.API.trigger("cycle-next", [a]).log("cycle-next"))
				}
			}, prev: function () {
				var a = this.opts();
				if (!a.busy || a.manualTrump) {
					var b = a.reverse ? 1 : -1;
					a.allowWrap === !1 && a.currSlide + b < 0 || (a.API.advanceSlide(b), a.API.trigger("cycle-prev", [a]).log("cycle-prev"))
				}
			}, destroy: function () {
				this.stop();
				var b = this.opts(), c = a.isFunction(a._data) ? a._data : a.noop;
				clearTimeout(b.timeoutId), b.timeoutId = 0, b.API.stop(), b.API.trigger("cycle-destroyed", [b]).log("cycle-destroyed"), b.container.removeData(), c(b.container[0], "parsedAttrs", !1), b.retainStylesOnDestroy || (b.container.removeAttr("style"), b.slides.removeAttr("style"), b.slides.removeClass(b.slideActiveClass)), b.slides.each(function () {
					var d = a(this);
					d.removeData(), d.removeClass(b.slideClass), c(this, "parsedAttrs", !1)
				})
			}, jump: function (a, b) {
				var c, d = this.opts();
				if (!d.busy || d.manualTrump) {
					var e = parseInt(a, 10);
					if (isNaN(e) || 0 > e || e >= d.slides.length)return void d.API.log("goto: invalid slide index: " + e);
					if (e == d.currSlide)return void d.API.log("goto: skipping, already on slide", e);
					d.nextSlide = e, clearTimeout(d.timeoutId), d.timeoutId = 0, d.API.log("goto: ", e, " (zero-index)"), c = d.currSlide < d.nextSlide, d._tempFx = b, d.API.prepareTx(!0, c)
				}
			}, stop: function () {
				var b = this.opts(), c = b.container;
				clearTimeout(b.timeoutId), b.timeoutId = 0, b.API.stopTransition(), b.pauseOnHover && (b.pauseOnHover !== !0 && (c = a(b.pauseOnHover)), c.off("mouseenter mouseleave")), b.API.trigger("cycle-stopped", [b]).log("cycle-stopped")
			}, reinit: function () {
				var a = this.opts();
				a.API.destroy(), a.container.cycle()
			}, remove: function (b) {
				for (var c, d, e = this.opts(), f = [], g = 1, h = 0; h < e.slides.length; h++)c = e.slides[h], h == b ? d = c : (f.push(c), a(c).data("cycle.opts").slideNum = g, g++);
				d && (e.slides = a(f), e.slideCount--, a(d).remove(), b == e.currSlide ? e.API.advanceSlide(1) : b < e.currSlide ? e.currSlide-- : e.currSlide++, e.API.trigger("cycle-slide-removed", [e, b, d]).log("cycle-slide-removed"), e.API.updateView())
			}
		}), a(document).on("click.cycle", "[data-cycle-cmd]", function (b) {
			b.preventDefault();
			var c = a(this), d = c.data("cycle-cmd"), e = c.data("cycle-context") || ".cycle-slideshow";
			a(e).cycle(d, c.data("cycle-arg"))
		})
	}(jQuery), /*! hash plugin for Cycle2;  version: 20130905 */
	function (a) {
		"use strict";
		function b(b, c) {
			var d;
			return b._hashFence ? void(b._hashFence = !1) : (d = window.location.hash.substring(1), void b.slides.each(function (e) {
				if (a(this).data("cycle-hash") == d) {
					if (c === !0)b.startingSlide = e; else {
						var f = b.currSlide < e;
						b.nextSlide = e, b.API.prepareTx(!0, f)
					}
					return !1
				}
			}))
		}

		a(document).on("cycle-pre-initialize", function (c, d) {
			b(d, !0), d._onHashChange = function () {
				b(d, !1)
			}, a(window).on("hashchange", d._onHashChange)
		}), a(document).on("cycle-update-view", function (a, b, c) {
			c.hash && "#" + c.hash != window.location.hash && (b._hashFence = !0, window.location.hash = c.hash)
		}), a(document).on("cycle-destroyed", function (b, c) {
			c._onHashChange && a(window).off("hashchange", c._onHashChange)
		})
	}(jQuery), /*! loader plugin for Cycle2;  version: 20131121 */
	function (a) {
		"use strict";
		a.extend(a.fn.cycle.defaults, {loader: !1}), a(document).on("cycle-bootstrap", function (b, c) {
			function d(b, d) {
				function f(b) {
					var f;
					"wait" == c.loader ? (h.push(b), 0 === j && (h.sort(g), e.apply(c.API, [h, d]), c.container.removeClass("cycle-loading"))) : (f = a(c.slides[c.currSlide]), e.apply(c.API, [b, d]), f.show(), c.container.removeClass("cycle-loading"))
				}

				function g(a, b) {
					return a.data("index") - b.data("index")
				}

				var h = [];
				if ("string" == a.type(b))b = a.trim(b); else if ("array" === a.type(b))for (var i = 0; i < b.length; i++)b[i] = a(b[i])[0];
				b = a(b);
				var j = b.length;
				j && (b.css("visibility", "hidden").appendTo("body").each(function (b) {
					function g() {
						0 === --i && (--j, f(k))
					}

					var i = 0, k = a(this), l = k.is("img") ? k : k.find("img");
					return k.data("index", b), l = l.filter(":not(.cycle-loader-ignore)").filter(':not([src=""])'), l.length ? (i = l.length, void l.each(function () {
						this.complete ? g() : a(this).load(function () {
							g()
						}).on("error", function () {
							0 === --i && (c.API.log("slide skipped; img not loaded:", this.src), 0 === --j && "wait" == c.loader && e.apply(c.API, [h, d]))
						})
					})) : (--j, void h.push(k))
				}), j && c.container.addClass("cycle-loading"))
			}

			var e;
			c.loader && (e = c.API.add, c.API.add = d)
		})
	}(jQuery), /*! pager plugin for Cycle2;  version: 20140415 */
	function (a) {
		"use strict";
		function b(b, c, d) {
			var e, f = b.API.getComponent("pager");
			f.each(function () {
				var f = a(this);
				if (c.pagerTemplate) {
					var g = b.API.tmpl(c.pagerTemplate, c, b, d[0]);
					e = a(g).appendTo(f)
				} else e = f.children().eq(b.slideCount - 1);
				e.on(b.pagerEvent, function (a) {
					b.pagerEventBubble || a.preventDefault(), b.API.page(f, a.currentTarget)
				})
			})
		}

		function c(a, b) {
			var c = this.opts();
			if (!c.busy || c.manualTrump) {
				var d = a.children().index(b), e = d, f = c.currSlide < e;
				c.currSlide != e && (c.nextSlide = e, c._tempFx = c.pagerFx, c.API.prepareTx(!0, f), c.API.trigger("cycle-pager-activated", [c, a, b]))
			}
		}

		a.extend(a.fn.cycle.defaults, {
			pager: "> .cycle-pager",
			pagerActiveClass: "cycle-pager-active",
			pagerEvent: "click.cycle",
			pagerEventBubble: void 0,
			pagerTemplate: "<span>&bull;</span>"
		}), a(document).on("cycle-bootstrap", function (a, c, d) {
			d.buildPagerLink = b
		}), a(document).on("cycle-slide-added", function (a, b, d, e) {
			b.pager && (b.API.buildPagerLink(b, d, e), b.API.page = c)
		}), a(document).on("cycle-slide-removed", function (b, c, d) {
			if (c.pager) {
				var e = c.API.getComponent("pager");
				e.each(function () {
					var b = a(this);
					a(b.children()[d]).remove()
				})
			}
		}), a(document).on("cycle-update-view", function (b, c) {
			var d;
			c.pager && (d = c.API.getComponent("pager"), d.each(function () {
				a(this).children().removeClass(c.pagerActiveClass).eq(c.currSlide).addClass(c.pagerActiveClass)
			}))
		}), a(document).on("cycle-destroyed", function (a, b) {
			var c = b.API.getComponent("pager");
			c && (c.children().off(b.pagerEvent), b.pagerTemplate && c.empty())
		})
	}(jQuery), /*! prevnext plugin for Cycle2;  version: 20140408 */
	function (a) {
		"use strict";
		a.extend(a.fn.cycle.defaults, {
			next: "> .cycle-next",
			nextEvent: "click.cycle",
			disabledClass: "disabled",
			prev: "> .cycle-prev",
			prevEvent: "click.cycle",
			swipe: !1
		}), a(document).on("cycle-initialized", function (a, b) {
			if (b.API.getComponent("next").on(b.nextEvent, function (a) {
					a.preventDefault(), b.API.next()
				}), b.API.getComponent("prev").on(b.prevEvent, function (a) {
					a.preventDefault(), b.API.prev()
				}), b.swipe) {
				var c = b.swipeVert ? "swipeUp.cycle" : "swipeLeft.cycle swipeleft.cycle", d = b.swipeVert ? "swipeDown.cycle" : "swipeRight.cycle swiperight.cycle";
				b.container.on(c, function () {
					b._tempFx = b.swipeFx, b.API.next()
				}), b.container.on(d, function () {
					b._tempFx = b.swipeFx, b.API.prev()
				})
			}
		}), a(document).on("cycle-update-view", function (a, b) {
			if (!b.allowWrap) {
				var c = b.disabledClass, d = b.API.getComponent("next"), e = b.API.getComponent("prev"), f = b._prevBoundry || 0, g = void 0 !== b._nextBoundry ? b._nextBoundry : b.slideCount - 1;
				b.currSlide == g ? d.addClass(c).prop("disabled", !0) : d.removeClass(c).prop("disabled", !1), b.currSlide === f ? e.addClass(c).prop("disabled", !0) : e.removeClass(c).prop("disabled", !1)
			}
		}), a(document).on("cycle-destroyed", function (a, b) {
			b.API.getComponent("prev").off(b.nextEvent), b.API.getComponent("next").off(b.prevEvent), b.container.off("swipeleft.cycle swiperight.cycle swipeLeft.cycle swipeRight.cycle swipeUp.cycle swipeDown.cycle")
		})
	}(jQuery), /*! progressive loader plugin for Cycle2;  version: 20130315 */
	function (a) {
		"use strict";
		a.extend(a.fn.cycle.defaults, {progressive: !1}), a(document).on("cycle-pre-initialize", function (b, c) {
			if (c.progressive) {
				var d, e, f = c.API, g = f.next, h = f.prev, i = f.prepareTx, j = a.type(c.progressive);
				if ("array" == j)d = c.progressive; else if (a.isFunction(c.progressive))d = c.progressive(c); else if ("string" == j) {
					if (e = a(c.progressive), d = a.trim(e.html()), !d)return;
					if (/^(\[)/.test(d))try {
						d = a.parseJSON(d)
					} catch (k) {
						return void f.log("error parsing progressive slides", k)
					} else d = d.split(new RegExp(e.data("cycle-split") || "\n")), d[d.length - 1] || d.pop()
				}
				i && (f.prepareTx = function (a, b) {
					var e, f;
					return a || 0 === d.length ? void i.apply(c.API, [a, b]) : void(b && c.currSlide == c.slideCount - 1 ? (f = d[0], d = d.slice(1), c.container.one("cycle-slide-added", function (a, b) {
						setTimeout(function () {
							b.API.advanceSlide(1)
						}, 50)
					}), c.API.add(f)) : b || 0 !== c.currSlide ? i.apply(c.API, [a, b]) : (e = d.length - 1, f = d[e], d = d.slice(0, e), c.container.one("cycle-slide-added", function (a, b) {
						setTimeout(function () {
							b.currSlide = 1, b.API.advanceSlide(-1)
						}, 50)
					}), c.API.add(f, !0)))
				}), g && (f.next = function () {
					var a = this.opts();
					if (d.length && a.currSlide == a.slideCount - 1) {
						var b = d[0];
						d = d.slice(1), a.container.one("cycle-slide-added", function (a, b) {
							g.apply(b.API), b.container.removeClass("cycle-loading")
						}), a.container.addClass("cycle-loading"), a.API.add(b)
					} else g.apply(a.API)
				}), h && (f.prev = function () {
					var a = this.opts();
					if (d.length && 0 === a.currSlide) {
						var b = d.length - 1, c = d[b];
						d = d.slice(0, b), a.container.one("cycle-slide-added", function (a, b) {
							b.currSlide = 1, b.API.advanceSlide(-1), b.container.removeClass("cycle-loading")
						}), a.container.addClass("cycle-loading"), a.API.add(c, !0)
					} else h.apply(a.API)
				})
			}
		})
	}(jQuery), /*! tmpl plugin for Cycle2;  version: 20121227 */
	function (a) {
		"use strict";
		a.extend(a.fn.cycle.defaults, {tmplRegex: "{{((.)?.*?)}}"}), a.extend(a.fn.cycle.API, {
			tmpl: function (b, c) {
				var d = new RegExp(c.tmplRegex || a.fn.cycle.defaults.tmplRegex, "g"), e = a.makeArray(arguments);
				return e.shift(), b.replace(d, function (b, c) {
					var d, f, g, h, i = c.split(".");
					for (d = 0; d < e.length; d++)if (g = e[d]) {
						if (i.length > 1)for (h = g, f = 0; f < i.length; f++)g = h, h = h[i[f]] || c; else h = g[c];
						if (a.isFunction(h))return h.apply(g, e);
						if (void 0 !== h && null !== h && h != c)return h
					}
					return c
				})
			}
		})
	}(jQuery);

/*! center plugin for Cycle2;  version: 20140121 */
(function ($) {
	"use strict";

	$.extend($.fn.cycle.defaults, {
		centerHorz: false,
		centerVert: false
	});

	$(document).on('cycle-pre-initialize', function (e, opts) {
		if (!opts.centerHorz && !opts.centerVert)
			return;

		// throttle resize event
		var timeout, timeout2;

		$(window).on('resize orientationchange load', resize);

		opts.container.on('cycle-destroyed', destroy);

		opts.container.on('cycle-initialized cycle-slide-added cycle-slide-removed', function (e, opts, slideOpts, slide) {
			resize();
		});

		adjustActive();

		function resize() {
			clearTimeout(timeout);
			timeout = setTimeout(adjustActive, 50);
		}

		function destroy(e, opts) {
			clearTimeout(timeout);
			clearTimeout(timeout2);
			$(window).off('resize orientationchange', resize);
		}

		function adjustAll() {
			opts.slides.each(adjustSlide);
		}

		function adjustActive() {
			/*jshint validthis: true */
			adjustSlide.apply(opts.container.find('.' + opts.slideActiveClass));
			clearTimeout(timeout2);
			timeout2 = setTimeout(adjustAll, 50);
		}

		function adjustSlide() {
			/*jshint validthis: true */
			var slide = $(this);
			var contW = opts.container.width();
			var contH = opts.container.height();
			var w = slide.outerWidth();
			var h = slide.outerHeight();
			if (w) {
				if (opts.centerHorz && w <= contW)
					slide.css('marginLeft', (contW - w) / 2);
				if (opts.centerVert && h <= contH)
					slide.css('marginTop', (contH - h) / 2);
			}
		}
	});

})(jQuery);


function initPopup() {
	'use strict';
	var popup = popup || {};
	popup.popupView = Backbone.View.extend({

		el: 'body',
		popup: '.ls-popup-dialog',
		nav: '.ls-items',
		closeBtn: '.ls-close',
		nextBtn: '.ls-next',
		previousBtn: '.ls-previous',
		gallery: '.gallery',
		showcase: '.gallery-showcase',
		expose: false,

		dialogOpen: false,

		// Our template for the line of statistics at the bottom of the app.
		popupTemplate: wp.template('popop-dialog'),
		galleryItems: [],

		// Delegated events for creating new items, and clearing completed ones.
		events: {
			'click .popup_gallery': 'displayPopup',
			'click .ls-all': 'displayAllItems',
			'click .gallery_item': 'displayGalleryItem',
			'click .ls-close': 'closeDialog'
		},

		// At initialization we bind to the relevant events on the `Todos`
		// collection, when items are added or changed. Kick things off by
		// loading any preexisting todos that might be saved in *localStorage*.
		initialize: function () {

			var _gallery = this.galleryItems;
			// Grab data from gallery
			this.title = jQuery('.description-section .description .name').text();
			this.desc = jQuery('.description-section .description .holder-text').html();
			this.price = jQuery('.description-section .description .price ').text();

			jQuery('.cycle-gallery .mask img').each(function () {
				_gallery.push(this.getAttribute('src'))
			});

			var gallery = jQuery('<div class="gallery_inner cycle-slideshow" ' +
				'data-cycle-prev=".ls-previous" ' +
				'data-cycle-next=".ls-next" ' +
				'data-cycle-loader="true" ' +
				'data-cycle-center-horz="true" ' +
				'data-cycle-center-vert="true" ' +
				'data-paused="true" ' +
				'data-cycle-swipe="true"' +
				'data-cycle-caption="' + this.popup + ' .ls-dialog-text"' +
				'data-cycle-caption-template="{{slideNum}} of {{slideCount}}" ' +
				'data-cycle-log="false"></div>');

			jQuery.each(_gallery, function (index, value) {
				gallery.append('<img src="' + value + '" />');
			});

			var showcase = jQuery('<div class="showcase_inner"></div>');

			jQuery.each(_gallery, function (index, value) {
				showcase.append('<div class="slide"><a href="#" class="item gallery_item"><img src="' + value + '" /></a></div>');
			});


			this.$el = jQuery(this.el);
			this.$el.append(this.popupTemplate({
					"title": this.title,
					"desc": this.desc,
					"price": this.price,
					"gallery": this.gallery
				})
			);
			this.$popup = jQuery(this.popup);

			this.$popup.find('.ls-content ' + this.gallery).html(gallery);
			this.$popup.find('.ls-content ' + this.showcase).html(showcase);

			this.$popup.find('.ls-content .gallery_inner').cycle();
		},

		displayPopup: function (e) {

			e.preventDefault();

			var index = jQuery(e.target).closest('.slide').index();
			this.displayAllItems();

			this.expose = false;
			this.dialogOpen = true;
			this.lockBodyScroll();

			var content = this.$popup.find('.ls-content');

			this.$popup.removeClass('is-hidden');

			this.$popup.find('.ls-content .gallery_inner').cycle('reinit');

		},

		displayAllItems: function (e) {

			var _showcase = this.showcase,
				_gallery = this.gallery;

			if (this.expose == false) {

				jQuery(this.gallery).fadeOut('slow', function () {
					jQuery(_showcase).removeClass('is-hidden');
					jQuery(_showcase).fadeIn('slow');
				});

				jQuery(this.nav).addClass('ls-disabled');
				jQuery(this.nextBtn).addClass('ls-disabled');
				jQuery(this.previousBtn).addClass('ls-disabled');

				this.expose = true;

			} else {

				jQuery(_showcase).fadeOut('slow', function () {
					jQuery(_gallery).removeClass('is-hidden');
					jQuery(_gallery).fadeIn('slow');
				});

				jQuery(this.nav).removeClass('ls-disabled');
				jQuery(this.nextBtn).removeClass('ls-disabled');
				jQuery(this.previousBtn).removeClass('ls-disabled');

				this.expose = false;

			}

		},

		displayGalleryItem: function (e) {
			e.preventDefault();


			var _showcase = this.showcase,
				_gallery = this.gallery,
				index = jQuery(e.target).closest('.slide').index(),
				_$popup = this.$popup;

			jQuery(_showcase).fadeOut('slow', function () {
				jQuery(_gallery).removeClass('is-hidden');
				jQuery(_gallery).fadeIn('slow');

			});

			_$popup.find('.ls-content .gallery_inner').cycle('goto', index);

			jQuery(this.nav).removeClass('ls-disabled');
			jQuery(this.nextBtn).removeClass('ls-disabled');
			jQuery(this.previousBtn).removeClass('ls-disabled');

			this.expose = false;

		},

		closeDialog: function (e) {
			if (this.expose == true) {
				this.$popup.find('.ls-content ' + this.gallery)
			}
			e.preventDefault();
			jQuery(this.popup).addClass('is-hidden');
			this.unlockBodyScroll();
		},

		renderPopup: function () {
			this.$el.html(this.popupTemplate());
		},

		/**
		 * Attach the dialog to the window
		 */
		attach: function () {
			this.$el.appendTo('body');
			return this;
		},

		/**
		 * Lock window scrolling for the main overlay
		 */
		lockBodyScroll: function () {
			if (jQuery('body').css('overflow') === 'hidden') {
				return;
			}

			// lock scroll position, but retain settings for later
			var scrollPosition = [
				self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
				self.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
			];

			jQuery('body')
				.data({
					'scroll-position': scrollPosition
				})
				.css('overflow', 'hidden');
			window.scrollTo(scrollPosition[0], scrollPosition[1]);
		},

		/**
		 * Unlock window scrolling
		 */
		unlockBodyScroll: function () {
			if (jQuery('body').css('overflow') !== 'hidden') {
				return;
			}

			// Check that there are no more dialogs or a live editor
			if (!jQuery('.ls-popup-dialog').is(':visible')) {
				jQuery('body').css('overflow', 'visible');
				var scrollPosition = jQuery('body').data('scroll-position');
				window.scrollTo(scrollPosition[0], scrollPosition[1]);
			}
		}
	});


	new popup.popupView();
}

// jQuery('.holder-text').readmore({
// 	heightMargin: 16,
// 	collapsedHeight: 207,
// 	moreLink: '<a href="#" class="read-btn">Read more</a>',
// 	lessLink: '<a href="#" class="read-btn">Close</a>'
// });

jQuery('.nano').nanoScroller({preventPageScrolling: true});

jQuery(window).on('resize', function () {
	var img = jQuery('.banner-home img'),
		height = jQuery('.banner-home img').height(),
		wrapper = jQuery('.loaded .banner-home'),
		wrapper_height = jQuery('.loaded .banner-home').height(),
		title = jQuery('.banner-home .title_wrapper');

	if (height <= wrapper_height) {
		wrapper.css({'height': height + 'px'});
		title.css({'top': (height / 2 + 10) + 'px'});
	} else {
		wrapper.removeAttr('style');
	}
});

window.mobilecheck = function () {
	var check = false;
	(function (a) {
		if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))check = true
	})(navigator.userAgent || navigator.vendor || window.opera);
	return check;
}

jQuery(document).ready(function () {
	if (!window.mobilecheck() && window.innerWidth >= 1000) {
		jQuery("[data-sticky_column]").stick_in_parent({
			parent: "[data-sticky_parent]",
			bottoming: true,
			offset_top: 110
		});
	}

});


jQuery(window).on("resize", (function (_this) {
	if (window.innerWidth <= 999) {
		jQuery(document.body).trigger("sticky_kit:detach");

	} else {
		return function (e) {
			return jQuery(document.body).trigger("sticky_kit:recalc");
		};
	}

})(this));

