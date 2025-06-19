// import html2canvas from 'html2canvas';
// import * as JsPDF from 'jspdf';

window.WPRMPrint = {
    args: {},
    setArgs( args ) {
        this.args = args;
        document.dispatchEvent( new Event( 'wprmPrintArgs' ) );
    },
	init() {
        this.checkToggles();

        // On click print button.
        document.querySelector( '#wprm-print-button-print' ).addEventListener( 'click', (e) => {
            e.preventDefault();
            this.onClickPrint();
        });

        // On click toggle.
        const toggles = [ ...document.querySelectorAll( '.wprm-print-toggle' )];

        for ( let toggle of toggles ) {
            // Event listener.
            toggle.addEventListener( 'change', (e) => {
                this.onClickToggle( toggle );
            });

            // Initial state.
            this.onClickToggle( toggle );
        }

        // On click Email.
        const emailButton = document.querySelector( '#wprm-print-button-email' );
        if ( emailButton ) {
            emailButton.addEventListener( 'click', (e) => {
                e.preventDefault();
                this.onClickEmail();
            });
        }

        // On click PDF.
        const pdfButton = document.querySelector( '#wprm-print-button-pdf' );
        if ( pdfButton ) {
            pdfButton.addEventListener( 'click', (e) => {
                e.preventDefault();
                this.onClickPdf();
            });
        }

        // Size changer.
        this.sizeChanger = document.querySelector( '#wprm-print-size-container' );
        this.initSizeChanger();

        // Add padding at bottom of recipe template to fit footer ad.
        const footerAd = document.querySelector( '#wprm-print-footer-ad' );
        if ( footerAd ) {
            const footerAdHeight = footerAd.offsetHeight;
            document.querySelector( 'body' ).style.paddingBottom = footerAdHeight + 'px';
        }

        // Optional remove links.
        if ( window.hasOwnProperty( 'wprm_print_settings' ) && window.wprm_print_settings.print_remove_links ) {
            this.removeLinks();
        }

        // Trigger init event.
        document.dispatchEvent( new Event( 'wprmPrintInit' ) );

        // Check for print args set in local storage. Needs to happen after init.
        let args = localStorage.getItem( 'wprmPrintArgs' );
        localStorage.removeItem( 'wprmPrintArgs' );

        if ( args ) {
            args = JSON.parse( args );

            if ( args && args.hasOwnProperty( 'id' ) ) {
                const firstRecipe = document.querySelector( '#wprm-print-recipe-0' );
                const firstRecipeId = firstRecipe && firstRecipe.dataset.hasOwnProperty( 'recipeId' ) ? parseInt( firstRecipe.dataset.recipeId ) : false;

                if ( firstRecipeId && firstRecipeId === parseInt( args.id ) ) {
                    this.setArgs( args );
                }
            }
        }
    },
    removeLinks() {
        const links = document.querySelector( '#wprm-print-content' ).querySelectorAll( 'a:not(.wprm-recipe-link)' );

        for ( let link of links ) {
            link.outerHTML = '<span>' + link.innerHTML + '</span>';
        }
    },
    checkToggles() {
        // Check if recipe image is present.
        const images = document.querySelectorAll( '.wprm-recipe-image' );

        if ( ! images.length ) {
            const toggle = document.querySelector( '#wprm-print-toggle-recipe-image' );
            if ( toggle ) {
                toggle.parentNode.style.display = 'none';
            }
        }

        // Check if recipe equipment is present.
        const equipment = document.querySelectorAll( '.wprm-recipe-equipment-container' );

        if ( ! equipment.length ) {
            const toggle = document.querySelector( '#wprm-print-toggle-equipment' );
            if ( toggle ) {
                toggle.parentNode.style.display = 'none';
            }
        }

        // Check if ingredient images are present.
        const ingredientMedia = document.querySelectorAll( '.wprm-recipe-ingredient-image' );

        if ( ! ingredientMedia.length ) {
            const toggle = document.querySelector( '#wprm-print-toggle-recipe-ingredient-media' );
            if ( toggle ) {
                toggle.parentNode.style.display = 'none';
            }
        }

        // Check if notes are present.
        const notes = document.querySelectorAll( '.wprm-recipe-notes-container' );

        if ( ! notes.length ) {
            const toggle = document.querySelector( '#wprm-print-toggle-recipe-notes' );
            if ( toggle ) {
                toggle.parentNode.style.display = 'none';
            }
        }

        // Check if nutrition label is present.
        const nutrition = document.querySelectorAll( '.wprm-nutrition-label-container' );

        if ( ! nutrition.length ) {
            const toggle = document.querySelector( '#wprm-print-toggle-recipe-nutrition' );
            if ( toggle ) {
                toggle.parentNode.style.display = 'none';
            }
        }
    },
    onClickToggle( toggle ) {
        // Get elements to toggle.
        let elems = [];
        if ( 'wprm-print-toggle-recipe-image' === toggle.id ) {
            elems = document.querySelectorAll( '.wprm-recipe-image, .wprm-condition-field-image:not(.wprm-condition-inverse)' );
        } else if ( 'wprm-print-toggle-recipe-equipment' === toggle.id ) {
            elems = document.querySelectorAll( '.wprm-recipe-equipment-container' );
        } else if ( 'wprm-print-toggle-recipe-ingredient-media' === toggle.id ) {
            elems = document.querySelectorAll( '.wprm-recipe-ingredient-image' );
        } else if ( 'wprm-print-toggle-recipe-instruction-media' === toggle.id ) {
            elems = document.querySelectorAll( '.wprm-recipe-instruction-media' );
        } else if ( 'wprm-print-toggle-recipe-notes' === toggle.id ) {
            elems = document.querySelectorAll( '.wprm-recipe-notes-container' );
        } else if ( 'wprm-print-toggle-recipe-nutrition' === toggle.id ) {
            elems = document.querySelectorAll( '.wprm-recipe-nutrition-header, .wprm-nutrition-label-container, .wprm-condition-field-nutrition:not(.wprm-condition-inverse)' );
        } else if ( 'wprm-print-toggle-collection-name' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-container-header-container' );
        } else if ( 'wprm-print-toggle-collection-description' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-collection-description' );
        } else if ( 'wprm-print-toggle-collection-images' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-collection-item-image' );
        } else if ( 'wprm-print-toggle-collection-servings' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-collection-item-servings' );
        } else if ( 'wprm-print-toggle-collection-nutrition' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-collection-column-nutrition, .wprmprc-collection-item-nutrition' );
        } else if ( 'wprm-print-toggle-collection-qr' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-collection-item-qr' );
        } else if ( 'wprm-print-toggle-shopping-list-collection' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-shopping-list-collection' );
        } else if ( 'wprm-print-toggle-shopping-list' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-shopping-list-list' );
        } else if ( 'wprm-print-toggle-checked-items' === toggle.id ) {
            elems = document.querySelectorAll( '.wprmprc-shopping-list-list-ingredient-checked' );
        }

        // Toggle display for elems.
        for ( let elem of elems ) {
            if ( toggle.checked ) {
                elem.style.display = '';
            } else {
                elem.style.display = 'none';
            }
        }
    },
    onClickPrint() {
        // Optional set URL to have it display nice in the print footer.
        let currentUrl = false;
        if ( window.hasOwnProperty( 'wprm_print_url' ) && window.wprm_print_url ) {
            currentUrl = window.location.href;
            window.history.replaceState( {}, document.title, window.wprm_print_url );
        }

        // Use setTimeout to prevent print window going blank in Safari.
        setTimeout( () => {
            window.print();
        }, 200 );

        if ( currentUrl ) {
            window.history.replaceState( {}, document.title, currentUrl );
        }
    },
    onClickEmail() {
        window.location = 'mailto:?body=' + window.location;
    },
    onClickPdf() {
        // const printContent = document.getElementById( 'wprm-print-content' );

        // html2canvas( printContent, {
        //     scrollY: -window.scrollY,
        //     imageTimeout: 5000,
        //     useCORS: true,
        // }).then( canvas => {
        //     document.getElementById( 'print-pdf' ).appendChild( canvas );

        //     let img = canvas.toDataURL( 'image/png' );
        //     let pdf = new JsPDF( 'portrait', 'mm' );

        //     pdf.addImage( img, 'JPEG', 5, 5, 200, 287 );
        //     pdf.save( document.title + '.pdf' );

        //     document.getElementById( 'print-pdf' ).innerHTML = '';
        // });
    },
    sizeChanger: false,
    initSizeChanger() {
        if ( this.sizeChanger ) {
            const options = this.sizeChanger.querySelectorAll( '.wprm-print-option' );

            // On click.
            for ( let option of options ) {
                option.addEventListener( 'click', () => {
                    this.setSize( option.dataset.size );
                });
            }
        }
    },
    setSize( size ) {
        if ( ['small', 'normal', 'large'].includes( size ) ) {
            const contentOptions = document.querySelectorAll( '#wprm-print-content, .wprm-recipe-collections-layout-grid, .wprm-recipe-collections-layout-classic' );

            for ( let content of contentOptions ) {
                switch ( size ) {
                    case 'small':
                        content.style.fontSize = '0.8em';
                        break;
                    case 'normal':
                        content.style.fontSize = '';
                        break;
                    case 'large':
                        content.style.fontSize = '1.2em';
                        break;
                }
            }            

            if ( this.sizeChanger ) {
                const options = this.sizeChanger.querySelectorAll( '.wprm-print-option' );
                for ( let option of options ) {
                    option.classList.remove( 'option-active');

                    if ( size === option.dataset.size ) {
                        option.classList.add( 'option-active' );
                    }
                }
            }
        }
    },
    maybeRedirect( url ) {
        if ( url && 0 === Object.keys( this.args ).length ) {
            window.location.replace( url );
        }
    },
};

ready(() => {
	window.WPRMPrint.init();
});

function ready( fn ) {
    if (document.readyState != 'loading'){
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}