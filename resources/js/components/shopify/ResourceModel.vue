<template>
    <div class="Polaris-Modal-Dialog__Container popup-customer-group" id="script-tag-resource-picker" style="display: none;">
        <div>
            <div class="Polaris-Modal-Dialog__Modal" role="dialog" aria-labelledby="modal-header15" tabindex="-1" style="max-height: max-content;">
                <div class="Polaris-Modal-Header">
                    <div id="modal-header15" class="Polaris-Modal-Header__Title">
                        <h2 class="Polaris-DisplayText Polaris-DisplayText--sizeSmall text-capitalize">Add {{resource}}</h2>
                    </div><button class="Polaris-Modal-CloseButton" aria-label="Close"@click="closeModel()" ><span class="Polaris-Icon Polaris-Icon--colorInkLighter Polaris-Icon--isColored"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                        <path d="M11.414 10l6.293-6.293a.999.999 0 1 0-1.414-1.414L10 8.586 3.707 2.293a.999.999 0 1 0-1.414 1.414L8.586 10l-6.293 6.293a.999.999 0 1 0 1.414 1.414L10 11.414l6.293 6.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z" fill-rule="evenodd"></path>
                    </svg></span></button>
                </div>
                <form name="customer_broup" id="find-table" style="margin-bottom: 0px;">
                    <div class="Polaris-Modal__BodyWrapper">
                        <div class="Polaris-Modal__Body Polaris-Scrollable Polaris-Scrollable--vertical" data-polaris-scrollable="true">
                            <div class="Polaris-Card__Section Polaris-Page-Header--separator">
                                <div class="Polaris-FormLayout">
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="Polaris-Filters">
                                            <div class="Polaris-Filters-ConnectedFilterControl__Wrapper">
                                                <div class="Polaris-Filters-ConnectedFilterControl">
                                                    <div class="Polaris-Filters-ConnectedFilterControl__CenterContainer">
                                                        <div class="Polaris-Filters-ConnectedFilterControl__Item">
                                                            <div class="Polaris-Labelled--hidden">
                                                                <div class="Polaris-TextField">
                                                                    <div class="Polaris-TextField__Prefix" id="TextField1Prefix">
                                                                <span class="Polaris-Filters__SearchIcon">
                                                                    <span class="Polaris-Icon">
                                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8m9.707 4.293l-4.82-4.82A5.968 5.968 0 0 0 14 8 6 6 0 0 0 2 8a6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414" fill-rule="evenodd"></path></svg>
                                                                    </span>
                                                                </span>
                                                                    </div>
                                                                    <input id="customer_group_filter" placeholder="Search" class="Polaris-TextField__Input Polaris-TextField__Input--hasClearButton" value="" v-model="search"
                                                                           aria-labelledby="TextField1Label TextField1Prefix" aria-invalid="false" @keyup="getResourceData(resource, resourceList)">
                                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-groups-div" style="height:300px;overflow-y: scroll;" v-on:scroll="addResourceData()">
                                <ul id="resource_group_list" v-if="resourceData.length > 0" v-for="(resource, index) in resourceData">
                                    <li style="display:none;"></li>
                                    <li class="table-row">
                                        <label class="Polaris-Choice" style="align-items: center;">
                                        <span class="Polaris-Choice__Control">
                                            <span class="Polaris-Checkbox">
                                                <input :id="`checkbox`+index" @change="resource.is_checked = !resource.is_checked, (resource.is_checked) ? selectedResource = selectedResource + 1 : selectedResource = selectedResource - 1" type="checkbox" class="Polaris-Checkbox__Input groups_value checkbox-check" aria-invalid="false" role="checkbox" aria-checked="false" name="" value="990605967498^New" data-id="gid://shopify/SavedSearch/990605967498">
                                                <input type="hidden" class="Polaris-Checkbox__Input groups_name" aria-invalid="false" name="" value="New">
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

                                            <span class="mr-10 Polaris-Avatar Polaris-Avatar--styleSix Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage border-radius-0 script-list-image">
                                                        <img :src=resource.image alt="" role="presentation" class="Polaris-Avatar__Image border-radius-3 script-list-image"></span>

                                            <span class="Polaris-Choice__Label">{{resource.title}}</span></label>
                                    </li>
                                </ul>
                                <ul v-else><li>NO {{resource}} found.</li></ul>
                            </div>
                        </div>
                    </div>

                    <div class="Polaris-Modal-Footer">
                        <div class="Polaris-Modal-Footer__FooterContent">
                            <div class="Polaris-Stack Polaris-Stack--alignmentCenter">
                                <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                                    {{selectedResource}} {{resource, selectedResource | format}} selected
                                </div>
                                <div class="Polaris-Stack__Item">
                                    <div class="Polaris-ButtonGroup">
                                        <div class="Polaris-ButtonGroup__Item"><button type="button" class="Polaris-Button" id="close_group_popup" @click="closeModel()"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Cancel</span></span></button></div>
                                        <div class="Polaris-ButtonGroup__Item"><button v-bind:class="{'Polaris-Button--disabled' : ( selectedResource <= 0  )}" type="button" class="Polaris-Button Polaris-Button--primary" id="group_form" @click="addDataInList()"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Add</span></span></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</template>
<script>
    var modal = "";
    export default {
        name: 'ResourceModel',
        props: ['showModel'],
        data(){
            return{
                resourceData: [],
                selectedResource: 0,
                resource: '',
                resourceList: [],
                search: '',
                next_page: '',
                prev_page: '',
            }
        },
        methods:{
            addResourceData(){
                    var el = document.getElementsByClassName('list-groups-div');
                    if(el[0].scrollTop + el[0].offsetHeight >= el[0].scrollHeight){
                        this.getResourceData(this.resource, this.resourceList)
                    }
            },

            closeModel() {
                this.$emit('update', false, this.resourceList, this.resource);
                this.resourceData = [];
                this.next_page = '';
                this.selectedResource = 0;
            },
            async getResourceData(resource, resourceList){
                this.resource = resource;
                this.resourceList = resourceList;
                // this.resourceData = [];
                this.is_load = true;
                let base = this;

                let endPoint = '/get-resource?rt=' + base.resource + '&s=' + base.search + '&page=' + base.next_page;
                await axios.get(endPoint)
                    .then(res => {
                        var data = res.data.data;

                        if( data.resource.length > 0 ){
                            data.resource.forEach(function (value, i) {
                                base.resourceData.push(value);
                            });
                            // base.resourceData = base.resourceData + data.resource;
                            base.next_page = data.next;
                            base.prev_page = data.prev;

                            if( base.next_page ){
                                base.addResourceData();
                            }
                        }else{
                            base.noData = true;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(res => {
                    });
            },
            addDataInList(){
                let base = this;
                base.resourceData.forEach(function (element, index){
                    if( element.is_checked ){
                        let result = _.find(base.resourceList, function (o) {
                            return o.resource_id === element.resource_id;
                        });
                        if (typeof result == "undefined") {
                            base.resourceList.push(element);
                        }
                    }
                });
                base.closeModel();
            }
        },
        watch: {
            showModel(newVal, oldVal) {
                if (newVal === true) {
                    modal.style.display = "block";
                } else {
                    modal.style.display = "none";
                }
            },
        },
        mounted() {
            modal = document.getElementById("script-tag-resource-picker");
        },
        filters:{
            format(str, count){
                return ( count === 1 ) ? str.substring(0, str.length - 1) : str;
            }
        }
    }
</script>
