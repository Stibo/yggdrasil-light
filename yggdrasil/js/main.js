/**********/
/* WINDOW */
/**********/

// Window
var oWindow							= {
	jQ 									: null,
	iWidth 								: 0,
	iHeight								: 0,
	iBreakingPoint						: 0,
	iMobileBreakingPoint				: 748,
	bIsMobile							: false,
	resize 								: function() {
		this.iWidth 						= this.jQ.width();
		this.iHeight						= this.jQ.height();

		if(this.bIsMobile != (this.iWidth < this.iMobileBreakingPoint)) {
			this.toggleMobileBreakpoint(!this.bIsMobile);
		}

		this.bIsMobile						= (this.iWidth < this.iMobileBreakingPoint);
	},
	toggleMobileBreakpoint				: function(bIsMobile) {
		if(bIsMobile) {
			oContent.oContentContainer.jQ.bind('movestart', function(oEventStart) {
				if(oBody.jQ.hasClass('showPage')) {
					oNavigation.swipe($(this), oEventStart);
				}
			});
		} else {
			oContent.oContentContainer.jQ.unbind('movestart');
		}
	},
	iMinPageChangeTime					: 600,
	bindNextAjax						: function() {
		//var iNextajaxLoadingStart 			= 0;

		$('a[data-nextajax]').nextajax({
			rpcFile 			: 'fileadmin/templates/rpc/GetPageContent.php',
			rootUrl				: sNextajaxRootURL,
			googleAnalytics		: true,
			debugMode: true,
			headerElements		: [
				['meta[name="language"]','content'],
				['meta[name="keywords"]','content'],
				['meta[name="description"]','content'],
				['meta[property="og:title"]','content'],
				['meta[property="og:description"]','content'],
				['meta[property="og:url"]','content'],
				['meta[property="og:image"]','content'],
				['link[rel="canonical"]','href']
			],
			beforeLoad			: function(oLink, callback) {
				var sOldSection		= "";
				var sNewSection		= "";

				// Hide breadcrjmb items
				/*if(oLink.attr('class') == 'breadCrumbLink') {
					oLink.parent().nextAll('li').remove();
				}*/

				// Show loader
				oLoader.showLoader();

				if(window.location.hash.indexOf('#!') == 0) {
					sOldSection		= window.location.hash.replace('#!/', '').split('/')[0];
				} else {
					sOldSection		= window.location.href.replace(sNextajaxRootURL, '').split('/')[0];
				}

				sNewSection			= oLink.attr('href').replace(sNextajaxRootURL, '').split('/')[0];

				// Close navigation if mobile
				if(oWindow.bIsMobile) {
					oContent.oContentContainer.jQ.attr('style', '');
					oBody.hideNavigation();
					oBody.hideSidebar();
				} else {
					if(sOldSection == sNewSection) {
						oBody.jQ.addClass('doNotHideNavigation');
					}
				}

				callback();
			},
			beforeReplace	: function(oLink, callback) {
				// Start page change
				oBody.startPageChange();

				// Hide loader
				oLoader.hideLoader();

				// Change section
				setTimeout(function() {
					/*var iHeaderHeight	= oContent.oContentContainer.jQ.children('header').height();

					oNavigation.jQ.addClass('noTransition').css('top', iHeaderHeight + 'px').removeClass('noTransition');
					oSidebar.jQ.addClass('noTransition').css('top', iHeaderHeight + 'px').removeClass('noTransition');*/

					oContent.oContent.jQ.scrollTop(0);
					oContent.oPageContainer.jQ.scrollTop(0);

					callback();

					// Change section
					oBody.jQ.removeClass('private stuff');

					if(oNavigation.jQ.children('ul').children('li.active').hasClass('private')) {
						oBody.jQ.addClass('private');
					} else if(oNavigation.jQ.children('ul').children('li.active').hasClass('stuff')) {
						oBody.jQ.addClass('stuff');
					} else {
						oBody.jQ.addClass('general');
					}

				}, oWindow.iMinPageChangeTime);
			},
			afterLoad	:  function(oLink) {
				// Finish page change
				oBody.finishPageChange();

				oBody.jQ.removeClass('doNotHideNavigation');

				// Trigger resize
				oWindow.jQ.trigger('resize');

				// Rebind toplink
				oToplink.jQ						= $('#topLink');
			}
		});
	}
};

/********/
/* BODY */
/********/

var oBody							= {
	jQ 									: null,
	startPageChange						: function() {
		oBody.jQ.addClass('changePage');
	},
	finishPageChange					: function() {
		oBody.jQ.removeClass('changePage');
	},
	showNavigation						: function() {
		oBody.jQ.addClass('showNavigation');
		oBody.jQ.removeClass('showSidebar');
	},
	hideNavigation						: function() {
		oBody.jQ.removeClass('showNavigation');
	},
	toggleNavigation					: function() {
		if(oBody.jQ.hasClass('showNavigation')) {
			oBody.hideNavigation();
		} else {
			oBody.showNavigation();
		}
	},
	showSidebar							: function() {
		oBody.jQ.addClass('showSidebar');
		oBody.jQ.removeClass('showNavigation');
	},
	hideSidebar							: function() {
		oBody.jQ.removeClass('showSidebar');
	},
	toggleSidebar						: function() {
		if(oBody.jQ.hasClass('showSidebar')) {
			oBody.hideSidebar();
		} else {
			oBody.showSidebar();
		}
	}
};

/**********/
/* LOADER */
/**********/

var oLoader							= {
	jQ 									: null,
	showLoader							: function() {
		oBody.jQ.addClass('loading');
	},
	hideLoader							: function() {
		oBody.jQ.removeClass('loading');
	},
	loadLazyImages						: function() {
		$('[data-preloadType="lazy"]').each(function() {
			var oElement 					= $(this);
			var iWindowScrollTop 			= oWindow.jQ.scrollTop();
			var iWindowScrollBottom 		= iWindowScrollTop + oWindow.iHeight;
			var iElementTop 				= oElement.offset().top;
			var iElementBottom 				= iElementTop + oElement.height();


			if((iElementTop <= iWindowScrollBottom) && (iElementBottom >= iWindowScrollTop)) {
				oLoader.loadImages(oElement);
			}
		});
	},
	loadImages 							: function(oImageElements, fCallback) {
		var fCallback						= fCallback || function(){};
		var iTotalImages					= oImageElements.length;
		var iImagesLoaded					= 0;

		if(iTotalImages > 0) {
			oImageElements.each(function() {
				var oImageElement 				= $(this);
				var oImage 						= $('<img />');
				var sPreloadType				= oImageElement.attr('data-preload');
				var sUrl						= oImageElement.attr('data-img');
				var aImageSizes					= typeof oImageElement.attr('data-imgSizes') == "undefined" ? [] : oImageElement.attr('data-imgSizes').split(',');
				var iSizeToLoad					= 0;

				if(typeof sUrl != 'undefined') {
					if(aImageSizes.length) {
						iSizeToLoad				= aImageSizes[aImageSizes.length - 1].split('x')[0];

						for(var iSizeIndex in aImageSizes) {
							var aSize 			= aImageSizes[iSizeIndex].split('x');

							if(oWindow.iWidth < aSize[0] && oWindow.iHeight < aSize[1]) {
								iSizeToLoad			= aSize[0];
								break;
							}
						}

						sUrl						= sUrl.split('.').slice(0, -1).join('.') + '_' + iSizeToLoad + '.' + sUrl.split('.').slice(-1)[0];
					}

					oImage.bind('load', function() {
						// Set image attribute
						if(oImageElement.prop("tagName").toLowerCase() == 'img') {
							oImageElement.attr('src', sUrl);
						} else {
							oImageElement.css('background-image', 'url(' + sUrl + ')');
						}

						// Remove data attr
						oImageElement.removeAttr('data-preload').removeAttr('data-img').removeAttr('data-imgSizes').removeAttr('data-preloadType');

						// Add loaded class
						oImageElement.addClass('loaded');

						iImagesLoaded++;

						if(iImagesLoaded == iTotalImages) {

							// Run callback
							fCallback(iTotalImages, oImageElements);
						}
					});

					oImage.attr('src', sUrl);
				} else {
					iImagesLoaded++;

					if(iImagesLoaded == iTotalImages) {
						// Run callback
						fCallback(iTotalImages, oImageElements);
					}
				}
			});
		} else {
			fCallback(iTotalImages, oImageElements);
		}
	}
};

/***********/
/* GALLERY */
/***********/

var oGallery						= {
	oImages 							: {
		jQ									: null
	},
	oContainer 							: {
		jQ									: null
	},
	oNavigation 						: {
		jQ									: null
	},
	oNavigationPosition					: {
		jQ									: null,
		updateCurrent						: function() {
			var iNewPosition					= oGallery.oImages.jQ.filter('.active').index('.galleryImage') + 1;

			oGallery.oNavigationPosition.jQ.find('.current').text(iNewPosition);
		}
	},
	oDetailLink							: {
		jQ 									: null,
		updateLink							: function() {
			var sDetailLink						= oGallery.oImages.jQ.filter('.active').attr('data-detailurl');

			oGallery.oDetailLink.jQ.attr('href', sDetailLink);
		}
	},
	showLoader							: function() {
		oBody.jQ.addClass('loadingGalleryImage');
	},
	hideLoader							: function() {
		oBody.jQ.removeClass('loadingGalleryImage');
	},
	isLoading							: function() {
		return oBody.jQ.hasClass('loadingGalleryImage');
	},
	keyNavigation						: function(iKeyCode) {
		if(iKeyCode == 37) {
			oGallery.oNavigation.jQ.filter('.previous').click();
		} else if(iKeyCode == 39) {
			oGallery.oNavigation.jQ.filter('.next').click();
		}
	},
	changeImage 						: function(oButton) {
		var oNavigationButton				= oButton;
		var oActiveImage					= this.oImages.jQ.filter('.active');
		var oNextImage						= null;
		var sDirection						= oButton.hasClass('next') ? 'next' : 'previous';

		if(!oGallery.isLoading()) {
			oGallery.showLoader();

			// Get next image
			if(sDirection == 'next') {
				oNextImage				= oActiveImage.next('.galleryImage');
			} else {
				oNextImage				= oActiveImage.prev('.galleryImage');
			}

			// Infinite loop
			if(oNextImage.length == 0) {
				if(sDirection == 'next') {
					oNextImage			= oGallery.oImages.jQ.first();
				} else {
					oNextImage			= oGallery.oImages.jQ.last();
				}
			}

			// Hide image info
			oGallery.oContainer.jQ.addClass('changeImage');

			// Preload image
			oLoader.loadImages(oNextImage, function() {
				if(oBody.jQ.hasClass('showGallery')) {
					oActiveImage.removeClass('active');
					oNextImage.addClass('active');
					oGallery.oContainer.jQ.removeClass('changeImage');

					oGallery.oNavigationPosition.updateCurrent();
					oGallery.oDetailLink.updateLink();

					oGallery.hideLoader();
				}
			});
		}
	}
};

/**************/
/* NAVIGATION */
/**************/

var oNavigation 					= {
	jQ									: null,
	iScrollTopOffset					: 10,
	scroll 								: function() {
		var iWindowScrollTop 				= oContent.oPageContainer.jQ.scrollTop();
		var iTopPosition					= oContent.oContentContainer.jQ.children('header').height() + oContent.oContentContainer.jQ.find('#headline').height();

		// Set new navigation position
		if(iWindowScrollTop > iTopPosition - oNavigation.iScrollTopOffset) {
			if(!oNavigation.jQ.hasClass('fixed')) {
				oNavigation.jQ.addClass('fixed').css({'top': oNavigation.iScrollTopOffset + 'px', 'position': 'fixed'});
			}
		} else {
			oNavigation.jQ.removeClass('fixed').css({'top': iTopPosition + 'px', 'position': 'absolute'});
		}
	},
	swipe								: function(oContainer, oEventStart) {
		var iStartX							= oContainer.offset().left;
		var iNavigationWidth				= oNavigation.jQ.width();

		// Allow vertical scrolling
		if((oEventStart.distX > oEventStart.distY && oEventStart.distX < -oEventStart.distY) || (oEventStart.distX < oEventStart.distY && oEventStart.distX > -oEventStart.distY)) {
			oEventStart.preventDefault();
		}

		// Disable transition
		oContainer.addClass('noTransition');

		// EVENT: Bind move
		oContainer.bind('move', function(oEventMove) {
			var iNewLeft					= iStartX + oEventMove.distX;
			var sDirection					= (oEventMove.distX < 0) ? 'left' : 'right';
			var sSide 						= (iNewLeft > 0) ? 'showNavigation' : 'showSidebar';
			var iMaxLeft					= iNavigationWidth;

			oBody.jQ.removeClass('showNavigation').removeClass('showSidebar').addClass(sSide);

			if(sDirection == 'left' && sSide != 'showNavigation' && !oSidebar.bHasSidebar) {
				iMaxLeft					= 0;

				oBody.jQ.removeClass('showSidebar');
			}

			if(iNewLeft >= iMaxLeft) {
				iNewLeft					= iMaxLeft;
			} else if(iNewLeft <= -iMaxLeft) {
				iNewLeft					= -iMaxLeft;
			}

			oContainer.css('left', iNewLeft + 'px');
		});

		// EVENT: Bind moveend
		oContent.oContentContainer.jQ.one('moveend', function(oEventMoveEnd) {
			var iNewLeft					= oContainer.offset().left;
			var sDirection					= (oEventMoveEnd.velocityX < 0) ? 'left' : 'right';
			var iPanPosition				= 0;

			// Enable transition
			// todo: manchmal wird klasse nicht entfernt
			oContainer.removeClass('noTransition');

			// Pan unfinished swipe
			if(oBody.jQ.hasClass('showSidebar')) {
				iPanPosition				= iNavigationWidth;
			} else if(oBody.jQ.hasClass('showNavigation')) {
				iPanPosition				= -iNavigationWidth;
			}

			if(iNewLeft != 0 && iNewLeft != iNavigationWidth) {
				if((iNavigationWidth / 2) <= Math.abs(iNewLeft)) {
					iNewLeft				= iPanPosition;
				} else {
					iNewLeft				= 0;
				}

				oContainer.css('left', -iNewLeft + 'px');
			}

			// Toggle status class
			if(oBody.jQ.hasClass('showSidebar')) {
				if(iNewLeft == 0) {
					oBody.hideSidebar();
				} else {
					oBody.showSidebar();
				}
			} else {
				if(iNewLeft == 0) {
					oBody.hideNavigation();
				} else {
					oBody.showNavigation();
				}
			}

			// TODO: Fix link problem on moveend

			oContainer.unbind('move');
		});
	}
};

/***********/
/* SIDEBAR */
/***********/

var oSidebar 						= {
	jQ									: null,
	iScrollTopOffset					: 10,
	bHasSidebar							: false,
	scroll 								: function() {
		var iWindowScrollTop 				= oContent.oPageContainer.jQ.scrollTop();
		var iTopPosition					= oContent.oContentContainer.jQ.children('header').height() + oContent.oContentContainer.jQ.find('#headline').height();

		// Set new sidebar position
		if(iWindowScrollTop > iTopPosition - oSidebar.iScrollTopOffset) {
			if(!oSidebar.jQ.hasClass('fixed')) {
				var iRightPosition			= 0;

				if(!oWindow.bIsMobile) {
					iRightPosition			= oContent.oPageContainer.jQ.width() - oContent.oPageContainer.jQ[0].clientWidth
				}

				oSidebar.jQ.addClass('fixed').css({'top': oSidebar.iScrollTopOffset + 'px', 'margin-right': iRightPosition + 'px', 'position': 'fixed'});
			}
		} else {
			oSidebar.jQ.removeClass('fixed').css({'top': iTopPosition + 'px', 'margin-right': '0', 'position': 'absolute'});
		}
	},
	checkSidebar						: function() {
		oSidebar.bHasSidebar				= oSidebar.jQ.children().length > 0;
	}
};

/***********/
/* CONTENT */
/***********/

var oContent						= {
	oPageContainer						: {
		jQ									: null
	},
	oContentContainer					: {
		jQ									: null
	},
	oContent 							: {
		jQ 									: null
	}
};

/***********/
/* TOPLINK */
/***********/

var oToplink						= {
	jQ 									: null,
	scroll 								: function() {
		var iScrollTop 						= 0;

		if(oWindow.bIsMobile) {
			iScrollTop = oContent.oContent.jQ.scrollTop();
		} else {
			iScrollTop = oContent.oPageContainer.jQ.scrollTop();
		}

		if(iScrollTop > oContent.oContentContainer.jQ.children('header').height()) {
			oToplink.jQ.addClass('show');
		} else {
			oToplink.jQ.removeClass('show');
		}
	},
	scrollTop 							: function() {
		if(oWindow.bIsMobile) {
			oContent.oContent.jQ.animate({scrollTop : 0}, 'slow');
		} else {
			oContent.oPageContainer.jQ.animate({scrollTop : 0}, 'slow');
		}
	},
	bind 								: function() {
		$(document).on('click', '#topLink', function() {
			oToplink.scrollTop();
		});
	}
};

/**********/
/* ONLOAD */
/**********/

$(window).load(function() {
	// Cache objects
	oWindow.jQ						= $(window);
	oBody.jQ						= $('body');
	oLoader.jQ 						= $('#pageLoader');
	oNavigation.jQ					= $('#mainNavigation');
	oSidebar.jQ						= $('aside');
	oGallery.oImages.jQ				= $('.galleryImage');
	oGallery.oContainer.jQ			= $('#galleryContainer');
	oGallery.oNavigation.jQ			= $('.galleryNavigation');
	oGallery.oNavigationPosition.jQ	= oGallery.oNavigation.jQ.filter('.position');
	oGallery.oDetailLink.jQ			= oGallery.oNavigation.jQ.filter('.detail');
	oContent.oPageContainer.jQ		= $('#pageContainer');
	oContent.oContentContainer.jQ	= $('#contentContainer');
	oContent.oContent.jQ			= $('#content');
	oToplink.jQ						= $('#topLink');

	// Bind responsivehelper
	if(typeof oBody.jQ.responsivehelper == "function") {
		oBody.jQ.responsivehelper({
			breakpoints: [{
				name: "Normal Max",
				breakpoint: 1920
			}, {
				name: "Normal S4",
				breakpoint: 1488
			}, {
				name: "Normal M2",
				breakpoint: 1112
			}, {
				name: "Normal S2",
				breakpoint: 930
			}, {
				name: "Normal S1",
				breakpoint: 748
			}, {
				name: "Mobile S2",
				breakpoint: 562
			}, {
				name: "Mobile S1",
				breakpoint: 1
			}]
		});
	}

	// Bind nextajax
	if(typeof sNextajaxRootURL !== 'undefined') {
		oWindow.bindNextAjax();
	}

	// Check if sidebar exists
	oSidebar.checkSidebar();

	// Set the gallery total count and detail url
	oGallery.oNavigationPosition.jQ.find('.total').text(oGallery.oImages.jQ.length);
	oGallery.oDetailLink.updateLink();

	// EVENT: Change to page
	oGallery.oDetailLink.jQ.bind('click', function() {
		$('.changeToPage').click();
	});

	// EVENT: Toplink
	oToplink.bind();

	// EVENT: Navigation toggle
	$(document).on('click', '.toggleNavigation', function() {
		oBody.toggleNavigation();
		oContent.oContentContainer.jQ.removeClass('noTransition');
		oContent.oContentContainer.jQ.attr('style', '');
	});

	// EVENT: Sidebar toggle
	$(document).on('click', '.toggleSidebar', function() {
		oBody.toggleSidebar();
		oContent.oContentContainer.jQ.removeClass('noTransition');
		oContent.oContentContainer.jQ.attr('style', '');
	});

	// EVENT: Change to gallery
	$(document).on('click', '.changeToGallery', function() {
		oContent.oContentContainer.jQ.removeClass('noTransition');
		oContent.oContentContainer.jQ.attr('style', '');
		oBody.jQ.addClass('showGallery').removeClass('showPage showNavigation');
	});

	// EVENT: Change to page
	$('.changeToPage').bind('click', function() {
		oBody.jQ.addClass('showPage').removeClass('showGallery');
	});

	// EVENT: Change gallery image
	$('.galleryNavigation').filter('.previous, .next').click(function() {
		oGallery.changeImage($(this));
	});

	// EVENT: Change gallery image (keyup)
	$(window).bind('keyup', function(oEvent) {
		if(oBody.jQ.hasClass('showGallery')) {
			oGallery.keyNavigation(oEvent.keyCode);
		}
	});

	// EVENT: Resize
	oWindow.jQ.bind('resize', function() {
		oWindow.resize();
		oLoader.loadLazyImages();
	});

	// EVENT: Scroll (non mobile)
	oContent.oPageContainer.jQ.bind('scroll', function() {
		oLoader.loadLazyImages();
		oNavigation.scroll();
		oSidebar.scroll();
		oToplink.scroll();
	});

	// EVENT: Scroll (mobile)
	oContent.oContent.jQ.bind('scroll', function() {
		oLoader.loadLazyImages();
		oToplink.scroll();
	});

	// EVENT: Enable active-state for mobile
	oBody.jQ.bind('touchstart', function(){});

	// TRIGGER: Resize
	oWindow.jQ.trigger('resize');

	// TRIGGER: Load auto images
	oLoader.loadImages($('[data-preloadType="auto"]'), function(iCount) {
		oLoader.hideLoader();
		oBody.jQ.removeClass('init');
	});

	// EVENT: Bind facebook like button
	$(document).on('click', '#facebookLike', function(oEvent) {
		oEvent.preventDefault();

		var sUrl 					= $('link[rel="canonical"]').attr('href');

		window.open(
			'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(sUrl),
			'facebook-share-dialog',
			'width=626,height=436'
		);
	});

	// Calculate hint buttons

	$(document).on('mouseenter', '.button.hint', function() {
		if(!oWindow.bIsMobile) {
			var oElement 			= $(this);
			var iWidth				= 0;
			var iStartWidth			= oElement.width();

			oElement.children().each(function() {
				iWidth				+= $(this).outerWidth();
			});


			$(this).css('width', iWidth + 'px');

			oElement.one('mouseleave', function() {
				$(this).css('width', $(this).children().first().width() + 'px');
			});
		}
	});
});