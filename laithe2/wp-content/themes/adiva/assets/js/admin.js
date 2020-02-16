var jmsImportAdmin;
(function($) {
    "use strict";
    var samplePage = function( $form ) {

        this.form = $form;

        this.interval = 0;

        this.sequence = false;

        this.mainArea = $('.jms-import-area');

        this.responseArea = this.form.find('.import-response');

        this.progressBar = this.form.find('.jms-import-progress');

        this.verSelect = this.form.find('.page_select');

        this.pagePreviews();

        $form.on('submit', { importBox: this}, this.formSubmit )

    };

    samplePage.prototype.formSubmit = function(e) {
        e.preventDefault();

        var importBox = e.data.importBox;

        var $form = importBox.form;

        if( $form.hasClass('form-in-action') ) return;

        $form.addClass('form-in-action');

        var page = $form.find('.page_select').val();

        if( $form.find('#full_import').prop('checked') == true ) {
            importBox.sequence = true;
            var allpages = $form.find('.adiva_pages').val();

            var subLenght = 3;

            var ajaxSuccess = function( response ) {

                if( ! pages[i] ) {
                }
            };

            var ajaxComplete = function() {

                if( ! pages[i] ) {

                    importBox.form.removeClass('form-in-action');

                    importBox.updateProgress( importBox.progressBar, 100, 0 );

                    importBox.initialClearer = setTimeout(function() {
                        importBox.destroyProgressBar(200);
                    }, 2000 );

                    importBox.mainArea.addClass( "full-imported right-after-import " +  pages.join("-imported ") + '-imported' );
                    importBox.mainArea.find('.full-import-box').remove();
                } else {
                    importBox.updateProgress( importBox.progressBar, progressSteps * i, 350 );

                    importBox.callImportAJAX( pages.slice(i, i + subLenght).join(','), ajaxSuccess, ajaxComplete );
                    i = i + subLenght;
                }
            };

            var pages = allpages.split(',');
            var i = 0;
            var progressSteps = 95 / pages.length;

            importBox.callImportAJAX( pages[i++], ajaxSuccess, ajaxComplete );

            importBox.updateProgress( importBox.progressBar, progressSteps, 350 );

            return;
        }

        clearInterval( importBox.initialClearer );

        importBox.fakeLoading( 30, 50, 70 );

        importBox.clearResponseArea();
        importBox.callImportAJAX( page, function(response) {

            importBox.clearResponseArea();
            importBox.handleResponse(response);

        }, function() {

            importBox.clearFakeLoading();

            importBox.form.removeClass('form-in-action');

            importBox.updateProgress( importBox.progressBar, 100, 0 );

            importBox.progressBar.parent().find('.jms-notice').remove();

            importBox.mainArea.addClass( "right-after-import imported-" +  page );

            importBox.initialClearer = setTimeout(function() {
                importBox.destroyProgressBar(200);
            }, 2000 );
        } );
    };


    samplePage.prototype.callImportAJAX = function( page, success, complete ) {
        var box = this;
        $.ajax({
            url: adivaConfig.ajax,
            data: {
                import_page: page,
                action: "jms_import_data",
                sequence: box.sequence
            },
            timeout: 1000000,
            success: function( response ) {

                if( success ) success( response );

            },
            error: function( response ) {
                box.responseArea.html( '<div class="jms-warning">Import AJAX problem. Please try import again.</div>' ).fadeIn();
                console.log('import ajax ERROR');
            },
            complete: function() {

                if( complete ) complete();

            },
        });
    };

    samplePage.prototype.handleResponse = function( response ) {
        var rJSON = { status: '', message: '' };

        try {
            rJSON = JSON.parse(response);
        } catch( e ) {}

        if( ! response ) {
            this.responseArea.html( '<div class="jms-warning">Empty AJAX response, please try again.</div>' ).fadeIn();
        } else if( rJSON.status == 'success' ) {
            console.log(rJSON.message);
            this.responseArea.html( '<div class="jms-success">All data imported successfully!</div>' ).fadeIn();
        } else if( rJSON.status == 'fail' ) {
            this.responseArea.html( '<div class="jms-error">' + rJSON.message + '</div>' ).fadeIn();
        } else {
            this.responseArea.html( '<div class="">' + response + '</div>' ).fadeIn();
        }

    };


    samplePage.prototype.fakeLoading = function(fake1progress, fake2progress, noticeProgress) {
        var that = this;

        this.destroyProgressBar(0);

        this.updateProgress( this.progressBar, fake1progress, 350 );

        this.fake2timeout = setTimeout( function() {
            that.updateProgress( that.progressBar, fake2progress, 100 );
        }, 25000 );

        this.noticeTimeout = setTimeout( function() {
            that.updateProgress( that.progressBar, noticeProgress, 100 );
            that.progressBar.after( '<p class="jms-notice small">Please, wait. Theme needs much time to download all attachments</p>' );
        }, 60000 );

        this.errorTimeout = setTimeout( function() {
            that.progressBar.parent().find('.jms-notice').remove();
            that.progressBar.after( '<p class="jms-notice small">Something wrong with import. Please, try to import data manually</p>' );
        }, 3100000 );
    };

    samplePage.prototype.clearFakeLoading = function() {
        clearTimeout( this.fake2timeout );
        clearTimeout( this.noticeTimeout );
        clearTimeout( this.errorTimeout );
    };

    samplePage.prototype.destroyProgressBar = function( hide ) {
        this.progressBar.hide( hide ).attr('data-progress', 0).find('div').width(0);
    };

    samplePage.prototype.clearResponseArea = function() {
        this.responseArea.fadeOut(200, function() {
            $(this).html( '' );
        });
    };

    samplePage.prototype.updateProgress = function( el, to, interval ) {
        el.show();
        var box = this;

        clearInterval( box.interval );

        var from = el.attr('data-progress'),
            i = from;

        if( interval == 0 ) {
            el.attr('data-progress', 100).find('div').width(el.attr('data-progress') + '%');
        } else {
            box.interval = setInterval(function() {
                i++;
                el.attr('data-progress', i).find('div').width(el.attr('data-progress') + '%');
                if( i >= to ) clearInterval( box.interval );
            }, interval);
        }

    };

    samplePage.prototype.pagePreviews = function() {
        var preview = this.form.find('.page-preview'),
            image = preview.find('img'),
            dir = image.data('dir'),
            newImage = '';

        image.on('load', function() {
          // do stuff on success
            $(this).removeClass('loading-image');
        }).on('error', function() {
          // do stuff on smth wrong (error 404, etc.)
            $(this).removeClass('loading-image');
        }).each(function() {
            if(this.complete) {
              $(this).load();
            } else if(this.error) {
              $(this).error();
            }
        });

        this.verSelect.on('change', function() {
            var page = $(this).val();

            if( page == '' || page == '--select--' ) page = 'base';

            newImage = dir + '/' + page + '/preview.jpg';

            image.addClass('loading-image').attr('src', newImage);
        });
    };


    $.fn.import_box = function() {
        new samplePage( this );
        return this;
    };

    jmsImportAdmin = (function() {

        var importAdmin = {
            importAction: function() {
                $('.jms-import-form').each(function() {
                    $(this).import_box();
                })
            },
        };

        return {
            init: function() {
                importAdmin.importAction();
            }
        }

    }());

})(jQuery);

jQuery(document).ready(function() {
    jmsImportAdmin.init();
});
