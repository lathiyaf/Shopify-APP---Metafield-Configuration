<template>
    <div class="">
        <back-arrow :name=rtype :label=label :resourceType=rtype></back-arrow>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header">
                    <div class="Polaris-Page__Title Polaris-TextContainer--spacingTight">
                        <h1 class="Polaris-Heading">Metafields Configuration</h1>
                        <p>This is your global metafield configuration for each object type.</p>
                    </div>
                </div>
                <div class="Polaris-Card__Section">
                    <div class="Polaris-Page__Content">
                        <content-loader :width="300" :height="200" primaryColor="#e0e4e8" class="Polaris-Page__Content" v-if="is_contentload">
                            <!--            first section-->
                            <rect x="10" y="10" rx="3" ry="3" width="50" height="10"/>
                            <rect x="10" y="25" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="25" rx="3" ry="3" width="50" height="10"/>
                            <rect x="10" y="40" rx="3" ry="3" width="50" height="10"/>
                            <rect x="0" y="55" rx="3" ry="3" width="250" height="1"/>

                            <!--            second section-->
                            <rect x="10" y="70" rx="3" ry="3" width="50" height="10"/>
                            <rect x="10" y="85" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="85" rx="3" ry="3" width="50" height="10"/>
                            <rect x="10" y="100" rx="3" ry="3" width="50" height="10"/>
                            <rect x="0" y="115" rx="3" ry="3" width="250" height="1"/>

                            <!--            third section-->
                            <rect x="10" y="130" rx="3" ry="3" width="50" height="10"/>
                            <rect x="10" y="145" rx="3" ry="3" width="180" height="10"/>
                            <rect x="195" y="145" rx="3" ry="3" width="50" height="10"/>
                            <rect x="10" y="160" rx="3" ry="3" width="50" height="10"/>
                            <rect x="0" y="175" rx="3" ry="3" width="250" height="1"/>

                        </content-loader>
                        <div v-for="(f, findex) in form" :key="findex">
                    <div class="custom-box mb-25">
                        <h4 class="mb-25 Polaris-Heading text-capitalize">{{f.rtype | format_resourceType}}</h4>
                        <div class="Polaris-FormLayout" id="sortable">
                            <draggable
                                :list="f.value"
                                :disabled="!enabled"
                                class="form-group"
                                ghost-class="ghost"
                                @change="renderForm"
                                @start="dragging = true"
                                @end="dragging = false"
                            >
                                <div class="Polaris-FormLayout__Item d-flex"
                                     v-for="(v, vindex) in f.value" :key="vindex">
                                    <div style="display: flex;align-items: center;min-height: 36px;margin-right: 8px">
                                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                    </div>
                                    <div class="custom-row w-100">

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label :id="`namespaceLabel`+findex+vindex"
                                                                                  :for="`Namespace`+findex+vindex"
                                                                                  class="Polaris-Label__Text">Namespace
                                                    *</label>

                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input :id="`Namespace`+findex+vindex"
                                                                                  :name="`namespace`+findex+vindex"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity" step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false"
                                                                                  aria-multiline="false"
                                                                                  value="" v-model="v.namespace"
                                                                                  :disabled="v.id.length > 0"
                                                                                  v-on:input="renderForm()">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
    <!--                                        {{errors[`data.${vindex}.${findex}.namespace`][0]}}"  [`data.${findex}.${vindex}.namespace`]-->
                                            <span v-if="errors[`data.${findex}.${vindex}.namespace`]" class="config-error">{{errors[`data.${findex}.${vindex}.namespace`][0]}}</span>
                                        </div>

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label :id="`KeyLabel`+findex+vindex"
                                                                                  :for="`Key`+findex+vindex"
                                                                                  class="Polaris-Label__Text">key *</label>
                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input :id="`Key`+findex+vindex"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity" step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false"
                                                                                  aria-multiline="false"
                                                                                  value="" v-model="v.key"
                                                                                  :disabled="v.id.length > 0"
                                                                                  v-on:input="renderForm()">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <span v-if="errors[`data.${findex}.${vindex}.key`]" class="config-error">{{errors[`data.${findex}.${vindex}.key`][0]}}</span>
                                        </div>

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label :id="`PolarisSelectLabel`+findex+vindex"
                                                                                  :for="`PolarisSelect`+findex+vindex"
                                                                                  class="Polaris-Label__Text">Type *</label>
                                                </div>
                                            </div>
                                            <div class="Polaris-Select" v-model="v.typev"
                                                 @change="selectType(findex, vindex, $event)">
                                                <select :id="`PolarisSelect`+findex+vindex" class="Polaris-Select__Input"
                                                        aria-invalid="false">
                                                    <option v-for="type in types" :value="type.value"
                                                            :selected="(type.value == v.typev) ? 'selected' : '' ">
                                                        {{type.name}}
                                                    </option>
                                                </select>
                                                <div class="Polaris-Select__Content" aria-hidden="true"><span
                                                    class="Polaris-Select__SelectedOption">{{v.typen}}</span><span
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
                                                <div class="Polaris-Label"><label :id="`configureLabel`+findex+vindex"
                                                                                  :for="`configure`+findex+vindex"
                                                                                  class="Polaris-Label__Text">Label
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input :id="`configure`+findex+vindex"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity" step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false"
                                                                                  aria-multiline="false"
                                                                                  value="" v-model="v.label"
                                                                                  v-on:input="renderForm()">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <span v-if="errors[`data.${findex}.${vindex}.label`]" class="config-error">{{errors[`data.${findex}.${vindex}.label`][0]}}</span>
                                        </div>
                                    </div>

                                    <div class="ml-16">
                                        <div class="Polaris-Label"><label class="Polaris-Label__Text">&nbsp;</label></div>
                                        <button type="button" class="Polaris-Button Polaris-Button--destructive button-width"
                                                @click="removeField(findex, vindex, v.id)"><span
                                            class="Polaris-Button__Content"><span
                                            class="Polaris-Button__Text">Remove</span></span></button>
                                    </div>
                                </div>
                            </draggable>
                        </div>
                        <div class="mt-16">
                            <button type="button" class="Polaris-Button Polaris-Button--primary" @click="addMoreField(findex)"><span
                                class="Polaris-Button__Content"><span class="Polaris-Button__Text">Add Metafield</span></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
                </div>
            </div>
        </div>
        <page-footer></page-footer>
    </div>
</template>

<script>
    import BackArrow from '../../shopify/BackArrow';
    import {Toast} from '@shopify/app-bridge/actions';
    import {ContextualSaveBar} from '@shopify/app-bridge/actions';
    import {ContentLoader} from 'vue-content-loader';
    import helper from '../../../helper';
    import draggable from 'vuedraggable';
    import PageFooter from "../../shopify/PageFooter";

    export default {
        components: {
            draggable,
            BackArrow,
            ContentLoader,
            PageFooter
        },
        data() {
            return {
                app: shopify_app_bridge,
                form: [],
                resourceTypes: [
                    {name: 'Products', value: 'products'},
                    {name: 'Variants', value: 'variants'},
                    {name: 'Shop', value: 'shop'},
                    {name: 'Smart Collections', value: 'smart_collections'},
                    {name: 'Custom Collections', value: 'custom_collections'},
                    {name: 'Customers', value: 'customers'},
                    {name: 'Blogs', value: 'blogs'},
                    {name: 'Articles', value: 'articles'},
                    {name: 'Orders', value: 'orders'},
                    {name: 'PAges', value: 'pages'},
                ],
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
                saveClick: 0,
                removeFieldsIds: [],
                dragging: false,
                enabled: true,
                temp_prisine: '',
                label: 'Dashboard',
                rtype: 'dashboard',
                namespace: '',
                watch: false,
                is_contentload: true,
            }
        },
        methods: {
            selectType(findex, vindex, e) {
                this.form[findex]['value'][vindex].typen = this.types[e.target.selectedIndex].name;
                this.form[findex]['value'][vindex].typev = this.types[e.target.selectedIndex].value;
                this.renderForm();
            },
            renderForm(){
                this.form.push('11');
                this.form.pop('11');
            },
            addMoreField(findex, data = null) {
                let base = this;
                if (findex == 'undefined') {
                    base.resourceTypes.forEach(function (element, index) {
                        base.form[index] = [];
                        base.form[index]['rtype'] =  element.value;
                        base.form[index]['value'] = [];
                    });

                    if( data ){
                        data.forEach(function (element, index){
                            let rt = element.rtype;
                            base.form.forEach(function (f, i) {
                                if( f.rtype == rt ){
                                    let result = _.find(base.types, function (o) {
                                        return o.value == element.type;
                                    });
                                    let t = result.name;

                                    let f = {
                                        id: element.id,
                                        namespace: element.namespace,
                                        key: element.key,
                                        typen: t,
                                        typev: element.type,
                                        label: element.label,
                                        rtype: element.rtype,
                                    }

                                    base.form[i]['value'].push(f);
                                    // base.form[i]['value'] = [];
                                    // base.form[index]['value'].push(f);
                                }
                            });
                        });
                    }
                } else {
                    let f = {
                        id: '',
                        namespace: base.namespace,
                        key: '',
                        typen: 'String',
                        typev: 'string',
                        label: '',
                        rtype: base.form[findex]['rtype'],
                    }
                    base.form[findex]['value'].push(f);
                    this.renderForm();
                }
            },
            removeField(findex, vindex, id) {
                delete this.errors[`data.${findex}.${vindex}.namespace`];
                delete this.errors[`data.${findex}.${vindex}.key`];
                delete this.errors[`data.${findex}.${vindex}.label`];
                this.form[findex]['value'].splice(vindex, 1);
                this.renderForm();
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
                        contextualSaveBar.set({saveAction: {loading: true}});
                        contextualSaveBar.set({discardAction: {disabled: true}});
                        base.sendForm(true);
                    });
                },
            async sendForm(isSubmit) {
                    let base = this;
                    let url = 'allmetafieldconfiguration';
                    let method = 'post';

                    let data = [];
                    base.form.forEach(function (element, index) {
                        if (typeof element.value !== 'undefined' && element.value.length > 0) {
                            data[index] = element.value;
                        }
                    });
                    base.saveClick = 1;
                    await axios({
                        url: url,
                        data: {
                            'isSubmit': isSubmit,
                            'removeField': base.removeFieldsIds,
                            'data': data,
                        },
                        method: method,
                    }).then(function (res) {
                        if( res.data.data !== 1 ){
                            var contextualSaveBar = helper.contextualSaveBar();
                            contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                            let msg = res.data.data;
                            const toastNotice = Toast.create(base.app, {message: msg});
                            toastNotice.dispatch(Toast.Action.SHOW);

                            base.errors = [];
                            base.removeFieldsIds = [];

                            base.getMetafields();
                        }
                        base.saveClick = 0;
                    })
                        .catch(function (err) {
                            base.createContextualSaveBar();
                            base.errors = err.response.data;
                        });
            },
            async getMetafields() {
                let base = this;
                let url = 'metafieldconfiguration?api=2';
                let method = 'get';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    let data = res.data.data.entity;
                    base.namespace = res.data.data.namespace;
                    base.form = [];
                    if (data.length) {
                        base.addMoreField('undefined', data);
                    } else {
                        base.addMoreField('undefined');
                    }

                    var arr = [];
                    base.form.forEach(function (e) {
                        arr.push(e.value);
                    });
                    base.watch = true;
                    base.temp_prisine = JSON.stringify(arr);
                    // base.temp_prisine = JSON.stringify(base.form);
                })
                    .catch(function (err) {
                        console.log(err);
                    }).finally(res => {
                        base.is_contentload = false;
                    });
            },
        },
        created() {
            this.getMetafields();
        },
        watch: {
            'form': {
                immediate: true,
                deep: true,
                handler: function (data) {
                    var arr = [];
                    this.form.forEach(function (e) {
                        arr.push(e.value);
                    });
                    if( this.watch ){
                        if ( this.temp_prisine === JSON.stringify(arr)) {
                            let contextualSaveBar = helper.contextualSaveBar();
                            contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                        } else {
                            this.createContextualSaveBar();
                        }
                    }
                },
            },

        },
        filters:{
            format_resourceType: function(str){
                return str.replace('_', ' ');
            }
        }
    };
</script>

<style>
    .config-error {
        color: #f90000 !important;
    }
</style>
