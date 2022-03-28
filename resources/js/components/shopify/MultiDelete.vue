<template>
    <div class="Polaris-Stack__Item--fill">
        <div>
            <label class="Polaris-Choice" for="multicheck"><span
                class="Polaris-Choice__Control"><span
                class="Polaris-Checkbox"><input id="multicheck" type="checkbox"
                                                class="Polaris-Checkbox__Input"
                                                aria-invalid="false"
                                                role="checkbox" aria-checked="false"
                                                value="0" :checked="isMultiCheck"
                                                @click="checkAll"><span
                class="Polaris-Checkbox__Backdrop"></span><span
                class="Polaris-Checkbox__Icon"><span
                class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg"
                                          focusable="false"
                                          aria-hidden="true">
                                                  <path
                                                      d="M8.315 13.859l-3.182-3.417a.506.506 0 0 1 0-.684l.643-.683a.437.437 0 0 1 .642 0l2.22 2.393 4.942-5.327a.437.437 0 0 1 .643 0l.643.684a.504.504 0 0 1 0 .683l-5.91 6.35a.437.437 0 0 1-.642 0"></path>
                                                </svg></span></span></span></span><span
                class="Polaris-Choice__Label"></span>
            </label>

            <span v-if="isSingleCheck || checkPageOnly">{{checkedIDs.length }} {{ resourceType, checkedIDs | format_string }} Selected </span>

                <span v-if="checkAllItems">{{list.length }} + {{ resourceType, checkedIDs | format_string }} Selected </span>

            <div style="position: relative;display: inline-block;">
                <div v-if="isSingleCheck || isMultiCheck" @click="showOption = !showOption">
                    <button type="button" class="Polaris-Button" tabindex="0" aria-controls="Polarispopover2" aria-owns="Polarispopover2" aria-expanded="true"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">More actions</span><span class="Polaris-Button__Icon">
                        <div class="Polaris-Button__DisclosureIcon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                              <path d="M5 8l5 5 5-5z" fill-rule="evenodd"></path>
                            </svg></span></div>
                      </span></span></button>
                </div>


                <div class="Polaris-PositionedOverlay Polaris-Popover__PopoverOverlay Polaris-Popover__PopoverOverlay--open" style="top: 36px; left: 0;right: 0;" v-if="showOption">
                    <div class="Polaris-Popover" data-polaris-overlay="true" style="margin: 0;">
                        <div class="Polaris-Popover__FocusTracker" tabindex="0"></div>
                        <div class="Polaris-Popover__Wrapper">
                            <div id="Polarispopover2" tabindex="-1" class="Polaris-Popover__Content">
                                <div class="Polaris-Popover__Pane Polaris-Scrollable Polaris-Scrollable--vertical" data-polaris-scrollable="true">
                                    <div class="Polaris-ActionList" style="padding: 0;">
                                        <div class="Polaris-ActionList__Section--withoutTitle">
                                            <ul class="Polaris-ActionList__Actions">
                                                <li><button type="button" class="Polaris-ActionList__Item nh" @click="deleteMetafield()">
                                                    <div class="Polaris-ActionList__Content">Delete</div>
                                                </button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Polaris-Popover__FocusTracker" tabindex="0"></div>
                    </div>
                </div>
            </div>
            <span v-if="checkPageOnly">All {{resourceType, checkedIDs | format_string}} on this page are selected.</span>
            <span class="all-select" @click="ChangeSelection('check')" target="_self"
                  v-if="next_page && checkPageOnly">Select all {{list.length }}+ {{resourceType, checkedIDs | format_string}}</span>


            <span v-if="checkAllItems">All {{resourceType}} are selected.</span>
            <span class="all-select" @click="ChangeSelection('clear')" target="_self"
                  v-if="next_page && checkAllItems">Clear Selection</span>
        </div>

    </div>
</template>

<script>
    import {ContextualSaveBar, Modal, Toast} from '@shopify/app-bridge/actions';
    import {Button} from '@shopify/app-bridge/actions';
    import Model from "./Model";
    import helper from "../../helper";

    export default {
        name: 'MultiDelete',
        props: ['resourceType', 'list', 'next_page', 'checkedIDs'],
        components: {
            Model,
        },
        data() {
            return {
                isSingleCheck: '',
                isMultiCheck: '',
                checkAllItems: '',
                checkPageOnly: '',
                is_disable: '',
                checkedIds: this.checkedIDs,
                showOption: false,
            }
        },
        methods: {
            init() {
                this.isSingleCheck = false;
                this.isMultiCheck = false;
                this.checkAllItems = false;
                this.checkPageOnly = false;
                this.is_disable = false;
                this.showOption = false;
            },
            singleChecked(checkIDs) {
                    if (checkIDs.length > 0) {
                        this.isSingleCheck = true;
                        this.checkPageOnly = false;
                        this.isMultiCheck = false;
                        this.checkAllItems = false;
                    } else {
                        this.isSingleCheck = false;
                    }
                    this.checkedIds = checkIDs;
            },
            checkAll() {
                this.isSingleCheck = false;
                this.checkPageOnly = !this.checkPageOnly;
                this.isMultiCheck = !this.isMultiCheck;
                if( !this.isMultiCheck ){
                    this.checkPageOnly = false;
                    this.checkAllItems = false;
                }
                this.$emit('update', this.isMultiCheck);
            },
            ChangeSelection(action) {
                if (action == 'check') {
                    const okButton = Button.create(shopify_app_bridge, {label: 'Confirm'});
                    const cancelButton = Button.create(shopify_app_bridge, {label: 'Cancel'});
                    const modalOptions = {
                        title: 'Select all ' + this.resourceType,
                        message: 'Are you sure you want to select all ' + this.resourceType + ' from your store?',
                        footer: {
                            buttons: {
                                primary: okButton,
                                secondary: [cancelButton],
                            },
                        },
                    };
                    const myModal = Modal.create(shopify_app_bridge, modalOptions);
                    myModal.dispatch(Modal.Action.OPEN);
                    cancelButton.subscribe(Button.Action.CLICK, data => {
                        this.checkAllItems = false;
                        this.checkPageOnly = true;
                        this.showOption = false;
                        myModal.dispatch(Modal.Action.CLOSE);
                    });
                    okButton.subscribe(Button.Action.CLICK, data => {
                        this.checkAllItems = true;
                        this.checkPageOnly = false;
                        this.showOption = false;
                        myModal.dispatch(Modal.Action.CLOSE);
                    });
                } else if (action == 'clear') {
                    this.checkPageOnly = !this.checkPageOnly;
                    this.checkAllItems = !this.checkAllItems;
                    this.showOption = false;
                }
            },
            deleteMetafield(){
                const okButton = Button.create(shopify_app_bridge, {label: 'Delete'});
                const cancelButton = Button.create(shopify_app_bridge, {label: 'Cancel'});
                const modalOptions = {
                    title: 'Delete Metafields!!',
                    message: 'Are you sure to delete Metafields???',
                    footer: {
                        buttons: {
                            primary: okButton,
                            secondary: [cancelButton],
                        },
                    },
                };
                const myModal = Modal.create(shopify_app_bridge, modalOptions);
                myModal.dispatch(Modal.Action.OPEN);
                cancelButton.subscribe(Button.Action.CLICK, data => {
                    myModal.dispatch(Modal.Action.CLOSE);
                    this.init();
                    this.$emit('update', false);
                });
                okButton.subscribe(Button.Action.CLICK, data => {
                    myModal.dispatch(Modal.Action.CLOSE);
                    this.showOption = false;
                    this.sendForm();
                });
            },
            async sendForm() {
                let base = this;
                let pageIDs = [];
                if (base.checkPageOnly) {
                    base.list.forEach(function (element, index) {
                        pageIDs.push(element.id);
                    });
                }
                await axios({
                    url: '/remove-metafield',
                    data: {
                        'isSingleCheck': base.isSingleCheck,
                        'checkPageOnly': base.checkPageOnly,
                        'data': (base.checkPageOnly) ? pageIDs : base.checkedIDs,
                        'checkAllItems': base.checkAllItems,
                        'isMultiCheck': base.isMultiCheck,
                        'resourceType': base.resourceType,
                    },
                    method: 'post',
                }).then(function (res) {
                    let msg = res.data.data;
                    const toastNotice = Toast.create(shopify_app_bridge, {message: msg});
                    toastNotice.dispatch(Toast.Action.SHOW);
                    base.init();
                    base.$emit('update', false);
                })
                    .catch(function (err) {
                        console.log(err);
                    })
                    .finally(function () {
                        base.is_disable = false;
                        base.showOption = false;
                    });
            }
        },
        created() {
            this.init();
            this.singleChecked(this.checkedIDs);
        },
        watch: {
            isSingleCheck: {
                immediate: true,
                deep: true,
                handler: function () {
                    var input = document.getElementById("multi-del");

                    if (this.isSingleCheck || this.isMultiCheck) {
                        input.colSpan = "4";
                    } else {
                        if (input) {
                            input.colSpan = "0";
                        }
                    }
                }
            },
            isMultiCheck: {
                immediate: true,
                deep: true,
                handler: function () {
                    var input = document.getElementById("multi-del");
                    if (this.isSingleCheck || this.isMultiCheck) {
                        input.colSpan = "4";
                    } else {
                        if (input) {
                            input.colSpan = "0";
                        }
                    }
                }
            }
        },
        filters:{
            format_string: function(str, ids){
                let new_str = ( ids.length == 1 ) ? str.substring(0, str.length-1) : str;
                return new_str.replace('_', ' ');
            },
        }
    }
</script>

