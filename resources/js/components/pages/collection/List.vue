<template>
    <div class="">
        <back-arrow name="dashboard" label="Dashboard"></back-arrow>
        <alert ref="syncalert" :resourceType="resourceType"></alert>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header Polaris-Card__Header-meta">
                    <div class="Polaris-Stack Polaris-Stack--alignmentBaseline">
                        <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                            <h2 class="Polaris-Heading text-capitalize">{{ resourceType | format_resourceType }}</h2>
                        </div>
                        <import-export :resourceType="resourceType" :lbl1="`Smart Collection Configurations`"
                                       :lbl2="`Custom Collection Configurations`" :r2="`custom_collections`"
                                       :shop="shop" :checkedIDs="checkedIDs" :is_data="collectionList.length > 0"></import-export>
                    </div>
                </div>


                <div class="Polaris-Card__Section">
                    <div class="d-flex justify-content-between{">
                        <div class="Polaris-Connected w-100">
                            <div class="Polaris-ButtonGroup Polaris-ButtonGroup--segmented w-100">
                                <div class="Polaris-ButtonGroup__Item">
                                    <div>
                                        <button type="button" class="Polaris-Button" tabindex="0"
                                                aria-controls="Polarispopover2" aria-owns="Polarispopover2"
                                                aria-expanded="true"
                                                @click="showCollectionOption = !showCollectionOption"><span
                                            class="Polaris-Button__Content"><span
                                            class="Polaris-Button__Text">More actions</span><span
                                            class="Polaris-Button__Icon">
                                                    <div class="Polaris-Button__DisclosureIcon"><span
                                                        class="Polaris-Icon"><svg viewBox="0 0 20 20"
                                                                                  class="Polaris-Icon__Svg"
                                                                                  focusable="false" aria-hidden="true">
                                                              <path d="M5 8l5 5 5-5z" fill-rule="evenodd"></path>
                                                            </svg></span></div>
                                                      </span></span>
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="Polaris-PositionedOverlay Polaris-Popover__PopoverOverlay Polaris-Popover__PopoverOverlay--open"
                                    style="top: 37px; left: 0;" v-if="showCollectionOption">
                                    <div class="Polaris-Popover" style="margin: 0;" data-polaris-overlay="true">
                                        <div class="Polaris-Popover__FocusTracker" tabindex="0"></div>
                                        <div class="Polaris-Popover__Wrapper" style="padding: 1.6rem;">
                                            <div id="Polarispopover2" tabindex="-1" class="Polaris-Popover__Content">
                                                <div
                                                    class="Polaris-Popover__Pane Polaris-Scrollable Polaris-Scrollable--vertical"
                                                    data-polaris-scrollable="true">
                                                    <p class="mb-10">
                                                        Choose Collection:
                                                    </p>
                                                    <div class="Polaris-Select"><select id="PolarisSelect01"
                                                                                        aria-invalid="false"
                                                                                        class="Polaris-Select__Input"
                                                                                        v-model="collect"
                                                                                        @change="callCollection(collect)">
                                                        <option value="smart_collections"
                                                                :selected="('smart_collections' == collect) ? 'selected' : '' ">
                                                            Smart
                                                        </option>
                                                        <option value="custom_collections"
                                                                :selected="('custom_collections' == collect) ? 'selected' : '' ">
                                                            Custom
                                                        </option>
                                                    </select>
                                                        <div aria-hidden="true" class="Polaris-Select__Content"><span
                                                            class="Polaris-Select__SelectedOption">{{collectName}}</span><span
                                                            class="Polaris-Select__Icon"><span class="Polaris-Icon"><svg
                                                            viewBox="0 0 20 20" focusable="false" aria-hidden="true"
                                                            class="Polaris-Icon__Svg"><path
                                                            d="M13 8l-3-3-3 3h6zm-.1 4L10 14.9 7.1 12h5.8z"
                                                            fill-rule="evenodd"></path></svg></span></span></div>
                                                        <div class="Polaris-Select__Backdrop"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="Polaris-Popover__FocusTracker" tabindex="0"></div>
                                    </div>
                                </div>
                                <div class="Polaris-ButtonGroup__Item w-100">
                                    <div
                                        class="Polaris-Connected__Item Polaris-Connected__Item--primary products-searchbar">
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
                                            <div class="Polaris-TextField__Backdrop"
                                                 style="border-top-left-radius: 0;border-bottom-left-radius: 0"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="Polaris-Button Polaris-Button--primary ml-16" v-on:click="getCollections(1, resourceType)"><span
                            class="Polaris-Button__Content"
                            v-model="search"><span
                            class="Polaris-Button__Text">Search</span></span></button>
                    </div>
                    <div class="Polaris-Labelled__HelpText mb-15">Type at least 3 characters</div>

                    <div class="metafield-list">
                        <table class="w-100">
                            <thead>
                            <tr v-if="collectionList.length > 0">
                                <th class="text-left" style="width: 18px;" id="multi-del" colspan="0">
                                    <multi-delete ref="multidelete" :resourceType="resourceType"
                                                  :list="collectionList" :next_page="next_page"
                                                  :checkedIDs="checkedIDs"
                                                  @update="updateChecked"></multi-delete>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong text-capitalize">{{ resourceType | format_resourceType }}</span></h3>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Handle</span></h3>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Created At</span></h3>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Availability</span></h3>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <bullet-list-loader :width="300" :height="200" primaryColor="#f3f3f3"
                                                v-if="is_load">
                            </bullet-list-loader>
                            <tr v-else-if="collectionList.length > 0"
                                v-for="(collection, index) in collectionList" :key="index" class="Polaris-ResourceItem">
                                <td style="width: 18px;">
                                    <label class="Polaris-Choice" :for="`chkbox`+ index">
                                        <span class="Polaris-Choice__Control">
                                            <span class="Polaris-Checkbox">
                                                <input type="checkbox" class="Polaris-Checkbox__Input"
                                                       aria-invalid="false" role="checkbox" aria-checked="false"
                                                       value="" :id="`chkbox`+ index" :checked="collection.is_checked"
                                                       @click="checkSingle(collection.id,index)">
                                                <span class="Polaris-Checkbox__Backdrop"></span>
                                                <span class="Polaris-Checkbox__Icon">
                                                    <span class="Polaris-Icon">
                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg"
                                                             focusable="false" aria-hidden="true">
              <path
                  d="M8.315 13.859l-3.182-3.417a.506.506 0 0 1 0-.684l.643-.683a.437.437 0 0 1 .642 0l2.22 2.393 4.942-5.327a.437.437 0 0 1 .643 0l.643.684a.504.504 0 0 1 0 .683l-5.91 6.35a.437.437 0 0 1-.642 0"></path>
            </svg>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </td>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: collection, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                            <span
                                                class="mr-10 Polaris-Avatar Polaris-Avatar--styleSix Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage border-radius-0">
                                                <img :src=collection.image alt="" role="presentation"
                                                     class="Polaris-Avatar__Image border-radius-0"></span>
                                        <p class="mb-0">{{collection.title}}</p>
                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: collection, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{collection.handle}}</p>
                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: collection, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{collection.created_at}}</p>
                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: collection, shop: shop  }}"
                                    tag="td" class="">
                                    <div v-if="collection.published_at === 'Hidden'" style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb;"><span class="Polaris-Badge Polaris-Badge--progressComplete"><span class="Polaris-Badge__Content">{{collection.published_at}}</span></span></div>

                                    <div v-if="collection.published_at === 'Visible'" style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb;"><span class="Polaris-Badge Polaris-Badge--statusSuccess"><span class="Polaris-Badge__Content">{{collection.published_at}}</span></span></div>
                                </router-link>
                            </tr>
                            <tr v-else>
                                <td colspan="4" class="text-center">
                                    <svg style="fill: #c3cfd8;width: 80px;margin-bottom: 5px;"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M8 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9.707 4.293l-4.82-4.82C13.585 10.493 14 9.296 14 8c0-3.313-2.687-6-6-6S2 4.687 2 8s2.687 6 6 6c1.296 0 2.492-.415 3.473-1.113l4.82 4.82c.195.195.45.293.707.293s.512-.098.707-.293c.39-.39.39-1.023 0-1.414z"></path>
                                    </svg>
                                    <h2 class="Polaris-Heading">Could not find any collection</h2>
                                    <p class="Polaris-TextStyle--variationSubdued">Try changing the filters or
                                        search term</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <custom-paginate :prev_page="prev_page" :next_page="next_page" :resourceType="resourceType" v-if="collectionList.length > 0"></custom-paginate>
                </div>
            </div>
        </div>
        <page-footer></page-footer>
    </div>

</template>

<script>
    import BackArrow from '../../shopify/BackArrow';
    import ImportExport from "../../shopify/ImportExport";
    import {BulletListLoader} from 'vue-content-loader';
    import MultiDelete from "../../shopify/MultiDelete";
    import CustomPaginate from "../../shopify/CustomPaginate";
    import Alert from '../../shopify/Alert';
    import PageFooter from "../../shopify/PageFooter";
    /*import abandoned from '@shopify/polaris-icons/images/abandoned-cart_major_monotone.svg';*/

    export default {
        components: {
            BackArrow,
            Alert,
            BulletListLoader,
            ImportExport,
            MultiDelete,
            CustomPaginate,
            PageFooter
        },
        data() {
            return {
                search: '',
                collectionList: [],
                is_load: true,
                routes: [],
                next_page: '',
                prev_page: '',
                resourceType: '',
                shop: '',
                checkedIDs: [],
                isMultiCheck: false,
                showCollectionOption: false,
                collect: 'smart_collections',
                collectName: 'Smart',
            }
        },
        methods: {
            callCollection(c) {
                this.getCollections(1, c);
                this.showCollectionOption = false;
            },
            async getCollections(page, type) {
                this.isMultiCheck = false;
                this.checkedIDs = [];
                this.collectionList = [];
                this.is_load = true;
                let base = this;
                let api = '/get-collections?type=' + type;
                let endPoint = (page == 1) ? api + '&s=' + base.search : api + '&page=' + page + '&s=' + base.search;

                await axios.get(endPoint)
                    .then(res => {
                        var data = res.data.data;
                        base.collectionList = data.collection;
                        base.next_page = data.next;
                        base.prev_page = data.prev;
                        base.resourceType = type;
                        base.collectName = (type === 'smart_collections') ? 'Smart' : 'Custom';

                        this.$refs.multidelete.init();
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
            checkSingle(id, index) {
                let base = this;
                var i = base.checkedIDs.indexOf(id);
                if (i !== -1) {
                    base.checkedIDs.splice(i, 1);
                } else {
                    base.checkedIDs.push(id);
                }
                base.isMultiCheck = false;
                base.collectionList[index].is_checked = !base.collectionList[index].is_checked;
                base.$refs.multidelete.singleChecked(base.checkedIDs);
            },
            updateChecked(type) {
                let base = this;
                base.checkedIDs = [];

                if (base.isMultiCheck) {
                    base.collectionList.forEach(function (element) {
                        element.is_checked = type;
                    });
                } else {
                    base.collectionList.forEach(function (element, index) {
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
            base.getCollections(1, base.resourceType);
            base.$root.$on('get-data', function (rtype, cursor) {
                if( base.resourceType === rtype ) {
                    base.getCollections(cursor, base.resourceType);
                }
            });
            base.$root.$on('get-syncdata', function(rtype){
                if( base.resourceType === rtype && base.$refs.syncalert ){
                    base.$refs.syncalert.getIsSynced();
                }
            });
        },
        filters:{
            format_resourceType: function(str){
                return str.replace('_', ' ');
            }
        }
    };
</script>
