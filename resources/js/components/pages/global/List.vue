<template>
    <div class="">
        <back-arrow :name=rtype :label=label :resourceType=rtype></back-arrow>
        <alert ref="syncalert" :resourceType="resourceType"></alert>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header">
                    <div class="Polaris-Stack Polaris-Stack--alignmentBaseline">
                        <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                            <h2 class="Polaris-Heading text-capitalize">{{resourceType | format_string}} Global Metafields</h2>
                        </div>

                        <div class="d-flex">
                            <button v-if="is_syncing" type="button" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}"
                                         class="Polaris-Button Polaris-Button--primary">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Add Metafield</span>
                                        </span>
                            </button>

                            <router-link v-else :to="{name :'globalmetafield', query : {id : ''}, params: { resourceType : resourceType, back: 'globalisting' }}" tag="button" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}"
                                         class="Polaris-Button Polaris-Button--primary">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Add Metafield</span>
                                        </span>
                            </router-link>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="Polaris-Card__Section">
                    <div class="metafield-list">
                        <content-loader :width="300" :height="200" primaryColor="#e0e4e8" class="Polaris-Page__Content" v-if="is_contentload">
                            <!--            first section-->
                            <rect x="10" y="10" rx="3" ry="3" width="110" height="10"/>
                            <rect x="125" y="10" rx="3" ry="3" width="50" height="10"/>
                            <rect x="185" y="10" rx="3" ry="3" width="60" height="10"/>
                            <rect x="0" y="30" rx="3" ry="3" width="250" height="1"/>

                            <!--            second section-->
                            <rect x="10" y="40" rx="3" ry="3" width="110" height="10"/>
                            <rect x="125" y="40" rx="3" ry="3" width="50" height="10"/>
                            <rect x="185" y="40" rx="3" ry="3" width="60" height="10"/>
                            <rect x="0" y="60" rx="3" ry="3" width="250" height="1"/>

                            <!--            third section-->
                            <rect x="10" y="70" rx="3" ry="3" width="110" height="10"/>
                            <rect x="125" y="70" rx="3" ry="3" width="50" height="10"/>
                            <rect x="185" y="70" rx="3" ry="3" width="60" height="10"/>
                            <rect x="0" y="90" rx="3" ry="3" width="250" height="1"/>
                            <!--            fourth section-->
                            <rect x="10" y="100" rx="3" ry="3" width="110" height="10"/>
                            <rect x="125" y="100" rx="3" ry="3" width="50" height="10"/>
                            <rect x="185" y="100" rx="3" ry="3" width="60" height="10"/>
                            <rect x="0" y="120" rx="3" ry="3" width="250" height="1"/>
                            <!--            fifth section-->
                            <rect x="10" y="130" rx="3" ry="3" width="110" height="10"/>
                            <rect x="125" y="130" rx="3" ry="3" width="50" height="10"/>
                            <rect x="185" y="130" rx="3" ry="3" width="60" height="10"/>
                            <rect x="0" y="150" rx="3" ry="3" width="250" height="1"/>

                        </content-loader>
                        <table class="w-100">
                            <thead>
                            <tr v-if="metafieldList.length > 0">
                                <th class="text-left" >
                                    <h3><span class="Polaris-TextStyle--variationStrong">Namespace</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Key</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Type</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Label</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Add in</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Action</span></h3>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="Polaris-ResourceItem" v-if="metafieldList.length > 0" v-for="(list, index) in metafieldList" :key="index">
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{list.namespace}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{list.key}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0 text-capitalize">{{list.type | format_string}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{list.label}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0 text-capitalize">{{list.add_in}} {{ resourceType, list.add_in | format_string_length}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="" >
                                        <button type="button" class="Polaris-Button" v-if="is_syncing" style="padding: .5rem 1rem;" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}"><span
                                            class="Polaris-Button__Content"><span class="Polaris-Button__Text">
                                            <i class="ion ion-md-create" style="font-size: 2rem;"></i></span></span>
                                        </button>
                                        <router-link v-else :to="{name :'globalmetafield', query : {id : list.id}, params: { resourceType : resourceType, back: 'globalisting' }}" tag="button" class="Polaris-Button" style="padding: .5rem 1rem;" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">
                                            <i class="ion ion-md-create" style="font-size: 2rem;"></i></span></span>
                                        </router-link>

                                        <button type="button" class="Polaris-Button" v-bind:class="{'Polaris-Button--disabled banned-sync' : ( is_syncing)}" @click = "!is_syncing ? removeMetaField(list.id) : ''"
                                                style="padding: .5rem 1rem;" ><span
                                            class="Polaris-Button__Content"><span class="Polaris-Button__Text">
                                            <i class="ion ion-md-trash" style="font-size: 2rem;"></i></span></span>
                                        </button>

                                        <span><img src="/static_upload/process.gif" style="height: 40px;padding-top: 2rem;" v-if="list.status === 'in_progress'"/></span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="4" class="text-center">
                                    <svg style="fill: #c3cfd8;width: 80px;margin-bottom: 5px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M8 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9.707 4.293l-4.82-4.82C13.585 10.493 14 9.296 14 8c0-3.313-2.687-6-6-6S2 4.687 2 8s2.687 6 6 6c1.296 0 2.492-.415 3.473-1.113l4.82 4.82c.195.195.45.293.707.293s.512-.098.707-.293c.39-.39.39-1.023 0-1.414z"></path></svg>
                                    <h2 class="Polaris-Heading">Could not find any global metafield</h2>
                                    <p class="Polaris-TextStyle--variationSubdued"></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <custom-paginate :prev_page="prev_page" :next_page="next_page" resourceType="global_metafield" v-if="metafieldList.length > 0"></custom-paginate>
                </div>
            </div>
        </div>
        <page-footer></page-footer>
    </div>
</template>

<script>
    import BackArrow from '../../shopify/BackArrow';
    import {ContentLoader} from 'vue-content-loader';
    import PageFooter from "../../shopify/PageFooter";
    import CustomPaginate from "../../shopify/CustomPaginate";
    import {Button, Modal} from "@shopify/app-bridge/actions";
    import helper from "../../../helper";
    import Alert from '../../shopify/Alert';

    export default {
        components: {
            BackArrow,
            ContentLoader,
            PageFooter,
            CustomPaginate,
            Alert
        },
        data() {
            return {
                metafieldList: [],
                label: '',
                rtype: '',
                errors: [],
                temp_prisine: '',
                is_contentload: true,
                prev_page: '',
                next_page: '',
                resourceType: '',
                is_syncing: '',
            }
        },
        methods: {
            async getMetafields(url) {
                let base = this;
                let method = 'get';

                url = url + '&rtype=' + base.resourceType;
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    let data = res.data.data.data;
                    if (data.length) {
                        base.metafieldList = data;
                        base.prev_page = res.data.data.prev;
                        base.next_page = res.data.data.next;
                    }
                }).catch(function (err) {
                    console.log(err);
                }).finally(res => {
                    base.is_contentload = false;
                });
            },
            async removeMetaField(id) {
                let base = this;
                const okButton = Button.create(shopify_app_bridge, {label: 'Delete', style: Button.Style.Danger});
                const cancelButton = Button.create(shopify_app_bridge, {label: 'Cancel'});
                const modalOptions = {
                    title: 'Delete Metafield!!',
                    message: 'Are you sure to delete this global metafield?',
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

                    let endPoint = '/delete-globalmetafield?id=' + id + '&rt=' + base.resourceType;
                    axios.get(endPoint)
                        .then(res => {
                            helper.successToast(res.data.data);
                            base.getMetafields('get-globalmetafields?page=1');
                        })
                        .catch(err => {
                            console.log(err);
                        });
                });
            },
        },
        created() {
            let base = this;
            this.resourceType = this.$route.params.resourceType;
            this.is_syncing = this.$route.params.is_syncing;

            this.getMetafields('get-globalmetafields?page=1');
            if (this.resourceType === 'articles') {
                this.label = 'Blogs';
                this.rtype = 'blogs';
            } else if (this.resourceType === 'variants') {
                this.label = 'Products';
                this.rtype = 'products';
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

            base.$root.$on('get-data', function(rtype, cursor){
                if( 'global_metafield' === rtype ){
                    base.getMetafields(cursor);
                }
            });
        },
        filters: {
            format_string: function (str) {
                return str.replace(/_/g, ' ');
            },
            format_string_length: function (str, add_in) {
               if(typeof add_in === 'number'){
                   str = str.replace(/_/g, ' ');
                   return ( add_in === 1 ) ? str.substring(0, str.length - 1) : str;
               }else{
                   return str.replace(/_/g, ' ');
               }
            }
        }
    }
</script>
