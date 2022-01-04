<template>
    <div class="modal is-clipped" :class="{'is-active':is_active}">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title"><slot name="header"></slot></p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <!-- Content ... -->
                <slot></slot>
            </section>
            <footer class="modal-card-foot">
                <slot name="footer"></slot>
                <button class="button"  @click="closeModal">Close</button>
            </footer>
        </div>
    </div>
</template>

<script>
export default {
    name: "modal",
    props: ['is_active'],
    methods: {
        closeModal(){
            this.$emit('closeModal')
        }
    }
}
</script>

<style scoped>

</style>
