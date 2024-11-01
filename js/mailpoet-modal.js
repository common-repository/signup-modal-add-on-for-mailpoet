jQuery(function() {
    if(typeof mmdsgvo !== "object") return;
    var mmdsgvoWrapper;
    var modal;
    var modalInner;
    var closeBtn;

    // Forms Events & Selectors
    var form;
    var formSubmit;
    var errorContainer;

    var getCookiePanelCookie = function() {
        var name = 'lampCookieConsent';
        var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
    };

    var setButtonState = function(enabled) {
        if(!enabled) {
            formSubmit.attr("disabled", true);
        } else {
            formSubmit.removeAttr("disabled");
        }

    };

    var handleFormSuccess = function(data, textStatus, XMLHttpRequest) {
        if(data.subscribed && mmdsgvo.success) {
            store_opt_out();
            handleTracking(mmdsgvo.register);
            window.setTimeout(function() {
                window.location = mmdsgvo.success;
                hideModal();
                setButtonState(true);
            }, 750);
        }

        if(!data.subscribed) {
            showOptOutChoice();
        }

        window.setTimeout(function() {
            setButtonState(true);
        }, 750);
    };

    var handleTracking = function(actionJs) {
        try {
            var cookieConsentData = getCookiePanelCookie();
            var cookieConsentGrps = cookieConsentData.split(',');
            var cookieConsentActiveGrps = '';
            if(cookieConsentGrps[1] === "group-2.1") { // statistics is checked
                eval(actionJs);
            }
        } catch (e) {
            return false;
        }
    }

    var showOptOutChoice = function() {
        errorContainer.html(mmdsgvo.error).show();
        handleResize();
        store_opt_out();
    }

    var handleFormFail = function(XMLHttpRequest, textStatus, errorThrown) {
        showOptOutChoice();
        window.setTimeout(function() {
            setButtonState(true);
        }, 750);
    };

    var handleFormSubmit = function (event) {
        setButtonState(false);
        jQuery.ajax({
            type: 'POST',
            url: mmdsgvo.ajaxurl,
            data: $(this).serialize(),
            success: handleFormSuccess,
            error: handleFormFail
        });
        event.preventDefault();
        return false;
    }

    var showModal = function() {
        window.setTimeout(function() {
            handleResize();
        }, 100);
        window.setTimeout(function() {
            handleResize();
        }, 200);
        window.setTimeout(function() {
            handleResize();
        }, 300);
        mmdsgvoWrapper.fadeIn(800, function() {
            handleResize();
        }).css({
                position: 'absolute'
            }).animate({
            top: '275'
            }, function() {
        });

        localStorage.removeItem('mmdsgvo_start');
        var restartTime = parseInt(Date.now()/1000 + (mmdsgvo.restart*60) );
        localStorage.setItem('mmdsgvo_restart', restartTime);
        handleTracking(mmdsgvo.view);
    }

    var handleResize = function() {
        modal.css('height', modalInner.height()+'px');
    }

    var hideModal = function() {
        mmdsgvoWrapper.hide();
    }

    var store_opt_out = function() {
        localStorage.setItem('mmdsgvo_opt_out', "1");
        localStorage.removeItem('mmdsgvo_restart');
        localStorage.removeItem('mmdsgvo_start');
    }

    var get_start_time = function () {
        var startTime = localStorage.getItem('mmdsgvo_start');

        if(startTime === null) {
            localStorage.setItem('mmdsgvo_start', parseInt(Date.now()/1000));
            return parseInt(Date.now()/1000);
        }
        return parseInt(startTime);
    }

    var currentTime = parseInt(Date.now()/1000);

    // Register Events add Markup to DOM
    jQuery('body').append(mmdsgvo.markup);

    mmdsgvoWrapper = jQuery('#mmdsgvo');
    modal = mmdsgvoWrapper.find('.modal');
    modalInner = mmdsgvoWrapper.find('.modal-inner-content');
    closeBtn = modal.find('.close-button');

    // Forms Events & Selectors
    form = jQuery('form.mmdsgvo-form');
    formSubmit = form.find('.submit');
    errorContainer = form.find('.error-message');

    closeBtn.on('click', hideModal);
    form.on('submit', handleFormSubmit);

    jQuery(window).on('resize', handleResize);


    try {
        var searchParams = new URL(document.location).searchParams;
        if(searchParams.has('mmdsgvoPreviewMode')) {
            showModal();
        }
    } catch (e) {

    }

    if(parseInt(mmdsgvo.active) === 0) {
        return;
    }

    // Opt Out Handling
    var optOut = localStorage.getItem('mmdsgvo_opt_out');
    if(optOut === "1") {
        return;
    }

    // Restart Handling
    var restartTime = localStorage.getItem('mmdsgvo_restart');
    if(restartTime !== null) {
        var restartTimer = parseInt(restartTime);
        if(currentTime <= restartTimer) {
            return;
        }
    }

    if(mmdsgvo.mode === "delay") {
        var startTime = get_start_time();

        var displayTime = startTime + parseInt(mmdsgvo.delay);
        var delay = 0;

        if(currentTime >= displayTime) {
            delay = 0;
        } else {
            delay = displayTime - currentTime;
        }

        window.setTimeout(showModal, delay*1000);
    }

});