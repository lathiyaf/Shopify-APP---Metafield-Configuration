<template>
    <div class="Polaris-Page__Content">
        <nav role="navigation" @click="hideSaveBar()">
            <label class="back-dashboard">
                <router-link data-polaris-unstyled="true" class="back-arrow" style="cursor: pointer;"
                             :to="{name :name, params: { base_id: base_id, resourceType : resourceType  }}" tag="span">
                    <span class="_2-hnq">
                        <svg viewBox="0 0 20 20" class="v3ASA" focusable="false" aria-hidden="true">
                            <path
                                d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16"
                                fill-rule="evenodd">
                            </path>
                        </svg>
                    </span>
                </router-link>
                <router-link data-polaris-unstyled="true" class="back-text" style="cursor: pointer;"
                             :to="{name :name, params: { resourceType : resourceType  }}" tag="span"> {{label}}
                </router-link>
            </label>
        </nav>
    </div>
</template>

<script>
    import {ContextualSaveBar} from "@shopify/app-bridge/actions";
    import helper from "../../helper";

    export default {
        props: ['name', 'label', 'resourceType', 'base_id'],
        name: 'BackArrow',
        methods: {
            hideSaveBar() {
                let options = {
                    saveAction: {
                        disabled: false,
                        loading: false,
                    },
                    discardAction: {
                        disabled: false,
                        loading: false,
                        discardConfirmationModal: true,
                    },
                };
                var contextualSaveBar = helper.contextualSaveBar(options);
                contextualSaveBar.dispatch(ContextualSaveBar.Action.HIDE);
            },
        },
        created() {
            console.log(this.base_id);
        }
    }
</script>
<style lang="scss">
    nav .back-dashboard {
        font-size: 1.5rem;
        font-weight: 400;
        line-height: 2rem;
        text-transform: none;
        letter-spacing: normal;
        color: var(--p-text-subdued-on-surface, #637381);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
        padding: 0;
        padding: 0.9rem 0.8rem 0rem 0.8rem;
        background: none;
        border: none;
        font-size: inherit;
        line-height: inherit;
        position: relative;
        display: flex;
        align-items: center;
        min-height: 3.6rem;
        margin: -.4rem -.8rem -.4rem -.4rem;
        /*padding: .8rem .8rem;*/
        color: #637381;
        text-decoration: none;

        .back-arrow {
            height: 2rem;
            width: 2rem;
            margin: -1rem 0 -1rem -.8rem;
            color: #637381;
        }

        .back-text {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            position: relative;
            z-index: 20;
        }
    }
</style>
