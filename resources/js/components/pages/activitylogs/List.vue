<template>
    <div class="">
        <back-arrow :name=rtype :label=label :resourceType=rtype></back-arrow>

        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header">
                    <h1 class="Polaris-Heading">Activity Log</h1>
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
                            <tr v-if="activityList.length > 0">
                                <th class="text-left" >
                                    <h3><span class="Polaris-TextStyle--variationStrong">Module</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Status</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Created at</span></h3>
                                </th>
                                <th class="text-left">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Action</span></h3>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="Polaris-ResourceItem" v-if="activityList.length > 0" v-for="(list, index) in activityList" :key="index">
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0 text-capitalize">{{list.module | format_string}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{list.status}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{list.created_at}}</p>
                                    </div>
                                </td>
                                <td class="csv-list">
                                    <div class="d-flex align-center">
                                        <button type="button" class="Polaris-Button" v-bind:class="{'Polaris-Button--disabled' : ( list.status === 'Complete' || list.status === 'Failed' )}" style="padding: .5rem 1rem;" ><span
                                        class="Polaris-Button__Content" @click="stopJob(list.id, list.job_id)">Stop Process
<!--                                            <span class="Polaris-Button__Text">-->
<!--                                            <i class="ion ion-md-trash" style="font-size: 2rem;"></i>-->
<!--                                            </span>-->
                                        </span>
                                    </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="noData">
                                <td colspan="4" class="text-center">
                                    <svg style="fill: #c3cfd8;width: 80px;margin-bottom: 5px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M8 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9.707 4.293l-4.82-4.82C13.585 10.493 14 9.296 14 8c0-3.313-2.687-6-6-6S2 4.687 2 8s2.687 6 6 6c1.296 0 2.492-.415 3.473-1.113l4.82 4.82c.195.195.45.293.707.293s.512-.098.707-.293c.39-.39.39-1.023 0-1.414z"></path></svg>
                                    <h2 class="Polaris-Heading">Could not find any activity</h2>
                                    <p class="Polaris-TextStyle--variationSubdued"></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <custom-paginate :prev_page="prev_page" :next_page="next_page" resourceType="activities" v-if="activityList.length > 0"></custom-paginate>
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
    import helper from "../../../helper";
    import {Button, ContextualSaveBar, Modal, Toast} from "@shopify/app-bridge/actions";

    export default {
        components: {
            BackArrow,
            ContentLoader,
            PageFooter,
            CustomPaginate
        },
        data() {
            return {
                activityList: [],
                label: 'Dashboard',
                rtype: 'dashboard',
                errors: [],
                temp_prisine: '',
                is_contentload: true,
                noData: false,
                prev_page: '',
                next_page: '',
            }
        },
        methods: {
            async getLogs(url) {
                let base = this;
                let method = 'get';
                await axios({
                    url: url,
                    method: method,
                }).then(function (res) {
                    let data = res.data.data.data;
                    if (data.length) {
                        base.activityList = data;
                        base.prev_page = res.data.data.prev;
                        base.next_page = res.data.data.next;
                    }else{
                        base.noData = true;
                    }
                }).catch(function (err) {
                    console.log(err);
                }).finally(res => {
                    base.is_contentload = false;
                });
            },
            async stopJob(id, job_id){
                let base = this;
                let method = 'get';
                let url = 'stopped-job?id='+ id + '&jid=' + job_id;
                const okButton = Button.create(shopify_app_bridge, {label: 'Ok'});
                const cancelButton = Button.create(shopify_app_bridge, {label: 'Cancel'});
                const modalOptions = {
                    title: 'Stopped Job!!',
                    message: 'Are you sure to stop this job?',
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
                    axios({
                        url: url,
                        method: method,
                    }).then(function (res) {
                        console.log(res);
                        helper.showToast('Job stopped successfully.');
                    }).catch(function (err) {
                        console.log(err);
                    }).finally(res => {
                        base.is_contentload = false;
                    });
                });

                cancelButton.subscribe(Button.Action.CLICK, data => {
                    myModal.dispatch(Modal.Action.CLOSE);
                });

            },
        },
        created() {
            let base = this;
            this.getLogs('get-activitylogs?page=1');
            base.$root.$on('get-data', function(rtype, cursor){
                if( 'activities' === rtype ){
                    base.getLogs(cursor);
                }
            });
        },
        filters: {
            format_string: function (str) {
                return str.replace(/_/g, ' ');
            },
        }
    }
</script>

<style>
    .csv-list{
        cursor:default;
    }
</style>

