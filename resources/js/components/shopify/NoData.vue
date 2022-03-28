<template>
    <div>
<div class="Polaris-EmptyState Polaris-EmptyState--withinPage">
    <div class="Polaris-EmptyState__Section">
        <div class="Polaris-EmptyState__DetailsContainer">
            <div class="Polaris-EmptyState__Details">
                <div class="Polaris-TextContainer">
                    <h2 class="Polaris-Heading text-capitalize" style="font-size: 3rem;">{{resourceType | format_resourceType}}</h2>
                    <p class="Polaris-DisplayText Polaris-DisplayText--sizeMedium" style="font-size: 1.6rem;">Manage your metafields</p>
                    <div class="Polaris-EmptyState__Content">
                        <p></p>
                    </div>
                </div>
                <div class="Polaris-EmptyState__Actions">
                    <div class="Polaris-Stack Polaris-Stack--alignmentCenter">
                        <div class="Polaris-Stack__Item">
                            <router-link :to="{name :'metafields', params: { resourceType : resourceType, shop : shop  }}"
                                tag="button" class="Polaris-Button Polaris-Button--primary Polaris-Button--sizeLarge">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Text">{{label}}</span>
                                </span>
                            </router-link>
                        </div>
                    </div>
                </div>
                <import-export ref="importexport" :resourceType="resourceType" @update="closeModel" v-if="resourceType === 'shop'"></import-export>
            </div>
        </div>
        <div class="Polaris-EmptyState__ImageContainer"><img src="/static_upload/no metafileds.png" role="presentation" alt="" class="Polaris-EmptyState__Image"></div>
    </div>
</div>
    </div>
</template>

<script>
    import ImportExport from "./ImportExport";
    export default {
        name:"NoData",
        props: ['label', 'resourceType', 'shop', 'base_id'],
        components:{
          ImportExport,
        },
        methods:{
            closeModel() {
                // this.showModel = false;
                this.$refs.importexport.closeModel(false, 'refresh');
            },
        },
        filters:{
            format_resourceType: function(str){
                return str.replace('_', ' ');
            }
        }
    }
</script>
