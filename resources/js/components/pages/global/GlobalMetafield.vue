<template>
    <div class="">
        <back-arrow :name=back :label=label :resourceType=rtype></back-arrow>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card" style="overflow:visible;">
                <div class="Polaris-Card__Header">
                    <div class="Polaris-Page__Title Polaris-TextContainer--spacingTight">
                        <h1 class="Polaris-Heading text-capitalize">{{ resourceType | format_string}} Global
                            Metafield</h1>
                        <p>This is your global metafield configuration for each object type.</p>
                    </div>
                </div>
                <div class="Polaris-Card__Section">
                    <div class="custom-box">
                        <content-loader :width="300" :height="100" primaryColor="#e0e4e8" class="Polaris-Page__Content"
                                        v-if="is_contentload">
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

                        <form ref=form enctype="multipart/form-data" id="metafieldconfig-form" class="mb-0"
                              v-if="!is_contentload">
                            <div class="Polaris-Stack Polaris-Stack--vertical" v-if="glform.id === ''">
                                <div class="Polaris-Stack__Item mt-1">
                                    <div>
                                        <label class="Polaris-Choice" for="disabled">
                                            <span class="Polaris-Choice__Control">
                                                <span class="Polaris-RadioButton">
                                                    <input id="disabled" name="add_in" type="radio"
                                                           v-model="glform.add_in" class="Polaris-RadioButton__Input"
                                                           aria-describedby="disabledHelpText" value="all" checked=""
                                                           :selected="(glform.add_in === 'all') ? 'selected' : '' "
                                                           @change="openModel('all')">
                                                    <span class="Polaris-RadioButton__Backdrop"></span>
                                                    <span class="Polaris-RadioButton__Icon"></span>
                                                </span>
                                            </span>
                                            <span class="Polaris-Choice__Label text-capitalize">All {{resourceType | format_string}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="Polaris-Stack__Item mt-1" style="margin-bottom: 10px;">
                                    <div>
                                        <label class="Polaris-Choice" for="optional">
                                            <span class="Polaris-Choice__Control">
                                                <span class="Polaris-RadioButton">
                                                    <input id="optional" name="add_in" type="radio"
                                                           v-model="glform.add_in" class="Polaris-RadioButton__Input"
                                                           aria-describedby="optionalHelpText" value="selected"
                                                           :selected="(glform.add_in === 'selected') ? 'selected' : '' ">
                                                    <span class="Polaris-RadioButton__Backdrop"></span>
                                                    <span class="Polaris-RadioButton__Icon"></span>
                                                </span>
                                            </span>
                                            <span class="Polaris-Choice__Label text-capitalize"><a style="cursor:pointer;color:blue;text-decoration:none;" @click="openModel('selected')">Select </a>{{ resourceType | format_string }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div v-if="glform.add_in === 'selected'">
                                <span class="" style="display: flex;align-items: flex-start;flex-shrink: 0;" v-for="(item, index ) in glform.resourceList">
                                <ul id="modal-header15" class="Polaris-Modal-Header__Title" style="margin-bottom: 1px;">
                                    <li class="Polaris-DisplayText">{{item.title}}</li>
                                </ul>
                                    <button class="Polaris-Modal-CloseButton" style="padding:0px;" aria-label="Close" @click.prevent="removeResource(index)" v-if="glform.id === ''">
                                        <span class="Polaris-Icon Polaris-Icon--colorInkLighter Polaris-Icon--isColored">
                                            <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                    <path d="M11.414 10l6.293-6.293a.999.999 0 1 0-1.414-1.414L10 8.586 3.707 2.293a.999.999 0 1 0-1.414 1.414L8.586 10l-6.293 6.293a.999.999 0 1 0 1.414 1.414L10 11.414l6.293 6.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z" fill-rule="evenodd">
                                                    </path>
                                                </svg>
                                        </span>
                                    </button>
                                </span>
                            </div>

                            <div class="Polaris-FormLayout" style="padding: 1.5rem 0rem 0px 0px;">
                                <div class="Polaris-FormLayout__Item d-xs-block d-flex">
                                    <div class="custom-row w-100 metafield-item">
                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label id="namespaceLabel"
                                                                                  for="Namespace"
                                                                                  class="Polaris-Label__Text">Namespace
                                                    *</label>

                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input id="Namespace" :disabled="glform.id !== ''"
                                                                                  name="namespace"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity"
                                                                                  step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false"
                                                                                  aria-multiline="false"
                                                                                  value="" v-model="glform.namespace">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <div class="Polaris-Labelled__Error" v-if="errors['data.namespace']">
                                                <div id="PolarisTextField4Error" class="Polaris-InlineError">
                                                    <div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg
                                                        viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false"
                                                        aria-hidden="true"><path
                                                        d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z"
                                                        fill-rule="evenodd"></path></svg></span></div>
                                                    {{errors['data.namespace'][0]}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label id="KeyLabel" for="Key"
                                                                                  class="Polaris-Label__Text">key
                                                    *</label>
                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input id="Key" :disabled="glform.id !== ''"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity"
                                                                                  step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false"
                                                                                  aria-multiline="false"
                                                                                  value="" v-model="glform.key"
                                            >
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <div class="Polaris-Labelled__Error" v-if="errors['data.key']">
                                                <div id="PolarisTextField4Errorkey" class="Polaris-InlineError">
                                                    <div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg
                                                        viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false"
                                                        aria-hidden="true"><path
                                                        d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z"
                                                        fill-rule="evenodd"></path></svg></span></div>
                                                    {{errors['data.key'][0]}}
                                                </div>
                                            </div>
                                        </div>


                                        <div class="w-25">
                                            <div class="Polaris-Labelled__LabelWrapper">
                                                <div class="Polaris-Label"><label id="PolarisSelectLabel"
                                                                                  for="PolarisSelect"
                                                                                  class="Polaris-Label__Text">Type
                                                    *</label>
                                                </div>
                                            </div>

                                            <div class="Polaris-TextField" v-if="glform.id !== ''"><input id="" disabled
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity"
                                                                                  step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false"
                                                                                  aria-multiline="false"
                                                                                  value="" v-model="glform.typev"
                                            >
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <div class="Polaris-Select" v-else v-model="glform.typev"
                                                 @change="updateForm($event, 'selectType')">
                                                <select id="PolarisSelect" class="Polaris-Select__Input"
                                                        aria-invalid="false" :disabled="glform.id !== ''">
                                                    <option v-for="type in types" :value="type.value"
                                                            :selected="(type.value === glform.typev) ? 'selected' : '' ">
                                                        {{type.name}}
                                                    </option>
                                                </select>
                                                <div class="Polaris-Select__Content" aria-hidden="true"><span
                                                    class="Polaris-Select__SelectedOption text-capitalize">{{glform.typev | format_string}}</span><span
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
                                                <div class="Polaris-Label"><label id="configureLabel"
                                                                                  for="configure"
                                                                                  class="Polaris-Label__Text">Label </label>
                                                </div>
                                            </div>
                                            <div class="Polaris-TextField"><input id="configure"
                                                                                  class="Polaris-TextField__Input"
                                                                                  min="-Infinity" max="Infinity"
                                                                                  step="1"
                                                                                  type="text"
                                                                                  aria-describedby="PolarisTextField2HelpText"
                                                                                  aria-invalid="false"
                                                                                  aria-multiline="false"
                                                                                  value="" v-model="glform.label">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                            <span v-if="errors['data.label']" class="config-error">{{errors['data.label'][0]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 metafield-item" style="padding: 1.5rem 1.5rem 0 0;">
                                <div class="Polaris-TextField" v-if="glform.typev === 'string'">
                                    <input id="PolarisTextcustomField1" class="Polaris-TextField__Input"
                                           v-model="glform.value"
                                           type="text" placeholder="Enter String">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>

                                <div class="Polaris-TextField" v-else-if="glform.typev === 'number'">
                                    <input id="PolarisTextcustomField2" class="Polaris-TextField__Input" type="number"
                                           v-model="glform.value" placeholder="Enter Number">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>

                                <div class="Polaris-TextField" v-else-if="glform.typev === 'phone'">
                                    <input id="PolarisTextcustomField3" class="Polaris-TextField__Input" type="tel"
                                           v-model="glform.value" placeholder="Enter Phone number">
                                    <div class="Polaris-TextField__Backdrop"></div>

                                </div>

                                <div class="Polaris-TextField" v-else-if="glform.typev === 'email'">
                                    <input id="PolarisTextcustomField4" class="Polaris-TextField__Input" type="email"
                                           v-model="glform.value" placeholder="Enter Email">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>

                                <div class="Polaris-TextField" v-else-if="glform.typev === 'url'">
                                    <input id="PolarisTextcustomField5" class="Polaris-TextField__Input" type="url"
                                           v-model="glform.value" placeholder="Enter url">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>

                                <div class="Polaris-TextField " v-else-if="glform.typev === 'file'">
                                    <div class="prw-file"></div>
                                    <input id="fileField" name="file" class="Polaris-TextField__Input PolarisFileField"
                                           type="file" v-on:change="updateForm($event, 'file')"
                                           placeholder="Select File" @input="checkFileSize()">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>

                                <div class="Polaris-TextField" v-else-if="glform.typev === 'json'">
                                <textarea rows="7" v-model="glform.value"
                                          placeholder='e.x: {"name": "John", "age": 31, "city": "New York"}'
                                          class="Polaris-TextField__Input" id="PolarisTextField7"></textarea>
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>

                                <vue-dropzone
                                    ref="vueDropzone"
                                    id="dropzone2"
                                    v-else-if="glform.typev === 'image' || glform.typev === 'multiple_image'"
                                    @vdropzone-success="updateForm($event, glform.typev)"
                                    @vdropzone-removed-file="updateForm($event, 'remove_' + glform.typev)"
                                    @vdropzone-complete="updateForm($event, 'added_'+ glform.typev)"
                                    @vdropzone-mounted="renderImage(glform.typev)"
                                    :options="dropzoneOptions">
                                </vue-dropzone>

                                <vue-editor v-model="glform.value"
                                            v-else-if="glform.typev === 'rich_text_box'"></vue-editor>

                                <div class="input-group color-picker-component"
                                     v-else-if="glform.typev === 'color_picker'">
                                    <input type="text"
                                           class="form-control"
                                           title="Color Picker"
                                           v-model="glform.value"
                                           @blur="displayPicker = false"
                                    />
                                    <span class="input-group-addon color-picker-container">
                                        <span class="current-color"
                                              :style="'background-color: ' + glform.value  "
                                              @click="is_display = !is_display"
                                        ></span>
                                         <chrome-picker v-model="glform.value"
                                                        @input="updateForm($event,'color')"
                                                        v-if="is_display"></chrome-picker>
                                    </span>
                                </div>

                                <datepicker v-model="date" :format=formatDate @input="updateForm($event, 'date')"
                                            v-else-if="glform.typev === 'date_picker'"></datepicker>
                            </div>
                            <div class="Polaris-Labelled__Error" v-if="errors['data.value']">
                                <div id="PolarisError" class="Polaris-InlineError">
                                    <div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg
                                        viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false"
                                        aria-hidden="true"><path
                                        d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z"
                                        fill-rule="evenodd"></path></svg></span></div>
                                    {{errors['data.value'][0]}}
                                </div>
                            </div>
                            <div class="Polaris-Labelled__Error" v-if="file_error"><div id="PolarisTextFieldErrorFile" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{file_error}}</div></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <resource-model ref="resourceModel" :show-model.sync="showModel" :resourceList="glform.resourceList"
                        @update="closeModel"></resource-model>
        <page-footer></page-footer>
    </div>
</template>

<script>
    import BackArrow from '../../shopify/BackArrow';
    import {Button, Modal, Toast} from '@shopify/app-bridge/actions';
    import {ContextualSaveBar} from '@shopify/app-bridge/actions';
    import {ResourcePicker} from '@shopify/app-bridge/actions';
    import ResourceModel from "../../shopify/ResourceModel";
    import {Redirect} from '@shopify/app-bridge/actions';
    import helper from '../../../helper';
    import draggable from 'vuedraggable';
    import PageFooter from "../../shopify/PageFooter";
    import {ContentLoader} from 'vue-content-loader';
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
    import {VueEditor} from "vue2-editor";
    import {Chrome} from 'vue-color';
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment-timezone';

    export default {
        components: {
            draggable,
            BackArrow,
            PageFooter,
            ContentLoader,
            vueDropzone: vue2Dropzone,
            VueEditor,
            'chrome-picker': Chrome,
            Datepicker,
            ResourceModel
        },
        data() {
            return {
                app: shopify_app_bridge,
                glform: [],
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
                temp_prisine: '',
                namespace: '',
                label: '',
                rtype: '',
                back: '',
                is_load: false,
                is_check: true,
                watch: false,
                is_contentload: false,
                saveBar: false,
                dropzoneOptions: {
                    url: window.shopify_app_bridge.localOrigin + '/api/store-images',
                    maxFilesize: 4, //MB
                    acceptedFiles: 'image/*',
                    thumbnailWidth: 150,
                    addRemoveLinks: true,
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Upload image",
                },
                is_display: false,
                date: new Date(),
                showModel: false,
                file_error: '',
            }
        },
        methods: {
            init() {
                this.glform = {
                    id: '',
                    namespace: this.namespace,
                    key: '',
                    typev: 'string',
                    label: '',
                    value: '',
                    add_in: 'all',
                    resourceList: [],
                    resourceType: this.resourceType
                }

                this.temp_prisine = JSON.stringify(this.glform);
                this.watch = true;
            },
            formatDate(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            updateForm(e, action) {
                let base = this;
                switch (action) {
                    case 'selectType':
                        base.errors = [];
                        base.file_error = '';
                        let type = base.glform.typev;

                        if (type === 'image' || type === 'multiple_image') {
                            base.$refs.vueDropzone.removeAllFiles();

                            if (type === 'image') {
                                base.$refs.vueDropzone.setOption('maxFiles', 100);
                            } else {
                                base.$refs.vueDropzone.setOption('maxFiles', 1);
                            }
                        }
                        base.glform.typev = base.types[e.target.selectedIndex].value;

                        base.glform.value = (base.glform.typev === 'multiple_image') ? [] : '';
                        if (base.glform.typev === 'image') {
                            // base.dropzoneOptions = base.dropzoneOptions;
                        }
                        break;
                    case 'file':
                        base.glform.value = e.target.files[0];
                        break;
                    case 'color':
                        base.glform.value = e.hex;
                        break;
                    case 'date':
                        base.glform.value = e.getTime();
                        console.log(base.glform.value);
                        break;
                    case 'image':
                        base.glform.value = e;
                        break;
                    case 'multiple_image':
                        base.glform.value.push(e);
                        break;
                    case 'remove_image':
                        base.glform.value = '';
                        base.$refs.vueDropzone.enable();
                        break;
                    case 'remove_multiple_image':
                        if (e.upload) {
                            var remove_uuid = e.upload.uuid;

                            if (base.glform.value.length > 0 && (Array.isArray(base.glform.value))) {
                                base.glform.value.forEach(function (value, i) {
                                    if (typeof value !== 'string') {
                                        if (value.upload.uuid === remove_uuid) {
                                            base.glform.value.splice(i, 1);
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
                            if ((base.glform.value.length > 0) && (Array.isArray(base.glform.value))) {
                                base.glform.value.forEach(function (value, i) {
                                    let str = value;
                                    if( value.indexOf('?') > 0 ){
                                        str = value.substring(0, value.indexOf('?'));
                                    }

                                    let v = str.substring(str.lastIndexOf('/') + 1);
                                    if (v === name) {
                                        base.glform.value.splice(i, 1);
                                        return;
                                    }
                                });
                            }
                        }
                        break;
                    case 'added_image':
                        base.$refs.vueDropzone.disable();
                        break;
                }
                // console.log(base.glform.value);
            },
            openModel(type) {
                if (type === 'all') {
                    this.glform.resourceList = [];
                } else {
                    if (this.resourceType === 'products' || this.resourceType === 'smart_collections' || this.resourceType === 'custom_collections') {
                        this.openPicker(this.resourceType);
                    } else {
                        this.showModel = true;
                        this.$refs.resourceModel.getResourceData(this.resourceType, this.glform.resourceList);
                    }
                }
            },
            openPicker(resource_name) {
                let base = this;
                let rt = (resource_name === 'products') ? 'product' : 'collection';
                let rts = (resource_name === 'products') ? 'Product' : 'Collection';

                var initialQuery = '';
                if (resource_name === 'smart_collections') {
                    initialQuery = "collection_type:smart";
                } else if (resource_name === 'custom_collections') {
                    initialQuery = "collection_type:custom";
                    initialQuery = "collection_type:custom";
                }

                var option = '';
                if (resource_name === 'products') {
                    option = "showVariants: false";

                } else {
                    option = "initialQuery: initialQuery";
                }
                const picker = ResourcePicker.create(shopify_app_bridge, {
                    resourceType: rt,
                    options: {
                        showVariants: false,
                        initialQuery: initialQuery
                    }
                });

                picker.dispatch(ResourcePicker.Action.OPEN);
                picker.subscribe(ResourcePicker.Action.SELECT, ({selection}) => {
                    selection.forEach(function (value, index) {
                        let resource = {};

                        let resource_id = (value.id.length > 0) ? value.id.replace("gid://shopify/" + rts + "/", "") : '';
                        let handle = (value.handle.length > 0 ) ? value.handle : '';
                        let title = ( value.title.length > 0 ) ? value.title : '';

                        resource = {
                            resource_id: resource_id,
                            handle: handle,
                            title: title,
                        }
                        if (base.glform.resourceList.length > 0) {
                            let result = _.find(base.glform.resourceList, function (o) {
                                return o.resource_id === resource_id;
                            });


                            if (typeof result == "undefined") {
                                base.glform.resourceList.push(resource);
                            }
                        } else {
                            base.glform.resourceList.push(resource);
                        }
                    });
                });
            },
            closeModel(message, data) {
                this.showModel = message;
                this.glform.resourceList = data;
            },
            renderImage(type) {
                let base = this;
                if (type === 'image') {
                    let str = base.glform.value;
                    if (str.length > 0) {
                        base.showImage(str, type, 0);
                    }
                } else if (type === 'multiple_image') {
                    let images = base.glform.value;
                    images.forEach(function (image, i) {
                        base.showImage(image, type, i);
                    });
                }
            },
            showImage(str, dropZone, i) {
                let base = this;
                let new_str = str;
                if(str.indexOf('?') > 0){
                    new_str = str.substring(0, str.indexOf('?'));
                }
                var extension = str.substring(new_str.lastIndexOf('.') + 1);
                var name = str.substring(new_str.lastIndexOf('/') + 1);
                var file = {size: 150, name: name, type: "image/" + extension};
                var url = str;

                base.$refs['vueDropzone'].manuallyAddFile(file, url);
                // ( dropZone == 'image' ) ? base.$refs[`${nm}`][0].manuallyAddFile(file, url) : base.$refs[`${nm}`][0].manuallyAddFile(file, url);
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
                    base.file_error = '';
                    if( base.glform.id === '' ){
                        base.init();
                    }else{
                        base.getMetafield(base.glform.id);
                    }
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                });
                contextualSaveBar.subscribe(ContextualSaveBar.Action.SAVE, function () {
                    if (base.is_check) {
                        base.checkError();
                    }
                });
            },
            checkFileSize(){
                let base = this;
                const fi = document.getElementById('fileField');
                base.errors['data.value'] = '';
                if (fi.files.length > 0) {
                    for (let i = 0; i <= fi.files.length - 1; i++) {
                        const fsize = fi.files.item(i).size;
                        const file = Math.round((fsize / 1024));
                        // The size of the file.
                        if (file >= 4096) {
                            base.file_error = 'File too Big, please select a file less than 4mb';
                        }else{
                            base.file_error = '';
                        }
                        base.form
                    }
                }else{
                    base.file_error = '';
                }
                console.log(base.file_error);
            },
            removeResource(index){
                this.glform.resourceList.splice(index, 1);
            },
            async checkError() {

                console.log(this.glform.add_in);
                let base = this;
                if( base.glform.add_in === 'selected' && base.glform.resourceList.length <= 0 ){
                    base.hideSaveBar();
                    helper.errorToast('select atleast 1 ' + base.resourceType.substring(0, base.resourceType.length - 1));
                }else {
                    if( base.glform.id === '' ){
                        base.sendCheckError();
                    }else {
                        const okButton = Button.create(shopify_app_bridge, {label: 'Save'});
                        const cancelButton = Button.create(shopify_app_bridge, {label: 'Cancel'});
                        const modalOptions = {
                            title: 'Metafield saving warning!!',
                            message: 'After saving, the previous changes from particular resource will overwrite with new value.',
                            footer: {
                                buttons: {
                                    primary: okButton,
                                    secondary: [cancelButton],
                                },
                            },
                        };
                        const myModal = Modal.create(shopify_app_bridge, modalOptions);
                        myModal.dispatch(Modal.Action.OPEN);
                        okButton.subscribe(Button.Action.CLICK, () => {
                            myModal.dispatch(Modal.Action.CLOSE);
                            base.sendCheckError();
                        });
                        cancelButton.subscribe(Button.Action.CLICK, () => {
                            myModal.dispatch(Modal.Action.CLOSE);
                            let contextualSaveBar = helper.contextualSaveBar();
                            contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                        });
                    }
                }
            },
            async sendCheckError(){
                let contextualSaveBar = helper.contextualSaveBar();
                contextualSaveBar.set({saveAction: {loading: true}, discardAction: {disabled: true}});
                let base = this;
                let value = '';
                if( base.glform.typev === 'file' ){
                    value = base.glform.value;
                    if( value !== '' ){
                        base.glform.value = 'file';
                    }
                }
                axios({
                    url: 'global-metafield',
                    data: {
                        'isSubmit': false,
                        'data': base.glform,
                        'resourceType': base.resourceType,
                    },
                    method: 'POST',
                }).then(function (res) {
                    if (res.data.data === true) {
                        base.errors = [];
                        if( base.file_error === '' ) {
                            base.is_load = true;
                            base.is_check = false;
                            if( base.glform.typev === 'file' ){
                                base.glform.value = value;
                            }
                            base.sendForm(true);
                        }else{
                            base.is_load = false;
                            base.is_check = true;
                            let contextualSaveBar = helper.contextualSaveBar();
                            contextualSaveBar.set({saveAction: {loading: false},discardAction: {disabled: false}});
                        }
                    }
                })
                    .catch(function (err) {
                        base.is_load = false;
                        base.is_check = true;
                        base.errors = err.response.data;
                        let contextualSaveBar = helper.contextualSaveBar();
                        contextualSaveBar.set({saveAction: {loading: false},discardAction: {disabled: false}});
                    });
            },
            hideSaveBar(){
                let contextualSaveBar = helper.contextualSaveBar();
                contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
            },
            async getMetafield(id){
                let base = this;
                base.is_load = false;
                base.scriptList = [];

                let endPoint = '/get-globalmetafield?id=' + id;

                await axios.get(endPoint)
                    .then(res => {
                        base.glform = res.data.data;

                        let v = '';
                        if (base.glform.typev === 'multiple_image') {
                            v = base.glform.value.split(',');
                        } else if (base.glform.typev === 'color_picker') {
                            v = base.glform.value;
                        } else if (base.glform.typev === 'date_picker') {
                            var timestamp = Number(base.glform.value);
                            var d = new Date(timestamp);
                            base.date = d;
                        } else {
                            v = base.glform.value;
                        }

                        base.glform.value = v;
                        base.glform.resourceType = base.resourceType;
                        base.temp_prisine = JSON.stringify(base.glform);
                        base.watch = true;
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                    });
            },
            async getCustomNamespace()   {
                let base = this;
                let url = 'get-customnamespace?api=1';
                let method = 'get';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    base.namespace = res.data.data;
                    base.init();
                }).catch(function (err) {
                    console.log(err);
                });
            },
            async sendForm(isSubmit) {
                let base = this;
                let url = 'global-metafield';

                this.errors = [];
                let formData = new FormData(this.$refs.form);

                if(  base.glform.typev === "file" || base.glform.typev === 'image' ){
                    formData.append('file', base.glform.value);
                    base.glform.value = base.glform.typev;
                }else if(  base.glform.typev === "multiple_image" ){
                    base.glform.value.forEach( function( image, index ){
                        formData.append('file[' + index + ']', image);
                    });
                    base.glform.value = base.glform.typev;
                }

                formData.append('isSubmit', isSubmit);
                formData.append('data', JSON.stringify(base.glform));
                formData.append('resourceType', base.resourceType);

                try{
                    let {data} = await axios.post(url, formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                '_method':'post',
                            }
                        });
                    let msg = data.data.msg;
                    let id = data.data.id;
                    helper.successToast(msg);
                    base.errors = [];
                    base.init();
                    base.is_load = false;
                    base.is_check = true;
                    let contextualSaveBar = helper.contextualSaveBar();
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                    base.$router.push({name: 'globalisting', query : {id : ''}, params: { resourceType : base.resourceType, back:  base.resourceType, is_syncing: true  }});
                }catch ({response}) {
                    console.log(response);
                    base.is_load = false;
                    base.is_check = true;
                    let contextualSaveBar = helper.contextualSaveBar();
                    contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
                }
                return true;
            },
        },
        created() {
            this.resourceType = this.$route.params.resourceType;
            let id = this.$route.query.id;
            if( id !== '' ){
                this.getMetafield(id);
            }else{
                this.getCustomNamespace();
            }

                if (this.resourceType === 'articles') {
                    this.label = 'Blogs';
                    this.rtype = 'blogs';
                } else if (this.resourceType === 'variants') {
                    this.label = 'Products';
                    this.rtype = 'products';
                } else if (this.resourceType === 'smart_collections' || this.resourceType === 'custom_collections') {
                    this.label = 'Collections';
                    this.rtype = 'smart_collections';
                } else {
                    this.label = this.resourceType.split('_').map(_.capitalize).join('');
                    this.rtype = this.resourceType;
                }
            this.back = (this.$route.params.back === 'globalisting' ) ? 'globalisting' : this.rtype;
            this.label = (this.$route.params.back === 'globalisting' ) ? 'Global Metafield' : this.label;
        },
        watch: {
            glform: {
                immediate: true,
                deep: true,
                handler: function () {
                    if (this.watch) {
                        if (this.temp_prisine === JSON.stringify(this.glform)) {
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
                if( typeof str != 'undefined'){
                    return str.replace('_', ' ');
                }
            },
        }
    };

</script>

<style>
    .config-error {
        color: #f90000 !important;
    }
</style>
