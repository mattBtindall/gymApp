import { setActiveElement, getPhpMethodUrl, getData } from "./utils.js";

export class Dashboard {
    constructor() {
        this.elements = {
            revenue: {
                filtersContainer: document.querySelector('.revenue-filters'),
                filters: document.querySelectorAll('.revenue-filters li'),
                chartOutput: document.querySelector('.revenue-chart-output')
            },
            visits: {
                filtersContainer: document.querySelector('.visits-filters'),
                filters: document.querySelectorAll('.visits-filters li'),
                chartOutput: document.querySelector('.vists-chart-output'),
                rightArrow: document.querySelectorAll('.visits-filters i')[1],
                leftArrow: document.querySelectorAll('.visits-filters i')[0]
            },
            membersOverview: {
                container: document.querySelector('.member-overview-container'),
                titlesContainer: document.querySelector('.member-overview-container .titles'),
                titles: document.querySelectorAll('.member-overview-container h3')
            }
        }

        this.filters = {
            revenue: '4 weeks',
            visits: '1 week',
        }
        this.visitsFilterNumber = 1;

        this.setEventListeners();
        this.getAllData()
            .then(data => console.log(data));
    }

    setEventListeners() {
        this.elements.revenue.filtersContainer.addEventListener('click', (e) => this.revenueFilterClick(e.target));
        this.elements.visits.filtersContainer.addEventListener('click', (e) => this.visitsFilterClick(e.target))
        this.elements.membersOverview.titlesContainer.addEventListener('click', (e) => this.membersOverviewClick(e.target));
    }

    revenueFilterClick(currentElement) {
        if (currentElement.tagName === 'LI') {
            setActiveElement(this.elements.revenue.filters, currentElement);
            this.filters.revenue = currentElement.dataset.filterValue;
        }
    }

    visitsFilterClick(currentElement) {
        // need to increment/decrement and wrap here
        const { leftArrow, rightArrow, filters} = this.elements.visits;

        if (currentElement === leftArrow) {
            this.visitsFilterNumber = this.decrement(this.visitsFilterNumber, filters.length, 0);
        } else if (currentElement === rightArrow) {
            this.visitsFilterNumber = this.increment(this.visitsFilterNumber, filters.length);
        } else {
            return;
        }

        setActiveElement(filters, filters[this.visitsFilterNumber]);
        this.filters.visits = filters[this.visitsFilterNumber].dataset.filterValue;
    }

    membersOverviewClick(currentElement) {
        if (currentElement.tagName === 'H3') {
            setActiveElement(this.elements.membersOverview.titles, currentElement);
        }
    }

    increment(n, maxValue) { // and wrap
        return ++n % maxValue;
    }

    decrement(n, maxValue, minValue) { // and wrap
        return (n ? n : maxValue) - 1;
    }

    setRevenueChart() {

    }

    setVisitsChart() {

    }

    getAllData() {
        const url = getPhpMethodUrl(`/Dashboards/getData/${this.filters.revenue}/${this.filters.visits}`);
        return getData(url);
    }
}
