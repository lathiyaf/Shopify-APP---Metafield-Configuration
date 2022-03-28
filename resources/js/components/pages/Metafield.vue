<template>
    <div class="">
        <back-arrow :name=rtype :label=label :resourceType=rtype></back-arrow>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header">
                    <div class="Polaris-Page__Title Polaris-TextContainer--spacingTight">
                        <h1 class="Polaris-Heading text-capitalize">{{ resourceType | format_string}} Metafield Configuration</h1>
                        <p>This is your global metafield configuration for each object type.</p>
                    </div>
                </div>
                <div class="Polaris-Card__Section">
                    <div class="custom-box">
                        <content-loader :width="300" :height="100" primaryColor="#e0e4e8" class="Polaris-Page__Content" v-if="is_contentload">
                            <!--            first section-->
                            <rect x="10" y="10" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="10" rx="3" ry="3" width="50" height="10"/>

                            <!--            second section-->
                            <rect x="10" y="25" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="25" rx="3" ry="3" width="50" height="10"/>

                            <!--            third section-->
                            <rect x="10" y="40" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="40" rx="3" ry="3" width="50" height="10"/>

                            <!--            fourth section-->
                            <rect x="10" y="55" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="55" rx="3" ry="3" width="50" height="10"/>

                            <!--            fifth section-->
                            <rect x="10" y="70" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="70" rx="3" ry="3" width="50" height="10"/>

                            <rect x="10" y="85" rx="3" ry="3" width="40" height="10"/>

                        </content-loader>
                        <div class="Polaris-FormLayout" id="sortable" v-if="!is_contentload">
                            <draggable
                                :list="form"
                                :disabled="!enabled"
                                class="form-group custom-dragn"
                                ghost-class="ghost"
                                :move=checkMove
                                @start="dragging = true"
                                @end="dragging = false"
                            >
                                <div class="Polaris-FormLayout__Item d-xs-block d-flex" v-for="(f, index) in form" :key="index">
                                    <div class="icon-dragn"
                                         style="display: flex;align-items: center;min-height: 36px;margin-right: 8px">
                                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                    </div>
                                    <div class="custom-row w-100 metafield-item">

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label :id="`namespaceLabel`+index"
                                                                                  :for="`Namespace`+index"
                                                                                  class="Polaris-Label__Text">Namespace
                                                    *</label>

                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input :id="`Namespace`+index"
                                                                                  :name="`namespace${{index}}`"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity" step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false" aria-multiline="false"
                                                                                  value="" v-model="f.namespace"
                                                                                  :disabled="f.id.length > 0">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <div class="Polaris-Labelled__Error" v-if="errors[`data.${index}.namespace`]"><div id="PolarisTextField4Error" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors[`data.${index}.namespace`][0]}}</div></div>
        <!--                                    <span v-if="errors[`data.${index}.namespace`]" class="config-error">{{errors[`data.${index}.namespace`][0]}}</span>-->
                                        </div>

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label :id="`KeyLabel`+index" :for="`Key`+index"
                                                                                  class="Polaris-Label__Text">key *</label>
                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input :id="`Key`+index"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity" step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false" aria-multiline="false"
                                                                                  value="" v-model="f.key"
                                                                                  :disabled="f.id.length > 0">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <div class="Polaris-Labelled__Error" v-if="errors[`data.${index}.key`]"><div id="PolarisTextField4Errorkey" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors[`data.${index}.key`][0]}}</div></div>
        <!--                                    <span v-if="errors[`data.${index}.key`]" class="config-error">{{errors[`data.${index}.key`][0]}}</span>-->
                                        </div>

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label :id="`PolarisSelectLabel`+index"
                                                                                  :for="`PolarisSelect`+index"
                                                                                  class="Polaris-Label__Text">Type *</label>
                                                </div>
                                            </div>
                                            <div class="Polaris-Select" v-model="f.typev" @change="selectType(index,$event)">
                                                <select :id="`PolarisSelect`+index" class="Polaris-Select__Input"
                                                        aria-invalid="false">
                                                    <option v-for="type in types" :value="type.value"
                                                            :selected="(type.value == f.typev) ? 'selected' : '' ">{{type.name}}
                                                    </option>
                                                </select>
                                                <div class="Polaris-Select__Content" aria-hidden="true"><span
                                                    class="Polaris-Select__SelectedOption">{{f.typen}}</span><span
                                                    class="Polaris-Select__Icon"><span class="Polaris-Icon"><svg
                                                    viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false"
                                                    aria-hidden="true">
                                                            <path d="M13 8l-3-3-3 3h6zm-.1 4L10 14.9 7.1 12h5.8z"
                                                                  fill-rule="evenodd"></path>
                                                            </svg></span></span></div>
                                                <div class="Polaris-Select__Backdrop"></div>
                                            </div>
                                        </div>

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label :id="`configureLabel`+index"
                                                                                  :for="`configure`+index"
                                                                                  class="Polaris-Label__Text">Label </label>
                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input :id="`configure`+index"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity" step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false" aria-multiline="false"
                                                                                  value="" v-model="f.label">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <span v-if="errors[`data.${index}.label`]" class="config-error">{{errors[`data.${index}.label`][0]}}</span>
                                        </div>
                                    </div>
                                    <div class="ml-16 ml-xl-0 mobile-remove-btn">
                                        <div class="Polaris-Label"><label class="Polaris-Label__Text">&nbsp;</label></div>
                                        <button type="button" v-if="form.length > 1"
                                                class="Polaris-Button Polaris-Button--destructive button-width"
                                                @click="removeField(index,f.id)"><span class="Polaris-Button__Content"><span
                                            class="Polaris-Button__Text">Remove</span></span></button>
                                    </div>
                                </div>
                            </draggable>
                        </div>
                        <div class="mt-16" v-if="!is_contentload">
                            <button type="button" class="Polaris-Button Polaris-Button--primary" @click="addMoreField()"><span
                                class="Polaris-Button__Content"><span class="Polaris-Button__Text">Add More</span></span>
                            </button>
                    </div>
            </div>
                </div>
            </div>
        </div>
        <page-footer></page-footer>
    </div>
</template>

<script>
    import BackArrow from '../shopify/BackArrow';
    import {Toast} from '@shopify/app-bridge/actions';
    import {ContextualSaveBar} from '@shopify/app-bridge/actions';
    import {Redirect} from '@shopify/app-bridge/actions';
    import helper from '../../helper';
    import draggable from 'vuedraggable';
    import PageFooter from "../shopify/PageFooter";
    import {ContentLoader} from 'vue-content-loader';

    export default {
        components: {
            draggable,
            BackArrow,
            PageFooter,
            ContentLoader
        },
        data() {
            return {
                app: shopify_app_bridge,
                form: [],
                types: [
                    {name: 'String', value: 'string'},
                    {name: 'Number', value: 'number'},
                    {name: 'Email', value: 'email'},
                    {name: 'Rich Text Box', value: 'rich_text_box'},
                    {name: 'File', value: 'file'},
                    {name: 'Image', value: 'image'},
                    {name: 'Multiple Image', value: 'multiple_image'},
                    {name: 'URL', value: 'url'},
                    {name: 'Phone', value: 'phone'},
                    {name: 'Color Picker', value: 'color_picker'},
                    {name: 'Date Picker', value: 'date_picker'},
                    {name: 'Json', value: 'json'},
                ],
                errors: [],
                resourceType: '',
                saveClick: 0,
                removeFieldsIds: [],
                dragging: false,
                enabled: true,
                temp_prisine: '',
                label: '',
                rtype: '',
                namespace: '',
                watch: false,
                is_contentload: true,
                saveBar: false,
            }
        },
        methods: {
            checkMove: function (e) {
                this.createContextualSaveBar();
            },
            selectType(index, e) {
                this.form[index].typen = this.types[e.target.selectedIndex].name;
                this.form[index].typev = this.types[e.target.selectedIndex].value;
            },
            addMoreField() {
                let f = {
                    id: '',
                    namespace: this.namespace,
                    key: '',
                    typen: 'String',
                    typev: 'string',
                    label: '',
                }
                this.form.push(f);
            },
            removeField(index, id) {
                delete this.errors[`data${index}namespace`];
                delete this.errors[`data${index}key`];
                delete this.errors[`data${index}label`];
                this.form.splice(index, 1);
                (id) ? this.removeFieldsIds.push(id) : '';
                if (this.saveClick == 1) {
                    this.errors = [];
                    this.sendForm(false);
                }
            },
            createContextualSaveBar() {
                let base = this;
                let options = {
                    saveAction: {
                        disabled: false,
                        loading: false,
                    },
                    discardAction: {
                        disabled: false,
                        loading: false,
                        discardConfirmationModal: true,
                    },
                };
                var contextualSaveBar = helper.contextualSaveBar(options);

                contextualSaveBar.dispatch(ContextualSaveBar.Action.SHOW);

                contextualSaveBar.subscribe(ContextualSaveBar.Action.DISCARD, function () {
                    base.errors = [];
                    base.getMetafields();
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                });
                contextualSaveBar.subscribe(ContextualSaveBar.Action.SAVE, function () {
                    contextualSaveBar.set({saveAction: {loading: true}, discardAction: {disabled: true}});
                    base.sendForm(true);
                });
            },
            // checkSaveBar(){
            //     const redirect = Redirect.create(shopify_app_bridge);
            //     console.log('/'+ this.rtype + '/:' + this.resourceType);
            //     redirect.dispatch(Redirect.Action.APP, '/products/resourceType=' + this.resourceType);
            //
            // },
            async sendForm(isSubmit) {
                let base = this;
                let url = 'metafieldconfiguration';
                let method = 'post';

                base.saveClick = 1;
                await axios({
                    url: url,
                    data: {
                        'isSubmit': isSubmit,
                        'removeField': base.removeFieldsIds,
                        'data': base.form,
                        'resourceType': base.resourceType,
                    },
                    method: method,
                }).then(function (res) {
                    if (res.data.data !== 1) {
                        var contextualSaveBar = helper.contextualSaveBar();
                        contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                        let msg = res.data.data;
                        const toastNotice = Toast.create(base.app, {message: msg});
                        toastNotice.dispatch(Toast.Action.SHOW);

                        base.errors = [];
                        base.removeFieldsIds = [];
                        base.saveClick = 0;
                        base.getMetafields();
                    }
                })
                    .catch(function (err) {
                        base.createContextualSaveBar();
                        base.errors = err.response.data;
                        console.log(base.errors);
                    });
            },
            async getMetafields() {
                let base = this;
                let url = 'metafieldconfiguration?api=1&resourceType=' + base.resourceType;
                let method = 'get';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    let data = res.data.data.entity;
                    base.namespace = res.data.data.namespace;
                    base.form = [];
                    if (data.length) {
                        data.forEach(function (element) {
                            let t = '';
                            let result = _.find(base.types, function (o) {
                                return o.value == element.type;
                            });
                            t = result.name;
                            let f = {
                                id: element.id,
                                namespace: element.namespace,
                                key: element.key,
                                typen: t,
                                typev: element.type,
                                label: element.label,
                            }

                            base.form.push(f);
                        });
                    } else {
                        base.addMoreField();
                    }
                    base.watch = true;
                    base.temp_prisine = JSON.stringify(base.form);
                })
                    .catch(function (err) {
                        console.log(err);
                    }).finally(response =>{
                        base.is_contentload = false;
                    });
            }
        },
        created() {
            this.resourceType = this.$route.params.resourceType;
            this.shop = this.$route.params.shop;
            this.getMetafields();

            if (this.resourceType == 'articles') {
                this.label = 'Blogs';
                this.rtype = 'blogs';
            } else if (this.resourceType == 'variants') {
                this.label = 'Products';
                this.rtype = 'products';
            } else if (this.resourceType == 'smart_collections' || this.resourceType == 'custom_collections') {
                this.label = 'Collections';
                this.rtype = 'smart_collections';
            } else if (this.resourceType == 'shop') {
                this.label = 'Dashboard';
                this.rtype = 'dashboard';
            } else {
                this.label = this.resourceType.split('_').map(_.capitalize).join('');
                this.rtype = this.resourceType;
            }
        },
        watch: {
            form: {
                immediate: true,
                deep: true,
                handler: function () {
                    if( this.watch ){
                        if (this.temp_prisine === JSON.stringify(this.form)) {
                            let contextualSaveBar = helper.contextualSaveBar();
                            contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                        } else {
                            this.createContextualSaveBar();
                        }
                    }
                }
            }
        },
        filters: {
            format_string: function (str) {
                return str.replace('_', ' ');
            },
        }
    };
</script>

<style>
    .config-error {
        color: #f90000 !important;
    }
</style>
