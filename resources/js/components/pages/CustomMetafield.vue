<template>
    <div>
        <div id="myCustomModel" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" @click.prevent="closeModel('no_refresh')">&times;</span>
                    <h1>Create Metafield</h1>
                </div>
                <div class="model_body">
                    <form ref=form enctype="multipart/form-data" id="metafieldconfig-form" class="mb-0">
                        <div class="mb-10">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label"><label id="labelnamespace" class="Polaris-Label__Text">Namespce
                                    *</label>
                                </div>
                            </div>
                            <div class="Polaris-TextField">
                                <input id="namespace" class="Polaris-TextField__Input" v-model="csForm.namespace"
                                       type="text" placeholder="Enter String">
                                <div class="Polaris-TextField__Backdrop"></div>
                            </div>
                            <div class="Polaris-Labelled__Error" v-if="errors['data.namespace']"><div id="PolarisTextField4Errornamespace" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors['data.namespace'][0]}}</div></div>
                        </div>

                        <div class="mb-10">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label"><label id="labelkey" class="Polaris-Label__Text">Key
                                    *</label>
                                </div>
                            </div>
                            <div class="Polaris-TextField">
                                <input id="key" class="Polaris-TextField__Input" v-model="csForm.key" type="text"
                                       placeholder="Enter String">
                                <div class="Polaris-TextField__Backdrop"></div>
                            </div>
                            <div class="Polaris-Labelled__Error" v-if="errors['data.key']"><div id="PolarisTextFieldErrorkey" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors['data.key'][0]}}</div></div>
                        </div>
                        <div class="mb-10">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label"><label id="label" class="Polaris-Label__Text">label
                                    </label>
                                </div>
                            </div>
                            <div class="Polaris-TextField">
                                <input id="label" class="Polaris-TextField__Input" v-model="csForm.label" type="text"
                                       placeholder="Enter String">
                                <div class="Polaris-TextField__Backdrop"></div>
                            </div>
                            <div><span v-if="errors['data.label']"
                                       class="custom-error">{{errors['data.label'][0]}}</span></div>
                        </div>
                        <div class="mb-10">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label"><label id="type" class="Polaris-Label__Text">Type
                                    *</label>
                                </div>
                            </div>
                            <div class="Polaris-Select" @change="updateForm($event, 'selectType')">
                                <select class="Polaris-Select__Input"
                                        aria-invalid="false">
                                    <option v-for="type in types" :value="type.value">{{type.name}}</option>
                                </select>
                                <div class="Polaris-Select__Content" aria-hidden="true"><span
                                    class="Polaris-Select__SelectedOption">{{ csForm.typen }}</span><span
                                    class="Polaris-Select__Icon"><span class="Polaris-Icon"><svg
                                    viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false"
                                    aria-hidden="true">
                                                        <path d="M13 8l-3-3-3 3h6zm-.1 4L10 14.9 7.1 12h5.8z"
                                                              fill-rule="evenodd"></path>
                                                        </svg></span></span></div>
                                <div class="Polaris-Select__Backdrop"></div>
                            </div>
                        </div>

                        <div class="Polaris-TextField" v-if="csForm.typev === 'string'">
                            <input id="PolarisTextcustomField1" class="Polaris-TextField__Input" v-model="csForm.value"
                                   type="text" placeholder="Enter String">
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>

                        <div class="Polaris-TextField" v-else-if="csForm.typev === 'number'">
                            <input id="PolarisTextcustomField2" class="Polaris-TextField__Input" type="number"
                                   v-model="csForm.value" placeholder="Enter Number">
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>

                        <div class="Polaris-TextField" v-else-if="csForm.typev === 'phone'">
                            <input id="PolarisTextcustomField3" class="Polaris-TextField__Input" type="tel"
                                   v-model="csForm.value" placeholder="Enter Phone number">
                            <div class="Polaris-TextField__Backdrop"></div>

                        </div>

                        <div class="Polaris-TextField" v-else-if="csForm.typev === 'email'">
                            <input id="PolarisTextcustomField4" class="Polaris-TextField__Input" type="email"
                                   v-model="csForm.value" placeholder="Enter Email">
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>

                        <div class="Polaris-TextField" v-else-if="csForm.typev === 'url'">
                            <input id="PolarisTextcustomField5" class="Polaris-TextField__Input" type="url"
                                   v-model="csForm.value" placeholder="Enter url">
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>

                        <div class="Polaris-TextField " v-else-if="csForm.typev === 'file'">
                            <div class="prw-file"></div>
                            <input id="fileField" name="file" class="Polaris-TextField__Input PolarisFileField"
                                   type="file" v-on:change="updateForm($event, 'file')" placeholder="Select File" @input="checkFileSize()">
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>

                        <div class="Polaris-TextField" v-else-if="csForm.typev === 'json'">
                            <textarea rows="7" v-model="csForm.value" placeholder='e.x: {"name": "John", "age": 31, "city": "New York"}'
                                      class="Polaris-TextField__Input" id="PolarisTextField7"></textarea>
                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>

                            <vue-dropzone
                                ref="vueDropzone"
                                id="dropzone2"
                                v-else-if="csForm.typev === 'image' || csForm.typev === 'multiple_image'"
                                @vdropzone-success="updateForm($event, csForm.typev)"
                                @vdropzone-removed-file="updateForm($event, 'remove_' + csForm.typev)"
                                @vdropzone-complete="updateForm($event, 'added_'+ csForm.typev)"
                                :options="dropzoneOptions">
                            </vue-dropzone>

                            <vue-editor v-model="csForm.value" v-else-if="csForm.typev === 'rich_text_box'"></vue-editor>

                            <div class="input-group color-picker-component" v-else-if="csForm.typev === 'color_picker'">
                            <input type="text"
                                   class="form-control"
                                   title="Color Picker"
                                   v-model="csForm.value"
                                   @blur="displayPicker = false"
                            />
                            <span class="input-group-addon color-picker-container">
                                    <span class="current-color"
                                          :style="'background-color: ' + csForm.value  "
                                          @click="is_display = !is_display"
                                    ></span>
                                     <chrome-picker v-model="csForm.value"
                                                    @input="updateForm($event,'color')"
                                                    v-if="is_display"></chrome-picker>
                                </span>
                        </div>

                            <datepicker v-model="date" :format=formatDate @input="updateForm($event, 'date')"
                                    v-else-if="csForm.typev === 'date_picker'"></datepicker>

                            <div class="Polaris-Labelled__Error" v-if="errors['data.value']"><div id="PolarisTextFieldError" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{errors['data.value'][0]}}</div></div>

                            <div class="Polaris-Labelled__Error" v-if="file_error"><div id="PolarisTextFieldErrorFile" class="Polaris-InlineError"><div class="Polaris-InlineError__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm-1-8h2V6H9v4zm0 4h2v-2H9v2z" fill-rule="evenodd"></path></svg></span></div>{{file_error}}</div></div>
                        <div class="mt-10">
                            <button v-bind:class="{'Polaris-Button--disabled' : ( is_saving  )}"  type="button" class="Polaris-Button Polaris-Button--primary" id="saveModel"
                                    v-on:click="checkError()"><span class="Polaris-Button__Content"><span
                                class="Polaris-Button__Text">Save</span></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
    import {VueEditor} from "vue2-editor";
    import {Chrome} from 'vue-color';
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment-timezone';

    var modal = "";
    export default {
        components: {
            vueDropzone: vue2Dropzone,
            VueEditor,
            'chrome-picker': Chrome,
            Datepicker
        },
        props: ['showModel', 'dropzone1', 'dropzone2', 'ownerId', 'handle', 'resourceType'],
        name: "Model",
        data() {
            return {
                csForm: '',
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
                dropzoneOptions: this.dropzone2,
                errors: [],
                file_error: '',
                is_display: false,
                date: new Date(),
                is_saving: false,
            }
        },
        methods: {
            init() {
                this.csForm = {
                    id: '',
                    namespace: '',
                    key: '',
                    typen: 'String',
                    typev: 'string',
                    label: '',
                    value: ''
                }
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
                    }
                }else{
                    base.file_error = '';
                }
                console.log(base.file_error);
            },
            async getCustomNamespace()   {
                let base = this;
                let url = 'get-customnamespace?api=1';
                let method = 'get';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    base.csForm.namespace = res.data.data;
                }).catch(function (err) {
                    console.log(err);
                });
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
                        let type = base.csForm.typev;

                        if (type === 'image' || type === 'multiple_image') {
                            base.$refs.vueDropzone.removeAllFiles();

                            if( type === 'image' ){
                                base.$refs.vueDropzone.setOption('maxFiles', 100);
                            }else{
                                base.$refs.vueDropzone.setOption('maxFiles', 1);
                            }
                        }
                        base.csForm.typen = base.types[e.target.selectedIndex].name;
                        base.csForm.typev = base.types[e.target.selectedIndex].value;

                        base.csForm.value = (base.csForm.typev === 'multiple_image') ? [] : '';
                        if( base.csForm.typev === 'image' ){
                            base.dropzoneOptions = this.dropzone1;
                        }
                        break;
                    case 'file':
                        base.csForm.value = e.target.files[0];
                        break;
                    case 'color':
                        base.csForm.value = e.hex;
                        break;
                    case 'date':
                        base.csForm.value = e.getTime();
                        break;
                    case 'image':
                        base.csForm.value = e;
                        break;
                    case 'multiple_image':
                        base.csForm.value.push(e);
                        break;
                    case 'remove_image':
                        base.csForm.value = '';
                        base.$refs.vueDropzone.enable();
                        break;
                    case 'remove_multiple_image':
                        var remove_uuid = e.upload.uuid;
                        if (base.csForm.value !== '' && base.csForm.typev === 'multiple_image') {
                            base.csForm.value.forEach(function (val, i) {
                                if (val.upload.uuid === remove_uuid) {
                                    base.csForm.value.splice(i, 1);
                                    return;
                                }
                            });
                        }
                        break;
                    case 'added_image':
                        base.$refs.vueDropzone.disable();
                        break;
                }
                // console.log(base.csForm.value);
            },
            closeModel(){
                this.errors = [];
                this.file_error = '';
                this.csForm = {
                    id: '',
                    namespace: this.namespace,
                    key: '',
                    typen: 'String',
                    typev: 'string',
                    label: '',
                    value: ''
                }
                this.$emit('update', false, 'no_refresh');
            },
            async checkError() {
                let base = this;
                base.is_saving = true;

                let value = '';
                if( base.csForm.typev === 'file' ){
                    value = base.csForm.value;
                    if( value !== '' ){
                        base.csForm.value = 'file';
                    }
                }
                await axios({
                    url: 'metafield',
                    data: {
                        'type': 'custom',
                        'isSubmit': false,
                        'data': base.csForm,
                        'resourceType': base.resourceType,
                    },
                    method: 'POST',
                }).then(function (res) {
                    if (res.data.data === true) {
                        base.errors = [];
                        if( base.file_error === '' ){
                            if( base.csForm.typev === 'file' ){
                                base.csForm.value = value;
                            }
                            base.sendForm(true);
                        }
                    }
                })
                    .catch(function (err) {
                        base.is_saving = false;
                        base.errors = err.response.data;
                    });
            },
            async sendForm(isSubmit) {
                let base = this;
                let url = 'metafield';

                base.saveClick = 1;
                this.errors = [];
                let formData = new FormData(this.$refs.form);

                if(  base.csForm.typev === "file" || base.csForm.typev === 'image' ){
                    formData.append('file', base.csForm.value);
                }else if(  base.csForm.typev === "multiple_image" ){
                    base.csForm.value.forEach( function( image, index ){
                        formData.append('file[' + index + ']', image);
                    });
                }

                formData.append("handle", base.handle);
                formData.append("owner_id", base.ownerId);
                formData.append('isSubmit', isSubmit);
                formData.append('data', JSON.stringify(base.csForm));
                formData.append('resourceType', base.resourceType);
                formData.append('is_custom', true);

                try{
                    let {data} = await axios.post(url, formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                '_method':'post',
                            }
                        });
                    base.errors = [];
                    base.csForm = {
                        id: '',
                        namespace: '',
                        key: '',
                        typen: 'String',
                        typev: 'string',
                        label: '',
                        value: ''
                    };
                    base.is_saving = false;
                    this.$emit('update', false, 'refresh');
                }catch ({response}) {
                    base.is_saving = false;
                    console.log(response);
                }
                return true;
            },
        },
        created() {
            this.init();
            this.getCustomNamespace();
        },
        watch: {
            showModel(newVal, oldVal) {
                if (newVal == true) {
                    modal.style.display = "block";
                } else {
                    modal.style.display = "none";
                }
            },
        },
        mounted() {
            modal = document.getElementById("myCustomModel");
        }
    }
</script>

