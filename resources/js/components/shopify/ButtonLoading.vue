<template>
    <div v-if="showButton" style="--top-bar-background:#00848e; --top-bar-color:#f9fafb; --top-bar-background-lighter:#1d9ba4;"><button type="button" class="Polaris-Button Polaris-Button--primary Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><img src="data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMjAgMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTcuMjI5IDEuMTczYTkuMjUgOS4yNSAwIDEgMCAxMS42NTUgMTEuNDEyIDEuMjUgMS4yNSAwIDEgMC0yLjQtLjY5OCA2Ljc1IDYuNzUgMCAxIDEtOC41MDYtOC4zMjkgMS4yNSAxLjI1IDAgMSAwLS43NS0yLjM4NXoiIGZpbGw9IiM5MTlFQUIiLz48L3N2Zz4K" alt="" class="Polaris-Spinner Polaris-Spinner--colorWhite Polaris-Spinner--sizeSmall" draggable="false" role="status" aria-label="Loading"></span><span class="Polaris-Button__Text">Save product</span></span></button></div>
</template>

<script>
    export default {
        props:['showButton'],
        data(){
            return{

            }
        }
    }
</script>
