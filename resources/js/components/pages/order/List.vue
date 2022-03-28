<template>
    <div class="">
        <back-arrow name="dashboard" label="Dashboard"></back-arrow>
        <alert ref="syncalert" :resourceType="resourceType"></alert>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header Polaris-Card__Header-meta">
                    <div class="Polaris-Stack Polaris-Stack--alignmentBaseline">
                        <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                            <h2 class="Polaris-Heading">Orders</h2>
                        </div>
                        <import-export :resourceType="resourceType" :lbl1="`Order Configurations`" :shop="shop" :checkedIDs="checkedIDs" :is_data="orderList.length > 0"></import-export>
                    </div>
                </div>

                <div class="Polaris-Card__Section">
                    <div class="d-flex justify-content-between{">
                        <div class="Polaris-Connected w-100">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary products-searchbar">
                                <div class="Polaris-TextField Polaris-TextField--hasValue">
                                    <div class="Polaris-TextField__Prefix"><span
                                        class="Polaris-Icon Polaris-Icon--hasBackdrop" aria-label=""><svg
                                        class="Polaris-Icon__Svg" viewBox="0 0 20 20"><path
                                        d="M8 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9.707 4.293l-4.82-4.82C13.585 10.493 14 9.296 14 8c0-3.313-2.687-6-6-6S2 4.687 2 8s2.687 6 6 6c1.296 0 2.492-.415 3.473-1.113l4.82 4.82c.195.195.45.293.707.293s.512-.098.707-.293c.39-.39.39-1.023 0-1.414z"
                                        fill="#95a7b7" fill-rule="evenodd"></path></svg></span></div>
                                    <input type="search" v-model="search" name="search_keyword"
                                           class="Polaris-TextField__Input browse_buy_product_search"
                                           aria-invalid="false"
                                           placeholder="Search title" autocomplete="off">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="Polaris-Button Polaris-Button--primary ml-16" v-on:click="getOrders(1)"><span
                            class="Polaris-Button__Content"  v-model="search"><span
                            class="Polaris-Button__Text">Search</span></span></button>
                    </div>
                    <div class="Polaris-Labelled__HelpText mb-15">Type at least 3 characters</div>

                    <div class="metafield-list">
                        <table class="w-100">
                            <thead>
                            <tr v-if="orderList.length > 0">
                                <th class="text-left" style="width: 18px;" id="multi-del" colspan="0">
                                    <multi-delete ref="multidelete" :resourceType="resourceType" :list="orderList" :next_page="next_page" :checkedIDs="checkedIDs" @update="updateChecked"></multi-delete>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Orders</span></h3>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Email</span></h3>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Payment Status</span></h3>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <bullet-list-loader :width="300" :height="200" primaryColor="#f3f3f3" v-if="is_load">
                            </bullet-list-loader>
                            <tr v-else-if="orderList.length > 0"
                                v-for="(order, index) in orderList" :key="index" class="Polaris-ResourceItem">
                                <td style="width: 18px;">
                                    <label class="Polaris-Choice" :for="`chkbox`+ index">
                                        <span class="Polaris-Choice__Control">
                                            <span class="Polaris-Checkbox">
                                                <input type="checkbox" class="Polaris-Checkbox__Input" aria-invalid="false" role="checkbox" aria-checked="false" value="" :id="`chkbox`+ index" :checked="order.is_checked" @click="checkSingle(order.id,index)">
                                                <span class="Polaris-Checkbox__Backdrop"></span>
                                                <span class="Polaris-Checkbox__Icon">
                                                    <span class="Polaris-Icon">
                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
              <path d="M8.315 13.859l-3.182-3.417a.506.506 0 0 1 0-.684l.643-.683a.437.437 0 0 1 .642 0l2.22 2.393 4.942-5.327a.437.437 0 0 1 .643 0l.643.684a.504.504 0 0 1 0 .683l-5.91 6.35a.437.437 0 0 1-.642 0"></path>
            </svg>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </td>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: order, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{order.name}}</p>
                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: order, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{order.email}}</p>
                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: order, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{order.payment_status}}</p>
                                    </div>
                                </router-link>
                            </tr>
                            <tr v-else>
                                <td colspan="4" class="text-center">
                                    <svg style="fill: #c3cfd8;width: 80px;margin-bottom: 5px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M8 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9.707 4.293l-4.82-4.82C13.585 10.493 14 9.296 14 8c0-3.313-2.687-6-6-6S2 4.687 2 8s2.687 6 6 6c1.296 0 2.492-.415 3.473-1.113l4.82 4.82c.195.195.45.293.707.293s.512-.098.707-.293c.39-.39.39-1.023 0-1.414z"></path></svg>
                                    <h2 class="Polaris-Heading">Could not find any Order</h2>
                                    <p class="Polaris-TextStyle--variationSubdued">Try changing the filters or search term</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <custom-paginate v-if="orderList.length > 0" :prev_page="prev_page" :next_page="next_page" :resourceType="resourceType"></custom-paginate>
                </div>
            </div>
        </div>
        <page-footer></page-footer>
    </div>

</template>

<script>
    import BackArrow from '../../shopify/BackArrow';
    import Alert from '../../shopify/Alert';
    import ImportExport from "../../shopify/ImportExport";
    import {BulletListLoader} from 'vue-content-loader';
    import MultiDelete from "../../shopify/MultiDelete";
    import CustomPaginate from "../../shopify/CustomPaginate";
    import PageFooter from "../../shopify/PageFooter";
    /*import abandoned from '@shopify/polaris-icons/images/abandoned-cart_major_monotone.svg';*/

    export default {
        components: {
            BackArrow,
            Alert,
            ImportExport,
            BulletListLoader,
            MultiDelete,
            CustomPaginate,
            PageFooter
        },
        data() {
            return {
                search: '',
                orderList: [],
                is_load: true,
                routes: [],
                next_page: '',
                prev_page: '',
                resourceType: '',
                shop: '',
                checkedIDs: [],
                isMultiCheck: false,
            }
        },
        methods: {
            async getOrders(cursor, page) {
                this.orderList = [];
                this.is_load = true;
                let base = this;

                let endPoint = ( cursor === 1 ) ? '/get-orders?s=' + base.search : '/get-orders?' + page + '=' + cursor + '&s=' + base.search;

                await axios.get(endPoint)
                    .then(res => {
                        var data = res.data.data;
                        base.orderList = data.order;
                        base.next_page = (data.pageInfo.hasNextPage) ?  data.next : '';
                        base.prev_page = (data.pageInfo.hasPreviousPage) ?  data.prev : '';
                        base.isMultiCheck = false;
                        base.checkedIDs = [];
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                        base.is_load = false;
                    });
            },
            checkSingle(id, index){
                let base = this;
                var i = base.checkedIDs.indexOf(id);
                if(i !== -1){
                    base.checkedIDs.splice(i, 1);
                } else{
                    base.checkedIDs.push(id);
                }
                base.isMultiCheck = false;
                base.orderList[index].is_checked = !base.orderList[index].is_checked;
                base.$refs.multidelete.singleChecked(base.checkedIDs);
            },
            updateChecked(type){
                let base = this;
                base.checkedIDs = [];

                if( base.isMultiCheck ){
                    base.orderList.forEach(function(element){
                        element.is_checked = type;
                    });
                }else{
                    base.orderList.forEach(function(element){
                        base.checkedIDs.push(element.id);
                        element.is_checked = type;
                    });
                }
                base.isMultiCheck = !base.isMultiCheck;
            }
        },
        computed: {
            VueContentLoading() {
                if (this.is_load) {
                    return VueContentLoading;
                }
            }
        },
        created() {
            let base = this;
            base.resourceType = base.$route.params.resourceType;
            base.getOrders(1);

            base.$root.$on('get-data', function(rtype, cursor, page){
                if( base.resourceType === rtype ) {
                    base.getOrders(cursor, page);
                }
            });
            base.$root.$on('get-syncdata', function(rtype){
                if( base.resourceType === rtype && base.$refs.syncalert ){
                    base.$refs.syncalert.getIsSynced();
                }
            });
        }
    };
</script>
