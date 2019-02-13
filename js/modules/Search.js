import $ from 'jquery';

class Search {
    constructor() {
        //add html to body
        this.addHtml();
        //set variables
        this.resultsDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.isOverlayOpen = false;
        this.searchTerm = $("#searchInput");
        this.typingTimer;
        this.isSpinnerSet = false;
        this.searchingValue;

        this.events();   
    }

    events() {
        //set events
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click",this.closeOverlay.bind(this));
        $(document).on('keydown', this.keyPressDispatcher.bind(this));
        this.searchTerm.on('keyup', this.searchForTerm.bind(this));
    }

    searchForTerm() {
        if(this.searchTerm.val() != this.searchingValue) {
            //clear timeout if keyup pressed continiously
            clearTimeout(this.typingTimer);

            if(this.searchTerm.val()) {
                if(!this.isSpinnerSet) {
                    //set spinner
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerSet = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                //clear spinner if no term is detected
                this.resultsDiv.html('');
                this.isSpinnerSet = false;
            }
            
        }
        this.searchingValue = this.searchTerm.val();
    }


    getResults() {
        this.resultsDiv.html('');
        this.isSpinnerSet = false;
        const customURL = 'http://localhost:3000/wordpress-university/wp-json/university/v1/search?term=' + this.searchTerm.val();
        $.getJSON(customURL, (data) => {

            this.resultsDiv.html(`
                <div class="row">

                    <div class="one-third"> 
                        <h2 class="search-overlay__section-title"> General Information </h2>
                        ${data.generalInfo.length > 0 ? '<ul class="link-list min-list">' : '<p>No Results have been found </p>'}
                        ${data.generalInfo.map((item) => `<li><a href="${item.permalink}">${item.title} ${item.author_name !== '' ? `by <a href="${item.author_link}">${item.author_name}</a>` : ''}</a></li>`).join('')}
                        </ul>
                    </div>

                    <div class="one-third"> 
                        <h2 class="search-overlay__section-title"> Programs </h2>
                        ${data.programs.length > 0 ? '<ul class="link-list min-list">' : `<p>No Programs have been found. <a href="${universityData.root_url + '/programs'}">All programs can be found here!</a> </p>`}
                        ${data.programs.map((item) => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                        </ul>

                        <h2 class="search-overlay__section-title"> Professors </h2> 
                        ${data.professors.length > 0 ? '<ul class="professor-cards">' : `<p>No Professors have been found.</p>`}
                        ${data.professors.map((item) => `
                        <li class="professor-card__list-item">
                            <a class="professor-card" href="${item.permalink}">
                                <img class="professor-card__image"  src="${item.thumbnail}" alt="Professor Photo">
                                <span class="professor-card__name">${item.title}</span>
                            </a>
                        </li>
                        `).join('')}
                        </ul>
                        
                    </div>

                    <div class="one-third">

                        <h2 class="search-overlay__section-title"> Events </h2>
                        ${data.events.length > 0 ? '<ul class="professor-cards">' : `<p>No Professors have been found.</p>`}
                        ${data.events.map((item) => `
                        <div class="event-summary">
                            <a class="event-summary__date t-center" href="#">
                                <span class="event-summary__month">
                                    ${item.date_month}
                                </span>
                                <span class="event-summary__day">${item.date_day}</span>  
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                                <p>${item.excerpt}<a href="${item.permalink}" class="nu gray">Learn more</a></p>
                            </div>
                        </div>
                        `).join('')}
                        </ul>

                    </div>

                </div>
            `);

        }, (err) => {
            console.log('There has been an error in retrieving ~the information: ' + err);
        });        
    }

    //feature to press S to open search bar and tab to close it
    keyPressDispatcher(event) {
        
        if(event.keyCode === 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
            this.openOverlay();
        }

        if(event.keyCode === 81) {
            this.closeOverlay();
        }

    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass('body-no-scroll');
        this.searchTerm.val('');
        setTimeout(() => this.searchTerm.focus(), 350);
        this.isOverlayOpen = true;
        return false;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").remove('body-no-scroll');
        this.isOverlayOpen = false;
    }

    addHtml() {
        $("body").append(`
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                    <i class="fas fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" class="search-term" placeholder="What are you looking for?" id="searchInput">
                    <i class="fas fa-window-close search-overlay__close" aria-hidden="true"></i>

                </div>
            </div>

            <div class="container">
                <div id="search-overlay__results">
                    
                </div>
            </div>
        </div>
        `);
    }
}

export default Search;