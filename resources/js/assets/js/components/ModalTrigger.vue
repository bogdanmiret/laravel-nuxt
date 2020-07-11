<template>
    <div :class="holder_classes">
        <div @click="$modal.show(modal_name)" class="f-pl-0">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            holder_classes: String,
            modal_name: {
                type: String,
                required: true
            }
        }
    }
</script>