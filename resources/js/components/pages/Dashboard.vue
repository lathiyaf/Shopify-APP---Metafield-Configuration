<template>
    <div class="Polaris-Page__Content">
        <div>
            <div class="Polaris-Page__Title Polaris-TextContainer--spacingTight">
                <h1 class="Polaris-Heading">Metafields Configuration</h1>
                <p>The easiest way to manage store custom data.</p>
            </div>
            <div class="Polaris-Page__Content">
                <div class="custom-box">
                    <div class="custom-row">
                        <div class="area-settings-nav__item" v-for="item in items">
                            <a class="area-settings-nav__action" v-if="item.name === 'installation-guide'" :href=installation_path>
                                <div class="area-settings-nav__media">
                                    <span class="menu-icons menu-settings" :style="`background-image: url(`+item.background_image+`)`"></span>
                                </div>
                                <div>
                                    <p class="area-settings-nav__title">
                                        {{ item.title }} {{item.installation}}
                                    </p>
                                    <p class="area-settings-nav__description">
                                        {{ item.description}}
                                    </p>
                                </div>
                            </a>
                            <a class="area-settings-nav__action" v-else-if="item.name === 'pricing'" :href=pricing_path>
                                <div class="area-settings-nav__media">
                                    <span class="menu-icons menu-settings" :style="`background-image: url(`+item.background_image+`)`"></span>
                                </div>
                                <div>
                                    <p class="area-settings-nav__title">
                                        {{ item.title }} {{item.installation}}
                                    </p>
                                    <p class="area-settings-nav__description">
                                        {{ item.description}}
                                    </p>
                                </div>
                            </a>
                            <router-link :to="{name :item.name , params:{ resourceType: item.resourceType } }" tag="a" class="area-settings-nav__action" v-else>
                                <div class="area-settings-nav__media">
                                   <span class="menu-icons menu-settings" :style="`background-image: url(`+item.background_image+`)`"></span>
                                </div>
                                <div>
                                    <p class="area-settings-nav__title">
                                        {{ item.title }}
                                    </p>
                                    <p class="area-settings-nav__description">
                                        {{ item.description}}
                                    </p>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <page-footer></page-footer>
        </div>
    </div>
</template>
<script>
    import PageFooter from "../shopify/PageFooter";
    export default {
        components:{
            PageFooter,
        },
        data(){
            return{
                items: [],
                installation_path: '',
                pricing_path: '',
                types : [
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
                curr_page: '',
                is_contentload: true,
            }
        },
        created() {
            this.$router.options.routes.forEach(route => {
                if(route.meta.ignoreInMenu === 0){
                    this.items.push({
                        title: route.meta.title,
                        description: route.meta.description,
                        name: route.name,
                        path: route.path,
                        dafaultActiveClass: route.meta.dafaultActiveClass,
                        resourceType: route.meta.resourceType,
                        background_image:route.meta.background_image,
                    });
                }
            });

            this.installation_path = window.shopify_app_bridge.localOrigin + '/installation-instruction';
            this.pricing_path = window.shopify_app_bridge.localOrigin + '/plan-pricing';

            document.body.classList.add('template-dashboard');
        },
    };
</script>
