<template>
    <div class="Polaris-Page">
        <router-view></router-view>
    </div>
</template>

<script>
    import Menus from './menu';
    export default {
        components:{
            Menus,
        },
        data(){
            return{

            }
        },
    };
</script>
