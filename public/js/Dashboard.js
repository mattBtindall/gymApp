import { setActiveElement, getPhpMethodUrl, getData, modals, userData } from "./utils.js";
import { Chart, LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler } from "chart.js";

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

        this.chart = null;
        this.filters = {
            revenue: '4 weeks',
            visits: 'today',
        }
        this.visitsFilterNumber = 1;

        this.setEventListeners();
        this.getData('getData', [this.filters.revenue, this.filters.visits])
            .then(data => {
                this.setVisits(data.numberOfVisits);
                this.setRevenueChart(data.revenue);
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
            this.getData('getRevenueJson', this.filters.revenue)
                .then(revenue => this.setRevenueChart(revenue));
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
        this.getData('getNumberOfVisitsJson', this.filters.visits)
            .then(visitsSpecs => this.setVisits(visitsSpecs));
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

    setRevenueChart(revenuePoints) {
        console.log(revenuePoints);
        if (this.chart) this.chart.destroy();
        Chart.register(LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler);
        const ctx = document.getElementById('myChart');

        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [0, 1, 2, 3],
                datasets: [{
                    label: 'My Dataset',
                    data: revenuePoints,
                    borderWidth: 2,
                    borderColor: 'rgb(138, 138, 138)',
                    fill: true,
                    backgroundColor: 'rgb(221, 221, 221)'
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid : {
                            display:false
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    getData(type, args) {
        if (args.constructor === Array) args = args.join('/');
        const url = getPhpMethodUrl(`/Dashboards/${type}/${args}`);
        return getData(url);
    }
}
