import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

const routes = [
    {
        path:'/',
        component: require('../components/pages/Dashboard').default,
        name:'dashboard',
        meta: {
            title: 'Dashboard',
            description: '',
            ignoreInMenu: 1,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'dashboard',
            background_image:'./images/settings.svg',
        },
    },
    {
        path:'/metafieldvalue',
        component: require('../components/pages/MetafieldValue').default,
        name:'metafieldvalue1',
        meta: {
            title: 'Shop',
            description: 'Store and display unique information of your shop.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'shop',
            background_image:'./images/shop.svg',
        },
        props: true
    },
    {
        path:'/products/:resourceType',
        component: require('../components/pages/product/List').default,
        name:'products',
        meta: {
            title: 'Products',
            description: 'Store and display unique information of products and product variants.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'products',
            background_image:'./images/products.svg',
        },
        props: true
    },
    {
        path:'/variants/:resourceType',
        component: require('../components/pages/product/variant/List').default,
        name:'variants',
        meta: {
            title: 'Variants',
            description: 'Store and display unique information of products and product variants.',
            ignoreInMenu: 1,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'variants',
            background_image:'./images/settings.svg',
        },
        props: true
    },
    {
        path:'/collection/:resourceType',
        component: require('../components/pages/collection/List').default,
        name:'smart_collections',
        meta: {
            title: 'Collections',
            description: 'Store and display unique information of smart and custom collections.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'smart_collections',
            background_image:'./images/collections.svg',
        },
        props: true
    },
    {
        path:'/customer/:resourceType',
        component: require('../components/pages/customer/List').default,
        name:'customers',
        meta: {
            title: 'Customers',
            description: 'Store and display unique information of customers.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'customers',
            background_image:'./images/customers.svg',
        },
        props: true
    },
    {
        path:'/blog/:resourceType',
        component: require('../components/pages/blog/List').default,
        name:'blogs',
        meta: {
            title: 'Blogs',
            description: 'Store and display unique information of blogs and articles.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'blogs',
            background_image:'./images/blogs.svg',
        },
        props: true
    },
    {
        path:'/articles/:resourceType',
        component: require('../components/pages/blog/article/List').default,
        name:'articles',
        meta: {
            title: 'Articles',
            description: 'Store and display unique information on blogs and articles.',
            ignoreInMenu: 1,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'articles',
            background_image:'./images/settings.svg',
        },
        props: true
    },
    {
        path:'/orders/:resourceType',
        component: require('../components/pages/order/List').default,
        name:'orders',
        meta: {
            title: 'Orders',
            description: 'Store and display unique information on orders',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'orders',
            background_image:'./images/orders.svg',
        },
        props: true
    },
    {
        path:'/page/:resourceType',
        component: require('../components/pages/page/List').default,
        name:'pages',
        meta: {
            title: 'Pages',
            description: 'Store and display unique information of pages.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            resourceType: 'pages',
            background_image:'./images/pages.svg',
        },
        props: true
    },
    {
        path:'/metafields',
        component: require('../components/pages/Metafield').default,
        name:'metafields',
        meta: {
            title: 'Metafields',
            description: 'Create metafields configurations for shop,products,order etc...',
            ignoreInMenu: 1,
            displayRight: 0,
            dafaultActiveClass: '',
        background_image:'./images/settings.svg',
        }
    },
    {
        path:'/metafieldvalue',
        component: require('../components/pages/MetafieldValue').default,
        name:'metafieldvalue',
        meta: {
            title: 'metafieldvalue',
            description: 'Add metafields value for different resource type...',
            ignoreInMenu: 1,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/settings.svg',
        }
    },
    {
        path:'/configurations',
        component: require('../components/pages/configure/configuration').default,
        name:'configurations',
        meta: {
            title: 'Configurations',
            description: 'Create and edit your metafield configuration.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/configurations.svg',
        }
    },
    {
        path:'/settings',
        component: require('../components/pages/setting/setting').default,
        name:'settings',
        meta: {
            title: 'Settings',
            description: 'Create metafields setting.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/settings.svg',
        }
    },
    {
        path:'/exportcsvs',
        component: require('../components/pages/exportedcsv/List').default,
        name:'exportcsvs',
        meta: {
            title: 'Exported Metafield CSV',
            description: 'Listing of all exported csv file, you can show status and dowload it from here.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/exportcsvs.svg',
        }
    },
    {
        path:'/activity-logs',
        component: require('../components/pages/activitylogs/List').default,
        name:'activity-logs',
        meta: {
            title: 'Activity Logs',
            description: 'Background activity Logs of import, export and sync metafields.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/activity_logs.svg',
        }
    },
    {
        path:'/installation-guide',
        // component: require('../components/pages/exportedcsv/List').default,
        name:'installation-guide',
        meta: {
            title: 'Installation Guide',
            description: 'Get installation guide from here.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/installtion_guide.svg',
        }
    },
    {
        path:'/pricing',
        // component: require('../components/pages/exportedcsv/List').default,
        name:'pricing',
        meta: {
            title: 'Plan & Pricing',
            description: 'Plan details.',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/pricing.svg',
        }
    },
    {
        path:'/globalisting',
        component: require('../components/pages/global/List').default,
        name:'globalisting',
        meta: {
            title: 'Gloval List',
            description: 'Create and edit your metafield globally.',
            ignoreInMenu: 1,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/configurations.svg',
        }
    },
    {
        path:'/globalmetafield',
        component: require('../components/pages/global/GlobalMetafield').default,
        name:'globalmetafield',
        meta: {
            title: 'Global Metafield',
            description: 'Create and edit your global metafield.',
            ignoreInMenu: 1,
            displayRight: 0,
            dafaultActiveClass: '',
            background_image:'./images/configurations.svg',
        }
    },
];

const router = new VueRouter({
    mode:'history',
    routes,
    scrollBehavior() {
        return {
            x: 0,
            y: 0,
        };
    },

});

router.beforeEach((to, from, next) => {
    if( to.name === 'dashboard' ){
        document.body.classList.add("template-dashboard");
    }else{
        document.body.classList.remove("template-dashboard");
    }
    return next();
})
export default router;
