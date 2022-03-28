<template>
    <div class="Polaris-Page__Content">
        <back-arrow :name=rtype :label=label :resourceType=rtype></back-arrow>

        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header">
                    <h1 class="Polaris-Heading">Settings</h1>
                 </div>
                <div class="Polaris-Card__Section">
                    <div>
                        <div class="d-flex align-center" style="margin: 0px 0px 10px 0px;">
                            <a href="#" class="export-import" @click="exportConfiguration()">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M13.707 6.707a.996.996 0 0 1-1.414 0L11 5.414V13a1 1 0 1 1-2 0V5.414L7.707 6.707a1 1 0 0 1-1.414-1.414l3-3a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 0 1.414zM17 18H3a1 1 0 1 1 0-2h14a1 1 0 1 1 0 2z"></path>
                                </svg>
                                Export Configuration
                            </a>
                            <a href="#" class="export-import" @click="showImportModel = !showImportModel">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 13.707l-3-3a1 1 0 0 1 1.414-1.414L9 10.586V3a1 1 0 1 1 2 0v7.586l1.293-1.293a1 1 0 0 1 1.414 1.414l-3 3a1 1 0 0 1-1.414 0zM17 16a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h14z"></path>
                                </svg>
                                Import Configuration
                            </a>
                        </div>

                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label class="Polaris-Label__Text">
                                    Custom Namespace (minimum of 3 characters, maximum of 20 characters)
                                </label>
                            </div>
                        </div>
                        <div class="Polaris-TextField">
                            <input id="namespace" class="Polaris-TextField__Input"
                                                              min="-Infinity" max="Infinity" step="1"
                                                              type="text"
                                                              aria-describedby="PolarisTextField2HelpText"
                                                              aria-invalid="false" aria-multiline="false"
                                                              value="" v-model="form.global_namespace"
                        >
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>
                        <div class="Polaris-Labelled__Error" v-if="errors['data.global_namespace']"><div id="PolarisTextFieldNamespaceError" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors['data.global_namespace'][0]}}</div></div>

                        <div class="Polaris-Labelled__LabelWrapper" style="margin-top: 10px;">
                            <div class="Polaris-Label">
                                <label class="Polaris-Label__Text">
                                    Add Email
                                </label>
                            </div>
                        </div>
                        <div class="Polaris-TextField">
                            <input id="email" class="Polaris-TextField__Input"
                                   min="-Infinity" max="Infinity" step="1"
                                   type="text"
                                   aria-describedby="PolarisTextField2HelpText"
                                   aria-invalid="false" aria-multiline="false"
                                   value="" v-model="form.email"
                            >
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>
                        <div class="Polaris-Labelled__Error" v-if="errors['data.email']"><div id="PolarisTextFieldEmailError" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors['data.email'][0]}}</div></div>
                    </div>
                </div>

            </div>
        </div>
        <!--     import modal-->
        <model v-if="showImportModel" @update="updateMessage">
            <h1 slot="model_header">Import Metafields from CSV file</h1>
            <div slot="model_body">
                <form ref="form" enctype="multipart/form-data" id="import-metafieldcsv-form" class="mb-0">
                    <div class="Polaris-TextField mb-15">
                        <div class="Polaris-TextField__Backdrop"></div>
                        <div>
                            <input @change="is_disable = false" id="PolarisFileField" name="csv_file"
                                   class="Polaris-TextField__Input PolarisFileField"
                                   type="file" placeholder="Select File" accept=".csv">
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>
                    </div>
                    <div class="mb-15">Download a <a :href="download_path" target="_self" download="" >Sample CSV template</a> to see an example of
                        the
                        format required.
                    </div>
                </form>
            </div>
            <div slot="model_footer">
                <div class="d-flex align-center">
                    <button type="button" class="Polaris-Button"
                            @click="showImportModel = false"><span
                        class="Polaris-Button__Content"><span
                        class="Polaris-Button__Text">Cancel</span></span></button>
                    <button v-bind:class="{'Polaris-Button--disabled' : ( is_disable)}" type="button" class="Polaris-Button ml-16  Polaris-Button--primary" @click.prevent="importConfiguration()">
                                <span class="Polaris-Button__Content">
                                <span class="Polaris-Button__Text">Upload File</span></span></button>
                    <div class="Polaris-ButtonGroup Polaris-ButtonGroup--segmented">
                    </div>
                </div>
            </div>
        </model>
        <page-footer></page-footer>
    </div>
</template>

<script>
    import BackArrow from '../../shopify/BackArrow';
    import helper from "../../../helper";
    import {Button, ContextualSaveBar, Modal, Toast} from "@shopify/app-bridge/actions";
    import Model from "../../shopify/Model";
    import PageFooter from "../../shopify/PageFooter";

    export default{
        components: {
            BackArrow,
            Model,
            PageFooter
        },
        data(){
            return{
                form: '',
                label: 'Dashboard',
                rtype: 'dashboard',
                errors: [],
                temp_prisine: '',
                watch: false,
                is_disable: true,
                is_replace: false,
                showImportModel: false,
                download_path: '/static_upload/sample_import_configuration.csv'
            }
        },
        methods:{
            updateMessage(message) {
                this.showImportModel = message
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

                if( base.is_load ){
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.SAVE);
                }

                contextualSaveBar.subscribe(ContextualSaveBar.Action.DISCARD, function () {
                    base.errors = [];
                    base.getSettings();
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                });
                contextualSaveBar.subscribe(ContextualSaveBar.Action.SAVE, function () {
                    contextualSaveBar.set({saveAction: {loading: true}, discardAction: {disabled: true}});
                    base.sendForm();

                });
            },
            async getSettings() {
                let base = this;
                let url = 'setting?api=1';
                let method = 'get';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    let data = res.data.data;
                    if (data.length !== 0) {
                        base.form = '';
                            let f = {
                                global_namespace: data.global_namespace,
                                email: data.email
                            };
                            base.form = f;
                    }else{
                        let f = {
                            global_namespace : '',
                            email : '',
                        }
                        base.form = f;
                    }
                    base.watch = true;
                    base.temp_prisine = JSON.stringify(base.form);
                }).catch(function (err) {
                    console.log(err);
                });
            },
            async sendForm() {
                let base = this;
                let url = 'setting';
                let method = 'post';

                await axios({
                    url: url,
                    data: {
                        'data': base.form,
                    },
                    method: method,
                }).then(function (res) {
                    var contextualSaveBar = helper.contextualSaveBar();
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);

                    let msg = res.data.data;
                    const toastNotice = Toast.create(shopify_app_bridge, {message: msg});
                    toastNotice.dispatch(Toast.Action.SHOW);

                    base.errors = [];
                    base.getSettings();
                })
                    .catch(function (err) {
                        console.log(err);
                        base.createContextualSaveBar();
                        base.errors = err.response.data;
                    });
            },
            async exportConfiguration(){
                let base = this;
                let url = 'export-importconfiguration?t=export';
                let method = 'post';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    let data = res.data.data;
                    if( data.is_download ){
                        const link = document.createElement('a');
                        link.href = data.file;
                        link.setAttribute('download', data.filename); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                    }else{
                        base.showToast(data.msg, true);
                    }

                }).catch(function (err) {
                    console.log(err);
                });
            },
            async importConfiguration(){
                let base = this;
                base.is_disable = true;
                let formData = new FormData(this.$refs.form);
                formData.append('t', 'import');
                try {
                    let {data} = await axios.post('/export-importconfiguration', formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                '_method': 'post',
                            }
                        });
                    base.showImportModel = false;
                    let msg = data.data.msg;
                    base.showToast(msg, false);
                    // base.showPopup('Import Successful', msg);
                    base.is_disable = true;
                } catch ({response}) {
                    let msg = response.data.data;
                    base.showToast(msg, true);
                    // base.showPopup('Import Warning!!', msg);
                    console.log(response);
                }
            },
            showPopup(title, msg){
                const okButton = Button.create(shopify_app_bridge, {label: 'Ok'});
                const modalOptions = {
                    title: title,
                    message: msg,
                    footer: {
                        buttons: {
                            primary: okButton,
                        },
                    },
                };
                const myModal = Modal.create(shopify_app_bridge, modalOptions);
                myModal.dispatch(Modal.Action.OPEN);
                okButton.subscribe(Button.Action.CLICK, data => {
                    myModal.dispatch(Modal.Action.CLOSE);
                });
            },
            showToast(msg, isError){
                const toastNotice = Toast.create(shopify_app_bridge, {message: msg, isError: isError});
                toastNotice.dispatch(Toast.Action.SHOW);
            }
        },
        created(){
            this.getSettings();
        },
        watch: {
            form: {
                immediate: true,
                deep: true,
                handler: function () {
                    if( this.watch ) {
                        if (_.isEqual(this.temp_prisine, JSON.stringify(this.form))) {
                            let contextualSaveBar = helper.contextualSaveBar();
                            contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                            this.errors = [];
                        } else {
                            this.createContextualSaveBar();
                        }
                    }
                }
            }
        }
    }
</script>
