<template>
    <div class="Polaris-Page__Content">
            <back-arrow :name=rtype :label=label :resourceType=rtype :base_id=base_id></back-arrow>
        <alert ref="syncalert" v-if="resourceType === 'shop'" :resourceType="resourceType"></alert>

<!--        content Loader-->
        <content-loader :width="300" :height="200" primaryColor="#e0e4e8" class="Polaris-Page__Content" v-if="is_contentload">
            <!--            first section-->
            <rect x="10" y="10" rx="3" ry="3" width="50" height="10"/>
            <rect x="170" y="10" rx="3" ry="3" width="70" height="10"/>
            <rect x="0" y="30" rx="3" ry="3" width="250" height="1"/>

            <!--            second section-->
            <rect x="10" y="40" rx="3" ry="3" width="40" height="10"/>
            <rect x="55" y="40" rx="3" ry="3" width="40" height="10"/>
            <rect x="100" y="40" rx="3" ry="3" width="40" height="10"/>

            <rect x="210" y="40" rx="3" ry="3" width="10" height="10"/>
            <rect x="225" y="40" rx="3" ry="3" width="10" height="10"/>

            <rect x="10" y="60" rx="3" ry="3" width="230" height="10"/>
            <rect x="0" y="80" rx="3" ry="3" width="250" height="1"/>

            <!--            third section-->
            <rect x="10" y="90" rx="3" ry="3" width="40" height="10"/>
            <rect x="55" y="90" rx="3" ry="3" width="40" height="10"/>
            <rect x="100" y="90" rx="3" ry="3" width="40" height="10"/>

            <rect x="210" y="90" rx="3" ry="3" width="10" height="10"/>
            <rect x="225" y="90" rx="3" ry="3" width="10" height="10"/>

            <rect x="10" y="110" rx="3" ry="3" width="230" height="10"/>
            <rect x="0" y="130" rx="3" ry="3" width="250" height="1"/>

            <!--            fourth section-->
            <rect x="10" y="140" rx="3" ry="3" width="40" height="10"/>
            <rect x="55" y="140" rx="3" ry="3" width="40" height="10"/>
            <rect x="100" y="140" rx="3" ry="3" width="40" height="10"/>

            <rect x="210" y="140" rx="3" ry="3" width="10" height="10"/>
            <rect x="225" y="140" rx="3" ry="3" width="10" height="10"/>

            <rect x="10" y="160" rx="3" ry="3" width="230" height="10"/>
            <rect x="0" y="180" rx="3" ry="3" width="250" height="1"/>
        </content-loader>
<!--        END:: content Loader       -->

<!--        Main content-->
        <div class="Polaris-Page__Content" v-else-if="is_data">
            <div class="Polaris-Card">
                <div class="custom-box hide-box-shadow p-0">
                    <div class="met-according">
                        <div class="met-according-item d-xs-block" v-if="resourceType !== 'shop'">
                            <div class="w-100">
                                <h1 class="Polaris-Heading" v-if="resourceType !== 'customers' || 'orders'">{{resourceData.title}}</h1>
                                <h1 class="Polaris-Heading" v-if="resourceType === 'customers' || 'orders'">
                                    {{resourceData.name}}</h1>

                            </div>

                            <div class="d-flex" v-if="resourceType !== 'shop'">
                                <button type="button" class="Polaris-Button Polaris-Button--primary"
                                        @click="showModel = true">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Create Metafield</span>
                                        </span>
                                </button>
                            </div>
                        </div>
                        <div class="Polaris-Stack Polaris-Stack--alignmentBaseline met-according-item"
                             v-if="resourceType === 'shop'">
                            <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                                <h2 class="Polaris-Heading" style="font-size: 2rem !important;">Shop</h2>
                            </div>
                            <import-export :resourceType="resourceType" :lbl1="`Configure Shop`"
                                           :shop="shop" :dropzone1="dropzoneOptions" :dropzone2="dropzoneOptionsMulti"
                                           :ownerId="resourceData.id" @update="closeModel"></import-export>
                        </div>
                        <!--        Sync custom tab-->
                        <div>
                            <div class="Polaris-Tabs__Wrapper">
                                <ul role="tablist" class="Polaris-Tabs">
                                    <li class="Polaris-Tabs__TabContainer">
                                        <button id="all-customers" role="tab" type="button" tabindex="0" class="Polaris-Tabs__Tab" v-bind:class="{'Polaris-Tabs__Tab--selected' : ( getValueType === 'apps')}" aria-selected="true" aria-controls="all-customers-content" aria-label="Apps Metafields" @click="getMetafields('apps')">
                                            <span class="Polaris-Tabs__Title">Apps Metafields</span>
                                        </button>
                                    </li>
                                    <li class="Polaris-Tabs__TabContainer">
                                        <button id="accepts-marketing" role="tab" type="button" tabindex="-1" class="Polaris-Tabs__Tab" aria-selected="false" v-bind:class="{'Polaris-Tabs__Tab--selected' : ( getValueType === 'sync')}"aria-controls="accepts-marketing-content" @click="getMetafields('sync')">
                                            <span class="Polaris-Tabs__Title">Synced Metafields</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--        END:: Sync custom tab-->
                        <form ref="form" enctype="multipart/form-data" id="metafield-form">
                            <div class="met-according-body p-0">
                                <div class="item ad-metafield-item" v-for="(f, index) in form">
                                    <div class="d-flex d-xs-block align-center justify-content-between mb-10">
                                        <div class="d-flex tag-list mb-xs-10">
                                            <div class="tag tag-color"><b>Namespace:</b>
                                                <span>{{f.namespace}}</span></div>
                                            <div class="tag tag-color"><b>Key:</b> <span>{{f.key}}</span></div>
                                            <div class="tag tag-color text-capitalize"><b>Type:</b> <span>{{f.type | format_string}}</span></div>
                                        </div>

                                        <div class="d-flex align-center justify-content-xs-end">
                                            <span v-if="f.type === 'file' && f.id !== ''" class="mr-10">Currently file available</span>
                                            <a class="Polaris-Button mr-10" v-if="f.type === 'file'" :href="f.value" target="_blank" download="" v-bind:class="{'Polaris-Button--disabled' : ( f.id === '')}">
                                                <span class="Polaris-Button__Content">
                                                <span class="Polaris-Button__Text"><i class="ion ion-md-download"
                                                                                      style="font-size: 2rem;"></i></span></span>
                                            </a>
                                            <button v-tooltip.top-center="toolTip"
                                                    v-bind:class="{'Polaris-Button--disabled' : ( f.id == '' )}"
                                                    type="button" class="Polaris-Button copyLiquid mr-10"
                                                    style="padding: .5rem 1rem;" tooltip="Copy liquid code"><span
                                                class="Polaris-Button__Content"
                                                v-on:click="CopyMetaField(index)"><span
                                                class="Polaris-Button__Text">
                                            <i class="ion ion-md-copy" style="font-size: 2rem;"></i></span></span>
                                            </button>

                                            <button v-bind:class="{'Polaris-Button--disabled' : ( f.id == '' )}"
                                                    type="button" class="Polaris-Button"
                                                    style="padding: .5rem 1rem;"
                                                    v-on:click="removeMetaField(index)"><span
                                                class="Polaris-Button__Content"><span class="Polaris-Button__Text">
                                            <i class="ion ion-md-trash" style="font-size: 2rem;"></i></span></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField" v-if="f.type === 'string'">
                                        <input :id="`PolarisTextField`+index" class="Polaris-TextField__Input"
                                               v-model="f.value" type="text" placeholder="Enter String"
                                               @input="current_chng_index = index">
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>

                                    <div class="Polaris-TextField" v-else-if="f.type === 'number'">
                                        <input :id="`PolarisTextField`+index" class="Polaris-TextField__Input"
                                               type="number" v-model="f.value" placeholder="Enter Number"
                                               @input="current_chng_index = index">
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>

                                    <div class="Polaris-TextField" v-else-if="f.type === 'phone'">
                                        <input :id="`PolarisTextField`+index" class="Polaris-TextField__Input"
                                               type="tel" v-model="f.value" placeholder="Enter Phone number"
                                               @input="current_chng_index = index">
                                        <div class="Polaris-TextField__Backdrop"></div>

                                    </div>

                                    <div class="Polaris-TextField" v-else-if="f.type === 'email'">
                                        <input :id="`PolarisTextField`+index" class="Polaris-TextField__Input"
                                               type="email" v-model="f.value" placeholder="Enter Email"
                                               @input="current_chng_index = index">
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>

                                    <div class="Polaris-TextField" v-else-if="f.type === 'url'">
                                        <input :id="`PolarisTextField`+index" class="Polaris-TextField__Input"
                                               type="url" v-model="f.value" placeholder="Enter url"
                                               @input="current_chng_index = index">
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>

                                    <div class="Polaris-TextField" v-else-if="f.type === 'file'">
                                        <div class="Polaris-TextField__Backdrop"></div>
                                        <div>
                                            <input id="PolarisFileField" name="file"
                                                   class="Polaris-TextField__Input PolarisFileField" type="file"
                                                   v-on:change="updateValue(index,'file', $event)"
                                                   placeholder="Select File">
                                            <div class="Polaris-TextField__Backdrop"></div>
                                        </div>
                                    </div>
                                    <div class="Polaris-Labelled__Error" v-if="file_error[index]"><div id="PolarisTextFieldError" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{file_error[index]}}</div></div>
                                    <div class="Polaris-TextField" v-else-if="f.type === 'json'">
                                            <textarea v-model="f.value" placeholder='e.x: {"name": "John", "age": 31, "city": "New York"}'
                                                      class="Polaris-TextField__Input"
                                                      id="PolarisTextField7"
                                                      @input="current_chng_index = index" rows="7"></textarea>
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>
                                    <div class="Polaris-Labelled__Error" v-if="errors[`data.${index}.value`]"><div id="PolarisTextField4Error" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors[`data.${index}.value`][0]}}</div></div>


                                    <vue-dropzone
                                        :ref="`myVueDropzone`+index"
                                        id="dropzone"
                                        v-else-if="f.type === 'image'"
                                        @vdropzone-success="updateValue(index,'image',$event)"
                                        @vdropzone-removed-file="updateValue(index,'remove_image',$event)"
                                        @vdropzone-mounted="renderImage(index, 'image')"
                                        @vdropzone-complete="updateValue(index,'image-added',$event)"
                                        :options="dropzoneOptions">
                                    </vue-dropzone>

                                    <vue-dropzone
                                        :ref="`myVueDropzoneMultiImage`+index"
                                        id="dropzone1"
                                        v-else-if="f.type === 'multiple_image'"
                                        @vdropzone-success="updateValue(index,'multiple_image',$event)"
                                        @vdropzone-removed-file="updateValue(index,'remove_multiple_image',$event)"
                                        @vdropzone-mounted="renderImage(index, 'multiple_image')"
                                        :options="dropzoneOptionsMulti">

                                    </vue-dropzone>

                                    <vue-editor v-model="f.value" v-else-if="f.type === 'rich_text_box'"
                                                @input="current_chng_index = index"></vue-editor>

                                    <div class="input-group color-picker-component"
                                         v-else-if="f.type == 'color_picker'">
                                        <input type="text"
                                               class="form-control"
                                               title="Color Picker"
                                               v-model="f.value"
                                               @blur="displayPicker = false"
                                        />
                                        <span class="input-group-addon color-picker-container">
                                        <span class="current-color"
                                              :style="'background-color: ' + f.value  "
                                              @click="displayPicker = !displayPicker"
                                        ></span>
                                         <chrome-picker v-model="f.value"
                                                        @input="updateValue(index,'color', $event)"
                                                        v-if="displayPicker"></chrome-picker>
                                            <!--                                    <chrome-picker v-model="f.value" @input="updateValue(index,'color')"></chrome-picker>-->
                                    </span>
                                    </div>

                                    <datepicker v-model="date" :format=formatDate
                                                    @input="updateValue(index,'date',$event)"
                                                v-else-if="f.type === 'date_picker'"></datepicker>
                                </div>
                            </div>
                        </form>

                        <div  v-if="(getValueType === 'apps' && !apps_data) || (getValueType === 'sync' && !sync_data)" class="text-center" style="padding: 2rem;">
                            <svg style="fill: #c3cfd8;width: 80px;margin-bottom: 5px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M8 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9.707 4.293l-4.82-4.82C13.585 10.493 14 9.296 14 8c0-3.313-2.687-6-6-6S2 4.687 2 8s2.687 6 6 6c1.296 0 2.492-.415 3.473-1.113l4.82 4.82c.195.195.45.293.707.293s.512-.098.707-.293c.39-.39.39-1.023 0-1.414z"></path></svg>
                            <h2 class="Polaris-Heading">There is no any {{(getValueType === 'sync' ? 'synced' : '')}} Metafields.</h2>
                            <p class="Polaris-TextStyle--variationSubdued"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        END:: Main content-->

        <no-data v-else label="Configure Metafield" :resourceType="resourceType" :shop="shop"></no-data>
        <input type="hidden" name="liquid" id="liquid" value="">
        <custom-metafield :show-model.sync="showModel" :dropzone1="dropzoneOptions" :dropzone2="dropzoneOptionsMulti"
                          :ownerId="resourceData.id" :handle="handle" :resourceType="resourceType"
                          @update="closeModel"></custom-metafield>
        <page-footer></page-footer>
    </div>
</template>

<script>
    import BackArrow from '../shopify/BackArrow';
    import Alert from '../shopify/Alert';
    import ImportExport from "../shopify/ImportExport";
    import helper from "../../helper";
    import CustomMetafield from "./CustomMetafield";
    import NoData from "../shopify/NoData";
    import {Button, ContextualSaveBar, Modal, Toast} from "@shopify/app-bridge/actions";
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
    import {VueEditor} from "vue2-editor";
    import {Chrome} from 'vue-color';
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment-timezone';
    import {ContentLoader} from 'vue-content-loader';
    import PageFooter from "../shopify/PageFooter";

    export default {
        components: {
            BackArrow,
            Alert,
            ImportExport,
            CustomMetafield,
            vueDropzone: vue2Dropzone,
            VueEditor,
            'chrome-picker': Chrome,
            Datepicker,
            ContentLoader,
            NoData,
            PageFooter
        },
        data() {
            return {
                displayPicker: false,
                resourceType: '',
                resourceData: [],
                form: '',
                showModel: false,
                is_contentload: true,
                temp_prisine: '',
                shop: '',
                date: new Date(),
                current_chng_index: -1,
                changeIndexes: [],
                getValueType: 'apps',
                dropzoneOptions: {
                    url: window.shopify_app_bridge.localOrigin + '/api/store-images',
                    maxFiles: 1,
                    maxFilesize: 4, //MB
                    acceptedFiles: 'image/*',
                    thumbnailWidth: 150,
                    addRemoveLinks: true,
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Upload image",
                },
                dropzoneOptionsMulti: {
                    url: window.shopify_app_bridge.localOrigin + '/api/store-images',
                    thumbnailWidth: 150,
                    maxFilesize: 4,
                    acceptedFiles: 'image/*',
                    addRemoveLinks: true,
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Upload images",
                },
                errors: [],
                file_error: [],
                label: '',
                rtype: '',
                is_load: true,
                is_check: true,
                is_data: false,
                toolTip: 'Copy liquid code',
                watch: false,
                handle: '',
                apps_data: true,
                sync_data: false,
                base_id: '',
            }
        },
        methods: {
            formatDate(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            updateValue(index, type, e) {
                let base = this;
                if (type === 'date') {
                    base.form[index].value = e.getTime();
                } else if (type === 'color') {
                    base.form[index].value = e.hex;
                } else if (type === 'file') {
                    base.file_error = [];
                        if (base.checkFileSize(e.target)) {
                            base.form[index].value = e.target.files[0];
                        } else {
                            base.form[index].value = e.target.files[0];
                            base.file_error[index] = 'File too Big, please select a file less than 4mb';
                        }
                } else if (type === 'image') {
                    console.log(e);
                    base.form[index].value = e;
                } else if (type === 'multiple_image') {
                    base.form[index].value.push(e);
                } else if (type === 'remove_image') {
                    base.form[index].value = '';
                    var nm = 'myVueDropzone' + index;
                    base.$refs[`${nm}`][0].enable();
                } else if (type === 'remove_multiple_image') {
                    if (e.upload) {
                        var remove_uuid = e.upload.uuid;

                        if (base.form[index].value.length > 0 && (Array.isArray(base.form[index].value))) {
                            base.form[index].value.forEach(function (value, i) {
                                if (typeof value !== 'string') {
                                    if (value.upload.uuid === remove_uuid) {
                                        base.form[index].value.splice(i, 1);
                                        return;
                                    }
                                }
                            });
                        }
                    } else {
                        let name = e.name;
                        if( name.indexOf('?') > 0 ){
                            name = name.substring(0, name.indexOf('?'));
                        }
                        if ((base.form[index].value.length > 0) && (Array.isArray(base.form[index].value))) {
                            base.form[index].value.forEach(function (value, i) {
                                let str = value;
                                if( value.indexOf('?') > 0 ){
                                    str = value.substring(0, value.indexOf('?'));
                                }

                                let v = str.substring(str.lastIndexOf('/') + 1);
                                if (v === name) {
                                    base.form[index].value.splice(i, 1);
                                    return;
                                }
                            });
                        }
                    }
                }else if (type === 'image-added') {
                    var nm = 'myVueDropzone' + index;
                    base.$refs[`${nm}`][0].disable();
                }
                base.current_chng_index = index;
            },
            checkFileSize(fi) {
                let base = this;
                base.file_error = [];
                if (fi.files.length > 0) {
                    for (let i = 0; i <= fi.files.length - 1; i++) {
                        const fsize = fi.files.item(i).size;
                        const file = Math.round((fsize / 1024));
                        // The size of the file.
                        if (file >= 4096) {
                            console.log('file >= 4096');
                            return false;
                        } else {
                            console.log('file < 4096');
                            base.file_error = [];
                        }
                    }
                } else {
                    base.file_error = [];
                }
                return true;
            },
            renderImage(index, type) {
                let base = this;
                if (type === 'image') {
                    let str = base.form[index].value;
                    if (str.length > 0) {
                        base.showImage(str, type, 0, index);
                    }
                } else if (type === 'multiple_image') {
                    let images = base.form[index].value;
                    images.forEach(function (image, i) {
                        base.showImage(image, type, i, index);
                    });
                }
            },
            showImage(str, dropZone, i, index) {
                let base = this;
                let new_str = str;
                if(str.indexOf('?') > 0){
                    new_str = str.substring(0, str.indexOf('?'));
                }
                var extension = str.substring(new_str.lastIndexOf('.') + 1);
                var name = str.substring(new_str.lastIndexOf('/') + 1);
                var file = {size: 150, name: name, type: "image/" + extension};
                var url = str;

                var nm = (dropZone === 'image') ? 'myVueDropzone' + index : 'myVueDropzoneMultiImage' + index;

                base.$refs[`${nm}`][0].manuallyAddFile(file, url);
                // ( dropZone == 'image' ) ? base.$refs[`${nm}`][0].manuallyAddFile(file, url) : base.$refs[`${nm}`][0].manuallyAddFile(file, url);
            },
            async removeMetaField(index) {
                let base = this;
                const okButton = Button.create(shopify_app_bridge, {label: 'Delete', style: Button.Style.Danger});
                const cancelButton = Button.create(shopify_app_bridge, {label: 'Cancel'});
                const modalOptions = {
                    title: 'Delete Metafield!!',
                    message: 'Are you sure to delete this metafield?',
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
                    let id = this.form[index].id;
                    let endPoint = '/delete-metafield?id=' + id;
                    axios.get(endPoint)
                        .then(res => {
                            console.log(res);
                            helper.successToast(res.data.data.msg);
                            // base.showPopup(res.data.data.title, res.data.data.msg);
                           base.getMetafields(base.getValueType);
                        })
                        .catch(err => {
                            console.log(err);
                        });
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
            closeModel(message, is_refresh) {
                this.showModel = message;
                if (is_refresh === 'refresh') {
                    this.getMetafields(base.getValueType);
                }
            },
            CopyMetaField(index) {
                var str = this.resourceType;
                var rtype = (str === 'smart_collections' || str === 'custom_collections') ? 'collection' : (str === 'shop') ? 'shop' : str.substring(0, str.length - 1);

                var val = "{{ " + rtype + ".metafields['" + this.form[index].namespace + "']['" + this.form[index].key + "'] }}";
                var element = document.getElementById('liquid');
                element.setAttribute('type', 'text');
                element.setAttribute('value', val);

                var copyText = document.getElementById('liquid');
                copyText.select();
                try {
                    document.execCommand("copy");
                    element.setAttribute('type', 'hidden');
                    const toastNotice = Toast.create(shopify_app_bridge, {message: 'Copied!'});
                    toastNotice.dispatch(Toast.Action.SHOW);

                } catch (err) {
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

                if (base.is_load) {
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.SAVE);
                }

                contextualSaveBar.subscribe(ContextualSaveBar.Action.DISCARD, function () {
                    base.errors = [];
                    base.getMetafields(base.getValueType);
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                });
                contextualSaveBar.subscribe(ContextualSaveBar.Action.SAVE, function () {
                    contextualSaveBar.set({saveAction: {loading: true}, discardAction: {disabled: true}});

                    if (base.is_check) {
                        base.checkError();
                    }
                });
            },
            async getMetafields(type) {
                let base = this;
                base.watch = false;
                base.getValueType = type;
                base.is_contentload = true;
                let url = 'metafield?api=1&resourceType=' + base.resourceType  + '&owner_id=' + base.resourceData.id + '&type=' + type;
                let method = 'get';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    let data = ( res.data.data.apps.length > 0 ) ? res.data.data.apps : res.data.data.sync;
                    base.apps_data = res.data.data.apps.length > 0;
                    base.sync_data = res.data.data.sync.length > 0;

                    let count = res.data.data.count;
                    if (count > 0) {
                        base.is_data = true;
                        base.form = [];
                        data.forEach(function (element, index) {
                            let v = '';
                            if (element.type === 'multiple_image') {
                                if (element.value === '') {
                                    v = [];
                                } else {
                                    v = element.value.split(',');
                                }
                            } else if (element.type === 'color_picker') {
                                v = (element.value === '') ? '#000000' : element.value;
                            } else if (element.type === 'date_picker') {
                                var date = new Date().getTime();
                                v = (element.value === '') ? date : element.value;
                                var timestamp = Number(element.value);
                                var d = new Date(timestamp);
                                base.date = (element.value !== '') ? d : base.date;
                            } else {
                                v = element.value;
                            }
                            let f = {
                                id: element.id,
                                metafield_configuration_id: element.metafield_configuration_id,
                                namespace: element.namespace,
                                key: element.key,
                                type: element.type,
                                value: v,
                                is_remove: false,
                                is_change: false,
                            }
                            base.form.push(f);
                        });
                    } else {
                        base.is_data = false;
                    }
                    base.watch = true;
                    base.changeIndexes = [];
                    base.temp_prisine = JSON.stringify(base.form);
                }).catch(function (err) {
                    console.log(err);
                }).finally(res => {
                    base.is_load = false;
                    base.is_contentload = false;
                });
            },
            async getShop() {
                let base = this;
                let endPoint = '/get-shop';

                await axios.get(endPoint)
                    .then(res => {
                        var data = res.data.data;
                        base.resourceData = data.shop;
                        this.getMetafields(base.getValueType);
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            async checkError() {
                let base = this;
                base.changeIndexes.forEach(function (index) {
                    base.form[index].is_change = true;
                });
                await axios({
                    url: 'metafield',
                    data: {
                        'isSubmit': false,
                        'data': base.form,
                        'resourceType': base.resourceType,
                    },
                    method: 'POST',
                }).then(function (res) {
                    if (res.data.data === true) {
                        base.errors = [];
                        if (base.file_error.length === 0) {
                            base.is_load = true;
                            base.is_check = false;
                            base.sendForm(true);
                        } else {
                            base.is_load = false;
                            base.is_check = true;
                            let contextualSaveBar = helper.contextualSaveBar();
                            contextualSaveBar.set({saveAction: {loading: false},discardAction: {disabled: false}});
                        }
                    }
                })
                    .catch(function (err) {
                        let contextualSaveBar = helper.contextualSaveBar();
                        contextualSaveBar.set({saveAction: {loading: false},discardAction: {disabled: false}});
                        base.errors = err.response.data;
                    });
            },
            async sendForm(isSubmit) {
                let base = this;
                let url = 'metafield';
                base.saveClick = 1;
                base.displayPicker = false;
                this.errors = [];
                let formData = new FormData(this.$refs.form);

                formData.append("owner_id", base.resourceData.id);
                formData.append("handle", base.handle);
                formData.append('isSubmit', isSubmit);
                formData.append('data', JSON.stringify(base.form));
                formData.append('resourceType', base.resourceType);
                formData.append('is_custom', false);

                base.form.forEach(function (element, index) {
                    if (element.type === "file") {
                        formData.append('file[' + index + ']', element.value);
                        element.value = 'file';
                    } else if (element.type === 'image') {
                        formData.append('image[' + index + ']', element.value);
                        element.value = 'image';
                    } else if (element.type === "multiple_image" && (Array.isArray(element.value))) {
                        element.value.forEach(function (image, i) {
                            formData.append('multiple_image[' + index + '][' + i + ']', image);
                        });
                        element.value = 'multiple_image';
                    }
                });
                try {
                    let {data} = await axios.post(url, formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                '_method': 'post',
                            }
                        });

                    let contextualSaveBar = helper.contextualSaveBar();
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                    base.errors = [];
                    base.saveClick = 0;
                    base.is_load = false;
                    base.is_check = true;

                    let msg = data.data;
                    const toastNotice = Toast.create(shopify_app_bridge, {message: msg});
                    toastNotice.dispatch(Toast.Action.SHOW);

                    base.getMetafields(base.getValueType);
                } catch ({response}) {
                    base.is_load = false;
                    base.is_check = true;
                    // let contextualSaveBar = helper.contextualSaveBar();
                    // contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                }
                return true;
            },
        },
        created() {
            let base = this;
            this.resourceType = this.$route.params.resourceType;

            if (this.resourceType === "shop") {
                this.getShop();
            } else {
                this.resourceData = this.$route.params.resource;
                this.shop = this.$route.params.shop;
                this.getMetafields('apps');
            }
            let hand = '';
            if( base.resourceType === 'variants' || base.resourceType === 'articles' || base.resourceType === 'shop'){
                base.handle = hand;
            }else{
                hand = ( base.resourceType === 'orders') ? 'name' : 'handle';
                hand = ( base.resourceType === 'customers' ) ? 'email' : hand;
                base.handle = this.$route.params.resource[hand];
            }
            if (this.resourceType === 'articles') {
                this.label = 'Articles';
                this.rtype = 'articles';
                this.base_id = this.resourceData.blog_id;
            } else if (this.resourceType === 'variants') {
                this.label = 'Variants';
                this.rtype = 'variants';
                this.base_id = this.resourceData.product_id;
            } else if (this.resourceType === 'smart_collections' || this.resourceType === 'custom_collections') {
                this.label = 'Collections';
                this.rtype = 'smart_collections';
            } else if (this.resourceType === 'shop') {
                this.label = 'Dashboard';
                this.rtype = 'dashboard';
            } else {
                this.label = this.resourceType.split('_').map(_.capitalize).join('');
                this.rtype = this.resourceType;
            }
            base.$root.$on('get-syncdata', function (rtype) {
                if (base.resourceType === rtype && base.$refs.syncalert) {
                    base.$refs.syncalert.getIsSynced();
                }
            });
        },
        watch: {
            file_error: {
                immediate: true,
                deep: true,
                handler: function () {

                }
            },
            form: {
                immediate: true,
                deep: true,
                handler: function () {
                    if (this.watch === true) {
                        if (this.form.length) {
                            if (_.isEqual(this.temp_prisine, JSON.stringify(this.form))) {
                                let contextualSaveBar = helper.contextualSaveBar();
                                contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                                this.errors = [];
                            } else {
                                let base = this;
                                let result = _.find(this.changeIndexes, function (o) {
                                    return o === base.current_chng_index;
                                });
                                if (result) {
                                } else {
                                    this.changeIndexes.push(this.current_chng_index);
                                }
                                this.createContextualSaveBar();
                            }
                        }
                    }
                }
            }
        },
        filters: {
            format_string: function (str) {
                // return str;
                // let new_str = str.charAt(0).toUpperCase() + str.substring(1);
                return str.replace(/_/g, ' ');
            },
        }
    }
</script>

<style>
    .vue-dropzone > .dz-preview .dz-image {
        height: 20%;
    }

    .ql-container {
        height: 30% !important;
    }

    .vc-chrome {
        margin-top: 10px !important;
    }

    /*input.form-control.color-picker {*/
    /*    border: 1px solid #ada5a5;*/
    /*    border-radius: 2px !important;*/
    /*    padding: .5rem 1.2rem;*/
    /*}*/
</style>
<style lang="scss">
    .color-picker-component {
        .current-color {
            display: inline-block;
            width: 16px;
            height: 16px;
            background-color: #000;
            cursor: pointer;
        }

        .vue-color__chrome {
            position: absolute;
            left: 0;
            top: calc(100% + 10px);
            z-index: 100;
        }
    }

    .tooltip {
        display: block !important;
        z-index: 10000;

        .tooltip-inner {
            background: black;
            color: white;
            border-radius: 16px;
            padding: 5px 10px 4px;
        }

        .tooltip-arrow {
            width: 0;
            height: 0;
            border-style: solid;
            position: absolute;
            margin: 5px;
            border-color: black;
            z-index: 1;
        }

        &[x-placement^="top"] {
            margin-bottom: 5px;

            .tooltip-arrow {
                border-width: 5px 5px 0 5px;
                border-left-color: transparent !important;
                border-right-color: transparent !important;
                border-bottom-color: transparent !important;
                bottom: -5px;
                left: calc(50% - 5px);
                margin-top: 0;
                margin-bottom: 0;
            }
        }

        &[x-placement^="bottom"] {
            margin-top: 5px;

            .tooltip-arrow {
                border-width: 0 5px 5px 5px;
                border-left-color: transparent !important;
                border-right-color: transparent !important;
                border-top-color: transparent !important;
                top: -5px;
                left: calc(50% - 5px);
                margin-top: 0;
                margin-bottom: 0;
            }
        }

        &[x-placement^="right"] {
            margin-left: 5px;

            .tooltip-arrow {
                border-width: 5px 5px 5px 0;
                border-left-color: transparent !important;
                border-top-color: transparent !important;
                border-bottom-color: transparent !important;
                left: -5px;
                top: calc(50% - 5px);
                margin-left: 0;
                margin-right: 0;
            }
        }

        &[x-placement^="left"] {
            margin-right: 5px;

            .tooltip-arrow {
                border-width: 5px 0 5px 5px;
                border-top-color: transparent !important;
                border-right-color: transparent !important;
                border-bottom-color: transparent !important;
                right: -5px;
                top: calc(50% - 5px);
                margin-left: 0;
                margin-right: 0;
            }
        }

        &[aria-hidden='true'] {
            visibility: hidden;
            opacity: 0;
            transition: opacity .15s, visibility .15s;
        }

        &[aria-hidden='false'] {
            visibility: visible;
            opacity: 1;
            transition: opacity .15s;
        }
    }
</style>
<style>
    .error {
        color: #f90000 !important;
        top: 40px;
        position: absolute;
    }

    .prw-file1 {
        position: absolute;
        top: -40px;
        left: 20%;
    }
</style>
