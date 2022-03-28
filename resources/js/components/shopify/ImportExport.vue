<template>
    <div class="w-100 ">
        <div class="d-flex align-center justify-content-between mt-10">
            <div class="d-flex align-center justify-content-between w-100">
                <div class="d-flex align-center">
                    <a href="#" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing )}" class="export-import" @click = "!is_syncing ? openModel('export', '') : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M13.707 6.707a.996.996 0 0 1-1.414 0L11 5.414V13a1 1 0 1 1-2 0V5.414L7.707 6.707a1 1 0 0 1-1.414-1.414l3-3a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 0 1.414zM17 18H3a1 1 0 1 1 0-2h14a1 1 0 1 1 0 2z"></path>
                        </svg>
                        Export
                    </a>
                    <a href="#" class="export-import" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}" @click = "!is_syncing ? openModel('import', resourceType) : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M9.293 13.707l-3-3a1 1 0 0 1 1.414-1.414L9 10.586V3a1 1 0 1 1 2 0v7.586l1.293-1.293a1 1 0 0 1 1.414 1.414l-3 3a1 1 0 0 1-1.414 0zM17 16a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h14z"></path>
                        </svg>
                        Import
                    </a>
                    <a href="#" class="export-import" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}" @click = "!is_syncing ? openModel('sync', '') : ''">
                        <svg xmlns="" viewBox="0 0 20 20" style="width:16px;">
                            <path
                                d="M19.707 3.293a.999.999 0 0 1 0 1.414l-3 3a.997.997 0 0 1-1.414 0 .999.999 0 0 1 0-1.414L16.586 5H7C4.794 5 3 6.794 3 9a1 1 0 1 1-2 0c0-3.309 2.691-6 6-6h9.586l-1.293-1.293A.999.999 0 1 1 16.707.293l3 3zM17 10a1 1 0 0 1 1 1c0 3.31-2.69 6-6 6H3.414l1.293 1.293a.999.999 0 1 1-1.414 1.414l-3-3a.999.999 0 0 1 0-1.414l3-3a.999.999 0 1 1 1.414 1.414L3.414 15H12c2.206 0 4-1.794 4-4a1 1 0 0 1 1-1z"></path>
                        </svg>
                        Sync
                    </a>

                    <a href="#" v-if="resourceType == 'products'" class="export-import" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}" @click = "!is_syncing ? openModel('import', 'variants') : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M9.293 13.707l-3-3a1 1 0 0 1 1.414-1.414L9 10.586V3a1 1 0 1 1 2 0v7.586l1.293-1.293a1 1 0 0 1 1.414 1.414l-3 3a1 1 0 0 1-1.414 0zM17 16a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h14z"></path>
                        </svg>
                        Import Variants
                    </a>
                    <a href="#" v-if="resourceType == 'blogs'" class="export-import" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}" @click = "!is_syncing ? openModel('import', 'articles') : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M9.293 13.707l-3-3a1 1 0 0 1 1.414-1.414L9 10.586V3a1 1 0 1 1 2 0v7.586l1.293-1.293a1 1 0 0 1 1.414 1.414l-3 3a1 1 0 0 1-1.414 0zM17 16a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h14z"></path>
                        </svg>
                        Import Articles
                    </a>
<!--                    Global configurations-->
                    <router-link :to="{name : ( is_global ) ? 'globalisting' : 'globalmetafield', query : {id : ''}, params: { resourceType : resourceType, back: ( is_global ) ? 'globalisting' : resourceType, is_syncing: is_syncing}}" tag="a" href="#" class="export-import" v-if="resourceType !== 'shop'">
                        <svg xmlns="" viewBox="0 0 20 20" style="width:16px;">
                            <path
                                d="M15.873 13.679l-.72 4.32H4.677l-1.431-3.576c.59-.177 1.628-.134 3.047 1.284A.999.999 0 1 0 8 15V7a1.001 1.001 0 0 1 2 0v4a1 1 0 0 0 .684.949l5.189 1.73zM5 7.999A3 3 0 0 1 2 5c0-1.653 1.346-3 3-3h10c1.654 0 3 1.347 3 3 0 1.656-1.346 3-3 3h-3V7c0-1.653-1.346-3-3-3S6 5.347 6 7v1H5zm10 2c2.757 0 5-2.242 5-5 0-2.756-2.243-5-5-5H5A5.008 5.008 0 0 0 0 5c0 2.758 2.243 5 5 5h1v2.972c-2.184-1.214-3.959-.426-4.707.322-.283.283-.37.707-.222 1.079l2 5c.153.379.52.628.929.628h12a.999.999 0 0 0 .986-.835l1-6a.999.999 0 0 0-.67-1.113L12 10.28V10h3z"></path>
                        </svg>
                        Global Values
                    </router-link>
<!--                    END:: Global configurations-->
                </div>
                <div class="d-flex align-center">
                    <div class="mr-10" v-if="lbl1">
                        <router-link
                            :to="{name :'metafields', params: { resourceType : resourceType, shop : shop  }}"
                            tag="button" class="Polaris-Button">
                            <span class="Polaris-Button__Content">
                            <span class="Polaris-Button__Text">{{lbl1}}</span>
                            </span>
                        </router-link>
                    </div>
                    <div v-if="lbl2" class="mr-10">
                        <router-link
                            :to="{name :'metafields', params: { resourceType : r2, shop : shop  }}"
                            tag="button" class="Polaris-Button">
                            <span class="Polaris-Button__Content"></span>
                            <span class="Polaris-Button__Text"  style="font-size: 1.4rem;">{{lbl2}}</span>
                        </router-link>
                    </div>
                </div>
            </div>
            <!--     export modal-->
                <model v-if="showExportModel" @update="updateMessage">
                    <h1 slot="model_header">Export Metafields to CSV file</h1>
                    <div slot="model_body">
                        <div
                            style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb;">
                            <div class="Polaris-Stack Polaris-Stack--vertical" v-if="resourceType === 'shop'">
                                <span><h3>Are you sure to export all metafields of shop??</h3></span>
                            </div>
                            <div class="Polaris-Stack Polaris-Stack--vertical" v-else>
                                <span><h3>Export :: </h3></span>
                                <div class="Polaris-Stack__Item">
                                    <div><label class="Polaris-Choice" for="disabled"><span class="Polaris-Choice__Control"><span
                                        class="Polaris-RadioButton"><input id="disabled" name="accounts" type="radio"
                                                                           v-model="export_metafield"
                                                                           :selected="(export_metafield === 'all') ? 'selected' : '' "
                                                                           class="Polaris-RadioButton__Input"
                                                                           aria-describedby="disabledHelpText" value="all"
                                                                           checked=""><span
                                        class="Polaris-RadioButton__Backdrop"></span><span
                                        class="Polaris-RadioButton__Icon"></span></span></span><span
                                        class="Polaris-Choice__Label">All {{resourceType | format_resourceType}}</span></label>
                                    </div>
                                </div>
                                <div class="Polaris-Stack__Item">
                                    <div><label class="Polaris-Choice" for="optional"><span class="Polaris-Choice__Control"><span
                                        class="Polaris-RadioButton"><input :disabled="checkedIDs.length <= 0" id="optional"
                                                                           name="accounts" type="radio"
                                                                           v-model="export_metafield"
                                                                           :selected="(export_metafield === 'selected') ? 'selected' : '' "
                                                                           class="Polaris-RadioButton__Input"
                                                                           aria-describedby="optionalHelpText"
                                                                           value="selected"><span
                                        class="Polaris-RadioButton__Backdrop"></span><span
                                        class="Polaris-RadioButton__Icon"></span></span></span><span
                                        class="Polaris-Choice__Label">Selected </span><span v-if="checkedIDs.length > 0">: {{checkedIDs.length}} {{resourceType, checkedIDs  | format_string}}</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div slot="model_footer">
                        <div class="d-flex align-center">
                            <button type="button" class="Polaris-Button"
                                    @click="closeExport()"><span
                                class="Polaris-Button__Content"><span
                                class="Polaris-Button__Text">Cancel</span></span></button>
                            <button type="button"
                                    class="Polaris-Button ml-16 Polaris-Button--primary"
                                    @click="generateCSV()" v-bind:class="{'Polaris-Button--disabled' : ( is_disableExport)}">
                                    <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Text">Confirm</span></span></button>
                            <div class="Polaris-ButtonGroup Polaris-ButtonGroup--segmented">
                            </div>
                        </div>
                    </div>
                </model>
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
                        <div class="mb-10">
                            <label class="Polaris-Choice" for="PolarisCheckbox2"><span
                                class="Polaris-Choice__Control"><span
                                class="Polaris-Checkbox"><input id="PolarisCheckbox2" type="checkbox"
                                                                class="Polaris-Checkbox__Input" aria-invalid="false"
                                                                role="checkbox" aria-checked="false" value="0"
                                                                v-on:click="is_replace = !is_replace"><span
                                class="Polaris-Checkbox__Backdrop"></span><span class="Polaris-Checkbox__Icon"><span
                                class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg"
                                                          focusable="false"
                                                          aria-hidden="true">
                          <path
                              d="M8.315 13.859l-3.182-3.417a.506.506 0 0 1 0-.684l.643-.683a.437.437 0 0 1 .642 0l2.22 2.393 4.942-5.327a.437.437 0 0 1 .643 0l.643.684a.504.504 0 0 1 0 .683l-5.91 6.35a.437.437 0 0 1-.642 0"></path>
                        </svg></span></span></span></span><span class="Polaris-Choice__Label">Replace any current Metafields that have the same key. Existing values will be used for any missing columns.</span></label>
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
                        <button v-bind:class="{'Polaris-Button--disabled' : ( is_disable)}" type="button"
                                class="Polaris-Button ml-16  Polaris-Button--primary"
                                @click="importCSV()">
                                <span class="Polaris-Button__Content">
                                <span class="Polaris-Button__Text">Upload File</span></span></button>
                        <div class="Polaris-ButtonGroup Polaris-ButtonGroup--segmented">
                        </div>
                    </div>
                </div>
            </model>
        </div>
    </div>
</template>

<script>
    import {Modal, Toast} from '@shopify/app-bridge/actions';
    import {Button} from '@shopify/app-bridge/actions';
    import Model from "./Model";
    import Alert from "./Alert";
    import CustomMetafield from "../pages/CustomMetafield";

    export default {
        name: 'ImportExport',
        props: ['resourceType', 'checkedIDs', 'shop', 'lbl1', 'lbl2', 'r2', 'id', 'is_data', 'dropzone1', 'dropzone2', 'ownerId'],
        components: {
            Model,
            Alert,
            CustomMetafield,
        },
        data() {
            return {
                showExportModel: false,
                showImportModel: false,
                is_replace: false,
                is_disable: true,
                export_metafield: 'all',
                resource_id: this.id,
                showModel: false,
                is_disableExport: false,
                download_path: '',
                is_syncing: false,
                is_global: false,
                differrtype: '',
            }
        },
        methods: {
            closeModel(message, is_refresh) {
                this.showModel = false;
                this.$emit('update', false, is_refresh);
            },
            closeExport() {
                this.export_metafield = 'all';
                this.showExportModel = false;
            },
            async generateCSV() {
                let base = this;
                let data = (base.export_metafield === 'selected') ? base.checkedIDs : '';
                base.is_disableExport = false;
                await axios({
                    url: '/generate-csv',
                    data: {
                        'type': 'export',
                        'data': data,
                        'resourceType': base.resourceType,
                        'export_metafield': base.export_metafield,
                        'id': base.resource_id
                    },
                    method: 'POST',
                }).then(function (res) {
                    let msg = res.data.data.msg;
                    base.showToast(msg, false);
                    base.getIsSynced();
                })
                    .catch(function (err) {
                        let msg = err.response.data.data;
                        base.showToast(msg, true);
                    })
                    .finally(res => {
                        base.is_disableExport = false;
                        base.export_metafield = 'all';
                        base.showExportModel = false;
                    });
            },
            async openModel(type, diffrtype) {
                let base = this;
                if (type === 'import') {
                    if( diffrtype == 'variants' || diffrtype == 'articles' ){
                        base.differrtype = diffrtype;
                        base.download_path = ( base.differrtype === 'shop' ) ?  '/static_upload/sample_import_shop.csv' : '/static_upload/sample_import_' + this.differrtype + '.csv';
                    }else{
                        base.differrtype = '';
                        base.download_path = ( base.resourceType === 'shop' ) ?  '/static_upload/sample_import_shop.csv' : '/static_upload/sample_import_' + base.resourceType + '.csv';
                    }
                    base.showImportModel = true;
                } else if (type === 'export') {
                    if( base.resourceType !== 'shop' ){
                        if( base.is_data ){
                            base.showExportModel = true;
                        }else{
                            base.showPopup('Export Warning!!', 'Their are no any data.');
                        }
                    }else{
                        base.showExportModel = true;
                    }

                } else if (type === 'sync') {
                    base.syncMeta();
                }
            },
            async importCSV() {
                let base = this;
                base.is_disable = true;
                let rt = ( base.differrtype == '' ) ? base.resourceType : base.differrtype;
                let formData = new FormData(this.$refs.form);
                formData.append('type', 'import');
                formData.append('is_replace', base.is_replace);
                formData.append('resourceType', rt);
                try {
                    let {data} = await axios.post('/generate-csv', formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                '_method': 'post',
                            }
                        });
                    base.showImportModel = false;
                    let msg = data.data.msg;
                    base.showToast(msg, false);
                    base.is_disable = true;
                    base.getIsSynced();
                    base.$root.$emit('get-syncdata', base.resourceType);
                } catch ({response}) {
                    let msg = response.data.data;
                    base.is_disable = false;
                    base.showToast(msg, true);
                    // base.showPopup('Import Warning', msg);
                    console.log(response);
                }
            },
            updateMessage(message) {
                this.showImportModel = message
                this.showExportModel = message
            },
            syncMeta() {
                let base = this;
                console.log(base.resource_id);
                const okButton = Button.create(shopify_app_bridge, {label: 'Confirm'});
                const cancelButton = Button.create(shopify_app_bridge, {label: 'Cancel'});
                const modalOptions = {
                    title: 'Sync Metafields',
                    message: 'Are you sure to sync all metafield of ' + this.resourceType.replace('_', ' ') + '??',
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
                });
                okButton.subscribe(Button.Action.CLICK, data => {
                    myModal.dispatch(Modal.Action.CLOSE);
                    var endPoint = '';

                    if( base.resourceType === 'articles' || base.resourceType === 'variants' ){
                        endPoint = '/sync-meta?rt=' + base.resourceType + '&id=' + base.resource_id;
                    }else{
                        endPoint = '/sync-meta?rt=' + base.resourceType;
                    }
                    axios.get(endPoint)
                        .then(res => {
                            var msg = res.data.data;
                            base.showToast(msg, false);
                            base.getIsSynced();
                        })
                        .catch(err => {
                            console.log(err);
                        })
                });
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
            async getIsSynced() {
                let base = this;
                await axios.get('/get-issynced?rtype=' + base.resourceType)
                    .then(res => {
                        let data = res.data.data;
                        base.is_syncing = data.synced_data.is_syncing;
                        base.is_global = data.is_global;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            showToast(msg, isError){
                const toastNotice = Toast.create(shopify_app_bridge, {message: msg, isError: isError});
                toastNotice.dispatch(Toast.Action.SHOW);
            }
        },
        created() {
            this.download_path = ( this.resourceType === 'shop' ) ?  '/static_upload/sample_import_shop.csv' : '/static_upload/sample_import_' + this.resourceType + '.csv';
            this.getIsSynced();
        },
        filters: {
            format_string: function (str, ids) {
                return (ids.length === 1) ? str.substring(0, str.length - 1) : str;
            },
            format_resourceType: function (str) {
                return str.replace('_', ' ');
            }
        }
    }
</script>

