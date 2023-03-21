import { setActiveElement, getPhpMethodUrl, getData, modals, userData } from "./utils.js";
import { Chart } from "chart.js/auto";

export class Dashboard {
    constructor() {
        this.elements = {
            activeMembers: {
                output: document.querySelector('.active-members-output')
            },
            revenue: {
                filtersContainer: document.querySelector('.revenue-filters'),
                filters: document.querySelectorAll('.revenue-filters li'),
                chartOutput: document.querySelector('.revenue-chart-output')
            },
            visits: {
                filtersContainer: document.querySelector('.visits-filters'),
                filters: document.querySelectorAll('.visits-filters li'),
                output: document.querySelector('.visits-output'),
                rightArrow: document.querySelectorAll('.visits-filters i')[1],
                leftArrow: document.querySelectorAll('.visits-filters i')[0],
                percentageDifferenceContainer: document.querySelector('.percentage-difference-container'),
                percentageDifferenceOutput: document.querySelector('.percentage-difference-output'),
                percentageDifferenceIcon: document.querySelector('.percentage-difference-icon'),
                upPercentageIcon: '<i class="bi bi-arrow-up-square-fill"></i>',
                downPercentageIcon: '<i class="bi bi-arrow-down-square-fill"></i>'
            },
            membersOverview: {
                container: document.querySelector('.member-overview-container'),
                titlesContainer: document.querySelector('.member-overview-container .titles'),
                titles: document.querySelectorAll('.member-overview-container h3')
            }
        }

        this.filters = {
            revenue: '4 weeks',
            visits: 'today',
        }
        this.visitsFilterNumber = 1;

        this.setEventListeners();
        this.setRevenueChart();
        this.getAllData()
            .then(data => {
                this.setVisits(data.numberOfVisits);
                this.setActiveMembers(data.activeMembers);
                this.setRelevantMembers(data.relevantMembers.recentMembers);
            });
    }

    setEventListeners() {
        this.elements.revenue.filtersContainer.addEventListener('click', (e) => this.revenueFilterClick(e.target));
        this.elements.visits.filtersContainer.addEventListener('click', (e) => this.visitsFilterClick(e.target))
        this.elements.membersOverview.titlesContainer.addEventListener('click', (e) => this.membersOverviewClick(e.target));
        this.elements.membersOverview.container.addEventListener('click', (e) =>
            modals.user.openModalOnClick(e.target, '.relevant-member-container', userData.get())
        );
    }

    setActiveMembers(numberOfActiveMembers) {
        this.elements.activeMembers.output.textContent = numberOfActiveMembers;
    }

    setVisits(visitsSpec) {
        let colourClass = 'text-danger';
        let icon = this.elements.visits.downPercentageIcon;
        this.elements.visits.output.textContent = visitsSpec.current;
        this.elements.visits.percentageDifferenceOutput.textContent = visitsSpec.percentageDifference + '%';
        if (visitsSpec.percentageDifference >= 0) {
            icon = this.elements.visits.upPercentageIcon;
            colourClass = 'text-success';
        }
        this.elements.visits.percentageDifferenceIcon.innerHTML = icon;
        this.elements.visits.percentageDifferenceContainer.className = "";
        this.elements.visits.percentageDifferenceContainer.classList.add(colourClass);
    }

    setMemberRow(member) {
        const memberRowTemplate = document.getElementById('dashboard-member-row');
        const memberRowContainer = document.importNode(memberRowTemplate.content, true);

        memberRowContainer.querySelector('img').src = member.img_url;
        memberRowContainer.querySelector('.name').textContent = member.name;
        memberRowContainer.querySelector('.days').textContent = member.days_difference;
        memberRowContainer.querySelector('.email').textContent = member.email;
        memberRowContainer.querySelector('.id').textContent = member.user_id;
        this.elements.membersOverview.container.appendChild(memberRowContainer);
    }

    setRelevantMembers(members) {
        members.forEach(member => this.setMemberRow(member));
    }

    revenueFilterClick(currentElement) {
        if (currentElement.tagName === 'LI') {
            setActiveElement(this.elements.revenue.filters, currentElement);
            this.filters.revenue = currentElement.dataset.filterValue;
        }
    }

    visitsFilterClick(currentElement) {
        // need to increment/decrement and wrap here
        const { leftArrow, rightArrow, filters } = this.elements.visits;

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
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    }

    getAllData() {
        const url = getPhpMethodUrl(`/Dashboards/getData/${this.filters.revenue}/${this.filters.visits}`);
        return getData(url);
    }
}
