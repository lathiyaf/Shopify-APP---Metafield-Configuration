<template>
    <div class="mt-15" v-if="is_syncing">
        <div class="Polaris-Banner Polaris-Banner--statusCritical Polaris-Banner--withinPage" tabindex="0" role="alert" aria-live="polite" aria-labelledby="Banner16Heading" aria-describedby="Banner16Content">
            <div class="Polaris-Banner__Ribbon"><span class="Polaris-Icon Polaris-Icon--colorRedDark Polaris-Icon--isColored Polaris-Icon--hasBackdrop"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
          <circle fill="currentColor" cx="10" cy="10" r="9"></circle>
          <path d="M2 10c0-1.846.635-3.543 1.688-4.897l11.209 11.209A7.954 7.954 0 0 1 10 18c-4.411 0-8-3.589-8-8m14.312 4.897L5.103 3.688A7.954 7.954 0 0 1 10 2c4.411 0 8 3.589 8 8a7.952 7.952 0 0 1-1.688 4.897M0 10c0 5.514 4.486 10 10 10s10-4.486 10-10S15.514 0 10 0 0 4.486 0 10"></path>
        </svg></span></div>
            <div>
                <div class="Polaris-Banner__Heading" id="Banner16Heading">
                    <p class="Polaris-Heading">{{sync_des}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name:"Alert",
        props:[ 'resourceType'],
        data(){
            return{
                sync_des: '',
                is_syncing: false,
            }
        },
        methods:{
            async getIsSynced() {
                let base = this;
                await axios.get('/get-issynced?rtype=' + base.resourceType)
                    .then(res => {
                        let data = res.data.data.synced_data;
                        if (!data.is_syncing) {
                            base.is_syncing = false;
                        } else {
                            base.is_syncing = true;
                            base.sync_des = data.data;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
        },
        created() {
            this.getIsSynced();
        }
    }
</script>
