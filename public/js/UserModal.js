import { Modal } from './Modal.js';
import { modals, userData, getPhpMethodUrl, getData, sendAjax, isAdmin, capitalise } from './utils.js';

export class UserModal extends Modal {
    constructor() {
        super({
            logBtn: '.log-btn',
            name: '.name',
            email: '.email',
            phone_number: '.phone_number',
            dob: '.dob',
            id: '.id'
        }, '.user-modal');

        this.init();
    }

    init() {
        this.elements.tabs = {
            menu: {
                items: document.querySelectorAll('.user-modal__menu-item'),
                activity: document.querySelectorAll('.user-modal__menu-item')[0],
                membership: document.querySelectorAll('.user-modal__menu-item')[1],
                addMembership: document.querySelectorAll('.user-modal__menu-item')[2]
            },
            content: {
                items: document.querySelectorAll('.user-modal__item'),
                activity: document.querySelector('.user-modal__item.activity .content'),
                memberships: document.querySelector('.user-modal__item.membership')
            }
        }

        isAdmin()
            .then(admin => {
                if (admin.isAdmin) {
                    this.setAddMembershipTab();
                }
            });
        this.setEventListeners();
    }

    setEventListeners() {
        const membershipTable = document.querySelector('.membership-table');
        const userModalMenuBar = document.querySelector('.user-modal__menu-bar');

        if (membershipTable)
            membershipTable.addEventListener('click', (e) => this.openModalOnClick(e.target, '.account-link', userData.get()));

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());

        if (userModalMenuBar) {
            // tab click
            userModalMenuBar.addEventListener('click', (e) => {
                if (e.target.classList.contains('user-modal__menu-item')) {
                    this.setTabs(e.target);
                }
            });

            this.elements.logBtn.addEventListener('click', (e) => {
                this.logMember(e.target.dataset.userId)
                    .then(activity => {
                        if (activity !== '{}') {
                            this.outputActivity(activity);
                            if (this.activity) this.activity.addActivityElement(activity);
                        };
                    });
            })

            // open and close memberships in user modal
            this.elements.tabs.content.memberships.addEventListener('click', (e) => {
                const parent = e.target.closest('.user-modal__membership');
                if (!parent) {
                    return;
                }

                parent.classList.toggle('active');
                parent.querySelector('.icon').classList.toggle('bi-caret-left-fill');
                parent.querySelector('.icon').classList.toggle('bi-caret-down-fill');
            });
        }
    }

    setTabs(menuItem) {
        this.elements.tabs.menu.items.forEach(menuItem => menuItem.classList.remove('active'));
        menuItem.classList.add('active');
        this.elements.tabs.content.items.forEach(item => item.classList.remove('active'));
        document.querySelector(`.${menuItem.dataset.contentClassName}`).classList.add('active');
    }

    setAddMembershipTab() {
        this.elements.addMembershipTab = {
            termDropDown: document.querySelector('.user-modal__content .term'),
            startDate: document.querySelector('.user-modal__content .start-date'),
            cost: document.querySelector('.user-modal__content .cost'),
            expiryDate: document.querySelector('.user-modal__content .expiry-date')
        };

        this.getTerms()
            .then(terms => this.setTerms(terms))
            .then(() => this.getModalStatus())
            .then(modalStatus => this.openModalOnLoad(modalStatus.open, modalStatus.user_id, modalStatus.selected))
            .catch(e => console.log(e));

        const addMembershipDropDownClick = (e) => {
            const dropDownOption = e.target.querySelector(`option[value="${e.target.value}"]`);
            const expiryDateParent = this.elements.addMembershipTab.expiryDate.closest('.expiry-date-container');

            // Show & hide expiry date input for custom membership
            if (e.target.value === 'custom') {
                expiryDateParent.classList.add('active');
                this.elements.addMembershipTab.cost.value = '';
                this.elements.addMembershipTab.cost.readOnly = false;
            } else {
                expiryDateParent.classList.remove('active');
                this.elements.addMembershipTab.cost.value = dropDownOption.dataset.cost;
                this.elements.addMembershipTab.cost.readOnly= true;
            }
        }

        this.elements.addMembershipTab.termDropDown.addEventListener('change', addMembershipDropDownClick);
    }

    setMembershipTab(userId) {
        const setMembership = (membership) => {
            const membershipTemplate = document.getElementById('user-modal-membership');
            const membershipElement = document.importNode(membershipTemplate.content, true);
            const container = membershipElement.querySelector('.user-modal__membership');
            const classes = [];
            switch (membership.status) {
                case 'expired' :
                    classes.push('bg-danger', 'border-danger');
                    break;
                case 'future' :
                    classes.push('bg-info', 'border-info');
                    break;
                case 'active' :
                    classes.push('bg-success', 'border-success', 'active');
                    break;
                case 'revoked' :
                    classes.push('bg-warning', 'border-danger');
                    break;
                default :
                    classes.push('bg-light', 'border-light');
            }
            container.classList.add(...classes);

            const revokeBtn = membershipElement.querySelector('.revoke');
            if (membership.status === 'revoked') {
                revokeBtn.style.display = 'none';
            }
            this.setHref(revokeBtn, membership.id);

            membershipElement.querySelector('.display-name-output').textContent = membership.display_name;
            membershipElement.querySelector('.membership-status').textContent = capitalise(membership.status);
            membershipElement.querySelector('.start-date-output').textContent = membership.start_date;
            membershipElement.querySelector('.expiry-date-output').textContent = membership.expiry_date;
            membershipElement.querySelector('.created-at-output').textContent = membership.created_at;
            membershipElement.querySelector('.cost-output').textContent = membership.cost;
            this.elements.tabs.content.memberships.appendChild(membershipElement);
        }

        this.getMembershipData(userId)
            .then(memberships => {
                if (memberships === '{}') return;

                for (const key in memberships) {
                    setMembership(memberships[key]);
                }
            });
    }

    outputActivity(activity) {
        const activityTemplate = document.getElementById('user-modal-activity');
        const activityElement = document.importNode(activityTemplate.content, true);
        const container = activityElement.querySelector('.row-container')

        if (activity.status.includes('granted')) {
            container.classList.add('text-success');
        } else {
            container.classList.add('text-danger');
        }

        activityElement.querySelector('.date-output').textContent = activity.date;
        activityElement.querySelector('.time-output').textContent = activity.time;
        activityElement.querySelector('.status-output').innerHTML = activity.status;
        this.elements.tabs.content.activity.prepend(activityElement);
    }

    setActivityTab(user) {
        if (!user.activity) return;
        user.activity.forEach(activity => this.outputActivity(activity));
    }

    setUserDetails(user) {
        this.elements.logBtn.dataset.userId = user.id;
        if (!user.status || user.status !== 'active') {
            this.elements.logBtn.disabled = true;
        }
        this.elements.name.textContent = user.name;
        this.elements.email.textContent = user.email;
        this.elements.phone_number.textContent = user.phone_number;
        this.elements.dob.textContent = user.dob;
        this.elements.id.value = user.id;
    }

    setModalAdmin(user) {
        isAdmin()
            .then(admin => {
                if (admin.isAdmin) {
                    this.setActivityTab(user);
                    this.setMembershipTab(user.id);
                }
            });
    }

    setTerm(term) {
        const optionElement = document.createElement('option');
        optionElement.innerText = term['display_name'];
        optionElement.value = term.id;
        optionElement.dataset.cost = term.cost;
        this.elements.addMembershipTab.termDropDown.appendChild(optionElement);
    }

    setTerms(terms) {
        return new Promise((res, rej) => {
            if (terms.length === 0) {
                rej('No terms');
            } else {
                for (const key in terms) {
                    this.setTerm(terms[key]);
                }
                res();
            }
        })
    }

    getMembershipData(userId) {
        const url = getPhpMethodUrl(`/members/getTermMembershipByUserId/${userId}`);
        return getData(url);
    }

    getModalStatus() {
        // sees whether to open the modal or not from php
        const url = getPhpMethodUrl('/userModal/getStatus');
        return getData(url);
    }

    getTerms() {
        const url = getPhpMethodUrl('/terms/getTerms');
        return getData(url);
    }

    openModalOnLoad(openModal, currentUserId, selected) {
        if (!openModal) return;
        Array.from(this.elements.addMembershipTab.termDropDown.children).forEach(option => {
            if (option.value == selected) {
                option.selected = true;
            }
        });

        if (selected === 'custom') {
            this.elements.addMembershipTab.cost.readOnly = false;
        }
        this.setTabs(this.elements.tabs.menu.addMembership);
        const user = this.getUserById(currentUserId, userData.get());
        this.openModal(user);
    }

    openModalOnClick(element, parentSelector, data, tab = null) {
        // tab should be the name of the tab element e.g. addMembership
        modals.search.closeModal();
        const user = this.getCurrentUser(element, parentSelector, data);
        this.openModal(user);
        if (tab) {
            this.setTabs(this.elements.tabs.menu[tab]);
        }
    }

    openModal(user) {
        modals.search.closeModal();
        this.setModalAdmin(user)
        this.setUserDetails(user);

        // adds delay in so users can't see the modal before data is loaded
        setTimeout(() => {
            super.openModal(user);
        }, 100);
    }

    closeModal() {
        if (!this.open) {
            return;
        }

        const url = getPhpMethodUrl('/userModal/disable');
        sendAjax(url); // remove modal errors so it doesn't reopen
        this.elements.logBtn.disabled = false;
        this.resetAddMembershipTab();
        this.resetMembershipTab();
        this.resetActivityTab();
        this.setTabs(this.elements.tabs.menu.activity);
        modals.search.closeModal();
        super.closeModal();
    }

    resetAddMembershipTab() {
        const { addMembershipTab: inputs } = this.elements;
        inputs.expiryDate.parentNode.classList.remove('active');
        inputs.cost.readOnly = true;
        for (const key in inputs) {
            inputs[key].classList.remove('is-invalid');
            inputs[key].value = inputs[key].dataset.initialValue;
        }
    }

    resetMembershipTab() {
        this.elements.tabs.content.memberships.innerText = "";
    }

    resetActivityTab() {
        this.elements.tabs.content.activity.innerText = "";
    }
}
