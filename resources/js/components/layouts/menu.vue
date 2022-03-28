<template>
    <div>
        <div>
            <ul role="tablist" class="Polaris-Tabs">
                <li  role="presentation" class="Polaris-Tabs__TabContainer" v-for="item in items">
                    <router-link :class="activeClass(item.name)" :to="{name :item.name}" tag="button" class="Polaris-Tabs__Tab ">
                        <span class="Polaris-Tabs__Title">{{ item.title }}</span>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                items: []
            }
        },
        created() {
            this.$router.options.routes.forEach(route => {
                if(route.meta.ignoreInMenu == 0){
                    this.items.push({
                        title: route.meta.title,
                        name: route.name,
                        path: route.path,
                        dafaultActiveClass: route.meta.dafaultActiveClass,
                    })
                }
            })
        },
        methods: {
            activeClass: function (...names) {
                for (let name of names) {
                    if (name == this.$route.name)
                        return 'Polaris-Tabs__Tab--selected';
                }
            }
        }
    };
</script>
