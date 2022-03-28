<template>
    <div class="">
        <back-arrow name="blogs" label="Blogs" resourceType="blogs" base_id="''"></back-arrow>
        <alert ref="syncalert" :resourceType="resourceType"></alert>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-Card__Header Polaris-Card__Header-meta">
                    <div class="Polaris-Stack Polaris-Stack--alignmentBaseline">
                        <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                            <h2 class="Polaris-Heading">Articles</h2>
                        </div>
                        <import-export :resourceType="resourceType" :lbl1="`Blog Configurations`" :lbl2="`Article Configurations`" :r2="resourceType" :shop="shop" :checkedIDs="checkedIDs" :id="blogId" :is_data="articleList.length > 0" ></import-export>
                    </div>
                </div>

                <div class="Polaris-Card__Section">
                    <div class="metafield-list">
                        <table class="w-100">
                            <thead>
                            <tr v-if="articleList.length > 0">
                                <th class="text-left" style="width: 18px;" id="multi-del" colspan="0">
                                    <multi-delete ref="multidelete" :resourceType="resourceType" :list="articleList" :next_page="next_page" :checkedIDs="checkedIDs" @update="updateChecked"></multi-delete>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Articles</span></h3>
                                </th>
                                <th class="text-left" v-if="checkedIDs.length <= 0">
                                    <h3><span class="Polaris-TextStyle--variationStrong">Author</span></h3>
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
                            <bullet-list-loader :width="300" :height="200" primaryColor="#f3f3f3" v-if="is_load">
                            </bullet-list-loader>
                            <tr v-else-if="articleList.length > 0"
                                v-for="(article, index) in articleList" :key="index" class="Polaris-ResourceItem">
                                <td style="width: 18px;">
                                    <label class="Polaris-Choice" :for="`chkbox`+ index">
                                        <span class="Polaris-Choice__Control">
                                            <span class="Polaris-Checkbox">
                                                <input type="checkbox" class="Polaris-Checkbox__Input" aria-invalid="false" role="checkbox" aria-checked="false" value="" :id="`chkbox`+ index" :checked="article.is_checked" @click="checkSingle(article.id,index)">
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
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: article, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                            <span class="mr-10 Polaris-Avatar Polaris-Avatar--styleSix Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage border-radius-0">
                                                <img :src=article.image alt="" role="presentation" class="Polaris-Avatar__Image border-radius-0"></span>
                                        <p class="mb-0">{{article.title}}</p>
                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: article, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{article.author}}</p>

                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: article, shop: shop  }}"
                                    tag="td" class="">
                                    <div class="d-flex align-center">
                                        <p class="mb-0">{{article.created_at}}</p>

                                    </div>
                                </router-link>
                                <router-link
                                    :to="{name :'metafieldvalue', params: { resourceType : resourceType, resource: article, shop: shop  }}"
                                    tag="td" class="">
                                    <div v-if="article.published_at === 'Hidden'" style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb;"><span class="Polaris-Badge Polaris-Badge--progressComplete"><span class="Polaris-Badge__Content">{{article.published_at}}</span></span></div>

                                    <div v-if="article.published_at === 'Visible'" style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb;"><span class="Polaris-Badge Polaris-Badge--statusSuccess"><span class="Polaris-Badge__Content">{{article.published_at}}</span></span></div>
                                </router-link>
                            </tr>
                            <tr v-else>
                                <td colspan="4" class="text-center">
                                    <svg style="fill: #c3cfd8;width: 80px;margin-bottom: 5px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M8 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9.707 4.293l-4.82-4.82C13.585 10.493 14 9.296 14 8c0-3.313-2.687-6-6-6S2 4.687 2 8s2.687 6 6 6c1.296 0 2.492-.415 3.473-1.113l4.82 4.82c.195.195.45.293.707.293s.512-.098.707-.293c.39-.39.39-1.023 0-1.414z"></path></svg>
                                    <h2 class="Polaris-Heading">Could not find any Article</h2>
                                    <p class="Polaris-TextStyle--variationSubdued">Try changing the filters or search term</p>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                    <custom-paginate v-if="articleList.length > 0" :prev_page="prev_page" :next_page="next_page" :resourceType="resourceType"></custom-paginate>
                </div>
            </div>
        </div>
        <page-footer></page-footer>
    </div>

</template>
<script>
    import BackArrow from '../../../shopify/BackArrow';
    import Alert from '../../../shopify/Alert';
    import ImportExport from "../../../shopify/ImportExport";
    import {BulletListLoader} from 'vue-content-loader';
    import MultiDelete from "../../../shopify/MultiDelete";
    import CustomPaginate from "../../../shopify/CustomPaginate";
    import PageFooter from "../../../shopify/PageFooter";
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
                blogId: '',
                articleList: [],
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
            async getArticles(page) {
                let base = this;
                base.articleList = [];
                base.is_load = true;
                let api = '/get-articles?blog_id=' + base.blogId;
                // let endPoint = ( page == 1 ) ? api + '&s=' + base.search : api + '&page=' + page + '&s=' + base.search ;
                let endPoint = ( page == 1 ) ? api : api + '&page=' + page ;

                await axios.get(endPoint)
                    .then(res => {
                        base.is_load = false;
                        var data = res.data.data;
                        base.articleList = data.article;
                        base.next_page = data.next;
                        base.prev_page =  data.prev ;
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
                base.articleList[index].is_checked = !base.articleList[index].is_checked;
                base.$refs.multidelete.singleChecked(base.checkedIDs);
            },
            updateChecked(type){
                let base = this;
                base.checkedIDs = [];

                if( base.isMultiCheck ){
                    base.articleList.forEach(function(element){
                        element.is_checked = type;
                    });
                }else{
                    base.articleList.forEach(function(element){
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
            base.blogId = base.$route.params.base_id;
            base.getArticles(1);
            console.log(this.$route);
            base.$root.$on('get-data', function(rtype, cursor){
                if( base.resourceType === rtype ) {
                    base.getArticles(cursor);
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
