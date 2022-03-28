<template>
    <div class="d-flex mt-10 justify-content-center">
        <nav class="Polaris-Pagination" aria-label="Pagination">
            <button :disabled="!prev_page" v-bind:class="{ 'Polaris-Button--disabled': !prev_page }" type="button"
                    class="Polaris-Pagination__Button Polaris-Pagination__PreviousButton" aria-label="Previous"
                    v-on:click="paginateData(prev_page, 'before')"><span
                class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                    <path
                        d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2"
                        fill-rule="evenodd"></path>
                    </svg></span></button>
            <button :disabled="!next_page" v-bind:class="{ 'Polaris-Button--disabled': !next_page }" type="button"
                    class="Polaris-Pagination__Button Polaris-Pagination__NextButton"
                    aria-label="Next" v-on:click="paginateData(next_page, 'after')"><span class="Polaris-Icon"><svg
                viewBox="0 0 20 20"
                class="Polaris-Icon__Svg"
                focusable="false" aria-hidden="true">
                    <path d="M17.707 9.293l-5-5a.999.999 0 1 0-1.414 1.414L14.586 9H3a1 1 0 1 0 0 2h11.586l-3.293 3.293a.999.999 0 1 0 1.414 1.414l5-5a.999.999 0 0 0 0-1.414" fill-rule="evenodd"></path>
                    </svg></span></button>
        </nav>
    </div>
</template>

<script>

    export default {
        name: 'CustomPaginate',
        props: ['prev_page', 'next_page', 'resourceType'],
        methods: {
            paginateData(cursor, page) {
                this.$root.$emit('get-data',this.resourceType, cursor, page);
            },
        }
    }
</script>

